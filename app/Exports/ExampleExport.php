<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExampleExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    /**
     * Les données pour l'exportation.
     */
    public function collection()
    {
        return collect([
            [
                'Numero2' => '0340502524',
                'Login' => 'RAKOTOE2',
                'Nom et Prenoms' => 'RAKOTOARISOA Eliot',
                'Fonction' => 'Ingénieur IT',
                'SERVICE' => 'ADM',
                'Libelle Imputation' => '2200001AD001 - Service Info - 300800',
                'TYPE FORFAIT' => 'Forfait 3',
            ],
        ]);
    }

    /**
     * Les en-têtes du fichier.
     */
    public function headings(): array
    {
        return [
            'Numero2',
            'Login',
            'Nom et Prenoms',
            'Fonction',
            'SERVICE',
            'Libelle Imputation',
            'TYPE FORFAIT',
        ];
    }

    /**
     * Stylisation des cellules, avec un effet striped.
     */
    public function styles(Worksheet $sheet)
    {
        // Styliser les en-têtes
        $sheet->getStyle('A1:G1')->applyFromArray([
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
                $sheet->getStyle("A$i:G$i")->applyFromArray([
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
        $sheet->getColumnDimension('A')->setWidth(15); // Numero2
        $sheet->getColumnDimension('B')->setWidth(20); // Login
        $sheet->getColumnDimension('C')->setWidth(25); // Nom et Prénoms
        $sheet->getColumnDimension('D')->setWidth(20); // Fonction
        $sheet->getColumnDimension('E')->setWidth(15); // SERVICE
        $sheet->getColumnDimension('F')->setWidth(40); // Libelle Imputation
        $sheet->getColumnDimension('G')->setWidth(15); // TYPE FORFAIT
    }

}
