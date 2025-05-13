import config from "./config.js"; 
$(document).ready(function() {
    $('#table_karyawan').DataTable({paging: false,searching: false,ordering:false,info: false,  language: {
        emptyTable: '',
        zeroRecords: '',
    }});
});

fetch(`${config.API_BASE_URL}/PHP/API/karyawan_API.php`)
    .then(response => {
        if (response.ok) {
            return response.json(); // Parse the JSON response
        } else {
            console.error('Error:', response.status); // Handle errors
        }
    }).then(karyawan => {
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
                <td><button class="delete_karyawan" >Delete</button>
                    <button class="update_karyawan" >Update</button>
                </td>
            `;

            tableBody.appendChild(row);

        }
    
    );
document.querySelectorAll('.delete_karyawan').forEach(button => {
    button.addEventListener('click', function () {
        const row = this.closest('tr'); // Get the closest row

        const karyawan_ID = row.cells[0].textContent; // Get the karyawan_ID from the first cell

        // Send a request to delete the karyawan
        fetch(`${config.API_BASE_URL}/PHP/delete_karyawan.php`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ karyawan_ID: karyawan_ID }), // Convert the data object to JSON
        })
        .then(response => {
            if (response.ok) {
                row.remove(); // Remove the row from the table
                console.log('Karyawan deleted successfully');
            } else {
                console.error('Error deleting karyawan:', response.status);
            }
        })
        .catch(error => {
            console.error('Error:', error); // Handle fetch errors
        });
    });
});
});




