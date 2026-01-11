<?php

namespace App\Filament\Resources\Purchases\Schemas;

use App\Models\Purchase;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;

class PurchaseInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Purchase Information'))
                    ->icon(Heroicon::DocumentText)
                    ->description(__('Purchase details.'))
                    ->columns(2)
                    ->columnSpanFull()
                    ->collapsible()
                    ->schema([
                        TextEntry::make('purchase_number')
                            ->label(__('Purchase Number'))
                            ->icon(Heroicon::DocumentText)
                            ->color(Color::Blue),

                        TextEntry::make('user.name')
                            ->label(__('User'))
                            ->icon(Heroicon::UserCircle)
                            ->color(Color::Blue)
                            ->placeholder('-'),

                        TextEntry::make('purchase_date')
                            ->label(__('Purchase Date'))
                            ->date()
                            ->icon(Heroicon::CalendarDays)
                            ->color(Color::Green),
                    ]),

                Section::make(__('Amounts'))
                    ->icon(Heroicon::CurrencyDollar)
                    ->description(__('Purchase amounts.'))
                    ->columns(2)
                    ->columnSpanFull()
                    ->collapsible()
                    ->schema([
                        TextEntry::make('subtotal')
                            ->label(__('Subtotal'))
                            ->icon(Heroicon::CurrencyDollar)
                            ->color(Color::Green)
                            ->moneyCop(),

                        TextEntry::make('tax_amount')
                            ->label(__('Tax Amount'))
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

                Section::make(__('Purchase Items'))
                    ->icon(Heroicon::ShoppingBag)
                    ->description(__('Items in this purchase.'))
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

                \App\Filament\Helpers\AuditSectionHelper::make(Purchase::class),
            ]);
    }
}
