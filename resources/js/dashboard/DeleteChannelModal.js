window.deleteChannel = async function (id) {

    if (!id || id == 1) {
        return;
    }

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
            // const errorData = await response.json();

            // if (errorData.errors?.name) {
            //     errorSpan.textContent = errorData.errors.name[0];
            // } else {
            //     errorSpan.textContent = errorData.message || `Erreur HTTP ${response.status}`;
            // }

            // Message d'erreur

            return;
        }

        window.location.replace('/channels');

    } catch (error) {
        // Message d'erreur
    }
};