<?php

namespace Webkul\Admin\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class QuotesExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(protected $quotes) {}

    public function collection()
    {
        return $this->quotes;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Subjek',
            'Deskripsi',
            'Diskon %',
            'Diskon Amount',
            'Tax Amount',
            'Adjustment Amount',
            'Sub Total',
            'Grand Total',
            'Kadaluarsa Pada',
            'Pemilik (Owner)',
            'Kontak Person',
            'Tanggal Dibuat',
        ];
    }

    public function map($quote): array
    {
        return [
            $quote->id,
            $quote->subject,
            $quote->description,
            $quote->discount_percent,
            $quote->discount_amount,
            $quote->tax_amount,
            $quote->adjustment_amount,
            $quote->sub_total,
            $quote->grand_total,
            $quote->expired_at ? $quote->expired_at->format('Y-m-d') : '',
            $quote->user?->name ?? '',
            $quote->person?->name ?? '',
            $quote->created_at ? $quote->created_at->format('Y-m-d H:i:s') : '',
        ];
    }
}
