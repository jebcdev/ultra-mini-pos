<?php

namespace App\Filament\Resources\Cities\Schemas;

use App\Models\City;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;

class CityInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('City Information'))
                    ->icon(Heroicon::MapPin)
                    ->description(__('City details.'))
                    ->columns(2)
                    ->columnSpanFull()
                    ->collapsible()
                    ->schema([
                        TextEntry::make('department.name')
                            ->label(__('Department'))
                            ->icon(Heroicon::BuildingOffice)
                            ->color(Color::Blue),

                        TextEntry::make('name')
                            ->label(__('Name'))
                            ->icon(Heroicon::MapPin)
                            ->color(Color::Blue),
                    ]),

                \App\Filament\Helpers\AuditSectionHelper::make(City::class),
            ]);
    }
}
