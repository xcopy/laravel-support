<?php

namespace Jenishev\Laravel\Support\Eloquent\Casts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use InvalidArgumentException;

/**
 * Cast an attribute as a validated email string.
 *
 * Extends AsTrimmedString to first trim whitespace, then validates the email
 * format, and converts to lowercase for consistent email storage. Returns null
 * for empty/falsy values.
 *
 * Example usage:
 * ```php
 * protected function casts(): array
 * {
 *     return [
 *         'email' => AsEmailString::class,
 *     ];
 * }
 * ```
 */
class AsEmailString extends AsTrimmedString
{
    /**
     * {@inheritDoc}
     */
    public function set(Model $model, string $key, mixed $value, array $attributes)
    {
        $trimmed = parent::set($model, $key, $value, $attributes);

        if ($trimmed === null) {
            return null;
        }

        $email = Str::lower($trimmed);

        throw_unless(
            filter_var($email, FILTER_VALIDATE_EMAIL),
            InvalidArgumentException::class,
            sprintf('The value [%s] is not a valid email address.', $email)
        );

        return $email;
    }
}
