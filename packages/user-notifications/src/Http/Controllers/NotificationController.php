<?php

namespace UserNotifications\Http\Controllers;

use App\Http\Controllers\Controller;
use BehinUserRoles\Models\Role;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use UserNotifications\Events\NotificationSent;
use UserNotifications\Http\Requests\StoreNotificationRequest;
use UserNotifications\Models\Notification;

class NotificationController extends Controller
{
    protected string $userModel;

    public function __construct()
    {
        $this->userModel = config('user-notifications.user_model', config('auth.providers.users.model'));
    }

    public function index(Request $request): View
    {
        $user = $request->user();

        $notifications = Notification::query()
            ->where('receiver_id', $user?->getKey())
            ->latest()
            ->paginate(15);

        $unreadCount = Notification::unreadFor($user?->getKey())->count();

        return view('user-notifications::user.index', compact('notifications', 'unreadCount'));
    }

    public function show(Request $request, Notification $notification): View
    {
        $this->authorizeFor($request, $notification);
        $notification->markAsRead();

        return view('user-notifications::user.show', compact('notification'));
    }

    public function markAsRead(Request $request, Notification $notification): RedirectResponse
    {
        $this->authorizeFor($request, $notification);
        $notification->markAsRead();

        return Redirect::back()->with('status', __('پیام خوانده شد.'));
    }

    public function adminIndex(): View
    {
        $notifications = Notification::query()->latest()->paginate(20);

        return view('user-notifications::admin.index', compact('notifications'));
    }

    public function create(): View
    {
        $userModel = $this->userModel;
        /** @var EloquentCollection<int, \Illuminate\Database\Eloquent\Model> $users */
        $users = $userModel::query()->orderBy('name')->get(['id', 'name']);
        $roles = class_exists(Role::class)
            ? Role::query()->orderBy('name')->get(['id', 'name'])
            : collect();

        return view('user-notifications::admin.create', compact('users', 'roles'));
    }

    public function store(StoreNotificationRequest $request): RedirectResponse
    {
        $sender = $request->user();
        $recipientType = $request->input('recipient_type');
        $userModel = $this->userModel;

        $targets = match ($recipientType) {
            'user' => $userModel::query()->whereKey($request->input('user_id'))->get(),
            'users' => $userModel::query()->whereKey($request->input('user_ids', []))->get(),
            'roles' => $userModel::query()->whereIn('role_id', $request->input('role_ids', []))->get(),
            'all' => $userModel::query()->get(),
            default => collect(),
        };

        $targets = $targets->unique(fn ($user) => $user->getKey());

        if ($targets->isEmpty()) {
            return Redirect::back()
                ->withInput()
                ->withErrors(['recipient_type' => __('هیچ کاربری برای ارسال پیام انتخاب نشد.')]);
        }

        $payload = $request->only(['title', 'message']);

        $targets->each(function ($user) use ($payload, $sender, $recipientType, $request) {
            $notification = Notification::create([
                ...$payload,
                'sender_id' => $sender?->getKey(),
                'receiver_id' => $user->getKey(),
                'receiver_role' => $this->resolveReceiverRole($recipientType, $user, $request->input('role_ids', [])),
            ]);

            NotificationSent::dispatch($notification);
        });

        return Redirect::route('admin.notifications.index')->with('status', __('پیام با موفقیت ارسال شد.'));
    }

    protected function authorizeFor(Request $request, Notification $notification): void
    {
        if ($notification->receiver_id !== $request->user()?->getKey()) {
            abort(403);
        }
    }

    protected function resolveReceiverRole(string $recipientType, $user, array $roleIds): ?string
    {
        if ($recipientType === 'roles') {
            if (!empty($roleIds)) {
                return implode(',', $roleIds);
            }

            return (string) ($user->role_id ?? '');
        }

        if ($recipientType === 'all') {
            return 'all';
        }

        return null;
    }
}
