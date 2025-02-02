@extends('base/baseIndex')

<head>
    <title>Paramètres - Telecom</title>
</head>

<style>
    /* Couleurs pour le style global */
    label {
        color: #0a4866 !important; /* Appliquer le style globalement */
    }

    :root {
        --bs-primary: #0B4865 !important;
        --bs-primary-rgb: 10, 72, 102 !important;
        }

        .btn-primary, .bg-primary, .btn-outline-primary {
            background-color: #0B4865 !important;
            border-color: #0B4865 !important;
            color: white !important;
        }

        .btn-primary:hover, .btn-outline-primary:hover {
            background-color: #083a52 !important; /* Teinte plus foncée au survol */
            border-color: #083a52 !important;
        }

        .text-primary {
            color: #0B4865 !important;
        }

        .btn-outline-primary {
            color: #0B4865 !important;
        }

        .btn-outline-primary:hover {
            background-color: #0B4865 !important;
            color: white !important;
        }
</style>    

@section('content_index')

<div class="container-fluid">

    <!-- Toast Notifications -->
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1055;">
        @foreach (['success' => 'text-bg-success', 'error' => 'text-bg-danger'] as $key => $class)
            @if (session($key))
                <div class="toast align-items-center {{ $class }} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            {{ session($key) }}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            @endif
        @endforeach
    </div>    

    <!-- Script pour activer les Toasts -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.toast').forEach(toastEl => {
                new bootstrap.Toast(toastEl, { delay: 5000 }).show();
            });
        });
    </script>

    <!-- Titre principal -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-dark mb-1">
            <i class="fas fa-cogs me-2"></i>Paramètres
        </h3>
    </div>

    <div class="row g-3">
        {{-- Infos Perso --}}
        <div class="col-xl-6">
            <div class="card shadow">
                <div class="card-header py-3 bg-light">
                    <h6 class="m-0 fw-bold text-primary">Changez vos informations</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('update_usr') }}" method="post">
                        @csrf
                        <div class="mb-3">
                            <label for="login_usr" class="form-label fw-bold">Login</label>
                            <input id="login_usr" class="form-control @error('login_usr') is-invalid @enderror" 
                                   type="text" placeholder="Entrez votre login" name="login_usr" 
                                   value="{{ old('login_usr', Auth::user()->login) }}">
                            @error('login_usr') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    
                        <div class="mb-3">
                            <label for="email_usr" class="form-label fw-bold">Adresse Email</label>
                            <input id="email_usr" class="form-control @error('email_usr') is-invalid @enderror" 
                                   type="email" placeholder="Entrez votre adresse email" name="email_usr" 
                                   value="{{ old('email_usr', Auth::user()->email) }}" readonly>
                            @error('email_usr') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nom_usr" class="form-label fw-bold">Nom</label>
                                <input id="nom_usr" class="form-control @error('nom_usr') is-invalid @enderror" 
                                       type="text" placeholder="Entrez votre nom" name="nom_usr" 
                                       value="{{ old('nom_usr', Auth::user()->nom_usr) }}">
                                @error('nom_usr') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                    
                            <div class="col-md-6 mb-3">
                                <label for="prenom_usr" class="form-label fw-bold">Prénom</label>
                                <input id="prenom_usr" class="form-control @error('prenom_usr') is-invalid @enderror" 
                                       type="text" placeholder="Entrez votre prénom" name="prenom_usr" 
                                       value="{{ old('prenom_usr', Auth::user()->prenom_usr) }}">
                                @error('prenom_usr') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    
                        <div class="text-center">
                            <button class="btn btn-primary btn-sm" type="submit">Enregistrer</button>
                        </div>
                    </form>
                    
                    <!-- JavaScript -->
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const loginField = document.getElementById('login_usr');
                            const emailField = document.getElementById('email_usr');
                    
                            // Mettre à jour l'adresse email automatiquement
                            loginField.addEventListener('input', function () {
                                const loginValue = loginField.value.trim().toLowerCase(); // Convertir en minuscule
                                if (loginValue) {
                                    emailField.value = `${loginValue}@colas.com`;
                                } else {
                                    emailField.value = ''; // Vider si le login est vide
                                }
                            });
                        });
                    </script>
                    
                </div>
            </div>
        </div>

        {{-- Mot de passe --}}
        <div class="col-xl-6">
            <div class="card shadow">
                <div class="card-header py-3 bg-light">
                    <h6 class="m-0 fw-bold text-primary">Changer votre mot de passe</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('change_pwd') }}" method="post">
                        @csrf
                        <div class="mb-3">
                            <label for="older_pwd" class="form-label fw-bold">Ancien mot de passe</label>
                            <input value="{{ old('older_pwd') }}" required id="older_pwd" class="form-control @error('older_pwd') is-invalid @enderror" type="password" placeholder="Entrez votre ancien mot de passe" name="older_pwd">
                            @error('older_pwd') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="new_pwd" class="form-label fw-bold">Nouveau mot de passe</label>
                            <input value="{{ old('new_pwd') }}" required id="new_pwd" class="form-control @error('new_pwd') is-invalid @enderror" type="password" placeholder="Entrez votre nouveau mot de passe" name="new_pwd">
                            @error('new_pwd') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="new_pwd_confirmation" class="form-label fw-bold">Confirmer votre mot de passe</label>
                            <input value="{{ old('new_pwd_confirmation') }}" required id="new_pwd_confirmation" class="form-control @error('new_pwd') is-invalid @enderror" type="password" placeholder="Confirmez votre nouveau mot de passe" name="new_pwd_confirmation">
                            @error('new_pwd') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="text-center">
                            <button class="btn btn-primary btn-sm" type="submit">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
