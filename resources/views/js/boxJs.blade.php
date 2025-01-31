{{-- ENR --}}

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialisation des éléments du formulaire pour les Box
        const enrBoxMarque = document.getElementById('enr_box_marque');
        const newMarqueInput = document.getElementById('new_box_marque');
        const enrBoxModele = document.getElementById('enr_box_modele');
        const newModeleInput = document.getElementById('new_box_modele');

        // Affiche ou masque le champ "Nouvelle Marque" et sélectionne automatiquement "Ajouter un nouveau modèle"
        function toggleNewMarqueInput() {
            if (enrBoxMarque.value === 'new') { // Vérifie si l'utilisateur a sélectionné "Ajouter une nouvelle marque"
                newMarqueInput.classList.remove('d-none'); // Affiche le champ "Nouvelle Marque"
                populateNewModeleOption(); // Ajoute automatiquement "Ajouter un nouveau modèle"
            } else {
                newMarqueInput.classList.add('d-none');
                newMarqueInput.value = ''; // Réinitialise le champ "Nouvelle Marque"
            }
        }

        // Affiche ou masque le champ "Nouveau Modèle"
        function toggleNewModeleInput() {
            if (enrBoxModele.value === 'new') { // Vérifie si l'utilisateur a sélectionné "Ajouter un nouveau modèle"
                newModeleInput.classList.remove('d-none'); // Affiche le champ "Nouveau Modèle"
            } else {
                newModeleInput.classList.add('d-none');
                newModeleInput.value = ''; // Réinitialise le champ s'il est masqué
            }
        }

        // Réinitialise un champ <select> avec une option par défaut
        function resetSelect(selectElement, defaultOptionText) {
            selectElement.innerHTML = `<option value="0" disabled selected>${defaultOptionText}</option>`;
        }

        // Remplit un champ <select> avec des options dynamiques, tout en gardant l'option "new"
        function populateSelect(selectElement, items, newItemValue, newItemText) {
            if (newItemValue && newItemText) {
                selectElement.insertAdjacentHTML('beforeend', `<option value="${newItemValue}">${newItemText}</option>`);
            }
            items.forEach(item => {
                selectElement.insertAdjacentHTML('beforeend', `<option value="${item.id}">${item.name}</option>`);
            });
        }

        // Ajoute et sélectionne automatiquement l'option "Ajouter un nouveau modèle"
        function populateNewModeleOption() {
            resetSelect(enrBoxModele, 'Choisir le modèle');
            enrBoxModele.insertAdjacentHTML('beforeend', `<option value="new" selected>Ajouter un nouveau modèle</option>`);
            toggleNewModeleInput(); // Affiche le champ "Nouveau Modèle"
        }

        // Gère les changements de marque
        enrBoxMarque.addEventListener('change', function () {
            const marqueId = this.value;
            resetSelect(enrBoxModele, 'Choisir le modèle');
            toggleNewMarqueInput(); // Affiche ou masque le champ "Nouvelle Marque"

            if (marqueId && marqueId !== 'new') { // Si une marque existante est sélectionnée
                fetch(`/get-modeles-by-marque/${marqueId}`)
                    .then(response => response.json())
                    .then(data => {
                        populateSelect(enrBoxModele, data.modeles, 'new', 'Ajouter un nouveau modèle');
                        toggleNewModeleInput(); // Vérifie s'il faut afficher le champ "Nouveau Modèle"
                    })
                    .catch(error => console.error('Erreur lors de la récupération des modèles :', error));
            }
        });

        // Gère les changements de modèle
        enrBoxModele.addEventListener('change', function () {
            toggleNewModeleInput(); // Affiche ou masque le champ "Nouveau Modèle"
        });

        // Gestion initiale lors du chargement de la page
        toggleNewMarqueInput(); // Gère le champ "Nouvelle Marque" au chargement
        toggleNewModeleInput(); // Gère le champ "Nouveau Modèle" au chargement

        // Affiche le modal en cas d'erreurs de validation
        @if ($errors->hasBag('enr_box_errors'))
            const modalEnrBox = new bootstrap.Modal(document.getElementById('modal_enr_box'));
            modalEnrBox.show();
        @endif
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if ($errors->hasBag('enr_box_errors'))
            setTimeout(function () {
                const modalEnrBox = new bootstrap.Modal(document.getElementById('modal_enr_box'));
                modalEnrBox.show();
            }, 500); // Petit délai pour s'assurer que le DOM est prêt
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
        const editButtons = document.querySelectorAll('[data-bs-target="#modal_edt_box"]');

        editButtons.forEach(button => {
            button.addEventListener('click', function () {
                const form = document.getElementById('form_edt_box');

                // Injecte les données dynamiques dans le formulaire
                injectDataIntoForm(form, this);

                // Met à jour l'action du formulaire
                const id_box = this.getAttribute('data-id');
                form.action = `/box/${id_box}`;
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
            { id: 'edt_box_id', data: 'data-id' },
            { id: 'edt_box_type', data: 'data-type' },
            { id: 'edt_box_marque', data: 'data-marque' },
            { id: 'edt_box_modele', data: 'data-modele' },
            { id: 'edt_box_imei', data: 'data-imei' },
            { id: 'edt_box_sn', data: 'data-sn' },
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
            { hiddenId: 'hidden_box_type', data: 'data-type' },
            { hiddenId: 'hidden_box_marque', data: 'data-marque' },
            { hiddenId: 'hidden_box_modele', data: 'data-modele' },
        ];

        hiddenFields.forEach(field => {
            const hiddenElement = document.getElementById(field.hiddenId);
            if (hiddenElement) {
                hiddenElement.value = button.getAttribute(field.data);
            }
        });
    }

    /**
     * Rouvre le modal en cas d'erreur de validation.
     */
    function reopenModalOnValidationError() {
        @if ($errors->hasBag('edt_box_errors'))
            const modalEdtbox = new bootstrap.Modal(document.getElementById('modal_edt_box'));
            modalEdtbox.show();
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
                const boxId = this.dataset.boxId;
                const boxName = this.dataset.boxName;
                const boxImei = this.dataset.boxImei;
                const boxSN = this.dataset.boxSn;

                // Injecte les données dans le formulaire
                document.getElementById('hs_box_id').value = boxId;
                document.getElementById('hs_box').value = boxName;
                document.getElementById('imei_box').value = boxImei;
                document.getElementById('sn_box').value = boxSN;

                // Affiche le modal
                const modalHSBox = new bootstrap.Modal(document.getElementById('modal_hs_box'));
                modalHSBox.show();
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
                const boxIdRetour = this.dataset.idRetour; // data-id-retour
                const affectationIdRetour = this.dataset.affectationRetour; // data-affectation-retour
                const debutRetour = this.dataset.debutRetour; // data-debut-retour
                const boxTypeRetour = this.dataset.typeRetour; // data-type-retour
                const boxNameRetour = this.dataset.nameRetour; // data-name-retour
                const boxImeiRetour = this.dataset.imeiRetour; // data-imei-retour
                const boxSnRetour = this.dataset.snRetour; // data-sn-retour
                const boxUserRetour = this.dataset.userRetour; // data-user-retour
    
                // Injecte les données dans le formulaire
                document.getElementById('retour_box_id').value = boxIdRetour;
                document.getElementById('retour_affectation_id').value = affectationIdRetour;
                document.getElementById('retour_debut').value = debutRetour;
                document.getElementById('retour_type').value = boxTypeRetour;
                document.getElementById('retour_box').value = boxNameRetour;
                document.getElementById('retour_imei').value = boxImeiRetour;
                document.getElementById('retour_sn').value = boxSnRetour;
                document.getElementById('retour_user').value = boxUserRetour;
    
                // Affiche le modal
                const modalRetourBox = new bootstrap.Modal(document.getElementById('modal_retour_box'));
                modalRetourBox.show();
            });
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Vérifie si des erreurs sont présentes dans `retour_box_errors` (backend Laravel)
        @if ($errors->hasBag('retour_box_errors') && $errors->retour_box_errors->any())
            const modalRetourBox = new bootstrap.Modal(document.getElementById('modal_retour_box'));
            modalRetourBox.show();
        @endif
    });
</script>

{{-- Voir Historique box --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Sélectionne tous les boutons pour voir les détails
        const voirBoxBtns = document.querySelectorAll('#btn_histo_box');
    
        // Ajoute un gestionnaire d'événements à chaque bouton
        voirBoxBtns.forEach(btn => {
            btn.addEventListener('click', function (event) {
                event.preventDefault(); // Empêche la redirection normale
                
                // Récupère les informations depuis les attributs data-*
                const idBox = this.getAttribute('data-id-histo');
                const marque = this.getAttribute('data-marque-histo') || '--';
                const modele = this.getAttribute('data-modele-histo') || '--';
                const serialNumber = this.getAttribute('data-serial-number-histo') || '--';
                const imei = this.getAttribute('data-imei-histo') || '--';

                // Remplit les champs du modal avec les informations générales
                document.querySelector('#modal_histo_box .modal-body [data-field="marque"]').textContent = marque;
                document.querySelector('#modal_histo_box .modal-body [data-field="modele"]').textContent = modele;
                document.querySelector('#modal_histo_box .modal-body [data-field="serial_number"]').textContent = serialNumber;
                document.querySelector('#modal_histo_box .modal-body [data-field="imei"]').textContent = imei;
    
                // Appelle l'API pour récupérer l'historique d'affectation
                fetch(`/box/histoBox/${idBox}`)
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
                        const modal = new bootstrap.Modal(document.getElementById('modal_histo_box'));
                        modal.show();
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        alert('Une erreur est survenue lors de la récupération des détails de la box.');
                    });
            });
        });
    
        // Fonction pour injecter les données d'historique dans le tableau
        function populateModal(data) {
            const tbody = document.querySelector('#modal_histo_box .modal-body #dataTable tbody');

            // Vide le tableau pour éviter d'afficher des données redondantes
            tbody.innerHTML = '';

            if (data && Array.isArray(data) && data.length > 0) {
                data.forEach(item => {
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
        }
    });
    
</script>