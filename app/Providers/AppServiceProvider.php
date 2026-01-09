<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // === CONFIGURACIÓN DE TIMEZONE ===
        // Asegura que el timezone de PHP coincida con config('app.timezone')
        // Esto hace que las funciones de fecha de PHP y Carbon usen el timezone configurado
        // incluso si el sistema/php.ini tiene un valor por defecto diferente
        date_default_timezone_set(config('app.timezone'));

        // === MACROS PARA COMPONENTES DE FILAMENT ===

        // Macro para TextColumn que combina searchable y sortable
        // Permite encadenar ambos métodos en una sola llamada para columnas de texto
        // que necesitan ser buscables y ordenables
        \Filament\Tables\Columns\TextColumn::macro('searchableAndSortable', function () {
            /** @var \Filament\Tables\Columns\TextColumn $this */
            return $this->searchable()->sortable();
        });

        // Macro para formatear valores monetarios en pesos colombianos
        // Configura el formato de moneda COP con locale español de Colombia
        // y formatea el estado usando number_format para separadores correctos
        \Filament\Tables\Columns\TextColumn::macro('moneyCop', function () {
            /** @var \Filament\Tables\Columns\TextColumn $this */
            return $this->money('COP', locale: 'es_CO', divideBy: 1)->formatStateUsing(fn($state) => number_format($state, 0, ',', '.'));
        });

        // Macro para IconColumn que combina searchable y sortable
        // Similar a la macro de TextColumn pero para columnas de iconos
        \Filament\Tables\Columns\IconColumn::macro('searchableAndSortable', function () {
            /** @var \Filament\Tables\Columns\IconColumn $this */
            return $this->searchable()->sortable();
        });

        // Macro para ImageColumn que combina searchable y sortable
        // Para columnas que muestran imágenes y necesitan búsqueda y ordenamiento
        \Filament\Tables\Columns\ImageColumn::macro('searchableAndSortable', function () {
            /** @var \Filament\Tables\Columns\ImageColumn $this */
            return $this->searchable()->sortable();
        });

        // Macro para Select que combina preload y searchable
        // Optimiza el componente Select habilitando precarga y búsqueda
        // Mejora la UX en selects con muchas opciones
        \Filament\Forms\Components\Select::macro('preloadSearchable', function () {
            /** @var \Filament\Forms\Components\Select $this */
            return $this->preload(true)->searchable(true);
        });

        // Macro para Section que combina collapsible y collapsed
        // Hace que las secciones sean colapsables y estén contraídas por defecto
        // Mejora la organización del UI ocultando secciones inicialmente
        \Filament\Schemas\Components\Section::macro('collapsibleCollapsed', function () {
            /** @var \Filament\Schemas\Components\Section $this */
            return $this->collapsible(true)->collapsed(true);
        });

        // Macro para formatear valores monetarios en pesos colombianos
        // Configura el formato de moneda COP con locale español de Colombia
        // y formatea el estado usando number_format para separadores correctos
        \Filament\Infolists\Components\TextEntry::macro('moneyCop', function () {
            /** @var \Filament\Infolists\Components\TextEntry $this */
            return $this->money('COP', locale: 'es_CO', divideBy: 1)->formatStateUsing(fn($state) => number_format($state, 0, ',', '.'));
        });
    }
}
