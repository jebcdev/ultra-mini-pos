<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\User;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Role Information'))
                    ->icon(Heroicon::UserGroup)
                    ->description(__('Current user role in the system.'))
                    ->columnSpanFull()
                    ->collapsible()
                    ->schema([
                        TextEntry::make('role')
                            ->label(__('Role'))
                            ->badge()
                            ->formatStateUsing(fn($state)=>($state=__($state)))
                            ->icon(Heroicon::ShieldCheck)

                            ->color(Color::Blue),
                    ]),

                Section::make(__('Basic Information'))
                    ->icon(Heroicon::User)
                    ->description(__('User\'s basic details and account information.'))
                    ->columns(2)
                    ->columnSpanFull()
                    ->collapsible()
                    ->schema([
                        TextEntry::make('name')
                            ->label(__('Name'))
                            ->icon(Heroicon::User)
                            ->color(Color::Blue),

                        TextEntry::make('email')
                            ->label(__('Email address'))
                            ->icon(Heroicon::AtSymbol)
                            ->color(Color::Blue),

                        TextEntry::make('email_verified_at')
                            ->label(__('Email Verified At'))
                            ->dateTime()
                            ->placeholder(__('Not verified'))
                            ->columnSpanFull()
                            ->icon(Heroicon::CheckCircle)
                            ->color(Color::Green),
                    ]),

                \App\Filament\Helpers\AuditSectionHelper::make(User::class),
            ]);
    }
}
