@extends('base/baseRef')
<head>
    <title>Lignes - Telecom</title>
</head>
<style>
    /* Couleurs pour le style global */
    td, .input-group-text {
        color: #0a4866 !important; /* Appliquer le style globalement */
    }
</style>    
@section('content_ref')

    <div class="container-fluid">
        <h3 class="text-dark mb-1">
            <i class="fas fa-satellite-dish" style="padding-right: 5px;"></i>Lignes
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
            <a class="btn btn-primary btn-icon-split" role="button" data-bs-target="#modal_act_ligne" data-bs-toggle="modal">
                <span class="icon"><i class="fas fa-plus-circle mt-1"></i></span>
                <span class="text">Demander l&#39;activation d&#39;une ligne</span>
            </a>
        </div>
        <div class="card shadow">
            <div class="card-header py-3">
                <p class="m-0 fw-bold" style="color: #0a4866;">Gestion des lignes</p>
            </div>
            <div class="card-body">
                <div class="row mt-2">
                     <!-- Formulaire pour rechercher par numéro de ligne -->
                    <div class="col">
                        <form action="{{ route('ref.ligne') }}" method="get">
                            <div class="input-group">
                                <span class="input-group-text">Ligne</span>
                                <input 
                                    class="form-control" 
                                    type="text" 
                                    placeholder="Rechercher par Ligne" 
                                    name="search_ligne_num" 
                                    value="{{ request('reset_filters') == 'reset' ? '' : request('search_ligne_num') }}" />
                                
                                <!-- Inputs cachés pour conserver les autres filtres/recherches -->
                                <input type="hidden" name="search_ligne_sim" value="{{ request('search_ligne_sim') }}">
                                <input type="hidden" name="search_ligne_user" value="{{ request('search_ligne_user') }}">
                                <input type="hidden" name="statut" value="{{ request('statut') }}">
                                <input type="hidden" name="type" value="{{ request('type') }}">

                                <button class="btn btn-primary" type="submit">Rechercher</button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Formulaire pour rechercher par numéro de SIM -->
                    <div class="col">
                        <form action="{{ route('ref.ligne') }}" method="get">
                            <div class="input-group">
                                <span class="input-group-text">SIM</span>
                                <input 
                                    class="form-control" 
                                    type="text" 
                                    placeholder="Rechercher par SIM" 
                                    name="search_ligne_sim" 
                                    value="{{ request('reset_filters') == 'reset' ? '' : request('search_ligne_sim') }}" />
                                
                                <!-- Inputs cachés pour conserver les autres filtres/recherches -->
                                <input type="hidden" name="search_ligne_num" value="{{ request('search_ligne_num') }}">
                                <input type="hidden" name="search_ligne_user" value="{{ request('search_ligne_user') }}">
                                <input type="hidden" name="statut" value="{{ request('statut') }}">
                                <input type="hidden" name="type" value="{{ request('type') }}">

                                <button class="btn btn-primary" type="submit">Rechercher</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col">
                        <div></div>
                    </div>
                    <div class="col">
                        <form action="{{ route('ref.ligne') }}" method="get">
                            <div class="input-group">
                                <span class="input-group-text">Utilisateur</span>
                                <input 
                                    class="form-control" 
                                    type="text" 
                                    placeholder="Rechercher par Utilisateur" 
                                    name="search_ligne_user" 
                                    value="{{ request('reset_filters') == 'reset' ? '' : request('search_ligne_user') }}" />
                                
                                <!-- Inputs cachés pour conserver les autres filtres/recherches -->
                                <input type="hidden" name="search_ligne_sim" value="{{ request('search_ligne_sim') }}">
                                <input type="hidden" name="search_ligne_num" value="{{ request('search_ligne_num') }}">
                                <input type="hidden" name="statut" value="{{ request('statut') }}">
                                <input type="hidden" name="type" value="{{ request('type') }}">

                                <button class="btn btn-primary" type="submit">Rechercher</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col">
                        <form action="{{ route('ref.ligne') }}" method="get">
                            <div class="btn-group" role="group">
                                {{-- Bouton "Tout" pour réinitialiser les filtres --}}
                                <button 
                                    class="btn btn-outline-primary {{ is_null(request('statut')) ? 'active' : '' }}" 
                                    type="submit" 
                                    name="reset_filters" 
                                    value="reset">
                                    Tout
                                </button>

                                <input type="hidden" name="search_ligne_num" value="{{ request('search_ligne_num') }}">
                                <input type="hidden" name="search_ligne_sim" value="{{ request('search_ligne_sim') }}">
                                <input type="hidden" name="search_ligne_user" value="{{ request('search_ligne_user') }}">
                                <input type="hidden" name="type" value="{{ request('type') }}">
                        
                                {{-- Boutons dynamiques pour chaque statut --}}
                                @foreach ($statuts as $statut)
                                    @php
                                        $colorClass = App\Models\StatutLigne::getBootstrapClass($statut->id_statut_ligne);
                                    @endphp
                                    <button 
                                        class="btn btn-outline-{{ $colorClass }} {{ request('statut') == $statut->id_statut_ligne ? 'active' : '' }}" 
                                        type="submit" 
                                        name="statut" 
                                        value="{{ $statut->id_statut_ligne }}">
                                        {{ $statut->statut_ligne }}
                                    </button>
                                @endforeach
                            </div>
                        </form>
                    </div>
                    <div class="col">
                        <form action="{{ route('ref.ligne') }}" method="get">
                            <div class="btn-group" role="group">
                                {{-- Bouton "Tout" pour réinitialiser les filtres --}}
                                <button 
                                    class="btn btn-outline-primary {{ is_null(request('type')) ? 'active' : '' }}" 
                                    type="submit" 
                                    name="reset_filters" 
                                    value="reset">
                                    Tout
                                </button>

                                <input type="hidden" name="search_ligne_num" value="{{ request('search_ligne_num') }}">
                                <input type="hidden" name="search_ligne_sim" value="{{ request('search_ligne_sim') }}">
                                <input type="hidden" name="search_ligne_user" value="{{ request('search_ligne_user') }}">
                                <input type="hidden" name="statut" value="{{ request('statut') }}">
                        
                                {{-- Boutons dynamiques pour chaque type --}}
                                @foreach ($types as $type)
                                    <button 
                                        class="btn btn-outline-primary {{ request('type') == $type->id_type_ligne ? 'active' : '' }}" 
                                        type="submit" 
                                        name="type" 
                                        value="{{ $type->id_type_ligne }}">
                                        {{ $type->type_ligne }}
                                    </button>
                                @endforeach
                            </div>
                        </form>
                    </div>
                </div>
                <div id="dataTable-1" class="table-responsive table mt-2">
                    <table id="dataTable" class="table table-hover table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Statut</th>
                                <th>Type</th>
                                <th>Forfait</th>
                                <th>Numéro Ligne</th>
                                <th>Numéro SIM</th>
                                <th>Utilisateur</th>
                                <th>Opérateur</th>
                                <th class="text-center" style="padding-right: 0px;padding-left: 0px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($lignes as $ligne)
                            <tr>
                                <td>{{ $ligne->statut_ligne }}</td>
                                <td>{{ $ligne->type_ligne }}</td>
                                <td>{{ $ligne->nom_forfait }}</td>
                                <td>{{ $ligne->num_ligne ?? '--' }}</td>
                                <td>{{ $ligne->num_sim }}</td>
                                <td>{{ $ligne->login ?? '--' }}</td>
                                <td>
                                    <a href="#" 
                                        class="mailto-link text-decoration-none"
                                        data-email="{{ $ligne->contact_email }}"
                                        data-num-sim="{{ $ligne->num_sim }}"
                                        data-forfait="{{ $ligne->nom_forfait }}"
                                        title="Relancer">
                                        <i class="far fa-paper-plane"></i>
                                        {{ $ligne->nom_operateur }}
                                    </a>
                                </td>
                                <!-- Actions -->
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        {{-- Boutons spécifiques au statut --}}
                                        @if ($ligne->statut_ligne === 'Resilie')
                                            {{-- <a id="btn_react_ligne"
                                                class="text-decoration-none"
                                                style="margin-right: 5px;" 
                                                data-bs-target="#modal_react_ligne" 
                                                data-bs-toggle="modal" 
                                                title="Réactiver" 
                                                href="#"
                                                data-sim-react="{{ $ligne->num_sim }}"
                                                data-operateur-react="{{ $ligne->id_operateur }}"
                                                data-operateur-email-react="{{ $ligne->contact_email }}"
                                                data-operateur-name-react="{{ $ligne->nom_operateur }}"
                                                data-type-react="{{ $ligne->id_type_ligne }}"
                                                data-forfait-react="{{ $ligne->id_forfait }}" 
                                                data-id-react="{{ $ligne->id_ligne }}">
                                                <i class="far fa-arrow-alt-circle-up text-success" style="font-size: 25px;"></i>
                                            </a> --}}
                                        @elseif ($ligne->statut_ligne === 'Inactif' || $ligne->statut_ligne === 'En attente')    
                                            <a href="#"
                                                id="btn_enr_ligne"
                                                class="text-decoration-none"
                                                style="margin-right: 5px;" 
                                                data-bs-target="#modal_enr_ligne" 
                                                data-bs-toggle="modal" 
                                                title="Enregistrer" 
                                                data-ligne-enr="{{ $ligne->num_ligne ?? 0 }}"
                                                data-sim-enr="{{ $ligne->num_sim }}" 
                                                data-id-forfait-enr="{{ $ligne->id_forfait }}" 
                                                data-forfait-enr="{{ $ligne->nom_forfait }}" 
                                                data-id-enr="{{ $ligne->id_ligne }}">
                                                <i class="far fa-save text-info" style="font-size: 25px;"></i>
                                            </a>
                                        @elseif ($ligne->statut_ligne === 'Attribue')
                                            <a href="#" 
                                                id="btn_resil_ligne"
                                                style="margin-right: 5px;"
                                                class="text-decoration-none"
                                                data-bs-target="#modal_resil_ligne" 
                                                data-bs-toggle="modal" 
                                                title="Résilier"
                                                data-sim-resil="{{ $ligne->num_sim }}"
                                                data-ligne-resil="{{ $ligne->num_ligne }}"
                                                data-operateur-resil="{{ $ligne->nom_operateur }}"
                                                data-email-resil="{{ $ligne->contact_email }}"
                                                data-type-resil="{{ $ligne->type_ligne }}"
                                                data-forfait-resil="{{ $ligne->nom_forfait }}"
                                                data-prix-resil="{{ $ligne->prix_forfait_ht }}"
                                                data-resp-resil="{{ $ligne->login }}"
                                                data-localisation-resil="{{ $ligne->localisation }}"
                                                data-date-resil="{{ $ligne->debut_affectation }}"
                                                data-id-aff-resil="{{ $ligne->id_affectation }}"
                                                data-id-resil="{{ $ligne->id_ligne }}">
                                                <i class="far fa-window-close text-danger" style="font-size: 25px;"></i>
                                            </a>                     
                                        @endif
                                        <a href="{{ url('/ligne/detailLigne/' . $ligne->id_ligne) }}"
                                            id="btn_voir_ligne"
                                            class="text-decoration-none"
                                            style="margin-right: 5px;" 
                                            data-bs-target="#modal_voir_ligne" 
                                            title="Plus d'information"
                                            data-bs-toggle="modal"
                                            data-id-voir="{{ $ligne->id_ligne }}">
                                            <i class="fas fa-info-circle text-primary" style="font-size: 25px;"></i>
                                        </a> 
                                        {{-- Boutons en commun --}}
                                        <a href="{{ url('/ligne/histoLigne/' . $ligne->id_ligne) }}"
                                            id="btn_histo_ligne"
                                            class="text-decoration-none"
                                            style="margin-right: 5px;" 
                                            data-bs-target="#modal_histo_ligne" 
                                            title="Historique"
                                            data-bs-toggle="modal"
                                            data-id-histo="{{ $ligne->id_ligne }}"
                                            data-sim-histo="{{ $ligne->num_sim }}"
                                            data-operateur-histo="{{ $ligne->nom_operateur }}"
                                            >
                                            <i class="fas fa-history text-primary" style="font-size: 25px;"></i>
                                        </a>   
                                        <a href="#"
                                            id="btn_edt_ligne"
                                            class="text-decoration-none"
                                            data-bs-target="#modal_edt_ligne" 
                                            data-bs-toggle="modal" 
                                            title="Modifier"
                                            data-sim-edt="{{ $ligne->num_sim }}"
                                            data-ligne-edt="{{ $ligne->num_ligne }}"
                                            data-operateur-edt="{{ $ligne->id_operateur }}"
                                            data-type-edt="{{ $ligne->id_type_ligne }}"
                                            data-forfait-edt="{{ $ligne->id_forfait }}"
                                            data-responsable-edt="{{ $ligne->login }}"
                                            data-date-edt="{{ $ligne->debut_affectation }}"
                                            data-id-edt="{{ $ligne->id_ligne }}"
                                            data-statut-edt="{{ $ligne->statut_ligne }}">
                                            <i class="far fa-edit text-warning" style="font-size: 25px;"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">Aucune ligne trouvée.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Liens de pagination -->
                <div class="mt-4">
                    {{ $lignes->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
                
            </div>
        </div>
    </div>

@endsection

@section('modal_ref')

    @include('modals.ligneModal')

@endsection

@section('scripts')

    @include('js.ligneJs')

@endsection