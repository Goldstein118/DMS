import config from "../JS/config.js"; 

document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('create_role').addEventListener('click', function () {
        document.getElementById('toggleDiv_role').classList.toggle('hidden_role');
    });
});
document.getElementById('submit_role').addEventListener('click', function () {
    // Collect form data
    const name_role = document.getElementById('name_role').value;
    const akses_role = document.getElementById('akses_role').value;
    // Create a data object
    const data_role = {action: 'submit_role', name_role,akses_role};

    // Send the data to the PHP script
    fetch(`${config.API_BASE_URL}/PHP/create.php`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data_role), // Convert the data object to JSON
    })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alert(result.message); // Show success message
            } else {
                alert(result.message); // Show error message
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
});