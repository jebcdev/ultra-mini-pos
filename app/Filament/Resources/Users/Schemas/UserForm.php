<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\Role;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Role Information'))
                    ->icon(Heroicon::UserGroup)
                    ->columns(2)
                    ->description(__('Assign the user\'s role in the system.'))
                    ->columnSpanFull()
                    ->schema([

                        Select::make('role')
                            ->label(__('Role'))
                            ->options(
                                collect(
                                    Role::cases()
                                )->mapWithKeys(fn ($role) => [
                                    $role->value => __($role->name),
                                ])->toArray()
                            )
                            ->preloadSearchable()
                            ->suffixIcon(Heroicon::ShieldCheck)
                            ->suffixIconColor(Color::Blue)
                            ->default(Role::user->value),
                    ]),

                Section::make(__('Basic Information'))
                    ->icon(Heroicon::User)
                    // Divide los campos internos en 2 columnas
                    ->columns(2)
                    ->description(__('Enter the user\'s basic details including name, email, and password.'))
                    ->columnSpanFull()
                    ->schema([

                        TextInput::make('name')
                            ->label(__('Name'))
                            ->required()
                            ->suffixIcon(Heroicon::User)
                            ->suffixIconColor(Color::Blue),

                        TextInput::make('email')
                            ->label(__('Email address'))
                            ->email()
                            ->required()
                            ->suffixIcon(Heroicon::AtSymbol)
                            ->suffixIconColor(Color::Blue),

                        TextInput::make('password')
                            ->label(__('Password'))
                            ->password()
                            ->columnSpanFull()
                            ->required()
                            ->suffixIcon(Heroicon::Key)
                            ->suffixIconColor(Color::Blue),
                    ]),

            ]);
    }
}
