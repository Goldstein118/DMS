document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('create_karyawan').addEventListener('click', function () {
        const div = document.getElementById('toggleDiv_karyawan');
        div.classList.toggle('hidden_karyawan');
        console.log('Create Karyawan button clicked');

        document.getElementById('toggleDiv_role').classList.add('hidden_role');
        document.getElementById('toggleDiv_user').classList.add('hidden_user');
    });

    document.getElementById('create_role').addEventListener('click', function () {
        const div = document.getElementById('toggleDiv_role');
        div.classList.toggle('hidden_role');
        console.log('Create role button clicked');
        document.getElementById('toggleDiv_karyawan').classList.add('hidden_karyawan');
        document.getElementById('toggleDiv_user').classList.add('hidden_user');
    });

    document.getElementById('create_user').addEventListener('click', function () {
        const div = document.getElementById('toggleDiv_user');
        div.classList.toggle('hidden_user');
        console.log('Create user button clicked');
        document.getElementById('toggleDiv_karyawan').classList.add('hidden_karyawan');
        document.getElementById('toggleDiv_role').classList.add('hidden_role');
    });
});

document.getElementById('submit_karyawan').addEventListener('click', function () {
    // Collect form data
    const id = document.getElementById('id_karyawan').value;
    const name = document.getElementById('name_karyawan').value;
    const divisi = document.getElementById('divisi_karyawan').value;
    const phone = document.getElementById('phone_karyawan').value;
    const address = document.getElementById('address_karyawan').value;
    const nik = document.getElementById('nik_karyawan').value;

    // Create a data object
    const data = { id, name, divisi, phone, address, nik };

    // Send the data to the PHP script
    fetch('http://localhost/DMS/Create.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data), // Convert the data object to JSON
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
