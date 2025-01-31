@extends('base/baseIndex')

<head>
    <title>Imports - Telecom</title>
</head>

@section('content_index')
<!-- Affichage du message d'erreur si présent dans la session -->
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
        // Sélectionne l'élément du message d'erreur
        const errorMessage = document.getElementById('error-message');
        
        if (errorMessage) {
            // Affiche le message d'erreur
            errorMessage.style.display = 'block';

            // Masque le message après 5 secondes
            setTimeout(() => {
                errorMessage.style.display = 'none';
            }, 5000);
        }
    });
</script>

<div class="container-fluid">

    <!-- Toasts Bootstrap -->
    <div aria-live="polite" aria-atomic="true" class="position-relative">
        <div class="toast-container position-fixed top-0 end-0 p-3">
            <!-- Toast de succès -->
            @if (session('success'))
            <div class="toast align-items-center text-bg-success border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        {{ session('success') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
            @endif

            <!-- Toast d'erreur -->
            @if (session('error'))
            <div class="toast align-items-center text-bg-danger border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        {{ session('error') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Script pour afficher automatiquement les toasts -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toastElList = [].slice.call(document.querySelectorAll('.toast'));
            toastElList.map(function (toastEl) {
                const toast = new bootstrap.Toast(toastEl, { delay: 25000 }); // Disparaît après 25s
                toast.show();
            });
        });
    </script>

    <!-- Titre principal -->
    <div class="d-sm-flex justify-content-between align-items-center mb-4">
        <h4 class="text-dark mb-0">
            <i class="fas fa-file-import"></i> Import Lignes, Utilisateurs et Téléphones
        </h4>
    </div>

    <!-- Row pour contenir les deux formulaires côte à côte -->
    <div class="row gy-4">
        <!-- Colonne Import Utilisateurs -->
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white py-3">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-file-csv"></i> Importer un fichier CSV - SUIVI FLOTTE
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('import.process') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="csvFile" class="font-weight-bold">Sélectionnez le fichier CSV</label>
                            <input 
                                type="file" 
                                name="csv_file" 
                                id="csvFile" 
                                class="form-control @error('csv_file') is-invalid @enderror" 
                                accept=".csv" 
                                required
                            >
                            @error('csv_file')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary mt-2">
                                <i class="fas fa-upload"></i> Importer
                            </button>
                        </div>
                        <div class="text-center mt-4">
                            <a href="{{ route('export.example', 'csv') }}" class="btn btn-outline-secondary me-2">
                                <i class="fas fa-file-csv"></i> Exemple CSV
                            </a>
                            <a href="{{ route('export.example', 'xlsx') }}" class="btn btn-outline-success">
                                <i class="fas fa-file-excel"></i> Exemple XLSX
                            </a>
                        </div>                
                        <p class="mt-2 text-center">
                            <i class="fas fa-info-circle text-warning"></i> Ce ne sont que des exemples de colonnes obligatoires.
                        </p>
                    </form>
                </div>
            </div>
        </div>

        <!-- Colonne Import Téléphones -->
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white py-3">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-file-import"></i> Importer un fichier CSV - SUIVI TELEPHONE
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('import.equipement') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="csvFileEquipement" class="font-weight-bold">Sélectionnez le fichier CSV</label>
                            <input 
                                type="file" 
                                name="csv_file_equipement" 
                                id="csvFileEquipement" 
                                class="form-control @error('csv_file_equipement') is-invalid @enderror" 
                                accept=".csv" 
                                required
                            >
                            @error('csv_file_equipement')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="text-center">
                            <button type="submit" class="text-white btn btn-info mt-2">
                                <i class="fas fa-upload"></i> Importer
                            </button>
                        </div>
                        <div class="text-center mt-4">
                            <a href="{{ route('export.example.equipement', 'csv') }}" class="btn btn-outline-secondary me-2">
                                <i class="fas fa-file-csv"></i> Exemple CSV
                            </a>
                            <a href="{{ route('export.example.equipement', 'xlsx') }}" class="btn btn-outline-success">
                                <i class="fas fa-file-excel"></i> Exemple XLSX
                            </a>
                        </div>       
                        <p class="mt-2 text-center">
                            <i class="fas fa-info-circle text-warning"></i> Ce ne sont que des exemples de colonnes obligatoires.
                        </p>         
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Instructions -->
    <div class="row mt-4">
        <div class="col-lg-6">
            <div class="alert alert-primary shadow-sm rounded">
                <h5 class="fw-bold mb-3">
                    <i class="fas fa-info-circle text-primary"></i> Instructions pour l'import des Utilisateurs
                </h5>
                <p class="mb-4">
                    Veuillez vous assurer que le fichier CSV contient les colonnes suivantes. Les colonnes doivent être correctement nommées (respect des <strong>majuscules, minuscules, accents, etc</strong>) :
                </p>
                <ul class="list-unstyled mb-4 ps-3">
                    <li class="mb-2">
                        <code class="bg-light px-1 py-1 rounded">Numero2</code> 
                        <span class="text-muted ms-2">(exemple : 0340502524)</span>
                    </li>
                    <li class="mb-2">
                        <code class="bg-light px-1 py-1 rounded">Login</code> 
                        <span class="text-muted ms-2">(exemple : RAKOTOE2)</span>
                    </li>
                    <li class="mb-2">
                        <code class="bg-light px-1 py-1 rounded">Nom et Prenoms</code> 
                        <span class="text-muted ms-2">(exemple : RAKOTOARISOA Eliot)</span>
                    </li>
                    <li class="mb-2">
                        <code class="bg-light px-1 py-1 rounded">Fonction</code> 
                        <span class="text-muted ms-2">(exemple : Ingénieur IT)</span>
                    </li>
                    <li class="mb-2">
                        <code class="bg-light px-1 py-1 rounded">SERVICE</code> 
                        <span class="text-muted ms-2">(exemple : ADM)</span>
                    </li>
                    <li class="mb-2">
                        <code class="bg-light px-1 py-1 rounded">Libelle Imputation</code> 
                        <span class="text-muted ms-2">(exemple : 2200001AD001 - Service Info - 300800)</span>
                    </li>
                    <li class="mb-2">
                        <code class="bg-light px-1 py-1 rounded">TYPE FORFAIT</code> 
                        <span class="text-muted ms-2">(exemple : Forfait 3)</span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="alert alert-info shadow-sm rounded">
                <h5 class="fw-bold mb-3">
                    <i class="fas fa-info-circle text-info"></i> Instructions pour l'import des Téléphones
                </h5>
                <p class="mb-4">
                    Veuillez vous assurer que le fichier CSV contient les colonnes suivantes. Les colonnes doivent être correctement nommées (respect des <strong>majuscules, minuscules, accents, etc</strong>) :
                </p>
                <ul class="list-unstyled mb-4 ps-3">
                    <li class="mb-2">
                        <code class="bg-light px-1 py-1 rounded">SMARTPHONE</code> 
                        <span class="text-muted ms-2">(exemple : O [Oui ou Non])</span>
                    </li>
                    <li class="mb-2">
                        <code class="bg-light px-1 py-1 rounded">Enrolle</code> 
                        <span class="text-muted ms-2">(exemple : O [Oui ou Non] si vide, N)</span>
                    </li>
                    <li class="mb-2">
                        <code class="bg-light px-1 py-1 rounded">Marque</code> 
                        <span class="text-muted ms-2">(exemple : Samsung)</span>
                    </li>
                    <li class="mb-2">
                        <code class="bg-light px-1 py-1 rounded">Type</code> 
                        <span class="text-muted ms-2">(exemple : A05)</span>
                    </li>
                    <li class="mb-2">
                        <code class="bg-light px-1 py-1 rounded">SN</code> 
                        <span class="text-muted ms-2">(exemple : 351186744997026)</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Section d'instructions -->
    <div class="alert alert-danger mt-4 p-4 shadow-sm rounded" style="border-left: 5px solid #0d6efd;">
        <h5 class="fw-bold mb-3">
            <i class="fas fa-info-circle text-danger"></i> Instructions Générales pour l'import
        </h5>
    
        <p class="fw-bold mb-3">Estimation du temps de traitement selon le nombre de lignes :</p>
        <table class="table table-bordered table-hover text-center align-middle mb-4">
            <thead class="table-light">
                <tr>
                    <th>Nombre de lignes</th>
                    <th>Temps attendu (moyen)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>100 lignes</td>
                    <td>1 à 3 secondes</td>
                </tr>
                <tr>
                    <td>500 lignes</td>
                    <td>10 à 20 secondes</td>
                </tr>
                <tr>
                    <td>1000 lignes</td>
                    <td>30 à 40 secondes</td>
                </tr>
                <tr>
                    <td>5000 lignes</td>
                    <td>3 à 5 minutes</td>
                </tr>
            </tbody>
        </table>
        <p class="mb-0">
            <strong class="text-danger">Note :</strong> Veuillez vérifier attentivement le format et les colonnes avant d'importer le fichier. En cas de doute, contactez le support.
        </p>
    </div>

</div>
@endsection
