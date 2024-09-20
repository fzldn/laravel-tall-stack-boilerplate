<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;

class MacrosServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Str::macro('wrapHtmlTag', function (string $string, string $tag = 'span', array $attributes = []): string {
            $attrString = '';

            if (!empty($attributes)) {
                foreach ($attributes as $key => $value) {
                    $attrString .= " {$key}=\"{$value}\"";
                }
            }

            return "<{$tag}{$attrString}>{$string}</{$tag}>";
        });

        Stringable::macro('wrapHtmlTag', function (string $tag = 'span', array $attributes = []): Stringable {
            return new Stringable(Str::wrapHtmlTag((string) $this, $tag, $attributes));
        });
    }
}
