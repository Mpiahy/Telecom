<?php

namespace Database\Seeders;

use App\Models\ContactOperateur;
use App\Models\Operateur;
use Illuminate\Database\Seeder;

class ContactOperateurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ContactOperateur::truncate();

        $contacts = [
            '34' => [ // ID de Yas
                ['nom' => 'Yas', 'email' => 'andriamahaleompiahisoa.randriamanivo@colas-mg.com'],
            ],
            '32' => [ // ID de Orange
                ['nom' => 'Orange', 'email' => 'mpiahyandriam@gmail.com'],
            ],
        ];

        // Insérer les contacts
        foreach ($contacts as $id_operateur => $contacts_list) {
            // Récupérer l'opérateur par son ID
            $operateur = Operateur::find($id_operateur);

            // Si l'opérateur existe, insérer les contacts
            if ($operateur) {
                foreach ($contacts_list as $contact) {
                    ContactOperateur::create([
                        'nom' => $contact['nom'],
                        'email' => $contact['email'],
                        'id_operateur' => $operateur->id_operateur,
                    ]);
                }
            }
        }
    }
}