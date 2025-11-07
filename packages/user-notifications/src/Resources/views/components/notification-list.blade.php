@props([
    'notifications' => collect(),
])

<div class="divide-y divide-gray-200">
    @forelse($notifications as $notification)
        <a href="{{ route('notifications.show', $notification) }}" class="flex items-start px-4 py-3 hover:bg-gray-50 {{ $notification->is_read ? '' : 'bg-blue-50' }}">
            <div class="flex-1">
                <p class="text-sm font-semibold text-gray-800">{{ $notification->title }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ \Illuminate\Support\Str::limit($notification->message, 80) }}</p>
                <p class="text-xs text-gray-400 mt-2">{{ optional($notification->created_at)->diffForHumans() }}</p>
            </div>
            @unless($notification->is_read)
                <span class="ml-3 inline-flex items-center justify-center w-2 h-2 bg-indigo-600 rounded-full"></span>
            @endunless
        </a>
    @empty
        <div class="px-4 py-6 text-center text-sm text-gray-500">
            {{ __('پیامی برای نمایش وجود ندارد.') }}
        </div>
    @endforelse
</div>
