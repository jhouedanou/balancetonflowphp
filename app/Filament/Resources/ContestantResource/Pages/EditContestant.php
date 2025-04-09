<?php

namespace App\Filament\Resources\ContestantResource\Pages;

use App\Filament\Resources\ContestantResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditContestant extends EditRecord
{
    protected static string $resource = ContestantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
