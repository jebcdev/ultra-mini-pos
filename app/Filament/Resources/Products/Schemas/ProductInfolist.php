<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\Product;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;

class ProductInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Product Information'))
                    ->icon(Heroicon::ShoppingCart)
                    ->description(__('Product details.'))
                    ->columns(2)
                    ->columnSpanFull()
                    ->collapsible()
                    ->schema([
                        TextEntry::make('category.name')
                            ->label(__('Category'))
                            ->icon(Heroicon::Tag)
                            ->color(Color::Blue)
                            ->placeholder('-'),

                        TextEntry::make('quality.name')
                            ->label(__('Quality'))
                            ->icon(Heroicon::CheckCircle)
                            ->color(Color::Blue)
                            ->placeholder('-'),

                        TextEntry::make('name')
                            ->label(__('Name'))
                            ->icon(Heroicon::ShoppingCart)
                            ->color(Color::Blue),

                        TextEntry::make('sku')
                            ->label(__('SKU'))
                            ->icon(Heroicon::QrCode)
                            ->color(Color::Blue),

                        TextEntry::make('slug')
                            ->label(__('Slug'))
                            ->icon(Heroicon::Link)
                            ->color(Color::Blue),

                        IconEntry::make('is_active')
                            ->label(__('Active'))
                            ->boolean(),
                    ]),

                Section::make(__('Description'))
                    ->icon(Heroicon::DocumentText)
                    ->description(__('Product description.'))
                    ->columns(1)
                    ->columnSpanFull()
                    ->collapsible()
                    ->schema([
                        TextEntry::make('description')
                            ->label(__('Description'))
                            ->icon(Heroicon::DocumentText)
                            ->color(Color::Blue)
                            ->placeholder('-')
                            ->columnSpanFull(),

                        TextEntry::make('images')
                            ->label(__('Images'))
                            ->icon(Heroicon::Camera)
                            ->color(Color::Blue)
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ]),

                Section::make(__('Pricing'))
                    ->icon(Heroicon::CurrencyDollar)
                    ->description(__('Product pricing.'))
                    ->columns(2)
                    ->columnSpanFull()
                    ->collapsible()
                    ->schema([
                        TextEntry::make('purchase_price')
                            ->label(__('Purchase Price'))
                            ->icon(Heroicon::CurrencyDollar)
                            ->color(Color::Green)
                            ->money(),

                        TextEntry::make('sale_price')
                            ->label(__('Sale Price'))
                            ->icon(Heroicon::CurrencyDollar)
                            ->color(Color::Green)
                            ->money(),
                    ]),

                Section::make(__('Inventory'))
                    ->icon(Heroicon::Squares2x2)
                    ->description(__('Stock information.'))
                    ->columns(2)
                    ->columnSpanFull()
                    ->collapsible()
                    ->schema([
                        TextEntry::make('stock')
                            ->label(__('Current Stock'))
                            ->icon(Heroicon::Squares2x2)
                            ->color(Color::Blue)
                            ->numeric(),

                        TextEntry::make('stock_min')
                            ->label(__('Minimum Stock'))
                            ->icon(Heroicon::ArrowDown)
                            ->color(Color::Red)
                            ->numeric(),

                        TextEntry::make('stock_max')
                            ->label(__('Maximum Stock'))
                            ->icon(Heroicon::ArrowUp)
                            ->color(Color::Green)
                            ->numeric(),

                        TextEntry::make('unit')
                            ->label(__('Unit of Measure'))
                            ->icon(Heroicon::Wrench)
                            ->color(Color::Blue),
                    ]),

                Section::make(__('Dimensions & Weight'))
                    ->icon(Heroicon::Squares2x2)
                    ->description(__('Physical specifications.'))
                    ->columns(3)
                    ->columnSpanFull()
                    ->collapsible()
                    ->schema([
                        TextEntry::make('width')
                            ->label(__('Width'))
                            ->icon(Heroicon::Squares2x2)
                            ->color(Color::Blue)
                            ->numeric(),

                        TextEntry::make('height')
                            ->label(__('Height'))
                            ->icon(Heroicon::Squares2x2)
                            ->color(Color::Blue)
                            ->numeric(),

                        TextEntry::make('weight')
                            ->label(__('Weight'))
                            ->icon(Heroicon::Scale)
                            ->color(Color::Blue)
                            ->numeric(),
                    ]),

                \App\Filament\Helpers\AuditSectionHelper::make(Product::class),
            ]);
    }
}
