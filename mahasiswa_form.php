<?php
// Koneksi ke Database
$conn = new mysqli('localhost', 'root', '', 'sistem_magang');
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Pengaturan form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nim = $_POST['nim'];
    $nama = $_POST['nama'];
    $lokasi = $_POST['lokasi'];
    $mulai = $_POST['mulai'];
    $selesai = $_POST['selesai'];
    $telp = $_POST['telp'];
    
    $sql = "INSERT INTO Mahasiswa VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssisss", $nim, $nama, $lokasi, $mulai, $selesai, $telp);
    
    if ($stmt->execute()) {
        $success_message = "Data mahasiswa berhasil disimpan!";
    } else {
        $error_message = "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Mahasiswa Magang</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary: #3498db;
            --primary-dark: #2980b9;
            --secondary: #2ecc71;
            --danger: #e74c3c;
            --warning: #f39c12;
            --light: #ecf0f1;
            --dark: #2c3e50;
            --gray: #95a5a6;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
            color: var(--dark);
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 20px 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .logo i {
            font-size: 2rem;
        }
        
        .logo-text h1 {
            font-size: 1.5rem;
            font-weight: 600;
        }
        
        nav a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            padding: 8px 15px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        
        nav a:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }
        
        .form-container {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }
        
        .form-title {
            font-size: 1.5rem;
            color: var(--dark);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--primary);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark);
        }
        
        input, select {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            font-family: 'Poppins', sans-serif;
            transition: border 0.3s ease;
        }
        
        input:focus, select:focus {
            outline: none;
            border-color: var(--primary);
        }
        
        .btn {
            display: inline-block;
            background: var(--primary);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            transition: background 0.3s ease;
        }
        
        .btn:hover {
            background: var(--primary-dark);
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-size: 16px;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        .data-table th, .data-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        .data-table th {
            background: var(--primary);
            color: white;
            font-weight: 500;
        }
        
        .data-table tr:nth-child(even) {
            background: #f9f9f9;
        }
        
        .data-table tr:hover {
            background: #f1f1f1;
        }
        
        .section-title {
            font-size: 1.3rem;
            color: var(--dark);
            margin: 30px 0 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #eee;
        }
        
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 15px;
            }
            
            .form-container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container header-content">
            <div class="logo">
                <i class="fas fa-user-graduate"></i>
                <div class="logo-text">
                    <h1>Sistem Informasi Magang</h1>
                </div>
            </div>
            <nav>
                <a href="index.php"><i class="fas fa-home"></i> Kembali ke Beranda</a>
            </nav>
        </div>
    </header>
    
    <main class="container">
        <div class="form-container">
            <h2 class="form-title">Form Pendaftaran Mahasiswa Magang</h2>
            
            <?php if (isset($success_message)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?= $success_message ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($error_message)): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> <?= $error_message ?>
                </div>
            <?php endif; ?>
            
            <form method="post">
                <div class="form-group">
                    <label for="nim">NIM Mahasiswa</label>
                    <input type="text" id="nim" name="nim" required placeholder="Masukkan NIM">
                </div>
                
                <div class="form-group">
                    <label for="nama">Nama Mahasiswa</label>
                    <input type="text" id="nama" name="nama" required placeholder="Masukkan nama lengkap">
                </div>
                
                <div class="form-group">
                    <label for="lokasi">Tempat Magang</label>
                    <select id="lokasi" name="lokasi" required>
                        <option value="">-- Pilih Perusahaan --</option>
                        <?php
                        $result = $conn->query("SELECT * FROM Tempat_Magang");
                        while($row = $result->fetch_assoc()) {
                            echo "<option value='{$row['ID_Lokasi_Magang']}'>{$row['Nama_Perusahaan']}</option>";
                        }
                        ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="mulai">Tanggal Mulai Magang</label>
                    <input type="date" id="mulai" name="mulai" required>
                </div>
                
                <div class="form-group">
                    <label for="selesai">Tanggal Selesai Magang</label>
                    <input type="date" id="selesai" name="selesai" required>
                </div>
                
                <div class="form-group">
                    <label for="telp">Nomor Telepon</label>
                    <input type="text" id="telp" name="telp" placeholder="Masukkan nomor telepon">
                </div>
                
                <button type="submit" class="btn">
                    <i class="fas fa-save"></i> Simpan Data
                </button>
            </form>
        </div>
        
        <h3 class="section-title">
            <i class="fas fa-list-alt"></i> Data Nilai Mahasiswa
        </h3>
        
        <table class="data-table">
            <thead>
                <tr>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Nilai Etika Profesi</th>
                    <th>Nilai Magang</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT m.NIM, m.Nama_Mahasiswa, kn.Nilai_MK_Etika_Profesi, kn.Nilai_Magang 
                                       FROM Mahasiswa m LEFT JOIN Konversi_Nilai kn ON m.NIM = kn.NIM");
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['NIM']}</td>
                                <td>{$row['Nama_Mahasiswa']}</td>
                                <td>".($row['Nilai_MK_Etika_Profesi'] ?? '-')."</td>
                                <td>".($row['Nilai_Magang'] ?? '-')."</td>
                              </tr>";
                    }
                } else {
                    echo "<tr>
                            <td colspan='4' style='text-align: center;'>Tidak ada data nilai</td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </main>
</body>
</html>