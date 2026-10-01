<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

#[Fillable(['path', 'sort_order'])]
class ServiceStepImage extends Model
{
    public function step(): BelongsTo
    {
        return $this->belongsTo(ServiceStep::class, 'service_step_id');
    }

    public function url(): string
    {
        return Storage::disk('public')->url($this->path);
    }
}
