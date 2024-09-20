<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\ActivityResource;
use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Actions\Modal\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

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
            ActivityResource\Widgets\ModelActivity::make(['causer' => $this->record]),
            ActivityResource\Widgets\ModelActivity::make(['subject' => $this->record]),
        ];
    }
}
