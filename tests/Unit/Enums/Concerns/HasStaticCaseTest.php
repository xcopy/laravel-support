<?php

namespace Jenishev\Laravel\Support\Tests\Unit\Enums\Concerns;

use BadMethodCallException;
use Jenishev\Laravel\Support\Enums\Concerns\HasStaticCase;

enum PaymentMethodEnum: string
{
    use HasStaticCase;

    case CREDIT_CARD = 'credit_card';
    case DEBIT_CARD = 'debit_card';
    case PAYPAL = 'paypal';
    case BANK_TRANSFER = 'bank_transfer';
    case CASH = 'cash';
}

enum StateEnum: string
{
    use HasStaticCase;

    case Pending = 'pending';
    case InProgress = 'in_progress';
    case Completed = 'completed';
    case OnHold = 'on_hold';
}

it('calls case using camelCase method name', function () {
    expect(PaymentMethodEnum::creditCard())->toBe('credit_card');
});

it('converts debitCard to DEBIT_CARD', function () {
    expect(PaymentMethodEnum::debitCard())->toBe('debit_card');
});

it('converts paypal to PAYPAL', function () {
    expect(PaymentMethodEnum::paypal())->toBe('paypal');
});

it('converts bankTransfer to BANK_TRANSFER', function () {
    expect(PaymentMethodEnum::bankTransfer())->toBe('bank_transfer');
});

it('converts cash to CASH', function () {
    expect(PaymentMethodEnum::cash())->toBe('cash');
});

it('throws exception for non-existent case', function () {
    PaymentMethodEnum::bitcoin();
})->throws(BadMethodCallException::class);

it('throws exception with descriptive message', function () {
    try {
        PaymentMethodEnum::nonExistentCase();
    } catch (BadMethodCallException $e) {
        expect($e->getMessage())->toContain('NON_EXISTENT_CASE');
        expect($e->getMessage())->toContain('NonExistentCase');
        expect($e->getMessage())->toContain('does not exist');
        expect($e->getMessage())->toContain(PaymentMethodEnum::class);
    }
});

it('handles single word case names', function () {
    expect(PaymentMethodEnum::cash())->toBe('cash');
});

it('handles multi-word case names', function () {
    expect(PaymentMethodEnum::bankTransfer())->toBe('bank_transfer');
});

it('returns case value not case instance', function () {
    $result = PaymentMethodEnum::creditCard();

    expect($result)->toBeString();
    expect($result)->toBe('credit_card');
    expect($result)->not->toBeInstanceOf(PaymentMethodEnum::class);
});

it('rejects PascalCase method names', function () {
    PaymentMethodEnum::CreditCard();
})->throws(BadMethodCallException::class, 'must be in camelCase format');

it('rejects UPPER_SNAKE_CASE method names', function () {
    PaymentMethodEnum::CREDIT_CARD();
})->throws(BadMethodCallException::class, 'must be in camelCase format');

it('rejects snake_case method names', function () {
    PaymentMethodEnum::credit_card();
})->throws(BadMethodCallException::class, 'must be in camelCase format');

it('only accepts camelCase naming convention', function () {
    expect(PaymentMethodEnum::creditCard())->toBe('credit_card');
    expect(PaymentMethodEnum::debitCard())->toBe('debit_card');
    expect(PaymentMethodEnum::paypal())->toBe('paypal');
});

it('can be used in switch statements', function () {
    $method = PaymentMethodEnum::creditCard();

    $result = match ($method) {
        'credit_card' => 'Card payment',
        'paypal' => 'PayPal payment',
        default => 'Unknown',
    };

    expect($result)->toBe('Card payment');
});

it('works with PascalCase enum cases using camelCase method', function () {
    expect(StateEnum::pending())->toBe('pending');
    expect(StateEnum::inProgress())->toBe('in_progress');
    expect(StateEnum::completed())->toBe('completed');
    expect(StateEnum::onHold())->toBe('on_hold');
});

it('rejects PascalCase method names for PascalCase enum', function () {
    StateEnum::InProgress();
})->throws(BadMethodCallException::class, 'must be in camelCase format');

it('rejects UPPER_SNAKE_CASE method names for PascalCase enum', function () {
    StateEnum::IN_PROGRESS();
})->throws(BadMethodCallException::class, 'must be in camelCase format');

it('rejects snake_case method names for PascalCase enum', function () {
    StateEnum::in_progress();
})->throws(BadMethodCallException::class, 'must be in camelCase format');
