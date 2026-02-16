<?php

namespace Jenishev\Laravel\Support\Enums\Concerns;

/**
 * Provides a values() method for backed enums.
 *
 * Returns a simple array of all enum values, useful for validation,
 * database queries, or anywhere you need just the raw values.
 *
 * Example usage:
 * ```php
 * enum StatusEnum: string
 * {
 *     use HasValues;
 *
 *     case Pending = 'pending';
 *     case Approved = 'approved';
 *     case Rejected = 'rejected';
 * }
 *
 * StatusEnum::values(); // ['pending', 'approved', 'rejected']
 * ```
 */
trait HasValues
{
    /**
     * Get all enum values as an indexed array.
     *
     * Returns a simple array containing only the values (not labels)
     * of all enum cases.
     *
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_column(static::cases(), 'value');
    }
}
