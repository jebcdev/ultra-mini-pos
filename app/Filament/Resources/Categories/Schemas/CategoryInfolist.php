<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Models\Category;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;

class CategoryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Category Information'))
                    ->icon(Heroicon::Tag)
                    ->description(__('Category details.'))
                    ->columns(2)
                    ->columnSpanFull()
                    ->collapsible()
                    ->schema([
                        ImageEntry::make('image')
                            ->label(__('Image'))
                            ->disk('public')
                            ->columnSpanFull()
                            ->placeholder('-'),

                        TextEntry::make('name')
                            ->label(__('Name'))
                            ->icon(Heroicon::Tag)
                            ->color(Color::Blue),

                        TextEntry::make('description')
                            ->label(__('Description'))
                            ->icon(Heroicon::DocumentText)
                            ->color(Color::Blue)
                            ->placeholder('-'),

                        TextEntry::make('slug')
                            ->label(__('Slug'))
                            ->icon(Heroicon::Link)
                            ->color(Color::Blue),

                    ]),

                \App\Filament\Helpers\AuditSectionHelper::make(Category::class),
            ]);
    }
}
