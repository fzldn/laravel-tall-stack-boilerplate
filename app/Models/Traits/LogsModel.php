<?php

namespace App\Models\Traits;

use App\Support\LogMasksAttribute;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\HtmlString;
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
            ->setDescriptionForEvent(fn(string $eventName) => $this instanceof Pivot ? $this->logDescriptionPivot($eventName) : $this->logDescription($eventName));
    }

    protected function logExcept(): array
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

    protected function getLogSubjectName(): string
    {
        return $this->name;
    }

    protected function getLogCauserName(): HtmlString
    {
        $user = auth('web')->user();

        return str($user->name ?? __('system'))
            ->wrap($user ? '**' : '*')
            ->inlineMarkdown()
            ->toHtmlString();
    }

    protected function getLogFirstSubject(): object
    {
        return $this;
    }

    protected function getLogFirstSubjectName(): string
    {
        return $this->name;
    }

    protected function getLogSecondSubject(): object
    {
        return $this;
    }

    protected function getLogSecondSubjectName(): string
    {
        return $this->name;
    }

    protected function logDescription(string $eventName): string
    {
        return __(':subject.type <strong>:subject.name</strong> was <strong>:event</strong> by :causer.name', [
            'subject.type' => str(class_basename(get_class($this)))->headline(),
            'subject.name' => e($this->getLogSubjectName()),
            'event' => $eventName,
            'causer.name' => $this->getLogCauserName(),
        ]);
    }

    protected function logDescriptionPivot(string $eventName): string
    {
        return __(':subject.first.type <strong>:subject.first.name</strong> was <strong>:event</strong> :to :subject.second.type <strong>:subject.second.name</strong> by :causer.name', [
            'subject.first.type' => str(class_basename(get_class($this->getLogFirstSubject())))->headline(),
            'subject.first.name' => e($this->getLogFirstSubjectName()),
            'event' => match ($eventName) {
                'created' => __('attached'),
                'deleted' => __('detached'),
                default => $eventName,
            },
            'to' => match ($eventName) {
                'created' => __('to'),
                'deleted' => __('from'),
                default => __('for'),
            },
            'subject.second.type' => str(class_basename(get_class($this->getLogSecondSubject())))->headline(),
            'subject.second.name' => e($this->getLogSecondSubjectName()),
            'causer.name' => $this->getLogCauserName(),
        ]);
    }
}
