@extends('base/baseRef')
<head>
    <title>Téléphones - Telecom</title>
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
        <i class="fas fa-phone-alt" style="padding-right: 6px;"></i>Téléphones</h3>
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

        <div class="text-center mb-4">
        <a class="btn btn-primary btn-icon-split" role="button" data-bs-target="#modal_enr_phone" data-bs-toggle="modal">
            <span class="icon">
                <i class="fas fa-plus-circle" style="padding-top: 5px;"></i>
            </span>
            <span class="text">Enregistrer un téléphone</span></a></div>
    <div class="card shadow">
        <div class="card-header py-3">
            <p class="m-0 fw-bold" style="color: #0a4866;">Gestion des téléphones</p>
        </div>
        <div class="card-body">
            <div class="row mt-2">
                <div class="col">
                    <form method="get" action="{{ route('ref.phone') }}">
                        <div class="input-group">
                            <span class="input-group-text">Marque</span>
                            <select name="filter_marque" class="form-select">
                                <option value="" {{ !request('filter_marque') ? 'selected' : '' }}>Toutes les marques</option>
                                @foreach ($marques as $marque)
                                    <option value="{{ $marque->marque }}" {{ request('filter_marque') == $marque->marque ? 'selected' : '' }}>
                                        {{ $marque->marque }}
                                    </option>
                                @endforeach
                            </select>
                            @foreach (['filter_statut', 'search_imei', 'search_sn', 'search_user'] as $filter)
                                @if (request($filter))
                                    <input type="hidden" name="{{ $filter }}" value="{{ request($filter) }}">
                                @endif
                            @endforeach
                            <button class="btn btn-primary" type="submit">Filtrer</button>
                        </div>
                    </form>
                </div>
                <div class="col">
                    <form method="get" action="{{ route('ref.phone') }}">
                        <div class="input-group">
                            <span class="input-group-text">Login</span>
                            <input class="form-control" type="text" name="search_user" placeholder="Rechercher par Utilisateur" value="{{ request('search_user') }}">
                            @foreach (['filter_statut', 'filter_marque', 'search_sn', 'search_imei'] as $filter)
                                @if (request($filter))
                                    <input type="hidden" name="{{ $filter }}" value="{{ request($filter) }}">
                                @endif
                            @endforeach
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
                    <form method="get" action="{{ route('ref.phone') }}">
                        <div class="input-group">
                            <span class="input-group-text">IMEI</span>
                            <input class="form-control" type="text" name="search_imei" placeholder="Rechercher par IMEI" value="{{ request('search_imei') }}">
                            @foreach (['filter_statut', 'filter_marque', 'search_sn', 'search_user'] as $filter)
                                @if (request($filter))
                                    <input type="hidden" name="{{ $filter }}" value="{{ request($filter) }}">
                                @endif
                            @endforeach
                            <button class="btn btn-primary" type="submit">Rechercher</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-xl-6">
                    <form method="get" action="{{ route('ref.phone') }}">
                        <div class="btn-group" role="group">
                            <button class="btn btn-outline-primary {{ !request('filter_statut') ? 'active' : '' }}" type="submit" name="reset_filters" value="1">Tout</button>
                            {{-- Boutons dynamiques pour chaque statut --}}
                            @foreach ($statuts as $statut)
                                @php
                                    $colorClass = App\Models\StatutEquipement::getBootstrapClass($statut->statut_equipement);
                                @endphp
                                <button 
                                    class="btn btn-outline-{{ $colorClass }} {{ request('filter_statut') == $statut->statut_equipement ? 'active' : '' }}" 
                                    type="submit" 
                                    name="filter_statut" 
                                    value="{{ $statut->statut_equipement }}">
                                    {{ $statut->statut_equipement }}
                                </button>
                            @endforeach

                            <!-- Champs cachés pour conserver les autres filtres -->
                            <input type="hidden" name="filter_marque" value="{{ request('filter_marque') }}">
                            <input type="hidden" name="search_imei" value="{{ request('search_imei') }}">
                            <input type="hidden" name="search_sn" value="{{ request('search_sn') }}">

                        </div>
                    </form>

                </div>
                <div class="col-xl-6">
                    <form method="get" action="{{ route('ref.phone') }}">
                        <div class="input-group">
                            <span class="input-group-text">SN</span>
                            <input class="form-control" type="text" name="search_sn" placeholder="Rechercher par Numéro de Série" value="{{ request('search_sn') }}">
                            @foreach (['filter_statut', 'filter_marque', 'search_imei'] as $filter)
                                @if (request($filter))
                                    <input type="hidden" name="{{ $filter }}" value="{{ request($filter) }}">
                                @endif
                            @endforeach
                            <button class="btn btn-primary" type="submit">Rechercher</button>
                        </div>
                    </form>
                </div>
            </div>
            <div id="dataTable-1" class="table-responsive table mt-2">
                <table id="dataTable" class="table table-hover table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Marque</th>
                            <th>Modèle</th>
                            <th>Type</th>
                            <th>Imei</th>
                            <th>SN</th>
                            <th>Utilisateur</th>
                            <th>Statut</th>
                            <th>Enrôlé</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($equipements as $equipement)
                            <tr>
                                <td>{{ $equipement->marque }}</td>
                                <td>{{ $equipement->modele }}</td>
                                <td>{{ $equipement->type_equipement }}</td>
                                <td>{{ $equipement->imei }}</td>
                                <td>{{ $equipement->serial_number }}</td>
                                <td>{{ $equipement->login ?? '--' }}</td>
                                <td>{{ $equipement->statut_equipement }}</td>
                                <td>{{ $equipement->enrole ? 'Oui' : 'Non' }}</td>
                                @if ($equipement->statut_equipement === 'HS')
                                    <td class="text-center">
                                        <a class="text-decoration-none"
                                            data-bs-target="#modal_edt_phone"
                                            data-bs-toggle="modal"
                                            title="Modifier"
                                            href="#"
                                            data-id="{{ $equipement->id_equipement }}"
                                            data-type="{{ $equipement->type_equipement }}"
                                            data-marque="{{ $equipement->marque ?? '' }}"
                                            data-modele="{{ $equipement->modele ?? '' }}"
                                            data-imei="{{ $equipement->imei ?? '' }}"
                                            data-sn="{{ $equipement->serial_number ?? '' }}"
                                            data-enroll="{{ $equipement->enrole ? '1' : '2' }}">
                                            <i class="far fa-edit text-info" style="font-size: 25px;"></i>
                                        </a>
                                        <a id="btn_histo_phone"
                                            class="text-decoration-none"
                                            data-bs-target="#modal_histo_phone"
                                            data-bs-toggle="modal"
                                            title="Historique"
                                            href="{{ url('/phone/detailPhone/' . $equipement->id_equipement) }}"
                                            style="margin-right: 10px;"
                                            data-id-histo="{{ $equipement->id_equipement }}">
                                            <i class="fas fa-history text-primary" style="font-size: 25px;"></i>
                                        </a>
                                    </td>
                                @else
                                    <td class="text-center">
                                        <!-- Action buttons -->
                                        <a class="text-decoration-none"
                                            data-bs-target="#modal_edt_phone"
                                            data-bs-toggle="modal"
                                            title="Modifier"
                                            href="#"
                                            style="margin-right: 10px;"
                                            data-id="{{ $equipement->id_equipement }}"
                                            data-type="{{ $equipement->type_equipement }}"
                                            data-marque="{{ $equipement->marque ?? '' }}"
                                            data-modele="{{ $equipement->modele ?? '' }}"
                                            data-imei="{{ $equipement->imei ?? '' }}"
                                            data-sn="{{ $equipement->serial_number ?? '' }}"
                                            data-enroll="{{ $equipement->enrole ? '1' : '2' }}">
                                            <i class="far fa-edit text-info" style="font-size: 25px;"></i>
                                        </a>
                                        <a id="btn_histo_phone"
                                            class="text-decoration-none"
                                            data-bs-target="#modal_histo_phone"
                                            data-bs-toggle="modal"
                                            title="Historique"
                                            href="{{ url('/phone/histoPhone/' . $equipement->id_equipement) }}"
                                            style="margin-right: 10px;"
                                            data-id-histo="{{ $equipement->id_equipement }}"
                                            data-marque-histo="{{ $equipement->marque }}"
                                            data-modele-histo="{{ $equipement->modele }}"
                                            data-serial-number-histo="{{ $equipement->serial_number }}"
                                            data-imei-histo="{{ $equipement->imei }}">
                                            <i class="fas fa-history text-primary" style="font-size: 25px;"></i>
                                        </a>

                                    @if ($equipement->statut_equipement === 'Retourné')
                                        <a class="text-decoration-none open-hs-modal"
                                            data-bs-toggle="tooltip"
                                            title="Déclarer HS"
                                            href="#"
                                            data-phone-id="{{ $equipement->id_equipement }}"
                                            data-phone-name="{{ $equipement->marque }} {{ $equipement->modele }}"
                                            data-phone-imei="{{ $equipement->imei }}"
                                            data-phone-sn="{{ $equipement->serial_number }}">
                                            <i class="far fa-times-circle text-danger" style="font-size: 25px;"></i>
                                        </a>
                                    @endif
                                        
                                    @if ($equipement->statut_equipement === 'Attribué')
                                        <a class="text-decoration-none open-retour-modal"
                                            data-bs-toggle="tooltip"
                                            title="Retourner"
                                            href="#"
                                            data-bs-target="#modal_retour_phone"
                                            data-bs-toggle="modal"
                                            title="Retourner"
                                            data-id-retour="{{ $equipement->id_equipement }}"
                                            data-affectation-retour="{{ $equipement->id_affectation }}"
                                            data-debut-retour="{{ $equipement->debut_affectation }}"
                                            data-type-retour="{{ $equipement->type_equipement }}"
                                            data-name-retour="{{ $equipement->marque }} {{ $equipement->modele }}"
                                            data-imei-retour="{{ $equipement->imei ?? '' }}"
                                            data-sn-retour="{{ $equipement->serial_number ?? '' }}"
                                            data-user-retour="{{ $equipement->login ?? '' }}"
                                            >
                                            <i class="fas fa-undo text-warning" style="font-size: 25px;"></i>
                                        </a>
                                    @endif
                                </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">Aucun téléphone trouvé.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Liens de pagination -->
            <div class="mt-4">
                {{ $equipements->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
            
        </div>
    </div>
</div>

@endsection

@section('modal_ref')

    @include('modals.phoneModal')

@endsection

@section('scripts')

    @include('js.phoneJs')

@endsection
