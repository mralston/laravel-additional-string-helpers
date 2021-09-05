<?php

namespace MRalston\LaravelAdditionalStringHelpers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;
use League\CommonMark\GithubFlavoredMarkdownConverter;

class ServiceProvider extends BaseServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        Str::macro('markdown', function ($content) {
            $converter = new GithubFlavoredMarkdownConverter([
                'html_input' => 'strip',
                'allow_unsafe_links' => false,
            ]);

            return $converter->convertToHtml($content);
        });

        Str::macro('pluralPhrase', function ($value) {
            $words = explode(' ', $value);

            if (count($words) > 1) {
                $lastWord = array_pop($words);

                return implode(' ', $words).' '.self::plural($lastWord);
            } else {
                return self::plural($words[0]);
            }
        });

        Stringable::macro('pluralPhrase', function () {
            return new static(Str::pluralPhrase($this->value));
        });

        Str::macro('humanise', function ($value) {
            return self::of($value)
                ->kebab()
                ->replace(['-', '_'], ' ');
        });

        Stringable::macro('humanise', function () {
            return new static(Str::humanise($this->value));
        });
    }
}