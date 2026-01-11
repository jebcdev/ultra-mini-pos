<?php

namespace App\Filament\Resources\Qualities;

use App\Filament\Resources\Qualities\Pages\CreateQuality;
use App\Filament\Resources\Qualities\Pages\EditQuality;
use App\Filament\Resources\Qualities\Pages\ListQualities;
use App\Filament\Resources\Qualities\Pages\ViewQuality;
use App\Filament\Resources\Qualities\Schemas\QualityForm;
use App\Filament\Resources\Qualities\Schemas\QualityInfolist;
use App\Filament\Resources\Qualities\Tables\QualitiesTable;
use App\Models\Quality;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QualityResource extends Resource
{
    protected static ?string $model = Quality::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedStar;

    protected static ?string $recordTitleAttribute = 'name';

    /*  Inicio de Personalización */

    use \App\Filament\Traits\SuperAdminAdminAccesTrait;

    public static function getNavigationLabel(): string
    {
        // Define el nombre en singular para la navegación lateral
        $text = __('Qualities');

        return $text;
    }

    // Opcional: Cambiar los nombres usados en los títulos y Breadcrumbs
    public static function getModelLabel(): string
    {
        $text = __('Quality');

        return $text; // Usado en 'Crear'
    }

    public static function getPluralModelLabel(): string
    {
        $text = __('Qualities');

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

        return 95;
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        $text = __('Total Qualities Registered');

        return $text;
    }

    /*  Fin de Personalización */

    public static function form(Schema $schema): Schema
    {
        return QualityForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return QualityInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return QualitiesTable::configure($table);
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
            'index' => ListQualities::route('/'),
            'create' => CreateQuality::route('/create'),
            'view' => ViewQuality::route('/{record}'),
            'edit' => EditQuality::route('/{record}/edit'),
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
