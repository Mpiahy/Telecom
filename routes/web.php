<?php

use App\Exports\ExampleExportEquipement;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\{
    LoginController,
    IndexController,
    UserController,
    ChantierController,
    OperateurController,
    LigneController,
    FibreController,
    PhoneController,
    BoxController,
    ForfaitController,
    ImportController,
    SimulationController,
    AdminController
};
use App\Exports\ExampleExport;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Routes d'authentification
Route::get('/', [LoginController::class, 'loginView'])->name('auth.login');
Route::get('/login', [LoginController::class, 'loginView'])->name('auth.login');
Route::post('/loginCheck', [LoginController::class, 'loginCheck'])->name('auth.loginCheck');
Route::get('/logout', [LoginController::class, 'logout'])->name('auth.logout');

Route::middleware(['check.session'])->group(function () {
    
        // Page d'accueil
        Route::get('/index', [IndexController::class, 'indexView'])->name('index');
      
        // Page des paramètres (modification du mot de passe et informations personnelles)
        Route::get('/settings', [LoginController::class, 'settingsView'])->name('settings');
        Route::post('/update_usr', [LoginController::class, 'updateUser'])->name('update_usr');
        Route::post('/change_pwd', [LoginController::class, 'updatePassword'])->name('change_pwd');
        
        // Route pour l'export PDF
        Route::get('/export/pdf', [IndexController::class, 'exportPDF'])->name('export.pdf');

        // Route pour l'export XLSX
        Route::get('/export/xlsx', [IndexController::class, 'exportXLSX'])->name('export.xlsx');

        // Route pour l'export XLSX
        Route::get('/export/suivi-flotte', [IndexController::class, 'exportSuiviFlotte'])->name('export.suivi.xlsx');

        // Route pour l'export XLSX
        Route::get('/export/equipement', [IndexController::class, 'exportEquipement'])->name('export.equipement.xlsx');

        Route::get('/user', [UserController::class, 'userView'])->name('ref.user');
        Route::get('/ligne/searchFonction', [UserController::class, 'searchFonction'])->name('ligne.searchFonction');
        Route::get('/ligne/searchChantier', [UserController::class, 'searchChantier'])->name('ligne.searchChantier');
        Route::get('/phones-inactifs', [UserController::class, 'showPhonesInactifs']);
        Route::get('/box-inactifs', [UserController::class, 'showBoxInactifs']);
        Route::get('/recherche-inactifs', [UserController::class, 'rechercherInactifs']);
        Route::get('/recherche-ligne-inactifs', [UserController::class, 'rechercherLigneInactifs']);
        Route::get('/user/histoUser/{id_user}', [UserController::class, 'histoUser'])->name('user.histoUser');
        Route::get('/user/equipementsAffectes/{id_user}', [UserController::class, 'equipementsAffectes'])->name('user.equipementsAffectes');
        Route::get('/user/lignesAffectes/{id_user}', [UserController::class, 'lignesAffectes'])->name('user.lignesAffectes');

        Route::get('/chantier', [ChantierController::class, 'chantierView'])->name('ref.chantier');

        Route::get('/operateur', [OperateurController::class, 'operateurView'])->name('ref.operateur');

        Route::get('/ligne', [LigneController::class, 'ligneView'])->name('ref.ligne');
        Route::get('/ligne/searchUser', [LigneController::class, 'searchUser'])->name('ligne.searchUser');
        Route::get('/ligne/detailLigne/{id_ligne}', [LigneController::class, 'detailLigne'])->name('ligne.detailLigne');

        // Route pour récupérer l'historique des affectations d'une ligne
        Route::get('/ligne/historiqueAffectations/{id}', [LigneController::class, 'getHistoriqueAffectations']);

        // Route pour récupérer l'historique des opérations d'une ligne
        Route::get('/ligne/historiqueOperations/{id}', [LigneController::class, 'getHistoriqueOperations']);

        Route::get('/ligne/histoLigne/{id_ligne}', [LigneController::class, 'histoLigne'])->name('ligne.histoLigne');

        Route::get('/fibre', [FibreController::class, 'fibreView'])->name('ref.fibre');

        Route::get('/phone', [PhoneController::class, 'phoneView'])->name('ref.phone');
        Route::get('/phone/histoPhone/{id_phone}', [PhoneController::class, 'histoPhone'])->name('phone.histoPhone');

        Route::get('/get-marques-by-type/{typeId}', [PhoneController::class, 'getMarquesByType']); //for phones
        Route::get('/get-modeles-by-marque/{marqueId}', [PhoneController::class, 'getModelesByMarque']); //for phones & box

        Route::get('/box', [BoxController::class, 'boxView'])->name('ref.box');
        Route::get('/box/histoBox/{id_box}', [BoxController::class, 'histoBox'])->name('box.histoBox');

        Route::get('/forfait', [ForfaitController::class, 'forfaitView'])->name('ref.forfait');

    // Routes pour les administrateurs uniquement
    Route::middleware(['admin'])->group(function () {

        // Page d'administration(accès utilisateurs)
        Route::get('/manage', [AdminController::class, 'manageView'])->name('manage');
        Route::post('/toggle-type/{id}', [AdminController::class, 'toggleType'])->name('toggleType');
        Route::post('/disable-account/{id}', [AdminController::class, 'disableAccount'])->name('disableAccount');
        Route::post('/reset-password/{id}', [AdminController::class, 'resetPassword'])->name('resetPassword');
        Route::post('/create-account', [AdminController::class, 'createAccount'])->name('createAccount');

        Route::get('/check-login/{baseLogin}', function ($baseLogin) {
            $count = User::where('login', 'like', $baseLogin . '%')->count();
            return response()->json(['count' => $count]);
        });

        // Route pour import données
        Route::get('/import', [ImportController::class, 'importView'])->name('import.view');
        Route::post('/import/process', [ImportController::class, 'processImport'])->name('import.process');
        Route::post('/import/equipement', [ImportController::class, 'equipementImport'])->name('import.equipement');

        Route::get('/export-example/{type}', function ($type) {
            $fileName = 'Exemple_Import_Lignes-Utilisateur.' . $type;

            if ($type === 'csv') {
                return Excel::download(new ExampleExport, $fileName, \Maatwebsite\Excel\Excel::CSV);
            } elseif ($type === 'xlsx') {
                return Excel::download(new ExampleExport, $fileName, \Maatwebsite\Excel\Excel::XLSX);
            }

            abort(404); // Retourne une erreur 404 si le type est invalide
        })->name('export.example');

        Route::get('/export-example-equipement/{type}', function ($type) {
            $fileName = 'Exemple_Import_Equipement.' . $type;

            if ($type === 'csv') {
                return Excel::download(new ExampleExportEquipement, $fileName, \Maatwebsite\Excel\Excel::CSV);
            } elseif ($type === 'xlsx') {
                return Excel::download(new ExampleExportEquipement, $fileName, \Maatwebsite\Excel\Excel::XLSX);
            }

            abort(404); // Retourne une erreur 404 si le type est invalide
        })->name('export.example.equipement');

        // Afficher la page de simulation
        Route::get('/simulation', [SimulationController::class, 'simulationView'])->name('simulation.view');

        // Lancer la simulation
        Route::post('/simulation/run', [SimulationController::class, 'runSimulation'])->name('simulation.run');

         // Route pour l'export PDF
        Route::get('/export/pdf', [IndexController::class, 'exportPDF'])->name('export.pdf');

        // Route pour l'export XLSX
        Route::get('/export/xlsx', [IndexController::class, 'exportXLSX'])->name('export.xlsx');

        // Route pour l'export XLSX
        Route::get('/export/suivi-flotte', [IndexController::class, 'exportSuiviFlotte'])->name('export.suivi.xlsx');

        // Route pour l'export XLSX
        Route::get('/export/equipement', [IndexController::class, 'exportEquipement'])->name('export.equipement.xlsx');

        Route::post('/utilisateur/ajouter', [UserController::class, 'ajouterUtilisateur'])->name('ajouter.utilisateur');
        Route::post('/utilisateur/modifier', [UserController::class, 'modifierUtilisateur'])->name('modifier.utilisateur');
        Route::post('/utilisateur/supprimer/{id}', [UserController::class, 'supprimerUtilisateur'])->name('utilisateur.supprimer');
        Route::post('/ligne/attrEquipement', [UserController::class, 'attrEquipement'])->name('ligne.attrEquipement');
        Route::post('/ligne/attrLigne', [UserController::class, 'attrLigne'])->name('ligne.attrLigne');

        Route::post('/addChantier', [ChantierController::class, 'ajouterChantier'])->name('ref.chantier.add');
        Route::post('/chantier/modifier/{id}', [ChantierController::class, 'modifierChantier'])->name('chantier.modifier');
        Route::get('/chantier/supprimer/{id}', [ChantierController::class, 'supprimerChantier'])->name('chantier.supprimer');

        Route::post('/operateur/modifier', [OperateurController::class, 'modifierOperateur'])->name('operateur.modifier');

        Route::post('/ligne/save', [LigneController::class, 'saveLigne'])->name('ligne.save');
        Route::post('/ligne/enr', [LigneController::class, 'enrLigne'])->name('ligne.enr');
        Route::get('/ligne/edt', [LigneController::class, 'edtLigne'])->name('ligne.edt');
        Route::post('/ligne/rsl', [LigneController::class, 'rslLigne'])->name('ligne.rsl');
        Route::post('/ligne/react', [LigneController::class, 'reactLigne'])->name('ligne.react');

        // Route pour récupérer les éléments d'un forfait
        Route::get('/ligne/{id_ligne}/forfait/elements', [LigneController::class, 'getElementsByLigne'])->name('ligne.forfait.elements');
    
        // Route pour enregistrer un rajout de forfait
        Route::post('/ligne/rajoutForfait', [LigneController::class, 'rajoutForfait'])->name('ligne.rajoutForfait');

        Route::post('/phone/save', [PhoneController::class, 'savePhone'])->name('phone.enr');
        Route::get('/phones/{id_phone}', [PhoneController::class, 'updatePhone'])->name('phone.edt');
        Route::post('/phone/hs', [PhoneController::class, 'hsPhone'])->name('phone.hs');
        Route::post('/phone/retour', [PhoneController::class, 'retourPhone'])->name('phone.retour');

        Route::post('/box/save', [BoxController::class, 'saveBox'])->name('box.enr');
        Route::get('/box/{id_box}', [BoxController::class, 'updateBox'])->name('box.edt');
        Route::post('/box/hs', [BoxController::class, 'hsBox'])->name('box.hs');
        Route::post('/box/retour', [BoxController::class, 'retourBox'])->name('box.retour');

        Route::get('/forfaits/update-element/{id_forfait}/{id_element}', [ForfaitController::class, 'updateElement'])->name('forfait.update.element');
        Route::get('/forfaits/delete-element/{id_forfait}/{id_element}', [ForfaitController::class, 'deleteElement'])->name('forfait.delete.element');
    });
});
