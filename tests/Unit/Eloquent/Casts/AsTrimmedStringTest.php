<?php

namespace Jenishev\Laravel\Support\Tests\Unit\Eloquent\Casts;

use Illuminate\Database\Eloquent\Model;
use Jenishev\Laravel\Support\Eloquent\Casts\AsTrimmedString;

beforeEach(function () {
    $this->cast = new AsTrimmedString;
    $this->model = new class extends Model
    {
        protected $fillable = ['name'];
    };
});

it('trims leading whitespace from string', function () {
    $result = $this->cast->set($this->model, 'name', '  John Doe', []);

    expect($result)->toBe('John Doe');
});

it('trims trailing whitespace from string', function () {
    $result = $this->cast->set($this->model, 'name', 'John Doe  ', []);

    expect($result)->toBe('John Doe');
});

it('trims both leading and trailing whitespace', function () {
    $result = $this->cast->set($this->model, 'name', '  John Doe  ', []);

    expect($result)->toBe('John Doe');
});

it('trims tabs and newlines', function () {
    $result = $this->cast->set($this->model, 'name', "\t\nJohn Doe\n\t", []);

    expect($result)->toBe('John Doe');
});

it('preserves internal whitespace', function () {
    $result = $this->cast->set($this->model, 'name', '  John   Doe  ', []);

    expect($result)->toBe('John   Doe');
});

it('returns null for empty string', function () {
    $result = $this->cast->set($this->model, 'name', '', []);

    expect($result)->toBeNull();
});

it('returns null for whitespace-only string', function () {
    $result = $this->cast->set($this->model, 'name', '   ', []);

    expect($result)->toBeNull();
});

it('handles already trimmed strings', function () {
    $result = $this->cast->set($this->model, 'name', 'John Doe', []);

    expect($result)->toBe('John Doe');
});

it('handles single character strings', function () {
    $result = $this->cast->set($this->model, 'name', ' A ', []);

    expect($result)->toBe('A');
});

it('handles unicode whitespace', function () {
    $result = $this->cast->set($this->model, 'name', "\u{00A0}John Doe\u{00A0}", []);

    expect($result)->toBe('John Doe');
});

it('handles very long strings with whitespace', function () {
    $longString = '  ' . str_repeat('Lorem ipsum dolor sit amet ', 1000) . '  ';
    $result = $this->cast->set($this->model, 'name', $longString, []);

    expect($result)->not->toStartWith(' ');
    expect($result)->not->toEndWith(' ');
});

it('works with different attribute names', function () {
    $result = $this->cast->set($this->model, 'email', '  test@example.com  ', []);

    expect($result)->toBe('test@example.com');
});

it('ignores attributes parameter', function () {
    $result = $this->cast->set($this->model, 'name', '  John  ', ['other' => 'value']);

    expect($result)->toBe('John');
});

// Null return cases
it('returns null for null value', function () {
    $result = $this->cast->set($this->model, 'name', null, []);

    expect($result)->toBeNull();
});

it('returns null for boolean false', function () {
    $result = $this->cast->set($this->model, 'name', false, []);

    expect($result)->toBeNull();
});

it('returns null for integer zero', function () {
    $result = $this->cast->set($this->model, 'name', 0, []);

    expect($result)->toBeNull();
});

it('returns null for arrays', function () {
    $result = $this->cast->set($this->model, 'name', ['test'], []);

    expect($result)->toBeNull();
});

it('returns null for objects without __toString', function () {
    $object = new class
    {
        public $property = 'value';
    };

    $result = $this->cast->set($this->model, 'name', $object, []);

    expect($result)->toBeNull();
});

// Type conversion cases
it('converts integer to string', function () {
    $result = $this->cast->set($this->model, 'name', 123, []);

    expect($result)->toBe('123');
});

it('converts float to string', function () {
    $result = $this->cast->set($this->model, 'name', 45.67, []);

    expect($result)->toBe('45.67');
});

it('converts boolean true to string', function () {
    $result = $this->cast->set($this->model, 'name', true, []);

    expect($result)->toBe('1');
});

it('preserves string zero', function () {
    $result = $this->cast->set($this->model, 'name', '0', []);

    expect($result)->toBe('0');
});

it('converts object with __toString to string', function () {
    $object = new class
    {
        public function __toString(): string
        {
            return '  Object Value  ';
        }
    };

    $result = $this->cast->set($this->model, 'name', $object, []);

    expect($result)->toBe('Object Value');
});

it('returns null for object without __toString', function () {
    $object = new class
    {
        public $property = 'value';
    };

    $result = $this->cast->set($this->model, 'name', $object, []);

    expect($result)->toBeNull();
});
