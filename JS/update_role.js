document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('.update_role').addEventListener('click', function () {
        document.getElementById('toggleDiv_role_update').classList.toggle('hidden_role_update');
    });
});


document.querySelectorAll('.update_role').forEach(button => {
    button.addEventListener('click', function () {
        const row = this.closest('tr'); // Get the closest row
        const role_ID = row.cells[0].textContent; // Get the role_ID from the first cell
        const currentNama = row.cells[1].textContent; // Get the current role name
        const currentAkses = row.cells[2].textContent; // Get the current access level

        // Prompt the user for new values
        const newNama = prompt('Enter new role name:', currentNama);
        const newAkses = prompt('Enter new access level:', currentAkses);

        // If the user cancels the prompt, do nothing
        if (newNama === null || newAkses === null) return;

        // Send the update request to the server
        fetch(`${config.API_BASE_URL}/PHP/update_role.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                role_ID: role_ID,
                nama: newNama,
                akses: newAkses,
            }),
        })
        .then(response => {
            if (response.ok) {
                // Update the table row with the new values
                row.cells[1].textContent = newNama;
                row.cells[2].textContent = newAkses;
                console.log('Role updated successfully');
            } else {
                console.error('Error updating role:', response.status);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
});