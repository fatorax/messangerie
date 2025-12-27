const { default: Swal } = require("sweetalert2");

window.deleteChannel = async function (id) {

    if (!id || id == 1) {
        Swal.fire({
            icon: 'error',
            title: 'Erreur',
            text: "Vous ne pouvez pas supprimer ce channel.",
            timer: 2000,
        });
        return;
    }

    Swal.fire({
        title: "Êtes-vous sûr de vouloir supprimer ce channel ?",
        text: "Cette action est irréversible !",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Oui, supprimer",
        cancelButtonText: "Annuler",
        reverseButtons: true
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                const response = await fetch('/channels/delete', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ id }),
                });

                if (!response.ok) {
                    let errorMsg = 'Erreur lors de la suppression.';
                    try {
                        const errorData = await response.json();
                        errorMsg = errorData.message || errorMsg;
                    } catch {}
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: 'Une erreur est survenue lors de la suppression.'
                        // text: errorMsg
                    });
                    return;
                }

                Swal.fire({
                    icon: 'success',
                    title: 'Succès !',
                    text: 'Le channel a été supprimé avec succès.'
                }).then(() => {
                    window.location.replace('/channels');
                });
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'Une erreur est survenue lors de la suppression.'
                });
            }
        }
    });
};