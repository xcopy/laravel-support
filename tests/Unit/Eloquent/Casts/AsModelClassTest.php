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
    $this->model = new class extends Model
    {
        protected $fillable = ['model_type'];

        protected function casts(): array
        {
            return [
                'model_type' => AsModelClass::of(TestInterface::class),
            ];
        }
    };
});

it('accepts class that implements interface', function () {
    $this->model->model_type = TestModel::class;

    expect($this->model->model_type)->toBe(TestModel::class);
});

it('returns class name on get', function () {
    $this->model->model_type = TestModel::class;

    expect($this->model->model_type)
        ->toBe(TestModel::class)
        ->and($this->model->getAttributes()['model_type'])->toBe(TestModel::class);
});

it('throws exception for class that does not implement interface', function () {
    $this->model->model_type = AnotherModel::class;
})->throws(InvalidArgumentException::class);

it('throws exception for non-existent class', function () {
    $this->model->model_type = 'NonExistentClass';
})->throws(InvalidArgumentException::class);

it('handles null value on set', function () {
    $this->model->model_type = null;

    expect($this->model->model_type)->toBeNull();
});

it('handles null value on get', function () {
    $this->model->forceFill(['model_type' => null]);

    expect($this->model->model_type)->toBeNull();
});

it('throws exception for non-string value', function () {
    $this->model->model_type = 123;
})->throws(InvalidArgumentException::class);

it('throws exception for array value', function () {
    $this->model->model_type = ['class' => TestModel::class];
})->throws(InvalidArgumentException::class);

it('throws exception for object value', function () {
    $this->model->model_type = new TestModel;
})->throws(InvalidArgumentException::class);

it('works with Model base class', function () {
    $model = new class extends Model
    {
        protected $fillable = ['model_type'];

        protected function casts(): array
        {
            return [
                'model_type' => AsModelClass::of(Model::class),
            ];
        }
    };

    $model->model_type = TestModel::class;

    expect($model->model_type)->toBe(TestModel::class);
});

it('throws exception when not extending required class', function () {
    $model = new class extends Model
    {
        protected $fillable = ['model_type'];

        protected function casts(): array
        {
            return [
                'model_type' => AsModelClass::of(Model::class),
            ];
        }
    };

    $model->model_type = NonModelClass::class;
})->throws(InvalidArgumentException::class);

it('validates on get as well as set', function () {
    $this->model->forceFill(['model_type' => AnotherModel::class]);

    $value = $this->model->model_type;
})->throws(InvalidArgumentException::class);

it('preserves fully qualified class names', function () {
    $this->model->model_type = TestModel::class;

    expect($this->model->model_type)->toBe(TestModel::class)
        ->and($this->model->model_type)->not->toStartWith('\\\\');
});

it('handles interface names', function () {
    $this->model->model_type = TestInterface::class;

    expect($this->model->model_type)->toBe(TestInterface::class);
});

it('throws exception for non-existent class on get', function () {
    $this->model->forceFill(['model_type' => 'NonExistentClass']);
})->throws(InvalidArgumentException::class);

it('works with static of method', function () {
    $castString = AsModelClass::of(TestInterface::class);

    expect($castString)->toBe(AsModelClass::class . ':' . TestInterface::class);
});

it('can handle multiple different interfaces', function () {
    $model = new class extends Model
    {
        protected $fillable = ['first_type', 'second_type'];

        protected function casts(): array
        {
            return [
                'first_type' => AsModelClass::of(TestInterface::class),
                'second_type' => AsModelClass::of(Model::class),
            ];
        }
    };

    $model->first_type = TestModel::class;
    $model->second_type = AnotherModel::class;

    expect($model->first_type)->toBe(TestModel::class)
        ->and($model->second_type)->toBe(AnotherModel::class);
});

it('preserves value in database format', function () {
    $this->model->model_type = TestModel::class;
    $this->model->syncOriginal();

    expect($this->model->getAttributes()['model_type'])->toBe(TestModel::class);
});
