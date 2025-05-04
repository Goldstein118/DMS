fetch('http://localhost/DMS/karyawan_API.php')
    .then(response => {
        if (response.ok) {
            return response.json(); // Parse the JSON response
        } else {
            console.error('Error:', response.status); // Handle errors
        }
    }).then(data => { if (data) {document.getElementById('app').textContent = JSON.stringify(data);}})
    
;