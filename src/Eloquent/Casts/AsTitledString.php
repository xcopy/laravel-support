<?php

namespace Jenishev\Laravel\Support\Eloquent\Casts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Cast an attribute as a titled string.
 *
 * Extends AsTrimmedString to first trim whitespace, then convert the string
 * to a title case (first letter of each word capitalized). Returns null for
 * empty/falsy values.
 *
 * Example usage:
 * ```php
 * protected function casts(): array
 * {
 *     return [
 *         'name' => AsTitledString::class,
 *         'city' => AsTitledString::class,
 *     ];
 * }
 * ```
 */
class AsTitledString extends AsTrimmedString
{
    /**
     * {@inheritDoc}
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        $trimmed = parent::set($model, $key, $value, $attributes);

        if ($trimmed === null) {
            return null;
        }

        // Normalize tabs to spaces for consistent handling
        $normalized = str_replace("\t", ' ', $trimmed);

        // Convert to lowercase, then capitalize the first letter
        $result = Str::of($normalized)->lower()->ucfirst();

        // Capitalize the first letter after spaces and hyphens
        // Pattern captures whitespace/hyphens followed by any letter (ASCII or Unicode)
        $result = $result->replaceMatches('/([\s-]+)([a-zA-Z\p{L}])/u', fn ($matches) => $matches[1] . Str::upper($matches[2]));

        return $result->toString();
    }
}
