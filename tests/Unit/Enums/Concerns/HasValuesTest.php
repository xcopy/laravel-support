<?php

namespace Jenishev\Laravel\Support\Tests\Unit\Enums\Concerns;

use Jenishev\Laravel\Support\Enums\Concerns\HasValues;

enum RoleEnum: string
{
    use HasValues;

    case Admin = 'admin';
    case Manager = 'manager';
    case User = 'user';
    case Guest = 'guest';
}

enum PriorityEnum: int
{
    use HasValues;

    case Low = 1;
    case Medium = 2;
    case High = 3;
    case Critical = 4;
}

it('returns array of string values', function () {
    $values = RoleEnum::values();

    expect($values)->toBeArray();
    expect($values)->toBe(['admin', 'manager', 'user', 'guest']);
});

it('returns array of integer values', function () {
    $values = PriorityEnum::values();

    expect($values)->toBeArray();
    expect($values)->toBe([1, 2, 3, 4]);
});

it('returns values in order', function () {
    $values = RoleEnum::values();

    expect($values[0])->toBe('admin');
    expect($values[1])->toBe('manager');
    expect($values[2])->toBe('user');
    expect($values[3])->toBe('guest');
});

it('returns indexed array not associative', function () {
    $values = RoleEnum::values();

    expect(array_keys($values))->toBe([0, 1, 2, 3]);
});

it('does not return case names', function () {
    $values = RoleEnum::values();

    expect($values)->not->toContain('Admin');
    expect($values)->not->toContain('Manager');
});

it('returns count matching number of cases', function () {
    expect(RoleEnum::values())->toHaveCount(4);
    expect(PriorityEnum::values())->toHaveCount(4);
});

it('can be used in validation rules', function () {
    $values = RoleEnum::values();

    // Simulate validation usage
    $testValue = 'admin';
    expect(in_array($testValue, $values))->toBeTrue();

    $invalidValue = 'superadmin';
    expect(in_array($invalidValue, $values))->toBeFalse();
});

it('can be used in database queries', function () {
    $values = RoleEnum::values();

    // Simulate query usage
    expect($values)->toBeArray();
    expect(implode(',', $values))->toBe('admin,manager,user,guest');
});
