<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

#[Fillable(['type', 'path', 'sort_order'])]
class ProjectMedia extends Model
{
    public const IMAGE = 'image';
    public const VIDEO = 'video';

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function isVideo(): bool
    {
        return $this->type === self::VIDEO;
    }

    public function url(): string
    {
        return Storage::disk('public')->url($this->path);
    }
}
