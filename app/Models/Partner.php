<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

#[Fillable(['logo', 'url', 'sort_order', 'is_active'])]
class Partner extends Model
{
    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function logoUrl(): string
    {
        return Storage::disk('public')->url($this->logo);
    }

    /**
     * ბმული ადამიანისთვის საჩვენებლად, პროტოკოლის გარეშე: "www.facebook.com".
     */
    public function displayUrl(): ?string
    {
        return $this->url ? preg_replace('#^https?://#i', '', rtrim($this->url, '/')) : null;
    }

    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('sort_order')->orderBy('id');
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }
}
