<?php

namespace App\Exports;

use App\Services\EquipementService;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SuiviEquipementExport implements FromArray, WithHeadings, WithTitle, WithStyles, WithColumnWidths
{
    private $equipementService;

    public function __construct(EquipementService $equipementService)
    {
        $this->equipementService = $equipementService;
    }

    public function array(): array
    {
        // Utiliser le service pour récupérer les données sous forme de tableau
        return $this->equipementService->getEquipementData();
    }

    public function headings(): array
    {
        return [
            'Statut',
            'Type',
            'Enrollé',
            'Login',
            'Nom et Prénom(s)',
            'Fonction',
            'Localisation',
            'Date d\'affectation',
            'Date de retour',
        ];
    }

    public function title(): string
    {
        return 'Equipement';
    }

    public function styles(Worksheet $sheet)
    {
        // Appliquer des styles aux en-têtes
        $sheet->getStyle('A1:I1')->applyFromArray([
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'D3D3D3'], // Gris clair
            ],
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10, // Statut
            'B' => 20, // Type
            'C' => 8, // Enrollé
            'D' => 15, // Login
            'E' => 40, // Nom et Prénom(s)
            'F' => 40, // Fonction
            'G' => 50, // Localisation
            'H' => 20, // Date d'affectation
            'I' => 20, // Date de retour
        ];
    }
}
