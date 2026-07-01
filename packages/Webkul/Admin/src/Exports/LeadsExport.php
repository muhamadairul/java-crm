<?php

namespace Webkul\Admin\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LeadsExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(protected $leads) {}

    public function collection()
    {
        return $this->leads;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Judul Lead',
            'Deskripsi',
            'Nilai (Value)',
            'Status',
            'Alasan Gagal (Lost Reason)',
            'Sumber (Source)',
            'Tipe (Type)',
            'Pipeline',
            'Stage',
            'Pemilik (Owner)',
            'Kontak Person',
            'Tanggal Dibuat',
        ];
    }

    public function map($lead): array
    {
        return [
            $lead->id,
            $lead->title,
            $lead->description,
            $lead->lead_value,
            $lead->status,
            $lead->lost_reason,
            $lead->source?->name ?? '',
            $lead->type?->name ?? '',
            $lead->pipeline?->name ?? '',
            $lead->stage?->name ?? '',
            $lead->user?->name ?? '',
            $lead->person?->name ?? '',
            $lead->created_at ? $lead->created_at->format('Y-m-d H:i:s') : '',
        ];
    }
}
