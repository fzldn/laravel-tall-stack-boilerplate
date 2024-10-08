<?php

namespace App\Support;

use Spatie\Activitylog\Contracts\LoggablePipe;
use Spatie\Activitylog\EventLogBag;

class LogMasksAttribute implements LoggablePipe
{
    protected array $attributes = ['password'];

    public function handle(EventLogBag $event, \Closure $next): EventLogBag
    {
        collect($this->attributes)
            ->each(function (string $attribute) use ($event) {
                if (data_get($event->changes, "attributes.{$attribute}")) {
                    data_set($event->changes, "attributes.{$attribute}", '********');
                }

                if (data_get($event->changes, "old.{$attribute}")) {
                    data_set($event->changes, "old.{$attribute}", '********');
                }
            });

        return $next($event);
    }
}
