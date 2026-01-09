<?php

namespace App\Filament\Resources\Cities\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;

class CityForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('City Information'))
                    ->icon(Heroicon::MapPin)
                    ->columns(1)
                    ->description(__('Enter the city details.'))
                    ->columnSpanFull()
                    ->schema([
                        Select::make('department_id')
                            ->relationship('department', 'name')
                            ->label(__('Department'))
                            ->required()
                            ->preloadSearchable()
                            ->suffixIcon(Heroicon::BuildingOffice)
                            ->suffixIconColor(Color::Blue),

                        TextInput::make('name')
                            ->label(__('Name'))
                            ->required()
                            ->suffixIcon(Heroicon::MapPin)
                            ->suffixIconColor(Color::Blue),
                    ]),
            ]);
    }
}
