<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

#[Fillable(['slug', 'title', 'category', 'location', 'excerpt', 'description', 'year', 'area', 'cover', 'sort_order', 'is_active'])]
class Project extends Model
{
    protected function casts(): array
    {
        return [
            'title' => 'array',
            'category' => 'array',
            'location' => 'array',
            'excerpt' => 'array',
            'description' => 'array',
            'year' => 'integer',
            'area' => 'integer',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function media(): HasMany
    {
        return $this->hasMany(ProjectMedia::class)->orderBy('sort_order')->orderBy('id');
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

    /**
     * ფართობი სამივე ენაზე: "18 400 მ²" / "18 400 m²" / "18 400 м²".
     */
    public function areaTranslations(): ?array
    {
        if (! $this->area) {
            return null;
        }

        $number = number_format($this->area, 0, '', ' ');

        return ['ka' => "$number მ²", 'en' => "$number m²", 'ru' => "$number м²"];
    }

    public function coverUrl(): string
    {
        return Storage::disk('public')->url($this->cover);
    }

    /**
     * უნიკალური slug ინგლისური სათაურიდან (თუ ცარიელია — ქართულიდან).
     */
    public static function uniqueSlug(array $title): string
    {
        $base = Str::slug($title['en'] ?? '') ?: Str::slug($title['ka'] ?? '') ?: 'project';
        $slug = $base;
        $counter = 2;

        while (static::where('slug', $slug)->exists()) {
            $slug = "$base-" . $counter++;
        }

        return $slug;
    }

    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('sort_order')->orderByDesc('year')->orderBy('id');
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }
}
