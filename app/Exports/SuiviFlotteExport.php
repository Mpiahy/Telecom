<?php

namespace App\Exports;

use App\Services\FlotteService;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class SuiviFlotteExport implements FromArray, WithHeadings, WithTitle, WithColumnWidths, WithStyles, WithEvents
{
    private $annee;
    private $flotteService;

    public function __construct(int $annee, FlotteService $flotteService)
    {
        $this->annee = $annee;
        $this->flotteService = $flotteService;
    }

    /**
     * Récupère les données formatées pour l'export.
     */
    public function array(): array
    {
        return $this->flotteService->getSuiviFlotteData($this->annee);
    }

    /**
     * Définit les en-têtes du fichier Excel.
     */
    public function headings(): array
    {
        $mois = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];

        return array_merge(
            ['Numéro', 'Sim', 'Statut', 'Login', 'Nom et Prénom(s)', 'Fonction', 'Localisation', 'Forfait'],
            $mois,
            ['Total Annuel']
        );
    }

    /**
     * Applique les styles à la feuille Excel.
     */
    public function styles(Worksheet $sheet)
    {
        // En-têtes grisées
        $sheet->getStyle('A1:U1')->applyFromArray([
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'D3D3D3'], // Gris clair
            ],
            'font' => [
                'bold' => true,
                'color' => ['rgb' => '000000'], // Texte noir
            ],
        ]);

        // Alignement à gauche pour les en-têtes
        $sheet->getStyle('A1:T1')->getAlignment()->setHorizontal('left');
        $sheet->getStyle('U1')->getAlignment()->setHorizontal('right');
    }

    /**
     * Définir les largeurs des colonnes.
     */
    public function columnWidths(): array
    {
        return [
            'A' => 15, // num_ligne
            'B' => 20, // num_sim
            'C' => 12, // statut_ligne
            'D' => 15, // login
            'E' => 50, // nom_prenom
            'F' => 50, // fonction
            'G' => 70, // localisation
            'H' => 15, // nom_forfait
            'I' => 12, // mois_1
            'J' => 12, // mois_2
            'K' => 12, // mois_3
            'L' => 12, // mois_4
            'M' => 12, // mois_5
            'N' => 12, // mois_6
            'O' => 12, // mois_7
            'P' => 12, // mois_8
            'Q' => 12, // mois_9
            'R' => 12, // mois_10
            'S' => 12, // mois_11
            'T' => 12, // mois_12
            'U' => 20, // total_annuel
        ];
    }

    /**
     * Définit le titre de l'onglet Excel.
     */
    public function title(): string
    {
        return $this->annee;
    }

    /**
     * Gérer les événements après la génération de l'Excel.
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();
    
                for ($row = 2; $row <= $highestRow; $row++) {
                    $sheet->setCellValue("U$row", "=SUM(I$row:T$row)");
                }
    
                $totalCell = 'U' . ($highestRow + 1);
                $sheet->setCellValue($totalCell, "=SUM(U2:U$highestRow)");
    
                $sheet->getStyle($totalCell)->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FF0000']],
                    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT],
                    'numberFormat' => ['formatCode' => '#,##0.00 "Ar"'],
                ]);
            },
        ];
    }
    
}
