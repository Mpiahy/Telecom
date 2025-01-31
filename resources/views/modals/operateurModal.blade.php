<!-- Modal pour modifier un contact -->
<div id="modifier_contact_operateur" class="modal" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-primary">Modifier le contact pour cet opérateur</h4>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form_edt_contact_operateur" action="{{ route('operateur.modifier') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_contact" id="modal-id-contact">
                    <div class="mb-3">
                        <label class="form-label" for="nom_contact"><strong>Nom du contact</strong></label>
                        <input id="modal-nom-contact" class="form-control" type="text" name="nom_contact" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="email_contact"><strong>Email du contact</strong></label>
                        <input id="modal-email-contact" class="form-control" type="email" name="email_contact" />
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-warning" type="button" data-bs-dismiss="modal">Fermer</button>
                <button class="btn btn-primary" type="submit" form="form_edt_contact_operateur">Enregistrer</button>
            </div>
        </div>
    </div>
</div>