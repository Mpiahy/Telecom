<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <link rel="icon" href="https://www.colas.com/favicon-32x32.png?v=a3aaafc2f61dca56c11ff88452088fe0" type="image/png">
    <link rel="stylesheet" href="{{asset('/assets/bootstrap/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('/assets/css/Nunito.css')}}">
    <link rel="stylesheet" href="{{asset('/assets/fonts/fontawesome-all.min.css')}}">
    <link rel="stylesheet" href="{{asset('/assets/css/animate.min.css')}}">
</head>

<style>
    /* Styles pour la modal */
#modal_voir_ligne .modal-content {
    border-radius: 8px; /* Coins arrondis pour moderniser */
}

#modal_voir_ligne .modal-header {
    background: #007bff; /* Couleur primaire Bootstrap */
    color: white;
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;
}

#modal_voir_ligne .modal-body {
    background-color: #f8f9fa; /* Arrière-plan clair pour le corps */
    padding: 20px;
}

#modal_voir_ligne table {
    border-collapse: separate;
    border-spacing: 0 10px; /* Espacement entre les lignes */
}

#modal_voir_ligne th {
    font-weight: 600;
    text-align: left;
    background-color: #e9ecef; /* Fond léger pour les en-têtes */
    border: none;
    padding: 10px 15px;
    vertical-align: middle;
}

#modal_voir_ligne td {
    border: none;
    padding: 10px 15px;
    vertical-align: middle;
    color: #495057; /* Couleur du texte des cellules */
    background-color: white; /* Fond blanc des cellules */
    border-left: 4px solid #007bff; /* Petite bordure colorée à gauche pour différencier */
}

/* Responsive design pour les petites résolutions */
@media (max-width: 768px) {
    #modal_voir_ligne table th,
    #modal_voir_ligne table td {
        font-size: 14px;
        padding: 8px 10px;
    }
}

.modal-custom-width {
    max-width: 80%; /* Ou utilisez une autre valeur comme 900px */
}

.modal-custom {
    max-width: 95%; /* Ajustez la largeur à 90% de l'écran, ou toute autre valeur */
}

</style>

<body id="page-top">
    <div id="wrapper">
        <nav class="navbar navbar-dark align-items-start sidebar sidebar-dark accordion bg-gradient-primary p-0" style="color: var(--bs-accordion-active-color);background: rgb(10,72,102);">
            <div class="container-fluid d-flex flex-column p-0">
                <a class="navbar-brand d-flex justify-content-center align-items-center sidebar-brand m-0" href="{{ url('/index') }}">
                    <div>
                        <img src="{{asset('/assets/img/COLAS.png')}}" width="201" height="63">
                    </div>
                </a>
                <hr class="sidebar-divider my-0">
                <ul id="accordionSidebar" class="navbar-nav text-light">
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('index') ? 'active' : '' }}" href="{{ url('/index') }}">
                            <i class="fas fa-tachometer-alt"></i><span>Tableau de Bord</span>
                        </a>
                    </li>
                    {{-- <li class="nav-item">
                        <a class="nav-link" href="profil.html">
                            <i class="fas fa-user"></i><span>Profil</span>
                        </a>
                    </li> --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link" aria-expanded="true" data-bs-toggle="dropdown" href="blank.html">
                            <i class="fas fa-database"></i><span>Référentiels</span>
                        </a>
                        <div class="dropdown-menu show" style="background: #0a4866;border-style: none;border-color: #0a4866;margin-right: 9px;padding-top: 0px;" data-bs-smooth-scroll="true">
                            <a class="nav-link {{ Route::is('ref.user') ? 'active' : '' }}" href="{{ route('ref.user') }}" style="padding-left: 35px;padding-top: 0px;padding-bottom: 15px;">
                                <i class="fas fa-users"></i><span>Utilisateurs</span>
                            </a>
                            <a class="nav-link {{ Route::is('ref.chantier') ? 'active' : '' }}" href="{{ route('ref.chantier') }}" style="padding-left: 35px;padding-top: 0px;padding-bottom: 15px;">
                                <i class="far fa-building" style="font-size: 14px;"></i><span>Localisations</span>
                            </a>
                            <a class="nav-link {{ Route::is('ref.operateur') ? 'active' : '' }}" href="{{ route('ref.operateur') }}" style="padding-left: 35px;padding-top: 0px;padding-bottom: 15px;">
                                <i class="fas fa-globe"></i><span>Opérateurs</span>
                            </a>
                            <a class="nav-link {{ Route::is('ref.ligne') ? 'active' : '' }}" href="{{ route('ref.ligne') }}" style="padding-left: 35px;padding-top: 0px;padding-bottom: 15px;">
                                <i class="fas fa-satellite-dish"></i><span>Lignes</span>
                            </a>
                            {{-- <a class="nav-link {{ Route::is('ref.fibre') ? 'active' : '' }}" href="{{ route('ref.fibre') }}" style="padding-left: 35px;padding-top: 0px;padding-bottom: 15px;">
                                <i class="fas fa-broadcast-tower"></i><span>Fibres Optiques</span>
                            </a> --}}
                            <a class="nav-link {{ Route::is('ref.phone') ? 'active' : '' }}" href="{{ route('ref.phone') }}" style="padding-left: 35px;padding-top: 0px;padding-bottom: 15px;">
                                <i class="fas fa-tablet-alt"></i><span>Téléphones</span>
                            </a>
                            <a class="nav-link {{ Route::is('ref.box') ? 'active' : '' }}" href="{{ route('ref.box') }}" style="padding-left: 35px;padding-top: 0px;padding-bottom: 15px;">
                                <i class="fas fa-wifi"></i><span>Box Internet</span>
                            </a>
                            <a class="nav-link {{ Route::is('ref.forfait') ? 'active' : '' }}" href="{{ route('ref.forfait') }}" style="padding-left: 35px;padding-top: 0px;padding-bottom: 10px;">
                                <i class="fas fa-money-check-alt"></i><span>Offres et forfaits</span>
                            </a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('import.view') ? 'active' : '' }}" href="{{ route('import.view') }}">
                            <i class="fas fa-file-import"></i><span>Imports</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('simulation.view') ? 'active' : '' }}" href="{{ route('simulation.view') }}">
                            <i class="fas fa-gamepad"></i><span>Simulations</span>
                        </a>
                    </li>  
                </ul>
            </div>
        </nav>
        <div class="d-flex flex-column" id="content-wrapper">
            <div id="content">
                <nav class="navbar navbar-light navbar-expand shadow mb-4 topbar static-top" style="background: #0a4866;">
                    <div class="container-fluid">
                        <button class="btn btn-link d-md-none rounded-circle me-3" id="sidebarToggleTop" type="button">
                            <i class="fas fa-bars"></i>
                        </button>
                        <a class="navbar-brand d-flex justify-content-center align-items-center sidebar-brand m-0" href="{{ url('/index') }}">
                            <div>
                                <span style="font-size: 30px;font-weight: bold;font-family: Nunito, sans-serif;color: #fff200;">TELECOM-MADA</span>
                            </div>
                        </a>
                        <ul class="navbar-nav flex-nowrap ms-auto">
                            <div class="d-none d-sm-block topbar-divider"></div>
                            <li class="nav-item dropdown no-arrow">
                                <div class="nav-item dropdown no-arrow">
                                    <a class="dropdown-toggle nav-link" aria-expanded="false" data-bs-toggle="dropdown" data-bss-hover-animate="pulse" href="#">
                                        @if (Auth::check())
                                        <span class="d-none d-lg-inline me-2 text-600 small" style="font-size: 18px;padding-right: 10px;">
                                            {{ $login }}
                                        </span>
                                        @else
                                            <span class="d-none d-lg-inline me-2 text-600 small" style="font-size: 18px;padding-right: 10px;">
                                                Error
                                            </span>
                                        @endif
                                        <img class="border rounded-circle img-profile" src="/assets/img/avatars/User.png" width="32" height="32">
                                    </a>
                                    <div class="dropdown-menu shadow dropdown-menu-end animated--grow-in">
                                        <a class="dropdown-item" data-bss-hover-animate="pulse" href="{{ url('/manage') }}">
                                            <i class="fas fa-users-cog fa-sm fa-fw me-2 text-gray-400"></i> Gestion des comptes
                                        </a>
                                        <a class="dropdown-item" data-bss-hover-animate="pulse" href="{{ url('/settings') }}">
                                            <i class="fas fa-cogs fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Paramètres
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" data-bss-hover-animate="pulse" href="{{ url('/logout') }}">
                                            <i class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Se déconnecter
                                        </a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>

                {{-- <!-- Contenu des pages référentiels --> --}}
                @yield('content_ref')

            <footer class="bg-white sticky-footer">
                <div class="container my-auto">
                    <div class="text-center my-auto copyright"><span>Copyright © TELECOM-MADA 2024</span></div>
                </div>
            </footer>
        </div><a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a>
    </div>

{{-- MODALS --}}
    @yield('modal_ref')
    
{{-- SCRIPTS --}}
    @yield('scripts')

    <script src="{{asset('/assets/bootstrap/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('/assets/js/chart.min.js')}}"></script>
    <script src="{{asset('/assets/js/bs-init.js')}}"></script>
    <script src="{{asset('/assets/js/theme.js')}}"></script>
</body>

</html>