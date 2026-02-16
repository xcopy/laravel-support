<?php

namespace Jenishev\Laravel\Support\Tests\Unit\Eloquent\Casts;

use Illuminate\Database\Eloquent\Model;
use Jenishev\Laravel\Support\Eloquent\Casts\AsTitledString;

beforeEach(function () {
    $this->model = new class extends Model
    {
        protected $fillable = ['name'];

        protected function casts(): array
        {
            return [
                'name' => AsTitledString::class,
            ];
        }
    };
});

it('converts to title case', function () {
    $this->model->name = 'john doe';

    expect($this->model->name)->toBe('John Doe');
});

it('trims and converts to title case', function () {
    $this->model->name = '  john doe  ';

    expect($this->model->name)->toBe('John Doe');
});

it('handles already titled strings', function () {
    $this->model->name = 'John Doe';

    expect($this->model->name)->toBe('John Doe');
});

it('handles all lowercase', function () {
    $this->model->name = 'mary jane watson';

    expect($this->model->name)->toBe('Mary Jane Watson');
});

it('handles all uppercase', function () {
    $this->model->name = 'JOHN DOE';

    expect($this->model->name)->toBe('John Doe');
});

it('handles mixed case', function () {
    $this->model->name = 'jOhN dOe';

    expect($this->model->name)->toBe('John Doe');
});

it('returns null for empty string', function () {
    $this->model->name = '';

    expect($this->model->name)->toBeNull();
});

it('returns null for null value', function () {
    $this->model->name = null;

    expect($this->model->name)->toBeNull();
});

it('returns null for whitespace-only string', function () {
    $this->model->name = '   ';

    expect($this->model->name)->toBeNull();
});

it('handles single word', function () {
    $this->model->name = 'john';

    expect($this->model->name)->toBe('John');
});

it('handles hyphenated names', function () {
    $this->model->name = 'jean-claude van damme';

    expect($this->model->name)->toBe('Jean-Claude Van Damme');
});

it('handles names with apostrophes', function () {
    $this->model->name = "o'connor";

    expect($this->model->name)->toBe("O'connor");
});

it('handles names with apostrophes in uppercase', function () {
    $this->model->name = "O'CONNOR";

    expect($this->model->name)->toBe("O'connor");
});

it('handles names with periods', function () {
    $this->model->name = 'dr. john doe';

    expect($this->model->name)->toBe('Dr. John Doe');
});

it('handles multiple spaces', function () {
    $this->model->name = 'john   doe';

    expect($this->model->name)->toBe('John   Doe');
});

it('handles city names', function () {
    $this->model->name = 'new york city';

    expect($this->model->name)->toBe('New York City');
});

it('handles names with numbers', function () {
    $this->model->name = 'john doe 2nd';

    expect($this->model->name)->toBe('John Doe 2nd');
});

it('handles ordinal numbers correctly', function () {
    $this->model->name = 'henry viii';

    expect($this->model->name)->toBe('Henry Viii');
});

it('does not capitalize after numbers', function () {
    $this->model->name = 'room 42b';

    expect($this->model->name)->toBe('Room 42b');
});

it('handles unicode characters', function () {
    $this->model->name = 'josé garcía';

    expect($this->model->name)->toBe('José García');
});

it('handles tabs and newlines in input', function () {
    $this->model->name = "\tjohn\tdoe\n";

    expect($this->model->name)->toBe('John Doe');
});

it('preserves value in database format', function () {
    $this->model->name = 'JOHN DOE';
    $this->model->syncOriginal();

    expect($this->model->getAttributes()['name'])->toBe('John Doe');
});

it('handles false value as null', function () {
    $this->model->name = false;

    expect($this->model->name)->toBeNull();
});

it('handles zero as null', function () {
    $this->model->name = 0;

    expect($this->model->name)->toBeNull();
});

it('converts array to null', function () {
    $this->model->name = ['John Doe'];

    expect($this->model->name)->toBeNull();
});

it('converts object without toString to null', function () {
    $this->model->name = new class {};

    expect($this->model->name)->toBeNull();
});

it('handles string with leading hyphen', function () {
    $this->model->name = '-john doe';

    expect($this->model->name)->toBe('-John Doe');
});

it('handles string with trailing hyphen', function () {
    $this->model->name = 'john doe-';

    expect($this->model->name)->toBe('John Doe-');
});

it('handles consecutive hyphens', function () {
    $this->model->name = 'jean--claude';

    expect($this->model->name)->toBe('Jean--Claude');
});

it('handles names with parentheses', function () {
    $this->model->name = 'john (jack) doe';

    expect($this->model->name)->toBe('John (jack) Doe');
});

it('capitalizes after opening parenthesis when followed by space', function () {
    $this->model->name = 'john ( jack) doe';

    expect($this->model->name)->toBe('John ( Jack) Doe');
});

it('handles single character names', function () {
    $this->model->name = 'a b c';

    expect($this->model->name)->toBe('A B C');
});

it('handles mixed tabs and spaces', function () {
    $this->model->name = "john\t \tdoe";

    expect($this->model->name)->toBe('John   Doe');
});

it('handles company names', function () {
    $this->model->name = 'acme corporation inc';

    expect($this->model->name)->toBe('Acme Corporation Inc');
});

it('handles street addresses', function () {
    $this->model->name = '123 main street';

    expect($this->model->name)->toBe('123 Main Street');
});

it('handles names with commas', function () {
    $this->model->name = 'doe, john';

    expect($this->model->name)->toBe('Doe, John');
});

it('handles names with slashes', function () {
    $this->model->name = 'and/or';

    expect($this->model->name)->toBe('And/or');
});

it('handles names with underscores', function () {
    $this->model->name = 'john_doe';

    expect($this->model->name)->toBe('John_doe');
});

it('handles very long strings', function () {
    $this->model->name = 'the quick brown fox jumps over the lazy dog';

    expect($this->model->name)->toBe('The Quick Brown Fox Jumps Over The Lazy Dog');
});

it('handles single letter with space', function () {
    $this->model->name = 'a ';

    expect($this->model->name)->toBe('A');
});

it('handles empty string after trim', function () {
    $this->model->name = "\n\t  \r\n";

    expect($this->model->name)->toBeNull();
});

it('handles names with exclamation marks', function () {
    $this->model->name = 'john doe!';

    expect($this->model->name)->toBe('John Doe!');
});

it('handles names with question marks', function () {
    $this->model->name = 'who is john doe?';

    expect($this->model->name)->toBe('Who Is John Doe?');
});

it('handles names with ampersands', function () {
    $this->model->name = 'john & jane';

    expect($this->model->name)->toBe('John & Jane');
});

it('handles names with colons', function () {
    $this->model->name = 'project: title';

    expect($this->model->name)->toBe('Project: Title');
});

it('handles names with semicolons', function () {
    $this->model->name = 'john; doe';

    expect($this->model->name)->toBe('John; Doe');
});

it('handles multiple consecutive spaces', function () {
    $this->model->name = 'john     doe';

    expect($this->model->name)->toBe('John     Doe');
});

it('handles object with toString method', function () {
    $obj = new class
    {
        public function __toString(): string
        {
            return 'john doe';
        }
    };

    $this->model->name = $obj;

    expect($this->model->name)->toBe('John Doe');
});

it('handles numeric string', function () {
    $this->model->name = '123';

    expect($this->model->name)->toBe('123');
});

it('handles boolean true as string', function () {
    $this->model->name = true;

    expect($this->model->name)->toBe('1');
});

it('handles string zero', function () {
    $this->model->name = '0';

    expect($this->model->name)->toBe('0');
});

it('handles names with brackets', function () {
    $this->model->name = 'john [doe]';

    expect($this->model->name)->toBe('John [doe]');
});

it('handles names with curly braces', function () {
    $this->model->name = 'john {doe}';

    expect($this->model->name)->toBe('John {doe}');
});

it('handles names starting with numbers', function () {
    $this->model->name = '3rd street market';

    expect($this->model->name)->toBe('3rd Street Market');
});

it('does not capitalize after apostrophe', function () {
    $this->model->name = "john's dog";

    expect($this->model->name)->toBe("John's Dog");
});

it('handles double apostrophes', function () {
    $this->model->name = "o''connor";

    expect($this->model->name)->toBe("O''connor");
});

it('handles mixed punctuation', function () {
    $this->model->name = 'john-doe.smith';

    expect($this->model->name)->toBe('John-Doe.smith');
});

it('handles prefix titles', function () {
    $this->model->name = 'mr. john doe';

    expect($this->model->name)->toBe('Mr. John Doe');
});

it('handles suffix titles', function () {
    $this->model->name = 'john doe jr.';

    expect($this->model->name)->toBe('John Doe Jr.');
});

it('handles accented characters', function () {
    $this->model->name = 'françois côté';

    expect($this->model->name)->toBe('François Côté');
});

it('handles german umlauts', function () {
    $this->model->name = 'jürgen müller';

    expect($this->model->name)->toBe('Jürgen Müller');
});

it('handles scandinavian characters', function () {
    $this->model->name = 'øyvind søren';

    expect($this->model->name)->toBe('Øyvind Søren');
});

it('handles cyrillic characters', function () {
    $this->model->name = 'иван иванов';

    expect($this->model->name)->toBe('Иван Иванов');
});

it('handles chinese characters', function () {
    $this->model->name = '张伟';

    expect($this->model->name)->toBe('张伟');
});

it('handles arabic characters', function () {
    $this->model->name = 'محمد أحمد';

    expect($this->model->name)->toBe('محمد أحمد');
});
