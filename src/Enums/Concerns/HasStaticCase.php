<?php

namespace Jenishev\Laravel\Support\Enums\Concerns;

use BadMethodCallException;
use Illuminate\Support\Str;

/**
 * Provides magic static method access to enum values.
 *
 * Allows accessing enum values using static method calls with camelCase names only.
 * The method name is automatically converted to UPPER_SNAKE_CASE or PascalCase
 * to match the enum case naming convention.
 *
 * Example usage:
 * ```php
 * // Works with UPPER_SNAKE_CASE enum cases
 * enum PaymentStatusEnum: string
 * {
 *     use HasStaticCase;
 *
 *     case PENDING = 'pending';
 *     case IN_PROGRESS = 'in_progress';
 *     case COMPLETED = 'completed';
 * }
 *
 * PaymentStatusEnum::pending(); // 'pending'
 * PaymentStatusEnum::inProgress(); // 'in_progress'
 * PaymentStatusEnum::completed(); // 'completed'
 * PaymentStatusEnum::COMPLETED(); // BadMethodCallException
 *
 * // Also works with PascalCase enum cases
 * enum OrderStatusEnum: string
 * {
 *     use HasStaticCase;
 *
 *     case Pending = 'pending';
 *     case InProgress = 'in_progress';
 *     case Completed = 'completed';
 * }
 *
 * OrderStatusEnum::pending(); // 'pending'
 * OrderStatusEnum::inProgress(); // 'in_progress'
 * OrderStatusEnum::completed(); // 'completed'
 * OrderStatusEnum::InProgress(); // BadMethodCallException
 * ```
 */
trait HasStaticCase
{
    /**
     * Handle dynamic static method calls.
     *
     * Only accepts camelCase method names and converts them to UPPER_SNAKE_CASE
     * or PascalCase to match the enum case naming convention.
     *
     * @param  string  $name  The method name (must be camelCase)
     * @param  array<int, mixed>  $arguments  Method arguments (unused)
     * @return string The enum case value
     *
     * @throws BadMethodCallException If the method name is not camelCase or a case doesn't exist
     */
    public static function __callStatic(string $name, array $arguments)
    {
        // Validate that the method name is in camelCase format
        // Must start with lowercase and contain no underscores
        throw_unless(
            preg_match('/^[a-z][a-zA-Z0-9]*$/', $name),
            BadMethodCallException::class,
            "Method '$name' must be in camelCase format (e.g., 'inProgress', not 'InProgress' or 'IN_PROGRESS')"
        );

        // Try UPPER_SNAKE_CASE first (e.g., IN_PROGRESS)
        $upperSnakeCase = Str::upper(Str::snake($name));
        $constant = static::class . '::' . $upperSnakeCase;

        if (defined($constant)) {
            return constant($constant)->value;
        }

        // Try PascalCase as a fallback (e.g., InProgress)
        $pascalCase = Str::studly($name);
        $constant = static::class . '::' . $pascalCase;

        if (defined($constant)) {
            return constant($constant)->value;
        }

        // Neither variant exists, throw exception
        throw new BadMethodCallException(
            "Case '$upperSnakeCase' or '$pascalCase' does not exist on enum " . static::class
        );
    }
}
