@extends('base/baseRef')
<head>
    <title>Forfaits - Telecom</title>
</head>
<style>
    /* Couleurs pour le style global */
    td, .input-group-text {
        color: #0a4866 !important; /* Appliquer le style globalement */
    }
</style>    
@section('content_ref')

    <div class="container-fluid">
        <h3 class="text-dark"><i class="fas fa-money-check-alt" style="padding-right: 5px;"></i>Offres et forfaits</h3>
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
        <div class="text-center mb-4"><a class="btn btn-primary btn-icon-split" role="button" data-bs-target="#ajouter_forfait" data-bs-toggle="modal"><span class="icon"><i class="fas fa-plus-circle" style="padding-top: 5px;"></i></span><span class="text">Ajouter un nouveau forfait</span></a></div>
        <div class="card shadow">
            <div class="card-header py-3">
                <p class="m-0 fw-bold" style="color: #0a4866;">Gestion des forfaits</p>
            </div>
            <div class="card-body">
                <div class="row mt-2">
                    <div class="col">
                        <form method="get" action="{{ route('ref.forfait') }}">
                            <div class="input-group">
                                <span class="input-group-text">Type de Forfait</span>
                                <select name="filter_type_forfait" class="form-select">
                                    @foreach ($types_forfait as $type_forfait)
                                        <option value="{{ $type_forfait->id_type_forfait }}" {{ request('filter_type_forfait') == $type_forfait->id_type_forfait ? 'selected' : '' }}>
                                            {{ $type_forfait->type_forfait }}
                                        </option>
                                    @endforeach
                                </select>
                                @foreach (['filter_operateur'] as $filter)
                                    @if (request($filter))
                                        <input type="hidden" name="{{ $filter }}" value="{{ request($filter) }}">
                                    @endif
                                @endforeach
                                <button class="btn btn-primary" type="submit">Filtrer</button>
                            </div>
                        </form>                      
                    </div>
                    <div class="col">
                        <form method="get" action="{{ route('ref.forfait') }}">
                            <div class="input-group">
                                <span class="input-group-text">Opérateur</span>
                                <select name="filter_operateur" class="form-select">
                                    <option value="" {{ !request('filter_operateur') ? 'selected' : '' }}>Tous les opérateurs</option>
                                    @foreach ($operateurs as $operateur)
                                        <option value="{{ $operateur->id_operateur }}" {{ request('filter_operateur') == $operateur->id_operateur ? 'selected' : '' }}>
                                            {{ $operateur->nom_operateur }}
                                        </option>
                                    @endforeach
                                </select>
                                @foreach (['filter_type_forfait'] as $filter)
                                    @if (request($filter))
                                        <input type="hidden" name="{{ $filter }}" value="{{ request($filter) }}">
                                    @endif
                                @endforeach
                                <button class="btn btn-primary" type="submit">Filtrer</button>
                            </div>
                        </form>                          
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-xl-12">
                        <form action="{{ route('ref.forfait') }}" method="get">
                            <!-- Inclure les filtres actifs comme champs cachés -->
                            <input type="hidden" name="filter_type_forfait" value="{{ request('filter_type_forfait') }}">
                            <input type="hidden" name="filter_operateur" value="{{ request('filter_operateur') }}">

                            <div class="btn-group" role="group">
                                @foreach ($forfaits as $index => $forfait)
                                    <button 
                                        class="btn btn-outline-primary 
                                        {{ (request('forfait') == $forfait->id_forfait || (is_null(request('forfait')) && $loop->first)) ? 'active' : '' }}" 
                                        type="submit"
                                        name="forfait"
                                        value="{{ $forfait->id_forfait }}">
                                        {{ $forfait->nom_forfait }}
                                    </button>
                                @endforeach
                            </div>
                        </form>                        
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        @if($forfaitDetails)
                        <div id="dataTable-1" class="table-responsive table mt-2">
                            <table id="dataTable" class="table table-hover table-bordered align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Libellé</th>
                                        <th>Prix</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Prix Unitaire Hors Taxe non remisé</td>
                                        <td>{{ $forfaitDetails->prix_forfait_ht_non_remise }}</td>
                                    </tr>
                                    <tr>
                                        <td>Droit d'accise (+8%)</td>
                                        <td>{{ $forfaitDetails->droit_d_accise }}</td>
                                    </tr>
                                    <tr>
                                        <td>Remise pied de pages (-21.6%)</td>
                                        <td>{{ $forfaitDetails->remise_pied_de_page }}</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Prix Unitaire Hors Taxe Final</th>
                                        <th>{{ $forfaitDetails->prix_forfait_ht }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @endif
                        @if($elements)
                            <div id="dataTable-1" class="table-responsive table mt-2">
                                <table id="dataTable" class="table table-hover table-bordered align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Éléments</th>
                                            <th>Quantité</th>
                                            <th>Unité (Débit)</th>
                                            <th>Prix Unitaire</th>
                                            <th>Prix HT non remisé</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($elements as $element)
                                            <tr>
                                                <td>{{ $element->libelle }}</td>
                                                <td>{{ $element->quantite }}</td>
                                                <td>{{ $element->unite }}</td>
                                                <td>{{ $element->prix_unitaire_element }}</td>
                                                <td>{{ $element->prix_total_element }}</td>
                                                <td class="text-center">
                                                    <a class="text-decoration-none" 
                                                        href="#" 
                                                        data-bs-target="#modifier_element" 
                                                        data-bs-toggle="modal" 
                                                        data-id_element="{{ $element->id_element }}"
                                                        data-id_forfait="{{ $selectedForfaitId }}"
                                                        data-libelle="{{ $element->libelle }}"
                                                        data-unite="{{ $element->unite }}"
                                                        data-prix_unitaire="{{ $element->prix_unitaire_element }}"
                                                        data-quantite="{{ $element->quantite }}"
                                                        style="margin-right: 10px;">
                                                            <i class="fas fa-cogs text-info" style="font-size: 25px;" title="Modifier"></i>
                                                    </a>
                                                    <a class="text-decoration-none" 
                                                        href="#" 
                                                        data-bs-target="#supprimer_element" 
                                                        data-bs-toggle="modal"
                                                        del-data-id_element="{{ $element->id_element }}"
                                                        del-data-id_forfait="{{ $selectedForfaitId }}"
                                                        del-data-libelle="{{ $element->libelle }}"
                                                        del-data-unite="{{ $element->unite }}"
                                                        del-data-prix_unitaire="{{ $element->prix_unitaire_element }}"
                                                        del-data-quantite={{ $element->quantite }}>
                                                        <i class="far fa-trash-alt text-danger" style="font-size: 25px;" title="Supprimer"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('modal_ref')

    @include('modals.forfaitModal')

@endsection

@section('scripts')

    @include('js.forfaitJs')

@endsection