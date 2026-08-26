<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMotorcycleRequest extends StoreMotorcycleRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'main_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);
    }
}
