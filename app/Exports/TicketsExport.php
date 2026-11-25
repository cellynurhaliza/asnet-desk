<?php

namespace App\Exports;

use App\Models\Ticket;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class TicketsExport implements FromQuery, WithMapping, WithHeadings, WithEvents
{
    protected ?int $month;
    protected int $year;
    protected ?string $customer;
    protected int $rowNumber = 0;
    protected ?int $customerId;

    public function __construct(?int $month, int $year, ?int $customerId = null)
    {
        $this->month = $month;
        $this->year = $year;
        $this->customerId = $customerId;
    }

    public function query()
    {
        return Ticket::query()
            ->whereYear('reporting_time', $this->year)
            ->when($this->month !== null, function ($query) {
                $query->whereMonth('reporting_time', $this->month);
            })
            ->when($this->customerId !== null, function ($query) {
                $query->where('customer_id', $this->customerId);
            })
            ->orderBy('reporting_time');
    }

    public function headings(): array
    {
        return [
            'NO',
            'TICKET ID',
            'CUSTOMER',
            'GANGGUAN',
            'START DATE',
            'END DATE',
            'DURASI [Menit]',
            'ACTION',
        ];
    }

    public function map($ticket): array
    {
        $this->rowNumber++;

        $startDate = $ticket->reporting_time;
        $endDate = $ticket->resolved_at;

        $duration = ($startDate && $endDate)
            ? (int) $startDate->diffInMinutes($endDate)
            : null;

        return [
            $this->rowNumber,
            $ticket->ticket_code,
            $ticket->customer->username ?? '-',
            $ticket->complaint ?? '-',
            $startDate?->format('d/m/Y H:i') ?? '-',
            $endDate?->format('d/m/Y H:i') ?? '-',
            $duration,
            $ticket->action ?? '-',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $sheet->insertNewRowBefore(1, 1);

                $sheet->getStyle('A2:H2')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => 'solid',
                        'startColor' => ['rgb' => '808080'],
                    ],
                    'alignment' => [
                        'horizontal' => 'center',
                        'vertical' => 'center',
                    ],
                ]);

                $sheet->mergeCells('A1:H1');
                $sheet->setCellValue(
                    'A1',
                    'LOG KERJA OPERASIONAL PT USAHA ADI SANGGORO'
                );

                $sheet->getStyle('A1:H1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14,
                    ],
                    'alignment' => [
                        'horizontal' => 'center',
                        'vertical' => 'center',
                    ],
                ]);

                $sheet->getRowDimension(1)->setRowHeight(28);
                $sheet->getRowDimension(2)->setRowHeight(25);

                $sheet->getStyle('A2:H' . $sheet->getHighestRow())
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(
                        \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                    );

                $sheet->getStyle('A3:A' . $sheet->getHighestRow())
                    ->getAlignment()
                    ->setHorizontal('center');

                $sheet->getStyle('C3:E' . $sheet->getHighestRow())
                    ->getAlignment()
                    ->setHorizontal('center');

                foreach (range('A', 'H') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }

                $sheet->getColumnDimension('B')->setWidth(45);
                $sheet->getColumnDimension('H')->setWidth(40);

                $sheet->getStyle('A2:H' . $sheet->getHighestRow())
                    ->getAlignment()
                    ->setVertical('center');

                $sheet->getStyle('A2:H' . $sheet->getHighestRow())
                    ->getAlignment()
                    ->setWrapText(true);
            },
        ];
    }
}
