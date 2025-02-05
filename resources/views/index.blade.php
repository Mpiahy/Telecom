@extends('base/baseIndex')

<head>
    <title>Tableau de bord - Telecom</title>
    <link rel="stylesheet" href="{{ asset('assets/css/jquery.dataTables.min.css') }}">
</head>

<style>
    .kpi-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 10px;
    }
    
    .kpi-card:hover {
        transform: translateY(-5px);
        box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.2);
    }

    .card-title {
        font-size: 1.2rem;
        font-weight: 600;
    }

    .display-5 {
        font-size: 2.5rem;
    }
</style>


@section('content_index')

<!-- Message d'erreur -->
@if (session('error'))
    <div id="error-message" style="
        position: fixed;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        background-color: #f8d7da;
        color: #842029;
        padding: 15px 20px;
        border-radius: 5px;
        border: 1px solid #f5c6cb;
        z-index: 1000;
        box-shadow: 0px 4px 6px rgba(0,0,0,0.1);
        display: none;">
        {{ session('error') }}
    </div>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const errorMessage = document.getElementById('error-message');
        if (errorMessage) {
            errorMessage.style.display = 'block';
            setTimeout(() => {
                errorMessage.style.display = 'none';
            }, 5000);
        }
    });
</script>

<div class="container-fluid">
    <!-- Titre -->
    <div class="d-sm-flex justify-content-between align-items-center mb-4">
        <h3 class="text-dark mb-0"><i class="fas fa-tachometer-alt"></i> Tableau de Bord</h3>
    </div>

    <!-- KPIs améliorés -->
    <div class="row text-center">
        <div class="col-lg-3 col-md-6">
            <div class="card kpi-card bg-primary text-white shadow">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-phone-alt me-2"></i> Lignes Actives</h5>
                    <p class="display-5 fw-bold">{{ $ligneActif ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card kpi-card bg-warning text-white shadow">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-exclamation-circle me-2"></i> Lignes Inactives</h5>
                    <p class="display-5 fw-bold">{{ $ligneInactif ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card kpi-card bg-secondary text-white shadow">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-ban me-2"></i> Lignes Résiliées</h5>
                    <p class="display-5 fw-bold">{{ $ligneResilie ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card kpi-card bg-info text-white shadow">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-hourglass-half me-2"></i> Lignes en Attente</h5>
                    <p class="display-5 fw-bold">{{ $ligneEnAttente ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row text-center mt-3">
        <div class="col-lg-4 col-md-6">
            <div class="card kpi-card bg-secondary text-white shadow">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-laptop-house me-2"></i> Équipements Inactifs</h5>
                    <p class="display-5 fw-bold">{{ $equipementInactif ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="card kpi-card bg-success text-white shadow">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-check-circle me-2"></i> Équipements Actifs</h5>
                    <p class="display-5 fw-bold">{{ $equipementActif ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="card kpi-card bg-danger text-white shadow">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-times-circle me-2"></i> Équipements HS</h5>
                    <p class="display-5 fw-bold">{{ $equipementHS ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulaire de filtrage -->
    <form method="GET" action="{{ route('index') }}" class="mt-4">
        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <label for="annee" class="form-label">Année :</label>
                <input type="number" name="annee" id="annee" class="form-control" value="{{ request('annee', date('Y')) }}" min="2000" max="{{ date('Y') }}" required>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary">Afficher les chiffres</button>
            </div>
        </div>
    </form>

    <!-- Graphiques -->
    <div class="row my-5">
        <div class="col-md-12">
            @if($monthlyData)
                <h4 class="text-center">Dépenses Télécoms {{ $selectedYear }}</h4>
                <canvas id="evolutionChart" class="w-100" style="height: 400px;"></canvas>
                <!-- Boutons d'exportation -->
                <div class="d-flex justify-content-center mt-4 gap-2">
                    <a href="{{ route('export.pdf', ['annee' => request('annee')]) }}" class="btn btn-outline-danger">
                        <i class="fas fa-file-pdf me-2"></i> Export TBD PDF
                    </a>
                    <a href="{{ route('export.xlsx', ['annee' => request('annee')]) }}" class="btn btn-outline-success">
                        <i class="fas fa-file-excel me-2"></i> Export TBD XLSX
                    </a>
                    <a href="{{ route('export.suivi.xlsx', ['annee' => request('annee')]) }}" class="btn btn-outline-info">
                        <i class="fas fa-file-excel me-2"></i> Export SUIVI FLOTTE XLSX
                    </a>                    
                </div>
            @else
                <div class="alert alert-warning text-center">
                    <h5>Aucune donnée disponible</h5>
                    <p>Essayez une autre période ou vérifiez les paramètres.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Graphiques circulaires -->
    <div class="row mt-5">
        <div class="col-md-6 text-center">
            <h5>État des Lignes</h5>
            <canvas id="ligneChart"></canvas>
        </div>
        <div class="col-md-6 text-center">
            <h5>État des Équipements</h5>
            <canvas id="equipementChart"></canvas>
        </div>
    </div>

    <!-- Tableau des données avec meilleur design -->
    <div class="mt-5">
        <h4 class="text-center">Détails des Dépenses</h4>
        <div class="table-responsive">
            <table id="dataTable" class="table table-hover table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th class="text-center">Mois</th>
                        <th class="text-center">Total Forfait HT (MGA)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($monthlyData as $data)
                        <tr>
                            <td class="text-center">{{ $data['mois'] }}</td>
                            <td class="text-center fw-bold">{{ number_format($data['total_prix_forfait_ht'], 2, ',', ' ') }} MGA</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Scripts -->
<script src="{{ asset('assets/js/chart.js') }}"></script>
<script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
<script>
    $(document).ready(function () {
        $('#dataTable').DataTable({
            "paging": false,        // ❌ Supprime la pagination
            "info": false,          // ❌ Supprime "X résultats"
            "searching": false,     // ❌ Supprime la barre de recherche
            "ordering": true,       // ✅ Garde le tri des colonnes activé
            "lengthChange": false,  // ❌ Supprime l'option de changer le nombre d'éléments par page
            "language": {
                "emptyTable": "Aucune donnée disponible",
                "zeroRecords": "Aucun résultat trouvé"
            }
        });
    });
    $(document).ready( function () {
        $('#dataTable').DataTable();
    });

    @if($monthlyData)
        const evolutionCtx = document.getElementById('evolutionChart').getContext('2d');
        new Chart(evolutionCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sept', 'Oct', 'Nov', 'Déc'],
                datasets: [{
                    label: 'Dépenses Télécom (MGA)',
                    data: @json(array_column($monthlyData, 'total_prix_forfait_ht')),
                    borderColor: 'rgba(255, 99, 132, 1)',
                    fill: true
                }]
            }
        });
    @endif

    // Graphique Doughnut - État des Lignes
    new Chart(document.getElementById('ligneChart'), {
        type: 'doughnut',
        data: {
            labels: ['Actifs', 'Inactifs', 'Résiliés', 'En Attente'],
            datasets: [{
                data: [{{ $ligneActif ?? 0 }}, {{ $ligneInactif ?? 0 }}, {{ $ligneResilie ?? 0 }}, {{ $ligneEnAttente ?? 0 }}],
                backgroundColor: ['#4CAF50', '#FFB74D', '#E57373', '#64B5F6'], // Couleurs plus douces
                borderColor: '#ffffff', // Bordure blanche
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Graphique Doughnut - État des Équipements
    new Chart(document.getElementById('equipementChart'), {
        type: 'doughnut',
        data: {
            labels: ['Actifs', 'Inactifs', 'HS'],
            datasets: [{
                data: [{{ $equipementActif ?? 0 }}, {{ $equipementInactif ?? 0 }}, {{ $equipementHS ?? 0 }}],
                backgroundColor: ['#66BB6A', '#FFCA28', '#EF5350'], // Couleurs plus agréables
                borderColor: '#ffffff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>

<style>
    .chart-container {
        width: 100%;
        max-width: 300px; /* Réduit la taille max */
        margin: auto;
    }

    canvas {
        max-width: 100%;
        max-height: 300px; /* Fixe une hauteur max */
    }

    /* Appliquer un fond légèrement gris sur les lignes impaires */
    #dataTable tbody tr:nth-child(odd) {
        background-color: #f9f9f9;
    }

    /* Espacement et bordures douces */
    .table-hover tbody tr:hover {
        background-color: #e9ecef !important;
    }

    .table-bordered th,
    .table-bordered td {
        border: 1px solid #dee2e6 !important;
    }

    /* Titre stylisé */
    h4 {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 20px;
    }

</style>
@endsection
