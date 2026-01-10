<?php

namespace App\Filament\Resources\Products;

use App\Filament\Resources\Products\Pages\CreateProduct;
use App\Filament\Resources\Products\Pages\EditProduct;
use App\Filament\Resources\Products\Pages\ListProducts;
use App\Filament\Resources\Products\Pages\ViewProduct;
use App\Filament\Resources\Products\Schemas\ProductForm;
use App\Filament\Resources\Products\Schemas\ProductInfolist;
use App\Filament\Resources\Products\Tables\ProductsTable;
use App\Models\Product;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    /*  Inicio de Personalización */

    use \App\Filament\Traits\SuperAdminAdminAccesTrait;

    public static function getNavigationLabel(): string
    {
        // Define el nombre en singular para la navegación lateral
        $text = __('Products');

        return $text;
    }

    // Opcional: Cambiar los nombres usados en los títulos y Breadcrumbs
    public static function getModelLabel(): string
    {
        $text = __('Product');

        return $text; // Usado en 'Crear'
    }

    public static function getPluralModelLabel(): string
    {
        $text = __('Products');

        return $text; // Usado en el título principal 'Lista de ...'
    }

    public static function getNavigationGroup(): ?string
    {
        $text = __('Inventory & Customers');

        return $text;
    }

    public static function getNavigationGroupSort(): ?int
    {

        return 97;
    }

    public static function getNavigationSort(): ?int
    {

        return 94;
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        $text = __('Total Products Registered');

        return $text;
    }

    /*  Fin de Personalización */

    public static function form(Schema $schema): Schema
    {
        return ProductForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProductInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProducts::route('/'),
            'create' => CreateProduct::route('/create'),
            'view' => ViewProduct::route('/{record}'),
            'edit' => EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
