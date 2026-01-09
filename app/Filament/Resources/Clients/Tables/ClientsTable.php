<?php

namespace App\Filament\Resources\Clients\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ClientsTable
{
    public static function configure(Table $table): Table
    {
        $table
            ->columns([
                TextColumn::make('city.name')
                    ->label(__('City'))
                    ->searchableAndSortable(),

                TextColumn::make('full_name')
                    ->label(__('Full Name'))
                    ->searchableAndSortable(),

                TextColumn::make('phone_number')
                    ->label(__('Phone Number'))
                    ->searchableAndSortable(),

                TextColumn::make('email')
                    ->label(__('Email Address'))
                    ->searchableAndSortable(),

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

        return \App\Filament\Helpers\TableConfigHelper::applyStandardConfig($table, \App\Filament\Resources\Clients\ClientResource::class);
    }
}
