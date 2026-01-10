<?php

namespace App\Filament\Resources\Invoices\Tables;

use App\Enums\InvoiceStatus;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class InvoicesTable
{
    public static function configure(Table $table): Table
    {
        $table
            ->columns([
                TextColumn::make('invoice_number')
                    ->label(__('Invoice Number'))
                    ->searchableAndSortable()
                    ->toggleable(isToggledHiddenByDefault: false),

                TextColumn::make('client.full_name')
                    ->label(__('Client'))
                    ->searchableAndSortable()
                    ->toggleable(isToggledHiddenByDefault: false),

                TextColumn::make('user.name')
                    ->label(__('User'))
                    ->searchableAndSortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('issue_date')
                    ->label(__('Issue Date'))
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),

                TextColumn::make('due_date')
                    ->label(__('Due Date'))
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('city.name')
                    ->label(__('City'))
                    ->searchableAndSortable()
                    ->toggleable(isToggledHiddenByDefault: true),

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

                TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge()
                    ->color(fn(string $state): string => InvoiceStatus::tryFrom($state)?->getColor() ?? 'gray')
                    ->formatStateUsing(fn($state) => __(ucfirst($state)))
                    ->searchableAndSortable()
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

        return \App\Filament\Helpers\TableConfigHelper::applyStandardConfig($table, \App\Filament\Resources\Invoices\InvoiceResource::class);
    }
}
