<?php

namespace MRalston\LaravelAdditionalStringHelpers;

use Illuminate\Support\Facades\Blade;
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
        $this->stringHelpers();
        $this->bladeHelpers();
    }

    private function stringHelpers()
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

        Str::macro('matchAllFull', function ($pattern, $subject) {
            preg_match_all($pattern, $subject, $matches);

            if (empty($matches[0])) {
                return collect();
            }

            return collect([$matches[1], $matches[2]]);
        });

        Stringable::macro('matchAllFull', function ($pattern) {
            return Str::matchAllFull($pattern, $this->value);
        });


        if (!method_exists(Str::class, 'squish')) {
            Str::macro('squish', function ($value) {
                return preg_replace('~(\s|\x{3164}|\x{1160})+~u', ' ', preg_replace('~^[\s\x{FEFF}]+|[\s\x{FEFF}]+$~u', '', $value));
            });
        }

        if (!method_exists(Stringable::class, 'squish')) {
            Stringable::macro('squish', function () {
                return new static(Str::squish($this->value));
            });
        }
    }

    private function bladeHelpers()
    {
        Blade::directive('nl2br', function ($text) {
            return '<?php echo(nl2br(e(' . $text . '))); ?>';
        });
    }
}
