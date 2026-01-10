<?php

namespace App\Filament\Resources\Purchases\Schemas;

use App\Models\Product;
use App\Models\Purchase;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;

class PurchaseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Purchase Information'))
                    ->icon(Heroicon::DocumentText)
                    ->columns(2)
                    ->description(__('Enter the purchase details.'))
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('purchase_number')
                            ->label(__('Purchase Number'))
                            ->required()
                            ->disabled()
                            ->dehydrated(true)
                            ->default(Purchase::generateUniquePurchaseNumber())
                            ->suffixIcon(Heroicon::DocumentText)
                            ->suffixIconColor(Color::Blue),

                        Hidden::make('user_id')
                            ->default(fn () => Auth::id())
                            ->required()
                            ->dehydrated(),
                    ]),

                Section::make(__('Dates'))
                    ->icon(Heroicon::Calendar)
                    ->columns(1)
                    ->description(__('Purchase date.'))
                    ->columnSpanFull()
                    ->schema([
                        DatePicker::make('purchase_date')
                            ->label(__('Purchase Date'))
                            ->required()
                            ->dehydrated()
                            ->default(Carbon::now()->toDateString())
                            ->suffixIcon(Heroicon::CalendarDays)
                            ->suffixIconColor(Color::Green),
                    ]),

                Section::make(__('Purchase Items'))
                    ->icon(Heroicon::ShoppingBag)
                    ->columns(4)
                    ->description(__('Add items to the purchase.'))
                    ->columnSpanFull()
                    ->schema([
                        Repeater::make('items')
                            ->relationship()
                            ->live(onBlur: true)
                            ->columns(4)
                            ->afterStateUpdated(function (Set $set) {
                                $set('totals_calculated', false);
                            })
                            ->schema([
                                Select::make('product_id')
                                    ->label(__('Product'))
                                    ->options(function () {
                                        return Product::orderBy('name')
                                            ->get()
                                            ->mapWithKeys(fn ($product) => [
                                                $product->id => "{$product->name} (Stock: {$product->stock})",
                                            ])
                                            ->toArray();
                                    })
                                    ->required()
                                    ->preloadSearchable()
                                    ->live()
                                    ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                                        if ($state) {
                                            $product = Product::find($state);
                                            if ($product) {
                                                $set('unit_price', $product->purchase_price);
                                                $quantity = (float) ($get('quantity') ?? 1);
                                                $discount = (float) ($get('discount') ?? 0);
                                                $set('total_price', ($quantity * $product->purchase_price) - $discount);
                                            }
                                        } else {
                                            $set('quantity', 1);
                                            $set('unit_price', 0);
                                            $set('discount', 0);
                                            $set('total_price', 0);
                                        }
                                        $set('totals_calculated', false);
                                    })
                                    ->suffixIcon(Heroicon::Cube)
                                    ->suffixIconColor(Color::Blue)
                                    ->columnSpanFull(),

                                TextInput::make('quantity')
                                    ->label(__('Quantity'))
                                    ->required()
                                    ->numeric()
                                    ->default(1)
                                    ->minValue(1)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (Get $get, Set $set) {
                                        $quantity = (float) ($get('quantity') ?? 0);
                                        $unitPrice = (float) ($get('unit_price') ?? 0);
                                        $discount = (float) ($get('discount') ?? 0);
                                        $set('total_price', ($quantity * $unitPrice) - $discount);
                                        $set('totals_calculated', false);
                                    })
                                    ->suffixIcon(Heroicon::OutlinedSquares2x2)
                                    ->suffixIconColor(Color::Blue),

                                TextInput::make('unit_price')
                                    ->label(__('Unit Price'))
                                    ->required()
                                    ->numeric()
                                    ->default(0)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (Get $get, Set $set) {
                                        $quantity = (float) ($get('quantity') ?? 0);
                                        $unitPrice = (float) ($get('unit_price') ?? 0);
                                        $discount = (float) ($get('discount') ?? 0);
                                        $set('total_price', ($quantity * $unitPrice) - $discount);
                                        $set('totals_calculated', false);
                                    })
                                    ->prefix('$')
                                    ->suffixIcon(Heroicon::CurrencyDollar)
                                    ->suffixIconColor(Color::Green),

                                TextInput::make('discount')
                                    ->label(__('Discount'))
                                    ->numeric()
                                    ->default(0)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (Get $get, Set $set) {
                                        $quantity = (float) ($get('quantity') ?? 0);
                                        $unitPrice = (float) ($get('unit_price') ?? 0);
                                        $discount = (float) ($get('discount') ?? 0);
                                        $set('total_price', ($quantity * $unitPrice) - $discount);
                                        $set('totals_calculated', false);
                                    })
                                    ->prefix('$')
                                    ->suffixIcon(Heroicon::Tag)
                                    ->suffixIconColor(Color::Red),

                                TextInput::make('total_price')
                                    ->label(__('Total Price'))
                                    ->numeric()
                                    ->default(0)
                                    ->prefix('$')
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->suffixIcon(Heroicon::Calculator)
                                    ->suffixIconColor(Color::Green),
                            ])
                            ->columns(5)
                            ->defaultItems(1)
                            ->addActionLabel(__('Add Item'))
                            ->reorderable()
                            ->collapsible()
                            ->columnSpanFull(),
                    ]),

                Section::make(__('Amounts'))
                    ->icon(Heroicon::CurrencyDollar)
                    ->columns(3)
                    ->description(__('Purchase amounts.'))
                    ->columnSpanFull()
                    ->headerActions([
                        Action::make('calculateTotals')
                            ->label(__('Calculate Totals'))
                            ->icon(Heroicon::Calculator)
                            ->color(Color::Blue)
                            ->badge(
                                fn (Get $get): ?string => $get('totals_calculated')
                                    ? __('Calculated')
                                    : __('Pending')
                            )
                            ->badgeColor(
                                fn (Get $get): string => $get('totals_calculated')
                                    ? 'success'
                                    : 'warning'
                            )
                            ->action(function (Get $get, Set $set) {
                                $items = $get('items') ?? [];

                                if (empty($items)) {
                                    Notification::make()
                                        ->title(__('No items found'))
                                        ->body(__('Please add at least one item before calculating totals'))
                                        ->warning()
                                        ->send();

                                    return;
                                }

                                $subtotal = 0;
                                $totalDiscount = 0;

                                foreach ($items as $item) {
                                    if (is_array($item)) {
                                        $quantity = (float) ($item['quantity'] ?? 0);
                                        $unitPrice = (float) ($item['unit_price'] ?? 0);
                                        $discount = (float) ($item['discount'] ?? 0);

                                        $subtotal += ($quantity * $unitPrice);
                                        $totalDiscount += $discount;
                                    }
                                }

                                if ($subtotal < 0) {
                                    Notification::make()
                                        ->title(__('Invalid Calculation'))
                                        ->body(__('The subtotal cannot be negative'))
                                        ->danger()
                                        ->send();

                                    return;
                                }

                                if ($totalDiscount > $subtotal) {
                                    Notification::make()
                                        ->title(__('Invalid Discount'))
                                        ->body(__('The total discount cannot exceed the subtotal'))
                                        ->danger()
                                        ->send();

                                    return;
                                }

                                $totalAmount = $subtotal - $totalDiscount;

                                $set('subtotal', round($subtotal, 2));
                                $set('discount_amount', round($totalDiscount, 2));
                                $set('total_amount', round($totalAmount, 2));
                                $set('totals_calculated', true);

                                Notification::make()
                                    ->title(__('Totals calculated successfully'))
                                    ->body(__('All amounts have been updated'))
                                    ->success()
                                    ->send();
                            }),
                    ])
                    ->schema([
                        TextInput::make('subtotal')
                            ->label(__('Subtotal'))
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->prefix('$')
                            ->disabled()
                            ->dehydrated()
                            ->suffixIcon(Heroicon::CurrencyDollar)
                            ->suffixIconColor(Color::Green),

                        TextInput::make('tax_amount')
                            ->label(__('Tax Amount'))
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->prefix('$')
                            ->disabled()
                            ->dehydrated()
                            ->suffixIcon(Heroicon::CurrencyDollar)
                            ->suffixIconColor(Color::Green),

                        TextInput::make('discount_amount')
                            ->label(__('Discount Amount'))
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->prefix('$')
                            ->disabled()
                            ->dehydrated()
                            ->suffixIcon(Heroicon::Tag)
                            ->suffixIconColor(Color::Red),

                        TextInput::make('total_amount')
                            ->label(__('Total Amount'))
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->prefix('$')
                            ->disabled()
                            ->dehydrated()
                            ->suffixIcon(Heroicon::Banknotes)
                            ->suffixIconColor(Color::Green),

                        Hidden::make('totals_calculated')
                            ->default(false)
                            ->dehydrated(),
                    ]),

                Section::make(__('Notes'))
                    ->icon(Heroicon::ChatBubbleBottomCenterText)
                    ->columns(1)
                    ->description(__('Additional notes.'))
                    ->columnSpanFull()
                    ->schema([
                        Textarea::make('notes')
                            ->label(__('Notes'))
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
