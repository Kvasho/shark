<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

#[Fillable(['path'])]
class MediaPhoto extends Model
{
    public function url(): string
    {
        return Storage::disk('public')->url($this->path);
    }

    /**
     * გალერეაში ახალი ფოტოები პირველი ჩანს.
     */
    public function scopeLatestFirst(Builder $query): void
    {
        $query->orderByDesc('id');
    }
}
