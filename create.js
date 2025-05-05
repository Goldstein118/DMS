document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('create_karyawan').addEventListener('click', function () {
        const div = document.getElementById('toggleDiv_karyawan');
        div.classList.toggle('hidden_karyawan');
        console.log('Create Karyawan button clicked');

        document.getElementById('toggleDiv_role').classList.add('hidden_role');
    });

    document.getElementById('create_role').addEventListener('click', function () {
        const div = document.getElementById('toggleDiv_role');
        div.classList.toggle('hidden_role');
        console.log('Create role button clicked');
        document.getElementById('toggleDiv_karyawan').classList.add('hidden_karyawan');
    });

});
document.addEventListener('DOMContentLoaded', function () {
    $(document).ready(function () {
        const selectElement = $('.js-example-basic-single');

        // Initialize Select2
        selectElement.select2({
            placeholder: 'Select a role',
            minimumInputLength: 3, // Minimum characters to trigger the search
        });

        // Fetch roles dynamically when the user types
        selectElement.on('select2:open', function () {
            const searchInput = document.querySelector('.select2-search__field');

            searchInput.addEventListener('input', function () {
                const searchTerm = searchInput.value;

                if (searchTerm.length >= 3) {
                    fetch(`getRoles.php?search=${searchTerm}`, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                    })
                        .then((response) => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then((data) => {
                            // Clear existing options
                            selectElement.empty();

                            // Populate the dropdown with new options
                            data.forEach((role) => {
                                const newOption = new Option(role.text, role.id, false, false);
                                selectElement.append(newOption);
                            });

                            // Trigger Select2 to refresh the dropdown
                            selectElement.trigger('change');
                        })
                        .catch((error) => {
                            console.error('Error fetching roles:', error);
                        });
                }
            });
        });
    });
});

document.getElementById('submit_karyawan').addEventListener('click', function () {

    // Collect form data
    const name_karyawan = document.getElementById('name_karyawan').value;
    const divisi_karyawan = document.getElementById('divisi_karyawan').value;
    const phone_karyawan = document.getElementById('phone_karyawan').value;
    const address_karyawan = document.getElementById('address_karyawan').value;
    const nik_karyawan = document.getElementById('nik_karyawan').value;
    const role_id = $('#role_karyawan').val();
    // Create a data object
    const data_karyawan = { action: 'submit_karyawan', name_karyawan, divisi_karyawan, phone_karyawan, address_karyawan, nik_karyawan,role_id };

    // Send the data to the PHP script
    fetch('http://localhost/Web_Project/DMS/create.php', {
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

document.getElementById('submit_role').addEventListener('click', function () {
    // Collect form data
    const name_role = document.getElementById('name_role').value;
    const akses_role = document.getElementById('akses_role').value;
    // Create a data object
    const data_role = {action: 'submit_role', name_role,akses_role};

    // Send the data to the PHP script
    fetch('http://localhost/Web_Project/DMS/create.php', {
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

