<div id="modal_histo_user" class="modal fade" role="dialog" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header bg-primary text-white">
                    <h4 id="modalTitle" class="modal-title">Historique d'affectation pour cet Utilisateur</h4>
                </div>
                <div class="modal-body text-dark">
                    <!-- Informations utilisateur -->
                    <div class="row mb-4">
                        <div class="col-md-5">
                                <div class="border rounded p-3 bg-light">
                                <p>
                                    <span class="fw-bold">Nom et prénom(s) :</span>
                                    <span class="fw-normal" data-field="utilisateur"></span>
                                </p>
                                <p>
                                    <span class="fw-bold">Login :</span>
                                    <span class="fw-normal" data-field="login"></span>
                                </p>
                                </div>
                            </div>
                        <div class="col-md-7">
                            <div class="border rounded p-3 bg-light">
                                <p>
                                    <span class="fw-bold">Fonction :</span>
                                    <span class="fw-normal" data-field="fonction"></span>
                                </p>
                                <p>
                                    <span class="fw-bold">Localisation :</span>
                                    <span class="fw-normal" data-field="localisation"></span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Table des affectations -->
                    <div class="table-responsive">
                        <table id="dataTableEquipement" class="table table-bordered table-hover align-middle">
                            <thead class="table-primary">
                                <tr>
                                    <th class="text-dark">Equipement</th>
                                    <th class="text-dark">Type</th>
                                    <th class="text-dark">Imei</th>
                                    <th class="text-dark">SN</th>
                                    <th class="text-dark">Date d'affectation</th>
                                    <th class="text-dark">Date de retour</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Les lignes seront ajoutées dynamiquement ici -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Table des affectations -->
                    <div class="table-responsive">
                        <table id="dataTableLigne" class="table table-bordered table-hover align-middle">
                            <thead class="table-primary">
                                <tr>
                                    <th class="text-dark">Numéro Ligne</th>
                                    <th class="text-dark">Numéro SIM</th>
                                    <th class="text-dark">Forfait</th>
                                    <th class="text-dark">Type</th>
                                    <th class="text-dark">Date d'affectation</th>
                                    <th class="text-dark">Date de retour</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Les lignes seront ajoutées dynamiquement ici -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Commentaire utilisateur -->
                    <div id="userComment" class="mt-4 p-3 bg-light border rounded">
                        <p class="text-dark fw-bold mb-0">Commentaire :</p>
                        <p class="text-dark fw-normal">Ceci est le commentaire lié à l'utilisateur.</p>
                    </div>
                </div>
            </div>
      </div>
</div>
<div id="modal_edit_emp" class="modal" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-primary">Modifier cet utilisateur</h4>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-dark">
                <div class="row">
                    <div class="col">
                        <div></div>
                    </div>
                    <div class="col-xl-8">
                        <div class="card shadow">
                            <div class="card-body">
                                <form id="edit_emp" action="{{ route('modifier.utilisateur') }}" method="POST">
                                    @csrf
                                    <!-- id_utilisateur (caché pour ne pas être modifiable) -->
                                    <input type="hidden" id="id_edt" name="id_edt" />

                                    <!-- Matricule -->
                                    <div class="mb-3">
                                        <label class="form-label" for="matricule_edt"><strong>Matricule</strong></label>
                                        <input id="matricule_edt" class="form-control @error('matricule') is-invalid @enderror" type="text" name="matricule_edt" placeholder="Matricule de l'utilisateur" value="{{ old('matricule') }}" />
                                        @error('nom')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Nom -->
                                    <div class="mb-3">
                                        <label class="form-label" for="nom_edt"><strong>Nom</strong></label>
                                        <input id="nom_edt" class="form-control @error('nom') is-invalid @enderror" type="text" name="nom_edt" placeholder="Nom de l'utilisateur" value="{{ old('nom') }}" />
                                        @error('nom')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Prénom -->
                                    <div class="mb-3">
                                        <label class="form-label" for="prenom_edt"><strong>Prénom(s)</strong></label>
                                        <input id="prenom_edt" class="form-control @error('prenom') is-invalid @enderror" type="text" name="prenom_edt" placeholder="Prénom(s) de l'utilisateur" value="{{ old('prenom') }}" />
                                        @error('prenom')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Login -->
                                    <div class="mb-3">
                                        <label class="form-label" for="login_edt"><strong>Login</strong></label>
                                        <input id="login_edt" class="form-control @error('login') is-invalid @enderror" type="text" name="login_edt" placeholder="Login de l'utilisateur" value="{{ old('login') }}" />
                                        @error('login')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Type d'utilisateur -->
                                    <div class="mb-3">
                                        <label class="form-label" for="id_type_utilisateur_edt"><strong>Type</strong></label>
                                        <select id="type-select-edt" class="form-select @error('id_type_utilisateur') is-invalid @enderror" name="id_type_utilisateur_edt">
                                            <option value="" selected disabled>Choisir un type</option>
                                            @foreach ($types as $type)
                                                <option value="{{ $type->id_type_utilisateur }}" {{ old('id_type_utilisateur') == $type->id_type_utilisateur ? 'selected' : '' }}>
                                                    {{ $type->type_utilisateur }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('id_type_utilisateur')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Fonction -->
                                    <div class="mb-3">
                                        <label class="form-label"><strong>Fonction</strong></label>
                                        <div class="d-flex align-items-center mb-3">
                                            <!-- Bouton pour basculer entre dropdown et input -->
                                            <button id="toggle-fonction-btn" type="button" class="btn btn-outline-success d-flex align-items-center">
                                                <i id="toggle-icon" class="fas fa-plus me-2"></i> <!-- Icône (Font Awesome) -->
                                                <span id="toggle-text">Ajouter une nouvelle fonction</span>
                                            </button>
                                        </div>

                                        <input class="form-control mb-2" type="text" id="search-fonction-edt" placeholder="Rechercher une fonction...">

                                        <!-- Liste déroulante des fonctions -->
                                        <div id="fonction-dropdown">
                                            <select id="fonction-select-edt" class="form-select @error('id_fonction_edt') is-invalid @enderror" name="id_fonction_edt">
                                                <option value="" selected disabled>Choisir une fonction</option>
                                                @foreach ($fonctions as $fonction)
                                                <option value="{{ $fonction->id_fonction }}" {{ old('id_fonction_edt') == $fonction->id_fonction ? 'selected' : '' }}>
                                                    {{ $fonction->fonction }}
                                                </option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" name="selected_fonction_edt" id="selected_fonction_edt_hidden" value="{{ old('id_fonction_edt') }}">
                                            @error('id_fonction_edt')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Champ pour ajouter une nouvelle fonction -->
                                        <div id="new-fonction-input" style="display: none;">
                                            <input class="form-control @error('new_fonction_edt') is-invalid @enderror" type="text" name="new_fonction_edt"
                                                id="new_fonction_edt" placeholder="Entrez une nouvelle fonction" value="{{ old('new_fonction_edt') }}" />
                                            @error('new_fonction_edt')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Chantier -->
                                    <div class="mb-3">
                                        <label class="form-label" for="chantier-select"><strong>Localisation</strong></label>
                                        <input class="form-control mb-2" type="text" id="search-chantier" placeholder="Rechercher une localisation...">
                                        <select id="chantier-select" class="form-select @error('id_localisation') is-invalid @enderror" name="id_localisation_edt">
                                            <option value="0" disabled selected>Choisir une localisation</option>
                                            @foreach ($chantiers as $chantier)
                                                <option value="{{ $chantier->id_localisation }}" {{ old('id_localisation') == $chantier->id_localisation ? 'selected' : '' }}>
                                                    {{ $chantier->localisation }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="selected_chantier" id="selected_chantier_hidden" value="{{ old('id_localisation_edt') }}">
                                        @error('id_localisation')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-warning" type="button" id="close-modal-edit" data-bs-dismiss="modal">Fermer</button>
                <button class="btn btn-primary" type="submit" form="edit_emp">Modifier</button>
            </div>
        </div>
    </div>
</div>
<div id="modal_add_emp" class="modal" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-primary">Ajouter un nouvel utilisateur</h4>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-dark">
                <div class="row">
                    <div class="col"></div>
                    <div class="col-xl-8">
                        <div class="card shadow">
                            <div class="card-body">
                                <form id="add_emp" action="{{ route('ajouter.utilisateur') }}" method="POST">
                                    @csrf <!-- Token CSRF pour la sécurité -->

                                    <!-- Champ Nom -->
                                    <div class="mb-3">
                                        <label class="form-label"><strong>Nom</strong></label>
                                        <input
                                            class="form-control @error('nom_add') is-invalid @enderror"
                                            type="text"
                                            name="nom_add"
                                            placeholder="Nom de l'utilisateur"
                                            value="{{ old('nom_add') }}"
                                            required
                                        />
                                        @error('nom_add')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Champ Prénom -->
                                    <div class="mb-3">
                                        <label class="form-label"><strong>Prénom(s)</strong></label>
                                        <input
                                            class="form-control @error('prenom_add') is-invalid @enderror"
                                            type="text"
                                            name="prenom_add"
                                            placeholder="Prénom(s) de l'utilisateur"
                                            value="{{ old('prenom_add') }}"
                                            required
                                        />
                                        @error('prenom_add')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Champ Login -->
                                    <div class="mb-3">
                                        <label class="form-label"><strong>Login</strong></label>
                                        <input
                                            class="form-control @error('login_add') is-invalid @enderror"
                                            type="text"
                                            name="login_add"
                                            placeholder="Login de l'utilisateur"
                                            value="{{ old('login_add') }}"
                                            required
                                        />
                                        @error('login_add')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Champ Matricule -->
                                    <div class="mb-3">
                                        <label class="form-label"><strong>Matricule</strong></label>
                                        <input
                                            class="form-control @error('matricule_add') is-invalid @enderror"
                                            type="number"
                                            name="matricule_add"
                                            placeholder="Matricule de l'utilisateur"
                                            value="{{ old('matricule_add') }}"
                                        />
                                        @error('matricule_add')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Type d'utilisateur -->
                                    <div class="mb-3">
                                        <label class="form-label"><strong>Type</strong></label>
                                        <select class="form-select @error('id_type_utilisateur_add') is-invalid @enderror"
                                        name="id_type_utilisateur_add"
                                        required>
                                            <option value="" selected disabled>Choisir le type</option>
                                            @foreach ($types as $type)
                                                <option value="{{ $type->id_type_utilisateur }}" {{ old('id_type_utilisateur_add') == $type->id_type_utilisateur ? 'selected' : '' }}>
                                                    {{ $type->type_utilisateur }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('id_type_utilisateur_add')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Fonction -->
                                    <div class="mb-3">
                                        <label class="form-label"><strong>Fonction</strong></label>
                                        <div class="d-flex align-items-center mb-3">
                                            <!-- Bouton pour basculer entre dropdown et input -->
                                            <button id="toggle-fonction-btn" type="button" class="btn btn-outline-success d-flex align-items-center">
                                                <i id="toggle-icon" class="fas fa-plus me-2"></i> <!-- Icône (Font Awesome) -->
                                                <span id="toggle-text">Ajouter une nouvelle fonction</span>
                                            </button>
                                        </div>

                                        <input class="form-control mb-2" type="text" id="search-fonction-add" placeholder="Rechercher une fonction...">

                                        <!-- Liste déroulante des fonctions -->
                                        <div id="fonction-dropdown">
                                            <select id="fonction-select-add" class="form-select @error('id_fonction_add') is-invalid @enderror" name="id_fonction_add">
                                                <option value="" selected disabled>Choisir une fonction</option>
                                                @foreach ($fonctions as $fonction)
                                                <option value="{{ $fonction->id_fonction }}" {{ old('id_fonction_add') == $fonction->id_fonction ? 'selected' : '' }}>
                                                    {{ $fonction->fonction }}
                                                </option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" name="selected_fonction_add" id="selected_fonction_add_hidden" value="{{ old('id_fonction_add') }}">
                                            @error('id_fonction_add')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Champ pour ajouter une nouvelle fonction -->
                                        <div id="new-fonction-input" style="display: none;">
                                            <input class="form-control @error('new_fonction_add') is-invalid @enderror" type="text" name="new_fonction_add"
                                                id="new_fonction_add" placeholder="Entrez une nouvelle fonction" value="{{ old('new_fonction_add') }}" />
                                            @error('new_fonction_add')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Chantier -->
                                    <div class="mb-3">
                                        <label class="form-label" for="chantier-select-add"><strong>Localisation</strong></label>
                                        <input class="form-control mb-2" type="text" id="search-chantier-add" placeholder="Rechercher une localisation...">
                                        <select id="chantier-select-add" class="form-select @error('id_localisation_add') is-invalid @enderror" name="id_localisation_add" required>
                                            <option value="" selected disabled>Choisir une localisation</option>
                                            @foreach ($chantiers as $chantier)
                                                <option value="{{ $chantier->id_localisation }}" {{ old('id_localisation_add') == $chantier->id_localisation ? 'selected' : '' }}>
                                                    {{ $chantier->localisation }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="selected_chantier_add" id="selected_chantier_add_hidden" value="{{ old('id_localisation_add') }}">
                                        @error('id_localisation_add')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col"></div>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-warning" type="button" id="close-modal-add" data-bs-dismiss="modal">Fermer</button>
                <button class="btn btn-info" type="submit" form="add_emp">Ajouter</button>
            </div>
        </div>
    </div>
</div>

<div id="supprimer_utilisateur" class="modal fade" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <!-- Header du Modal -->
            <div class="modal-header bg-danger text-white">
                <h4 class="modal-title" id="modalTitle">Départ d'un utilisateur</h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Corps du Modal -->
            <div class="modal-body text-dark">
                <!-- Informations utilisateur -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="border rounded p-3 bg-light">
                            <p class="visually-hidden"><strong>ID : </strong><span id="utilisateur_id"></span></p>
                            <p><strong>Nom : </strong><span id="utilisateur_nom"></span></p>
                            <p><strong>Matricule : </strong><span id="utilisateur_matricule"></span></p>
                            <p><strong>Login : </strong><span id="utilisateur_login"></span></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border rounded p-3 bg-light">
                            <p><strong>Type : </strong><span id="utilisateur_type"></span></p>
                            <p><strong>Fonction : </strong><span id="utilisateur_fonction"></span></p>
                            <p><strong>Localisation : </strong><span id="utilisateur_chantier"></span></p>
                        </div>
                    </div>
                </div>

                <!-- Date de départ -->
                <div class="mb-4">
                    <label for="date_depart" class="form-label"><strong>Date de départ :</strong></label>
                    <input type="date" id="date_depart" class="form-control" required>
                </div>

                <!-- Résiliation des lignes associées -->
                <div class="mb-4">
                    <label class="form-label"><strong>Lignes associées à résilier :</strong></label>
                    <div id="lignes_associees" class="border rounded p-3 bg-light">
                        <!-- Liste dynamique des lignes -->
                        <p class="text-muted">Aucune ligne trouvée.</p>
                    </div>
                </div>

                <!-- Retour des équipements -->
                <div class="mb-4">
                    <label class="form-label"><strong>Retour d'équipement :</strong></label>
                    <div id="equipements_affectes" class="border rounded p-3 bg-light">
                        <!-- Liste dynamique des équipements -->
                        <p class="text-muted">Aucun équipement trouvé.</p>
                    </div>
                </div>

                <!-- Commentaire -->
                <div id="commentaire_retour" class="mb-3 d-none">
                    <label for="commentaire" class="form-label">Commentaire :</label>
                    <textarea id="commentaire" class="form-control" rows="3" placeholder="Ajoutez un commentaire..."></textarea>
                </div>
            </div>

            <!-- Footer du Modal -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" id="valider_depart_utilisateur" class="btn btn-danger">Valider</button>
            </div>
        </div>
    </div>
</div>

{{-- ATTRIBUTION EQUIPEMENT --}}
<div id="modal_attribuer_equipement" class="modal" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <!-- En-tête du modal -->
            <div class="modal-header">
                <h4 class="modal-title text-primary">Attribuer un Équipement</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body text-dark">
                <form id="form_attr_equipement" action="{{ route('ligne.attrEquipement') }}" method="post">
                    @csrf
                    <input id="id_utilisateur_attr" type="hidden" name="id_utilisateur_attr">
                    <!-- Informations sur l'utilisateur -->
                    <div class="mb-3">
                        <label for="login_attr" class="form-label">Login de l'utilisateur</label>
                        <input type="text" class="form-control" id="login_attr" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="nom_prenom_attr" class="form-label">Nom et prénom(s) de l'utilisateur</label>
                        <input type="text" class="form-control" id="nom_prenom_attr" readonly>
                    </div>

                    <!-- Choix entre Téléphones ou Box -->
                    <div class="mb-3">
                        <label for="type_equipement_attr" class="form-label">
                            Type d'Équipement <span class="text-danger">*</span>
                        </label>
                        <select class="form-select" id="type_equipement_attr" required>
                            <option value="" disabled selected>Choisir un type d'équipement</option>
                            <option value="phones">Téléphones</option>
                            <option value="box">Box</option>
                        </select>
                    </div>

                    <!-- Liste des équipements -->
                    <div class="mb-3">
                        <label for="equipement_attr" class="form-label">
                            Équipement <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control mb-1" id="search-equipement-attr" placeholder="Rechercher un équipement...">
                        <select class="form-select" id="equipement_attr" required disabled name="id_equipement_attr">
                            <option value="" disabled selected>Choisir un équipement</option>
                        </select>
                    </div>

                    <!-- Date d'attribution -->
                    <div class="mb-3">
                        <label for="date_attr" class="form-label">
                            Date d'attribution <span class="text-danger">*</span>
                        </label>
                        <input type="date" class="form-control" id="date_attr" name="date_attr" required>
                    </div>

                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Annuler</button>
                <button id="btn_attribuer_equipement" class="btn btn-primary" form="form_attr_equipement" disabled>Attribuer</button>
            </div>
        </div>
    </div>
</div>

{{-- ATTRIBUTION LIGNE --}}
<div id="modal_attribuer_ligne" class="modal" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <!-- En-tête du modal -->
            <div class="modal-header">
                <h4 class="modal-title text-primary">Attribuer une Ligne</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body text-dark">
                <form id="form_attr_ligne" action="{{ route('ligne.attrLigne') }}" method="post">
                    @csrf
                    <input id="id_utilisateur_attr_ligne" type="hidden" name="id_utilisateur_attr_ligne">
                    <!-- Informations sur l'utilisateur -->
                    <div class="mb-3">
                        <label for="login_attr_ligne" class="form-label">Login de l'utilisateur</label>
                        <input type="text" class="form-control" id="login_attr_ligne" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="nom_prenom_attr_ligne" class="form-label">Nom et prénom(s) de l'utilisateur</label>
                        <input type="text" class="form-control" id="nom_prenom_attr_ligne" readonly>
                    </div>

                    <!-- Choix entre Téléphones ou Box -->
                    <div class="mb-3">
                        <label for="id_operateur_attr_ligne" class="form-label">
                            Opérateur <span class="text-danger">*</span>
                        </label>
                        <select class="form-select" id="id_operateur_attr_ligne" required>
                            <option value="" disabled selected>Choisir un Opérateur</option>
                            @foreach ($operateurs as $operateur)
                                <option value="{{ $operateur->id_operateur }}" {{ old('id_operateur_attr_ligne') == $operateur->id_operateur ? 'selected' : '' }}>
                                    {{ $operateur->nom_operateur }}
                                </option>
                            @endforeach
                        </select>
                    @error('id_operateur_attr_ligne')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    </div>

                    <!-- Liste des Lignes -->
                    <div class="mb-3">
                        <label for="ligne_attr_ligne" class="form-label">
                            Ligne <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control mb-1" id="search-ligne-attr" placeholder="Rechercher une ligne inactif ou en attente...">
                        <select class="form-select" id="ligne_attr_ligne" required disabled name="id_ligne_attr_ligne">
                            <option value="" disabled selected>Choisir une ligne</option>
                        </select>
                    </div>

                    <!-- Date d'attribution -->
                    <div class="mb-3">
                        <label for="date_attr_ligne" class="form-label">
                            Date d'attribution(activation) <span class="text-danger">*</span>
                        </label>
                        <input type="date" class="form-control" id="date_attr_ligne" name="date_attr_ligne" required>
                    </div>

                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Annuler</button>
                <button id="btn_attribuer_ligne" class="btn btn-primary" form="form_attr_ligne" disabled>Attribuer</button>
            </div>
        </div>
    </div>
</div>
