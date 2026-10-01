<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['title', 'description', 'sort_order', 'is_active'])]
class ServiceStep extends Model
{
    protected function casts(): array
    {
        return [
            'title' => 'array',
            'description' => 'array',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function images(): HasMany
    {
        return $this->hasMany(ServiceStepImage::class)->orderBy('sort_order')->orderBy('id');
    }

    /**
     * სათარგმნი ველის მნიშვნელობა მითითებულ ენაზე; თუ ცარიელია — ქართული.
     */
    public function translate(string $field, ?string $locale = null): ?string
    {
        $values = $this->{$field} ?? [];

        return ($values[$locale ?? 'ka'] ?? null) ?: ($values['ka'] ?? null);
    }

    /**
     * ველი ყველა ენაზე (ცარიელი ენა ქართულით ივსება) — საჯარო გვერდზე ენის გადართვისთვის.
     */
    public function translations(string $field): array
    {
        $values = [];

        foreach (array_keys(config('admin.locales')) as $locale) {
            $values[$locale] = $this->translate($field, $locale);
        }

        return $values;
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
