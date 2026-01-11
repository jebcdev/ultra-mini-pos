<?php

namespace App\Filament\Resources\Qualities\Pages;

use App\Filament\Resources\Qualities\QualityResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewQuality extends ViewRecord
{
    protected static string $resource = QualityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
