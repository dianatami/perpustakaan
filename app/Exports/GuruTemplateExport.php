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

class GuruTemplateExport extends DefaultValueBinder implements FromCollection, WithHeadings, WithColumnFormatting, WithCustomValueBinder
{
    public function headings(): array
    {
        return ['NIP', 'Nama Guru', 'Email (Opsional)', 'Nomor HP'];
    }

    public function collection()
    {
        return collect([
            ['198501152010011002', 'Drs. H. Ahmad Fauzi M.Pd.', 'ahmad.fauzi@smk.sch.id', '081200000001'],
            ['198803202015022005', 'Siti Nurjanah S.Pd.', 'siti.nurjanah@smk.sch.id', '081200000002'],
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
