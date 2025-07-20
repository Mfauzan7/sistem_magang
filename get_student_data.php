<?php
header('Content-Type: application/json');

$conn = new mysqli('localhost', 'root', '', 'sistem_magang');
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$nim = $_GET['nim'] ?? '';

$query = "SELECT m.Nama_Mahasiswa, tm.Nama_Perusahaan 
          FROM Mahasiswa m
          LEFT JOIN Tempat_Magang tm ON m.ID_Lokasi_Magang = tm.ID_Lokasi_Magang
          WHERE m.NIM = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $nim);
$stmt->execute();
$result = $stmt->get_result();

echo json_encode($result->fetch_assoc() ?: ['Nama_Mahasiswa' => '', 'Nama_Perusahaan' => '']);
?>