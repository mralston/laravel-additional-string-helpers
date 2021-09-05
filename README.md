# laravel-additional-string-helpers

## Description

Adds additional helpers to Laravel's Str and Stringable helpers.

## Installation

Require Laravel Additional String Helpers using [Composer](https://getcomposer.org):

```bash
composer require mralston/laravel-additional-string-helpers
```

The package will automatically register itself.

## Additional string helpers provided by this package:

- `Str::markdown()`
- `Str::pluralPhrase()`
- `Stringable::pluralPhrase()` usage: `Str::of('the item')->pluralPhrase()`
- `Str::humanise()`
- `Stringable::humanise()` usage: `Str::of('the_item')->humanise()`