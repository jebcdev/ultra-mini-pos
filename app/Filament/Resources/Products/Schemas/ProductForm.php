<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Enums\UnitOfMeasure;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Str;


class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Product Information'))
                    ->icon(Heroicon::ShoppingCart)
                    ->columns(2)
                    ->description(__('Enter the product details.'))
                    ->columnSpanFull()
                    ->schema([
                        Select::make('category_id')
                            ->label(__('Category'))
                            ->relationship('category', 'name')
                            ->required()
                            ->preloadSearchable()
                            ->suffixIcon(Heroicon::Tag)
                            ->suffixIconColor(Color::Blue),

                        Select::make('quality_id')
                            ->label(__('Quality'))
                            ->relationship('quality', 'name')
                            ->suffixIcon(Heroicon::CheckCircle)
                            ->preloadSearchable()
                            ->required()
                            ->suffixIconColor(Color::Blue),

                        TextInput::make('name')
                            ->label(__('Name'))
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state)))
                            ->suffixIcon(Heroicon::ShoppingCart)
                            ->suffixIconColor(Color::Blue),

                            TextInput::make('slug')
                            ->label(__('Slug'))
                            ->required()
                            ->readOnly()->disabled()->dehydrated(true)
                            ->suffixIcon(Heroicon::Link)
                            ->suffixIconColor(Color::Blue),

                        TextInput::make('sku')
                            ->label(__('SKU'))
                            ->required()
                            ->suffixIcon(Heroicon::QrCode)
                            ->suffixIconColor(Color::Blue),

                        Toggle::make('is_active')
                            ->label(__('Active'))
                            ->default(true),
                    ]),

                Section::make(__('Description'))
                    ->icon(Heroicon::DocumentText)
                    ->columns(1)
                    ->description(__('Product description and details.'))
                    ->columnSpanFull()
                    ->schema([
                        Textarea::make('description')
                            ->label(__('Description'))
                            ->columnSpanFull(),

                        FileUpload::make('images')
                            ->columnSpanFull()
                            ->disk('public')
                            ->directory('products-images')
                            ->hint(__('Upload max 5 images for the product.'))
                            ->hintIcon(Heroicon::OutlinedInformationCircle)
                            ->acceptedFileTypes([
                                'image/jpeg',
                                'image/png',
                                'image/gif',
                                'image/svg+xml',
                                'image/webp',
                            ])
                            ->multiple()
                            ->reorderable()
                            ->maxFiles(5)
                            ->image()
                            ->label(__('Images')),
                    ]),

                Section::make(__('Pricing'))
                    ->icon(Heroicon::CurrencyDollar)
                    ->columns(2)
                    ->description(__('Set product prices.'))
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('purchase_price')
                            ->label(__('Purchase Price'))
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->prefix('$')
                            ->suffixIcon(Heroicon::CurrencyDollar)
                            ->suffixIconColor(Color::Green),

                        TextInput::make('sale_price')
                            ->label(__('Sale Price'))
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->prefix('$')
                            ->suffixIcon(Heroicon::CurrencyDollar)
                            ->suffixIconColor(Color::Green),
                    ]),

                Section::make(__('Inventory'))
                    ->icon(Heroicon::Squares2x2)
                    ->columns(3)
                    ->description(__('Manage product stock.'))
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('stock')
                            ->label(__('Current Stock'))
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->suffixIcon(Heroicon::Squares2x2)
                            ->suffixIconColor(Color::Blue),

                        TextInput::make('stock_min')
                            ->label(__('Minimum Stock'))
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->suffixIcon(Heroicon::ArrowDown)
                            ->suffixIconColor(Color::Red),

                        TextInput::make('stock_max')
                            ->label(__('Maximum Stock'))
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->suffixIcon(Heroicon::ArrowUp)
                            ->suffixIconColor(Color::Green),

                        Select::make('unit')
                            ->label(__('Unit of Measure'))
                            ->options(
                                            collect(UnitOfMeasure::values())
                                                ->mapWithKeys(fn($value) => [$value => __($value)])
                                        )
                            ->required()
                            ->default('unit')
                            ->preloadSearchable()
                            ->suffixIcon(Heroicon::Wrench)
                            ->suffixIconColor(Color::Blue),
                    ]),

                Section::make(__('Dimensions & Weight'))
                    ->icon(Heroicon::Squares2x2)
                    ->columns(3)
                    ->description(__('Physical specifications.'))
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('width')
                            ->label(__('Width'))
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->suffix('cm')
                            ->suffixIcon(Heroicon::Squares2x2)
                            ->suffixIconColor(Color::Blue),

                        TextInput::make('height')
                            ->label(__('Height'))
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->suffix('cm')
                            ->suffixIcon(Heroicon::Squares2x2)
                            ->suffixIconColor(Color::Blue),

                        TextInput::make('weight')
                            ->label(__('Weight'))
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->suffix('kg')
                            ->suffixIcon(Heroicon::Scale)
                            ->suffixIconColor(Color::Blue),
                    ]),
            ]);
    }
}
