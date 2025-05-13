import config from "../JS/config.js"; 
$(document).ready(function() {
    $('#table_user').DataTable({paging: false,searching: false,ordering:false,info: false,language: {
        emptyTable: '',zeroRecords: '',
    }});
});

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
                <td><button class="delete_user">Delete</button>
                    <button class="update_user">Update</button>
                </td>
            `;

            tableBody.appendChild(row);
});
document.querySelectorAll('.delete_user').forEach(button => {
    button.addEventListener('click', function() {
        const row = this.closest('tr'); // Get the closest row
        const userID = row.cells[0].textContent; // Get the role ID from the first cell

        // Send a request to delete the role
        fetch(`${config.API_BASE_URL}/PHP/delete_user.php`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ user_ID: userID }), // Convert the data object to JSON
        })
        .then(response => {
            if (response.ok) {
                row.remove(); 
            } else {
                console.error('Error deleting role:', response.status);
            }
        })
        .catch(error => console.error('Error fetching user data:', error));
    });
});
});