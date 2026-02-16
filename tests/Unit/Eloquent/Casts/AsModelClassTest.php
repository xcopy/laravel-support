<?php

namespace Jenishev\Laravel\Support\Tests\Unit\Eloquent\Casts;

use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use Jenishev\Laravel\Support\Eloquent\Casts\AsModelClass;

// Test interface
interface TestInterface {}

// Test classes
class TestModel extends Model implements TestInterface {}
class AnotherModel extends Model {}
class NonModelClass implements TestInterface {}

beforeEach(function () {
    $this->cast = AsModelClass::of(TestInterface::class);
    $this->model = new class extends Model
    {
        protected $fillable = ['model_type'];
    };
});

it('accepts class that implements interface', function () {
    $result = $this->cast->set($this->model, 'model_type', TestModel::class, []);

    expect($result)->toBe(TestModel::class);
});

it('returns class name on get', function () {
    $result = $this->cast->get($this->model, 'model_type', TestModel::class, []);

    expect($result)->toBe(TestModel::class);
});

it('throws exception for class that does not implement interface', function () {
    $this->cast->set($this->model, 'model_type', AnotherModel::class, []);
})->throws(InvalidArgumentException::class, 'must implement');

it('throws exception for non-existent class', function () {
    $this->cast->set($this->model, 'model_type', 'NonExistentClass', []);
})->throws(InvalidArgumentException::class, 'does not exist');

it('returns null for null value on set', function () {
    $result = $this->cast->set($this->model, 'model_type', null, []);

    expect($result)->toBeNull();
});

it('returns null for null value on get', function () {
    $result = $this->cast->get($this->model, 'model_type', null, []);

    expect($result)->toBeNull();
});

it('throws exception for non-string value', function () {
    $this->cast->set($this->model, 'model_type', 123, []);
})->throws(InvalidArgumentException::class, 'must be a string');

it('throws exception for array value', function () {
    $this->cast->set($this->model, 'model_type', ['class' => TestModel::class], []);
})->throws(InvalidArgumentException::class, 'must be a string');

it('accepts class that implements interface on get', function () {
    $result = $this->cast->get($this->model, 'model_type', TestModel::class, []);

    expect($result)->toBe(TestModel::class);
});

it('can validate against Model class', function () {
    $cast = AsModelClass::of(Model::class);
    $result = $cast->set($this->model, 'model_type', TestModel::class, []);

    expect($result)->toBe(TestModel::class);
});

it('throws exception when not extending required class', function () {
    $cast = AsModelClass::of(Model::class);
    $cast->set($this->model, 'model_type', NonModelClass::class, []);
})->throws(InvalidArgumentException::class);

it('works with static of method', function () {
    $cast = AsModelClass::of(TestInterface::class);
    $result = $cast->set($this->model, 'model_type', TestModel::class, []);

    expect($result)->toBe(TestModel::class);
});

it('preserves fully qualified class names', function () {
    $result = $this->cast->set($this->model, 'model_type', TestModel::class, []);

    expect($result)->toBe(TestModel::class);
    expect($result)->not->toStartWith('\\\\');
});

it('validates on get as well as set', function () {
    $cast = AsModelClass::of(TestInterface::class);
    $cast->get($this->model, 'model_type', AnotherModel::class, []);
})->throws(InvalidArgumentException::class);

it('handles interface names', function () {
    $result = $this->cast->set($this->model, 'model_type', TestInterface::class, []);

    expect($result)->toBe(TestInterface::class);
});
