<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class SiswaTemplateExport extends DefaultValueBinder implements FromCollection, WithHeadings, WithColumnFormatting, WithCustomValueBinder
{
    public function headings(): array
    {
        return ['NISN', 'Nama Siswa', 'Email (Opsional)', 'Nomor HP'];
    }

    public function collection()
    {
        return collect([
            ['0019241678', 'Ahmad Rizky', 'ahmad.rizky@siswa.smk.sch.id', '089876543210'],
            ['0019241679', 'Anisa Rahmawati', 'anisa.rahmawati@siswa.smk.sch.id', '089876543211'],
        ]);
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
            'D' => NumberFormat::FORMAT_TEXT,
        ];
    }

    public function bindValue(Cell $cell, $value)
    {
        if (is_numeric($value) || is_string($value)) {
            $cell->setValueExplicit((string) $value, DataType::TYPE_STRING);
            return true;
        }

        return parent::bindValue($cell, $value);
    }
}
