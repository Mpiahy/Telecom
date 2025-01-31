{{-- FILTRE --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Gestionnaire unique pour les boutons de filtre (Délégation d'événement)
        const btnGroup = document.querySelector(".btn-group");
        const rows = document.querySelectorAll(".utilisateur-row");

        if (btnGroup) {
            btnGroup.addEventListener("click", function (event) {
                const button = event.target;
                if (button.classList.contains("btn")) {
                    // Supprimer l'état actif de tous les boutons
                    document.querySelectorAll(".btn-group .btn").forEach(btn => btn.classList.remove("active"));

                    // Ajouter l'état actif au bouton cliqué
                    button.classList.add("active");

                    // Récupérer le texte du bouton (filtre)
                    const filter = button.textContent.toLowerCase();

                    // Filtrer les lignes du tableau
                    rows.forEach(row => {
                        row.style.display =
                            (filter === "tout" || row.classList.contains(filter)) ? "" : "none";
                    });
                }
            });
        }
    });
</script>

{{-- MODIFIER --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Attacher un gestionnaire d'événement au clic sur les liens contenant les attributs data-*
        document.querySelectorAll('a[data-id-edt]').forEach(link => {
            link.addEventListener('click', function (e) {
                // Récupérer les valeurs des attributs data-* du lien cliqué
                const idEdt = this.getAttribute('data-id-edt');
                const matricule = this.getAttribute('data-edt-matricule');
                const nom = this.getAttribute('data-edt-nom');
                const prenom = this.getAttribute('data-edt-prenom');
                const login = this.getAttribute('data-edt-login');
                const type = this.getAttribute('data-edt-type');
                const fonction = this.getAttribute('data-edt-fonction');
                const chantier = this.getAttribute('data-edt-chantier');

                // Remplir les champs du formulaire avec les données récupérées
                document.getElementById('id_edt').value = idEdt;
                document.getElementById('matricule_edt').value = matricule;
                document.getElementById('nom_edt').value = nom;
                document.getElementById('prenom_edt').value = prenom;
                document.getElementById('login_edt').value = login;
                document.getElementById('type-select-edt').value = type;
                document.getElementById('fonction-select-edt').value = fonction;
                document.getElementById('chantier-select').value = chantier;
            });
        });

        // Afficher le modal d'édition s'il y a des erreurs
        @if (session('modal_with_error') === 'modal_edit_emp')
            const editModal = new bootstrap.Modal(document.getElementById("modal_edit_emp"), { backdrop: "static" });
            editModal.show();
        @endif
    });
</script>

{{-- SUPPRIMER --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const deleteButtons = document.querySelectorAll(".open-delete-modal");

        deleteButtons.forEach(button => {
            button.addEventListener("click", function () {
                // Récupération des données utilisateur
                const id = this.getAttribute("data-id");
                const name = this.getAttribute("data-name");
                const matricule = this.getAttribute("data-matricule");
                const login = this.getAttribute("data-login");
                const type = this.getAttribute("data-type");
                const fonction = this.getAttribute("data-fonction");
                const chantier = this.getAttribute("data-chantier");

                // Mise à jour des champs de la modale
                const modal = {
                    id: document.querySelector("#supprimer_utilisateur .modal-body #utilisateur_id"),
                    nom: document.querySelector("#supprimer_utilisateur .modal-body #utilisateur_nom"),
                    matricule: document.querySelector("#supprimer_utilisateur .modal-body #utilisateur_matricule"),
                    login: document.querySelector("#supprimer_utilisateur .modal-body #utilisateur_login"),
                    type: document.querySelector("#supprimer_utilisateur .modal-body #utilisateur_type"),
                    fonction: document.querySelector("#supprimer_utilisateur .modal-body #utilisateur_fonction"),
                    chantier: document.querySelector("#supprimer_utilisateur .modal-body #utilisateur_chantier"),
                    equipements: document.querySelector("#equipements_affectes"),
                };

                // Mise à jour des informations utilisateur dans la modale
                if (modal.id) modal.id.textContent = id;
                if (modal.nom) modal.nom.textContent = name;
                if (modal.matricule) modal.matricule.textContent = matricule;
                if (modal.login) modal.login.textContent = login;
                if (modal.type) modal.type.textContent = type;
                if (modal.fonction) modal.fonction.textContent = fonction;
                if (modal.chantier) modal.chantier.textContent = chantier;

                // Charger les équipements affectés via la nouvelle route
                fetch(`/user/equipementsAffectes/${id}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.equipements.length > 0) {
                            let equipementHTML = "";

                            data.equipements.forEach(equipement => {
                                equipementHTML += `
                                    <div class="form-check">
                                        <input class="form-check-input equipement-checkbox" type="checkbox" value="${equipement.id_equipement}" id="equipement_${equipement.id_equipement}">
                                        <label class="form-check-label" for="equipement_${equipement.id_equipement}">
                                            ${equipement.marque} ${equipement.modele} (${equipement.type_equipement})
                                        </label>
                                    </div>
                                `;
                            });

                            modal.equipements.innerHTML = equipementHTML;

                            // Ajouter un événement pour gérer l'affichage du champ de commentaire
                            const checkboxes = document.querySelectorAll(".equipement-checkbox");
                            const commentaireField = document.querySelector("#commentaire_retour");

                            checkboxes.forEach(checkbox => {
                                checkbox.addEventListener("change", function () {
                                    const isChecked = Array.from(checkboxes).some(cb => cb.checked);
                                    commentaireField.classList.toggle("d-none", !isChecked);
                                });
                            });
                        } else {
                            // Aucun équipement affecté trouvé
                            modal.equipements.innerHTML = '<p class="text-muted">Aucun équipement affecté.</p>';
                        }
                    })
                    .catch(error => {
                        console.error("Erreur lors du chargement des équipements :", error);
                        modal.equipements.innerHTML = '<p class="text-danger">Erreur lors du chargement des équipements.</p>';
                    });

                // Charger les lignes associées à l'utilisateur
                fetch(`/user/lignesAffectes/${id}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            let lignesHTML = "";

                            // Vérifier si des lignes existent dans "data.lignes"
                            if (data.lignes && data.lignes.length > 0) {
                                // Supposons que "data.lignes" soit un tableau contenant les lignes associées
                                data.lignes.forEach(ligne => {
                                    const mailtoLink = generateMailtoLink(
                                        ligne.email,
                                        ligne.num_ligne,
                                        ligne.num_sim,
                                        ligne.nom_forfait,
                                        ligne.debut_affectation,
                                        document.querySelector("#date_depart").value // Date de départ saisie dans le modal
                                    );

                                    // Génération dynamique des éléments HTML pour chaque ligne
                                    lignesHTML += `
                                        <div class="ligne-item d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
                                            <div>
                                                <p class="mb-0"><strong>Numéro de ligne :</strong> ${ligne.num_ligne}</p>
                                                <p class="mb-0"><strong>Forfait :</strong> ${ligne.nom_forfait}</p>
                                                <p class="mb-0"><strong>Date d'activation :</strong> ${ligne.debut_affectation}</p>
                                            </div>
                                            <button type="button" class="btn btn-outline-danger btn-sm ouvrir-mailto" data-mailto="${mailtoLink}">
                                                Résilier
                                            </button>
                                        </div>
                                    `;
                                });

                                // Insérer les lignes générées dans le conteneur
                                document.querySelector("#lignes_associees").innerHTML = lignesHTML;
                            } else {
                                // Si aucune ligne n'est trouvée, afficher le message par défaut
                                document.querySelector("#lignes_associees").innerHTML = '<p class="text-muted">Aucune ligne trouvée.</p>';
                            }

                            // Ajouter des événements pour les boutons "Résilier"
                            const mailtoButtons = document.querySelectorAll(".ouvrir-mailto");
                            mailtoButtons.forEach(button => {
                                button.addEventListener("click", function () {
                                    const mailto = this.getAttribute("data-mailto");
                                    window.location.href = mailto; // Ouvrir le client de messagerie avec le lien mailto
                                });
                            });
                        } else {
                            // Si la réponse "data.success" est fausse, afficher le message d'erreur par défaut
                            document.querySelector("#lignes_associees").innerHTML = '<p class="text-muted">Aucune ligne trouvée.</p>';
                        }

                    })
                    .catch(error => {
                        console.error("Erreur lors du chargement des lignes :", error);
                        document.querySelector("#lignes_associees").innerHTML = '<p class="text-danger">Erreur lors du chargement des lignes.</p>';
                    });

                    function generateMailtoLink(email, numLigne, numSim, forfaitNom, dateResiliation) {
                        const subject = encodeURIComponent("Demande de résiliation d'une ligne");
                        const body = encodeURIComponent(
                            `Bonjour,

                            Veuillez procéder à la résiliation de la ligne suivante :

                            - Numéro de ligne : ${numLigne}
                            - SIM : ${numSim}
                            - Forfait : ${forfaitNom}

                            Merci de traiter cette demande dans les meilleurs délais.

                            Cordialement,`
                        );

                        return `mailto:${email}?subject=${subject}&body=${body}`;
                    }
            });
        });

        // Validation du départ
        const validateButton = document.querySelector("#valider_depart_utilisateur");
        validateButton.addEventListener("click", function () {
            const idUtilisateur = document.querySelector("#utilisateur_id").textContent;
            const dateDepart = document.querySelector("#date_depart").value;
            const commentaire = document.querySelector("#commentaire").value;
            const equipements = Array.from(document.querySelectorAll(".equipement-checkbox:checked")).map(cb => cb.value);

            // Vérification minimale
            if (!dateDepart) {
                alert("Veuillez sélectionner une date de départ.");
                return;
            }

            fetch(`/utilisateur/supprimer/${idUtilisateur}`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                },
                body: JSON.stringify({
                    date_depart: dateDepart,
                    equipements: equipements,
                    commentaire: commentaire,
                }),
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("Départ validé avec succès.");
                        window.location.reload();
                    } else {
                        alert(data.message || "Erreur lors du départ de l'utilisateur.");
                    }
                })
                .catch(error => {
                    console.error("Erreur lors de la validation du départ :", error);
                    alert("Erreur serveur. Veuillez réessayer.");
                });
        });
    });
    
</script>

{{-- AJOUTER --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Afficher le modal d'ajout s'il y a des erreurs
        @if (session('modal_with_error') === 'modal_add_emp')
            const addModal = new bootstrap.Modal(document.getElementById("modal_add_emp"), { backdrop: "static" });
            addModal.show();
        @endif

        // Afficher/masquer le champ "Nouvelle Fonction"
        const toggleBtn = document.getElementById("toggle-fonction-btn");
        const toggleIcon = document.getElementById("toggle-icon");
        const toggleText = document.getElementById("toggle-text");
        const fonctionDropdown = document.getElementById("fonction-dropdown");
        const newFonctionInput = document.getElementById("new-fonction-input");

        if (toggleBtn && toggleIcon && toggleText && fonctionDropdown && newFonctionInput) {
            // Gestion du clic sur le bouton
            toggleBtn.addEventListener("click", function () {
                const isDropdownVisible = fonctionDropdown.style.display !== "none"; // Vérifie si le dropdown est visible

                if (isDropdownVisible) {
                    // Masquer le dropdown et afficher l'input
                    fonctionDropdown.style.display = "none";
                    newFonctionInput.style.display = "block";

                    // Mettre à jour le style et le contenu du bouton
                    toggleBtn.classList.remove("btn-outline-success");
                    toggleBtn.classList.add("btn-outline-secondary");
                    toggleIcon.classList.remove("fa-plus");
                    toggleIcon.classList.add("fa-minus");
                    toggleText.textContent = "Choisir une fonction existante";
                } else {
                    // Afficher le dropdown et masquer l'input
                    fonctionDropdown.style.display = "block";
                    newFonctionInput.style.display = "none";

                    // Revenir au style et contenu par défaut du bouton
                    toggleBtn.classList.remove("btn-outline-secondary");
                    toggleBtn.classList.add("btn-outline-success");
                    toggleIcon.classList.remove("fa-minus");
                    toggleIcon.classList.add("fa-plus");
                    toggleText.textContent = "Ajouter une nouvelle fonction";
                }
            });
        }
    });
</script>

{{-- RESET --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const closeModalAdd = document.getElementById('close-modal-add');
        const form = document.getElementById('add_emp'); // Formulaire à réinitialiser

        closeModalAdd.addEventListener('click', function () {
            // 1. Réinitialiser le formulaire (vide les champs)
            form.reset();

            // 2. Supprimer les classes d'erreur (Bootstrap) et vider les messages d'erreur
            form.querySelectorAll('.is-invalid').forEach(function (element) {
                element.classList.remove('is-invalid'); // Retirer la classe Bootstrap d'erreur
            });

            form.querySelectorAll('.invalid-feedback').forEach(function (element) {
                element.innerHTML = ''; // Vider les messages d'erreur
            });

            // 3. Vider les champs de recherche dynamiques si nécessaire (ex: select2 ou filtres)
            form.querySelectorAll('input[type="text"]').forEach(function (input) {
                input.value = ''; // Réinitialiser les champs texte
            });

            // 4. Réinitialiser les selects à leur option par défaut
            form.querySelectorAll('select').forEach(function (select) {
                select.value = ''; // Réinitialiser les selects
            });
        });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const closeModalEdit = document.getElementById('close-modal-edit');
        const form = document.getElementById('edit_emp'); // Formulaire à réinitialiser

        closeModalEdit.addEventListener('click', function () {
            // 1. Réinitialiser le formulaire (vide les champs)
            form.reset();

            // 2. Supprimer les classes d'erreur (Bootstrap) et vider les messages d'erreur
            form.querySelectorAll('.is-invalid').forEach(function (element) {
                element.classList.remove('is-invalid'); // Retirer la classe Bootstrap d'erreur
            });

            form.querySelectorAll('.invalid-feedback').forEach(function (element) {
                element.innerHTML = ''; // Vider les messages d'erreur
            });

            // 3. Vider les champs de recherche dynamiques si nécessaire (ex: select2 ou filtres)
            form.querySelectorAll('input[type="text"]').forEach(function (input) {
                input.value = ''; // Réinitialiser les champs texte
            });

            // 4. Réinitialiser les selects à leur option par défaut
            form.querySelectorAll('select').forEach(function (select) {
                select.value = ''; // Réinitialiser les selects
            });
        });
    });
</script>

{{-- RECHERCHE DYNAMIQUE DANS FORMS --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Recherche des fonctions
    configureSearchField(
        'search-fonction-edt',       // ID du champ de recherche
        'fonction-select-edt',       // ID du <select> à mettre à jour
        'selected_fonction_edt_hidden', // ID du champ caché
        '/ligne/searchFonction'  // URL pour récupérer les données
    );

    configureSearchField(
        'search-fonction-add',   // Pour le formulaire "AJOUTER"
        'fonction-select-add',
        'selected_fonction_add_hidden',
        '/ligne/searchFonction'
    );

    // Recherche des chantiers
    configureSearchField(
        'search-chantier',
        'chantier-select',
        'selected_chantier_hidden',
        '/ligne/searchChantier'
    );

    configureSearchField(
        'search-chantier-add',
        'chantier-select-add',
        'selected_chantier_add_hidden',
        '/ligne/searchChantier'
    );
});

/**
 * Fonction générique pour gérer un champ de recherche et son <select>.
 */
function configureSearchField(searchInputId, selectId, hiddenInputId, searchUrl) {
    const searchInput = document.getElementById(searchInputId);
    const select = document.getElementById(selectId);
    const hiddenInput = document.getElementById(hiddenInputId);
    const spinner = document.createElement('div');
    spinner.innerHTML = '<small>Recherche en cours...</small>';
    spinner.style.display = 'none';
    searchInput.parentElement.appendChild(spinner);

    let timeout = null;

    searchInput.addEventListener('input', function () {
        clearTimeout(timeout);
        const query = searchInput.value.trim();

        if (query.length >= 2) {
            spinner.style.display = 'block';

            timeout = setTimeout(() => {
                fetch(`${searchUrl}?query=${query}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Erreur lors de la récupération des données.');
                        }
                        return response.json();
                    })
                    .then(data => {
                        spinner.style.display = 'none';
                        select.innerHTML = '<option value="0" disabled>Choisir une option</option>';

                        if (data.length > 0) {
                            data.forEach((item, index) => {
                                const option = document.createElement('option');
                                option.value = item.id;
                                option.textContent = item.label; // "label" représente un nom générique
                                select.appendChild(option);

                                // Sélectionner le premier résultat et mettre à jour le champ caché
                                if (index === 0) {
                                    option.selected = true;
                                    hiddenInput.value = item.id; // Mettre à jour le champ caché
                                }
                            });
                        } else {
                            const noResultOption = document.createElement('option');
                            noResultOption.value = "0";
                            noResultOption.textContent = "Aucun résultat trouvé";
                            select.appendChild(noResultOption);

                            hiddenInput.value = ""; // Réinitialiser le champ caché
                        }
                    })
                    .catch(error => {
                        spinner.style.display = 'none';
                        console.error('Erreur lors de la recherche :', error);
                        select.innerHTML = '<option value="0" disabled>Erreur lors du chargement</option>';
                        hiddenInput.value = ""; // Réinitialiser le champ caché
                    });
            }, 300);
        } else {
            spinner.style.display = 'none';
            select.innerHTML = '<option value="0" disabled>Choisir une option</option>';
            hiddenInput.value = ""; // Réinitialiser le champ caché
        }
    });

    // Synchroniser le champ caché avec le champ <select> lorsqu'une option est sélectionnée
    select.addEventListener('change', function () {
        hiddenInput.value = select.value;
    });
}

</script>

{{-- ATTRIBUER EQUIPEMENT --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Sélection des éléments
        const modal = document.getElementById('modal_attribuer_equipement');
        const typeEquipementSelect = document.getElementById('type_equipement_attr');
        const searchEquipementInput = document.getElementById('search-equipement-attr');
        const equipementSelect = document.getElementById('equipement_attr');
        const btnAttribuer = document.getElementById('btn_attribuer_equipement');
    
        // Fonction pour gérer l'état du bouton "Attribuer"
        function toggleAttribuerButton() {
            btnAttribuer.disabled = !equipementSelect.value; // Activer si un équipement est sélectionné
        }
    
        // Fonction pour charger les équipements dynamiquement via API
        function rechercherEquipements() {
            const type = typeEquipementSelect.value; // 'phones' ou 'box'
            const searchTerm = searchEquipementInput.value; // Terme de recherche
    
            // Désactiver les champs pendant le chargement
            equipementSelect.disabled = true;
            equipementSelect.innerHTML = '<option value="" disabled selected>Chargement...</option>';
            btnAttribuer.disabled = true;
    
            // Vérifier si un type est sélectionné
            if (!type) return;
    
            // Appel AJAX vers l'API
            fetch(`/recherche-inactifs?type=${type}&searchTerm=${searchTerm}`)
                .then(response => response.json())
                .then(data => {
                    equipementSelect.innerHTML = '<option value="" disabled selected>Choisir un équipement</option>';
    
                    if (data.length > 0) {
                        // Ajouter les options au select
                        data.forEach(equipement => {
                            const option = document.createElement('option');
                            option.value = equipement.id_equipement;
                            option.textContent = `${equipement.marque} - ${equipement.modele} (IMEI: ${equipement.imei}, SN: ${equipement.serial_number})`;
                            equipementSelect.appendChild(option);
                        });
    
                        equipementSelect.disabled = false; // Activer le select
                        equipementSelect.selectedIndex = 1; // Sélectionner la première option par défaut
                    } else {
                        equipementSelect.innerHTML = '<option value="" disabled selected>Aucun résultat trouvé</option>';
                        equipementSelect.disabled = true;
                    }
    
                    toggleAttribuerButton(); // Vérifier l'état du bouton
                })
                .catch(error => {
                    console.error('Erreur lors de la recherche:', error);
                    equipementSelect.innerHTML = '<option value="" disabled selected>Erreur de chargement</option>';
                    equipementSelect.disabled = true;
                });
        }
    
        // Réinitialiser les champs du formulaire à l'ouverture du modal
        modal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
    
            // Alimenter les champs avec les data-*
            document.getElementById('id_utilisateur_attr').value = button.getAttribute('data-id-utilisateur-attr');
            document.getElementById('login_attr').value = button.getAttribute('data-login-attr');
            document.getElementById('nom_prenom_attr').value = `${button.getAttribute('data-nom-attr')} ${button.getAttribute('data-prenom-attr')}`;
    
            // Réinitialiser les autres champs
            typeEquipementSelect.value = '';
            searchEquipementInput.value = '';
            equipementSelect.innerHTML = '<option value="" disabled selected>Choisir un équipement</option>';
            equipementSelect.disabled = true;
            btnAttribuer.disabled = true;
        });
    
        // Écouteurs pour les changements
        typeEquipementSelect.addEventListener('change', rechercherEquipements);
        searchEquipementInput.addEventListener('input', rechercherEquipements);
        equipementSelect.addEventListener('change', toggleAttribuerButton);
    });
</script>

{{-- ATTRIBUER LIGNE --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Sélection des éléments
        const modal = document.getElementById('modal_attribuer_ligne');
        const operateurSelect = document.getElementById('id_operateur_attr_ligne');
        const searchLigneInput = document.getElementById('search-ligne-attr');
        const ligneSelect = document.getElementById('ligne_attr_ligne');
        const btnAttribuer = document.getElementById('btn_attribuer_ligne');

        // Fonction pour gérer l'état du bouton "Attribuer"
        function toggleAttribuerButton() {
            btnAttribuer.disabled = !ligneSelect.value; // Activer si une ligne est sélectionnée
        }

        // Fonction pour charger les lignes dynamiquement via API
        function rechercherLignes() {
            const operateurId = operateurSelect.value; // ID de l'opérateur sélectionné
            const searchTerm = searchLigneInput.value; // Terme de recherche

            // Désactiver les champs pendant le chargement
            ligneSelect.disabled = true;
            ligneSelect.innerHTML = '<option value="" disabled selected>Chargement...</option>';
            btnAttribuer.disabled = true;

            // Vérifier si un opérateur est sélectionné
            if (!operateurId) return;

            // Appel AJAX vers l'API
            fetch(`/recherche-ligne-inactifs?operateur=${operateurId}&searchTerm=${searchTerm}`)
                .then(response => response.json())
                .then(data => {
                    ligneSelect.innerHTML = '<option value="" disabled selected>Choisir une ligne</option>';

                    if (data.length > 0) {
                        // Ajouter les options au select
                        data.forEach(ligne => {
                            const option = document.createElement('option');
                            option.value = ligne.id_ligne;
                            option.textContent = `N°: ${ligne.num_ligne} - SIM: ${ligne.num_sim}`;
                            ligneSelect.appendChild(option);
                        });

                        ligneSelect.disabled = false; // Activer le select
                        ligneSelect.selectedIndex = 1; // Sélectionner la première option par défaut
                    } else {
                        ligneSelect.innerHTML = '<option value="" disabled selected>Aucun résultat trouvé</option>';
                        ligneSelect.disabled = true;
                    }

                    toggleAttribuerButton(); // Vérifier l'état du bouton
                })
                .catch(error => {
                    console.error('Erreur lors de la recherche:', error);
                    ligneSelect.innerHTML = '<option value="" disabled selected>Erreur de chargement</option>';
                    ligneSelect.disabled = true;
                });
        }

        // Réinitialiser les champs du formulaire à l'ouverture du modal
        modal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;

            // Alimenter les champs avec les data-*
            document.getElementById('id_utilisateur_attr_ligne').value = button.getAttribute('data-id-utilisateur-attr-ligne');
            document.getElementById('login_attr_ligne').value = button.getAttribute('data-login-attr-ligne');
            document.getElementById('nom_prenom_attr_ligne').value = `${button.getAttribute('data-nom-attr-ligne')} ${button.getAttribute('data-prenom-attr-ligne')}`;

            // Réinitialiser les autres champs
            operateurSelect.value = '';
            searchLigneInput.value = '';
            ligneSelect.innerHTML = '<option value="" disabled selected>Choisir une ligne</option>';
            ligneSelect.disabled = true;
            btnAttribuer.disabled = true;
        });

        // Écouteurs pour les changements
        operateurSelect.addEventListener('change', rechercherLignes);
        searchLigneInput.addEventListener('input', rechercherLignes);
        ligneSelect.addEventListener('change', toggleAttribuerButton);
    });
</script>

{{-- Voir Historique User --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Sélectionne tous les boutons pour voir les détails
        const voirUserBtns = document.querySelectorAll('#btn_histo_user');
    
        // Ajoute un gestionnaire d'événements à chaque bouton
        voirUserBtns.forEach(btn => {
            btn.addEventListener('click', function (event) {
                event.preventDefault(); // Empêche la redirection normale
    
                // Récupère les informations depuis les attributs data-*
                const idUser = this.getAttribute('data-id-histo');
                const user = this.getAttribute('data-user-histo') || '--';
                const login = this.getAttribute('data-login-histo') || '--';
                const fonction = this.getAttribute('data-fonction-histo') || '--';
                const localisation = this.getAttribute('data-localisation-histo') || '--';
    
                // Remplit les champs du modal avec les informations générales
                document.querySelector('#modal_histo_user .modal-body [data-field="utilisateur"]').textContent = user;
                document.querySelector('#modal_histo_user .modal-body [data-field="login"]').textContent = login;
                document.querySelector('#modal_histo_user .modal-body [data-field="fonction"]').textContent = fonction;
                document.querySelector('#modal_histo_user .modal-body [data-field="localisation"]').textContent = localisation;
    
                // Appelle l'API pour récupérer l'historique d'affectation
                fetch(`/user/histoUser/${idUser}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Erreur lors de la récupération des données.');
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Injecte l'historique d'affectation dans le tableau
                        populateModal(data);
    
                        // Affiche le modal
                        const modal = new bootstrap.Modal(document.getElementById('modal_histo_user'));
                        modal.show();
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        alert('Une erreur est survenue lors de la récupération des détails de cet utilisateur.');
                    });
            });
        });
    
        // Fonction pour injecter les données d'historique dans les tableaux
        function populateModal(data) {
            // 1. Sélection des tableaux et du conteneur modal
            const tbodyEquipement = document.querySelector('#dataTableEquipement tbody');
            const tbodyLigne = document.querySelector('#dataTableLigne tbody');
            const modalBody = document.querySelector('#modal_histo_user .modal-body'); // Le conteneur principal du modal

            // 2. Vider les tableaux et retirer tout commentaire précédent
            tbodyEquipement.innerHTML = ''; // Vide le tableau des équipements
            tbodyLigne.innerHTML = ''; // Vide le tableau des lignes

            // Retire l'ancien commentaire s'il existe
            const existingCommentElement = document.querySelector('#userComment');
            if (existingCommentElement) {
                existingCommentElement.remove();
            }

            // 3. Gérer les équipements
            if (data.equipements && Array.isArray(data.equipements) && data.equipements.length > 0) {
                data.equipements.forEach(item => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="text-dark">${item.marque || '--'} ${item.modele || '--'}</td>
                        <td class="text-dark">${item.type_equipement || '--'}</td>
                        <td class="text-dark">${item.imei || '--'}</td>
                        <td class="text-dark">${item.serial_number || '--'}</td>
                        <td class="text-dark">${item.debut_affectation || '--'}</td>
                        <td class="text-dark">${item.fin_affectation || '--'}</td>
                    `;
                    tbodyEquipement.appendChild(row);
                });
            } else {
                const noEquipementRow = document.createElement('tr');
                noEquipementRow.innerHTML = `
                    <td class="text-dark text-center" colspan="6">Aucun historique d'équipement disponible.</td>
                `;
                tbodyEquipement.appendChild(noEquipementRow);
            }

            // 4. Gérer les lignes
            if (data.lignes && Array.isArray(data.lignes) && data.lignes.length > 0) {
                data.lignes.forEach(item => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="text-dark">${item.num_ligne || '--'}</td>
                        <td class="text-dark">${item.num_sim || '--'}</td>
                        <td class="text-dark">${item.nom_forfait || '--'}</td>
                        <td class="text-dark">${item.type_ligne || '--'}</td>
                        <td class="text-dark">${item.debut_affectation || '--'}</td>
                        <td class="text-dark">${item.fin_affectation || '--'}</td>
                    `;
                    tbodyLigne.appendChild(row);
                });
            } else {
                const noLigneRow = document.createElement('tr');
                noLigneRow.innerHTML = `
                    <td class="text-dark text-center" colspan="6">Aucun historique de ligne disponible.</td>
                `;
                tbodyLigne.appendChild(noLigneRow);
            }

            // 5. Ajouter le commentaire à la fin du modal
            if (data.commentaire) {
                const commentaireDiv = document.createElement('div'); // Créer un conteneur pour le commentaire
                commentaireDiv.id = 'userComment';
                commentaireDiv.classList.add('mt-4', 'p-3', 'bg-light', 'border', 'rounded');
                commentaireDiv.innerHTML = `
                    <p class="text-dark fw-bold mb-0">Commentaire :</p>
                    <p class="text-dark fw-normal">${data.commentaire}</p>
                `;

                // Ajout du commentaire après les tableaux
                modalBody.appendChild(commentaireDiv);
            } else {
                console.warn('Aucun commentaire trouvé pour cet utilisateur.');
            }
        }

    });
    
</script>