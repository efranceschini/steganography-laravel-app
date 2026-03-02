<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Image;
use Illuminate\Database\Eloquent\Factories\Factory;

class ImageFactory extends Factory
{
    protected $model = Image::class;

    public function definition()
    {
        return [
            'title' => $this->faker->unique()->sentence(3),
            'path' => 'uploads/'.$this->faker->uuid().'.png',
            'user_id' => null,
        ];
    }
}
