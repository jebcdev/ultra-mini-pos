<?php

namespace App\Filament\Resources\Clients\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;

class ClientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Client Information'))
                    ->icon(Heroicon::UserGroup)
                    ->columns(2)
                    ->description(__('Enter the client details.'))
                    ->columnSpanFull()
                    ->schema([
                        Select::make('city_id')
                            ->relationship('city', 'name')
                            ->label(__('City'))
                            ->required()
                            ->preloadSearchable()
                            ->suffixIcon(Heroicon::MapPin)
                            ->suffixIconColor(Color::Blue),

                        TextInput::make('full_name')
                            ->label(__('Full Name'))
                            ->required()
                            ->suffixIcon(Heroicon::User)
                            ->suffixIconColor(Color::Blue),

                        TextInput::make('phone_number')
                            ->label(__('Phone Number'))
                            ->tel()
                            ->required()
                            ->suffixIcon(Heroicon::DevicePhoneMobile)
                            ->suffixIconColor(Color::Blue),

                        TextInput::make('email')
                            ->label(__('Email Address'))
                            ->email()
                            ->required()
                            ->suffixIcon(Heroicon::AtSymbol)
                            ->suffixIconColor(Color::Blue),

                        Textarea::make('address')
                            ->label(__('Address'))
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
