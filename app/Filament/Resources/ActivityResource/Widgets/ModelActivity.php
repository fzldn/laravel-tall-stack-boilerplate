<?php

namespace App\Filament\Resources\ActivityResource\Widgets;

use App\Models\Activity;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ModelActivity extends BaseWidget
{
    public ?Model $causer = null;
    public ?Model $subject = null;

    public function getHeading(): ?string
    {
        if ($this->causer) {
            return __('User Activity');
        }

        if ($this->subject) {
            return str(class_basename($this->subject))
                ->headline()
                ->append(__(' History'));
        }

        return null;
    }

    public function table(Table $table): Table
    {
        $query = Activity::query()
            ->when($this->causer, function ($q, $causer) {
                $q->causedBy($causer);
            })
            ->when($this->subject, function ($q, $subject) {
                $q->forSubject($subject);
            });

        return $table
            ->heading($this->getHeading())
            ->query($query)
            ->columns([
                Tables\Columns\Layout\Split::make([
                    Tables\Columns\TextColumn::make('description')->html(),
                    Tables\Columns\Layout\Split::make([
                        Tables\Columns\TextColumn::make('created_at')
                            ->since()
                            ->dateTimeTooltip()
                            ->badge()
                            ->color('warning')
                            ->grow(false),
                    ])
                        ->grow(false),
                ])
                    ->from('md'),
                Tables\Columns\Layout\Panel::make([
                    Tables\Columns\ViewColumn::make('properties')
                        ->view('filament.tables.columns.activity-properties'),
                ])
                    ->hidden(fn(Model $model) => (new $model->subject_type) instanceof Pivot)
                    ->extraAttributes(['class' => 'overflow-x-auto'])
                    ->collapsible(),
            ]);
    }
}
