<?php

namespace App\Models\Traits;

use App\Support\LogMasksAttribute;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;
use Nette\Utils\Html;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

trait LogsModel
{
    use LogsActivity;

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
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => $this->logDescription($eventName));
    }

    public function logExcept(): array
    {
        return [];
    }

    public static function bootLogsModel(): void
    {
        static::addLogChange(new LogMasksAttribute);
    }

    public function logIncludes(Builder $query): Builder
    {
        return $query;
    }

    public function getLogSubjectName(): string
    {
        return $this->name;
    }

    public function getLogCauserName(): HtmlString
    {
        $user = auth('web')->user();

        return str($user->name ?? __('system'))
            ->wrap($user ? '**' : '*')
            ->inlineMarkdown()
            ->toHtmlString();
    }

    public function logDescription(string $eventName): string
    {
        return __(':subject.type <strong>:subject.name</strong> was <strong>:event</strong> by :causer.name', [
            'subject.type' => str(class_basename(get_class($this)))->headline(),
            'subject.name' => e($this->getLogSubjectName()),
            'event' => $eventName,
            'causer.name' => $this->getLogCauserName(),
        ]);
    }
}
