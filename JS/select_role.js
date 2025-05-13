import config from "../JS/config.js"; 
$(document).ready(function() {
    $('#table_role').DataTable({paging: false,searching: false,ordering:false,info: false,language: {
        emptyTable: '',zeroRecords: '',
    }});
});

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
                <td><button  class ="delete_role">Delete</button>
                    <button id="update_role" class="update_role">Update</button>
                </td>

            `;

            tableBody.appendChild(row);
        });
        document.querySelectorAll('.delete_role').forEach(button => {
        button.addEventListener('click', function() {
            const row = this.closest('tr'); // Get the closest row
            const roleId = row.cells[0].textContent; // Get the role ID from the first cell

            // Send a request to delete the role
            fetch(`${config.API_BASE_URL}/PHP/delete_role.php`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({role_ID: roleId }), // Convert the data object to JSON
            })

        .then(response => {
            if (response.ok) {
                row.remove(); 
            } else {
                console.error('Error deleting role:', response.status);
            }
        })
        .catch(error => console.error('Error:', error));
        });
    });
});

