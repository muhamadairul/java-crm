<?php

namespace Webkul\Admin\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ContactsExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(protected $persons) {}

    public function collection()
    {
        return $this->persons;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama',
            'Email',
            'No. Telepon',
            'Pekerjaan (Job Title)',
            'Organisasi',
            'Pemilik (Owner)',
            'Tanggal Dibuat',
        ];
    }

    public function map($person): array
    {
        $emails = is_array($person->emails) ? implode(', ', $person->emails) : $person->emails;
        $phones = is_array($person->contact_numbers) ? implode(', ', $person->contact_numbers) : $person->contact_numbers;

        return [
            $person->id,
            $person->name,
            $emails,
            $phones,
            $person->job_title,
            $person->organization?->name ?? '',
            $person->user?->name ?? '',
            $person->created_at ? $person->created_at->format('Y-m-d H:i:s') : '',
        ];
    }
}
