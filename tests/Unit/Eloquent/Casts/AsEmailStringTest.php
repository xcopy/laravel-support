<?php

namespace Jenishev\Laravel\Support\Tests\Unit\Eloquent\Casts;

use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use Jenishev\Laravel\Support\Eloquent\Casts\AsEmailString;

beforeEach(function () {
    $this->model = new class extends Model
    {
        protected $fillable = ['email'];

        protected function casts(): array
        {
            return [
                'email' => AsEmailString::class,
            ];
        }
    };
});

it('accepts valid email address', function () {
    $this->model->email = 'user@example.com';

    expect($this->model->email)->toBe('user@example.com');
});

it('converts email to lowercase', function () {
    $this->model->email = 'USER@EXAMPLE.COM';

    expect($this->model->email)->toBe('user@example.com');
});

it('trims whitespace from email', function () {
    $this->model->email = '  user@example.com  ';

    expect($this->model->email)->toBe('user@example.com');
});

it('handles email with uppercase and whitespace', function () {
    $this->model->email = '  USER@EXAMPLE.COM  ';

    expect($this->model->email)->toBe('user@example.com');
});

it('throws exception for invalid email format', function () {
    $this->model->email = 'not-an-email';
})->throws(InvalidArgumentException::class, 'The value [not-an-email] is not a valid email address.');

it('throws exception for email without domain', function () {
    $this->model->email = 'user@';
})->throws(InvalidArgumentException::class);

it('throws exception for email without at symbol', function () {
    $this->model->email = 'userexample.com';
})->throws(InvalidArgumentException::class);

it('throws exception for email with spaces in middle', function () {
    $this->model->email = 'user name@example.com';
})->throws(InvalidArgumentException::class);

it('handles null value', function () {
    $this->model->email = null;

    expect($this->model->email)->toBeNull();
});

it('converts empty string to null', function () {
    $this->model->email = '';

    expect($this->model->email)->toBeNull();
});

it('converts whitespace-only string to null', function () {
    $this->model->email = '   ';

    expect($this->model->email)->toBeNull();
});

it('handles valid email with subdomain', function () {
    $this->model->email = 'user@mail.example.com';

    expect($this->model->email)->toBe('user@mail.example.com');
});

it('handles valid email with plus sign', function () {
    $this->model->email = 'user+tag@example.com';

    expect($this->model->email)->toBe('user+tag@example.com');
});

it('handles valid email with dots', function () {
    $this->model->email = 'first.last@example.com';

    expect($this->model->email)->toBe('first.last@example.com');
});

it('handles valid email with numbers', function () {
    $this->model->email = 'user123@example456.com';

    expect($this->model->email)->toBe('user123@example456.com');
});

it('handles valid email with hyphen in domain', function () {
    $this->model->email = 'user@my-domain.com';

    expect($this->model->email)->toBe('user@my-domain.com');
});

it('throws exception for email with double at symbols', function () {
    $this->model->email = 'user@@example.com';
})->throws(InvalidArgumentException::class);

it('throws exception for email starting with dot', function () {
    $this->model->email = '.user@example.com';
})->throws(InvalidArgumentException::class);

it('throws exception for email ending with dot before at', function () {
    $this->model->email = 'user.@example.com';
})->throws(InvalidArgumentException::class);

it('converts array to null', function () {
    $this->model->email = ['user@example.com'];

    expect($this->model->email)->toBeNull();
});

it('converts object without toString to null', function () {
    $this->model->email = new class {};

    expect($this->model->email)->toBeNull();
});

it('handles false value as null', function () {
    $this->model->email = false;

    expect($this->model->email)->toBeNull();
});

it('handles zero as null', function () {
    $this->model->email = 0;

    expect($this->model->email)->toBeNull();
});

it('preserves value in database format', function () {
    $this->model->email = 'USER@EXAMPLE.COM';
    $this->model->syncOriginal();

    expect($this->model->getAttributes()['email'])->toBe('user@example.com');
});

it('handles long email addresses', function () {
    $longEmail = 'very.long.email.address.with.many.dots@subdomain.example.com';
    $this->model->email = $longEmail;

    expect($this->model->email)->toBe($longEmail);
});

it('handles international domain names', function () {
    $this->model->email = 'user@xn--d1acufc.xn--p1ai';

    expect($this->model->email)->toBe('user@xn--d1acufc.xn--p1ai');
});

it('throws exception for email with consecutive dots', function () {
    $this->model->email = 'user..name@example.com';
})->throws(InvalidArgumentException::class);

it('throws exception for multiple at symbols', function () {
    $this->model->email = 'user@domain@example.com';
})->throws(InvalidArgumentException::class);
