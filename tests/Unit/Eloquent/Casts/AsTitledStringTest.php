<?php

namespace Jenishev\Laravel\Support\Tests\Unit\Eloquent\Casts;

use Illuminate\Database\Eloquent\Model;
use Jenishev\Laravel\Support\Eloquent\Casts\AsTitledString;

beforeEach(function () {
    $this->cast = new AsTitledString;
    $this->model = new class extends Model
    {
        protected $fillable = ['name'];
    };
});

it('converts to title case', function () {
    $result = $this->cast->set($this->model, 'name', 'john doe', []);

    expect($result)->toBe('John Doe');
});

it('trims and converts to title case', function () {
    $result = $this->cast->set($this->model, 'name', '  john doe  ', []);

    expect($result)->toBe('John Doe');
});

it('handles already titled strings', function () {
    $result = $this->cast->set($this->model, 'name', 'John Doe', []);

    expect($result)->toBe('John Doe');
});

it('handles all lowercase', function () {
    $result = $this->cast->set($this->model, 'name', 'mary jane watson', []);

    expect($result)->toBe('Mary Jane Watson');
});

it('handles all uppercase', function () {
    $result = $this->cast->set($this->model, 'name', 'JOHN DOE', []);

    expect($result)->toBe('John Doe');
});

it('handles mixed case', function () {
    $result = $this->cast->set($this->model, 'name', 'jOhN dOe', []);

    expect($result)->toBe('John Doe');
});

it('returns null for empty string', function () {
    $result = $this->cast->set($this->model, 'name', '', []);

    expect($result)->toBeNull();
});

it('returns null for null value', function () {
    $result = $this->cast->set($this->model, 'name', null, []);

    expect($result)->toBeNull();
});

it('returns null for whitespace-only string', function () {
    $result = $this->cast->set($this->model, 'name', '   ', []);

    expect($result)->toBeNull();
});

it('handles single word', function () {
    $result = $this->cast->set($this->model, 'name', 'john', []);

    expect($result)->toBe('John');
});

it('handles hyphenated names', function () {
    $result = $this->cast->set($this->model, 'name', 'jean-claude van damme', []);

    expect($result)->toBe('Jean-Claude Van Damme');
});

it('handles names with apostrophes', function () {
    $result = $this->cast->set($this->model, 'name', "o'connor", []);

    expect($result)->toBe("O'connor");
});

it('handles names with apostrophes in uppercase', function () {
    $result = $this->cast->set($this->model, 'name', "O'CONNOR", []);

    expect($result)->toBe("O'connor");
});

it('handles names with periods', function () {
    $result = $this->cast->set($this->model, 'name', 'dr. john doe', []);

    expect($result)->toBe('Dr. John Doe');
});

it('handles multiple spaces', function () {
    $result = $this->cast->set($this->model, 'name', 'john   doe', []);

    expect($result)->toBe('John   Doe');
});

it('handles city names', function () {
    $result = $this->cast->set($this->model, 'name', 'new york city', []);

    expect($result)->toBe('New York City');
});

it('handles names with numbers', function () {
    $result = $this->cast->set($this->model, 'name', 'john doe 2nd', []);

    expect($result)->toBe('John Doe 2nd');
});

it('handles ordinal numbers correctly', function () {
    $result = $this->cast->set($this->model, 'name', 'henry viii', []);

    expect($result)->toBe('Henry Viii');
});

it('does not capitalize after numbers', function () {
    $result = $this->cast->set($this->model, 'name', 'room 42b', []);

    expect($result)->toBe('Room 42b');
});

it('handles unicode characters', function () {
    $result = $this->cast->set($this->model, 'name', 'josé garcía', []);

    expect($result)->toBe('José García');
});

it('handles tabs and newlines in input', function () {
    $result = $this->cast->set($this->model, 'name', "\tjohn\tdoe\n", []);

    expect($result)->toBe('John Doe');
});
