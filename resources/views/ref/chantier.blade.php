@extends('base/baseRef')
<head>
    <title>Localisations - Telecom</title>
</head>
<style>
    /* Couleurs pour le style global */
    td, .input-group-text {
        color: #0a4866 !important; /* Appliquer le style globalement */
    }
</style>    
@section('content_ref')

    <div class="container-fluid">
        <h3 class="text-dark"><i class="far fa-building" style="padding-right: 5px;"></i>Localisations</h3>

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
            <a class="btn btn-primary btn-icon-split" role="button" data-bs-target="#ajouter_chantier" data-bs-toggle="modal">
                <span class="icon">
                    <i class="fas fa-plus-circle" style="padding-top: 5px;"></i>
                </span>
                <span class="text">Ajouter une localisation</span>
            </a>
        </div>
        <div class="card shadow">
            <div class="card-header py-3">
                <p class="m-0 fw-bold" style="color: #0a4866;">Gestion des localisations</p>
            </div>
            <div class="card-body">
                <div class="row mt-2">
                    <div class="col-5">
                        <div class="col">
                            <form action="{{ route('ref.chantier') }}" method="get">
                                <div class="input-group">
                                    <span class="input-group-text">Imputation</span>
                                    <input class="form-control" type="text" placeholder="Rechercher par Imputation" name="search_imputation" value="{{ request('reset_filters') == 'reset' ? '' :  request('search_imputation') }}" />
                                    <input type="hidden" name="service" value="{{ request('service') }}">
                                    <input type="hidden" name="search_chantier" value="{{ request('search_chantier') }}">
                                    <button class="btn btn-primary" type="submit">Rechercher</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-2"></div>
                    <div class="col-5">
                        <form action="{{ route('ref.chantier') }}" method="get">
                            <div class="input-group">
                                <span class="input-group-text">Localisation</span>
                                <input class="form-control" type="text" placeholder="Rechercher par Localisation" name="search_chantier" value="{{ request('reset_filters') == 'reset' ? '' :  request('search_chantier') }}" />
                                <input type="hidden" name="service" value="{{ request('service') }}">
                                <input type="hidden" name="search_imputation" value="{{ request('search_imputation') }}">
                                <button class="btn btn-primary" type="submit">Rechercher</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col">
                        <form id="filterForm" method="get" action="{{ route('ref.chantier') }}">
                            <div class="btn-group" role="group">
                                <!-- Bouton "Tout" -->
                                <button 
                                    class="btn btn-outline-primary {{ is_null(request('service')) ? 'active' : '' }}" 
                                    type="submit" 
                                    name="reset_filters" 
                                    value="reset">
                                    Tout
                                </button>
                        
                                <!-- Boutons dynamiques pour chaque UE -->
                                @foreach ($services as $service)
                                    <button 
                                        class="btn btn-outline-primary {{ request('service') == $service->id_service ? 'active' : '' }}" 
                                        type="submit" 
                                        name="service" 
                                        value="{{ $service->id_service }}">
                                        {{ $service->libelle_service }}
                                    </button>
                                @endforeach
                        
                                <!-- Champs cachés pour conserver les autres filtres -->
                                <input type="hidden" name="search_chantier" value="{{ request('search_chantier') }}">
                                <input type="hidden" name="search_imputation" value="{{ request('search_imputation') }}">
                            </div>
                        </form>                        
                    </div>
                </div>
                <div id="dataTable-1" class="table-responsive table mt-2">
                    <table id="dataTable" class="table table-hover table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Service</th>
                                <th>Imputation</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($localisations as $localisation)
                                <tr>
                                    <td>{{ $localisation->service->libelle_service }}</td> <!-- Service -->
                                    <td>{{ $localisation->imputation->libelle_imputation }}</td> <!-- Code Imputation -->
                                    <td class="text-center">
                                        <a href="#"
                                        data-bs-target="#modifier_chantier" 
                                        data-bs-toggle="modal" 
                                        data-id="{{ $localisation->id_localisation }}" 
                                        data-service="{{ $localisation->service->id_service }}" 
                                        data-imputation="{{ $localisation->imputation->libelle_imputation }}"
                                        class="open-edit-modal" 
                                        style="margin-right: 10px; text-decoration: none">
                                            <i class="far fa-edit text-info" style="font-size: 25px;" data-toggle="tooltip" title="Modifier"></i>
                                        </a>
                                        <a href="#" 
                                        data-bs-target="#supprimer_chantier" 
                                        data-bs-toggle="modal" 
                                        data-id="{{ $localisation->id_localisation }}" 
                                        data-name="{{ $localisation->service->libelle_service }}-{{ $localisation->imputation->libelle_imputation }}"
                                        class="open-delete-modal">
                                            <i class="far fa-trash-alt text-danger" style="font-size: 25px;" data-toggle="tooltip" title="Supprimer"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Aucune localisation trouvée.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Liens de pagination -->
                <div class="mt-4">
                    {{ $localisations->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
                
            </div>
        </div>
    </div>
    
@endsection

@section('modal_ref')

    @include('modals.chantierModal')

@endsection

@section('scripts')

    @include('js.chantierJs')

@endsection