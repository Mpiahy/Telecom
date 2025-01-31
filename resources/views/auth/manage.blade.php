@extends('base/baseIndex')

<head>
    <title>Comptes - Telecom</title>
    <style>
        /* Style général */
        label, td, .input-group-text {
            color: #0a4866 !important;
        }

        /* Design de la table */
        .table {
            border-radius: 8px;
            overflow: hidden;
        }

        .table thead {
            background-color: #0a4866;
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
                <i class="fas fa-plus-circle"></i>
            </span>
            <span class="text">Ajouter un compte</span>
        </a>
    </div>

    <div class="card shadow">
        <div class="card-header py-3">
            <p class="m-0 fw-bold" style="color: #0a4866;">Comptes</p>
        </div>
        <div class="card-body">
            <div class="row mt-2">
                <div class="col">
                    <div class="btn-group" role="group">
                        <button class="btn btn-outline-primary active">Tout</button>
                        <button class="btn btn-outline-primary">Admin</button>
                        <button class="btn btn-outline-primary">Invité</button>
                    </div>
                </div>
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
                            <th>Nom et Prénom(s)</th>
                            <th>Login</th>
                            <th>Email</th>
                            <th>Accès</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($accounts as $account)
                        <tr id="row-{{ $account->id }}">
                            <td>{{ $account->nom_usr }} {{ $account->prenom_usr }}</td>
                            <td>{{ $account->login }}</td>
                            <td>{{ $account->email }}</td>
                            <td id="type-{{ $account->id }}">{{ $account->isAdmin ? 'Admin' : 'Invité' }}</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-warning btn-action" onclick="toggleType({{ $account->id }})">
                                    <i class="fas fa-exchange-alt"></i> Changer Accès
                                </button>
                                <button class="btn btn-sm btn-outline-danger btn-action" onclick="disableAccount({{ $account->id }})">
                                    <i class="fas fa-ban"></i> Désactiver
                                </button>
                                <button class="btn btn-sm btn-outline-info btn-action" onclick="resetPassword({{ $account->id }})">
                                    <i class="fas fa-key"></i> Réinitialiser
                                </button>
                                <br>
                                <small id="pwd-{{ $account->id }}" class="text-success fw-bold"></small>
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
    <div id="liveToast" class="toast bg-success text-white" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto">Succès</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body" id="toast-message"></div>
    </div>
</div>

<script>
    function showToast(message) {
        document.getElementById('toast-message').innerText = message;
        let toast = new bootstrap.Toast(document.getElementById('liveToast'));
        toast.show();
    }

    function toggleType(id) {
        fetch(`/toggle-type/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({})
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('type-' + id).innerText = data.newType;
            showToast('Le type a été modifié avec succès.');
        })
        .catch(error => console.error('Erreur:', error));
    }

    function disableAccount(id) {
        if (confirm('Voulez-vous vraiment désactiver ce compte ?')) {
            fetch(`/disable-account/${id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(() => {
                document.getElementById('row-' + id).remove();
                showToast('Compte désactivé avec succès.');
            })
            .catch(error => console.error('Erreur:', error));
        }
    }

    function resetPassword(id) {
        fetch(`/reset-password/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({})
        })
        .then(response => response.json())
        .then(data => {
            if (data.newPassword) {
                document.getElementById('pwd-' + id).innerText = "Nouveau mot de passe : " + data.newPassword;
                showToast('Mot de passe réinitialisé avec succès.');
            } else {
                showToast('Erreur : ' + data.error);
            }
        })
        .catch(error => console.error('Erreur:', error));
    }

</script>

@endsection
