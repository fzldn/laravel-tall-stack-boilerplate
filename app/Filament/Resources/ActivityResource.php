<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityResource\Pages;
use App\Filament\Resources\ActivityResource\RelationManagers;
use App\Models\Activity;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ActivityResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static ?string $navigationIcon = 'heroicon-o-bars-arrow-down';

    protected static ?int $navigationSort = 2;

    public static function getNavigationGroup(): ?string
    {
        return __('Access Management');
    }

    public static function table(Table $table): Table
    {
        return $table
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
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('causer_id')
                    ->label(__('Causer'))
                    ->options(
                        User::orderBy('name')->pluck('name', 'id')
                    )
                    ->searchable(),
                Tables\Filters\Filter::make('from')
                    ->form([Forms\Components\DateTimePicker::make('from')])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn(Builder $query, $date): Builder => $query->where('created_at', '>=', $date),
                            );
                    })
                    ->indicateUsing(function ($data) {
                        if (! $data['from']) {
                            return null;
                        }

                        return __('From: :date', ['date' => $data['from']]);
                    }),
                Tables\Filters\Filter::make('to')
                    ->form([Forms\Components\DateTimePicker::make('to')])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['to'],
                                fn(Builder $query, $date): Builder => $query->where('created_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function ($data) {
                        if (! $data['to']) {
                            return null;
                        }

                        return __('To: :date', ['date' => $data['to']]);
                    }),
            ], layout: Tables\Enums\FiltersLayout::AboveContent);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivities::route('/'),
        ];
    }
}
