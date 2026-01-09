<?php

namespace App\Filament\Resources\Departments\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;

class DepartmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Department Information'))
                    ->icon(Heroicon::BuildingOffice)
                    ->columns(1)
                    ->description(__('Enter the department details.'))
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('name')
                            ->label(__('Name'))
                            ->required()
                            ->suffixIcon(Heroicon::Tag)
                            ->suffixIconColor(Color::Blue),
                    ]),
            ]);
    }
}
