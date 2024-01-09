function confirmDelete() {
    var userConfirmed = confirm("Sei sicuro di voler eliminare il tuo account? Questa azione non può essere annullata.");

    // Se l'utente conferma, invia il form di eliminazione
    if (userConfirmed) {
        document.getElementById("deleteAccountForm").submit();
    }
}