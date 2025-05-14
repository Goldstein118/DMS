import config from "../JS/config.js";

document.addEventListener('DOMContentLoaded', function () {
    // Toggle the visibility of the role form
    document.getElementById('create_role').addEventListener('click', function () {
        document.getElementById('toggleDiv_role').classList.toggle('hidden_role');
    });

    // Attach event listener for form submission
    document.getElementById('submit_role').addEventListener('click', submitRole);
});

function submitRole() {
    // Collect form data
    const name_role = document.getElementById('name_role').value;
    const akses_role = document.getElementById('akses_role').value;

    // Validate form data
    if (!name_role || !akses_role) {
        toastr.error('Please fill in all fields before submitting.');
        return;
    }

    // Create a data object
    const data_role = { action: 'submit_role', name_role, akses_role };

    // Send the data to the PHP script
    fetch(`${config.API_BASE_URL}/PHP/create.php`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data_role),
    })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                toastr.success(result.message, {
                    timeOut: 500,
                    extendedTimeOut: 500,
                });

                // Reset the form
                document.getElementById('name_role').value = '';
                document.getElementById('akses_role').value = '';

                // Hide the form
                const updateDiv = document.getElementById('toggleDiv_role');
                updateDiv.classList.toggle('hidden_role');
            } else {
                toastr.error(result.message, {
                    timeOut: 500,
                    extendedTimeOut: 500,
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            toastr.error('An error occurred while submitting the form. Please try again.');
        });
}