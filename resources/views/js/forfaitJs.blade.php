<script>
    document.addEventListener('DOMContentLoaded', function () {
        setupEditElementButtonListeners();
        setupDelElementButtonListeners();

        // Rouvre le modal d'édition en cas d'erreurs de validation après redirection
        @if (session('openEditModal'))
            injectOldDataIntoEditForm(); // Injecte les anciennes données dans le formulaire d'édition
            const modalEdtElement = new bootstrap.Modal(document.getElementById('modifier_element'));
            modalEdtElement.show();
        @endif

        // Rouvre le modal de suppression en cas d'erreurs de validation après redirection
        @if (session('openDeleteModal'))
            injectOldDataIntoDelForm(); // Injecte les anciennes données dans le formulaire de suppression
            const modalDelElement = new bootstrap.Modal(document.getElementById('supprimer_element'));
            modalDelElement.show();
        @endif
    });

    /**
     * Initialise les événements pour les boutons "Modifier" des éléments du forfait.
     */
    function setupEditElementButtonListeners() {
        const editButtons = document.querySelectorAll('[data-bs-target="#modifier_element"]');

        editButtons.forEach(button => {
            button.addEventListener('click', function () {
                const form = document.getElementById('edt_element_form');

                // Injecter les données dynamiques dans le formulaire d'édition
                injectElementDataIntoForm(form, this);

                // Mettre à jour l'action du formulaire
                const idElement = this.getAttribute('data-id_element');
                const idForfait = this.getAttribute('data-id_forfait');
                form.action = `/forfaits/update-element/${idForfait}/${idElement}`;
            });
        });
    }

    /**
     * Injecte les données dynamiques dans le formulaire d'édition d'élément.
     * @param {HTMLElement} form - Le formulaire cible.
     * @param {HTMLElement} button - Le bouton contenant les données.
     */
    function injectElementDataIntoForm(form, button) {
        const fields = [
            { id: 'edt_element', data: 'data-libelle' },
            { id: 'edt_unite', data: 'data-unite' },
            { id: 'edt_qu', data: 'data-quantite' },
            { id: 'edt_pu', data: 'data-prix_unitaire' },
            { id: 'edt_id_forfait', data: 'data-id_forfait' },
            { id: 'edt_id_element', data: 'data-id_element' }
        ];

        fields.forEach(field => {
            const element = document.getElementById(field.id);
            if (element) {
                element.value = button.getAttribute(field.data);
            }
        });

        // Nettoyer et formater le prix unitaire avant de l'injecter
        const prixUnitaire = button.getAttribute('data-prix_unitaire')
            .replace(/\s/g, '') // Supprimer les espaces
            .replace(',', '.'); // Remplacer la virgule par un point

        document.getElementById('edt_pu').value = parseFloat(prixUnitaire) || 0;
    }

    /**
     * Injecte les anciennes données dans le formulaire d'édition après validation échouée.
     */
    function injectOldDataIntoEditForm() {
        const fields = [
            { id: 'edt_element', value: "{{ old('edt_element') }}" },
            { id: 'edt_unite', value: "{{ old('edt_unite') }}" },
            { id: 'edt_qu', value: "{{ old('edt_qu') }}" },
            { id: 'edt_pu', value: "{{ old('edt_pu') }}" },
            { id: 'edt_id_forfait', value: "{{ old('edt_id_forfait') }}" },
            { id: 'edt_id_element', value: "{{ old('edt_id_element') }}" }
        ];

        fields.forEach(field => {
            const element = document.getElementById(field.id);
            if (element) {
                element.value = field.value || '';
            }
        });
    }

    /**
     * Initialise les événements pour les boutons "Supprimer" des éléments du forfait.
     */
    function setupDelElementButtonListeners() {
        const deleteButtons = document.querySelectorAll('[data-bs-target="#supprimer_element"]');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                const form = document.getElementById('del_element_form');

                // Injecter les données dynamiques dans le formulaire de suppression
                injectElementDataIntoFormDel(form, this);

                // Mettre à jour l'action du formulaire
                const idElement = this.getAttribute('del-data-id_element');
                const idForfait = this.getAttribute('del-data-id_forfait');
                form.action = `/forfaits/delete-element/${idForfait}/${idElement}`;
            });
        });
    }

    /**
     * Injecte les données dynamiques dans le formulaire de suppression d'élément.
     * @param {HTMLElement} form - Le formulaire cible.
     * @param {HTMLElement} button - Le bouton contenant les données.
     */
    function injectElementDataIntoFormDel(form, button) {
        const fields = [
            { id: 'del_element', data: 'del-data-libelle' },
            { id: 'del_unite', data: 'del-data-unite' },
            { id: 'del_qu', data: 'del-data-quantite' },
            { id: 'del_pu', data: 'del-data-prix_unitaire' },
            { id: 'del_id_forfait', data: 'del-data-id_forfait' },
            { id: 'del_id_element', data: 'del-data-id_element' }
        ];

        fields.forEach(field => {
            const element = document.getElementById(field.id);
            if (element) {
                element.value = button.getAttribute(field.data);
            }
        });

        // Nettoyer et formater le prix unitaire avant de l'injecter
        const prixUnitaire = button.getAttribute('del-data-prix_unitaire')
            .replace(/\s/g, '') // Supprimer les espaces
            .replace(',', '.'); // Remplacer la virgule par un point

        document.getElementById('del_pu').value = parseFloat(prixUnitaire) || 0;
    }

    /**
     * Injecte les anciennes données dans le formulaire de suppression après validation échouée.
     */
    function injectOldDataIntoDelForm() {
        const fields = [
            { id: 'del_element', value: "{{ old('del_element') }}" },
            { id: 'del_unite', value: "{{ old('del_unite') }}" },
            { id: 'del_qu', value: "{{ old('del_qu') }}" },
            { id: 'del_pu', value: "{{ old('del_pu') }}" },
            { id: 'del_id_forfait', value: "{{ old('del_id_forfait') }}" },
            { id: 'del_id_element', value: "{{ old('del_id_element') }}" }
        ];

        fields.forEach(field => {
            const element = document.getElementById(field.id);
            if (element) {
                element.value = field.value || '';
            }
        });
    }
</script>
