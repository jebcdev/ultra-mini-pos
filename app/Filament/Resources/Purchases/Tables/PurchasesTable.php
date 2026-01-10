<?php

namespace App\Filament\Resources\Purchases\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PurchasesTable
{
    public static function configure(Table $table): Table
    {
        $table
            ->columns([
                TextColumn::make('purchase_number')
                    ->label(__('Purchase Number'))
                    ->searchableAndSortable()
                    ->toggleable(isToggledHiddenByDefault: false),

                TextColumn::make('user.name')
                    ->label(__('User'))
                    ->searchableAndSortable()
                    ->toggleable(isToggledHiddenByDefault: false),

                TextColumn::make('purchase_date')
                    ->label(__('Purchase Date'))
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),

                TextColumn::make('subtotal')
                    ->label(__('Subtotal'))
                    ->moneyCop()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('tax_amount')
                    ->label(__('Tax Amount'))
                    ->moneyCop()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('discount_amount')
                    ->label(__('Discount Amount'))
                    ->moneyCop()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('total_amount')
                    ->label(__('Total Amount'))
                    ->moneyCop()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),

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

        return \App\Filament\Helpers\TableConfigHelper::applyStandardConfig($table, \App\Filament\Resources\Purchases\PurchaseResource::class);
    }
}
