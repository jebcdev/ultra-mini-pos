<?php

namespace App\Filament\Resources\Invoices\Pages;

use App\Filament\Resources\Invoices\InvoiceResource;
use Dompdf\Dompdf;
use Dompdf\Options;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Response;

class ViewInvoice extends ViewRecord
{
    protected static string $resource = InvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            \Filament\Actions\Action::make('exportSingleInvoice')
                ->label(__('Export to PDF'))
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(function () {
                    return $this->exportSingleInvoice();
                }),
        ];
    }

    protected function exportSingleInvoice()
    {
        $invoice = $this->record->load(['user', 'client', 'city', 'items.product']);

        // Configurar opciones de DomPDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Arial');

        // Crear instancia de DomPDF
        $dompdf = new Dompdf($options);

        // Renderizar la vista a HTML
        $html = view('pdf.single-invoice-report', compact('invoice'))->render();

        // Cargar HTML en DomPDF
        $dompdf->loadHtml($html);

        // Configurar tamaño de página y orientación
        $dompdf->setPaper('A4', 'portrait');

        // Renderizar PDF
        $dompdf->render();

        // Retornar respuesta de descarga
        return Response::streamDownload(
            fn() => print($dompdf->output()),
            "invoice_{$invoice->id}.pdf",
            ['Content-Type' => 'application/pdf']
        );
    }
}
