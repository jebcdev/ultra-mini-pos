<?php

namespace App\Filament\Resources\Departments\Schemas;

use App\Models\Department;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;

class DepartmentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Department Information'))
                    ->icon(Heroicon::BuildingOffice)
                    ->description(__('Department details.'))
                    ->columns(1)
                    ->columnSpanFull()
                    ->collapsible()
                    ->schema([
                        TextEntry::make('name')
                            ->label(__('Name'))
                            ->icon(Heroicon::Tag)
                            ->color(Color::Blue),
                    ]),

                \App\Filament\Helpers\AuditSectionHelper::make(Department::class),
            ]);
    }
}
