<?php

namespace Jenishev\Laravel\Support\Tests\Unit\Enums\Concerns;

use Jenishev\Laravel\Support\Enums\Concerns\HasChoices;

enum OrderStatusEnum: string
{
    use HasChoices;

    case Pending = 'pending';
    case Processing = 'processing';
    case Cancelled = 'cancelled';
    case ReviewRequested = 'review_requested';
    case UnderReview = 'under_review';
    case Completed = 'COMPLETED';
    case VOID = 'void';
}

it('returns choices as value => label pairs', function () {
    $choices = OrderStatusEnum::choices();

    expect($choices)->toBeArray();
    expect($choices)->toHaveKey('pending');
    expect($choices)->toHaveKey('processing');
    expect($choices)->toHaveKey('cancelled');
    expect($choices)->toHaveKey('review_requested');
    expect($choices)->toHaveKey('under_review');
    expect($choices)->toHaveKey('COMPLETED');
    expect($choices)->toHaveKey('void');
});

it('returns human readable labels', function () {
    $choices = OrderStatusEnum::choices();

    expect($choices['pending'])->toBe('Pending');
    expect($choices['processing'])->toBe('Processing');
    expect($choices['cancelled'])->toBe('Cancelled');
    expect($choices['review_requested'])->toBe('Review Requested');
    expect($choices['under_review'])->toBe('Under Review');
    expect($choices['COMPLETED'])->toBe('Completed');
    expect($choices['void'])->toBe('Void');
});

it('returns associative array', function () {
    $choices = OrderStatusEnum::choices();

    expect(array_keys($choices))->toBe(['pending', 'processing', 'cancelled', 'review_requested', 'under_review', 'COMPLETED', 'void']);
    expect(array_values($choices))->toBe(['Pending', 'Processing', 'Cancelled', 'Review Requested', 'Under Review', 'Completed', 'Void']);
});

it('can be used in select options', function () {
    $choices = OrderStatusEnum::choices();

    foreach ($choices as $value => $label) {
        expect($value)->toBeString();
        expect($label)->toBeString();
    }
});

it('returns all enum cases', function () {
    $choices = OrderStatusEnum::choices();

    expect($choices)->toHaveCount(7);
});

it('maintains case order', function () {
    $choices = OrderStatusEnum::choices();
    $keys = array_keys($choices);

    expect($keys[0])->toBe('pending');
    expect($keys[1])->toBe('processing');
    expect($keys[2])->toBe('cancelled');
    expect($keys[3])->toBe('review_requested');
    expect($keys[4])->toBe('under_review');
    expect($keys[5])->toBe('COMPLETED');
    expect($keys[6])->toBe('void');
});

it('uses label method for values', function () {
    $choices = OrderStatusEnum::choices();

    // Label method converts to headline
    expect($choices['pending'])->toBe(OrderStatusEnum::Pending->label());
});

it('can iterate over choices', function () {
    $choices = OrderStatusEnum::choices();
    $count = 0;

    foreach ($choices as $value => $label) {
        $count++;
        expect($value)->toBeString();
        expect($label)->toBeString();
    }

    expect($count)->toBe(7);
});
