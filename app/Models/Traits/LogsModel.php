<?php

namespace App\Models\Traits;

use App\Support\LogMasksAttribute;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
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

    protected function getLogCauserName(): string
    {
        if ($user = auth('web')->user()) {
            return $user->name;
        }

        return __('System');
    }

    protected function getLogFirstSubject(): Model
    {
        return $this;
    }

    protected function getLogFirstSubjectName(): string
    {
        return $this->name;
    }

    protected function getLogSecondSubject(): Model
    {
        return $this;
    }

    protected function getLogSecondSubjectName(): string
    {
        return $this->name;
    }

    protected function logDescription(string $eventName): string
    {
        return __(':subject.type :subject.name was :event by :causer.name', [
            'subject.type' => str(class_basename($this))->headline(),
            'subject.name' => str(e($this->getLogSubjectName()))->wrapHtmlTag('strong'),
            'event' => str($eventName)->wrapHtmlTag('em')->wrapHtmlTag('strong'),
            'causer.name' => str(e($this->getLogCauserName()))->wrapHtmlTag('strong'),
        ]);
    }

    protected function logDescriptionPivot(string $eventName): string
    {
        return __(':subject.first.type :subject.first.name was :event :to :subject.second.type :subject.second.name by :causer.name', [
            'subject.first.type' => str(class_basename($this->getLogFirstSubject()))->headline(),
            'subject.first.name' => str(e($this->getLogFirstSubjectName()))->wrapHtmlTag('strong'),
            'event' => str(match ($eventName) {
                'created' => __('attached'),
                'deleted' => __('detached'),
                default => $eventName,
            })->wrapHtmlTag('em')->wrapHtmlTag('strong'),
            'to' => match ($eventName) {
                'created' => __('to'),
                'deleted' => __('from'),
                default => __('for'),
            },
            'subject.second.type' => str(class_basename($this->getLogSecondSubject()))->headline(),
            'subject.second.name' => str(e($this->getLogSecondSubjectName()))->wrapHtmlTag('strong'),
            'causer.name' => str(e($this->getLogCauserName()))->wrapHtmlTag('strong'),
        ]);
    }
}
