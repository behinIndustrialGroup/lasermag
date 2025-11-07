<?php

namespace UserNotifications\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNotificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
            'recipient_type' => ['required', 'in:user,users,roles,all'],
            'user_id' => ['nullable', 'exists:users,id'],
            'user_ids' => ['nullable', 'array'],
            'user_ids.*' => ['required', 'exists:users,id'],
            'role_ids' => ['nullable', 'array'],
            'role_ids.*' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'recipient_type.in' => __('نوع دریافت‌کننده انتخاب شده معتبر نیست.'),
        ];
    }
}
