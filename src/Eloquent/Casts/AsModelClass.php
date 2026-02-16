<?php

namespace Jenishev\Laravel\Support\Eloquent\Casts;

use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

/**
 * Cast an attribute as a validated model class name.
 *
 * Validates that the stored value is a fully qualified class name that
 * implements or extends the specified interface or class. Ensures type
 * safety when storing polymorphic model class references.
 *
 * Example usage:
 * ```php
 * protected function casts(): array
 * {
 *     return [
 *         'model_type' => AsModelClass::of(SomeModel::class),
 *         // or
 *         // 'model_type' => AsModelClass::of(SomeInterface::class),
 *     ];
 * }
 * ```
 */
class AsModelClass implements Castable
{
    /**
     * {@inheritDoc}
     */
    public static function castUsing(array $arguments): CastsAttributes
    {
        return new class($arguments) implements CastsAttributes
        {
            /**
             * The arguments passed to the cast.
             *
             * @var array<int, string>
             */
            protected array $arguments;

            /**
             * Create a new cast instance.
             *
             * @param  array<int, string>  $arguments
             */
            public function __construct(array $arguments)
            {
                $this->arguments = $arguments;
            }

            /**
             * {@inheritDoc}
             */
            public function get(Model $model, string $key, mixed $value, array $attributes): ?string
            {
                if ($value === null) {
                    return null;
                }

                $this->validate($value);

                return $value;
            }

            /**
             * {@inheritDoc}
             */
            public function set(Model $model, string $key, mixed $value, array $attributes): ?string
            {
                if ($value === null) {
                    return null;
                }

                throw_unless(
                    is_string($value),
                    InvalidArgumentException::class,
                    sprintf('The value must be a string, %s given.', get_debug_type($value))
                );

                $this->validate($value);

                return $value;
            }

            /**
             * Validate that the class exists and implements/extends the required interface or class.
             *
             * @param  string  $value  The fully qualified class name to validate
             *
             * @throws InvalidArgumentException If validation fails
             */
            protected function validate(string $value): void
            {
                $className = $this->arguments[0];

                throw_if(
                    ! class_exists($className) && ! interface_exists($className),
                    InvalidArgumentException::class,
                    sprintf('Class or interface [%s] does not exist.', $className)
                );

                throw_unless(
                    is_a($value, $className, true),
                    InvalidArgumentException::class,
                    sprintf('Class [%s] must implement [%s] or extend it.', $value, $className)
                );
            }
        };
    }

    /**
     * Create a cast instance with the specified class or interface name.
     *
     * @param  string  $class  The fully qualified class or interface name to validate against
     * @return string The cast string for use in model casts
     */
    public static function of(string $class): string
    {
        return static::class . ':' . $class;
    }
}
