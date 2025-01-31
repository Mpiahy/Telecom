<div id="ajouter_chantier" class="modal" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-primary">Ajouter une localisation</h4>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-6 mx-auto">
                        <div class="card shadow">
                            <div class="card-body">
                                <form id="add_chantier" method="post" action="{{ route('ref.chantier.add') }}" style="color: #a0c8d8;">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label" for="add_service"><strong>Libellé Service</strong></label>
                                        <select id="add_service" 
                                                class="form-select @error('add_service','add_chantier_errors') is-invalid @enderror" 
                                                name="add_service">
                                            <option value="0" selected disabled>Choisir Service</option>
                                            @foreach ($services as $service)
                                                <option value="{{ $service->id_service }}" {{ old('add_service') == $service->id_service ? 'selected' : '' }}>
                                                    {{ $service->libelle_service }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('add_service','add_chantier_errors')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="add_lib_imp"><strong>Libellé Imputation</strong></label>
                                        <input id="add_lib_imp" 
                                                class="form-control @error('add_lib_imp','add_chantier_errors') is-invalid @enderror" 
                                                type="text" 
                                                name="add_lib_imp" 
                                                placeholder="Entrer l'imputation"
                                                value="{{ old('add_lib_imp') }}" />
                                        @error('add_lib_imp','add_chantier_errors')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-warning" type="button" data-bs-dismiss="modal">Fermer</button>
                <button class="btn btn-primary" type="submit" form="add_chantier">Ajouter</button>
            </div>
        </div>
    </div>
</div>

<div id="modifier_chantier" class="modal" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-primary">Modifier cette localisation</h4>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-9 mx-auto">
                        <div class="card shadow">
                            <div class="card-body">
                                <form id="edt_chantier" action="" method="post" style="color: #a0c8d8;">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label" for="edt_service"><strong>Libellé Service</strong></label>
                                        <select id="edt_service" 
                                                class="form-select @error('edt_service','edt_chantier_errors') is-invalid @enderror" 
                                                name="edt_service">
                                            <option value="0" disabled>Choisir Service</option>
                                            @foreach ($services as $service)
                                                <option value="{{ $service->id_service }}" {{ old('edt_service') == $service->id_service ? 'selected' : '' }}>
                                                    {{ $service->libelle_service }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('edt_service','edt_chantier_errors')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="edt_imp"><strong>Libellé Imputation</strong></label>
                                        <input id="edt_imp" 
                                               class="form-control @error('edt_imp','edt_chantier_errors') is-invalid @enderror" 
                                               type="text" 
                                               placeholder="Entrer l'imputation" 
                                               name="edt_imp" 
                                               value="{{ old('edt_imp') }}" />
                                        @error('edt_imp','edt_chantier_errors')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-warning" type="button" data-bs-dismiss="modal">Fermer</button>
                <button class="btn btn-info" type="submit" form="edt_chantier">Modifier</button>
            </div>
        </div>
    </div>
</div>

<div id="supprimer_chantier" class="modal" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-danger">Voulez-vous vraiment supprimer cette localisation ?</h4>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-dark" style="margin-bottom: 0px;">Localisation: <strong>{{ old('localisation') }}</strong></p>
                @if (session('modal_del_errors'))
                    <div class="text-danger">{{ session('modal_del_errors')['id'] ?? '' }}</div>
                @endif
            </div>
            <div class="modal-footer">
                <button class="btn btn-warning" type="button" data-bs-dismiss="modal">Fermer</button>
                <a href="#" class="btn btn-danger">Supprimer</a>
            </div>
        </div>
    </div>
</div>
