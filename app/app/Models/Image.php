<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = ['title', 'path', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
