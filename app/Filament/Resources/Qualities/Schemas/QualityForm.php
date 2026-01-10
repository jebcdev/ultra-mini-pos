<?php

namespace App\Filament\Resources\Qualities\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;

class QualityForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Quality Information'))
                    ->icon(Heroicon::Star)
                    ->columns(1)
                    ->description(__('Enter the quality details.'))
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('name')
                            ->label(__('Name'))
                            ->required()
                            ->suffixIcon(Heroicon::Star)
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
                            ->directory('qualities'),
                    ]),
            ]);
    }
}
