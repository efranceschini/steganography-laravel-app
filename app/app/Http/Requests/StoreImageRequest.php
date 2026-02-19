<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreImageRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'image' => ['required', 'image', 'mimetypes:image/png', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'image.mimetypes' => 'The uploaded image is not a PNG.',
            'image.max' => 'File size limit: 2MB.',
        ];
    }
}
