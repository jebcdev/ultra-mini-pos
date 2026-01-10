<?php

namespace App\Filament\Resources\Invoices\Pages;

use App\Filament\Resources\Invoices\InvoiceResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateInvoice extends CreateRecord
{
    protected static string $resource = InvoiceResource::class;

    protected function beforeCreate(): void
    {
        // Validar que los totales hayan sido calculados
        if (!$this->data['totals_calculated'] ?? false) {
            Notification::make()
                ->title(__('Action Required'))
                ->body(__('You must calculate totals before saving the invoice'))
                ->danger()
                ->send();

            $this->halt();
        }
    }
}
