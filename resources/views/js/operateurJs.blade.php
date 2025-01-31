<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Écoute les clics sur les boutons d'édition
        document.querySelectorAll('.edit-contact').forEach(function (button) {
            button.addEventListener('click', function () {
                // Récupère les données depuis les attributs
                const id = this.getAttribute('data-id');
                const nom = this.getAttribute('data-nom');
                const email = this.getAttribute('data-email');
                const operateur = this.getAttribute('data-operateur');
                
                // Remplit les champs du modal
                document.getElementById('modal-id-contact').value = id;
                document.getElementById('modal-nom-contact').value = nom;
                document.getElementById('modal-email-contact').value = email;
            });
        });
    });
</script>