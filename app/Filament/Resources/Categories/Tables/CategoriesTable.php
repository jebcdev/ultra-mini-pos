<?php

namespace App\Filament\Resources\Categories\Tables;

use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchableAndSortable(),

                TextColumn::make('description')
                    ->label(__('Description'))
                    ->searchableAndSortable(),

                TextColumn::make('slug')
                    ->label(__('Slug'))
                    ->searchableAndSortable(),

                ImageColumn::make('image')
                    ->label(__('Image'))
                    ->disk('public')
                    ->circular()
                    ->imageWidth(100)
                    ->imageHeight(100),

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

        return \App\Filament\Helpers\TableConfigHelper::applyStandardConfig($table, \App\Filament\Resources\Categories\CategoryResource::class);
    }
}
