<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Enums\Role;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;

class RolesRelationManager extends RelationManager
{
    protected static string $relationship = 'roles';

    public function table(Table $table): Table
    {
        /** @var \App\Models\User */
        $authUser = auth('web')->user();

        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('permissions_count')
                    ->counts('permissions')
                    ->label(__('Permissions'))
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->multiple()
                    ->recordSelectOptionsQuery(function (Builder $query) use ($authUser) {
                        if (!$authUser->isSuperAdmin()) {
                            $query->where('name', '!=', Role::SUPER_ADMIN);
                        }
                    }),
            ])
            ->actions([
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make()->using(function (Collection $records, Table $table) {
                        /** @var BelongsToMany $relationship */
                        $relationship = $table->getRelationship();

                        if ($table->allowsDuplicates()) {
                            $records->each(
                                fn(Model $record) => $record->{$relationship->getPivotAccessor()}->delete(),
                            );
                        } else {
                            $records = $records->filter(function ($record) {
                                $canDetach = Gate::check('detach', $record);

                                if (!$canDetach) {
                                    Notification::make()
                                        ->title(__('Permission Denied'))
                                        ->body(__('You do not have permission to detach :name.', ['name' => $record->name]))
                                        ->danger()
                                        ->persistent()
                                        ->send();
                                }

                                return $canDetach;
                            });

                            $relationship->detach($records);
                        }
                    }),
                ]),
            ]);
    }
}
