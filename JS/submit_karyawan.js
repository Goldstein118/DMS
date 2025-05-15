import config from "./config.js";

document.addEventListener('DOMContentLoaded', function () {
    // Toggle the visibility of the karyawan form
    document.getElementById('create_karyawan').addEventListener('click', function () {
        document.getElementById('toggleDiv_karyawan').classList.toggle('hidden_karyawan');
    });

    // Initialize Select2 on the role_select element
    $('#role_select').select2({
        placeholder: 'Search and select a role',
        allowClear: true,
    });

    // Fetch roles and populate the dropdown
    fetchRoles();

    // Submit form data
    document.getElementById('submit_karyawan').addEventListener('click', function () {
        submitKaryawan();
    });
});

function fetchRoles() {
    fetch(`${config.API_BASE_URL}/PHP/API/role_API.php`)
        .then(response => {
            if (response.ok) {
                return response.json();
            } else {
                throw new Error(`Failed to fetch roles. Status: ${response.status} ${response.statusText}`);
            }
        })
        .then(data => {
            if (Array.isArray(data) && data.length > 0) {
                populateRoleDropdown(data);
            } else {
                console.warn('No roles found or invalid data format.');
            }
        })
        .catch(error => {
            console.error('Error fetching roles:', error);
            toastr.error('Failed to load roles. Please refresh the page.');
        });
}

function populateRoleDropdown(data) {
    const select = document.getElementById('role_select');
    select.innerHTML = ''; // Clear existing options

    const defaultOption = document.createElement('option');
    defaultOption.value = '';
    defaultOption.textContent = 'Select a role';
    select.appendChild(defaultOption);

    data.forEach(item => {
        const option = document.createElement('option');
        option.value = item.role_ID;
        option.textContent = item.nama;
        select.appendChild(option);
    });

    $('#role_select').select2();
}

function submitKaryawan() {
    // Collect form data
    const name_karyawan = document.getElementById('name_karyawan').value;
    const divisi_karyawan = document.getElementById('divisi_karyawan').value;
    const phone_karyawan = document.getElementById('phone_karyawan').value;
    const address_karyawan = document.getElementById('address_karyawan').value;
    const nik_karyawan = document.getElementById('nik_karyawan').value;
    const role_id = document.getElementById('role_select').value;
    const npwp_karyawan= document.getElementById('npwp_karyawan').value;
    const status_karyawan= document.getElementById('status_karyawan').value;

    // Validate form data
    if (!name_karyawan || !divisi_karyawan || !phone_karyawan || !address_karyawan || !nik_karyawan || !role_id) {
        toastr.error('Please fill in all fields before submitting.');
        return;
    }

    // Create a data object
    const data_karyawan = { action: 'submit_karyawan', name_karyawan, divisi_karyawan, phone_karyawan, address_karyawan, nik_karyawan, role_id, npwp_karyawan, status_karyawan };

    // Send the data to the PHP script
    fetch(`${config.API_BASE_URL}/PHP/create.php`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data_karyawan),
    })
        .then(response => {
            return response.json();
        })
        .then(jsonData => {
            if (jsonData.success) {
                toastr.success(jsonData.message, {
                    timeOut: 500,
                    extendedTimeOut: 500,
                });

                // Reset the form
                document.getElementById('name_karyawan').value = '';
                document.getElementById('divisi_karyawan').value = '';
                document.getElementById('phone_karyawan').value = '';
                document.getElementById('address_karyawan').value = '';
                document.getElementById('nik_karyawan').value = '';
                document.getElementById('npwp_karyawan').value = '';
                document.getElementById('status_karyawan').value = '';
                $('#role_select').val(null).trigger('change');

                // Hide the form
                const updateDiv = document.getElementById('toggleDiv_karyawan');
                updateDiv.classList.toggle('hidden_karyawan');
            } else {
                toastr.error(jsonData.message, {
                    timeOut: 500,
                    extendedTimeOut: 500,
                });
            }
        })
        .catch(error => {
            console.error('Error submitting karyawan:', error);
            toastr.error('An error occurred while submitting the form. Please try again.');
        });
}