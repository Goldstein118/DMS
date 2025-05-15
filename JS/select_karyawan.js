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
                <td>${karyawan.karyawan_id}</td>
                <td>${karyawan.nama}</td>
                <td>${karyawan.role_ID}</td>
                <td>${karyawan.divisi}</td>
                <td>${karyawan.noTelp}</td>
                <td>${karyawan.alamat}</td>
                <td>${karyawan.ktp}</td>
                <td>${karyawan.npwp}</td>
                <td>${karyawan.status}</td>
                <td>
                    <button class="delete_karyawan">Delete</button>
                    <button class="update_karyawan">Update</button>
                </td>
            `;

            tableBody.appendChild(row);
        });

        attachEventListeners();
    })
    .catch(error => {
        console.error('Error fetching karyawan:', error);
    });
function attachEventListeners() 
{
    document.getElementById('karyawan_table_body').addEventListener('click', function (event) {
    if (event.target.classList.contains('delete_karyawan')) {
        handleDeleteKaryawan(event.target);
    } else if (event.target.classList.contains('update_karyawan')) {
        handleUpdateKaryawan(event.target);
    }});

}
// Attach delete listeners
async function handleDeleteKaryawan(button) {
    const row= button.closest('tr');
    const karyawan_ID = row.cells[0].textContent;
    try{
        const response = await fetch(`${config.API_BASE_URL}/PHP/delete_karyawan.php`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ karyawan_id:karyawan_ID }),
            });
        if (response.ok)
            {
                row.remove();
                toastr.success('Karyawan deleted successfully.', {
                        timeOut: 500,
                        extendedTimeOut: 500,
                    });
            } else
            {
                throw new Error (`Failed to delete karyawan. Status: ${response.status}`);
            }
    }
    catch (error) 
    {
        console.error('Error deleting karyawan:', error);
        toastr.error('Failed to delete karyawan.', {
            timeOut: 500,
            extendedTimeOut: 500,
        });
    }
           


}
// Attach update listeners
function handleUpdateKaryawan(button) {

    const row = button.closest('tr');
    const karyawan_ID = row.cells[0].textContent;
    const currentNama = row.cells[1].textContent;
    const currentrole_ID = row.cells[2].textContent;
    const currentdivisi = row.cells[3].textContent;
    const currentnoTelp = row.cells[4].textContent;
    const currentalamat = row.cells[5].textContent;
    const currentKTP_NPWP = row.cells[6].textContent;
    const currentnpwp = row.cells[7].textContent;
    const currentstatus = row.cells[8].textContent;
    const updateDiv = document.getElementById('toggleDiv_karyawan_update');
    updateDiv.classList.toggle('hidden_karyawan_update');

    document.getElementById('update_karyawan_ID').value = karyawan_ID;
    document.getElementById('update_name_karyawan').value = currentNama;
    document.getElementById('update_divisi_karyawan').value = currentdivisi;
    document.getElementById('update_phone_karyawan').value = currentnoTelp;
    document.getElementById('update_address_karyawan').value = currentalamat;
    document.getElementById('update_nik_karyawan').value = currentKTP_NPWP;
    document.getElementById('update_npwp_karyawan').value = currentnpwp;
    document.getElementById('update_status_karyawan').value = currentstatus;
    const role_ID_Field = $('#update_role_select');

    fetch(`${config.API_BASE_URL}/PHP/API/role_API.php`)
        .then(response => response.json())
        .then(roles => {
                role_ID_Field.empty();
                roles.forEach(role => {
                    const option = new Option(role.nama, role.role_id, false, role.role_id === currentrole_ID);
                    role_ID_Field.append(option);
                    });
                    role_ID_Field.trigger('change');
                })
                .catch(error => {
                    console.error('Error fetching roles:', error);});
    window.currentRow = row;        
}
            




// Submit updated karyawan
document.getElementById('submit_karyawan_update').addEventListener('click', async function () {
    if (!window.currentRow) {
         toastr.error('No row selected for update.');
        return;
    }

    const row = window.currentRow;
    const karyawan_nama_new = document.getElementById('update_name_karyawan').value;
    const role_ID_new = $('#update_role_select').val();
    const divisi_new = document.getElementById('update_divisi_karyawan').value;
    const noTelp_new = document.getElementById('update_phone_karyawan').value;
    const alamat_new = document.getElementById('update_address_karyawan').value;
    const KTP_NPWP_new = document.getElementById('update_nik_karyawan').value;
    const npwp_new = document.getElementById('update_npwp_karyawan').value;
    const status_new = document.getElementById('update_status_karyawan').value;

    try {
    const response = await fetch(`${config.API_BASE_URL}/PHP/update_karyawan.php`, {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
    },
    body: JSON.stringify({
        karyawan_id,
        nama: karyawan_nama_new,
        role_id: role_ID_new,
        divisi: divisi_new,
        noTelp: noTelp_new,
        alamat: alamat_new,
        ktp: KTP_NPWP_new,
        npwp: npwp_new,
        status: status_new,
    }),
});
    if (response.ok){
            row.cells[1].textContent = karyawan_nama_new;
            row.cells[2].textContent = role_ID_new;
            row.cells[3].textContent = divisi_new;
            row.cells[4].textContent = noTelp_new;
            row.cells[5].textContent = alamat_new;
            row.cells[6].textContent = KTP_NPWP_new;
            row.cells[7].textContent = npwp_new;
            row.cells[8].textContent = status_new;

            toastr.success('Karyawan updated successfully', {
                timeOut: 500,
                extendedTimeOut: 500,
            });

            const updateDiv = document.getElementById('toggleDiv_karyawan_update');
            updateDiv.classList.toggle('hidden_karyawan_update');
    } else{
        throw new Error(`Failed to update karyawan. Status: ${response.status}`);
    }
    } catch (error) {
        console.error('Error updating karyawan:', error);
        toastr.error('Failed to update karyawan.', {
            timeOut: 500,
            extendedTimeOut: 500,
        });
    }


});