{{-- ACTIVATION LIGNE --}}

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Gestion des liens mailto statiques dans le HTML
        const mailtoLinks = document.querySelectorAll('.mailto-link');

        mailtoLinks.forEach(link => {
            // Récupération des attributs personnalisés
            const email = link.dataset.email;
            const numSim = link.dataset.numSim;
            const forfait = link.dataset.forfait;

            // Définition du sujet et du corps du mail avec encodage URI
            const subject = encodeURIComponent("Demande d'activation d'une ligne");
            const body = encodeURIComponent(
                `Bonjour,

                Merci d'activer une ligne sur la SIM : ${numSim}.

                Forfait : ${forfait}

                Merci de bien vouloir traiter cette demande dans les meilleurs délais.

                Cordialement,`
            );

            // Générer et assigner le lien `mailto` dynamique
            link.href = `mailto:${email}?subject=${subject}&body=${body}`;
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Fonction pour gérer l'email d'activation de ligne
         function setupFormHandler(formId, simId, operateurId, typeId, forfaitId, subjectPrefix) {
            const form = document.getElementById(formId);

            if (form) {
                form.addEventListener('submit', function (event) {
                    event.preventDefault(); // Empêche l'envoi normal du formulaire

                    // Récupère les données du formulaire
                    const sim = document.getElementById(simId).value;

                    // Gestion de l'opérateur (support pour input caché ou select)
                    let operateur = '';
                    let email = '';
                    const operateurElement = document.getElementById(operateurId);

                    if (operateurElement.tagName === 'SELECT') {
                        // Si c'est un select (comme dans act_ligne)
                        operateur = operateurElement.options[operateurElement.selectedIndex]?.text || '';
                        email = operateurElement.options[operateurElement.selectedIndex]?.dataset.email || '';
                    } else if (operateurElement.tagName === 'INPUT') {
                        // Si c'est un input readonly avec un champ caché
                        operateur = document.getElementById('react_operateur_name')?.value || ''; // Nom opérateur
                        email = document.getElementById('react_operateur_email')?.value || ''; // Email récupéré dans le champ caché
                    }

                    // Vérifie si un e-mail est défini pour l'opérateur sélectionné
                    if (!email) {
                        alert('Veuillez sélectionner un opérateur avec une adresse e-mail valide.');
                        return;
                    }

                    // Récupération des autres champs
                    const typeSelect = document.getElementById(typeId);
                    const forfaitSelect = document.getElementById(forfaitId);

                    const type = typeSelect.options[typeSelect.selectedIndex]?.text || '';
                    const forfait = forfaitSelect.options[forfaitSelect.selectedIndex]?.text || '';

                    // Préparation du lien mailto
                    const subject = encodeURIComponent("Demande d'activation d'une ligne");
                    const body = encodeURIComponent(
                        `Bonjour,

                        Merci d'activer une ligne sur la SIM : ${sim}.

                        Opérateur : ${operateur}
                        Type : ${type}
                        Forfait : ${forfait}

                        Merci de bien vouloir traiter cette demande dans les meilleurs délais.

                        Cordialement,`
                    );

                    const mailtoLink = `mailto:${email}?subject=${subject}&body=${body}`;

                    // Ouvre le client de messagerie par défaut
                    window.location.href = mailtoLink;

                    // Optionnel : soumettre le formulaire après avoir ouvert le client de messagerie
                    form.submit();
                });
            }
        }

        // Gestion du formulaire `act_ligne`
        setupFormHandler(
            'form_act_ligne',        // ID du formulaire
            'act_sim',               // ID du champ SIM
            'act_operateur',         // ID du select opérateur
            'act_type',              // ID du select type
            'act_forfait',           // ID du select forfait
        );

        // Gestion du formulaire `react_ligne`
        setupFormHandler(
            'form_react_ligne',      // ID du formulaire
            'react_sim',             // ID du champ SIM
            'react_operateur',       // ID du champ caché opérateur
            'react_type',            // ID du select type
            'react_forfait',         // ID du select forfait
        );
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const operateurSelect = document.getElementById('act_operateur');
        const typeSelect = document.getElementById('act_type');
        const forfaitSelect = document.getElementById('act_forfait');
        const simInput = document.getElementById('act_sim');
        const demanderButton = document.getElementById('btn_demander');

        // Fonction pour filtrer les forfaits en fonction de l'opérateur et du type sélectionnés
        function filterForfaits() {
            const selectedOperateur = operateurSelect.value;
            const selectedType = typeSelect.value;

            let hasVisibleForfaits = false;

            // Parcourt les options de forfaits et applique un filtrage conditionnel
            Array.from(forfaitSelect.options).forEach(option => {
                const operateurId = option.getAttribute('data-id-operateur');
                const typeForfaitId = option.getAttribute('data-id-type-forfait');
                const isVisible =
                    (operateurId === selectedOperateur || !selectedOperateur) &&
                    (typeForfaitId === selectedType || !selectedType);

                // Affiche ou masque l'option selon le filtre
                option.style.display = isVisible ? '' : 'none';
                if (isVisible) hasVisibleForfaits = true;
            });

            // Active ou désactive le menu déroulant selon les options disponibles
            forfaitSelect.disabled = !hasVisibleForfaits;
            if (!hasVisibleForfaits) forfaitSelect.value = '';
        }

        // Active ou désactive le bouton "Demander" selon la validité du formulaire
        function toggleDemanderButton() {
            const isFormComplete =
                simInput.value.trim() &&
                operateurSelect.value &&
                typeSelect.value &&
                forfaitSelect.value &&
                !forfaitSelect.disabled;

            demanderButton.disabled = !isFormComplete;
        }

        // Gère les changements d'entrée utilisateur pour filtrer et valider les données
        function handleInputChange() {
            filterForfaits();
            toggleDemanderButton();
        }

        // Ajoute les écouteurs sur les sélecteurs et les champs d'entrée
        [operateurSelect, typeSelect].forEach(el =>
            el.addEventListener('change', handleInputChange)
        );
        forfaitSelect.addEventListener('change', toggleDemanderButton);
        simInput.addEventListener('input', toggleDemanderButton);

        // Initialise l'état des forfaits et du bouton au chargement de la page
        filterForfaits();
        toggleDemanderButton();
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Vérifie si des erreurs sont présentes dans `act_ligne_errors` (backend Laravel)
        @if ($errors->hasBag('act_ligne_errors') && $errors->act_ligne_errors->any())
            const modalActLigne = new bootstrap.Modal(document.getElementById('modal_act_ligne'));
            modalActLigne.show(); // Affiche automatiquement le modal pour corriger les erreurs
        @endif
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Sélectionne tous les boutons pour fermer le modal
        const closeModalButtons = document.querySelectorAll('#close_modal_act');

        closeModalButtons.forEach(button => {
            button.addEventListener('click', function () {
                // Sélectionne le formulaire dans le modal
                const form = document.getElementById('form_act_ligne');

                if (form) {
                    // Réinitialise les champs du formulaire
                    form.reset();

                    // Réinitialise manuellement les sélecteurs si nécessaire
                    const selects = form.querySelectorAll('select');
                    selects.forEach(select => {
                        select.value = ''; // Réinitialise le champ
                        select.dispatchEvent(new Event('change')); // Notifie les autres scripts éventuels
                    });

                    // Supprime les classes CSS d'erreur des champs
                    const invalidFields = form.querySelectorAll('.is-invalid');
                    invalidFields.forEach(field => {
                        field.classList.remove('is-invalid');
                    });

                    // Supprime les messages d'erreur affichés
                    const errorMessages = form.querySelectorAll('.invalid-feedback');
                    errorMessages.forEach(error => {
                        error.textContent = '';
                    });
                }
            });
        });
    });
</script>

{{-- ENREGISTREMENT LIGNE --}}

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Sélectionne tous les boutons ayant l'id "btn_enr_ligne"
        document.querySelectorAll('#btn_enr_ligne').forEach(function (button) {
            button.addEventListener('click', function (event) {
                event.preventDefault(); // Empêche le comportement par défaut du lien

                // Récupérer les données des attributs data-*
                const ligneEnr = button.getAttribute('data-ligne-enr'); 
                const simEnr = button.getAttribute('data-sim-enr'); 
                const idForfaitEnr = button.getAttribute('data-id-forfait-enr');
                const forfaitEnr = button.getAttribute('data-forfait-enr');
                const idEnr = button.getAttribute('data-id-enr');

                // Injecter les valeurs dans le formulaire du modal
                document.getElementById('enr_ligne').value = ligneEnr || ''; 
                document.getElementById('enr_sim').value = simEnr || ''; 
                document.getElementById('enr_id_forfait').value = idForfaitEnr || '';
                document.getElementById('enr_forfait').value = forfaitEnr || '';
                document.getElementById('enr_id_ligne').value = idEnr || '';
            });
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Vérifie si des erreurs sont présentes dans `enr_ligne_errors` (backend Laravel)
        @if ($errors->hasBag('enr_ligne_errors') && $errors->enr_ligne_errors->any())
            const modalEnrLigne = new bootstrap.Modal(document.getElementById('modal_enr_ligne'));
            modalEnrLigne.show(); // Affiche automatiquement le modal pour corriger les erreurs
        @endif
    });
</script>

{{-- searchUser --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('search_enr_user');
        const userSelect = document.getElementById('enr_user');
        const hiddenInput = document.getElementById('selected_user_hidden');
        const spinner = document.getElementById('loadingSpinner');

        let timeout = null;

        searchInput.addEventListener('input', function () {
            clearTimeout(timeout);
            const query = searchInput.value.trim();

            if (query.length >= 2) {
                spinner.style.display = 'block';

                timeout = setTimeout(() => {
                    fetch(`/ligne/searchUser?query=${query}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Erreur lors de la récupération des utilisateurs.');
                            }
                            return response.json();
                        })
                        .then(data => {
                            spinner.style.display = 'none';
                            userSelect.innerHTML = '<option value="0" disabled>Choisir un utilisateur</option>';

                            if (data.length > 0) {
                                data.forEach((user, index) => {
                                    const option = document.createElement('option');
                                    option.value = user.id_utilisateur;
                                    option.textContent = `${user.nom} ${user.prenom} | ${user.login}`;
                                    userSelect.appendChild(option);

                                    if (index === 0) {
                                        option.selected = true;
                                        hiddenInput.value = user.id_utilisateur; // Mettre à jour le champ caché
                                    }
                                });
                            } else {
                                const noResultOption = document.createElement('option');
                                noResultOption.value = "0";
                                noResultOption.textContent = "Aucun utilisateur trouvé";
                                userSelect.appendChild(noResultOption);

                                hiddenInput.value = ""; // Réinitialiser le champ caché
                            }
                        })
                        .catch(error => {
                            spinner.style.display = 'none';
                            console.error('Erreur lors de la recherche des utilisateurs:', error);
                            userSelect.innerHTML = '<option value="0" disabled>Erreur lors du chargement</option>';
                            hiddenInput.value = ""; // Réinitialiser le champ caché
                        });
                }, 300);
            } else {
                spinner.style.display = 'none';
                userSelect.innerHTML = '<option value="0" disabled>Choisir un utilisateur</option>';
                hiddenInput.value = ""; // Réinitialiser le champ caché
            }
        });

        // Synchronise le champ caché avec le champ <select> lorsqu'une option est sélectionnée
        userSelect.addEventListener('change', function () {
            hiddenInput.value = userSelect.value;
        });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Sélectionne tous les boutons pour fermer le modal
        const closeModalButtons = document.querySelectorAll('#close_modal_enr');

        closeModalButtons.forEach(button => {
            button.addEventListener('click', function () {
                // Sélectionne le formulaire dans le modal
                const form = document.getElementById('form_enr_ligne');

                if (form) {
                    // Réinitialise les champs du formulaire
                    form.reset();

                    // Réinitialise manuellement les sélecteurs si nécessaire
                    const selects = form.querySelectorAll('select');
                    selects.forEach(select => {
                        select.value = ''; // Réinitialise le champ
                        select.dispatchEvent(new Event('change')); // Notifie les autres scripts éventuels
                    });

                    // Supprime les classes CSS d'erreur des champs
                    const invalidFields = form.querySelectorAll('.is-invalid');
                    invalidFields.forEach(field => {
                        field.classList.remove('is-invalid');
                    });

                    // Supprime les messages d'erreur affichés
                    const errorMessages = form.querySelectorAll('.invalid-feedback');
                    errorMessages.forEach(error => {
                        error.textContent = '';
                    });
                }
            });
        });
    });
</script>

{{-- VOIR PLUS LIGNE --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Sélectionne tous les boutons pour voir les détails
        const voirLigneBtns = document.querySelectorAll('#btn_voir_ligne');

        // Ajoute un gestionnaire d'événements à chaque bouton
        voirLigneBtns.forEach(btn => {
            btn.addEventListener('click', function (event) {
                event.preventDefault(); // Empêche la redirection normale

                // Récupère l'ID de la ligne depuis l'attribut `data-id-voir`
                const idLigne = this.getAttribute('data-id-voir');

                // Vérifie que l'ID est valide
                if (!idLigne) {
                    alert("ID de ligne non valide !");
                    return;
                }

                // Appelle l'API pour récupérer les données
                fetch(`/ligne/detailLigne/${idLigne}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Erreur lors de la récupération des données.');
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Injecte les données dans le contenu du modal
                        populateModal(data);

                        // Affiche le modal
                        const modal = new bootstrap.Modal(document.getElementById('modal_voir_ligne'));
                        modal.show();
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        alert('Une erreur est survenue lors de la récupération des détails de la ligne.');
                    });
            });
        });

        // Fonction pour injecter les données dans le modal
        function populateModal(data) {
            // Remplace les valeurs dans le tableau du modal
            document.querySelector('#modal_voir_ligne .modal-body [data-field="num_ligne"]').textContent = data.num_ligne;
            document.querySelector('#modal_voir_ligne .modal-body [data-field="num_sim"]').textContent = data.num_sim;
            document.querySelector('#modal_voir_ligne .modal-body [data-field="type_ligne"]').textContent = data.type_ligne;
            document.querySelector('#modal_voir_ligne .modal-body [data-field="nom_forfait"]').textContent = data.nom_forfait;
            document.querySelector('#modal_voir_ligne .modal-body [data-field="prix_forfait_ht"]').textContent = parseFloat(data.prix_forfait_ht).toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + " Ar";
            document.querySelector('#modal_voir_ligne .modal-body [data-field="login"]').textContent = data.login;
            document.querySelector('#modal_voir_ligne .modal-body [data-field="localisation"]').textContent = data.localisation;
            document.querySelector('#modal_voir_ligne .modal-body [data-field="debut_affectation"]').textContent = data.debut_affectation;
            document.querySelector('#modal_voir_ligne .modal-body [data-field="fin_affectation"]').textContent = data.fin_affectation;
        }
    });
</script>


{{-- MODIFICATION LIGNE --}}

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const operateurSelect = document.getElementById('edt_operateur');
        const typeSelect = document.getElementById('edt_type');
        const forfaitSelect = document.getElementById('edt_forfait');

        // Fonction pour filtrer les forfaits en fonction de l'opérateur et du type sélectionnés
        function filterForfaits() {
            const selectedOperateur = operateurSelect.value;
            const selectedType = typeSelect.value;

            // Variable pour suivre si au moins une option reste visible
            let hasVisibleOption = false;

            // Parcourt les options de forfaits et applique un filtrage conditionnel
            Array.from(forfaitSelect.options).forEach(option => {
                const operateurId = option.getAttribute('data-id-operateur-edt');
                const typeForfaitId = option.getAttribute('data-id-type-forfait-edt');
                const isVisible =
                    (operateurId === selectedOperateur || !selectedOperateur) &&
                    (typeForfaitId === selectedType || !selectedType);

                // Affiche ou masque l'option selon le filtre
                option.style.display = isVisible ? '' : 'none';

                // Si l'option est visible, mettre à jour le drapeau
                if (isVisible) {
                    hasVisibleOption = true;
                }
            });

            // Désactiver le sélecteur si aucune option n'est visible
            forfaitSelect.disabled = !hasVisibleOption;
        }

        // Gestion des boutons pour l'ouverture du modal
        document.querySelectorAll('#btn_edt_ligne').forEach(function (button) {
            button.addEventListener('click', function (event) {
                event.preventDefault(); // Empêche le comportement par défaut du lien

                // Récupérer les données des attributs data-*
                const simEdt = button.getAttribute('data-sim-edt'); 
                const ligneEdt = button.getAttribute('data-ligne-edt');
                const operateurEdt = button.getAttribute('data-operateur-edt'); 
                const typeEdt = button.getAttribute('data-type-edt'); 
                const forfaitEdt = button.getAttribute('data-forfait-edt'); 
                const respEdt = button.getAttribute('data-responsable-edt'); 
                const dateEdt = button.getAttribute('data-date-edt');
                const idEdt = button.getAttribute('data-id-edt');
                const statutEdt = button.getAttribute('data-statut-edt');

                // Injecter les valeurs dans le formulaire du modal
                document.getElementById('edt_sim').value = simEdt || ''; 
                document.getElementById('edt_ligne').value = ligneEdt || ''; 
                document.getElementById('edt_operateur').value = operateurEdt || ''; 
                document.getElementById('edt_type').value = typeEdt || ''; 
                document.getElementById('edt_forfait').value = forfaitEdt || '';
                document.getElementById('edt_resp').value = respEdt || '';
                document.getElementById('edt_date').value = dateEdt || '';
                document.getElementById('edt_id_ligne').value = idEdt || '';
                document.getElementById('edt_statut').value = statutEdt || '';

                // Gérer l'affichage des champs en fonction du statut
                const ligneInputGroup = document.getElementById('edt_ligne').closest('.mb-3');
                const respInputGroup = document.getElementById('edt_resp').closest('.mb-3');
                const dateInputGroup = document.getElementById('edt_date').closest('.mb-3');

                if (statutEdt === 'En attente') {
                    // Masquer les champs pour les lignes en attente
                    ligneInputGroup.style.display = 'none';
                    respInputGroup.style.display = 'none';
                    dateInputGroup.style.display = 'none';
                } else {
                    // Afficher les champs pour les autres statuts
                    ligneInputGroup.style.display = '';
                    respInputGroup.style.display = '';
                    dateInputGroup.style.display = '';
                }

                // Filtrer les options de forfaits après l'injection des valeurs
                filterForfaits();
            });
        });

        // Ajoute les écouteurs sur les sélecteurs pour actualiser le filtrage lorsque l'utilisateur change les sélections
        [operateurSelect, typeSelect].forEach(el =>
            el.addEventListener('change', filterForfaits)
        );

        // Filtrer les forfaits au chargement initial (utile si le formulaire est déjà rempli)
        filterForfaits();
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Vérifie si des erreurs sont présentes dans `edt_ligne_errors` (backend Laravel)
        @if ($errors->hasBag('edt_ligne_errors') && $errors->edt_ligne_errors->any())
            const modalEdtLigne = new bootstrap.Modal(document.getElementById('modal_edt_ligne'));
            modalEdtLigne.show(); // Affiche automatiquement le modal pour corriger les erreurs
        @endif
    });
</script>

{{-- RESILIATION Ligne --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('#btn_resil_ligne').forEach(function (button) {
            button.addEventListener('click', (event) => {
                event.preventDefault(); // Empêche le comportement par défaut du lien

                // Récupérer les données data-* de l'élément cliqué
                const simRsl = button.getAttribute('data-sim-resil');
                const ligneRsl = button.getAttribute('data-ligne-resil');
                const operateurRsl = button.getAttribute('data-operateur-resil');
                const emailRsl = button.getAttribute('data-email-resil');
                const typeRsl = button.getAttribute('data-type-resil');
                const forfaitRsl = button.getAttribute('data-forfait-resil');
                const prixRsl = button.getAttribute('data-prix-resil');
                const respRsl = button.getAttribute('data-resp-resil');
                const localisationRsl = button.getAttribute('data-localisation-resil');
                const dateAffectationRsl = button.getAttribute('data-date-resil');
                const idAffRsl = button.getAttribute('data-id-aff-resil');
                const idLigneRsl = button.getAttribute('data-id-resil');

                // Injecter les données dans les champs visibles du formulaire
                document.getElementById('resil_sim').value = simRsl;
                document.getElementById('resil_ligne').value = ligneRsl;
                document.getElementById('resil_operateur').value = operateurRsl;
                document.getElementById('resil_type').value = typeRsl;
                document.getElementById('resil_forfait').value = forfaitRsl;
                document.getElementById('resil_prix').value = parseFloat(prixRsl).toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + " Ar";
                document.getElementById('resil_responsable').value = respRsl;
                document.getElementById('resil_localisation').value = localisationRsl;
                document.getElementById('resil_date_affectation').value = dateAffectationRsl;

                // Injecter les données dans les champs cachés
                document.getElementById('resil_id_aff').value = idAffRsl;
                document.getElementById('resil_id_ligne').value = idLigneRsl;
                document.getElementById('resil_email').value = emailRsl;
            });
        })
    });
</script>


<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Sélectionne tous les boutons pour fermer le modal
        const closeModalButtons = document.querySelectorAll('#close_modal_rsl');

        closeModalButtons.forEach(button => {
            button.addEventListener('click', function () {
                // Sélectionne le formulaire dans le modal
                const form = document.getElementById('form_rsl_ligne');

                if (form) {
                    // Réinitialise les champs du formulaire
                    form.reset();

                    // Réinitialise manuellement les sélecteurs si nécessaire
                    const selects = form.querySelectorAll('select');
                    selects.forEach(select => {
                        select.value = ''; // Réinitialise le champ
                        select.dispatchEvent(new Event('change')); // Notifie les autres scripts éventuels
                    });

                    // Supprime les classes CSS d'erreur des champs
                    const invalidFields = form.querySelectorAll('.is-invalid');
                    invalidFields.forEach(field => {
                        field.classList.remove('is-invalid');
                    });

                    // Supprime les messages d'erreur affichés
                    const errorMessages = form.querySelectorAll('.invalid-feedback');
                    errorMessages.forEach(error => {
                        error.textContent = '';
                    });
                }
            });
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const formRsl = document.getElementById('form_rsl_ligne');

        if (formRsl) {
            formRsl.addEventListener('submit', function (event) {
                event.preventDefault(); // Empêche l'envoi normal du formulaire

                // Récupérer les données du formulaire
                const emailRsl = document.getElementById('resil_email').value;
                const simRsl = document.getElementById('resil_sim').value;
                const ligneRsl = document.getElementById('resil_ligne').value;
                const forfaitRsl = document.getElementById('resil_forfait').value;
                const dateResil = document.getElementById('resil_date').value;

                // Validation : vérifier si l'email est disponible
                if (!emailRsl) {
                    alert('Adresse email du destinataire non disponible.');
                    return;
                }

                // Préparer le sujet et le corps de l'email
                const subject = encodeURIComponent("Demande de résiliation d'une ligne");
                const body = encodeURIComponent(
                    `Bonjour,

                    Veuillez procéder à la résiliation de la ligne sur la SIM : ${simRsl}

                    - Numéro de ligne : ${ligneRsl}
                    - Forfait : ${forfaitRsl}

                    Date de résiliation : ${dateResil}

                    Merci de traiter cette demande dans les meilleurs délais.

                    Cordialement,
                `);

                // Générer le lien mailto
                const mailtoLink = `mailto:${emailRsl}?subject=${subject}&body=${body}`;

                // Ouvrir le client de messagerie par défaut avec le lien généré
                window.location.href = mailtoLink;

                // Optionnel : soumettre le formulaire après avoir ouvert le client de messagerie
                formRsl.submit();
            });
        }
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Vérifie si des erreurs sont présentes dans `rsl_ligne_errors` (backend Laravel)
        @if ($errors->hasBag('rsl_ligne_errors') && $errors->rsl_ligne_errors->any())
            const modalRslLigne = new bootstrap.Modal(document.getElementById('modal_resil_ligne'));
            modalRslLigne.show(); // Affiche automatiquement le modal pour corriger les erreurs
        @endif
    });
</script>

{{-- REACTIVATION --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Vérifie si des erreurs sont présentes dans `react_ligne_errors` (backend Laravel)
        @if ($errors->hasBag('react_ligne_errors') && $errors->react_ligne_errors->any())
            const modalReactLigne = new bootstrap.Modal(document.getElementById('modal_react_ligne'));
            modalReactLigne.show(); // Affiche automatiquement le modal pour corriger les erreurs
        @endif
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const operateurSelect = document.getElementById('react_operateur');
        const typeSelect = document.getElementById('react_type');
        const forfaitSelect = document.getElementById('react_forfait');
        const simInput = document.getElementById('react_sim');
        const ligneIdInput = document.getElementById('react_ligne_id');
        const operateurNameInput = document.getElementById('react_operateur_name'); // Nom affiché
        const operateurEmailInput = document.getElementById('react_operateur_email'); // Email caché

        /**
         * Fonction pour filtrer les options du champ "react_forfait"
         * selon l'opérateur et le type sélectionnés.
         */
        function filterForfaits() {
            const selectedOperateur = operateurSelect.value;
            const selectedType = typeSelect.value;

            let hasVisibleOptions = false; // Vérifie si des options sont visibles
            let firstVisibleOption = null; // Stocke la première option visible

            // Parcourt toutes les options de "react_forfait"
            Array.from(forfaitSelect.options).forEach(option => {
                const optionOperateurId = option.getAttribute('data-id-operateur');
                const optionTypeId = option.getAttribute('data-id-type-forfait');

                // Applique les conditions de visibilité
                const isVisible =
                    (optionOperateurId === selectedOperateur || !selectedOperateur) &&
                    (optionTypeId === selectedType || !selectedType);

                // Affiche ou masque l'option selon les conditions
                option.style.display = isVisible ? '' : 'none';

                if (isVisible) {
                    hasVisibleOptions = true;
                    if (!firstVisibleOption) {
                        firstVisibleOption = option; // Stocke la première option visible
                    }
                }
            });

            // Active ou désactive le champ "react_forfait" selon la disponibilité des options
            forfaitSelect.disabled = !hasVisibleOptions;

            if (hasVisibleOptions) {
                const existingValue = forfaitSelect.value;
                const existingOption = forfaitSelect.querySelector(
                    `option[value="${existingValue}"]`
                );

                // Si une valeur existante est encore valide, on la garde
                if (existingOption && existingOption.style.display !== 'none') {
                    forfaitSelect.value = existingValue;
                } else {
                    // Sinon, on sélectionne la première option visible
                    forfaitSelect.value = firstVisibleOption ? firstVisibleOption.value : '';
                }
            } else {
                // Réinitialise la valeur si aucune option n'est visible
                forfaitSelect.value = '';
            }
        }

        /**
         * Fonction pour gérer les modifications dans les sélecteurs (opérateur, type).
         */
        function handleInputChange() {
            filterForfaits();
        }

        /**
         * Fonction pour remplir les champs du formulaire après un clic sur un bouton "Réactiver".
         * @param {HTMLElement} button - Le bouton "Réactiver" cliqué.
         */
        function fillFormFromButton(button) {
            const simNum = button.getAttribute('data-sim-react') || '';
            const operateurId = button.getAttribute('data-operateur-react') || '';
            const operateurEmail = button.getAttribute('data-operateur-email-react') || '';
            const operateurName = button.getAttribute('data-operateur-name-react') || '';
            const typeId = button.getAttribute('data-type-react') || '';
            const forfaitId = button.getAttribute('data-forfait-react') || '';
            const ligneId = button.getAttribute('data-id-react') || '';

            // Remplit les champs du formulaire avec les données extraites
            if (simInput) simInput.value = simNum;
            if (operateurSelect) operateurSelect.value = operateurId;
            if (operateurEmailInput) operateurEmailInput.value = operateurEmail;
            if (operateurNameInput) operateurNameInput.value = operateurName;
            if (typeSelect) typeSelect.value = typeId;
            if (forfaitSelect) forfaitSelect.value = forfaitId;
            if (ligneIdInput) ligneIdInput.value = ligneId;

            // Refiltre les options de "react_forfait" après mise à jour
            filterForfaits();
        }

        // Ajoute des écouteurs pour réagir aux changements dans les sélecteurs
        [operateurSelect, typeSelect].forEach(el =>
            el.addEventListener('change', handleInputChange)
        );

        // Ajoute des écouteurs pour gérer le clic sur les boutons "Réactiver"
        const reactButtons = document.querySelectorAll('#btn_react_ligne');
        reactButtons.forEach(button => {
            button.addEventListener('click', function () {
                fillFormFromButton(button);
            });
        });

        // Filtre les options de "react_forfait" au premier chargement de la page
        filterForfaits();
    });
</script>

{{-- Historique Affectation Ligne --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Sélectionne tous les boutons pour voir les détails
        const histoLigneBtns = document.querySelectorAll('#btn_histo_ligne');
    
        // Ajoute un gestionnaire d'événements à chaque bouton
        histoLigneBtns.forEach(btn => {
            btn.addEventListener('click', function (event) {
                event.preventDefault(); // Empêche la redirection normale
    
                // Récupère les informations depuis les attributs data-*
                const idLigne = this.getAttribute('data-id-histo');
                const sim = this.getAttribute('data-sim-histo') || '--';
                const operateur = this.getAttribute('data-operateur-histo') || '--';
    
                // Remplit les champs du modal avec les informations générales
                document.querySelector('#modal_histo_ligne .modal-body [data-field="sim"]').textContent = sim;
                document.querySelector('#modal_histo_ligne .modal-body [data-field="operateur"]').textContent = operateur;
    
                // Appelle l'API pour récupérer l'historique d'affectation
                fetch(`/ligne/histoLigne/${idLigne}`)
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
                        const modal = new bootstrap.Modal(document.getElementById('modal_histo_ligne'));
                        modal.show();
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        alert('Une erreur est survenue lors de la récupération des détails de cette ligne.');
                    });
            });
        });
    
        // Fonction pour injecter les données d'historique dans le tableau
        function populateModal(data) {
            const tbody = document.querySelector('#modal_histo_ligne .modal-body #dataTable tbody');
    
            // Vide le tableau pour éviter d'afficher des données redondantes
            tbody.innerHTML = '';
    
            if (data && Array.isArray(data) && data.length > 0) {
                data.forEach(item => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="text-dark">${item.nom || ''} ${item.prenom || ''}</td>
                        <td class="text-dark">${item.login || '--'}</td>
                        <td class="text-dark">${item.localisation || '--'}</td>
                        <td class="text-dark">${item.num_ligne || '--'}</td>
                        <td class="text-dark">${item.type_forfait || '--'}</td>
                        <td class="text-dark">${item.forfait || '--'}</td>
                        <td class="text-dark">${formatPrix(item.prix_forfait_ht)}</td>
                        <td class="text-dark">${item.debut_affectation || '--'}</td>
                        <td class="text-dark">${item.fin_affectation || '--'}</td>
                    `;
                    tbody.appendChild(row);
                });
            } else {
                // Aucun historique trouvé
                const noDataRow = document.createElement('tr');
                noDataRow.innerHTML = `
                    <td class="text-dark text-center" colspan="9">Aucun historique disponible.</td>
                `;
                tbody.appendChild(noDataRow);
            }
        }

        function formatPrix(prix) {
            if (!prix) {
                return '--'; // Retourne '--' si la valeur est absente ou 0
            }
            // Convertir en nombre et formater
            return parseFloat(prix).toLocaleString('fr-FR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
            }) + ' MGA';
        }
    });
</script>