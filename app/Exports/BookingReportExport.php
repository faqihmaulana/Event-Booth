<?php

namespace App\Exports;

use App\Models\Booking;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Carbon\Carbon;

class BookingReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize
{
    protected $startDate;
    protected $endDate;
    protected $eventId;
    protected $status;
    protected $paymentStatus;

    public function __construct($startDate, $endDate, $eventId = null, $status = null, $paymentStatus = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->eventId = $eventId;
        $this->status = $status;
        $this->paymentStatus = $paymentStatus;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Booking::with(['booth.event'])
            ->whereBetween('created_at', [
                Carbon::parse($this->startDate)->startOfDay(),
                Carbon::parse($this->endDate)->endOfDay()
            ]);

        if ($this->eventId) {
            $query->whereHas('booth.event', function($q) {
                $q->where('id', $this->eventId);
            });
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->paymentStatus) {
            $query->where('payment_status', $this->paymentStatus);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'No',
            'Order ID',
            'Tanggal Booking',
            'Event',
            'Booth ID',
            'Booth Name',
            'Section',
            'Nama Perusahaan',
            'Contact Person',
            'Telepon',
            'Email',
            'Deskripsi',
            'Harga Booth',
            'Status Booking',
            'Status Pembayaran',
            'Metode Pembayaran',
            'Transaction ID',
            'Tanggal Bayar',
            'Expired Payment'
        ];
    }

    /**
     * @param mixed $booking
     * @return array
     */
    public function map($booking): array
    {
        static $no = 1;
        
        $eventName = 'N/A';
        $boothId = 'N/A';
        $boothName = 'N/A';
        $section = 'N/A';

        if ($booking->booth) {
            $boothId = $booking->booth->booth_id ?? 'N/A';
            $boothName = $booking->booth->booth_name ?? $booking->booth->name ?? 'N/A';
            $section = $booking->booth->section ?? 'N/A';

            if ($booking->booth->event) {
                $eventName = $booking->booth->event->main_title ?? 
                           $booking->booth->event->name ?? 
                           $booking->booth->event->title ?? 
                           'Event #' . $booking->booth->event->id;
            }
        }

        return [
            $no++,
            $booking->order_id ?? 'N/A',
            $booking->created_at ? $booking->created_at->format('d/m/Y H:i') : 'N/A',
            $eventName,
            $boothId,
            $boothName,
            $section,
            $booking->company_name ?? 'N/A',
            $booking->contact_person ?? 'N/A',
            $booking->phone ?? 'N/A',
            $booking->email ?? 'N/A',
            $booking->description ?? 'N/A',
            $booking->total_price ? 'Rp' . number_format($booking->total_price, 0, ',', '.') : 'Rp0',
            ucfirst($booking->status ?? 'N/A'),
            ucfirst($booking->payment_status ?? 'N/A'),
            $booking->payment_method ?? 'N/A',
            $booking->transaction_id ?? 'N/A',
            $booking->paid_at ? $booking->paid_at->format('d/m/Y H:i') : 'N/A',
            $booking->payment_expired_at ? $booking->payment_expired_at->format('d/m/Y H:i') : 'N/A',
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text with blue background
            1 => [
                'font' => [
                    'bold' => true, 
                    'size' => 12,
                    'color' => ['argb' => Color::COLOR_WHITE]
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF4472C4']
                ]
            ],
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Laporan Booking ' . Carbon::parse($this->startDate)->format('d-m-Y') . 
               ' s/d ' . Carbon::parse($this->endDate)->format('d-m-Y');
    }
}