<?php

namespace Jenishev\Laravel\Support\Tests\Unit\Enums\Concerns;

use Jenishev\Laravel\Support\Enums\Concerns\HasLabel;

enum StatusEnum: string
{
    use HasLabel;

    case Pending = 'pending';
    case Approved = 'approved';
    case Rejected = 'rejected';
    case InProgress = 'in_progress';
    case OnHold = 'on_hold';
    case Completed = 'COMPLETED';
    case VOID = 'void';
}

it('returns label for single word case', function () {
    expect(StatusEnum::Pending->label())->toBe('Pending');
});

it('returns label for approved case', function () {
    expect(StatusEnum::Approved->label())->toBe('Approved');
});

it('returns label for completed case', function () {
    expect(StatusEnum::Completed->label())->toBe('Completed');
});

it('returns label for void case', function () {
    expect(StatusEnum::VOID->label())->toBe('Void');
});

it('converts snake_case to headline', function () {
    expect(StatusEnum::InProgress->label())->toBe('In Progress');
});

it('converts snake_case with multiple words', function () {
    expect(StatusEnum::OnHold->label())->toBe('On Hold');
});

it('returns translated label', function () {
    // The label() method uses __() for translation
    $label = StatusEnum::Pending->label();

    expect($label)->toBeString();
});

it('handles all enum cases', function () {
    foreach (StatusEnum::cases() as $case) {
        expect($case->label())->toBeString();
        expect($case->label())->not->toBeEmpty();
    }
});

it('converts underscores to spaces', function () {
    expect(StatusEnum::InProgress->label())->toContain(' ');
    expect(StatusEnum::InProgress->label())->not->toContain('_');
});

it('capitalizes each word', function () {
    expect(StatusEnum::InProgress->label())->toBe('In Progress');
    expect(StatusEnum::OnHold->label())->toBe('On Hold');
});
