<?php

namespace Jenishev\Laravel\Support\Tests\Unit\Eloquent\Casts;

use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use Jenishev\Laravel\Support\Eloquent\Casts\AsEmailString;

beforeEach(function () {
    $this->cast = new AsEmailString;
    $this->model = new class extends Model
    {
        protected $fillable = ['email'];
    };
});

it('converts email to lowercase', function () {
    $result = $this->cast->set($this->model, 'email', 'John.Doe@EXAMPLE.COM', []);

    expect($result)->toBe('john.doe@example.com');
});

it('trims and converts to lowercase', function () {
    $result = $this->cast->set($this->model, 'email', '  USER@DOMAIN.COM  ', []);

    expect($result)->toBe('user@domain.com');
});

it('validates email format', function () {
    $result = $this->cast->set($this->model, 'email', 'valid.email@example.com', []);

    expect($result)->toBe('valid.email@example.com');
});

it('throws exception for invalid email format', function () {
    $this->cast->set($this->model, 'email', 'not-an-email', []);
})->throws(InvalidArgumentException::class, 'not a valid email address');

it('throws exception for email without domain', function () {
    $this->cast->set($this->model, 'email', 'user@', []);
})->throws(InvalidArgumentException::class);

it('throws exception for email without username', function () {
    $this->cast->set($this->model, 'email', '@example.com', []);
})->throws(InvalidArgumentException::class);

it('throws exception for email with spaces', function () {
    $this->cast->set($this->model, 'email', 'user name@example.com', []);
})->throws(InvalidArgumentException::class);

it('returns null for empty string', function () {
    $result = $this->cast->set($this->model, 'email', '', []);

    expect($result)->toBeNull();
});

it('returns null for null value', function () {
    $result = $this->cast->set($this->model, 'email', null, []);

    expect($result)->toBeNull();
});

it('returns null for whitespace-only string', function () {
    $result = $this->cast->set($this->model, 'email', '   ', []);

    expect($result)->toBeNull();
});

it('handles email with plus addressing', function () {
    $result = $this->cast->set($this->model, 'email', 'USER+TAG@EXAMPLE.COM', []);

    expect($result)->toBe('user+tag@example.com');
});

it('handles email with subdomain', function () {
    $result = $this->cast->set($this->model, 'email', 'USER@MAIL.EXAMPLE.COM', []);

    expect($result)->toBe('user@mail.example.com');
});

it('handles email with numbers', function () {
    $result = $this->cast->set($this->model, 'email', 'USER123@EXAMPLE123.COM', []);

    expect($result)->toBe('user123@example123.com');
});

it('handles email with hyphens', function () {
    $result = $this->cast->set($this->model, 'email', 'FIRST-LAST@EX-AMPLE.COM', []);

    expect($result)->toBe('first-last@ex-ample.com');
});

it('handles email with dots in username', function () {
    $result = $this->cast->set($this->model, 'email', 'FIRST.LAST@EXAMPLE.COM', []);

    expect($result)->toBe('first.last@example.com');
});

it('throws exception for double at sign', function () {
    $this->cast->set($this->model, 'email', 'user@@example.com', []);
})->throws(InvalidArgumentException::class);

it('throws exception for missing TLD', function () {
    $this->cast->set($this->model, 'email', 'user@localhost', []);
})->throws(InvalidArgumentException::class);
