@extends('base/baseIndex')

<head>
    <title>Comptes - Telecom</title>
    <style>
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

        /* Style général */
        label, td, .input-group-text {
            color: #0B4865 !important;
        }

        /* Design de la table */
        .table {
            border-radius: 8px;
            overflow: hidden;
        }

        .table thead {
            background-color: #0B4865;
            color: white;
        }

        .table tbody tr:hover {
            background-color: #f1f1f1;
        }

        /* Boutons */
        .btn-action {
            padding: 5px 10px;
            border-radius: 5px;
        }
    </style>
</head>

@section('content_index')

<div class="container-fluid">
    <h3 class="text-dark mb-1">
        <i class="fas fa-users-cog"></i> Gestion des comptes
    </h3>

    <div class="text-center mb-4">
        <a class="btn btn-primary btn-icon-split" role="button" data-bs-target="#modal_add_account" data-bs-toggle="modal">
            <span class="icon">
                <i class="fas fa-plus-circle" style="padding-top: 5px;"></i>
            </span>
            <span class="text">Créer un compte</span>
        </a>
    </div>

    <div class="card shadow">
        <div class="card-header py-3">
            <p class="m-0 fw-bold" style="color: #0B4865;">Comptes</p>
        </div>
        <div class="card-body">
            <div class="row mt-2">
                <div class="col">
                    <form action="search_account">
                        <div class="input-group">
                            <span class="input-group-text">Compte</span>
                            <input class="form-control" type="text" placeholder="Rechercher un Compte" name="search_account">
                            <button class="btn btn-primary" type="submit">Rechercher</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="table-responsive mt-3">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Login</th>
                            <th>Nom et Prénom(s)</th>
                            <th>Email</th>
                            <th>Accès</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($accounts as $account)
                        <tr id="row-{{ $account->id }}">
                            <td>{{ $account->login }}</td>
                            <td>{{ $account->nom_usr }} {{ $account->prenom_usr }}</td>
                            <td>{{ $account->email }}</td>
                            <td id="type-{{ $account->id }}" class="text-primary fw-bold" style="cursor: pointer;" onclick="toggleType({{ $account->id }})">
                                <i id="icon-{{ $account->id }}" class="fas fa-exchange-alt"></i>
                                <span>{{ $account->isAdmin ? 'Admin' : 'Invité' }}</span>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-danger btn-action" onclick="disableAccount({{ $account->id }})">
                                    <i class="fas fa-ban"></i> Désactiver
                                </button>
                                <button class="btn btn-sm btn-outline-info btn-action" onclick="resetPassword({{ $account->id }})">
                                    <i class="fas fa-key"></i> Réinitialiser
                                </button>
                                <br>
                                <small id="pwd-{{ $account->id }}" class="text-dark">
                                    {{ $account->temp_password ? 'Mot de passe : ' . $account->temp_password : 'Aucun mot de passe temporaire' }}
                                </small>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Toast de confirmation -->
<div id="toast-container" class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <i id="toast-icon" class="fas me-2"></i>
            <strong id="toast-title" class="me-auto">Notification</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body" id="toast-message"></div>
    </div>
</div>

@section('modals')

    @include('modals.manageModal')

@endsection


@section('scripts')

    @include('js.manageJs')

@endsection

@endsection
