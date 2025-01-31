<?php

namespace App\Services;

use App\Models\Affectation;
use App\Models\Ligne;
use App\Models\Equipement;
use App\Models\StatutLigne;
use App\Models\StatutEquipement;
use App\Models\Utilisateur;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class SimulationService
{
    /**
     * Exécute la simulation des affectations.
     *
     * @return string Message de succès ou d'erreur.
     */
    public function run()
    {
        try {
            // Étape 1 : Récupération des données
            $utilisateurs = Utilisateur::all();
            $lignes = Ligne::all();
            $equipements = Equipement::all();

            // Étape 2 : Vérifications de préconditions
            if ($utilisateurs->isEmpty()) {
                throw new Exception("Aucun utilisateur trouvé pour la simulation.");
            }

            if ($lignes->isEmpty() && $equipements->isEmpty()) {
                throw new Exception("Aucune ligne ni équipement disponible pour la simulation.");
            }

            // Étape 3 : Mélanger les ressources
            // $lignes = $lignes->shuffle();
            // $equipements = $equipements->shuffle();

            // Étape 4 : Exécution dans une transaction
            DB::transaction(function () use ($utilisateurs, $lignes, $equipements) {
                // Liste des utilisateurs pour réassigner les ressources restantes
                $utilisateursLoop = $utilisateurs->toArray(); 

                // 1. Itérer sur tous les utilisateurs et assigner 1 ressource à chacun
                foreach ($utilisateurs as $utilisateur) {
                    if ($lignes->isNotEmpty()) {
                        // Si une ligne est disponible, attribuer une ligne
                        $ligne = $lignes->pop();
                        $this->createAffectation($utilisateur, $ligne, null);
                        Log::info("Ligne ID {$ligne->id_ligne} attribuée à l'utilisateur ID {$utilisateur->id_utilisateur}");
                    } elseif ($equipements->isNotEmpty()) {
                        // Sinon, attribuer un équipement
                        $equipement = $equipements->pop();
                        $this->createAffectation($utilisateur, null, $equipement);
                        Log::info("Équipement ID {$equipement->id_equipement} attribué à l'utilisateur ID {$utilisateur->id_utilisateur}");
                    }
                }

                // 2. Assigner les ressources restantes (lignes ou équipements) aux utilisateurs de manière aléatoire
                while ($lignes->isNotEmpty() || $equipements->isNotEmpty()) {
                    $utilisateur = $utilisateurs->random(); // Choisir un utilisateur au hasard

                    if ($lignes->isNotEmpty()) {
                        $ligne = $lignes->pop();
                        $this->createAffectation($utilisateur, $ligne, null);
                        Log::info("Ligne ID {$ligne->id_ligne} attribuée (supplémentaire) à l'utilisateur ID {$utilisateur->id_utilisateur}");
                    } elseif ($equipements->isNotEmpty()) {
                        $equipement = $equipements->pop();
                        $this->createAffectation($utilisateur, null, $equipement);
                        Log::info("Équipement ID {$equipement->id_equipement} attribué (supplémentaire) à l'utilisateur ID {$utilisateur->id_utilisateur}");
                    }
                }
            });

            // Étape 5 : Retourner un message de succès
            Log::info("Simulation terminée avec succès. Toutes les ressources ont été affectées.");
            return "Simulation effectuée avec succès. Toutes les lignes et tous les équipements ont été affectés.";
        } catch (Exception $e) {
            // En cas d'échec
            Log::error("Erreur lors de la simulation : " . $e->getMessage());
            return "Une erreur est survenue lors de la simulation : " . $e->getMessage();
        }
    }

    /**
     * Crée une affectation pour un utilisateur (ligne ou équipement).
     *
     * @param  \App\Models\Utilisateur $utilisateur
     * @param  \App\Models\Ligne|null $ligne
     * @param  \App\Models\Equipement|null $equipement
     * @throws \Exception
     */
    private function createAffectation($utilisateur, $ligne = null, $equipement = null)
    {
        try {
            // Générer des dates aléatoires
            $debutAffectation = $this->randomDate('2017-01-01', '2027-12-31');
            $finAffectation = null;

            // Décider aléatoirement si l'affectation sera résiliée
            $isActive = rand(0, 1); // 0 = actif, 1 = résilié
            if ($isActive === 1) {
                $finAffectation = $this->randomDate($debutAffectation, '2027-12-31');
            }

            // Créer l'affectation
            Affectation::create([
                'debut_affectation' => $debutAffectation,
                'fin_affectation' => $finAffectation,
                'id_ligne' => $ligne?->id_ligne,
                'id_forfait' => $ligne?->id_forfait,
                'id_equipement' => $equipement?->id_equipement,
                'id_utilisateur' => $utilisateur->id_utilisateur,
            ]);

            // Mettre à jour le statut si une ligne est affectée
            if ($ligne) {
                $ligne->update([
                    'id_statut_ligne' => $finAffectation ? StatutLigne::STATUT_RESILIE : StatutLigne::STATUT_ATTRIBUE,
                ]);
            }

            // Mettre à jour le statut si un équipement est affecté
            if ($equipement) {
                $equipement->update([
                    'id_statut_equipement' => $finAffectation
                        ? StatutEquipement::STATUT_RETOURNE // Si fin d'affectation, statut retourné
                        : StatutEquipement::STATUT_ATTRIBUE, // Sinon, statut attribué
                ]);
            }
        } catch (Exception $e) {
            // Loguer l'erreur si une affectation échoue
            Log::error("Erreur lors de la création de l'affectation pour l'utilisateur ID {$utilisateur->id_utilisateur}: " . $e->getMessage());
            throw new Exception("Échec de l'affectation pour l'utilisateur ID {$utilisateur->id_utilisateur}.");
        }
    }

    /**
     * Génère une date aléatoire entre deux dates.
     *
     * @param  string $startDate
     * @param  string $endDate
     * @return string
     */
    private function randomDate($startDate, $endDate)
    {
        try {
            $timestamp = rand(strtotime($startDate), strtotime($endDate));
            return date('Y-m-d', $timestamp);
        } catch (Exception $e) {
            Log::error("Erreur lors de la génération d'une date aléatoire : " . $e->getMessage());
            throw new Exception("Impossible de générer une date aléatoire.");
        }
    }
}
