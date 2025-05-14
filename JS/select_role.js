import config from "../JS/config.js";

$(document).ready(function () {
    $('#table_role').DataTable({
        paging: false,
        searching: false,
        ordering: false,
        info: false,
        language: {
            emptyTable: '',
            zeroRecords: '',
        },
    });

    fetchRoles();
});

async function fetchRoles() {
    try {
        const response = await fetch(`${config.API_BASE_URL}/PHP/API/role_API.php`);
        if (!response.ok) {
            throw new Error(`Failed to fetch roles. Status: ${response.status}`);
        }
        const roles = await response.json();
        populateRoleTable(roles);
    } catch (error) {
        console.error('Error fetching roles:', error);
        toastr.error('Failed to load roles.');
    }
}

function populateRoleTable(roles) {
    const tableBody = document.getElementById('role_table_body');
    tableBody.innerHTML = ''; // Clear existing rows

    roles.forEach(role => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${role.role_ID}</td>
            <td>${role.nama}</td>
            <td>${role.akses}</td>
            <td>
                <button class="delete_role">Delete</button>
                <button class="update_role">Update</button>
            </td>
        `;
        tableBody.appendChild(row);
    });

    attachEventListeners();
}

function attachEventListeners() {
    document.getElementById('role_table_body').addEventListener('click', function (event) {
        if (event.target.classList.contains('delete_role')) {
            handleDeleteRole(event.target);
        } else if (event.target.classList.contains('update_role')) {
            handleUpdateRole(event.target);
        }
    });
}

async function handleDeleteRole(button) {
    const row = button.closest('tr');
    const roleId = row.cells[0].textContent;

    try {
        const response = await fetch(`${config.API_BASE_URL}/PHP/delete_role.php`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ role_ID: roleId }),
        });

        if (response.ok) {
            row.remove();
            toastr.success('Role deleted successfully.');
        } else {
            throw new Error(`Failed to delete role. Status: ${response.status}`);
        }
    } catch (error) {
        console.error('Error deleting role:', error);
        toastr.error('Failed to delete role.');
    }
}

function handleUpdateRole(button) {
    const row = button.closest('tr');
    const role_ID = row.cells[0].textContent;
    const currentNama = row.cells[1].textContent;
    const currentAkses = row.cells[2].textContent;

    const updateDiv = document.getElementById('toggleDiv_role_update');
    updateDiv.classList.toggle('hidden_role_update');

    document.getElementById('update_role_ID').value = role_ID;
    document.getElementById('update_role_name').value = currentNama;
    document.getElementById('update_role_akses').value = currentAkses;

    window.currentRow = row;
}

document.getElementById('submit_role_update').addEventListener('click', async function () {
    if (!window.currentRow) {
        toastr.error('No row selected for update.');
        return;
    }

    const row = window.currentRow;
    const role_ID = document.getElementById('update_role_ID').value;
    const newNama = document.getElementById('update_role_name').value;
    const newAkses = document.getElementById('update_role_akses').value;

    try {
        const response = await fetch(`${config.API_BASE_URL}/PHP/update_role.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                role_ID: role_ID,
                nama: newNama,
                akses: newAkses,
            }),
        });

        if (response.ok) {
            row.cells[1].textContent = newNama;
            row.cells[2].textContent = newAkses;

            const updateDiv = document.getElementById('toggleDiv_role_update');
            updateDiv.classList.add('hidden_role_update');

            toastr.success('Role updated successfully.');
        } else {
            throw new Error(`Failed to update role. Status: ${response.status}`);
        }
    } catch (error) {
        console.error('Error updating role:', error);
        toastr.error('Failed to update role.');
    }
});