<?php
include('db.php');
$search = $_GET['search'] ?? '';

// Fetch roles from the database based on the search term
$query = "SELECT role_ID, nama FROM tb_role WHERE nama LIKE ?";
$stmt = $conn->prepare($query);
$searchTerm = '%' . $search . '%';
$stmt->bind_param('s', $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

$roles = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $roles[] = [
            'id' => $row['role_ID'], // Use role_ID as the value
            'text' => $row['nama'],  // Use nama as the display text
        ];
    }
}

echo json_encode($roles);
$conn->close();
?>