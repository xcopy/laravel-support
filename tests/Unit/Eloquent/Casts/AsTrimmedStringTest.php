<?php

namespace Jenishev\Laravel\Support\Tests\Unit\Eloquent\Casts;

use Illuminate\Database\Eloquent\Model;
use Jenishev\Laravel\Support\Eloquent\Casts\AsTrimmedString;

beforeEach(function () {
    $this->model = new class extends Model
    {
        protected $fillable = ['name'];

        protected function casts(): array
        {
            return [
                'name' => AsTrimmedString::class,
            ];
        }
    };
});

it('trims leading whitespace from string', function () {
    $this->model->name = '  John Doe';

    expect($this->model->name)->toBe('John Doe');
});

it('trims trailing whitespace from string', function () {
    $this->model->name = 'John Doe  ';

    expect($this->model->name)->toBe('John Doe');
});

it('trims both leading and trailing whitespace', function () {
    $this->model->name = '  John Doe  ';

    expect($this->model->name)->toBe('John Doe');
});

it('trims tabs and newlines', function () {
    $this->model->name = "\t\nJohn Doe\n\t";

    expect($this->model->name)->toBe('John Doe');
});

it('preserves internal whitespace', function () {
    $this->model->name = '  John   Doe  ';

    expect($this->model->name)->toBe('John   Doe');
});

it('returns null for empty string', function () {
    $this->model->name = '';

    expect($this->model->name)->toBeNull();
});

it('returns null for whitespace-only string', function () {
    $this->model->name = '   ';

    expect($this->model->name)->toBeNull();
});

it('handles already trimmed strings', function () {
    $this->model->name = 'John Doe';

    expect($this->model->name)->toBe('John Doe');
});

it('handles single character strings', function () {
    $this->model->name = ' A ';

    expect($this->model->name)->toBe('A');
});

it('handles unicode whitespace', function () {
    $this->model->name = "\u{00A0}John Doe\u{00A0}";

    expect($this->model->name)->toBe('John Doe');
});

it('handles very long strings with whitespace', function () {
    $longString = '  ' . str_repeat('Lorem ipsum dolor sit amet ', 1000) . '  ';
    $this->model->name = $longString;

    expect($this->model->name)->not->toStartWith(' ')
        ->and($this->model->name)->not->toEndWith(' ');
});

it('preserves value in database format', function () {
    $this->model->name = '  John Doe  ';
    $this->model->syncOriginal();

    expect($this->model->getAttributes()['name'])->toBe('John Doe');
});

// Null return cases
it('returns null for null value', function () {
    $this->model->name = null;

    expect($this->model->name)->toBeNull();
});

it('returns null for boolean false', function () {
    $this->model->name = false;

    expect($this->model->name)->toBeNull();
});

it('returns null for integer zero', function () {
    $this->model->name = 0;

    expect($this->model->name)->toBeNull();
});

it('returns null for arrays', function () {
    $this->model->name = ['test'];

    expect($this->model->name)->toBeNull();
});

it('returns null for objects without __toString', function () {
    $object = new class
    {
        public $property = 'value';
    };

    $this->model->name = $object;

    expect($this->model->name)->toBeNull();
});

// Type conversion cases
it('converts integer to string', function () {
    $this->model->name = 123;

    expect($this->model->name)->toBe('123');
});

it('converts float to string', function () {
    $this->model->name = 45.67;

    expect($this->model->name)->toBe('45.67');
});

it('converts boolean true to string', function () {
    $this->model->name = true;

    expect($this->model->name)->toBe('1');
});

it('preserves string zero', function () {
    $this->model->name = '0';

    expect($this->model->name)->toBe('0');
});

it('converts object with __toString to string', function () {
    $object = new class
    {
        public function __toString(): string
        {
            return '  Object Value  ';
        }
    };

    $this->model->name = $object;

    expect($this->model->name)->toBe('Object Value');
});

it('handles multiple types of whitespace', function () {
    $this->model->name = " \t\n\r\x0B  John Doe  \t\n\r\x0B ";

    expect($this->model->name)->toBe('John Doe');
});

it('handles carriage returns', function () {
    $this->model->name = "\rJohn Doe\r";

    expect($this->model->name)->toBe('John Doe');
});

it('handles vertical tabs', function () {
    $this->model->name = "\x0BJohn Doe\x0B";

    expect($this->model->name)->toBe('John Doe');
});

it('handles form feeds', function () {
    $this->model->name = "\fJohn Doe\f";

    expect($this->model->name)->toBe('John Doe');
});

it('returns null for empty array', function () {
    $this->model->name = [];

    expect($this->model->name)->toBeNull();
});

it('handles numeric zero string after trim', function () {
    $this->model->name = ' 0 ';

    expect($this->model->name)->toBe('0');
});

it('handles special characters with spaces', function () {
    $this->model->name = '  @#$%  ';

    expect($this->model->name)->toBe('@#$%');
});

it('handles emoji with spaces', function () {
    $this->model->name = '  😀 Hello  ';

    expect($this->model->name)->toBe('😀 Hello');
});

it('handles multiline strings', function () {
    $this->model->name = "  Line 1\nLine 2\nLine 3  ";

    expect($this->model->name)->toBe("Line 1\nLine 2\nLine 3");
});

it('handles strings with only tabs', function () {
    $this->model->name = "\t\t\t";

    expect($this->model->name)->toBeNull();
});

it('handles mixed unicode and ascii whitespace', function () {
    $this->model->name = " \t\u{00A0}\u{2000}John\u{2000}\u{00A0}\t ";

    expect($this->model->name)->toBe('John');
});

it('handles negative numbers', function () {
    $this->model->name = -123;

    expect($this->model->name)->toBe('-123');
});

it('handles scientific notation', function () {
    $this->model->name = 1.23e10;

    expect($this->model->name)->toBe('12300000000');
});

it('handles string with only spaces between words', function () {
    $this->model->name = 'John     Doe';

    expect($this->model->name)->toBe('John     Doe');
});

it('handles zero width space', function () {
    $this->model->name = "\u{200B}John Doe\u{200B}";

    expect($this->model->name)->toBe('John Doe');
});

it('handles non-breaking space', function () {
    $this->model->name = "\u{00A0}\u{00A0}John Doe\u{00A0}\u{00A0}";

    expect($this->model->name)->toBe('John Doe');
});

it('handles thin space', function () {
    $this->model->name = "\u{2009}John Doe\u{2009}";

    expect($this->model->name)->toBe('John Doe');
});

it('handles hair space', function () {
    $this->model->name = "\u{200A}John Doe\u{200A}";

    expect($this->model->name)->toBe('John Doe');
});

it('handles en space', function () {
    $this->model->name = "\u{2002}John Doe\u{2002}";

    expect($this->model->name)->toBe('John Doe');
});

it('handles em space', function () {
    $this->model->name = "\u{2003}John Doe\u{2003}";

    expect($this->model->name)->toBe('John Doe');
});

it('handles only newlines', function () {
    $this->model->name = "\n\n\n";

    expect($this->model->name)->toBeNull();
});

it('handles only carriage returns', function () {
    $this->model->name = "\r\r\r";

    expect($this->model->name)->toBeNull();
});

it('handles windows line endings', function () {
    $this->model->name = "\r\nJohn Doe\r\n";

    expect($this->model->name)->toBe('John Doe');
});

it('preserves internal tabs', function () {
    $this->model->name = "  John\tDoe  ";

    expect($this->model->name)->toBe("John\tDoe");
});

it('preserves internal newlines', function () {
    $this->model->name = "  John\nDoe  ";

    expect($this->model->name)->toBe("John\nDoe");
});

it('handles string with only zero', function () {
    $this->model->name = '0';

    expect($this->model->name)->toBe('0');
});

it('handles string with padded zero', function () {
    $this->model->name = '  0  ';

    expect($this->model->name)->toBe('0');
});

it('handles empty string after whitespace trim', function () {
    $this->model->name = " \t\n\r\x0B\f ";

    expect($this->model->name)->toBeNull();
});

it('handles stringable object', function () {
    $stringable = new class
    {
        public function __toString(): string
        {
            return '  Stringable  ';
        }
    };

    $this->model->name = $stringable;

    expect($this->model->name)->toBe('Stringable');
});

it('handles object returning empty string', function () {
    $stringable = new class
    {
        public function __toString(): string
        {
            return '   ';
        }
    };

    $this->model->name = $stringable;

    expect($this->model->name)->toBeNull();
});

it('handles HTML with spaces', function () {
    $this->model->name = '  <div>Content</div>  ';

    expect($this->model->name)->toBe('<div>Content</div>');
});

it('handles JSON string with spaces', function () {
    $this->model->name = '  {"key":"value"}  ';

    expect($this->model->name)->toBe('{"key":"value"}');
});

it('handles URL with spaces', function () {
    $this->model->name = '  https://example.com  ';

    expect($this->model->name)->toBe('https://example.com');
});

it('handles email with spaces', function () {
    $this->model->name = '  test@example.com  ';

    expect($this->model->name)->toBe('test@example.com');
});

it('handles phone number with spaces', function () {
    $this->model->name = '  +1-234-567-8900  ';

    expect($this->model->name)->toBe('+1-234-567-8900');
});

it('handles UUID with spaces', function () {
    $this->model->name = '  550e8400-e29b-41d4-a716-446655440000  ';

    expect($this->model->name)->toBe('550e8400-e29b-41d4-a716-446655440000');
});

it('handles base64 string with spaces', function () {
    $this->model->name = '  SGVsbG8gV29ybGQ=  ';

    expect($this->model->name)->toBe('SGVsbG8gV29ybGQ=');
});

it('handles string with quotes', function () {
    $this->model->name = '  "Hello World"  ';

    expect($this->model->name)->toBe('"Hello World"');
});

it('handles string with single quotes', function () {
    $this->model->name = "  'Hello World'  ";

    expect($this->model->name)->toBe("'Hello World'");
});

it('handles string with backticks', function () {
    $this->model->name = '  `Hello World`  ';

    expect($this->model->name)->toBe('`Hello World`');
});

it('handles SQL query with spaces', function () {
    $this->model->name = '  SELECT * FROM users  ';

    expect($this->model->name)->toBe('SELECT * FROM users');
});

it('handles file path with spaces', function () {
    $this->model->name = '  /path/to/file.txt  ';

    expect($this->model->name)->toBe('/path/to/file.txt');
});

it('handles windows path with spaces', function () {
    $this->model->name = '  C:\\path\\to\\file.txt  ';

    expect($this->model->name)->toBe('C:\\path\\to\\file.txt');
});

it('handles multiple models with same cast', function () {
    $model1 = clone $this->model;
    $model2 = clone $this->model;

    $model1->name = '  Name 1  ';
    $model2->name = '  Name 2  ';

    expect($model1->name)->toBe('Name 1')
        ->and($model2->name)->toBe('Name 2');
});

it('handles reassignment', function () {
    $this->model->name = '  First Value  ';
    expect($this->model->name)->toBe('First Value');

    $this->model->name = '  Second Value  ';
    expect($this->model->name)->toBe('Second Value');
});

it('handles empty string after object toString', function () {
    $object = new class
    {
        public function __toString(): string
        {
            return '';
        }
    };

    $this->model->name = $object;

    expect($this->model->name)->toBeNull();
});

it('handles float with trailing zeros', function () {
    $this->model->name = 123.4500;

    expect($this->model->name)->toBe('123.45');
});

it('handles very large numbers', function () {
    $this->model->name = PHP_INT_MAX;

    expect($this->model->name)->toBe((string) PHP_INT_MAX);
});

it('handles very small numbers', function () {
    $this->model->name = PHP_INT_MIN;

    expect($this->model->name)->toBe((string) PHP_INT_MIN);
});

it('handles infinity', function () {
    $this->model->name = INF;

    expect($this->model->name)->toBe('INF');
});

it('handles negative infinity', function () {
    $this->model->name = -INF;

    expect($this->model->name)->toBe('-INF');
});
