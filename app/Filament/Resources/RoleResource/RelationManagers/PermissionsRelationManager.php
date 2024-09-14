<?php

namespace App\Filament\Resources\RoleResource\RelationManagers;

use App\Models\Permission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PermissionsRelationManager extends RelationManager
{
    protected static string $relationship = 'permissions';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitle(fn(Permission $permission) => $permission->label)
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('label')
                    ->label(__('Name')),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->multiple(),
            ])
            ->actions([
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return !$ownerRecord->isSuperAdmin();
    }
}
