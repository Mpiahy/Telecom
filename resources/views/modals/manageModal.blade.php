
<!-- Modal Ajout Compte -->
<div class="modal fade" id="modal_add_account" tabindex="-1" aria-labelledby="modal_add_account_label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modal_add_account_label">Créer un compte</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addAccountForm">
                    @csrf
                    <div class="mb-3">
                        <label for="nom_usr" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="nom_usr" name="nom_usr" required>
                    </div>
                    <div class="mb-3">
                        <label for="prenom_usr" class="form-label">Prénom</label>
                        <input type="text" class="form-control" id="prenom_usr" name="prenom_usr" required>
                    </div>
                    <div class="mb-3">
                        <label for="login" class="form-label">Login</label>
                        <input type="text" class="form-control" id="login" name="login" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="isAdmin" class="form-label">Type de compte</label>
                        <select class="form-select" id="isAdmin" name="isAdmin">
                            <option value="0">Invité</option>
                            <option value="1">Admin</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="temp_password" class="form-label">Mot de passe temporaire</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="temp_password" name="temp_password" readonly>
                            <button class="btn btn-secondary" type="button" onclick="generateTempPassword()">Générer</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" onclick="addAccount()">Créer le compte</button>
            </div>
        </div>
    </div>
</div>