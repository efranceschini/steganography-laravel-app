<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = ['title', 'path', 'size', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function human_filesize(int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $power = $this->size > 0 ? floor(log($this->size, 1024)) : 0;

        return number_format($this->size / (1024 ** $power), $precision).' '.$units[$power];
    }
}
