@extends('base/baseIndex')

<head>
    <title>Simulations - Telecom</title>
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
            <i class="fas fa-gamepad"></i> Simulations
        </h4>
    </div>

    <!-- Formulaire de simulation -->
    <div class="card shadow-sm mb-4" style="max-width: 800px;">
        <div class="card-header bg-primary text-white py-3">
            <h6 class="m-0 font-weight-bold">
                <i class="fas fa-random"></i> Simuler Affectations
            </h6>
        </div>
        <div class="card-body">
            <div class="alert alert-warning p-4 shadow-sm rounded" style="max-width: 800px; border-left: 5px solid #0d6efd;">
                <p class="card-text">
                    <i class="fas fa-info-circle text-warning"></i>
                    Cliquez sur le bouton ci-dessous pour générer des affectations aléatoires entre utilisateurs lignes et équipements.
                    Une affectation sera créée pour chaque utilisateur, ligne et équipement avec des dates aléatoires.
                </p>
            </div>
            <form action="{{ route('simulation.run') }}" method="POST">
                @csrf
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-random"></i> Simuler Affectations
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
