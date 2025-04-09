<?php

namespace App\Filament\Resources\ContestantResource\Pages;

use App\Filament\Resources\ContestantResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListContestants extends ListRecords
{
    protected static string $resource = ContestantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
