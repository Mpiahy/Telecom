@extends('base/baseRef')
<head>
    <title>Utilisateurs - Telecom</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<style>
    /* Couleurs pour le style global */
    td, .input-group-text {
        color: #0a4866 !important; /* Appliquer le style globalement */
    }

</style>    

@section('content_ref')

    <div class="container-fluid">
        <!-- Contenu principal -->
        <h3 class="text-dark" style="color: #0a4866;">
            <i class="fas fa-users" style="padding-right: 5px;"></i>Utilisateurs
        </h3>

        <!-- Toast container -->
        <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1055;">
            <!-- Toast for Success Message -->
            @if (session('success'))
                <div class="toast align-items-center text-bg-success border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            {{ session('success') }}
                        </div>
                        <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            @endif

            <!-- Toast for Error Message -->
            @if (session('error'))
                <div class="toast align-items-center text-bg-danger border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            {{ session('error') }}
                        </div>
                        <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            @endif

            <!-- Toast for Validation Errors -->
            @if ($errors->any())
                <div class="toast align-items-center text-bg-danger border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            <strong>Veuillez corriger les erreurs suivantes :</strong>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            @endif
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const toastElList = [].slice.call(document.querySelectorAll('.toast'));
                const toastList = toastElList.map(function (toastEl) {
                    return new bootstrap.Toast(toastEl, { delay: 5000 }); // Durée de 5 secondes
                });

                toastList.forEach(toast => toast.show());
            });
        </script>

        <div class="text-center mb-4">
            <a class="btn btn-primary btn-icon-split" role="button" data-bs-target="#modal_add_emp" data-bs-toggle="modal">
                <span class="icon" style="padding-right: 12px;">
                    <i class="fas fa-plus-circle" style="padding-top: 5px;"></i>
                </span>
                <span class="text">Ajouter un utilisateur</span>
            </a>
        </div>
        <div class="card shadow">
            <div class="card-header py-3">
                <p class="m-0 fw-bold" style="color: #0a4866;">Gestion des utilisateurs</p>
            </div>
            <div class="card-body">
                <div class="row mt-2">
                    {{-- Rechercher par nom et prénom --}}
                    <div class="col">
                        <form action="{{ route('ref.user') }}" method="get">
                            <div class="input-group">
                                <span class="input-group-text">Utilisateur</span>
                                <input class="form-control" type="text" placeholder="Rechercher par nom et prénom(s)" name="search_user_name" value="{{ request('search_user_name') }}" />
                                <input type="hidden" name="type" value="{{ request('type') }}"> <!-- Conserver le filtre actif par type -->
                                <button class="btn btn-primary" type="submit">Rechercher</button>
                            </div>
                        </form>
                    </div>
                    {{-- Rechercher par Login --}}
                    <div class="col">
                        <form action="{{ route('ref.user') }}" method="get">
                            <div class="input-group">
                                <span class="input-group-text">Login</span>
                                <input class="form-control" type="text" placeholder="Rechercher par Login" name="search_user_login" value="{{ request('search_user_login') }}" />
                                <input type="hidden" name="type" value="{{ request('type') }}"> <!-- Conserver le filtre actif par type -->
                                <button class="btn btn-primary" type="submit">Rechercher</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row mt-2">
                    <!-- Filtres par type d'utilisateur -->
                    <div class="col">
                        <form id="filterForm" method="get" action="{{ route('ref.user') }}">
                            <div class="btn-group" role="group">
                                <!-- Bouton "Tous les utilisateurs" -->
                                <button class="btn btn-outline-primary {{ request('type') ? '' : 'active' }}" type="submit" name="type" value="">Tous</button>

                                <!-- Boutons pour les types d'utilisateurs -->
                                @foreach ($types as $type)
                                    <button
                                        class="btn btn-outline-primary {{ request('type') == $type->type_utilisateur ? 'active' : '' }}"
                                        type="submit"
                                        name="type"
                                        value="{{ $type->type_utilisateur }}">
                                        {{ $type->type_utilisateur }}
                                    </button>
                                @endforeach
                            </div>
                        </form>
                    </div>
                    {{-- Rechercher par Localisation --}}
                    <div class="col">
                        <form action="{{ route('ref.user') }}" method="get">
                            <div class="input-group">
                                <span class="input-group-text">Localisation</span>
                                <input class="form-control" type="text" placeholder="Rechercher par Localisation" name="search_user_chantier" value="{{ request('search_user_chantier') }}" />
                                <input type="hidden" name="type" value="{{ request('type') }}"> <!-- Conserver le filtre actif par type -->
                                <button class="btn btn-primary" type="submit">Rechercher</button>
                            </div>
                        </form>
                    </div>
                    
                </div>
                <div id="dataTable-1" class="table-responsive mt-2">
                    <table id="dataTable" class="table table-hover table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">Matricule</th>
                                <th>Nom et Prénom(s)</th>
                                <th>Login</th>
                                <th class="text-center">Type</th>
                                <th>Fonction</th>
                                <th>Localisation</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($utilisateurs as $utilisateur)
                                <tr class="utilisateur-row {{ strtolower($utilisateur->typeUtilisateur->type_utilisateur) }}">
                                    <td class="text-center py-2 px-3">{{ $utilisateur->matricule ?? 'N/A' }}</td>
                                    <td class="text-wrap" style="word-break: break-word;">
                                        {{ $utilisateur->nom }} {{ $utilisateur->prenom }}
                                    </td>
                                    <td>{{ $utilisateur->login }}</td>
                                    <td class="text-center py-2 px-3">{{ $utilisateur->typeUtilisateur->type_utilisateur ?? 'N/A' }}</td>
                                    <td class="text-wrap" style="word-break: break-word;">
                                        {{ $utilisateur->fonction->fonction ?? 'N/A' }}
                                    </td>
                                    <td class="text-wrap" style="word-break: break-word;">
                                        {{ $utilisateur->localisation->localisation ?? 'N/A' }}
                                    </td>
                                    <!-- Actions -->
                                    <td class="text-center">
                                        @if ($utilisateur->deleted_at)
                                            <!-- Si l'utilisateur est supprimé (soft delete), afficher la date et l'heure de suppression -->
                                            <span class="text-danger">Départ: {{ $utilisateur->deleted_at->format('d/m/Y') }}</span>
                                            <!-- historique -->
                                            <a id="btn_histo_user"
                                                class="text-decoration-none"
                                                data-bs-target="#modal_histo_user"
                                                data-bs-toggle="modal"
                                                title="Historique"
                                                href="{{ url('/user/histoUser/' . $utilisateur->id_utilisateur) }}"
                                                data-id-histo="{{ $utilisateur->id_utilisateur }}"
                                                data-user-histo="{{ $utilisateur->nom }} {{ $utilisateur->prenom }}"
                                                data-login-histo="{{ $utilisateur->login }}"
                                                data-fonction-histo="{{ $utilisateur->fonction->fonction ?? 'N/A' }}"
                                                data-localisation-histo="{{ $utilisateur->localisation->localisation }}">
                                                <i class="fas fa-history text-primary" style="font-size: 20px;"></i>
                                            </a>
                                        @else
                                            <div class="d-flex justify-content-center gap-2">
                                                <!-- Attribuer Ligne -->
                                                <a href="#"
                                                    class="text-decoration-none"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modal_attribuer_ligne"
                                                    data-id-utilisateur-attr-ligne="{{ $utilisateur->id_utilisateur }}"
                                                    data-login-attr-ligne="{{ $utilisateur->login }}"
                                                    data-nom-attr-ligne="{{ $utilisateur->nom }}"
                                                    data-prenom-attr-ligne="{{ $utilisateur->prenom }}"
                                                    title="Attribuer une ligne">
                                                    <i class="fas fa-sim-card text-info" style="font-size: 20px;"></i>
                                                </a>

                                                <!-- Attribuer Équipement -->
                                                <a href="#"
                                                    class="text-decoration-none"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modal_attribuer_equipement"
                                                    data-id-utilisateur-attr="{{ $utilisateur->id_utilisateur }}"
                                                    data-login-attr="{{ $utilisateur->login }}"
                                                    data-nom-attr="{{ $utilisateur->nom }}"
                                                    data-prenom-attr="{{ $utilisateur->prenom }}"
                                                    title="Attribuer un équipement">
                                                    <i class="fas fa-laptop-medical text-success" style="font-size: 20px;"></i>
                                                </a>

                                                <!-- historique -->
                                                <a id="btn_histo_user"
                                                    class="text-decoration-none"
                                                    data-bs-target="#modal_histo_user"
                                                    data-bs-toggle="modal"
                                                    title="Historique"
                                                    href="{{ url('/user/histoUser/' . $utilisateur->id_utilisateur) }}"
                                                    data-id-histo="{{ $utilisateur->id_utilisateur }}"
                                                    data-user-histo="{{ $utilisateur->nom }} {{ $utilisateur->prenom }}"
                                                    data-login-histo="{{ $utilisateur->login }}"
                                                    data-fonction-histo="{{ $utilisateur->fonction->fonction ?? 'N/A' }}"
                                                    data-localisation-histo="{{ $utilisateur->localisation->localisation }}">
                                                    <i class="fas fa-history text-primary" style="font-size: 20px;"></i>
                                                </a>
                        
                                                <!-- Modifier -->
                                                <a href="#"
                                                class="text-decoration-none"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modal_edit_emp"
                                                data-id-edt="{{ $utilisateur->id_utilisateur }}"
                                                data-edt-matricule="{{ $utilisateur->matricule }}"
                                                data-edt-nom="{{ $utilisateur->nom }}"
                                                data-edt-prenom="{{ $utilisateur->prenom }}"
                                                data-edt-login="{{ $utilisateur->login }}"
                                                data-edt-type="{{ $utilisateur->typeUtilisateur->id_type_utilisateur }}"
                                                data-edt-fonction="{{ $utilisateur->fonction->id_fonction ?? '' }}"
                                                data-edt-chantier="{{ $utilisateur->localisation->id_localisation }}"
                                                title="Modifier">
                                                    <i class="far fa-edit text-warning" style="font-size: 20px;"></i>
                                                </a>
                        
                                                <!-- Supprimer -->
                                                <a href="#"
                                                class="text-decoration-none open-delete-modal"
                                                data-bs-target="#supprimer_utilisateur"
                                                data-bs-toggle="modal"
                                                data-id="{{ $utilisateur->id_utilisateur }}"
                                                data-matricule="{{ $utilisateur->matricule ?? 'N/A'}}"
                                                data-name="{{ $utilisateur->nom }} {{ $utilisateur->prenom ?? 'N/A'}}"
                                                data-login="{{ $utilisateur->login ?? 'N/A'}}"
                                                data-type="{{ $utilisateur->typeUtilisateur->type_utilisateur ?? 'N/A'}}"
                                                data-fonction="{{ $utilisateur->fonction->fonction ?? 'N/A' }}"
                                                data-chantier="{{ $utilisateur->localisation->localisation ?? 'N/A'}}"
                                                title="Départ">
                                                    <i class="fas fa-sign-out-alt text-danger" style="font-size: 20px;"></i>
                                                </a>
                                            </div>
                                        @endif
                                    </td>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Aucun utilisateur trouvé.</td>
                                    </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Liens de pagination -->
                <div class="mt-4">
                    {{ $utilisateurs->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>

            </div>
        </div>
    </div>

@endsection


@section('modal_ref')

    @include('modals.userModal')

@endsection

@section('scripts')

    @include('js.userJs')

@endsection