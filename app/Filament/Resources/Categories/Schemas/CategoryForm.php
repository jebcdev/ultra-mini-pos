<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Category Information'))
                    ->icon(Heroicon::Tag)
                    ->columns(1)
                    ->description(__('Enter the category details.'))
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('name')
                            ->label(__('Name'))
                            ->required()
                            ->suffixIcon(Heroicon::Tag)
                            ->suffixIconColor(Color::Blue),

                        TextInput::make('description')
                            ->label(__('Description'))
                            ->suffixIcon(Heroicon::DocumentText)
                            ->suffixIconColor(Color::Blue),

                        TextInput::make('slug')
                            ->label(__('Slug'))
                            ->required()
                            ->suffixIcon(Heroicon::Link)
                            ->suffixIconColor(Color::Blue),

                        FileUpload::make('image')
                            ->label(__('Image'))
                            ->acceptedFileTypes([
                                'image/jpeg',
                                'image/png',
                                'image/gif',
                                'image/svg+xml',
                                'image/webp',
                            ])
                            ->disk('public')
                            ->image()
                            ->maxSize(2024)
                            ->uploadingMessage(__('Uploading...'))
                            ->directory('categories'),
                    ]),
            ]);
    }
}
