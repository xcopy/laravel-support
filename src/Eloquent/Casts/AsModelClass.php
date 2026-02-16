<?php

namespace Jenishev\Laravel\Support\Eloquent\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

/**
 * Cast an attribute as a model class name with type validation.
 *
 * Validates that the stored class name implements a specific interface
 * or extends a specific class.
 *
 * Example usage:
 * ```php
 * protected function casts(): array
 * {
 *     return [
 *         'model_type' => AsModelClass::of(ExampleContract::class),
 *     ];
 * }
 * ```
 *
 * @template T of object
 *
 * @implements CastsAttributes<class-string<T>, string>
 */
class AsModelClass implements CastsAttributes
{
    /**
     * The interface or class that the model must implement/extend.
     *
     * @var class-string<T>
     */
    protected string $type;

    /**
     * Create a new cast instance.
     *
     * @param  class-string<T>  $type  The interface or class to validate against
     */
    public function __construct(string $type)
    {
        $this->type = $type;
    }

    /**
     * Create a new cast instance for the given type.
     *
     * @param  class-string<T>  $type  The interface or class to validate against
     */
    public static function of(string $type): self
    {
        return new self($type);
    }

    /**
     * Cast the given value.
     *
     * Validates that the stored class name implements or extends the required type
     * and returns the class name as a string.
     *
     * @return class-string<T>|null
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
     * Prepare the given value for storage.
     *
     * Validates that the provided value is a string representing a class name
     * that implements or extends the required type before storing.
     *
     * @throws InvalidArgumentException If value is not a string or class doesn't implement the required type
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if ($value === null) {
            return null;
        }

        $this->validate($value);

        return $value;
    }

    /**
     * Validate that the class implements/extends the required type.
     *
     * Checks if the given class name exists and implements the interface or
     * extends the class specified in the $type property.
     *
     * @param  string  $className  The fully qualified class name to validate
     *
     * @throws InvalidArgumentException If a class doesn't exist or doesn't implement the required type
     */
    protected function validate(string $className): void
    {
        throw_if(
            ! class_exists($className) && ! interface_exists($className),
            InvalidArgumentException::class,
            sprintf('Class or interface [%s] does not exist.', $className)
        );

        throw_unless(
            is_a($className, $this->type, true),
            InvalidArgumentException::class,
            sprintf('Class [%s] must implement [%s] or extend it.', $className, $this->type)
        );
    }
}
