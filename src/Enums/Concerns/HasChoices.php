<?php

namespace Jenishev\Laravel\Support\Enums\Concerns;

/**
 * Provides a choices() method for backed enums.
 *
 * Returns an array of enum values mapped to their human-readable labels,
 * perfect for use in select dropdowns, radio buttons, etc.
 *
 * Example usage:
 * ```php
 * enum StatusEnum: string
 * {
 *     use HasChoices;
 *
 *     case Pending = 'pending';
 *     case Approved = 'approved';
 * }
 *
 * StatusEnum::choices(); // ['pending' => 'Pending', 'approved' => 'Approved']
 * ```
 */
trait HasChoices
{
    use HasLabels;

    /**
     * Get all enum cases as value => label pairs.
     *
     * Returns an associative array where keys are the enum values
     * and values are the human-readable labels (using the label() method).
     *
     * @return array<string, string>
     */
    public static function choices(): array
    {
        $cases = static::cases();

        return array_combine(
            array_map(fn (self $case) => $case->value, $cases),
            array_map(fn (self $case) => $case->label(), $cases)
        );
    }
}
