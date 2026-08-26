<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        // Accept "@satpanha", "satpanha" or " https://t.me/satpanha " —
        // keep only the bare username characters, never a full URL.
        $username = $this->input('telegram_username');

        if (is_string($username)) {
            $username = trim($username);
            $username = preg_replace('#^https?://(t\.me|telegram\.me)/#i', '', $username);
            $username = ltrim($username, '@');
            $username = $username === '' ? null : $username;
        } else {
            $username = null;
        }

        $this->merge(['telegram_username' => $username]);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:150', Rule::unique('users')->ignore($this->user()->id)],
            'phone' => ['required', 'string', 'min:8', 'max:30', 'regex:/^[0-9+\-\s()]+$/'],
            'profile_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],

            // Official Telegram username shape: 5-32 chars, starts with a letter,
            // then letters/digits/underscores. Blocks arbitrary URLs and symbols.
            'telegram_username' => [
                'nullable',
                'string',
                'max:32',
                'regex:/^[A-Za-z][A-Za-z0-9_]{4,31}$/',
                Rule::unique('users', 'telegram_username')->ignore($this->user()->id),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'telegram_username.regex' => 'Telegram username must be 5-32 characters: letters, numbers or underscores, starting with a letter (example: @satpanha).',
            'telegram_username.unique' => 'This Telegram username is already used by another account.',
        ];
    }
}
