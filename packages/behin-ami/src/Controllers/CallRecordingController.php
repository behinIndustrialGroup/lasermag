<?php

namespace Behin\Ami\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class CallRecordingController
{
    public function download(Request $request)
    {
        $token = $request->query('token');
        if (!$token) {
            abort(404);
        }

        $decoded = json_decode(base64_decode($token, true) ?: '', true);
        if (!is_array($decoded)) {
            abort(404);
        }

        $disk = $decoded['disk'] ?? null;
        $path = $decoded['path'] ?? null;
        $remotePath = $decoded['remote_path'] ?? null;

        if (!$path && !$remotePath) {
            abort(404);
        }

        $defaultName = $path ? basename($path) : ($remotePath ? basename($remotePath) : 'recording.wav');
        $fileName = $request->query('name') ?: $defaultName;
        if (str_contains($fileName, '?')) {
            $fileName = strstr($fileName, '?', true) ?: $fileName;
        }
        if ($fileName === '') {
            $fileName = 'recording.wav';
        }
        $inline = $request->boolean('inline');

        if ($disk && $path) {
            $allowedDisk = config('behin-ami.recordings.disk');
            if (!$allowedDisk || $disk !== $allowedDisk) {
                abort(404);
            }

            if (!Storage::disk($disk)->exists($path)) {
                abort(404);
            }

            return $this->streamLocalDisk($disk, $path, $fileName, $inline);
        }

        if ($path) {
            $basePath = config('behin-ami.recordings.base_path');
            if (!$basePath) {
                abort(404);
            }

            $baseReal = realpath($basePath);
            $fileReal = realpath($path);

            if (!$baseReal || !$fileReal || !str_starts_with($fileReal, $baseReal) || !is_file($fileReal)) {
                abort(404);
            }

            return $this->streamLocalFile($fileReal, $fileName, $inline);
        }

        if ($remotePath) {
            $downloadConfig = (array) config('behin-ami.recordings.download', []);
            if (!empty($downloadConfig['url'])) {
                return $this->streamFromDownloadScript($remotePath, $fileName, $inline, $downloadConfig);
            }

            return $this->streamFromIssabel($remotePath, $fileName, $inline);
        }

        abort(404);
    }

    protected function streamLocalDisk(string $disk, string $path, string $fileName, bool $inline): Response
    {
        if ($inline) {
            $stream = Storage::disk($disk)->readStream($path);
            if (!$stream) {
                abort(404);
            }

            return $this->streamResponse(function () use ($stream) {
                while (!feof($stream)) {
                    echo fread($stream, 8192);
                }
                fclose($stream);
            }, $fileName, $inline, Storage::disk($disk)->mimeType($path) ?: null, $this->resolveStorageSize($disk, $path));
        }

        return Storage::disk($disk)->download($path, $fileName);
    }

    protected function streamLocalFile(string $filePath, string $fileName, bool $inline): Response
    {
        if (!$inline) {
            return response()->download($filePath, $fileName);
        }

        $mimeType = mime_content_type($filePath) ?: null;
        $fileSize = is_file($filePath) ? filesize($filePath) ?: null : null;

        return $this->streamResponse(function () use ($filePath) {
            $handle = fopen($filePath, 'rb');
            if ($handle === false) {
                abort(404);
            }

            try {
                while (!feof($handle)) {
                    echo fread($handle, 8192);
                }
            } finally {
                fclose($handle);
            }
        }, $fileName, true, $mimeType, $fileSize);
    }

    /**
     * @param array<string, mixed> $config
     */
    protected function streamFromDownloadScript(string $remotePath, string $fileName, bool $inline, array $config): StreamedResponse
    {
        $url = (string) ($config['url'] ?? '');
        if ($url === '') {
            abort(404);
        }

        $username = $config['username'] ?? null;
        $password = $config['password'] ?? null;
        $verifySsl = array_key_exists('verify_ssl', $config) ? (bool) $config['verify_ssl'] : true;
        $timeout = (int) ($config['timeout'] ?? 15);

        $clientOptions = [
            'timeout' => max($timeout, 1),
            'http_errors' => false,
            'verify' => $verifySsl,
        ];

        if ($username !== null && $password !== null) {
            $clientOptions['auth'] = [$username, $password];
        }

        $client = new Client($clientOptions);

        try {
            $response = $client->get($url, [
                'query' => ['file' => $remotePath],
                'stream' => true,
            ]);
        } catch (GuzzleException $exception) {
            Log::warning('AMI call history: Issabel download script request failed', [
                'exception' => $exception->getMessage(),
                'path' => $remotePath,
            ]);

            abort(404);
        }

        if ($response->getStatusCode() !== 200) {
            Log::warning('AMI call history: Issabel download script returned unexpected status', [
                'status' => $response->getStatusCode(),
                'path' => $remotePath,
            ]);

            abort(404);
        }

        $body = $response->getBody();
        $mimeType = $response->getHeaderLine('Content-Type') ?: 'audio/wav';
        $contentLength = $response->getHeaderLine('Content-Length') ?: null;

        return $this->streamResponse(function () use ($body) {
            while (!$body->eof()) {
                echo $body->read(8192);
            }
        }, $fileName, $inline, $mimeType, $contentLength ? (int) $contentLength : null);
    }

    protected function streamFromIssabel(string $remotePath, string $fileName, bool $inline): StreamedResponse
    {
        $config = (array) config('behin-ami.recordings.issabel', []);
        $baseUrl = rtrim((string) ($config['base_url'] ?? ''), '/');
        $username = $config['username'] ?? null;
        $password = $config['password'] ?? null;

        if ($baseUrl === '' || $username === null || $password === null) {
            abort(404);
        }

        $loginPath = ltrim((string) ($config['login_path'] ?? '/index.php?module=auth&action=login'), '/');
        $usernameField = (string) ($config['username_field'] ?? 'input_user');
        $passwordField = (string) ($config['password_field'] ?? 'input_pass');
        $extraFields = (array) ($config['extra_fields'] ?? []);
        $verifySsl = (bool) ($config['verify_ssl'] ?? true);
        $timeout = (int) ($config['timeout'] ?? 15);

        $client = new Client([
            'base_uri' => $baseUrl . '/',
            'timeout' => max($timeout, 1),
            'cookies' => true,
            'http_errors' => false,
            'verify' => $verifySsl,
        ]);

        try {
            $loginResponse = $client->post($loginPath, [
                'form_params' => array_merge($extraFields, [
                    $usernameField => $username,
                    $passwordField => $password,
                ]),
                'allow_redirects' => true,
            ]);
        } catch (GuzzleException $exception) {
            Log::warning('AMI call history: Issabel login failed', [
                'exception' => $exception->getMessage(),
            ]);
            abort(404);
        }

        if ($loginResponse->getStatusCode() >= 400) {
            Log::warning('AMI call history: Issabel login returned unexpected status', [
                'status' => $loginResponse->getStatusCode(),
            ]);
            abort(404);
        }

        try {
            $response = $client->get(ltrim($remotePath, '/'), [
                'stream' => true,
            ]);
        } catch (GuzzleException $exception) {
            Log::warning('AMI call history: Issabel recording fetch failed', [
                'exception' => $exception->getMessage(),
                'path' => $remotePath,
            ]);
            abort(404);
        }

        if ($response->getStatusCode() !== 200) {
            Log::warning('AMI call history: Issabel recording fetch returned unexpected status', [
                'status' => $response->getStatusCode(),
                'path' => $remotePath,
            ]);
            abort(404);
        }

        $body = $response->getBody();
        $mimeType = $response->getHeaderLine('Content-Type') ?: null;
        $contentLength = $response->getHeaderLine('Content-Length') ?: null;

        return $this->streamResponse(function () use ($body) {
            while (!$body->eof()) {
                echo $body->read(8192);
            }
        }, $fileName, $inline, $mimeType, $contentLength ? (int) $contentLength : null);
    }

    /**
     * @param callable():void $callback
     */
    protected function streamResponse(callable $callback, string $fileName, bool $inline, ?string $mimeType, ?int $contentLength): StreamedResponse
    {
        $headers = [];

        $disposition = HeaderUtils::makeDisposition(
            $inline ? HeaderUtils::DISPOSITION_INLINE : HeaderUtils::DISPOSITION_ATTACHMENT,
            $fileName
        );

        $headers['Content-Disposition'] = $disposition;

        if ($mimeType) {
            $headers['Content-Type'] = $mimeType;
        }

        if ($contentLength) {
            $headers['Content-Length'] = (string) $contentLength;
        }

        return response()->stream($callback, 200, $headers);
    }

    protected function resolveStorageSize(string $disk, string $path): ?int
    {
        try {
            return Storage::disk($disk)->size($path) ?: null;
        } catch (Throwable $exception) {
            Log::debug('AMI call history: unable to determine storage size', [
                'disk' => $disk,
                'path' => $path,
                'exception' => $exception->getMessage(),
            ]);

            return null;
        }
    }
}
