<?php

namespace App\Filament\Resources\Invoices\Schemas;

use App\Enums\InvoiceStatus;
use App\Models\Invoice;
use App\Models\Product;
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

class InvoiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Invoice Information'))
                    ->icon(Heroicon::DocumentText)
                    ->columns(2)
                    ->description(__('Enter the invoice details.'))
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('invoice_number')
                            ->label(__('Invoice Number'))
                            ->required()
                            ->disabled()
                            ->dehydrated(true)
                            ->default(Invoice::generateUniqueInvoiceNumber())
                            ->suffixIcon(Heroicon::DocumentText)
                            ->suffixIconColor(Color::Blue),

                        Select::make('client_id')
                            ->label(__('Client'))
                            ->relationship('client', 'full_name')
                            ->required()
                            ->preloadSearchable()
                            ->suffixIcon(Heroicon::User)
                            ->suffixIconColor(Color::Blue),

                        Hidden::make('user_id')
                            ->default(fn() => Auth::id())
                            ->required()
                            ->dehydrated(),

                        Select::make('status')
                            ->label(__('Status'))
                            ->options(
                                collect(InvoiceStatus::values())
                                    ->mapWithKeys(fn($value) => [$value => __(ucfirst($value))])
                            )
                            ->required()
                            ->default('pending')
                            ->suffixIcon(Heroicon::Flag)
                            ->suffixIconColor(Color::Blue),
                    ]),

                Section::make(__('Dates'))
                    ->icon(Heroicon::Calendar)
                    ->columns(2)
                    ->description(__('Invoice dates.'))
                    ->columnSpanFull()
                    ->schema([
                        DatePicker::make('issue_date')
                            ->label(__('Issue Date'))
                            ->required()
                            ->dehydrated()
                            ->default(Carbon::now()->toDateString())
                            ->suffixIcon(Heroicon::CalendarDays)
                            ->suffixIconColor(Color::Green),

                        DatePicker::make('due_date')
                            ->label(__('Due Date'))
                            ->required()
                            ->default(fn() => Carbon::now()->addWeek()->toDateString())
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                $issueDate = $get('issue_date');
                                if ($issueDate) {
                                    $dueDate = Carbon::parse($issueDate)->addWeek()->toDateString();
                                    $set('due_date', $dueDate);
                                }
                            })
                            ->suffixIcon(Heroicon::CalendarDays)
                            ->suffixIconColor(Color::Red),
                    ]),

                Section::make(__('Delivery'))
                    ->icon(Heroicon::Truck)
                    ->columns(2)
                    ->description(__('Delivery information.'))
                    ->columnSpanFull()
                    ->schema([
                        Select::make('city_id')
                            ->label(__('City'))
                            ->relationship('city', 'name')
                            ->preloadSearchable()
                            ->suffixIcon(Heroicon::MapPin)
                            ->suffixIconColor(Color::Blue),

                        Textarea::make('delivery_address')
                            ->label(__('Delivery Address'))
                            ->columnSpanFull(),
                    ]),

                Section::make(__('Invoice Items'))
                    ->icon(Heroicon::ShoppingCart)
                    ->columns(4)
                    ->description(__('Add items to the invoice.'))
                    ->columnSpanFull()
                    ->schema([
                        Repeater::make('items')
                            ->relationship()
                            ->live(onBlur: true)
                            ->columns(4)
                            ->afterStateUpdated(function (Set $set) {
                                // Marcar que los totales están desactualizados
                                $set('totals_calculated', false);
                            })
                            ->schema([
                                Select::make('product_id')
                                    ->label(__('Product'))
                                    ->relationship('product', 'name')
                                    ->required()
                                    ->preloadSearchable()
                                    ->live()
                                    ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                                        if ($state) {
                                            $product = Product::find($state);
                                            if ($product) {
                                                $set('unit_price', $product->sale_price);
                                                $quantity = (float) ($get('quantity') ?? 1);
                                                $discount = (float) ($get('discount') ?? 0);
                                                $set('total_price', ($quantity * $product->sale_price) - $discount);
                                            }
                                        } else {
                                            $set('quantity', 1);
                                            $set('unit_price', 0);
                                            $set('discount', 0);
                                            $set('total_price', 0);
                                        }
                                        // Marcar como no calculado
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
                                        // Marcar como no calculado
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
                                        // Marcar como no calculado
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
                                        // Marcar como no calculado
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
                    ->description(__('Invoice amounts.'))
                    ->columnSpanFull()
                    ->headerActions([
                        Action::make('calculateTotals')
                            ->label(__('Calculate Totals'))
                            ->icon(Heroicon::Calculator)
                            ->color(Color::Blue)
                            ->badge(
                                fn(Get $get): ?string => $get('totals_calculated')
                                    ? __('Calculated')
                                    : __('Pending')
                            )
                            ->badgeColor(
                                fn(Get $get): string => $get('totals_calculated')
                                    ? 'success'
                                    : 'warning'
                            )
                            ->action(function (Get $get, Set $set) {
                                // 1. Obtener todos los items
                                $items = $get('items') ?? [];

                                // 2. Validar que haya items
                                if (empty($items)) {
                                    Notification::make()
                                        ->title(__('No items found'))
                                        ->body(__('Please add at least one item before calculating totals'))
                                        ->warning()
                                        ->send();
                                    return;
                                }

                                // 3. Calcular subtotal y descuentos totales
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

                                // 4. Validar que el subtotal sea válido
                                if ($subtotal < 0) {
                                    Notification::make()
                                        ->title(__('Invalid Calculation'))
                                        ->body(__('The subtotal cannot be negative'))
                                        ->danger()
                                        ->send();
                                    return;
                                }

                                // 5. Asegurar que el descuento total no exceda el subtotal
                                if ($totalDiscount > $subtotal) {
                                    Notification::make()
                                        ->title(__('Invalid Discount'))
                                        ->body(__('The total discount cannot exceed the subtotal'))
                                        ->danger()
                                        ->send();
                                    return;
                                }

                                // 6. Calcular total final
                                $totalAmount = $subtotal - $totalDiscount;

                                // 7. Actualizar campos
                                $set('subtotal', round($subtotal, 2));
                                $set('discount_amount', round($totalDiscount, 2));
                                $set('total_amount', round($totalAmount, 2));
                                $set('totals_calculated', true);

                                // 8. Notificación de éxito
                                Notification::make()
                                    ->title(__('Totals calculated successfully'))
                                    ->body(__('All amounts have been updated'))
                                    ->success()
                                    ->send();
                            })
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
