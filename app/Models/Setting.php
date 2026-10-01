<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['key', 'value'])]
class Setting extends Model
{
    /**
     * ერთი მოთხოვნის განმავლობაში პარამეტრები ერთხელ იკითხება ბაზიდან.
     *
     * @var array<string, string|null>|null
     */
    private static ?array $loaded = null;

    public static function valueOf(string $key, ?string $default = null): ?string
    {
        self::$loaded ??= static::query()->pluck('value', 'key')->all();

        return self::$loaded[$key] ?? $default;
    }

    /**
     * @param  array<string, string|null>  $values
     */
    public static function put(array $values): void
    {
        foreach ($values as $key => $value) {
            static::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        self::$loaded = null;
    }
}
