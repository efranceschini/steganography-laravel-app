<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreImageRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('images', 'title')->where(function ($query) {
                    return $query->where('user_id', $this->user()->id);
                })],
            'image' => ['required', 'image', 'mimetypes:image/png', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.unique' => 'You already have an image with this title.',
            'image.mimetypes' => 'The uploaded image is not a PNG.',
            'image.max' => 'File size limit: 2MB.',
        ];
    }
}
