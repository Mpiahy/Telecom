<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Import
{
    //********************** SECTION LIGNE UTILISATEUR **********************//
    /**
     * Traite et filtre les données du fichier CSV.
     *
     * @param string $filePath
     * @return array
     */
    public static function processCSV($filePath)
    {
        $data = [];
        if (($handle = fopen($filePath, 'r')) !== false) {
            $headers = fgetcsv($handle, 0, ';');
            $headers[0] = preg_replace('/[\x{FEFF}]/u', '', $headers[0]); // Enlever le BOM (UTF-8)

            $allowedHeaders = ['Numero2', 'Login', 'Nom et Prenom', 'Fonction', 'SERVICE', 'Libelle Imputation', 'TYPE FORFAIT'];
            $headers = array_map('trim', $headers);

            // Valider la correspondance des colonnes avec celles autorisées
            $headerMap = array_flip(array_intersect($headers, $allowedHeaders));

            while (($row = fgetcsv($handle, 0, ';')) !== false) {
                $rowData = [];
                foreach ($headerMap as $key => $index) {
                    $rowData[$key] = $row[$index] ?? null;
                }
                $data[] = $rowData;
            }
            fclose($handle);
        }

        // Valider et filtrer les données selon des critères spécifiques
        $forfaits = DB::table('forfait')->pluck('nom_forfait')->toArray();
        return array_filter($data, function ($row) use ($forfaits) {
            return !empty($row['Numero2']) && !empty($row['TYPE FORFAIT']) &&
                in_array(trim(strtoupper($row['TYPE FORFAIT'])), array_map('strtoupper', $forfaits));
        });
    }

    /**
     * Insère les données en batch dans la base de données.
     *
     * @param array $filteredData
     * @return int Nombre de lignes insérées
     */
    public static function batchInsert(array $filteredData)
    {
        $insertedCount = 0;

        // Chunk pour éviter de dépasser la limite mémoire
        $chunks = array_chunk($filteredData, 1000);
        foreach ($chunks as $chunk) {
            DB::transaction(function () use ($chunk, &$insertedCount) {
                foreach ($chunk as $row) {
                    try {
                        self::importRow($row);
                        $insertedCount++;
                    } catch (\Exception $e) {
                        Log::error('Erreur lors du traitement d\'une ligne CSV : ' . $e->getMessage(), [
                            'row' => $row,
                            'stack' => $e->getTraceAsString(),
                        ]);
                    }
                }
            });
        }

        return $insertedCount;
    }

    
    /**
     * Importer une ligne de données dans la base.
     */
    public static function importRow($row)
    {
        try {
            DB::transaction(function () use ($row) {
                // Mappage des faux services aux services réels
                $fauxServicesMap = [
                    'ADM' => ['LABORATOIRE', 'AP'],
                    'CBCI' => ['DTIP', 'TPC'],
                    'GRAND PROJET' => ['RN6', 'RN13'],
                    'CENTRE ROUTE' => ['TOPO', 'QHSE'],
                ];

                // Convertir les champs critiques en majuscule pour les comparaisons
                $row['Fonction'] = strtoupper(trim($row['Fonction'] ?? ''));
                $row['Libelle Imputation'] = strtoupper(trim($row['Libelle Imputation'] ?? ''));
                $row['SERVICE'] = strtoupper(trim($row['SERVICE'] ?? ''));

                // Vérifier la validité de TYPE FORFAIT
                $forfait = DB::table('forfait')->where('nom_forfait', trim($row['TYPE FORFAIT'] ?? ''))->first();
                if (!$forfait) {
                    Log::info('Ligne ignorée : TYPE FORFAIT introuvable.', ['row' => $row]);
                    return;
                }

                // Insérer dans 'fonction'
                $fonction = DB::table('fonction')->where('fonction', $row['Fonction'])->first();
                if (!$fonction) {
                    $id_fonction = DB::table('fonction')->insertGetId([
                        'fonction' => $row['Fonction'],
                        'created_at' => now(),
                        'updated_at' => now()
                    ], 'id_fonction');
                    $fonction = DB::table('fonction')->where('id_fonction', $id_fonction)->first();
                }

                // Gestion du 'SERVICE' (libelle_service)
                $originalService = strtoupper(trim($row['SERVICE'] ?? 'NEANT'));
                $libelleService = $originalService;

                // Vérifier si le service est un faux service
                $isFauxService = false;
                foreach ($fauxServicesMap as $realService => $fauxServices) {
                    if (in_array($originalService, $fauxServices)) {
                        $libelleService = $realService; // Remplace par le service réel
                        $isFauxService = true; // Indique qu'il s'agit d'un faux service
                        break;
                    }
                }

                $service = DB::table('service')
                    ->where('libelle_service', $libelleService)
                    ->first();

                if (!$service) {
                    $id_service = DB::table('service')->insertGetId([
                        'libelle_service' => $libelleService,
                        'created_at' => now(),
                        'updated_at' => now()
                    ], 'id_service');
                    $service = DB::table('service')->where('id_service', $id_service)->first();
                }

                // Gestion de l'imputation (libelle_imputation)
                $libelleImputation = $row['Libelle Imputation'] ?: 'NEANT';

                // Ajouter le faux service à la fin si c'est un faux service
                if ($isFauxService) {
                    $libelleImputation = $libelleImputation . ' - ' . $originalService;
                }

                $imputation = DB::table('imputation')
                    ->where('libelle_imputation', $libelleImputation)
                    ->first();

                if (!$imputation) {
                    $id_imputation = DB::table('imputation')->insertGetId([
                        'libelle_imputation' => $libelleImputation,
                        'id_service' => $service->id_service,
                        'created_at' => now(),
                        'updated_at' => now()
                    ], 'id_imputation');
                    $imputation = DB::table('imputation')->where('id_imputation', $id_imputation)->first();
                }

                // Construire la localisation
                $localisationValue = $libelleService . ' - ' . $libelleImputation;

                $localisation = DB::table('localisation')
                    ->where('localisation', $localisationValue)
                    ->where('id_service', $service->id_service)
                    ->where('id_imputation', $imputation->id_imputation)
                    ->first();

                if (!$localisation) {
                    $id_localisation = DB::table('localisation')->insertGetId([
                        'localisation' => $localisationValue,
                        'id_service' => $service->id_service,
                        'id_imputation' => $imputation->id_imputation,
                        'created_at' => now(),
                        'updated_at' => now()
                    ], 'id_localisation');
                    $localisation = DB::table('localisation')->where('id_localisation', $id_localisation)->first();
                }

                // Gestion des noms et prénoms
                $nomPrenomParts = explode(' ', trim($row['Nom et Prenom'] ?? ''));
                $nom = strtoupper($nomPrenomParts[0] ?? 'INCONNU');
                $prenom = self::formatPrenom(implode(' ', array_slice($nomPrenomParts, 1)));

                // Gestion du login
                $login = trim($row['Login']);
                if (empty($login)) {
                    $baseLogin = substr($nom, 0, 6) . substr($prenom, 0, 1);
                    $login = $baseLogin;
                    $counter = 1;

                    while (DB::table('utilisateur')->where('login', $login)->exists()) {
                        $login = $baseLogin . $counter;
                        $counter++;
                    }
                }

                // Vérifiez si un utilisateur avec le même nom et prénom existe
                $utilisateur = DB::table('utilisateur')
                    ->where('nom', $nom)
                    ->where('prenom', $prenom)
                    ->first();

                if (!$utilisateur) {
                    $utilisateurId = DB::table('utilisateur')->insertGetId([
                        'nom' => $nom,
                        'prenom' => $prenom,
                        'login' => $login,
                        'id_type_utilisateur' => 1,
                        'id_fonction' => $fonction->id_fonction,
                        'id_localisation' => $localisation->id_localisation,
                        'created_at' => now(),
                        'updated_at' => now()
                    ], 'id_utilisateur');
                }

                // Insérer dans 'ligne'
                DB::table('ligne')->insert([
                    'num_ligne' => $row['Numero2'],
                    'num_sim' => random_int(10000000000000, 99999999999999),
                    'id_forfait' => $forfait->id_forfait,
                    'id_statut_ligne' => 1,
                    'id_type_ligne' => 1,
                    'id_operateur' => 34,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            });
        } catch (\Exception $e) {
            Log::error('Erreur lors du traitement d\'une ligne : ' . $e->getMessage(), [
                'row' => $row,
                'stack' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }   

    /**
     * Formater le prénom : chaque mot commence par une majuscule.
     *
     * @param string $prenom
     * @return string
     */
    private static function formatPrenom($prenom)
    {
        return ucwords(strtolower(trim($prenom)));
    }


    //********************** SECTION EQUIPEMENT **********************//
    /**
     * Traite et filtre les données du fichier CSV.
     *
     * @param string $filePath
     * @return array
     */
    public static function equipementCSV($filePath)
    {
        $data = [];
        if (($handle = fopen($filePath, 'r')) !== false) {
            $headers = fgetcsv($handle, 0, ';');
            $headers[0] = preg_replace('/[\x{FEFF}]/u', '', $headers[0]); // Enlever le BOM (UTF-8)

            $allowedHeaders = ['SMARTPHONE', 'Enrolle', 'Marque', 'Type', 'SN'];
            $headers = array_map('trim', $headers);

            // Mapper les colonnes du fichier CSV aux colonnes autorisées
            $headerMap = array_flip(array_intersect($headers, $allowedHeaders));

            while (($row = fgetcsv($handle, 0, ';')) !== false) {
                $rowData = [];
                foreach ($headerMap as $key => $index) {
                    $rowData[$key] = $row[$index] ?? null;
                }

                // Filtrer uniquement les lignes valides (par exemple, colonne SMARTPHONE non vide)
                if (!empty($rowData['SMARTPHONE']) && !empty($rowData['Marque']) && !empty($rowData['Type'])) {
                    $data[] = $rowData;
                }
            }
            fclose($handle);
        }

        return $data;
    }

    /**
     * Insère les données en batch dans la base de données.
     *
     * @param array $filteredData
     * @return int Nombre de lignes insérées
     */
    public static function batchInsertEquipement(array $filteredData)
    {
        $insertedCount = 0;
    
        // Diviser les données en chunks pour un traitement par lots
        $chunks = array_chunk($filteredData, 1000);
    
        foreach ($chunks as $chunk) {
            DB::transaction(function () use ($chunk, &$insertedCount) {
                foreach ($chunk as $row) {
                    try {
                        // Traiter chaque ligne individuellement
                        self::importRowEquipement($row);
                        $insertedCount++;
                    } catch (\Exception $e) {
                        Log::error('Erreur lors du traitement d\'une ligne CSV : ' . $e->getMessage(), [
                            'row' => $row,
                            'stack' => $e->getTraceAsString(),
                        ]);
                    }
                }
            });
        }
    
        return $insertedCount;
    }
    
    public static function importRowEquipement(array $row)
    {
        // 1. Déterminer `id_type_equipement` (SMARTPHONE ou TELEPHONE_TOUCHE)
        $idTypeEquipement = ($row['SMARTPHONE'] === 'O')
            ? TypeEquipement::SMARTPHONE
            : TypeEquipement::TELEPHONE_TOUCHE;
    
        // 2. Gérer la colonne Enrolle (O -> true, N/vide -> false)
        $enrole = ($row['Enrolle'] === 'O');
    
        // 3. Normaliser la marque et vérifier si elle existe déjà
        $marqueNom = strtoupper(trim($row['Marque']));
        $marque = DB::table('marque')->where('marque', $marqueNom)->first();
    
        if (!$marque) {
            // Générer un nouvel ID pour la marque si elle n'existe pas
            $idMarque = Marque::generateId($idTypeEquipement);
            DB::table('marque')->insert([
                'id_marque' => $idMarque,
                'marque' => $marqueNom,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $idMarque = $marque->id_marque;
        }
    
        // 4. Normaliser le type (modèle) et vérifier si le modèle existe déjà
        $modeleNom = strtoupper(trim($row['Type']));
        $modele = DB::table('modele')->where('nom_modele', $modeleNom)->where('id_marque', $idMarque)->first();
    
        if (!$modele) {
            // Générer un nouvel ID pour le modèle si il n'existe pas
            $idModele = Modele::generateId($idMarque);
            DB::table('modele')->insert([
                'id_modele' => $idModele,
                'nom_modele' => $modeleNom,
                'id_marque' => $idMarque,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $idModele = $modele->id_modele;
        }
    
        // 5. Insérer l'équipement dans la table `equipement`
        DB::table('equipement')->insert([
            'imei' => null, // Si le CSV ne fournit pas l'IMEI
            'serial_number' => $row['SN'] ?? null,
            'enrole' => $enrole,
            'id_type_equipement' => $idTypeEquipement,
            'id_modele' => $idModele,
            'id_statut_equipement' => StatutEquipement::STATUT_NOUVEAU, // Par défaut : STATUT_NOUVEAU
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
    
}
