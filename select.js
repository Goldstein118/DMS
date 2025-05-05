fetch('http://localhost/Web_Project/DMS/API/karyawan_API.php')
    .then(response => {
        if (response.ok) {
            return response.json(); // Parse the JSON response
        } else {
            console.error('Error:', response.status); // Handle errors
        }
    }).then(data => { if (data) {document.getElementById('app1').textContent = JSON.stringify(data);}})
    
;

fetch('http://localhost/Web_Project/DMS/API/user_API.php')
    .then(response => {
        if (response.ok) {
            return response.json(); // Parse the JSON response
        } else {
            console.error('Error:', response.status); // Handle errors
        }
    }).then(data => { if (data) {document.getElementById('app2').textContent = JSON.stringify(data);}})
    
;

fetch('http://localhost/Web_Project/DMS/API/role_API.php')
    .then(response => {
        if (response.ok) {
            return response.json(); // Parse the JSON response
        } else {
            console.error('Error:', response.status); // Handle errors
        }
    }).then(data => { if (data) {document.getElementById('app3').textContent = JSON.stringify(data);}})
    
;
