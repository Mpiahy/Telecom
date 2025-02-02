<script>
    function generateTempPassword() {
        let password = Math.random().toString(36).slice(-8) + "!@";
        document.getElementById("temp_password").value = password;
    }

    document.getElementById("nom_usr").addEventListener("input", generateLoginAndEmail);
    document.getElementById("prenom_usr").addEventListener("input", generateLoginAndEmail);
    document.getElementById("login").addEventListener("input", updateEmail);

    function generateLoginAndEmail() {
        let nom = document.getElementById("nom_usr").value.trim().toUpperCase();
        let prenom = document.getElementById("prenom_usr").value.trim().toUpperCase();

        if (nom.length >= 6 && prenom.length >= 1) {
            let baseLogin = nom.substring(0, 6) + prenom[0];

            // Vérifier le nombre d'utilisateurs existants ayant le même login de base
            fetch(`/check-login/${baseLogin}`)
                .then(response => response.json())
                .then(data => {
                    let uniqueLogin = baseLogin + (data.count + 1);
                    document.getElementById("login").value = uniqueLogin;
                    updateEmail(); // Met à jour l'email en fonction du login
                })
                .catch(error => console.error("Erreur:", error));
        } else {
            document.getElementById("login").value = "";
            document.getElementById("email").value = "";
        }
    }

    // Met à jour l'email en fonction du login saisi
    function updateEmail() {
        let login = document.getElementById("login").value.trim().toLowerCase();
        document.getElementById("email").value = login ? login + "@colas.com" : "";
    }

    // Générer un mot de passe dès l'ouverture du modal
    document.getElementById("modal_add_account").addEventListener("shown.bs.modal", generateTempPassword);

    function addAccount() {
        let formData = new FormData(document.getElementById("addAccountForm"));

        fetch("/create-account", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                showToast(data.message, "success");

                // Fermer le modal
                let modal = bootstrap.Modal.getInstance(document.getElementById("modal_add_account"));
                modal.hide();

                // Ajouter dynamiquement la nouvelle ligne à la table
                addNewRow(data.user);

                // Réinitialiser le formulaire après ajout
                document.getElementById("addAccountForm").reset();
            }
        })
        .catch(error => {
            showToast("Erreur lors de la création du compte.", "error");
            console.error("Erreur:", error);
        });
    }

    function addNewRow(user) {
        let tableBody = document.querySelector(".table tbody");
        let newRow = `
            <tr id="row-${user.id}">
                <td>${user.nom_usr} ${user.prenom_usr}</td>
                <td>${user.login}</td>
                <td>${user.email}</td>
                <td id="type-${user.id}" class="text-primary fw-bold" style="cursor: pointer;" onclick="toggleType(${user.id})">
                    <i id="icon-${user.id}" class="fas fa-exchange-alt"></i>
                    <span>${user.isAdmin ? 'Admin' : 'Invité'}</span>
                </td>
                <td class="text-center">
                    <button class="btn btn-sm btn-outline-danger btn-action" onclick="disableAccount(${user.id})">
                        <i class="fas fa-ban"></i> Désactiver
                    </button>
                    <button class="btn btn-sm btn-outline-info btn-action" onclick="resetPassword(${user.id})">
                        <i class="fas fa-key"></i> Réinitialiser
                    </button>
                    <br>
                    <small id="pwd-${user.id}" class="text-success fw-bold">${user.temp_password}</small>
                </td>
            </tr>
        `;
        tableBody.insertAdjacentHTML("afterbegin", newRow);
    }

    function showToast(message, type = 'success') {
        let toast = document.getElementById('liveToast');
        let toastTitle = document.getElementById('toast-title');
        let toastMessage = document.getElementById('toast-message');
        let toastIcon = document.getElementById('toast-icon');

        // Définir les classes CSS selon le type
        let toastClass, iconClass, titleText;
        switch (type) {
            case 'success':
                toastClass = 'bg-success text-white';
                iconClass = 'fa-check-circle';
                titleText = 'Succès';
                break;
            case 'error':
                toastClass = 'bg-danger text-white';
                iconClass = 'fa-times-circle';
                titleText = 'Erreur';
                break;
            case 'warning':
                toastClass = 'bg-warning text-dark';
                iconClass = 'fa-exclamation-triangle';
                titleText = 'Attention';
                break;
            case 'info':
                toastClass = 'bg-info text-white';
                iconClass = 'fa-info-circle';
                titleText = 'Information';
                break;
            default:
                toastClass = 'bg-secondary text-white';
                iconClass = 'fa-bell';
                titleText = 'Notification';
        }

        // Appliquer les changements
        toast.className = `toast ${toastClass} show`;
        toastTitle.innerText = titleText;
        toastMessage.innerText = message;
        toastIcon.className = `fas ${iconClass} me-2`;

        // Afficher le toast
        let toastInstance = new bootstrap.Toast(toast);
        toastInstance.show();

        // Masquer le toast après 4 secondes
        setTimeout(() => {
            toastInstance.hide();
        }, 4000);
    }

    function toggleType(id) {
    fetch(`/toggle-type/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({})
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                showToast(data.error,'error');
                return;
            }
            let typeElement = document.getElementById('type-' + id);

            // Met à jour le texte et l'icône
            typeElement.innerHTML = `<i id="icon-${id}" class="fas fa-exchange-alt"></i> <span>${data.newType}</span>`;
            
            showToast('Le type a été modifié avec succès.', 'info');
        })
        .catch(error => console.error('Erreur:', error));
    }

    function disableAccount(id) {
        if (confirm('Voulez-vous vraiment désactiver ce compte ?')) {
            fetch(`/disable-account/${id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(() => {
                document.getElementById('row-' + id).remove();
                showToast('Compte désactivé avec succès.','info');
            })
            .catch(error => console.error('Erreur:', error));
        }
    }

    function resetPassword(id) {
        fetch(`/reset-password/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({})
        })
        .then(response => response.json())
        .then(data => {
            if (data.newPassword) {
                // Mettre à jour le champ de mot de passe temporaire en temps réel
                let pwdElement = document.getElementById('pwd-' + data.userId);
                pwdElement.innerText = "Mot de passe : " + data.newPassword;

                showToast('Mot de passe réinitialisé avec succès.', 'success');
            } else {
                showToast('Erreur : ' + data.error, 'error');
            }
        })
        .catch(error => console.error('Erreur:', error));
    }

</script>