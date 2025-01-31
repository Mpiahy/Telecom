{{-- ENR --}}

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialisation des éléments du formulaire
        const enrPhoneType = document.getElementById('enr_phone_type');
        const enrPhoneMarque = document.getElementById('enr_phone_marque');
        const newMarqueInput = document.getElementById('new_phone_marque');
        const enrPhoneModele = document.getElementById('enr_phone_modele');
        const newModeleInput = document.getElementById('new_phone_modele');
        const enrPhoneEnroll = document.getElementById('enr_phone_enroll');
        const enrPhoneEnrollDiv = enrPhoneEnroll.closest('.mb-3'); // Conteneur du champ "Enrôlé"

        // Affiche ou masque le champ "Nouvelle Marque"
        function toggleNewMarqueInput() {
            if (enrPhoneMarque.value === 'new_marque') {
                newMarqueInput.classList.remove('d-none');
                populateNewModeleOption(); // Ajoute automatiquement "Ajouter un nouveau modèle"
            } else {
                newMarqueInput.classList.add('d-none');
                newMarqueInput.value = ''; // Réinitialise le champ
            }
        }

        // Affiche ou masque le champ "Nouveau Modèle"
        function toggleNewModeleInput() {
            if (enrPhoneModele.value === 'new') {
                newModeleInput.classList.remove('d-none');
            } else {
                newModeleInput.classList.add('d-none');
                newModeleInput.value = ''; // Réinitialise le champ
            }
        }

        // Affiche ou masque le champ "Enrôlé" en fonction du type d'équipement
        function togglePhoneEnroll() {
            if (enrPhoneType.value === '2') { // Téléphone à touche
                enrPhoneEnrollDiv.classList.add('d-none');
                enrPhoneEnroll.value = '2'; // Définit la valeur par défaut à "Non"
            } else {
                enrPhoneEnrollDiv.classList.remove('d-none');
                enrPhoneEnroll.value = '0'; // Réinitialise la valeur par défaut
            }
        }

        // Réinitialise un champ <select> avec une option par défaut
        function resetSelect(selectElement, defaultOptionText) {
            selectElement.innerHTML = `<option value="0" disabled selected>${defaultOptionText}</option>`;
        }

        // Remplit un champ <select> avec des options dynamiques, en conservant l'option "new" ou "new_marque"
        function populateSelect(selectElement, items, newItemValue, newItemText) {
            if (newItemValue && newItemText) {
                selectElement.insertAdjacentHTML('beforeend', `<option value="${newItemValue}">${newItemText}</option>`);
            }
            items.forEach(item => {
                selectElement.insertAdjacentHTML('beforeend', `<option value="${item.id}">${item.name}</option>`);
            });
        }

        // Ajoute l'option "Ajouter un nouveau modèle" et la sélectionne automatiquement
        function populateNewModeleOption() {
            resetSelect(enrPhoneModele, 'Choisir le modèle');
            enrPhoneModele.insertAdjacentHTML('beforeend', `<option value="new">Ajouter un nouveau modèle</option>`);
            enrPhoneModele.value = 'new';
            toggleNewModeleInput(); // Affiche le champ "Nouveau Modèle"
        }

        // Gère les changements de type d'équipement
        enrPhoneType.addEventListener('change', function () {
            const typeId = this.value;
            resetSelect(enrPhoneMarque, 'Choisir la marque');
            resetSelect(enrPhoneModele, 'Choisir le modèle');
            togglePhoneEnroll();

            if (typeId) {
                fetch(`/get-marques-by-type/${typeId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            populateSelect(enrPhoneMarque, data.marques, 'new_marque', 'Ajouter une nouvelle marque');
                        }
                    })
                    .catch(error => console.error('Erreur lors de la récupération des marques :', error));
            }
        });

        // Gère les changements de marque
        enrPhoneMarque.addEventListener('change', function () {
            const marqueId = this.value;
            resetSelect(enrPhoneModele, 'Choisir le modèle');
            toggleNewMarqueInput();

            if (marqueId && marqueId !== 'new_marque') {
                fetch(`/get-modeles-by-marque/${marqueId}`)
                    .then(response => response.json())
                    .then(data => {
                        populateSelect(enrPhoneModele, data.modeles, 'new', 'Ajouter un nouveau modèle');
                    })
                    .catch(error => console.error('Erreur lors de la récupération des modèles :', error));
            }
        });

        // Gère les changements de modèle
        enrPhoneModele.addEventListener('change', function () {
            toggleNewModeleInput();
        });

        // Gère l'affichage initial (au cas où des champs seraient pré-sélectionnés)
        toggleNewMarqueInput();
        toggleNewModeleInput();
        togglePhoneEnroll();

        // Affiche le modal en cas d'erreurs de validation
        @if ($errors->hasBag('enr_phone_errors'))
            var modalEnrPhone = new bootstrap.Modal(document.getElementById('modal_enr_phone'));
            modalEnrPhone.show();
        @endif
    });
</script>


{{-- EDIT --}}

<script>
    document.addEventListener('DOMContentLoaded', function () {
        setupEditButtonListeners();
        reopenModalOnValidationError();
    });

    /**
     * Initialise les événements pour les boutons "Éditer".
     */
    function setupEditButtonListeners() {
        const editButtons = document.querySelectorAll('[data-bs-target="#modal_edt_phone"]');

        editButtons.forEach(button => {
            button.addEventListener('click', function () {
                const form = document.getElementById('form_edt_phone');

                // Injecte les données dynamiques dans le formulaire
                injectDataIntoForm(form, this);

                // Met à jour l'action du formulaire
                const id_phone = this.getAttribute('data-id');
                form.action = `/phones/${id_phone}`;

                // Gère l'affichage du champ "Enrôlé"
                togglePhoneEnroll(this.getAttribute('data-type'), this.getAttribute('data-enroll'));
            });
        });
    }

    /**
     * Injecte les données dynamiques dans le formulaire d'édition.
     * @param {HTMLElement} form - Le formulaire cible.
     * @param {HTMLElement} button - Le bouton contenant les données.
     */
    function injectDataIntoForm(form, button) {
        const fields = [
            { id: 'edt_phone_id', data: 'data-id' },
            { id: 'edt_phone_type', data: 'data-type' },
            { id: 'edt_phone_marque', data: 'data-marque' },
            { id: 'edt_phone_modele', data: 'data-modele' },
            { id: 'edt_phone_imei', data: 'data-imei' },
            { id: 'edt_phone_sn', data: 'data-sn' },
            { id: 'edt_phone_enroll', data: 'data-enroll' },
        ];

        fields.forEach(field => {
            const element = document.getElementById(field.id);
            if (element) {
                element.value = button.getAttribute(field.data);
            }
        });

        syncDisabledFieldsWithHiddenInputs(button);
    }

    /**
     * Synchronise les champs désactivés avec leurs champs cachés correspondants.
     * @param {HTMLElement} button - Bouton contenant les données.
     */
    function syncDisabledFieldsWithHiddenInputs(button) {
        const hiddenFields = [
            { hiddenId: 'hidden_phone_type', data: 'data-type' },
            { hiddenId: 'hidden_phone_marque', data: 'data-marque' },
            { hiddenId: 'hidden_phone_modele', data: 'data-modele' },
        ];

        hiddenFields.forEach(field => {
            const hiddenElement = document.getElementById(field.hiddenId);
            if (hiddenElement) {
                hiddenElement.value = button.getAttribute(field.data);
            }
        });
    }

    function togglePhoneEnroll(dataType, enrollValue) {
        const edtPhoneEnroll = document.getElementById('edt_phone_enroll');
        const edtPhoneEnrollDiv = edtPhoneEnroll.closest('.mb-3');

        // Si le type est "Téléphone à Touche", on cache le champ "Enrôlé"
        if (dataType === 'Téléphone à Touche') {
            edtPhoneEnrollDiv.classList.add('d-none');
            edtPhoneEnroll.value = '2'; // Valeur par défaut pour "Non enrôlé"
        } else {
            edtPhoneEnrollDiv.classList.remove('d-none');
            edtPhoneEnroll.value = enrollValue || '0'; // Valeur par défaut
        }
    }

    /**
     * Rouvre le modal en cas d'erreur de validation.
     */
    function reopenModalOnValidationError() {
        @if ($errors->hasBag('edt_phone_errors'))
            const modalEdtPhone = new bootstrap.Modal(document.getElementById('modal_edt_phone'));
            modalEdtPhone.show();
        @endif
    }
</script>


{{-- HS --}}

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const openHSModalButtons = document.querySelectorAll('.open-hs-modal');

        openHSModalButtons.forEach(button => {
            button.addEventListener('click', function (event) {
                event.preventDefault(); // Empêche l'action par défaut

                // Récupère les données dynamiques
                const phoneId = this.dataset.phoneId;
                const phoneName = this.dataset.phoneName;
                const phoneImei = this.dataset.phoneImei;
                const phoneSN = this.dataset.phoneSn;

                // Injecte les données dans le formulaire
                document.getElementById('hs_phone_id').value = phoneId;
                document.getElementById('hs_phone').value = phoneName;
                document.getElementById('imei_phone').value = phoneImei;
                document.getElementById('sn_phone').value = phoneSN;

                // Affiche le modal
                const modalHSPhone = new bootstrap.Modal(document.getElementById('modal_hs_phone'));
                modalHSPhone.show();
            });
        });
    });
</script>

{{-- Retour --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const openRetourModalButtons = document.querySelectorAll('.open-retour-modal');

    openRetourModalButtons.forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault(); // Empêche l'action par défaut

            // Récupère les données dynamiques correctement
            const phoneIdRetour = this.dataset.idRetour; // data-id-retour
            const affectationIdRetour = this.dataset.affectationRetour; // data-affectation-retour
            const debutRetour = this.dataset.debutRetour; // data-debut-retour
            const phoneTypeRetour = this.dataset.typeRetour; // data-type-retour
            const phoneNameRetour = this.dataset.nameRetour; // data-name-retour
            const phoneImeiRetour = this.dataset.imeiRetour; // data-imei-retour
            const phoneSnRetour = this.dataset.snRetour; // data-sn-retour
            const phoneUserRetour = this.dataset.userRetour; // data-user-retour

            // Injecte les données dans le formulaire
            document.getElementById('retour_phone_id').value = phoneIdRetour;
            document.getElementById('retour_affectation_id').value = affectationIdRetour;
            document.getElementById('retour_debut').value = debutRetour;
            document.getElementById('retour_type').value = phoneTypeRetour;
            document.getElementById('retour_phone').value = phoneNameRetour;
            document.getElementById('retour_imei').value = phoneImeiRetour;
            document.getElementById('retour_sn').value = phoneSnRetour;
            document.getElementById('retour_user').value = phoneUserRetour;

            // Affiche le modal
            const modalRetourPhone = new bootstrap.Modal(document.getElementById('modal_retour_phone'));
            modalRetourPhone.show();
        });
    });
});
</script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Vérifie si des erreurs sont présentes dans `retour_phone_errors` (backend Laravel)
        @if ($errors->hasBag('retour_phone_errors') && $errors->retour_phone_errors->any())
            const modalRetourPhone = new bootstrap.Modal(document.getElementById('modal_retour_phone'));
            modalRetourPhone.show();
        @endif
    });
</script>

{{-- Voir Historique Phone --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Sélectionne tous les boutons pour voir les détails
        const voirPhoneBtns = document.querySelectorAll('#btn_histo_phone');

        // Ajoute un gestionnaire d'événements à chaque bouton
        voirPhoneBtns.forEach(btn => {
            btn.addEventListener('click', function (event) {
                event.preventDefault(); // Empêche la redirection normale

                // Récupère les informations depuis les attributs data-*
                const idPhone = this.getAttribute('data-id-histo');
                const marque = this.getAttribute('data-marque-histo') || '--';
                const modele = this.getAttribute('data-modele-histo') || '--';
                const serialNumber = this.getAttribute('data-serial-number-histo') || '--';
                const imei = this.getAttribute('data-imei-histo') || '--';

                // Remplit les champs du modal avec les informations générales
                document.querySelector('#modal_histo_phone .modal-body [data-field="marque"]').textContent = marque;
                document.querySelector('#modal_histo_phone .modal-body [data-field="modele"]').textContent = modele;
                document.querySelector('#modal_histo_phone .modal-body [data-field="serial_number"]').textContent = serialNumber;
                document.querySelector('#modal_histo_phone .modal-body [data-field="imei"]').textContent = imei;

                // Appelle l'API pour récupérer l'historique d'affectation
                fetch(`/phone/histoPhone/${idPhone}`)
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
                        const modal = new bootstrap.Modal(document.getElementById('modal_histo_phone'));
                        modal.show();
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        alert('Une erreur est survenue lors de la récupération des détails du téléphone.');
                    });
            });
        });

        // Fonction pour injecter les données d'historique dans le tableau
        function populateModal(data) {
            const tbody = document.querySelector('#modal_histo_phone .modal-body #dataTable tbody');
            const modalBody = document.querySelector('#modal_histo_phone .modal-body'); // Conteneur principal du modal

            // Vide le tableau pour éviter d'afficher des données redondantes
            tbody.innerHTML = '';

            // Supprime le commentaire précédent s'il existe
            const existingCommentElement = document.querySelector('#phoneComment');
            if (existingCommentElement) {
                existingCommentElement.remove();
            }

            // Affichage des historiques
            if (data.historique && Array.isArray(data.historique) && data.historique.length > 0) {
                data.historique.forEach(item => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="text-dark">${item.nom || ''} ${item.prenom || ''}</td>
                        <td class="text-dark">${item.login || '--'}</td>
                        <td class="text-dark">${item.localisation || '--'}</td>
                        <td class="text-dark">${item.debut_affectation || '--'}</td>
                        <td class="text-dark">${item.fin_affectation || '--'}</td>
                    `;
                    tbody.appendChild(row);
                });
            } else {
                // Aucun historique trouvé
                const noDataRow = document.createElement('tr');
                noDataRow.innerHTML = `
                    <td class="text-dark text-center" colspan="5">Aucun historique disponible.</td>
                `;
                tbody.appendChild(noDataRow);
            }

            // Ajout du commentaire unique à la fin
            if (data.commentaire) {
                const commentaireDiv = document.createElement('div'); // Conteneur pour le commentaire
                commentaireDiv.id = 'phoneComment';
                commentaireDiv.classList.add('mt-4', 'p-3', 'bg-light', 'border', 'rounded');
                commentaireDiv.innerHTML = `
                    <p class="text-dark fw-bold mb-0">Commentaire :</p>
                    <p class="text-dark fw-normal">${data.commentaire}</p>
                `;
                modalBody.appendChild(commentaireDiv); // Ajoute le commentaire après le tableau
            } else {
                console.warn('Aucun commentaire trouvé pour ce téléphone.');
            }
        }
    });

</script>
