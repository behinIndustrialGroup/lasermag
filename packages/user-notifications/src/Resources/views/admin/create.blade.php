@extends('behin-layouts.app')

@section('style')
    <style>
        .notification-form-wrapper {
            max-width: 960px;
            margin: 0 auto;
        }

        .notification-form-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 22px 45px rgba(15, 76, 129, 0.18);
            overflow: hidden;
        }

        .notification-form-header {
            background: linear-gradient(135deg, #0f4c81 0%, #1d8cf8 100%);
            padding: 32px 36px;
            color: #fff;
        }

        .notification-form-header h1 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .notification-form-header p {
            margin: 0;
            opacity: .85;
        }

        .notification-form-body {
            padding: 32px 36px 40px;
            background: #fff;
        }

        .form-floating-label {
            font-weight: 600;
            color: #1f3a60;
            margin-bottom: 10px;
        }

        .custom-input {
            border-radius: 14px !important;
            border: 1px solid rgba(31, 58, 96, 0.12);
            padding: 12px 16px;
            box-shadow: none;
            transition: border-color .2s ease, box-shadow .2s ease;
        }

        .custom-input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.2);
        }

        .custom-radio {
            border-radius: 12px;
            border: 1px solid rgba(31, 58, 96, 0.08);
            padding: 14px 16px;
            background: #f8fbff;
            transition: all .2s ease;
        }

        .custom-radio:hover {
            border-color: #2563eb;
            box-shadow: 0 12px 24px rgba(37, 99, 235, 0.15);
        }

        .options-scroll {
            max-height: 240px;
            overflow-y: auto;
            border-radius: 14px;
            border: 1px solid rgba(31, 58, 96, 0.1);
            padding: 16px;
            background: #fdfefe;
        }

        .options-scroll label {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 12px;
            font-weight: 500;
        }

        .btn-primary-gradient {
            background: linear-gradient(135deg, #2563eb 0%, #1d8cf8 100%);
            border: none;
            color: #fff;
            border-radius: 14px;
            padding: 12px 32px;
            font-weight: 600;
            box-shadow: 0 18px 30px rgba(37, 99, 235, 0.25);
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .btn-primary-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 34px rgba(37, 99, 235, 0.3);
        }

        .form-error-text {
            font-size: .85rem;
            color: #dc3545;
            margin-top: 6px;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="notification-form-wrapper">
            <div class="notification-form-card">
                <div class="notification-form-header">
                    <h1>{{ __('ارسال پیام جدید') }}</h1>
                    <p>{{ __('پیام خود را با رنگ و حس متریال برای کاربران ارسال کنید') }}</p>
                </div>
                <div class="notification-form-body">
                    <form action="{{ route('admin.notifications.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="form-floating-label">{{ __('عنوان پیام') }}</label>
                            <input type="text" name="title" value="{{ old('title') }}" class="form-control custom-input" required>
                            @error('title')
                                <p class="form-error-text">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-floating-label">{{ __('متن پیام') }}</label>
                            <textarea name="message" rows="6" class="form-control custom-input" required>{{ old('message') }}</textarea>
                            @error('message')
                                <p class="form-error-text">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-floating-label mb-3">{{ __('ارسال برای') }}</label>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="custom-radio w-100">
                                        <input type="radio" class="form-check-input me-2" name="recipient_type" value="user" {{ old('recipient_type') === 'user' ? 'checked' : '' }}>
                                        <span>{{ __('یک کاربر خاص') }}</span>
                                    </label>
                                </div>
                                <div class="col-md-6">
                                    <label class="custom-radio w-100">
                                        <input type="radio" class="form-check-input me-2" name="recipient_type" value="users" {{ old('recipient_type') === 'users' ? 'checked' : '' }}>
                                        <span>{{ __('چند کاربر خاص') }}</span>
                                    </label>
                                </div>
                                <div class="col-md-6">
                                    <label class="custom-radio w-100">
                                        <input type="radio" class="form-check-input me-2" name="recipient_type" value="roles" {{ old('recipient_type') === 'roles' ? 'checked' : '' }}>
                                        <span>{{ __('یک یا چند نقش') }}</span>
                                    </label>
                                </div>
                                <div class="col-md-6">
                                    <label class="custom-radio w-100">
                                        <input type="radio" class="form-check-input me-2" name="recipient_type" value="all" {{ old('recipient_type', 'all') === 'all' ? 'checked' : '' }}>
                                        <span>{{ __('همه کاربران') }}</span>
                                    </label>
                                </div>
                            </div>
                            @error('recipient_type')
                                <p class="form-error-text">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="user-select" class="mb-4 d-none">
                            <label class="form-floating-label">{{ __('انتخاب کاربر') }}</label>
                            <select name="user_id" class="form-select custom-input">
                                <option value="">{{ __('انتخاب کنید') }}</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div id="users-select" class="mb-4 d-none">
                            <label class="form-floating-label">{{ __('انتخاب چند کاربر') }}</label>
                            <div class="options-scroll">
                                @foreach($users as $user)
                                    <label>
                                        <input type="checkbox" class="form-check-input me-2" name="user_ids[]" value="{{ $user->id }}" {{ in_array($user->id, old('user_ids', [])) ? 'checked' : '' }}>
                                        <span>{{ $user->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div id="roles-select" class="mb-4 d-none">
                            <label class="form-floating-label">{{ __('انتخاب نقش‌ها') }}</label>
                            <div class="options-scroll">
                                @forelse($roles as $role)
                                    <label>
                                        <input type="checkbox" class="form-check-input me-2" name="role_ids[]" value="{{ $role->id }}" {{ in_array($role->id, old('role_ids', [])) ? 'checked' : '' }}>
                                        <span>{{ $role->name }}</span>
                                    </label>
                                @empty
                                    <p class="text-muted mb-0">{{ __('هیچ نقشی یافت نشد.') }}</p>
                                @endforelse
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary-gradient">
                                <i class="fa fa-send ms-2"></i>
                                {{ __('ارسال پیام') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const typeInputs = document.querySelectorAll('input[name="recipient_type"]');
            const userSelect = document.getElementById('user-select');
            const usersSelect = document.getElementById('users-select');
            const rolesSelect = document.getElementById('roles-select');

            const toggle = () => {
                const value = document.querySelector('input[name="recipient_type"]:checked')?.value;
                userSelect.classList.toggle('d-none', value !== 'user');
                usersSelect.classList.toggle('d-none', value !== 'users');
                rolesSelect.classList.toggle('d-none', value !== 'roles');
            };

            typeInputs.forEach(input => input.addEventListener('change', toggle));
            toggle();
        });
    </script>
@endsection
