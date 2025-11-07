@props([
    'count' => 0,
    'url' => \Illuminate\Support\Facades\Route::has('notifications.index') ? route('notifications.index') : '#',
])

<a href="{{ $url }}" class="relative inline-flex items-center justify-center p-2 bg-indigo-50 text-indigo-600 rounded-full hover:bg-indigo-100">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a3 3 0 11-5.714 0" />
    </svg>
    @if($count > 0)
        <span class="absolute -top-1 -right-1 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
            {{ $count }}
        </span>
    @endif
</a>
