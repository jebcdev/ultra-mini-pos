<?php

namespace App\Filament\Resources\Invoices\Schemas;

use App\Enums\InvoiceStatus;
use App\Models\Invoice;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;

class InvoiceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Invoice Information'))
                    ->icon(Heroicon::DocumentText)
                    ->description(__('Invoice details.'))
                    ->columns(2)
                    ->columnSpanFull()
                    ->collapsible()
                    ->schema([
                        TextEntry::make('invoice_number')
                            ->label(__('Invoice Number'))
                            ->icon(Heroicon::DocumentText)
                            ->color(Color::Blue),

                        TextEntry::make('client.full_name')
                            ->label(__('Client'))
                            ->icon(Heroicon::User)
                            ->color(Color::Blue)
                            ->placeholder('-'),

                        TextEntry::make('user.name')
                            ->label(__('User'))
                            ->icon(Heroicon::UserCircle)
                            ->color(Color::Blue)
                            ->placeholder('-'),

                        TextEntry::make('status')
                            ->label(__('Status'))
                            ->badge()
                            ->color(fn(string $state): string => InvoiceStatus::tryFrom($state)?->getColor() ?? 'gray')
                            ->formatStateUsing(fn($state) => __(ucfirst($state)))
                            ->icon(Heroicon::Flag),
                    ]),

                Section::make(__('Dates'))
                    ->icon(Heroicon::Calendar)
                    ->description(__('Invoice dates.'))
                    ->columns(2)
                    ->columnSpanFull()
                    ->collapsible()
                    ->schema([
                        TextEntry::make('issue_date')
                            ->label(__('Issue Date'))
                            ->date()
                            ->icon(Heroicon::CalendarDays)
                            ->color(Color::Green),

                        TextEntry::make('due_date')
                            ->label(__('Due Date'))
                            ->date()
                            ->icon(Heroicon::CalendarDays)
                            ->color(Color::Red)
                            ->placeholder('-'),
                    ]),

                Section::make(__('Delivery'))
                    ->icon(Heroicon::Truck)
                    ->description(__('Delivery information.'))
                    ->columns(2)
                    ->columnSpanFull()
                    ->collapsible()
                    ->schema([
                        TextEntry::make('city.name')
                            ->label(__('City'))
                            ->icon(Heroicon::MapPin)
                            ->color(Color::Blue)
                            ->placeholder('-'),

                        TextEntry::make('delivery_address')
                            ->label(__('Delivery Address'))
                            ->icon(Heroicon::MapPin)
                            ->color(Color::Blue)
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ]),

                Section::make(__('Amounts'))
                    ->icon(Heroicon::CurrencyDollar)
                    ->description(__('Invoice amounts.'))
                    ->columns(2)
                    ->columnSpanFull()
                    ->collapsible()
                    ->schema([
                        TextEntry::make('subtotal')
                            ->label(__('Subtotal'))
                            ->icon(Heroicon::CurrencyDollar)
                            ->color(Color::Green)
                            ->moneyCop(),

                        TextEntry::make('discount_amount')
                            ->label(__('Discount Amount'))
                            ->icon(Heroicon::Tag)
                            ->color(Color::Red)
                            ->moneyCop(),

                        TextEntry::make('total_amount')
                            ->label(__('Total Amount'))
                            ->icon(Heroicon::Banknotes)
                            ->color(Color::Green)
                            ->moneyCop(),
                    ]),

                Section::make(__('Invoice Items'))
                    ->icon(Heroicon::ShoppingCart)
                    ->description(__('Items in this invoice.'))
                    ->columnSpanFull()
                    ->collapsible()
                    ->schema([
                        \Filament\Infolists\Components\RepeatableEntry::make('items')
                            ->schema([
                                \Filament\Infolists\Components\TextEntry::make('product.name')
                                    ->label(__('Product'))
                                    ->color(Color::Blue),

                                \Filament\Infolists\Components\TextEntry::make('quantity')
                                    ->label(__('Quantity'))
                                    ->color(Color::Blue),

                                \Filament\Infolists\Components\TextEntry::make('unit_price')
                                    ->label(__('Unit Price'))
                                    ->moneyCop()
                                    ->color(Color::Green),

                                \Filament\Infolists\Components\TextEntry::make('discount')
                                    ->label(__('Discount'))
                                    ->moneyCop()
                                    ->color(Color::Red),

                                \Filament\Infolists\Components\TextEntry::make('total_price')
                                    ->label(__('Total Price'))
                                    ->moneyCop()
                                    ->color(Color::Green),
                            ])
                            ->columns(5),
                    ]),

                Section::make(__('Notes'))
                    ->icon(Heroicon::ChatBubbleBottomCenterText)
                    ->description(__('Additional notes.'))
                    ->columns(1)
                    ->columnSpanFull()
                    ->collapsible()
                    ->schema([
                        TextEntry::make('notes')
                            ->label(__('Notes'))
                            ->icon(Heroicon::ChatBubbleBottomCenterText)
                            ->color(Color::Blue)
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ]),

                \App\Filament\Helpers\AuditSectionHelper::make(Invoice::class),
            ]);
    }
}
