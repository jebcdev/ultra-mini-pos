<?php

namespace App\Filament\Helpers;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuditSectionHelper
{
    /**
     * Crea una sección de información de auditoría estándar
     *
     * @param  string|null  $modelClass  Clase del modelo para verificar SoftDeletes (ej: Item::class)
     */
    public static function make(?string $modelClass = null): Section
    {
        // Verificar si el modelo tiene el trait SoftDeletes
        $hasSoftDeletes = false;
        if ($modelClass) {
            $hasSoftDeletes = in_array(SoftDeletes::class, class_uses($modelClass));
        }

        $schema = [
            TextEntry::make('created_at')
                ->label(__('Created At'))
                ->dateTime()
                ->since()
                ->dateTooltip()
                ->placeholder('-')
                ->icon(Heroicon::OutlinedCalendar)
                ->iconColor(Color::Green),

            TextEntry::make('updated_at')
                ->label(__('Updated At'))
                ->dateTime()
                ->since()
                ->dateTooltip()
                ->placeholder('-')
                ->icon(Heroicon::OutlinedPencilSquare)
                ->iconColor(Color::Blue),

            TextEntry::make('deleted_at')
                ->label(__('Deleted At'))
                ->dateTime()
                ->since()
                ->dateTooltip()
                ->placeholder('-')
                ->icon(Heroicon::OutlinedTrash)
                ->iconColor(Color::Red)
                ->visible(fn (Model $record): bool => $hasSoftDeletes && $record->trashed()),
        ];

        return Section::make(__('Audit Information'))
            ->icon(Heroicon::OutlinedClock)
            ->description(__('Audit details of the record'))
            ->columns(3)
            ->columnSpanFull()
            ->collapsible(true)
            ->collapsed(true)
            ->schema($schema);
    }
}
