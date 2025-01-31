<div id="modal_enr_phone" class="modal" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-primary">Enregistrer un téléphone</h4>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <div></div>
                    </div>
                    <div class="col-xl-6">
                        <div class="card shadow">
                            <div class="card-body">
                                <form id="form_enr_phone" action="{{ route('phone.enr') }}" method="post" style="color: #a0c8d8;">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label" for="enr_phone_type"><strong>Type</strong></label>
                                        <select id="enr_phone_type" class="form-select @error('enr_phone_type','enr_phone_errors') is-invalid @enderror" name="enr_phone_type" required>
                                            <option value="0" disabled {{ old('enr_phone_type') ? '' : 'selected' }}>Choisir le type</option>
                                            @foreach($types as $type)
                                                <option value="{{ $type->id_type_equipement }}"
                                                    {{ old('enr_phone_type') == $type->id_type_equipement ? 'selected' : '' }}>
                                                    {{ $type->type_equipement }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('enr_phone_type','enr_phone_errors')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="enr_phone_marque"><strong>Marque</strong></label>
                                        <select id="enr_phone_marque" class="form-select @error('enr_phone_marque','enr_phone_errors') is-invalid @enderror" name="enr_phone_marque" required>
                                            <option value="0" disabled {{ old('enr_phone_marque') ? '' : 'selected' }}>Choisir la marque</option>
                                            <option value="new_marque" {{ old('enr_phone_marque') == 'new_marque' ? 'selected' : '' }}>Ajouter une nouvelle marque</option>
                                            @foreach($marques as $marque)
                                                <option value="{{ $marque->id_marque }}"
                                                    {{ old('enr_phone_marque') == $marque->id_marque ? 'selected' : '' }}>
                                                    {{ $marque->marque }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('enr_phone_marque','enr_phone_errors')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror

                                        <!-- Champ pour nouvelle marque -->
                                        <input id="new_phone_marque" class="form-control mt-2 d-none @error('new_phone_marque','enr_phone_errors') is-invalid @enderror" type="text" placeholder="Nouvelle marque" name="new_phone_marque" value="{{ old('new_phone_marque') }}" />
                                        @error('new_phone_marque','enr_phone_errors')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="enr_phone_modele"><strong>Modèle</strong></label>
                                        <select id="enr_phone_modele" class="form-select @error('enr_phone_modele','enr_phone_errors') is-invalid @enderror" name="enr_phone_modele" required>
                                            <option value="0" disabled {{ old('enr_phone_modele') ? '' : 'selected' }}>Choisir le modèle</option>
                                            <option value="new" {{ old('enr_phone_modele') == 'new' ? 'selected' : '' }}>Ajouter un nouveau modèle</option>
                                            @foreach($modeles as $modele)
                                                <option value="{{ $modele->id_modele }}" {{ old('enr_phone_modele') == $modele->id_modele ? 'selected' : '' }}>
                                                    {{ $modele->nom_modele }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('enr_phone_modele','enr_phone_errors')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror

                                        <!-- Champ pour ajouter un nouveau modèle -->
                                        <input id="new_phone_modele" class="form-control mt-2 d-none @error('new_phone_modele','enr_phone_errors') is-invalid @enderror"
                                               type="text"
                                               placeholder="Nouveau modèle"
                                               name="new_phone_modele"
                                               value="{{ old('new_phone_modele') }}" />
                                        @error('new_phone_modele','enr_phone_errors')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="enr_phone_imei"><strong>Imei</strong></label>
                                        <input id="enr_phone_imei" class="form-control @error('enr_phone_imei','enr_phone_errors') is-invalid @enderror"
                                            type="text"
                                            placeholder="Entrer l'imei"
                                            name="enr_phone_imei"
                                            value="{{ old('enr_phone_imei') }}"
                                            required />
                                        @error('enr_phone_imei','enr_phone_errors')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="enr_phone_sn"><strong>Numéro de série</strong></label>
                                        <input id="enr_phone_sn" class="form-control @error('enr_phone_sn','enr_phone_errors') is-invalid @enderror"
                                            type="text"
                                            placeholder="Entrer le numéro de série"
                                            name="enr_phone_sn"
                                            value="{{ old('enr_phone_sn') }}"
                                            required />
                                        @error('enr_phone_sn','enr_phone_errors')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="enr_phone_enroll"><strong>Enrôlé</strong></label>
                                        <select id="enr_phone_enroll" class="form-select @error('enr_phone_enroll','enr_phone_errors') is-invalid @enderror" name="enr_phone_enroll" required>
                                            <option value="0" disabled {{ old('enr_phone_enroll') ? '' : 'selected' }}>Oui ou Non</option>
                                            <option value="1" {{ old('enr_phone_enroll') == '1' ? 'selected' : '' }}>Oui</option>
                                            <option value="2" {{ old('enr_phone_enroll') == '2' ? 'selected' : '' }}>Non</option>
                                        </select>
                                        @error('enr_phone_enroll','enr_phone_errors')
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
                <button class="btn btn-warning" type="button" data-bs-dismiss="modal">Fermer</button>
                <button class="btn btn-primary" type="submit" form="form_enr_phone">Enregistrer</button></div>
        </div>
    </div>
</div>
<div id="modal_edt_phone" class="modal" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-primary">Modifier un téléphone</h4>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <div></div>
                    </div>
                    <div class="col-xl-7">
                        <div class="card shadow">
                            <div class="card-body">
                                <form id="form_edt_phone" method="get" style="color: #a0c8d8;">
                                    <!-- Champs cachés pour transmettre les valeurs des champs désactivés -->
                                    <input type="hidden" id="edt_phone_id" name="edt_phone_id" value="">

                                    <div class="mb-3">
                                        <label class="form-label" for="edt_phone_type"><strong>Type</strong></label>
                                        <input id="edt_phone_type" class="form-control @error('edt_phone_type','edt_phone_errors') is-invalid @enderror"
                                            type="text"
                                            name="edt_phone_type"
                                            value="{{ old('edt_phone_type') }}"
                                            disabled />
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="edt_phone_marque"><strong>Marque</strong></label>
                                        <input id="edt_phone_marque" class="form-control @error('edt_phone_marque','edt_phone_errors') is-invalid @enderror"
                                            type="text"
                                            name="edt_phone_marque"
                                            value="{{ old('edt_phone_marque') }}"
                                            disabled />
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="edt_phone_modele"><strong>Modèle</strong></label>
                                        <input id="edt_phone_modele" class="form-control @error('edt_phone_modele','edt_phone_errors') is-invalid @enderror"
                                            type="text"
                                            name="edt_phone_modele"
                                            value="{{ old('edt_phone_modele') }}"
                                            disabled />
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="edt_phone_imei"><strong>Imei</strong></label>
                                        <input id="edt_phone_imei" class="form-control @error('edt_phone_imei','edt_phone_errors') is-invalid @enderror"
                                            type="text"
                                            placeholder="Entrer l'imei"
                                            name="edt_phone_imei"
                                            value="{{ old('edt_phone_imei') }}"
                                            required />
                                        @error('edt_phone_imei','edt_phone_errors')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="edt_phone_sn"><strong>Numéro de série</strong></label>
                                        <input id="edt_phone_sn" class="form-control @error('edt_phone_sn','edt_phone_errors') is-invalid @enderror"
                                            type="text"
                                            placeholder="Entrer le numéro de série"
                                            name="edt_phone_sn"
                                            value="{{ old('edt_phone_sn') }}"
                                            required />
                                        @error('edt_phone_sn','edt_phone_errors')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="edt_phone_enroll"><strong>Enrôlé</strong></label>
                                        <select id="edt_phone_enroll" class="form-select @error('edt_phone_enroll','edt_phone_errors') is-invalid @enderror" name="edt_phone_enroll" required>
                                            <option value="0" disabled {{ old('edt_phone_enroll') ? '' : 'selected' }}>Oui ou Non</option>
                                            <option value="1" {{ old('edt_phone_enroll') == '1' ? 'selected' : '' }}>Oui</option>
                                            <option value="2" {{ old('edt_phone_enroll') == '2' ? 'selected' : '' }}>Non</option>
                                        </select>
                                        @error('edt_phone_enroll','edt_phone_errors')
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
                <button class="btn btn-warning" type="button" data-bs-dismiss="modal">Fermer</button>
                <button class="btn btn-info" type="submit" form="form_edt_phone">Modifier</button>
            </div>
        </div>
    </div>
</div>

<div id="modal_histo_phone" class="modal fade" role="dialog" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-primary text-white">
                <h4 id="modalTitle" class="modal-title">Historique d'affectation pour ce téléphone</h4>
            </div>
            <div class="modal-body">
                <!-- Détails du téléphone -->
                <div class="mb-4">
                    <p class="text-dark mb-1">
                        <span class="fw-bold">Téléphone :</span>
                        <span class="fw-normal" data-field="marque"></span>
                        <span class="fw-normal" data-field="modele"></span>
                    </p>
                    <p class="text-dark mb-1">
                        <span class="fw-bold">Numéro de série :</span>
                        <span class="fw-normal" data-field="serial_number"></span>
                    </p>
                    <p class="text-dark">
                        <span class="fw-bold">IMEI :</span>
                        <span class="fw-normal" data-field="imei"></span>
                    </p>
                </div>

                <!-- Table des affectations -->
                <div class="table-responsive">
                    <table id="dataTable" class="table table-bordered table-hover align-middle">
                        <thead class="table-primary">
                            <tr>
                                <th class="text-dark">Utilisateur</th>
                                <th class="text-dark">Login</th>
                                <th class="text-dark">Localisation</th>
                                <th class="text-dark">Date d'affectation</th>
                                <th class="text-dark">Date de retour</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Les lignes seront ajoutées dynamiquement ici -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modal_hs_phone" class="modal" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-danger">Voulez-vous vraiment déclarer HS?</h4><button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <div></div>
                    </div>
                    <div class="col-xl-7">
                        <div class="card shadow">
                            <div class="card-body">
                                <!-- Formulaire de déclaration HS -->
                                <form id="form_hs_phone" action="{{ route('phone.hs') }}" method="post">
                                    @csrf
                                    <!-- Champ caché pour l'ID du téléphone (utilisé par le backend) -->
                                    <input type="hidden" name="phone_id" id="hs_phone_id" value="">

                                    <div class="mb-3">
                                        <label class="form-label" for="hs_phone"><strong>Téléphone</strong></label>
                                        <!-- Champ désactivé pour affichage uniquement -->
                                        <input id="hs_phone" class="form-control" type="text" value="" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="imei_phone"><strong>Imei</strong></label>
                                        <!-- Champ désactivé pour affichage uniquement -->
                                        <input id="imei_phone" class="form-control" type="text" value="" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="sn_phone"><strong>SN</strong></label>
                                        <!-- Champ désactivé pour affichage uniquement -->
                                        <input id="sn_phone" class="form-control" type="text" value="" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="statut_phone"><strong>Etat</strong></label>
                                        <select id="statut_phone" class="form-select" readonly>
                                            <option value="3" selected>HS</option>
                                        </select>
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
                <button class="btn btn-warning" type="button" data-bs-dismiss="modal">Fermer</button>
                <button class="btn btn-danger" type="submit" form="form_hs_phone">Déclarer HS</button></div>
        </div>
    </div>
</div>

<div id="modal_retour_phone" class="modal text-dark" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-danger">Voulez-vous vraiment retourner ce téléphone?</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <div></div>
                    </div>
                    <div class="col-xl-10">
                        <div class="card shadow">
                            <div class="card-body">
                                <form id="form_retour_phone" action="{{ route('phone.retour') }}" method="post">
                                    @csrf
                                    <!-- Champs cachés -->
                                    <input type="hidden" name="retour_phone_id" id="retour_phone_id" value="{{ old('retour_phone_id') }}">
                                    <input type="hidden" name="retour_affectation_id" id="retour_affectation_id" value="{{ old('retour_affectation_id') }}">

                                    <!-- Champs affichés deux par deux -->
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label" for="retour_type"><strong>Type</strong></label>
                                            <input id="retour_type"
                                                   name="retour_type"
                                                   class="form-control"
                                                   type="text"
                                                   value="{{ old('retour_type') }}"
                                                   readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="retour_phone"><strong>Téléphone</strong></label>
                                            <input id="retour_phone"
                                                   name="retour_phone"
                                                   class="form-control"
                                                   type="text"
                                                   value="{{ old('retour_phone') }}"
                                                   readonly>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label" for="retour_imei"><strong>Imei</strong></label>
                                            <input id="retour_imei"
                                                   name="retour_imei"
                                                   class="form-control"
                                                   type="text"
                                                   value="{{ old('retour_imei') }}"
                                                   readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="retour_sn"><strong>SN</strong></label>
                                            <input id="retour_sn"
                                                   name="retour_sn"
                                                   class="form-control"
                                                   type="text"
                                                   value="{{ old('retour_sn') }}"
                                                   readonly>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label" for="retour_user"><strong>Utilisateur</strong></label>
                                            <input id="retour_user"
                                                   name="retour_user"
                                                   class="form-control"
                                                   type="text"
                                                   value="{{ old('retour_user') }}"
                                                   readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="retour_statut"><strong>Etat</strong><span class="text-danger"> *</span></label>
                                            <select id="retour_statut"
                                                    name="retour_statut"
                                                    class="form-select"
                                                    required>
                                                <option value="0" selected>Choisir un état</option>
                                                <option value="3" {{ old('retour_statut') === '' ? 'selected' : '' }}>Retourné</option>
                                                <option value="4" {{ old('retour_statut') === '' ? 'selected' : '' }}>HS</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label" for="retour_debut"><strong>Date d'affectation</strong></label>
                                            <input type="date"
                                                   class="form-control @error('retour_debut') is-invalid @enderror"
                                                   name="retour_debut"
                                                   id="retour_debut"
                                                   value="{{ old('retour_debut') }}"
                                                   readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="retour_date"><strong>Date de retour<span class="text-danger"> *</span></strong></label>
                                            <input type="date"
                                                   class="form-control @error('retour_date', 'retour_phone_errors') is-invalid @enderror"
                                                   name="retour_date"
                                                   id="retour_date"
                                                   value="{{ old('retour_date') }}"
                                                   required>
                                            @error('retour_date', 'retour_phone_errors')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col">
                                            <!-- Commentaire -->
                                            <div class="row mb-3">
                                                <div class="col">
                                                    <label for="retour_commentaire" class="form-label"><strong>Commentaire<span class="text-danger"> *</span></strong></label>
                                                    <textarea id="retour_commentaire" name="retour_commentaire" class="form-control" rows="3" placeholder="Ajoutez un commentaire...">{{ old('commentaire') }}</textarea>
                                                </div>
                                            </div>
                                        </div>
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
                <button class="btn btn-danger" type="submit" form="form_retour_phone">Retourner</button>
            </div>
        </div>
    </div>
</div>
