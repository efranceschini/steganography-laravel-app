<?php

declare(strict_types=1);

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SteganographyEncodeApiRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'image_id' => [
                'required',
                'integer',
                Rule::exists('images', 'id')->where(function ($query) {
                    return $query->where('user_id', $this->user()->id);
                }),
            ],
            'message' => ['required', 'string', 'max:2048'],
            'encoding' => ['required', 'string', Rule::in(config('api.steganography.encodings'))],
        ];
    }
}
