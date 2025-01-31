<div id="modal_enr_box" class="modal" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-primary">Enregistrer un box</h4>
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
                                <form id="form_enr_box" action="{{ route('box.enr') }}" method="post" style="color: #a0c8d8;">
                                    @csrf
                                    <!-- Champ pour la marque -->
                                    <div class="mb-3">
                                        <label class="form-label" for="enr_box_marque"><strong>Marque</strong></label>
                                        <select id="enr_box_marque" class="form-select @error('enr_box_marque','enr_box_errors') is-invalid @enderror" name="enr_box_marque" required>
                                            <option value="0" disabled {{ old('enr_box_marque') ? '' : 'selected' }}>Choisir la marque</option>
                                            <option value="new" {{ old('enr_box_marque') == 'new' ? 'selected' : '' }}>Ajouter une nouvelle marque</option>
                                            @foreach($marques as $marque)
                                                <option value="{{ $marque->id_marque }}" {{ old('enr_box_marque') == $marque->id_marque ? 'selected' : '' }}>
                                                    {{ $marque->marque }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('enr_box_marque','enr_box_errors')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror

                                        <!-- Champ pour nouvelle marque, visible seulement si "new" est sélectionné -->
                                        <input id="new_box_marque" 
                                            class="form-control mt-2 @error('new_box_marque','enr_box_errors') is-invalid @enderror {{ old('enr_box_marque') == 'new' ? '' : 'd-none' }}" 
                                            type="text" 
                                            placeholder="Nouvelle marque" 
                                            name="new_box_marque" 
                                            value="{{ old('new_box_marque') }}" />
                                        @error('new_box_marque','enr_box_errors')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="enr_box_modele"><strong>Modèle</strong></label>
                                        <select id="enr_box_modele" class="form-select @error('enr_box_modele','enr_box_errors') is-invalid @enderror" name="enr_box_modele" required>
                                            <option value="0" disabled {{ old('enr_box_modele') ? '' : 'selected' }}>Choisir le modèle</option>
                                            <option value="new" {{ old('enr_box_modele') == 'new' ? 'selected' : '' }}>Ajouter un nouveau modèle</option>
                                            @foreach($modeles as $modele)
                                                <option value="{{ $modele->id_modele }}" {{ old('enr_box_modele') == $modele->id_modele ? 'selected' : '' }}>
                                                    {{ $modele->nom_modele }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('enr_box_modele','enr_box_errors')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror

                                        <!-- Champ pour ajouter un nouveau modèle -->
                                        <input id="new_box_modele" class="form-control mt-2 d-none @error('new_box_modele','enr_box_errors') is-invalid @enderror"
                                               type="text"
                                               placeholder="Nouveau modèle"
                                               name="new_box_modele"
                                               value="{{ old('new_box_modele') }}" />
                                        @error('new_box_modele','enr_box_errors')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="enr_box_imei"><strong>Imei</strong></label>
                                        <input id="enr_box_imei" class="form-control @error('enr_box_imei','enr_box_errors') is-invalid @enderror"
                                            type="text"
                                            placeholder="Entrer l'imei"
                                            name="enr_box_imei"
                                            value="{{ old('enr_box_imei') }}"
                                            required />
                                        @error('enr_box_imei','enr_box_errors')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="enr_box_sn"><strong>Numéro de série</strong></label>
                                        <input id="enr_box_sn" class="form-control @error('enr_box_sn','enr_box_errors') is-invalid @enderror"
                                            type="text"
                                            placeholder="Entrer le numéro de série"
                                            name="enr_box_sn"
                                            value="{{ old('enr_box_sn') }}"
                                            required />
                                        @error('enr_box_sn','enr_box_errors')
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
                <button class="btn btn-primary" type="submit" form="form_enr_box">Enregistrer</button></div>
        </div>
    </div>
</div>
<div id="modal_edt_box" class="modal" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-primary">Modifier un box</h4>
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
                                <form id="form_edt_box" method="get" style="color: #a0c8d8;">
                                    <!-- Champs cachés pour transmettre les valeurs des champs désactivés -->
                                    <input type="hidden" id="edt_box_id" name="edt_box_id" value="">
                                    
                                    <div class="mb-3">
                                        <label class="form-label" for="edt_box_type"><strong>Type</strong></label>
                                        <input id="edt_box_type" class="form-control @error('edt_box_type','edt_box_errors') is-invalid @enderror"
                                            type="text"
                                            name="edt_box_type"
                                            value="{{ old('edt_box_type') }}"
                                            disabled />
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="edt_box_marque"><strong>Marque</strong></label>
                                        <input id="edt_box_marque" class="form-control @error('edt_box_marque','edt_box_errors') is-invalid @enderror"
                                            type="text"
                                            name="edt_box_marque"
                                            value="{{ old('edt_box_marque') }}"
                                            disabled />
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="edt_box_modele"><strong>Modèle</strong></label>
                                        <input id="edt_box_modele" class="form-control @error('edt_box_modele','edt_box_errors') is-invalid @enderror"
                                            type="text"
                                            name="edt_box_modele"
                                            value="{{ old('edt_box_modele') }}"
                                            disabled />
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="edt_box_imei"><strong>Imei</strong></label>
                                        <input id="edt_box_imei" class="form-control @error('edt_box_imei','edt_box_errors') is-invalid @enderror"
                                            type="text"
                                            placeholder="Entrer l'imei"
                                            name="edt_box_imei"
                                            value="{{ old('edt_box_imei') }}"
                                            required />
                                        @error('edt_box_imei','edt_box_errors')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="edt_box_sn"><strong>Numéro de série</strong></label>
                                        <input id="edt_box_sn" class="form-control @error('edt_box_sn','edt_box_errors') is-invalid @enderror"
                                            type="text"
                                            placeholder="Entrer le numéro de série"
                                            name="edt_box_sn"
                                            value="{{ old('edt_box_sn') }}"
                                            required />
                                        @error('edt_box_sn','edt_box_errors')
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
                <button class="btn btn-info" type="submit" form="form_edt_box">Modifier</button>
            </div>
        </div>
    </div>
</div>

<div id="modal_histo_box" class="modal fade" role="dialog" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-primary text-white">
                <h4 id="modalTitle" class="modal-title">Historique d'affectation pour cette Box</h4>
            </div>
            <div class="modal-body">
                <!-- Détails du Box -->
                <div class="mb-4">
                    <p class="text-dark mb-1">
                        <span class="fw-bold">Box :</span>
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


<div id="modal_hs_box" class="modal" role="dialog" tabindex="-1">
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
                                <form id="form_hs_box" action="{{ route('box.hs') }}" method="post">
                                    @csrf
                                    <!-- Champ caché pour l'ID du Box (utilisé par le backend) -->
                                    <input type="hidden" name="box_id" id="hs_box_id" value="">

                                    <div class="mb-3">
                                        <label class="form-label" for="hs_box"><strong>Box</strong></label>
                                        <!-- Champ désactivé pour affichage uniquement -->
                                        <input id="hs_box" class="form-control" type="text" value="" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="imei_box"><strong>Imei</strong></label>
                                        <!-- Champ désactivé pour affichage uniquement -->
                                        <input id="imei_box" class="form-control" type="text" value="" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="sn_box"><strong>SN</strong></label>
                                        <!-- Champ désactivé pour affichage uniquement -->
                                        <input id="sn_box" class="form-control" type="text" value="" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="statut_box"><strong>Etat</strong></label>
                                        <select id="statut_box" class="form-select" readonly>
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
                <button class="btn btn-danger" type="submit" form="form_hs_box">Déclarer HS</button></div>
        </div>
    </div>
</div>

<div id="modal_retour_box" class="modal" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-danger">Voulez-vous vraiment retourner ce Box?</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <div></div>
                    </div>
                    <div class="col-xl-10">
                        <div class="card shadow">
                            <div class="card-body">
                                <form id="form_retour_box" action="{{ route('box.retour') }}" method="post">
                                    @csrf
                                    <!-- Champs cachés -->
                                    <input type="hidden" name="retour_box_id" id="retour_box_id" value="{{ old('retour_box_id') }}">
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
                                            <label class="form-label" for="retour_box"><strong>Box</strong></label>
                                            <input id="retour_box"
                                                   name="retour_box"
                                                   class="form-control"
                                                   type="text"
                                                   value="{{ old('retour_box') }}"
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
                                            <label class="form-label" for="retour_statut"><strong>Etat</strong></label>
                                            <select id="retour_statut"
                                                    name="retour_statut"
                                                    class="form-select"
                                                    readonly>
                                                <option value="" {{ old('retour_statut') === '' ? 'selected' : '' }}>Retourné</option>
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
                                            <label class="form-label" for="retour_date"><strong>Date de retour<span class="text-danger">*</span></strong></label>
                                            <input type="date"
                                                   class="form-control @error('retour_date', 'retour_box_errors') is-invalid @enderror"
                                                   name="retour_date"
                                                   id="retour_date"
                                                   value="{{ old('retour_date') }}"
                                                   required>
                                            @error('retour_date', 'retour_box_errors')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
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
                <button class="btn btn-danger" type="submit" form="form_retour_box">Retourner</button>
            </div>
        </div>
    </div>
</div>