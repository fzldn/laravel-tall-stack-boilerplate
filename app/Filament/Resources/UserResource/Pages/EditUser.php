<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\ActivityResource;
use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            ActivityResource\Widgets\ModelActivity::make(['causer' => $this->record]),
            ActivityResource\Widgets\ModelActivity::make(['subject' => $this->record]),
        ];
    }
}
