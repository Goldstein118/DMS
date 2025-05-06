<?php
include('db.php');
header('Access-Control-Allow-Origin: *'); // Allow requests from any origin
header('Access-Control-Allow-Methods: GET, POST, OPTIONS'); // Allow specific HTTP methods
header('Access-Control-Allow-Headers: Content-Type'); // Allow specific headers
header('Content-Type: application/json');
$search = $_GET['search'] ?? '';

// Fetch roles from the database based on the search term
$query = "SELECT role_ID, nama FROM tb_role WHERE nama LIKE ?";
$stmt = $conn->prepare($query);
$likeSearch = "%" . $search . "%";
$stmt->bind_param("s", $likeSearch);
$stmt->execute();
$result = $stmt->get_result();

$roles = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $roles[] = [
            'role_id' => $row['role_ID'],
            'role_text' => $row['nama']
        ];
    }
}

echo json_encode($roles);
$stmt->close();
?>