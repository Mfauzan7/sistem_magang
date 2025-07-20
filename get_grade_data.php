<?php
header('Content-Type: application/json');

$conn = new mysqli('localhost', 'root', '', 'sistem_magang');
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$nim = $_GET['nim'] ?? '';

$query = "SELECT * FROM Konversi_Nilai WHERE NIM = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $nim);
$stmt->execute();
$result = $stmt->get_result();

echo json_encode($result->fetch_assoc() ?: ['Nilai_MK_Etika_Profesi' => 0, 'Nilai_Magang' => 0]);
?>