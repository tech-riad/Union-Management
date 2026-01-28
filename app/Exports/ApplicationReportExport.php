<?php

namespace App\Exports;

use App\Models\Application;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ApplicationReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $period;
    
    public function __construct($period = 'month')
    {
        $this->period = $period;
    }

    public function collection()
    {
        $query = Application::with(['user', 'certificateType']);
            
        // Apply period filter if needed
        if ($this->period === 'today') {
            $query->whereDate('created_at', now()->today());
        } elseif ($this->period === 'week') {
            $query->whereDate('created_at', '>=', now()->subDays(7));
        } elseif ($this->period === 'month') {
            $query->whereDate('created_at', '>=', now()->subDays(30));
        } elseif ($this->period === 'year') {
            $query->whereDate('created_at', '>=', now()->startOfYear());
        }
            
        return $query->orderBy('created_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'Application ID',
            'Citizen Name',
            'Email',
            'Phone',
            'Certificate Type',
            'Fee (৳)',
            'Status',
            'Applied Date',
            'Processed Date',
            'Address'
        ];
    }

    public function map($application): array
    {
        return [
            $application->id,
            $application->user->name ?? 'N/A',
            $application->user->email ?? 'N/A',
            $application->user->phone ?? 'N/A',
            $application->certificateType->name ?? 'N/A',
            number_format($application->certificateType->fee ?? 0, 2),
            ucfirst($application->status),
            $application->created_at->format('d/m/Y H:i'),
            $application->processed_at ? $application->processed_at->format('d/m/Y H:i') : 'Pending',
            $application->user->address ?? 'N/A'
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
            'A1:J' . ($sheet->getHighestRow()) => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000'],
                    ],
                ],
            ],
            
            // Style status column based on value
            'G' => [
                'font' => [
                    'color' => ['argb' => 'FFFFFFFF']
                ]
            ]
        ];
    }
    
    public function columnFormats(): array
    {
        return [
            'F' => '#,##0.00',
        ];
    }
}