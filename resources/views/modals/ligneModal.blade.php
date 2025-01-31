{{-- Demander l'activation --}}
<div id="modal_act_ligne" class="modal" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-primary">Demande d'activation d'une ligne</h4>
                <button id="close_modal_act" class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <div></div>
                    </div>
                    <div class="col-xl-8">
                        <div class="card shadow">
                            <div class="card-body">
                                <form id="form_act_ligne" action="{{ route('ligne.save') }}" method="POST" style="color: #a0c8d8;">
                                    @csrf <!-- Protection CSRF -->

                                    <!-- Numéro SIM -->
                                    <div class="mb-3">
                                        <label class="form-label" for="act_sim"><strong>Numéro SIM</strong></label>
                                        <input id="act_sim" class="form-control @error('act_sim', 'act_ligne_errors') is-invalid @enderror" 
                                            type="number" name="act_sim" placeholder="Numéro SIM" 
                                            value="{{ old('act_sim') }}" required />
                                        @error('act_sim', 'act_ligne_errors')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Opérateur -->
                                    <div class="mb-3">
                                        <label class="form-label" for="act_operateur"><strong>Opérateur</strong></label>
                                        <select id="act_operateur" class="form-select @error('act_operateur', 'act_ligne_errors') is-invalid @enderror" 
                                                name="act_operateur" required>
                                            <option value="" disabled {{ old('act_operateur') ? '' : 'selected' }}>Choisir l'opérateur</option>
                                            @foreach ($contactsOperateurs as $contact)
                                                <option value="{{ $contact->id_operateur }}" data-email="{{ $contact->email }}"
                                                    {{ old('act_operateur') == $contact->id_operateur ? 'selected' : '' }}>
                                                    {{ $contact->operateur->nom_operateur }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('act_operateur', 'act_ligne_errors')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Type -->
                                    <div class="mb-3">
                                        <label class="form-label" for="act_type"><strong>Type</strong></label>
                                        <select id="act_type" class="form-select @error('act_type', 'act_ligne_errors') is-invalid @enderror" 
                                                name="act_type" required>
                                            <option value="" disabled {{ old('act_type') ? '' : 'selected' }}>Choisir le type</option>
                                            @foreach ($types as $type)
                                                <option value="{{ $type->id_type_ligne }}"
                                                    {{ old('act_type') == $type->id_type_ligne ? 'selected' : '' }}>
                                                    {{ $type->type_ligne }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('act_type', 'act_ligne_errors')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Forfait -->
                                    <div class="mb-3">
                                        <label class="form-label" for="act_forfait"><strong>Forfait</strong></label>
                                        <select id="act_forfait" class="form-select @error('act_forfait', 'act_ligne_errors') is-invalid @enderror" 
                                                name="act_forfait" {{ old('act_forfait') ? '' : 'disabled' }}>
                                            <option value="" disabled {{ old('act_forfait') ? '' : 'selected' }}>Choisir le forfait</option>
                                            @foreach ($forfaits as $forfait)
                                                <option value="{{ $forfait->id_forfait }}" 
                                                        data-id-operateur="{{ $forfait->id_operateur }}" 
                                                        data-id-type-forfait="{{ $forfait->id_type_forfait }}"
                                                    {{ old('act_forfait') == $forfait->id_forfait ? 'selected' : '' }}>
                                                    {{ $forfait->nom_forfait }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('act_forfait', 'act_ligne_errors')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Texte de warning -->
                                    <div class="row mb-2">
                                        <div class="col">
                                            <p class="text-info small mb-0">
                                                <em>*Ce formulaire génère automatiquement un email de demande d'activation après sa soumission.</em>
                                            </p>
                                        </div>
                                    </div>
                                    <!-- Fin Texte de warning -->
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
                <button id="close_modal_act" class="btn btn-warning" type="button" data-bs-dismiss="modal">Fermer</button>
                <button id="btn_demander" class="btn btn-primary" type="submit" form="form_act_ligne" disabled>Demander</button>
            </div>
        </div>
    </div>
</div>

{{-- Enregister une ligne --}}
<div id="modal_enr_ligne" class="modal" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-primary">Enregistrer une ligne</h4>
                <button id="close_modal_enr" class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <div></div>
                    </div>
                    <div class="col-xl-10">
                        <div class="card shadow">
                            <div class="card-body">
                                <form id="form_enr_ligne" action="{{ route('ligne.enr') }}" method="post" style="color: #a0c8d8;">
                                    @csrf
                                    <div class="mb-3">
                                        <input id="enr_id_ligne" class="form-control" type="hidden" name="enr_id_ligne" value="{{ old('enr_id_ligne') }}"/>
                                    </div>                                    
                                    <div class="mb-3">
                                        <label class="form-label" for="enr_sim">
                                            <strong>Numéro SIM</strong>
                                        </label>
                                        <input id="enr_sim" class="form-control" type="text" name="enr_sim" readonly value="{{ old('enr_sim') }}"/>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="enr_forfait">
                                            <strong>Forfait</strong>
                                        </label>
                                        <input id="enr_forfait" class="form-control" type="text" name="enr_forfait" readonly value="{{ old('enr_forfait') }}"/>
                                        <input id="enr_id_forfait" class="form-control" type="hidden" name="enr_id_forfait" readonly value="{{ old('enr_id_forfait') }}"/>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="enr_ligne">
                                            <strong>Numéro Ligne</strong>
                                        </label>
                                        <input id="enr_ligne" class="form-control @error('enr_ligne', 'enr_ligne_errors') is-invalid @enderror" 
                                               type="text" name="enr_ligne" placeholder="Entrer le numéro ligne" value="{{ old('enr_ligne') ?? '0'}}" />
                                        @error('enr_ligne', 'enr_ligne_errors')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="enr_user">
                                            <strong>Utilisateur</strong>
                                        </label>
                                        <input id="search_enr_user" class="form-control mb-1" type="text" name="search_enr_user" placeholder="Rechercher un utilisateur" value="{{ old('search_enr_user') }}" />
                                        <div id="loadingSpinner" style="display: none;"><small>Recherche en cours...</small></div>
                                        <select id="enr_user" class="form-select @error('enr_user', 'enr_ligne_errors') is-invalid @enderror" name="enr_user">
                                            <option value="0" disabled {{ old('enr_user') ? '' : 'selected' }}>Choisir un utilisateur</option>
                                            @foreach ($utilisateurs as $utilisateur)
                                                <option value="{{ $utilisateur->id_utilisateur }}" {{ old('enr_user') == $utilisateur->id_utilisateur ? 'selected' : '' }}>
                                                    {{ $utilisateur->nom }} {{ $utilisateur->prenom }} | {{ $utilisateur->login }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="selected_user" id="selected_user_hidden" value="{{ old('enr_user') }}">
                                        @error('enr_user', 'enr_ligne_errors')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>                                    
                                    
                                    <div class="mb-3">
                                        <label class="form-label" for="enr_date">
                                            <strong>Date d&#39;affectation(activation)</strong>
                                        </label>
                                        <input id="enr_date" class="form-control @error('enr_date', 'enr_ligne_errors') is-invalid @enderror" 
                                               name="enr_date" type="date" value="{{ old('enr_date') }}" />
                                        @error('enr_date', 'enr_ligne_errors')
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
                <button id="close_modal_enr" class="btn btn-warning" type="button" data-bs-dismiss="modal">Fermer</button>
                <button class="btn btn-primary" type="submit" form="form_enr_ligne">Enregistrer</button>
            </div>
        </div>
    </div>
</div>

{{-- Voir plus --}}
<div id="modal_voir_ligne" class="modal" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <!-- Header de la modal -->
            <div class="modal-header bg-primary text-white">
                <h4 class="modal-title">Plus d&#39;information pour cette ligne</h4>
            </div>
            
            <!-- Corps de la modal -->
            <div class="modal-body bg-light">
                <div id="dataTable-2" class="table-responsive table mt-2" role="grid" aria-describedby="dataTable_info">
                    <table id="dataTable" class="table table-hover table-bordered align-middle">
                        <tbody>
                            <tr>
                                <th class="table-primary text-start">Numéro d&#39;Appel</th>
                                <td class="text-dark text-start" data-field="num_ligne"></td>
                            </tr>
                            <tr>
                                <th class="table-primary text-start">Numéro SIM</th>
                                <td class="text-dark text-start" data-field="num_sim"></td>
                            </tr>
                            <tr>
                                <th class="table-primary text-start">Type</th>
                                <td class="text-dark text-start" data-field="type_ligne"></td>
                            </tr>
                            <tr>
                                <th class="table-primary text-start">Forfait</th>
                                <td class="text-dark text-start" data-field="nom_forfait"></td>
                            </tr>
                            <tr>
                                <th class="table-primary text-start">Prix HT mensuel</th>
                                <td class="text-dark text-start" data-field="prix_forfait_ht"></td>
                            </tr>
                            <tr>
                                <th class="table-primary text-start">Responsable</th>
                                <td class="text-dark text-start" data-field="login"></td>
                            </tr>
                            <tr>
                                <th class="table-primary text-start">Localisation</th>
                                <td class="text-dark text-start" data-field="localisation"></td>
                            </tr>
                            <tr>
                                <th class="table-primary text-start">Date d&#39;affectation</th>
                                <td class="text-dark text-start" data-field="debut_affectation"></td>
                            </tr>
                            <tr>
                                <th class="table-primary text-start">Date de resiliation</th>
                                <td class="text-dark text-start" data-field="fin_affectation"></td>
                            </tr>
                        </tbody>                        
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modification ligne--}}
<div id="modal_edt_ligne" class="modal" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-primary">Modifier cette ligne</h4>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <div></div>
                    </div>
                    <div class="col-xl-8">
                        <div class="card shadow">
                            <div class="card-body">
                                <form id="form_edt_ligne" action="{{ route('ligne.edt') }}" method="get" style="color: #a0c8d8;">
                                    <div class="mb-3">
                                        <input id="edt_id_ligne" class="form-control" type="hidden" name="edt_id_ligne" value="{{ old('edt_id_ligne') }}"/>
                                    </div>                                 
                                    
                                    <!-- STATUT -->
                                    <div class="mb-3">
                                        <label class="form-label" for="edt_statut">
                                            <strong>Statut</strong>
                                        </label>
                                        <input id="edt_statut" class="form-control @error('edt_statut', 'edt_ligne_errors') is-invalid @enderror" type="text" name="edt_statut" value="{{ old('edt_statut') }}" readonly/>
                                        @error('edt_statut', 'edt_ligne_errors')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- SIM -->
                                    <div class="mb-3">
                                        <label class="form-label" for="edt_sim">
                                            <strong>Numéro SIM</strong>
                                        </label>
                                        <input id="edt_sim" class="form-control @error('edt_sim', 'edt_ligne_errors') is-invalid @enderror" type="number" name="edt_sim" value="{{ old('edt_sim') }}"  />
                                        @error('edt_sim', 'edt_ligne_errors')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Ligne -->                                    
                                    <div class="mb-3">
                                        <label class="form-label" for="edt_ligne">
                                            <strong>Numéro ligne</strong></label>
                                            <input id="edt_ligne" class="form-control @error('edt_ligne', 'edt_ligne_errors') is-invalid @enderror" type="text" name="edt_ligne" value="{{ old('edt_ligne') }}" />
                                        @error('edt_ligne', 'edt_ligne_errors')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        </div>

                                    <!-- Operateur -->                                    
                                    <div class="mb-3">
                                        <label class="form-label" for="edt_operateur">
                                            <strong>Opérateur</strong>
                                        </label>
                                        <select id="edt_operateur" class="form-select @error('edt_operateur', 'edt_ligne_errors') is-invalid @enderror" 
                                                name="edt_operateur" required>
                                            <option value="" disabled {{ old('edt_operateur') ? '' : 'selected' }}>Choisir l'opérateur</option>
                                            @foreach ($contactsOperateurs as $contact)
                                                <option value="{{ $contact->id_operateur }}"
                                                    {{ old('edt_operateur') == $contact->id_operateur ? 'selected' : '' }}>
                                                    {{ $contact->operateur->nom_operateur }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('edt_operateur', 'edt_ligne_errors')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Type -->
                                    <div class="mb-3">
                                        <label class="form-label" for="edt_type"><strong>Type</strong></label>
                                        <select id="edt_type" class="form-select @error('edt_type', 'edt_ligne_errors') is-invalid @enderror" 
                                                name="edt_type" required>
                                            <option value="" disabled {{ old('edt_type') ? '' : 'selected' }}>Choisir le type</option>
                                            @foreach ($types as $type)
                                                <option value="{{ $type->id_type_ligne }}"
                                                    {{ old('edt_type') == $type->id_type_ligne ? 'selected' : '' }}>
                                                    {{ $type->type_ligne }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('edt_type', 'edt_ligne_errors')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Forfait -->
                                    <div class="mb-3">
                                        <label class="form-label" for="edt_forfait"><strong>Forfait</strong></label>
                                        <select id="edt_forfait" class="form-select @error('edt_forfait', 'edt_ligne_errors') is-invalid @enderror" 
                                                name="edt_forfait" {{ old('edt_forfait') }}>
                                            <option value="" {{ old('edt_forfait') ? '' : 'selected' }}>Choisir le forfait</option>
                                            @foreach ($forfaits as $forfait)
                                                <option value="{{ $forfait->id_forfait }}" 
                                                        data-id-operateur-edt="{{ $forfait->id_operateur }}" 
                                                        data-id-type-forfait-edt="{{ $forfait->id_type_forfait }}"
                                                    {{ old('edt_forfait') == $forfait->id_forfait ? 'selected' : '' }}>
                                                    {{ $forfait->nom_forfait }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('edt_forfait', 'edt_ligne_errors')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Responsable -->                                   
                                    <div class="mb-3">
                                        <label class="form-label" for="edt_resp">
                                            <strong>Responsable</strong>
                                        </label>
                                        <input id="edt_resp" class="form-control" type="text" name="edt_resp" value="{{ old('edt_resp') }}" readonly/>
                                    </div>

                                    <!-- Date d'affectation -->                                    
                                    <div class="mb-3">
                                        <label class="form-label" for="edt_date">
                                            <strong>Date d&#39;affectation(activation)</strong>
                                        </label>
                                        <input id="edt_date" class="form-control @error('edt_date', 'edt_ligne_errors') is-invalid @enderror" type="date" name="edt_date" value="{{ old('edt_date') }}" />
                                        @error('edt_date', 'edt_ligne_errors')
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
                <button id="btn_modifier" class="btn btn-primary" type="submit" form="form_edt_ligne" >Modifier</button>
            </div>
        </div>
    </div>
</div>

{{-- Demande résiliation --}}
<div id="modal_resil_ligne" class="modal" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h4 class="modal-title text-light">Demande de Résiliation</h4>
                <button id="close_modal_rsl" class="btn-close text-white" type="button" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow">
                            <div class="card-body">
                                <form id="form_rsl_ligne" action="{{ route('ligne.rsl') }}" method="post">
                                    @csrf
                                    <input type="hidden" id="resil_id_ligne" name="resil_id_ligne" />
                                    <input type="hidden" id="resil_id_aff" name="resil_id_affectation" />
                                    <input type="hidden" id="resil_email" name="resil_email" />

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label" for="resil_ligne">
                                                <strong>Numéro d'Appel</strong>
                                            </label>
                                            <input id="resil_ligne" class="form-control" type="text" name="resil_ligne" readonly />
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="resil_sim">
                                                <strong>Numéro SIM</strong>
                                            </label>
                                            <input id="resil_sim" class="form-control" type="text" name="resil_sim" readonly />
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label" for="resil_operateur">
                                                <strong>Opérateur</strong>
                                            </label>
                                            <input id="resil_operateur" class="form-control" type="text" name="resil_operateur" readonly />
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="resil_type">
                                                <strong>Type</strong>
                                            </label>
                                            <input id="resil_type" class="form-control" type="text" name="resil_type" readonly />
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label" for="resil_forfait">
                                                <strong>Forfait</strong>
                                            </label>
                                            <input id="resil_forfait" class="form-control" type="text" name="resil_forfait" readonly />
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="resil_prix">
                                                <strong>Prix HT mensuel</strong>
                                            </label>
                                            <input id="resil_prix" class="form-control" type="text" name="resil_prix" readonly />
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col">
                                            <label class="form-label" for="resil_localisation">
                                                <strong>Localisation</strong>
                                            </label>
                                            <input id="resil_localisation" class="form-control" type="text" name="resil_localisation" readonly />
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label" for="resil_responsable">
                                                <strong>Responsable</strong>
                                            </label>
                                            <input id="resil_responsable" class="form-control" type="text" name="resil_responsable" readonly />
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="resil_date_affectation">
                                                <strong>Date d'affectation(activation)</strong>
                                            </label>
                                            <input id="resil_date_affectation" class="form-control" type="text" name="resil_date_affectation" readonly />
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-4">
                                        <div class="col">
                                            <label class="form-label" for="resil_date">
                                                <strong>Date de Résiliation<span class="text-danger">*</span></strong>
                                            </label>
                                            <input 
                                                id="resil_date" 
                                                class="form-control @error('resil_date', 'rsl_ligne_errors') is-invalid @enderror" 
                                                type="date" 
                                                name="resil_date" 
                                                value="{{ old('resil_date') }}" 
                                                required 
                                            />
                                            @error('resil_date', 'rsl_ligne_errors')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>                          

                                    <!-- Texte de warning -->
                                    <div class="row mb-0">
                                        <div class="col">
                                            <p class="text-danger small mb-0">
                                                <em>*Ce formulaire génère automatiquement un email de demande de résiliation après sa soumission.</em>
                                            </p>
                                        </div>
                                    </div>
                                    <!-- Fin Texte de warning -->

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button id="close_modal_rsl" class="btn btn-secondary" type="button" data-bs-dismiss="modal">Annuler</button>
                <button id="confirm_rsl_ligne" class="btn btn-danger" type="submit" form="form_rsl_ligne">
                    Confirmer la Résiliation
                </button>
            </div>
        </div>
    </div>
</div>

<div id="modal_react_ligne" class="modal" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-primary">Demande de réactivation d'une ligne</h4>
                <button id="close_modal_react" class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <div></div>
                    </div>
                    <div class="col-xl-8">
                        <div class="card shadow">
                            <div class="card-body">
                                <form id="form_react_ligne" action="{{ route('ligne.react') }}" method="POST" style="color: #a0c8d8;">
                                    @csrf <!-- Protection CSRF -->

                                    <!-- Champ caché pour l'ID de la ligne -->
                                    <input type="hidden" id="react_ligne_id" name="react_ligne_id" value="">

                                    <!-- Numéro SIM -->
                                    <div class="mb-3">
                                        <label class="form-label" for="react_sim"><strong>Numéro SIM</strong></label>
                                        <input id="react_sim" class="form-control @error('react_sim', 'react_ligne_errors') is-invalid @enderror" 
                                            type="number" name="react_sim" placeholder="Numéro SIM" 
                                            value="{{ old('react_sim') }}" readonly/>
                                        @error('react_sim', 'react_ligne_errors')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Opérateur (en lecture seule) -->
                                    <div class="mb-3">
                                        <label class="form-label" for="react_operateur"><strong>Opérateur</strong></label>
                                        <input id="react_operateur_name" class="form-control" type="text" name="react_operateur_name"
                                            readonly />
                                        <!-- Champ caché pour envoyer l'ID de l'opérateur au backend -->
                                        <input id="react_operateur" type="hidden" name="react_operateur" value="">
                                        <!-- Champ caché pour envoyer l'email de l'opérateur -->
                                        <input id="react_operateur_email" type="hidden" name="react_operateur_email" value="">
                                    </div>

                                    <!-- Type -->
                                    <div class="mb-3">
                                        <label class="form-label" for="react_type"><strong>Type</strong></label>
                                        <select id="react_type" class="form-select @error('react_type', 'react_ligne_errors') is-invalid @enderror" 
                                                name="react_type" required>
                                            <option value="" disabled {{ old('react_type') ? '' : 'selected' }}>Choisir le type</option>
                                            @foreach ($types as $type)
                                                <option value="{{ $type->id_type_ligne }}"
                                                    {{ old('react_type') == $type->id_type_ligne ? 'selected' : '' }}>
                                                    {{ $type->type_ligne }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('react_type', 'react_ligne_errors')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Forfait -->
                                    <div class="mb-3">
                                        <label class="form-label" for="react_forfait"><strong>Forfait</strong></label>
                                        <select id="react_forfait" class="form-select @error('react_forfait', 'react_ligne_errors') is-invalid @enderror" 
                                                name="react_forfait" {{ old('react_forfait') ? '' : 'disabled' }}>
                                            <option value="" disabled>Choisir le forfait</option>
                                            @foreach ($forfaits as $forfait)
                                                <option value="{{ $forfait->id_forfait }}" 
                                                        data-id-operateur="{{ $forfait->id_operateur }}" 
                                                        data-id-type-forfait="{{ $forfait->id_type_forfait }}"
                                                    {{ old('react_forfait') == $forfait->id_forfait ? 'selected' : '' }}>
                                                    {{ $forfait->nom_forfait }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('react_forfait', 'react_ligne_errors')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Texte de warning -->
                                    <div class="row mb-2">
                                        <div class="col">
                                            <p class="text-info small mb-0">
                                                <em>*Ce formulaire génère automatiquement un email de demande de réactivation après sa soumission.</em>
                                            </p>
                                        </div>
                                    </div>
                                    <!-- Fin Texte de warning -->
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
                <button id="close_modal_react" class="btn btn-warning" type="button" data-bs-dismiss="modal">Fermer</button>
                <button id="btn_demander" class="btn btn-primary" type="submit" form="form_react_ligne">Demander</button>
            </div>
        </div>
    </div>
</div>

<div id="modal_histo_ligne" class="modal fade" role="dialog" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-custom modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-primary text-white">
                <h4 id="modalTitle" class="modal-title">Historique d'affectation pour cette ligne</h4>
            </div>
            <div class="modal-body">
                <!-- Détails du téléphone -->
                <div class="mb-4">
                    <div class="row">
                        <div class="col">
                            <p class="text-dark mb-1">
                                <span class="fw-bold">SIM :</span>
                                <span class="fw-normal" data-field="sim"></span>
                            </p>
                        </div>
                        <div class="col"> 
                            <p class="text-dark mb-1">
                                <span class="fw-bold">Opérateur :</span>
                                <span class="fw-normal" data-field="operateur"></span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Table des affectations -->
                <div class="table-responsive">
                    <table id="dataTable" class="table table-bordered table-hover align-middle">
                        <thead class="table-primary">
                            <tr>
                                <th class="text-dark">Utilisateur</th>
                                <th class="text-dark">Login</th>
                                <th class="text-dark">Localisation</th>
                                <th class="text-dark">Numéro Ligne</th>
                                <th class="text-dark">Type</th>
                                <th class="text-dark">Forfait</th>
                                <th class="text-dark">Prix HT Mensuel</th>
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