<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

#[Fillable(['title', 'path', 'sort_order', 'is_active'])]
class MediaVideo extends Model
{
    protected function casts(): array
    {
        return [
            'title' => 'array',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * სათაური მითითებულ ენაზე; თუ ცარიელია — ქართული.
     */
    public function translate(string $field, ?string $locale = null): ?string
    {
        $values = $this->{$field} ?? [];

        return ($values[$locale ?? 'ka'] ?? null) ?: ($values['ka'] ?? null);
    }

    /**
     * ველი ყველა ენაზე — საჯარო გვერდზე ენის გადართვისთვის.
     */
    public function translations(string $field): array
    {
        $values = [];

        foreach (array_keys(config('admin.locales')) as $locale) {
            $values[$locale] = $this->translate($field, $locale);
        }

        return $values;
    }

    public function url(): string
    {
        return Storage::disk('public')->url($this->path);
    }

    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('sort_order')->orderByDesc('id');
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }
}
