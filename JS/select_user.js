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
                <td>${user.user_id}</td>
                <td>${user.karyawan_id}</td>
                <td><button class="delete_user">Delete</button>
                    <button class="update_user">Update</button>
                </td>
            `;

            tableBody.appendChild(row);},
            attachEventListeners());

function attachEventListeners() {
    document.getElementById('user_table_body').addEventListener('click', function(event) {
        if (event.target.classList.contains('delete_user')) {
            handleDeleteUser(event.target);
        } else if (event.target.classList.contains('update_user')) {
            handleUpdateUser(event.target);
            }
    })
}
async function handleDeleteUser(button) {
        const row = button.closest('tr'); // Get the closest row
        const userID = row.cells[0].textContent; // Get the role ID from the first cell
        try{
           const response=  await fetch(`${config.API_BASE_URL}/PHP/delete_user.php`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ user_id: userID }), // Convert the data object to JSON
        })

        if (response.ok) {
            row.remove(); 
            toastr.success('User deleted successfully.',{
            timeOut: 500,
            extendedTimeOut: 500,});

        } else {
            throw new Error(`Failed to delete role. Status: ${response.status}`,
            toastr.error('Failed to delete user.',{
            timeOut: 500,extendedTimeOut: 500})
        )}
        
        }
        catch(error){
        console.error('Error deleting role:', error);
        toastr.error('Failed to delete role.'),{
            timeOut: 500,
            extendedTimeOut: 500,
        };
        }
}
});

function handleUpdateUser(button) {
    const row = button.closest('tr');
    const userID = row.cells[0].textContent;
    const karyawanID = row.cells[1].textContent;


    const updatediv=document.getElementById('toggleDiv_user_update');
    updatediv.classList.toggle('hidden_user_update');

    document.getElementById('update_user_ID').value = userID;
    const karyawan_ID_field = $('#update_karyawan_ID');
    fetch(`${config.API_BASE_URL}/PHP/API/karyawan_API.php`)
        .then(response=> response.json())
        .then(karyawans=>{
            karyawan_ID_field.empty();
            karyawans.forEach(karyawan=> {
                const option = new Option(`${karyawan.karyawan_id} - ${karyawan.nama}`, karyawan.karyawan_id, karyawan.karyawan_id === karyawanID);
                karyawan_ID_field.append(option);
            });
            karyawan_ID_field.trigger('change');
        })
        .catch(error=> {
            console.error('Error fetching karyawan:', error);
        });
    window.currentRow= row;
}

document.getElementById('submit_user_update').addEventListener('click', async function() {
    if (!window.currentRow)
        {
            toastr.error('No row selected for update.', {
                timeOut: 500,
                extendedTimeOut: 500,
            });
            return;
        }
    const row = window.currentRow;
    const User_ID = document.getElementById('update_user_ID').value;
    const karyawan_ID_new = $('#update_karyawan_ID').val();

    try{
        const response = await fetch(`${config.API_BASE_URL}/PHP/update_user.php`,{
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                user_id: User_ID,
                karyawan_id: karyawan_ID_new,
            }),
        });
        if (response.ok) {
            row.cells[1].textContent = karyawan_ID_new;
            toastr.success('User updated successfully.', {
                timeOut: 500,
                extendedTimeOut: 500,
            });
            const updatediv=document.getElementById('toggleDiv_user_update');
            updatediv.classList.toggle('hidden_user_update');
        } else {
            throw new Error(`Failed to update user. Status: ${response.status}`);
        }
    }catch(error){
        console.error('Error updating user:', error);
        toastr.error('Failed to update user.', {
            timeOut: 500,
            extendedTimeOut: 500,
        });
    }
    
})