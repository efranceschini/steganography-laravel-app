<?php

declare(strict_types=1);

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SteganographyDecodeApiRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'image' => ['required', 'image', 'mimetypes:image/png', 'max:2048'],
            'encoding' => ['required', 'string', Rule::in(config('api.steganography.encodings'))],
        ];
    }
}
