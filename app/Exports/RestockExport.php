<?php

namespace App\Exports;

use App\Models\Restock;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize; // Tambah
use Maatwebsite\Excel\Concerns\WithStyles;     // Tambah
use Maatwebsite\Excel\Concerns\WithColumnFormatting; // Tambah
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class RestockExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithColumnFormatting
{
    public function collection()
    {
        return Restock::where('status', 'approved')
            ->with(['supplier', 'items.ingredient'])
            ->orderBy('purchase_date', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return ['TANGGAL BELI', 'NO. INVOICE', 'SUPPLIER', 'TOTAL BELANJA (RP)', 'DETAIL BARANG'];
    }

    public function map($restock): array
    {
        $details = $restock->items->map(function($item) {
            return $item->ingredient->name . ' (' . $item->quantity . ' ' . $item->ingredient->unit . ')';
        })->implode(', ');

        return [
            $restock->purchase_date,
            $restock->invoice_number,
            $restock->supplier->name,
            $restock->total_spent,
            $details
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }

    public function columnFormats(): array
    {
        return [
            'D' => '#,##0', 
        ];
    }
}