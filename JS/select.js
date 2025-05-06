import config from "../JS/config.js"; 
$(document).ready(function() {
    $('#table_karyawan').DataTable({paging: false,searching: false,ordering:false,info: false,  language: {
        emptyTable: '',
        zeroRecords: '',
    }});
    $('#table_role').DataTable({paging: false,searching: false,ordering:false,info: false,language: {
        emptyTable: '',zeroRecords: '',
    }});
    $('#table_user').DataTable({paging: false,searching: false,ordering:false,info: false,language: {
        emptyTable: '',zeroRecords: '',
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
                <td>${karyawan.karyawan_ID}</td>
                <td>${karyawan.role_ID}</td>
                <td>${karyawan.divisi}</td>
                <td>${karyawan.noTelp}</td>
                <td>${karyawan.alamat}</td>
                <td>${karyawan.KTP_NPWP}</td>
                <td><button id="delete_karyawan" class ="delete_button">Delete</button>
                    <button id="update_karyawan" class="update_button">Update</button>
                </td>
            `;

            tableBody.appendChild(row);

        }
    
    );
    })
    .catch(error => console.error('Error fetching user data:', error));
    
;

fetch(`${config.API_BASE_URL}/PHP/API/user_API.php`)
    .then(response => {
        if (response.ok) {
            return response.json(); // Parse the JSON response
        } else {
            console.error('Error:', response.status); // Handle errors
        }
    })
    .then(users => {
        const tableBody = document.getElementById('user_table_body');

        users.forEach(user => {
            const row = document.createElement('tr');

            row.innerHTML = `
                <td>${user.user_ID}</td>
                <td>${user.karyawan_ID}</td>
                <td><button id="delete_user" class ="delete_button">Delete</button>
                    <button id="update_user" class="update_button">Update</button>
                </td>
            `;

            tableBody.appendChild(row);
        });
    })
    .catch(error => console.error('Error fetching user data:', error));
    
;

fetch(`${config.API_BASE_URL}/PHP/API/role_API.php`)
    .then(response => {
        if (response.ok) {
            return response.json(); // Parse the JSON response
        } else {
            console.error('Error:', response.status); // Handle errors
        }
    }) .then(role => {
        const tableBody = document.getElementById('role_table_body');

        role.forEach(role => {
            const row = document.createElement('tr');

            row.innerHTML = `
                <td>${role.role_ID}</td>
                <td>${role.nama}</td>
                <td>${role.akses}</td>
                <td><button id="delete_role" class ="delete_button">Delete</button>
                    <button id="update_role" class="update_button">Update</button>
                </td>

            `;

            tableBody.appendChild(row);
        });
    })
    .catch(error => console.error('Error fetching user data:', error));
    
;
