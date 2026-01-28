<?php

namespace App\Exports;

use App\Models\Invoice;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RevenueReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $period;
    
    public function __construct($period = 'month')
    {
        $this->period = $period;
    }

    public function collection()
    {
        $query = Invoice::where('payment_status', 'paid')
            ->with(['application.user', 'application.certificateType']);
            
        // Apply period filter if needed
        if ($this->period === 'today') {
            $query->whereDate('paid_at', now()->today());
        } elseif ($this->period === 'week') {
            $query->whereDate('paid_at', '>=', now()->subDays(7));
        } elseif ($this->period === 'month') {
            $query->whereDate('paid_at', '>=', now()->subDays(30));
        } elseif ($this->period === 'year') {
            $query->whereDate('paid_at', '>=', now()->startOfYear());
        }
            
        return $query->orderBy('paid_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'Invoice ID',
            'Application ID',
            'Citizen Name',
            'Certificate Type',
            'Amount (৳)',
            'Payment Date',
            'Payment Method',
            'Status',
            'Created Date'
        ];
    }

    public function map($invoice): array
    {
        return [
            $invoice->invoice_number ?? 'N/A',
            $invoice->application_id,
            $invoice->application->user->name ?? 'N/A',
            $invoice->application->certificateType->name ?? 'N/A',
            number_format($invoice->amount, 2),
            $invoice->paid_at ? $invoice->paid_at->format('d/m/Y') : 'N/A',
            $invoice->payment_method ?? 'N/A',
            ucfirst($invoice->payment_status),
            $invoice->created_at->format('d/m/Y H:i')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text with background
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['argb' => 'FFE0E0E0']
                ]
            ],
            
            // Add borders to all cells
            'A1:I' . ($sheet->getHighestRow()) => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000'],
                    ],
                ],
            ],
        ];
    }
}