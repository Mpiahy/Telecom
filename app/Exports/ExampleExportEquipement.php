<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExampleExportEquipement implements FromCollection, WithHeadings, WithStyles, WithColumnFormatting
{
    public function generateSN()
    {
        $sn = '351186744997026';
        return (string) $sn;
        }

    /**
     * Les données pour l'exportation.
     */
    public function collection()
        {
        return collect([
            [
            'SMARTPHONE' => 'O',
            'Enrolle' => 'N',
            'Marque' => 'Samsung',
            'Type' => 'A05',
            'SN' => $this->generateSN(),
            ],
        ]);
    }

    /**
     * Les en-têtes du fichier.
     */
    public function headings(): array
    {
        return [
            'SMARTPHONE',
            'Enrolle',
            'Marque',
            'Type',
            'SN',
        ];
    }

    /**
     * Stylisation des cellules, avec un effet striped.
     */
    public function styles(Worksheet $sheet)
    {
        // Styliser les en-têtes
        $sheet->getStyle('A1:E1')->applyFromArray([
            'font' => [
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => 'solid',
                'startColor' => ['rgb' => '4472C4'], // Couleur bleu foncé
            ],
            'alignment' => [
                'vertical' => 'center',
            ],
        ]);

        // Effet striped (coloration alternée des lignes)
        $rows = $sheet->getHighestRow(); // Récupère le nombre de lignes
        for ($i = 2; $i <= $rows; $i++) {
            if ($i % 2 == 0) {
                $sheet->getStyle("A$i:E$i")->applyFromArray([
                    'fill' => [
                        'fillType' => 'solid',
                        'startColor' => ['rgb' => 'D9E1F2'], // Bleu clair
                    ],
                ]);
            }
        }

        // Centrer tout le contenu
        $sheet->getStyle('A1:G' . $rows)->getAlignment()->setVertical('center');

        // Définir la largeur manuelle des colonnes
        $sheet->getColumnDimension('A')->setWidth(15); // SMARTPHONE
        $sheet->getColumnDimension('B')->setWidth(15); // Enrolle
        $sheet->getColumnDimension('C')->setWidth(40); // Marque
        $sheet->getColumnDimension('D')->setWidth(40); // Type
        $sheet->getColumnDimension('E')->setWidth(40); // SN
    }

    /**
     * Formatage des colonnes.
     */
    public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_TEXT, // Colonne SN formatée en texte
        ];
    }
}
