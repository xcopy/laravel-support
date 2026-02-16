<?php

namespace Jenishev\Laravel\Support\Eloquent\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsInboundAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Cast an attribute as a trimmed string.
 *
 * Automatically trims whitespace from string values before storing them
 * in the database. Returns null for empty/falsy values. Non-string values
 * are safely converted to strings before trimming.
 *
 * Example usage:
 * ```php
 * protected function casts(): array
 * {
 *     return [
 *         'name' => AsTrimmedString::class,
 *         'email' => AsTrimmedString::class,
 *     ];
 * }
 * ```
 */
class AsTrimmedString implements CastsInboundAttributes
{
    /**
     * {@inheritDoc}
     */
    public function set(Model $model, string $key, mixed $value, array $attributes)
    {
        // Handle null and falsy values (but keep '0' as valid)
        if (empty($value) && $value !== '0') {
            return null;
        }

        // Return null for arrays (can't be converted to string)
        if (is_array($value)) {
            return null;
        }

        // Handle objects: only if they have __toString()
        if (is_object($value)) {
            if (method_exists($value, '__toString')) {
                $stringValue = (string) $value;
            } else {
                return null;
            }
        } else {
            // Convert scalar values to string
            $stringValue = is_string($value) ? $value : (string) $value;
        }

        // Trim and return, or null if empty after trimming
        $trimmed = Str::trim($stringValue);

        return $trimmed !== '' ? $trimmed : null;
    }
}
