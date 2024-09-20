<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\ActivityResource;
use App\Filament\Resources\RoleResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewRole extends ViewRecord
{
    protected static string $resource = RoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            ActivityResource\Widgets\ModelActivity::make(['subject' => $this->record]),
        ];
    }
}
