<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMotorcycleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isSeller() || $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'brand_id' => ['required', 'exists:brands,id'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:150'],
            'model' => ['required', 'string', 'max:100'],
            'year' => ['required', 'integer', 'min:1950', 'max:' . (now()->year + 1)],
            'price' => ['required', 'numeric', 'min:1', 'max:9999999'],
            'engine_cc' => ['required', 'integer', 'min:49', 'max:2500'],
            'mileage' => ['required', 'integer', 'min:0', 'max:500000'],
            'condition' => ['required', 'in:new,used'],
            'transmission' => ['required', 'in:automatic,manual'],
            'fuel_type' => ['required', 'in:petrol,diesel,electric,hybrid'],
            'color' => ['nullable', 'string', 'max:50'],
            'location' => ['required', 'string', 'max:120'],
            'description' => ['required', 'string', 'min:9', 'max:5000'],
            'features' => ['nullable', 'string', 'max:2000'],
            'main_image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'gallery_images' => ['nullable', 'array', 'max:6'],
            'gallery_images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'status' => ['nullable', 'in:pending,approved,rejected,sold'],
        ];
    }

    public function messages(): array
    {
        return [
            'main_image.max' => 'The main image may not be larger than 4MB.',
            'gallery_images.*.max' => 'Each gallery image may not be larger than 4MB.',
        ];
    }
}
