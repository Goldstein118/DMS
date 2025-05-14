import config from "./config.js";

$(document).ready(function () {
    $('#table_karyawan').DataTable({
        paging: false,
        searching: false,
        ordering: false,
        info: false,
        language: {
            emptyTable: '',
            zeroRecords: '',
        },
    });
});

// Fetch and populate the karyawan table
fetch(`${config.API_BASE_URL}/PHP/API/karyawan_API.php`)
    .then(response => response.json())
    .then(karyawan => {
        const tableBody = document.getElementById('karyawan_table_body');

        karyawan.forEach(karyawan => {
            const row = document.createElement('tr');

            row.innerHTML = `
                <td>${karyawan.karyawan_ID}</td>
                <td>${karyawan.nama}</td>
                <td>${karyawan.role_ID}</td>
                <td>${karyawan.divisi}</td>
                <td>${karyawan.noTelp}</td>
                <td>${karyawan.alamat}</td>
                <td>${karyawan.KTP_NPWP}</td>
                <td>
                    <button class="delete_karyawan">Delete</button>
                    <button class="update_karyawan">Update</button>
                </td>
            `;

            tableBody.appendChild(row);
        });

        attachDeleteListeners();
        attachUpdateListeners();
    })
    .catch(error => {
        console.error('Error fetching karyawan:', error);
    });

// Attach delete listeners
function attachDeleteListeners() {
    document.querySelectorAll('.delete_karyawan').forEach(button => {
        button.addEventListener('click', function () {
            const row = this.closest('tr');
            const karyawan_ID = row.cells[0].textContent;

            fetch(`${config.API_BASE_URL}/PHP/delete_karyawan.php`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ karyawan_ID }),
            })
                .then(response => {
                    if (response.ok) {
                        row.remove();
                        toastr.success('Karyawan deleted successfully', {
                            timeOut: 500,
                            extendedTimeOut: 500,
                        });
                    } else {
                        console.error('Error deleting karyawan:', response.status);
                        toastr.error('Failed to delete karyawan.', {
                            timeOut: 500,
                            extendedTimeOut: 500,
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    toastr.error('An error occurred while deleting the karyawan.');
                });
        });
    });
}

// Attach update listeners
function attachUpdateListeners() {
    document.querySelectorAll('.update_karyawan').forEach(button => {
        button.addEventListener('click', function () {
            const row = this.closest('tr');
            const karyawan_ID = row.cells[0].textContent;
            const currentNama = row.cells[1].textContent;
            const currentrole_ID = row.cells[2].textContent;
            const currentdivisi = row.cells[3].textContent;
            const currentnoTelp = row.cells[4].textContent;
            const currentalamat = row.cells[5].textContent;
            const currentKTP_NPWP = row.cells[6].textContent;

            const updateDiv = document.getElementById('toggleDiv_karyawan_update');
            updateDiv.classList.toggle('hidden_karyawan_update');

            document.getElementById('update_karyawan_ID').value = karyawan_ID;
            document.getElementById('update_name_karyawan').value = currentNama;
            document.getElementById('update_divisi_karyawan').value = currentdivisi;
            document.getElementById('update_phone_karyawan').value = currentnoTelp;
            document.getElementById('update_address_karyawan').value = currentalamat;
            document.getElementById('update_nik_karyawan').value = currentKTP_NPWP;

            const role_ID_Field = $('#update_role_select');
            console.log(`${config.API_BASE_URL}/PHP/update_karyawan.php`);

            fetch(`${config.API_BASE_URL}/PHP/API/role_API.php`)
                .then(response => response.json())
                .then(roles => {
                    role_ID_Field.empty();
                    roles.forEach(role => {
                        const option = new Option(role.nama, role.role_ID, false, role.role_ID === currentrole_ID);
                        role_ID_Field.append(option);
                    });
                    role_ID_Field.trigger('change');
                })
                .catch(error => {
                    console.error('Error fetching roles:', error);
                });

            window.currentRow = row;
        });
    });
}

// Submit updated karyawan
document.getElementById('submit_karyawan_update').addEventListener('click', function () {
    if (!window.currentRow) {
         toastr.error('No row selected for update.');
        return;
    }

    const row = window.currentRow;
    const karyawan_ID = document.getElementById('update_karyawan_ID').value;
    const karyawan_nama_new = document.getElementById('update_name_karyawan').value;
    const role_ID_new = $('#update_role_select').val();
    const divisi_new = document.getElementById('update_divisi_karyawan').value;
    const noTelp_new = document.getElementById('update_phone_karyawan').value;
    const alamat_new = document.getElementById('update_address_karyawan').value;
    const KTP_NPWP_new = document.getElementById('update_nik_karyawan').value;
    console.log(JSON.stringify({
    karyawan_ID,
    nama: karyawan_nama_new,
    role_ID: role_ID_new,
    divisi: divisi_new,
    noTelp: noTelp_new,
    alamat: alamat_new,
    KTP_NPWP: KTP_NPWP_new,
}));

fetch(`${config.API_BASE_URL}/PHP/update_karyawan.php`, {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
    },
    body: JSON.stringify({
        karyawan_ID,
        nama: karyawan_nama_new,
        role_ID: role_ID_new,
        divisi: divisi_new,
        noTelp: noTelp_new,
        alamat: alamat_new,
        KTP_NPWP: KTP_NPWP_new,
    }),
})
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            row.cells[1].textContent = karyawan_nama_new;
            row.cells[2].textContent = role_ID_new;
            row.cells[3].textContent = divisi_new;
            row.cells[4].textContent = noTelp_new;
            row.cells[5].textContent = alamat_new;
            row.cells[6].textContent = KTP_NPWP_new;

            toastr.success(data.message, {
                timeOut: 500,
                extendedTimeOut: 500,
            });

            const updateDiv = document.getElementById('toggleDiv_karyawan_update');
            updateDiv.classList.toggle('hidden_karyawan_update');
        } else {
            toastr.error(data.message, {
                timeOut: 500,
                extendedTimeOut: 500,
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        toastr.error('An error occurred while updating the karyawan.');
    });
});