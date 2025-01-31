<div id="ajouter_forfait" class="modal" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-primary">Ajouter un forfait</h4><button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <div></div>
                    </div>
                    <div class="col-xl-8">
                        <div class="card shadow">
                            <div class="card-body">
                                <form id="add_forfait" action="add_forfait" method="get" style="color: #a0c8d8;">
                                    <div class="mb-3"><label class="form-label" for="add_lib_ue"><strong>Nom du forfait</strong></label><input id="add_bu-1" class="form-control" type="text" placeholder="Entrer le nom du forfait" name="add_bu" /></div>
                                    <div class="mb-3"><label class="form-label" for="add_element-1"><strong>Appel Flotte initial</strong><br /></label>
                                        <div class="input-group"><span class="input-group-text">Quantité</span><input class="form-control" type="number" placeholder="Entrer la quantité" name="add_element-1" min="0" value="0" /><span class="input-group-text">Unité=Heures</span></div>
                                    </div>
                                    <div class="mb-3"><label class="form-label" for="add_element-1"><strong>Appel Flotte supplémentaire</strong><br /></label>
                                        <div class="input-group"><span class="input-group-text">Quantité</span><input class="form-control" type="number" placeholder="Entrer la quantité" name="add_element-1" min="0" value="0" /><span class="input-group-text">Unité=Heures</span></div>
                                    </div>
                                    <div class="mb-3"><label class="form-label" for="add_element-1"><strong>Appel Tout TELMA</strong><br /></label>
                                        <div class="input-group"><span class="input-group-text">Quantité</span><input class="form-control" type="number" placeholder="Entrer la quantité" name="add_element-1" min="0" value="0" /><span class="input-group-text">Unité=Heures</span></div>
                                    </div>
                                    <div class="mb-3"><label class="form-label" for="add_element-1"><strong>Appel Tout MADA</strong><br /></label>
                                        <div class="input-group"><span class="input-group-text">Quantité</span><input class="form-control" type="number" placeholder="Entrer la quantité" name="add_element-1" min="0" value="0" /><span class="input-group-text">Unité=Heures</span></div>
                                    </div>
                                    <div class="mb-3"><label class="form-label" for="add_element-1"><strong>Appel vers Etranger</strong><br /></label>
                                        <div class="input-group"><span class="input-group-text">Quantité</span><input class="form-control" type="number" placeholder="Entrer la quantité" name="add_element-1" min="0" value="0" /><span class="input-group-text">Unité=Heures</span></div>
                                    </div>
                                    <div class="mb-3"><label class="form-label" for="add_element-1"><strong>DATA</strong><br /></label>
                                        <div class="input-group"><span class="input-group-text">Quantité</span><input class="form-control" type="number" placeholder="Entrer la quantité" name="add_element-1" min="0" value="0" /><span class="input-group-text">Unité=15Go</span></div>
                                    </div>
                                    <div class="mb-3"><label class="form-label" for="add_element-1"><strong>SMS</strong><br /></label>
                                        <div class="input-group"><span class="input-group-text">Quantité</span><input class="form-control" type="number" placeholder="Entrer la quantité" name="add_element-1" min="0" value="0" /><span class="input-group-text">Unité=100Sms</span></div>
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
            <div class="modal-footer"><button class="btn btn-warning" type="button" data-bs-dismiss="modal">Fermer</button><button class="btn btn-primary" type="submit" form="add_forfait">Ajouter</button></div>
        </div>
    </div>
</div>
<div id="modifier_element" class="modal" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-primary">Modifier cet élément</h4><button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <div></div>
                    </div>
                    <div class="col-xl-6">
                        <div class="card shadow">
                            <div class="card-body">
                                <form id="edt_element_form" action="" method="get">
                                    <!-- Champs cachés -->
                                    <input type="hidden" id="edt_id_element" name="edt_id_element" value="{{ old('edt_id_element') }}">
                                    <input type="hidden" id="edt_id_forfait" name="edt_id_forfait" value="{{ old('edt_id_forfait') }}">

                                    <!-- Champ Elément -->
                                    <div class="mb-3">
                                        <label class="form-label" for="edt_element"><strong>Elément</strong></label>
                                        <input 
                                            id="edt_element" 
                                            class="form-control @error('edt_element', 'edt_element_errors') is-invalid @enderror" 
                                            type="text" 
                                            name="edt_element" 
                                            readonly 
                                            value="{{ old('edt_element') }}">
                                        @error('edt_element', 'edt_element_errors')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Champ Unité -->
                                    <div class="mb-3">
                                        <label class="form-label" for="edt_unite"><strong>Unité</strong></label>
                                        <input 
                                            id="edt_unite" 
                                            class="form-control @error('edt_unite', 'edt_element_errors') is-invalid @enderror" 
                                            type="text" 
                                            name="edt_unite" 
                                            readonly 
                                            value="{{ old('edt_unite') }}">
                                        @error('edt_unite', 'edt_element_errors')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Champ Prix Unitaire -->
                                    <div class="mb-3">
                                        <label class="form-label" for="edt_pu"><strong>Prix Unitaire</strong></label>
                                        <div class="input-group">
                                            <input 
                                                id="edt_pu" 
                                                class="form-control @error('edt_pu', 'edt_element_errors') is-invalid @enderror" 
                                                type="number" 
                                                name="edt_pu" 
                                                value="{{ old('edt_pu') }}">
                                            <span class="input-group-text">Ar</span>
                                            @error('edt_pu', 'edt_element_errors')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Champ Quantité -->
                                    <div class="mb-3">
                                        <label class="form-label" for="edt_qu"><strong>Quantité</strong></label>
                                        <input 
                                            id="edt_qu" 
                                            class="form-control @error('edt_qu', 'edt_element_errors') is-invalid @enderror" 
                                            type="number" 
                                            name="edt_qu" 
                                            value="{{ old('edt_qu') }}" 
                                            required>
                                        @error('edt_qu', 'edt_element_errors')
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
                <button class="btn btn-info" type="submit" form="edt_element_form">Modifier</button>
            </div>
        </div>
    </div>
</div>
<div id="supprimer_element" class="modal" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-danger">Voulez vous vraiment supprimer cet élémént?</h4><button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <div></div>
                    </div>
                    <div class="col-xl-6">
                        <div class="card shadow">
                            <div class="card-body">
                                <form id="del_element_form" action="" method="get">
                                    @if ($errors->hasBag('del_element_errors'))
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->del_element_errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <input type="hidden" id="del_id_element" name="del_id_element" value="{{ old('del_id_element') }}">
                                    <input type="hidden" id="del_id_forfait" name="del_id_forfait" value="{{ old('del_id_forfait') }}">

                                    <div class="mb-3">
                                        <label class="form-label" for="del_element"><strong>Elément</strong></label>
                                        <input id="del_element" class="form-control" type="text" name="del_element" readonly value="{{ old('del_element') }}">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="del_unite"><strong>Unité</strong></label>
                                        <input id="del_unite" class="form-control" type="text" name="del_unite" readonly value="{{ old('del_unite') }}">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="del_pu"><strong>Prix Unitaire</strong></label>
                                        <div class="input-group">
                                            <input id="del_pu" class="form-control @error('del_pu') is-invalid @enderror" type="number" name="del_pu" value="{{ old('del_pu') }}" readonly>
                                            <span class="input-group-text">Ar</span>
                                            @error('del_pu')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="del_qu"><strong>Quantité</strong></label>
                                        <input id="del_qu" class="form-control @error('del_qu') is-invalid @enderror" type="number" name="del_qu" value="{{ old('del_qu') }}" readonly>
                                        @error('del_qu')
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
                <button class="btn btn-danger" type="submit" form="del_element_form">Supprimer</button>
            </div>
        </div>
    </div>
</div>