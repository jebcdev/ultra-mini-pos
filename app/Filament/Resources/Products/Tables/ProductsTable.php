<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        $table
            ->columns([
                TextColumn::make('category.name')
                    ->label(__('Category'))
                    ->searchableAndSortable(),

                TextColumn::make('quality.name')
                    ->label(__('Quality'))
                    ->searchableAndSortable(),

                TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchableAndSortable(),

                TextColumn::make('sku')
                    ->label(__('SKU'))
                    ->searchableAndSortable(),

                TextColumn::make('sale_price')
                    ->label(__('Sale Price'))
                    ->money()
                    ->sortable(),

                TextColumn::make('stock')
                    ->label(__('Stock'))
                    ->numeric()
                    ->sortable(),

                TextColumn::make('unit')
                    ->label(__('Unit'))
                    ->searchableAndSortable(),

                IconColumn::make('is_active')
                    ->label(__('Active'))
                    ->boolean(),

                TextColumn::make('slug')
                    ->label(__('Slug'))
                    ->searchableAndSortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('purchase_price')
                    ->label(__('Purchase Price'))
                    ->money()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('stock_min')
                    ->label(__('Min Stock'))
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('stock_max')
                    ->label(__('Max Stock'))
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('width')
                    ->label(__('Width'))
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('height')
                    ->label(__('Height'))
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('weight')
                    ->label(__('Weight'))
                    ->numeric()
                    ->sortable()
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

        return \App\Filament\Helpers\TableConfigHelper::applyStandardConfig($table, \App\Filament\Resources\Products\ProductResource::class);
    }
}
