import config from "./config.js"; 

document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('create_karyawan').addEventListener('click', function () {
        document.getElementById('toggleDiv_karyawan').classList.toggle('hidden_karyawan');
    });
});

document.addEventListener('DOMContentLoaded', function () {
    // Initialize Select2 on the role_select element
    $(document).ready(function () {
        $('#role_select').select2({
            placeholder: 'Search and select a role', // Placeholder text
            allowClear: true, // Allow clearing the selection
        });
    });

    // Fetch roles and populate the dropdown
    fetch(`${config.API_BASE_URL}/PHP/API/role_API.php`)
        .then(response => {
            if (response.ok) {
                return response.json(); // Parse the JSON response
            } else {
                throw new Error(`Failed to fetch roles. Status: ${response.status} ${response.statusText}`);
            }
        })
        .then(data => {
            if (Array.isArray(data) && data.length > 0) {
                const select = document.getElementById('role_select');
                select.innerHTML = ''; // Clear existing options

                // Add a default option
                delay:500
                const defaultOption = document.createElement('option');
                defaultOption.value = '';
                defaultOption.textContent = 'Select a role';
                select.appendChild(defaultOption);

                // Add fetched options
                data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.role_ID; // Use the correct key
                    option.textContent = item.nama; // Use the correct key
                    select.appendChild(option);
                });

                // Reinitialize Select2 to refresh the dropdown
                $('#role_select').select2();
            } else {
                console.warn('No roles found or invalid data format.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
});

document.getElementById('submit_karyawan').addEventListener('click', function () {

    // Collect form data
    const name_karyawan = document.getElementById('name_karyawan').value;
    const divisi_karyawan = document.getElementById('divisi_karyawan').value;
    const phone_karyawan = document.getElementById('phone_karyawan').value;
    const address_karyawan = document.getElementById('address_karyawan').value;
    const nik_karyawan = document.getElementById('nik_karyawan').value;
    const role_id = document.getElementById('role_select').value; // Get the selected role ID
    // Create a data object
    const data_karyawan = { action: 'submit_karyawan', name_karyawan, divisi_karyawan, phone_karyawan, address_karyawan, nik_karyawan,role_id };

    // Send the data to the PHP script
    fetch(`${config.API_BASE_URL}/PHP/create.php`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data_karyawan),
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json(); // Attempt to parse JSON
        })
        .then(result => {
            if (result.success) {
                alert(result.message);
            } else {
                alert(result.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please check the console for details.');
        });
});



