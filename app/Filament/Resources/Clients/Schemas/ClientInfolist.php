<?php

namespace App\Filament\Resources\Clients\Schemas;

use App\Models\Client;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;

class ClientInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Client Information'))
                    ->icon(Heroicon::UserGroup)
                    ->description(__('Client details.'))
                    ->columns(2)
                    ->columnSpanFull()
                    ->collapsible()
                    ->schema([
                        TextEntry::make('city.name')
                            ->label(__('City'))
                            ->icon(Heroicon::MapPin)
                            ->color(Color::Blue),

                        TextEntry::make('full_name')
                            ->label(__('Full Name'))
                            ->icon(Heroicon::User)
                            ->color(Color::Blue),

                        TextEntry::make('phone_number')
                            ->label(__('Phone Number'))
                            ->icon(Heroicon::DevicePhoneMobile)
                            ->color(Color::Blue),

                        TextEntry::make('email')
                            ->label(__('Email Address'))
                            ->icon(Heroicon::AtSymbol)
                            ->color(Color::Blue),

                        TextEntry::make('address')
                            ->label(__('Address'))
                            ->columnSpanFull()
                            ->icon(Heroicon::MapPin)
                            ->color(Color::Blue),
                    ]),

                \App\Filament\Helpers\AuditSectionHelper::make(Client::class),
            ]);
    }
}
