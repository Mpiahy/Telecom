<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Localisation extends Model
{
    use HasFactory;
    protected $table = 'localisation';
    protected $primaryKey = 'id_localisation';
    protected $fillable = ['localisation', 'id_service', 'id_imputation'];

    public function service()
    {
        return $this->belongsTo(Service::class, 'id_service');
    }

    public function imputation()
    {
        return $this->belongsTo(Imputation::class, 'id_imputation');
    }

    // Méthode pour filtrer par service
    public function scopeFilterByService($query, $filterService)
    {
        if ($filterService) {
            $query->whereHas('service', function ($q) use ($filterService) {
                $q->where('id_service', 'like', "%{$filterService}%");
            });
        }
        // Ajouter un tri par les plus récents
        return $query->orderBy('created_at', 'desc');
    }

    // Méthode pour filtrer par libelle imputation
    public function scopeFilterByImputation($query, $searchImputation)
    {
        if ($searchImputation) {
            $query->whereHas('imputation', function ($q) use ($searchImputation) {
                $q->where('libelle_imputation', 'like', "%{$searchImputation}%");
            });
        }
        return $query;
    }

    // Recherche d'un Chantier
    public function scopeSearchByTerm(Builder $query, $term)
    {
        if ($term) {
            return $query->where('localisation', 'ILIKE', "%{$term}%");
        }

        return $query;
    }

    /**
     * Crée une localisation avec les relations nécessaires.
     */
    public static function createWithRelations(array $data)
    {
        $service = Service::findOrFail($data['add_service']);

        $imputation = Imputation::create([
            'libelle_imputation' => strtoupper($data['add_imp']),
            'id_service' => $service->id_service,
        ]);

        $localisation_value = "{$service->libelle_service} - {$imputation->libelle_imputation}";
        return self::create([
            'localisation' => $localisation_value,
            'id_service' => $service->id_service,
            'id_imputation' => $imputation->id_imputation,
        ]);
    }

    /**
     * Met à jour une localisation avec ses relations.
     */
    public function updateWithRelations(array $data)
    {
        $service = Service::findOrFail($data['edt_service']);

        // Met à jour l'imputation
        $this->imputation->update([
            'libelle_imputation' => strtoupper($data['edt_imp']),
        ]);

        // Met à jour la localisation
        $this->update([
            'localisation' => "{$service->libelle_service} - {$this->imputation->libelle_imputation}",
            'id_service' => $service->id_service,
        ]);
    }

    /**
     * Supprime une localisation avec vérification des relations.
     */
    public function deleteWithRelations()
    {
        $imputation = $this->imputation;

        if ($imputation && $imputation->localisations()->count() <= 1) {
            $imputation->delete();
        }

        $service = $this->service;

        if ($service && $service->imputations()->count() <= 1 && $service->localisations()->count() <= 1) {
            $service->delete();
        }

        $this->delete();
    }
}
