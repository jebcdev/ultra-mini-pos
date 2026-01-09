<?php

namespace App\Filament\Helpers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\SoftDeletes;
use ReflectionClass;

class TableConfigHelper
{
    /**
     * Aplica la configuración estándar de filtros, acciones y bulk actions a una tabla
     *
     * @param  Table  $table  Instancia de la tabla
     * @param  string  $resourceClass  Clase del recurso (ej: CompanyResource::class)
     * @param  array  $additionalFilters  Filtros adicionales opcionales
     * @param  bool  $deleteable  Si se permiten acciones de eliminación
     */
    public static function applyStandardConfig(
        Table $table,
        string $resourceClass,
        array $additionalFilters = [],
        bool $deleteable = true,
        bool $viewable = true,
        bool $editable = true
    ): Table {
        // Obtener la propiedad protegida $model usando Reflection
        $reflection = new ReflectionClass($resourceClass);
        $modelProperty = $reflection->getProperty('model');
        $modelProperty->setAccessible(true);
        $modelClass = $modelProperty->getValue();

        $hasSoftDeletes = in_array(SoftDeletes::class, class_uses($modelClass));

        // Construir filtros
        $filters = $additionalFilters;
        if ($hasSoftDeletes) {
            $filters = array_merge([
                TrashedFilter::make(),
            ], $additionalFilters);
        }

        // Construir acciones de registro
        $recordActions = [];

        if ($viewable) {
            $recordActions[] = ViewAction::make()
                ->url(fn ($record) => $resourceClass::getUrl('view', ['record' => $record]))
                ->label(__('View'));
        }

        if ($editable) {
            $recordActions[] = EditAction::make()
                ->url(fn ($record) => $resourceClass::getUrl('edit', ['record' => $record]))
                ->label(__('Edit'));
        }

        if ($deleteable) {
            $recordActions[] = \Filament\Actions\DeleteAction::make()
                ->label(__('Delete'));

            if ($hasSoftDeletes) {
                $recordActions[] = \Filament\Actions\RestoreAction::make()
                    ->label(__('Restore'));
                $recordActions[] = \Filament\Actions\ForceDeleteAction::make()->label(__('Force Delete'));
            }
        }

        // Construir bulk actions
        $bulkActions = [];

        if ($deleteable) {
            $bulkActions[] = DeleteBulkAction::make();

            if ($hasSoftDeletes) {
                $bulkActions[] = RestoreBulkAction::make();
                $bulkActions[] = ForceDeleteBulkAction::make();
            }
        }

        return $table
            ->filters($filters)
            ->recordActions(
                [
                    \Filament\Actions\ActionGroup::make($recordActions),
                ],
                position: RecordActionsPosition::BeforeColumns
            )
            ->toolbarActions([
                BulkActionGroup::make($bulkActions),
            ]);
    }
}
