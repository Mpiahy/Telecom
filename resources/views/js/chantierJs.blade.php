<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Ciblez tous les boutons de modification
        document.querySelectorAll('.open-edit-modal').forEach(button => {
            button.addEventListener('click', function() {
                // Récupérez les valeurs actuelles du chantier depuis les attributs data-*
                const id = this.getAttribute('data-id');
                const service = this.getAttribute('data-service');
                const imputation = this.getAttribute('data-imputation');

                // Pré-remplissez les champs du formulaire dans le modal
                document.getElementById('edt_service').value = service;
                document.getElementById('edt_imp').value = imputation;

                // Mettez à jour l'action du formulaire pour inclure l'ID du chantier
                document.getElementById('edt_chantier').action = `/chantier/modifier/${id}`;
            });
        });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Ciblez tous les boutons de suppression
        document.querySelectorAll('.open-delete-modal').forEach(button => {
            button.addEventListener('click', function() {
                // Récupérez l'ID et le nom du chantier depuis les attributs data-*
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                
                // Mettez à jour le texte du modal pour afficher le nom du chantier
                document.querySelector('#supprimer_chantier .modal-body p strong').textContent = name;

                // Mettez à jour le lien de suppression
                const deleteButton = document.querySelector('#supprimer_chantier .modal-footer .btn-danger');
                deleteButton.href = `/chantier/supprimer/${id}`;
            });
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Vérifie si des erreurs sont présentes dans `add_chantier_errors` (backend Laravel)
        @if ($errors->hasBag('add_chantier_errors') && $errors->add_chantier_errors->any())
            const modalAddChantier = new bootstrap.Modal(document.getElementById('ajouter_chantier'));
            modalAddChantier.show(); // Affiche automatiquement le modal pour corriger les erreurs
        @endif
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Vérifie si des erreurs sont présentes dans `edt_chantier_errors` (backend Laravel)
        @if ($errors->hasBag('edt_chantier_errors') && $errors->edt_chantier_errors->any())
            const modalEdtChantier = new bootstrap.Modal(document.getElementById('modifier_chantier'));
            modalEdtChantier.show(); // Affiche automatiquement le modal pour corriger les erreurs
        @endif
    });
</script>

<script>
    document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(button => {
        button.addEventListener('click', function() {
            // Supprimer les classes d'erreur
            document.querySelectorAll('.is-invalid').forEach(input => {
                input.classList.remove('is-invalid');
            });
        });
    });
</script>

