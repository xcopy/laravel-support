<?php

namespace Jenishev\Laravel\Support\Enums\Concerns;

use Illuminate\Support\Str;

/**
 * Provides a label() method for backed enums.
 *
 * Automatically generates human-readable labels from enum values by
 * converting them to a headline case and running through Laravel's
 * translation system.
 *
 * Example usage:
 * ```php
 * enum StatusEnum: string
 * {
 *     use HasLabels;
 *
 *     case Pending = 'pending';
 *     case InProgress = 'in_progress';
 * }
 *
 * StatusEnum::Pending->label(); // 'Pending'
 * StatusEnum::InProgress->label(); // 'In Progress'
 * ```
 */
trait HasLabels
{
    /**
     * Get the human-readable label for the enum case.
     *
     * Converts the enum value to a headline case (title case with spaces)
     * and passes it through Laravel's translation system.
     */
    public function label(): string
    {
        return __(Str::headline(Str::lower($this->value)));
    }
}
