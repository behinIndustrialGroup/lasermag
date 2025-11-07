<?php

namespace Behin\Ami\Services;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class CallHistoryService
{
    protected ?string $lastError = null;

    /** @var array<int, string> */
    protected array $lastSearchNumbers = [];

    /**
     * دریافت تاریخچه مکالمات برای شماره مشخص شده.
     */
    public function getCallsByPhone(?string $phone, ?int $limit = null): Collection
    {
        $this->lastError = null;
        $this->lastSearchNumbers = [];

        $phone = $this->sanitizePhone($phone);
        if ($phone === null) {
            return collect();
        }

        $numbers = $this->resolvePossibleNumbers($phone);
        if (empty($numbers)) {
            return collect();
        }

        $this->lastSearchNumbers = $numbers;

        $connection = config('behin-ami.cdr_connection') ?: config('database.default');
        $table = config('behin-ami.cdr_table', 'cdr');

        if (!$table) {
            $this->setError('جدول مکالمات برای دریافت اطلاعات تعریف نشده است.');
            return collect();
        }

        try {
            if (!Schema::connection($connection)->hasTable($table)) {
                $this->setError('جدول مکالمات در پایگاه داده پیدا نشد.');
                return collect();
            }
        } catch (\Throwable $exception) {
            $this->setError('امکان دسترسی به جدول مکالمات وجود ندارد.', $exception);
            return collect();
        }

        $columnsConfig = (array) config('behin-ami.columns', []);

        $dateColumn = $this->resolveColumn($connection, $table, $columnsConfig['date'] ?? 'calldate');
        if (!$dateColumn) {
            $this->setError('ستون تاریخ مکالمه در جدول وجود ندارد.');
            return collect();
        }

        $sourceColumn = $this->resolveColumn($connection, $table, $columnsConfig['source'] ?? 'src');
        $destinationColumn = $this->resolveColumn($connection, $table, $columnsConfig['destination'] ?? 'dst');

        if (!$sourceColumn && !$destinationColumn) {
            $this->setError('ستون‌های مرتبط با شماره تماس در جدول مکالمات یافت نشد.');
            return collect();
        }

        $durationColumn = $this->resolveColumn($connection, $table, $columnsConfig['duration'] ?? 'billsec');
        $fallbackDurationColumn = $this->resolveColumn($connection, $table, $columnsConfig['duration_fallback'] ?? 'duration');
        $statusColumn = $this->resolveColumn($connection, $table, $columnsConfig['status'] ?? 'disposition');
        $recordingColumn = $this->resolveColumn($connection, $table, $columnsConfig['recording'] ?? 'recordingfile');

        $selectColumns = [
            $dateColumn . ' as started_at_raw',
        ];

        if ($sourceColumn) {
            $selectColumns[] = $sourceColumn . ' as source';
        }

        if ($destinationColumn) {
            $selectColumns[] = $destinationColumn . ' as destination';
        }

        if ($durationColumn) {
            $selectColumns[] = $durationColumn . ' as primary_duration';
        }

        if ($fallbackDurationColumn && $fallbackDurationColumn !== $durationColumn) {
            $selectColumns[] = $fallbackDurationColumn . ' as fallback_duration';
        }

        if ($statusColumn) {
            $selectColumns[] = $statusColumn . ' as status';
        }

        if ($recordingColumn) {
            $selectColumns[] = $recordingColumn . ' as recording';
        }

        $limit = $limit ?? (int) config('behin-ami.default_limit', 50);

        try {
            $query = DB::connection($connection)
                ->table($table)
                ->select($selectColumns)
                ->orderByDesc($dateColumn)
                ->where(function ($query) use ($numbers, $sourceColumn, $destinationColumn) {
                    foreach ($numbers as $number) {
                        if ($sourceColumn) {
                            $query->orWhere($sourceColumn, $number);
                        }
                        if ($destinationColumn) {
                            $query->orWhere($destinationColumn, $number);
                        }
                    }
                });

            if ($limit > 0) {
                $query->limit($limit);
            }

            $rows = $query->get();
        } catch (\Throwable $exception) {
            $this->setError('خطا در دریافت اطلاعات مکالمات از پایگاه داده.', $exception);
            return collect();
        }

        $normalizedNumbers = array_map([$this, 'normalizeForComparison'], $numbers);

        return $rows->map(function ($row) use ($normalizedNumbers) {
            $startedAt = null;
            if (!empty($row->started_at_raw)) {
                try {
                    $startedAt = Carbon::parse($row->started_at_raw)->setTimezone(config('app.timezone'));
                } catch (\Throwable $exception) {
                    Log::debug('AMI call history: unable to parse call date', [
                        'value' => $row->started_at_raw,
                        'exception' => $exception->getMessage(),
                    ]);
                }
            }

            $source = $row->source ?? null;
            $destination = $row->destination ?? null;

            $direction = 'unknown';
            $sourceNormalized = $this->normalizeForComparison($source);
            if ($source && in_array($sourceNormalized, $normalizedNumbers, true)) {
                $direction = 'inbound';
            }

            $destinationNormalized = $this->normalizeForComparison($destination);
            if ($direction === 'unknown' && $destination && in_array($destinationNormalized, $normalizedNumbers, true)) {
                $direction = 'outbound';
            }

            $counterparty = $direction === 'outbound'
                ? ($destination ?: $source)
                : ($direction === 'inbound' ? ($source ?: $destination) : ($destination ?: $source));

            $durationSeconds = (int) ($row->primary_duration ?? $row->fallback_duration ?? 0);
            if ($durationSeconds < 0) {
                $durationSeconds = 0;
            }

            $recording = $this->resolveRecording($row->recording ?? null);

            return [
                'started_at' => $startedAt,
                'source' => $source,
                'destination' => $destination,
                'direction' => $direction,
                'counterparty' => $counterparty,
                'duration_seconds' => $durationSeconds,
                'duration_human' => $this->formatDuration($durationSeconds),
                'status' => $row->status ?? null,
                'recording' => $recording,
            ];
        });
    }

    public function getLastError(): ?string
    {
        return $this->lastError;
    }

    /**
     * @return array<int, string>
     */
    public function getLastSearchNumbers(): array
    {
        return $this->lastSearchNumbers;
    }

    protected function setError(string $message, ?\Throwable $exception = null): void
    {
        $this->lastError = $message;

        if ($exception) {
            Log::warning('AMI call history error: ' . $message, [
                'exception' => $exception->getMessage(),
            ]);
        }
    }

    protected function sanitizePhone(?string $phone): ?string
    {
        if ($phone === null) {
            return null;
        }

        $trimmed = trim($phone);
        return $trimmed !== '' ? $trimmed : null;
    }

    /**
     * @return array<int, string>
     */
    protected function resolvePossibleNumbers(string $phone): array
    {
        $digits = preg_replace('/\D+/', '', $phone) ?? '';
        $last10 = substr($digits, -10) ?: $digits;

        $candidates = array_filter([
            $phone,
            $digits,
            $last10,
            $last10 ? '0' . $last10 : null,
            $last10 ? '98' . $last10 : null,
            $last10 ? '+98' . $last10 : null,
        ]);

        return array_values(array_unique(array_map('strval', $candidates)));
    }

    protected function normalizeForComparison(?string $value): string
    {
        if ($value === null) {
            return '';
        }

        $digits = preg_replace('/\D+/', '', $value) ?? '';
        return substr($digits, -10) ?: $digits;
    }

    protected function formatDuration(int $seconds): string
    {
        $minutes = intdiv($seconds, 60);
        $remaining = $seconds % 60;

        return sprintf('%02d:%02d', $minutes, $remaining);
    }

    protected function resolveRecording(?string $recording): ?array
    {
        if ($recording === null) {
            return null;
        }

        $recording = trim($recording);
        if ($recording === '') {
            return null;
        }

        $fileName = basename($recording);
        $result = [
            'name' => $fileName,
            'available' => false,
            'download_url' => null,
            'stream_url' => null,
            'token' => null,
        ];

        if (filter_var($recording, FILTER_VALIDATE_URL)) {
            $result['available'] = true;
            $result['download_url'] = $recording;
            $result['stream_url'] = $recording;
            return $result;
        }

        $recordingConfig = (array) config('behin-ami.recordings', []);

        $disk = $recordingConfig['disk'] ?? null;
        $prefix = trim($recordingConfig['prefix'] ?? '', '/');
        $storagePath = ltrim($recording, '/');
        if ($prefix !== '') {
            $storagePath = trim($prefix . '/' . $storagePath, '/');
        }

        if ($disk) {
            try {
                if (Storage::disk($disk)->exists($storagePath)) {
                    $token = $this->encodeDownloadToken([
                        'disk' => $disk,
                        'path' => $storagePath,
                    ]);

                    $result['available'] = true;
                    $result['download_url'] = route('ami.calls.recordings.download', ['token' => $token, 'name' => $fileName]);
                    $result['stream_url'] = Storage::disk($disk)->url($storagePath);
                    $result['token'] = $token;

                    return $result;
                }
            } catch (\Throwable $exception) {
                Log::debug('AMI call history: unable to access storage disk', [
                    'disk' => $disk,
                    'exception' => $exception->getMessage(),
                ]);
            }
        }

        $basePath = $recordingConfig['base_path'] ?? null;
        if ($basePath) {
            $fullPath = $this->resolveAbsolutePath($basePath, $recording);
            if ($fullPath && is_file($fullPath)) {
                $token = $this->encodeDownloadToken([
                    'path' => $fullPath,
                ]);

                $result['available'] = true;
                $result['download_url'] = route('ami.calls.recordings.download', ['token' => $token, 'name' => $fileName]);
                $result['stream_url'] = route('ami.calls.recordings.download', ['token' => $token, 'name' => $fileName, 'inline' => 1]);
                $result['token'] = $token;
            }
        }

        $downloadConfig = (array) ($recordingConfig['download'] ?? []);
        if (!empty($downloadConfig['url'])) {
            $token = $this->encodeDownloadToken([
                'remote_path' => ltrim($recording, '/'),
            ]);

            $result['available'] = true;
            $result['download_url'] = route('ami.calls.recordings.download', ['token' => $token, 'name' => $fileName]);
            $result['stream_url'] = route('ami.calls.recordings.download', ['token' => $token, 'name' => $fileName, 'inline' => 1]);
            $result['token'] = $token;
        }

        if (!$result['available']) {
            $baseUrl = $recordingConfig['base_url'] ?? null;
            if ($baseUrl) {
                $token = $this->encodeDownloadToken([
                    'remote_path' => ltrim($recording, '/'),
                ]);

                $result['available'] = true;
                $result['download_url'] = route('ami.calls.recordings.download', ['token' => $token, 'name' => $fileName]);
                $result['stream_url'] = route('ami.calls.recordings.download', ['token' => $token, 'name' => $fileName, 'inline' => 1]);
                $result['token'] = $token;
            }
        }

        if (!$result['available']) {
            $baseUrl = $recordingConfig['base_url'] ?? null;
            if ($baseUrl) {
                $url = rtrim($baseUrl, '/') . '/' . ltrim($recording, '/');
                $result['available'] = true;
                $result['download_url'] = $url;
                $result['stream_url'] = $url;
            }
        }

        return $result;
    }

    protected function encodeDownloadToken(array $payload): string
    {
        return base64_encode(json_encode($payload));
    }

    protected function resolveAbsolutePath(string $basePath, string $relative): ?string
    {
        $baseReal = realpath($basePath);
        if (!$baseReal) {
            return null;
        }

        $fullPath = $baseReal . DIRECTORY_SEPARATOR . ltrim($relative, DIRECTORY_SEPARATOR);
        $fullReal = realpath($fullPath);

        if (!$fullReal) {
            return null;
        }

        if (!str_starts_with($fullReal, $baseReal)) {
            return null;
        }

        return $fullReal;
    }

    protected function resolveColumn(string $connection, string $table, ?string $column): ?string
    {
        if (!$column) {
            return null;
        }

        try {
            if (Schema::connection($connection)->hasColumn($table, $column)) {
                return $column;
            }

            return null;
        } catch (\Throwable $exception) {
            return $this->resolveColumnWithFallback($connection, $table, $column, $exception);
        }
    }

    protected function resolveColumnWithFallback(string $connection, string $table, string $column, \Throwable $originalException): ?string
    {
        Log::debug('AMI call history: unable to verify column with schema builder, falling back to SHOW COLUMNS', [
            'table' => $table,
            'column' => $column,
            'exception' => $originalException->getMessage(),
        ]);

        try {
            $db = DB::connection($connection);
            $grammar = $db->getQueryGrammar();

            $like = "'" . str_replace("'", "''", $column) . "'";

            $sql = sprintf(
                'SHOW COLUMNS FROM %s LIKE %s',
                $grammar->wrapTable($table),
                $like
            );

            $result = $db->select($sql);

            return !empty($result) ? $column : null;
        } catch (\Throwable $exception) {
            Log::debug('AMI call history: fallback column detection failed', [
                'table' => $table,
                'column' => $column,
                'exception' => $exception->getMessage(),
            ]);

            return null;
        }
    }
}
