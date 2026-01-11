<?php

namespace App\Filament\Resources\Purchases\Pages;

use App\Filament\Resources\Purchases\PurchaseResource;
use Dompdf\Dompdf;
use Dompdf\Options;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Response;

class ViewPurchase extends ViewRecord
{
    protected static string $resource = PurchaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            Action::make('exportSinglePurchase')
                ->label(__('Export to PDF'))
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(function () {
                    return $this->exportSinglePurchase();
                }),
        ];
    }

    protected function exportSinglePurchase()
    {
        $purchase = $this->record->load(['user', 'items.product']);

        // Configurar opciones de DomPDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Arial');

        // Crear instancia de DomPDF
        $dompdf = new Dompdf($options);

        // Renderizar la vista a HTML
        $html = view('pdf.single-purchase-report', compact('purchase'))->render();

        // Cargar HTML en DomPDF
        $dompdf->loadHtml($html);

        // Configurar tamaño de página y orientación
        $dompdf->setPaper('A4', 'portrait');

        // Renderizar PDF
        $dompdf->render();

        // Retornar respuesta de descarga
        return Response::streamDownload(
            fn () => print($dompdf->output()),
            "purchase_{$purchase->id}.pdf",
            ['Content-Type' => 'application/pdf']
        );
    }
}
