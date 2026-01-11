<?php

namespace App\Filament\Resources\Users\Tables;

use App\Filament\Resources\Users\UserResource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        $table
            ->columns([
                TextColumn::make('role')
                    ->label(__('Role'))
                    ->formatStateUsing(fn ($state) => ($state = __($state)))
                    ->searchableAndSortable(),

                TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchableAndSortable(),

                TextColumn::make('email')
                    ->label(__('Email address'))
                    ->searchableAndSortable(),

                TextColumn::make('email_verified_at')
                    ->label(__('Email Verified At'))
                    ->dateTime()
                    ->searchableAndSortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label(__('Created At'))
                    ->dateTime()
                    ->searchableAndSortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label(__('Updated At'))
                    ->dateTime()
                    ->searchableAndSortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('deleted_at')
                    ->label(__('Deleted At'))
                    ->dateTime()
                    ->searchableAndSortable()
                    ->toggleable(isToggledHiddenByDefault: true),

            ]);

        return \App\Filament\Helpers\TableConfigHelper::applyStandardConfig($table, UserResource::class);
    }
}
