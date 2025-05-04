document.getElementById('create').addEventListener('click', function () {
    const div = document.getElementById('toggleDiv');
    div.classList.toggle('hidden'); // Toggles the 'hidden' class
});
document.getElementById('submit').addEventListener('click', function () {
    // Collect form data
    const id = document.getElementById('id').value;
    const name = document.getElementById('name').value;
    const divisi = document.getElementById('divisi').value;
    const phone = document.getElementById('phone').value;
    const address = document.getElementById('address').value;
    const nik = document.getElementById('nik').value;

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
