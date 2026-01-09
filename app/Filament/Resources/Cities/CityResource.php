<?php

namespace App\Filament\Resources\Cities;

use App\Filament\Resources\Cities\Pages\CreateCity;
use App\Filament\Resources\Cities\Pages\EditCity;
use App\Filament\Resources\Cities\Pages\ListCities;
use App\Filament\Resources\Cities\Pages\ViewCity;
use App\Filament\Resources\Cities\Schemas\CityForm;
use App\Filament\Resources\Cities\Schemas\CityInfolist;
use App\Filament\Resources\Cities\Tables\CitiesTable;
use App\Filament\Traits\SuperAdminAdminAccesTrait;
use App\Models\City;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CityResource extends Resource
{
    use SuperAdminAdminAccesTrait;
    
    protected static ?string $model = City::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice;

    protected static ?string $recordTitleAttribute = 'name';

    /*  Inicio de Personalización */

    public static function getNavigationLabel(): string
    {
        // Define el nombre en singular para la navegación lateral
        $text = __('Cities');

        return $text;
    }

    // Opcional: Cambiar los nombres usados en los títulos y Breadcrumbs
    public static function getModelLabel(): string
    {
        $text = __('City');

        return $text; // Usado en 'Crear'
    }

    public static function getPluralModelLabel(): string
    {
        $text = __('Cities');

        return $text; // Usado en el título principal 'Lista de ...'
    }

    public static function getNavigationGroup(): ?string
    {
        $text = __('Localization Management');

        return $text;
    }

    public static function getNavigationGroupSort(): ?int
    {

        return 98;
    }

    public static function getNavigationSort(): ?int
    {

        return 97;
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        $text = __('Total Cities Registered');

        return $text;
    }

    /*  Fin de Personalización */

    public static function form(Schema $schema): Schema
    {
        return CityForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CityInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CitiesTable::configure($table);
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
            'index' => ListCities::route('/'),
            'create' => CreateCity::route('/create'),
            'view' => ViewCity::route('/{record}'),
            'edit' => EditCity::route('/{record}/edit'),
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
