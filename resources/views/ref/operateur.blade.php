@extends('base/baseRef')

<head>
    <title>Opérateur - Telecom</title>
</head>
<style>
    /* Couleurs pour le style global */
    td, .input-group-text {
        color: #0a4866 !important; /* Appliquer le style globalement */
    }
</style>    
@section('content_ref')
<div class="container-fluid">
    <h3 class="text-dark mb-5"><i class="fas fa-globe" style="padding-right: 5px;"></i>Opérateurs</h3>
    
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

    <div class="card shadow">
        <div class="card-header py-3">
            <p class="m-0 fw-bold" style="color: #0a4866;">Gestion des opérateurs</p>
        </div>
        <div class="card-body">
            <div id="dataTable-1" class="table-responsive table mt-2">
                <table id="dataTable" class="table table-hover table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Opérateur</th>
                            <th>Contact</th>
                            <th>E-mail</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($contactsOperateurs as $contact)
                            <tr>
                                <td>
                                    <a class="text-decoration-none" href="http://www.{{ strtolower($contact->operateur->nom_operateur) }}.mg">
                                        {{ $contact->operateur->nom_operateur }}
                                    </a>
                                </td>
                                <td>{{ $contact->nom }}</td>
                                <td>
                                    <a class="link-primary" href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>
                                </td>
                                <td class="text-center">
                                    <a href="#" 
                                       data-bs-toggle="modal" 
                                       data-bs-target="#modifier_contact_operateur" 
                                       class="edit-contact"
                                       data-id="{{ $contact->id_contact }}" 
                                       data-nom="{{ $contact->nom }}" 
                                       data-email="{{ $contact->email }}" 
                                       data-operateur="{{ $contact->operateur->nom_operateur }}">
                                        <i class="far fa-edit text-warning" style="font-size: 25px;"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>                    
            </div>
        </div>
    </div>
</div>
@endsection

@section('modal_ref')

    @include('modals.operateurModal')

@endsection

@section('scripts')

    @include('js.operateurJs')

@endsection