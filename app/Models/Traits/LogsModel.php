<?php

namespace App\Models\Traits;

use App\Support\LogMasksAttributes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

trait LogsModel
{
    use LogsActivity;

    protected static array $maskedAttributes = ['password'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logExcept(
                collect($this->logExcept())
                    ->add($this->getKeyName())
                    ->when($this->usesTimestamps(), fn($collection) => $collection->add($this->getCreatedAtColumn())->add($this->getUpdatedAtColumn()))
                    ->toArray()
            )
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function logExcept(): array
    {
        return [];
    }

    public static function bootLogsModel(): void
    {
        static::addLogChange(new LogMasksAttributes(static::$maskedAttributes));
    }
}
