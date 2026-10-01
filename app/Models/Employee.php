<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

#[Fillable(['first_name', 'last_name', 'position', 'bio', 'photo', 'sort_order', 'is_active'])]
class Employee extends Model
{
    protected function casts(): array
    {
        return [
            'first_name' => 'array',
            'last_name' => 'array',
            'position' => 'array',
            'bio' => 'array',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * სათარგმნი ველის მნიშვნელობა მითითებულ ენაზე; თუ ცარიელია — ქართული.
     */
    public function translate(string $field, ?string $locale = null): ?string
    {
        $values = $this->{$field} ?? [];

        return ($values[$locale ?? 'ka'] ?? null) ?: ($values['ka'] ?? null);
    }

    public function fullName(?string $locale = null): string
    {
        return trim($this->translate('first_name', $locale) . ' ' . $this->translate('last_name', $locale));
    }

    /**
     * საჯარო გვერდისთვის: ყველა ენის მონაცემი ერთად, რომ ენის შეცვლა
     * ბრაუზერში მოხდეს გვერდის გადატვირთვის გარეშე.
     *
     * @return array{photo: string, name: array, position: array, bio: array}
     */
    public function toPublicArray(): array
    {
        $data = ['photo' => $this->photoUrl()];

        foreach (array_keys(config('admin.locales')) as $locale) {
            $data['name'][$locale] = $this->fullName($locale);
            $data['position'][$locale] = $this->translate('position', $locale);
            $data['bio'][$locale] = $this->translate('bio', $locale);
        }

        return $data;
    }

    public function photoUrl(): string
    {
        return Storage::disk('public')->url($this->photo);
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
