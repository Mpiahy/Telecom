@extends('base/baseRef')
<head>
    <title>Utilisateurs - Telecom</title>
</head>
<style>
    /* Couleurs pour le style global */
    td, .input-group-text {
        color: #0a4866 !important; /* Appliquer le style globalement */
    }
</style>    
@section('content_ref')

    <div class="container-fluid">
        <h3 class="text-dark" style="color: #0a4866;"><i class="fas fa-users" style="padding-right: 5px;"></i>Utilisateur</h3>
        <div class="text-center mb-4">
            <a class="btn btn-primary btn-icon-split" role="button" data-bs-target="#modal_add_emp" data-bs-toggle="modal">
                <span class="icon"><i class="fas fa-plus-circle" style="padding-top: 5px;"></i></span>
                <span class="text">Ajouter un utilisateur</span></a></div>
        <div class="card shadow">
            <div class="card-header py-3">
                <p class="m-0 fw-bold" style="color: #0a4866;">Gestion des utilisateurs</p>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-xl-7">
                        <form method="get" action="filtre_emp_fonction">
                            <div class="row">
                                <div class="col-xl-3" style="padding-bottom: 0px;"><label class="col-form-label" style="font-size: 14px;" for="filtre_emp_fonction">Filtrer par Fonction:</label></div>
                                <div class="col-xl-6"><select id="filtre_emp_fonction" class="form-select form-select-sm" name="filtre_emp_fonction">
                                        <option value="0" selected>Choisir une fonction</option>
                                        <option value="1">Comptable</option>
                                        <option value="2">Dessinateur</option>
                                        <option value="3">Conducteur</option>
                                    </select></div>
                                <div class="col-xl-3 text-center"><button class="btn btn-outline-primary btn-sm" type="submit">Filtrer</button></div>
                            </div>
                        </form>
                    </div>
                    <div class="col">
                        <form action="search_emp_nom" method="get">
                            <div class="row">
                                <div class="col-xl-8"><input class="form-control form-control-sm" type="text" placeholder="Rechercher un utilisateur" name="search_emp_nom" /></div>
                                <div class="col-xl-4 text-center"><button class="btn btn-outline-primary btn-sm" type="submit">Rechercher</button></div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-7">
                        <form method="get" action="filtre_emp_ue">
                            <div class="row">
                                <div class="col-xl-3" style="padding-bottom: 0px;"><label class="col-form-label" style="font-size: 14px;" for="filtre_emp_ue">Filtrer par Chantier:</label></div>
                                <div class="col-xl-6"><select id="filtre_emp_ue" class="form-select form-select-sm" name="filtre_emp_ue">
                                        <option value="0" selected>Choisir un chantier</option>
                                        <option value="1">Dépôt Anosibe</option>
                                        <option value="2">DTAMA</option>
                                        <option value="3">AMBIL</option>
                                    </select></div>
                                <div class="col-xl-3 text-center"><button class="btn btn-outline-primary btn-sm" type="submit">Filtrer</button></div>
                            </div>
                        </form>
                    </div>
                    <div class="col">
                        <form action="search_emp_matricule" method="get">
                            <div class="row">
                                <div class="col-xl-8"><input class="form-control form-control-sm" type="text" placeholder="Rechercher par matricule" name="search_emp_matricule" /></div>
                                <div class="col-xl-4 text-center"><button class="btn btn-outline-primary btn-sm" type="submit">Rechercher</button></div>
                            </div>
                        </form>
                    </div>
                </div>
                <div id="dataTable-1" class="table-responsive table mt-2" role="grid" aria-describedby="dataTable_info">
                    <table id="dataTable" class="table table-hover my-0">
                        <thead>
                            <tr>
                                <th class="text-center">Matricule</th>
                                <th>Nom et Prénom(s)</th>
                                <th>Login</th>
                                <th>Type</th>
                                <th>Fonction</th>
                                <th>Localisation</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">78976431</td>
                                <td>Airina Satou</td>
                                <td>AIRINS40</td>
                                <td>Collaborateur</td>
                                <td>Comptable</td>
                                <td>Dépôt Anosibe</td>
                                <td class="text-center"><a href="voir_emp" style="margin-right: 10px;" data-bs-target="#modal_voir_emp" data-bs-toggle="modal" data-toggle="tooltip" title="Voir"><i class="fas fa-history text-primary" style="font-size: 25px;"></i></a><a href="edit_emp" data-toggle="tooltip" title="Modifier" data-bs-target="#modal_edit_emp" data-bs-toggle="modal"><i class="far fa-edit text-warning" style="font-size: 25px;"></i></a></td>
                            </tr>
                            <tr>
                                <td class="text-center">65483145</td>
                                <td>Angelica Ramos</td>
                                <td>ANGELR12</td>
                                <td>Collaborateur</td>
                                <td>Dessinateur<br /></td>
                                <td>Dépôt Anosibe</td>
                                <td class="text-center"><a href="voir_emp" style="margin-right: 10px;" data-bs-target="#modal_voir_emp" data-bs-toggle="modal" data-toggle="tooltip" title="Voir"><i class="fas fa-history text-primary" style="font-size: 25px;"></i></a><a href="edit_emp" data-toggle="tooltip" title="Modifier"><i class="far fa-edit text-warning" style="font-size: 25px;"></i></a></td>
                            </tr>
                            <tr>
                                <td class="text-center">65421483</td>
                                <td>Ashton Cox</td>
                                <td>ASHTOC5</td>
                                <td>Prestataire</td>
                                <td>Conducteur<br /></td>
                                <td>PROJET MASAY IMMEUBLE R+6<br /></td>
                                <td class="text-center"><a href="voir_emp" style="margin-right: 10px;" data-bs-target="#modal_voir_emp" data-bs-toggle="modal" data-toggle="tooltip" title="Voir"><i class="fas fa-history text-primary" style="font-size: 25px;"></i></a><a href="edit_emp" data-toggle="tooltip" title="Modifier"><i class="far fa-edit text-warning" style="font-size: 25px;"></i></a></td>
                            </tr>
                            <tr>
                                <td class="text-center">65483221</td>
                                <td>Randriamanivo Mpiahisoa</td>
                                <td>RANDRM12</td>
                                <td>Stagiaire</td>
                                <td>Informaticien<br /></td>
                                <td>DTAMA</td>
                                <td class="text-center"><a href="voir_emp" style="margin-right: 10px;" data-bs-target="#modal_voir_emp" data-bs-toggle="modal" data-toggle="tooltip" title="Voir"><i class="fas fa-history text-primary" style="font-size: 25px;"></i></a><a href="edit_emp" data-toggle="tooltip" title="Modifier"><i class="far fa-edit text-warning" style="font-size: 25px;"></i></a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('modal_ref')

<div id="modal_voir_emp" class="modal fade" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-primary">Historique des affectations pour cet utilisateur</h4><button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-dark">Nom et prénom(s): <strong>Randriamanivo Mpiahisoa</strong></p>
                <div id="dataTable-2" class="table-responsive table mt-2" role="grid" aria-describedby="dataTable_info">
                    <table id="dataTable" class="table table-hover my-0">
                        <thead>
                            <tr>
                                <th class="text-center">Equipement</th>
                                <th>Type</th>
                                <th class="text-center">Ligne</th>
                                <th>Etat</th>
                                <th>Chantier</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">Marque + Modèle</td>
                                <td>Smartphone</td>
                                <td class="text-center">+261 34 49 599 53</td>
                                <td>Actif</td>
                                <td>SGMAD</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer"><button class="btn btn-warning" type="button" data-bs-dismiss="modal">Fermer</button></div>
        </div>
    </div>
</div>

<div id="modal_edit_emp" class="modal fade" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-primary">Modifier cet utilisateur</h4><button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <div></div>
                    </div>
                    <div class="col-xl-6">
                        <div class="card shadow">
                            <div class="card-body">
                                <form id="edit_emp" action="edit_emp" method="get" style="color: #a0c8d8;">
                                    <div class="mb-3"><label class="form-label" for="edt_emp_nom"><strong>Nom</strong></label><input id="edt_emp_nom" class="form-control" type="text" name="edt_emp_nom" value="Randriamanivo" placeholder="Nom de l&#39;utilisateur" /></div>
                                    <div class="mb-3"><label class="form-label" for="edt_emp_prenom"><strong>Prénom(s)</strong></label><input id="edt_emp_prenom" class="form-control" type="text" name="edt_sim" value="Andriamahaleo Mpiahisoa" placeholder="Prénom(s) du collaborateur" /></div>
                                    <div class="mb-3"><label class="form-label" for="edt_emp_login"><strong>Login</strong></label><input id="edt_emp_login" class="form-control" type="text" name="edt_emp_login" value="RANDRIA3" placeholder="Login de l&#39;utilisateur" /></div>
                                    <div class="mb-3"><label class="form-label" for="edt_emp_matricule"><strong>Matricule</strong></label><input id="edt_emp_matricule" class="form-control" type="text" name="edt_emp_matricule" value="412576" placeholder="Matricule de l&#39;utilisateur" /></div>
                                    <div class="mb-3"><label class="form-label" for="edt_emp_type"><strong>Type</strong></label><select id="edt_emp_type" class="form-select" name="edt_emp_type">
                                            <option value="0">Type</option>
                                            <option value="1" selected>Collaborateur</option>
                                            <option value="2">Stagiaire</option>
                                            <option value="3">Prestataire</option>
                                        </select></div>
                                    <div class="mb-3"><label class="form-label" for="edt_emp_fonction"><strong>Fonction</strong></label><select id="edt_emp_fonction" class="form-select" name="edt_emp_fonction">
                                            <option value="0">Fonction</option>
                                            <option value="1" selected>Informaticien</option>
                                            <option value="2">Dessinateur</option>
                                            <option value="3">Comptable</option>
                                        </select></div>
                                    <div class="mb-3"><label class="form-label" for="edt_emp_ue"><strong>Chantier</strong><br /></label><select id="edt_emp_ue" class="form-select" name="edt_emp_ue">
                                            <option value="0">Chantier</option>
                                            <option value="1" selected>Dépôt Anosibe</option>
                                            <option value="2">DTAMA</option>
                                            <option value="3">AMBIL</option>
                                        </select></div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer"><button class="btn btn-warning" type="button" data-bs-dismiss="modal">Fermer</button><button class="btn btn-primary" type="submit" form="edit_emp">Modifier</button></div>
        </div>
    </div>
</div>

<div id="modal_add_emp" class="modal fade" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-primary">Ajouter un nouvel utilisateur</h4><button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <div></div>
                    </div>
                    <div class="col-xl-6">
                        <div class="card shadow">
                            <div class="card-body">
                                <form id="add_emp" action="add_emp" method="get" style="color: #a0c8d8;">
                                    <div class="mb-3"><label class="form-label" for="add_emp_nom"><strong>Nom</strong></label><input id="add_emp_nom" class="form-control" type="text" name="add_emp_nom" placeholder="Nom de l&#39;utilisateur" /></div>
                                    <div class="mb-3"><label class="form-label" for="add_emp_prenom"><strong>Prénom(s)</strong></label><input id="add_emp_prenom" class="form-control" type="text" name="add_emp_prenom" placeholder="Prénom(s) de l&#39;utilisateur" /></div>
                                    <div class="mb-3"><label class="form-label" for="add_emp_login"><strong>Login</strong></label><input id="add_emp_login" class="form-control" type="text" name="edt_emp_login" placeholder="Login de l&#39;utilisateur" /></div>
                                    <div class="mb-3"><label class="form-label" for="add_emp_matricule"><strong>Matricule</strong></label><input id="add_emp_matricule" class="form-control" type="text" name="add_emp_matricule" placeholder="Matricule de l&#39;utilisateur" /></div>
                                    <div class="mb-3"><label class="form-label" for="add_emp_type"><strong>Type</strong></label><select id="add_emp_type" class="form-select" name="add_emp_type">
                                            <option value="0" selected>Type</option>
                                            <option value="1">Collaborateur</option>
                                            <option value="2">Stagiaire</option>
                                            <option value="3">Prestataire</option>
                                        </select></div>
                                    <div class="mb-3"><label class="form-label" for="add_emp_fonction"><strong>Fonction</strong></label><select id="add_emp_fonction" class="form-select" name="add_emp_fonction">
                                            <option value="0" selected>Fonction du collaborateur</option>
                                            <option value="1">Informaticien</option>
                                            <option value="2">Dessinateur</option>
                                            <option value="3">Comptable</option>
                                        </select></div>
                                    <div class="mb-3"><label class="form-label" for="add_emp_chantier"><strong>Chantier</strong><br /></label><select id="edt_emp_ue-1" class="form-select" name="add_emp_chantier">
                                            <option value="0" selected>Chantier</option>
                                            <option value="1">Dépôt Anosibe</option>
                                            <option value="2">DTAMA</option>
                                            <option value="3">AMBIL</option>
                                        </select></div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer"><button class="btn btn-warning" type="button" data-bs-dismiss="modal">Fermer</button><button class="btn btn-info" type="submit" form="add_emp">Ajouter</button></div>
        </div>
    </div>
</div>

@endsection