<?php
// Koneksi database
$conn = new mysqli('localhost', 'root', '', 'sistem_magang');
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Proses form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_perusahaan = $_POST['nama_perusahaan'];
    $alamat = $_POST['alamat'];
    
    $sql = "INSERT INTO Tempat_Magang (Nama_Perusahaan, Alamat_Perusahaan) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $nama_perusahaan, $alamat);
    
    if ($stmt->execute()) {
        $success = "Data perusahaan berhasil disimpan!";
    } else {
        $error = "Error: " . $stmt->error;
    }
}

// Ambil data perusahaan untuk ditampilkan
$companies = $conn->query("SELECT * FROM Tempat_Magang ORDER BY Nama_Perusahaan");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Tempat Magang</title>
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
            --company-color: #9b59b6;
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
            background: linear-gradient(135deg, var(--company-color), #8e44ad);
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
        
        .logo-text p {
            font-size: 0.9rem;
            opacity: 0.9;
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
            border-bottom: 2px solid var(--company-color);
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
        
        input, textarea, select {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            font-family: 'Poppins', sans-serif;
            transition: border 0.3s ease;
        }
        
        textarea {
            min-height: 120px;
            resize: vertical;
        }
        
        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: var(--company-color);
        }
        
        .btn {
            display: inline-block;
            background: var(--company-color);
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
            background: #8e44ad;
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
            background: var(--company-color);
            color: white;
            font-weight: 500;
        }
        
        .data-table tr:nth-child(even) {
            background: #f9f9f9;
        }
        
        .data-table tr:hover {
            background: #f1f1f1;
        }
        
        .action-btn {
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-edit {
            background: var(--warning);
            color: white;
        }
        
        .btn-edit:hover {
            background: #e67e22;
        }
        
        .btn-delete {
            background: var(--danger);
            color: white;
        }
        
        .btn-delete:hover {
            background: #c0392b;
        }
        
        .section-title {
            font-size: 1.3rem;
            color: var(--dark);
            margin: 30px 0 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #eee;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .section-title i {
            color: var(--company-color);
        }
        
        .empty-state {
            text-align: center;
            padding: 30px;
            background: #f8f9fa;
            border-radius: 8px;
            margin-top: 20px;
        }
        
        .empty-state i {
            font-size: 3rem;
            color: var(--gray);
            margin-bottom: 15px;
        }
        
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
            
            .form-container {
                padding: 20px;
            }
            
            .data-table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container header-content">
            <div class="logo">
                <i class="fas fa-building"></i>
                <div class="logo-text">
                    <h1>Form Tempat Magang</h1>
                    <p>Institut Teknologi PLN</p>
                </div>
            </div>
            <nav>
                <a href="index_pj.php"><i class="fas fa-arrow-left"></i> Kembali</a>
            </nav>
        </div>
    </header>
    
    <main class="container">
        <div class="form-container">
            <h2 class="form-title">
                <i class="fas fa-plus-circle"></i> Tambah Tempat Magang Baru
            </h2>
            
            <?php if (isset($success)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?= $success ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> <?= $error ?>
                </div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="nama_perusahaan">
                        <i class="fas fa-building"></i> Nama Perusahaan
                    </label>
                    <input type="text" id="nama_perusahaan" name="nama_perusahaan" required placeholder="Masukkan nama perusahaan">
                </div>
                
                <div class="form-group">
                    <label for="alamat">
                        <i class="fas fa-map-marker-alt"></i> Alamat Perusahaan
                    </label>
                    <textarea id="alamat" name="alamat" required placeholder="Masukkan alamat lengkap perusahaan"></textarea>
                </div>
                
                <button type="submit" class="btn">
                    <i class="fas fa-save"></i> Simpan Data
                </button>
            </form>
        </div>
        
        <h3 class="section-title">
            <i class="fas fa-list-ul"></i> Daftar Tempat Magang
        </h3>
        
        <?php if ($companies->num_rows > 0): ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Perusahaan</th>
                        <th>Alamat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    <?php while($company = $companies->fetch_assoc()): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($company['Nama_Perusahaan']) ?></td>
                            <td><?= htmlspecialchars($company['Alamat_Perusahaan']) ?></td>
                            <td>
                                <button class="action-btn btn-edit">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="action-btn btn-delete">
                                    <i class="fas fa-trash-alt"></i> Hapus
                                </button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-building"></i>
                <p>Belum ada data tempat magang</p>
            </div>
        <?php endif; ?>
    </main>
    
    <script>
        // Function to handle edit button click
        function editCompany(id) {
            // Implement edit functionality here
            console.log("Editing company with ID:", id);
        }
        
        // Function to handle delete button click
        function deleteCompany(id) {
            if (confirm("Apakah Anda yakin ingin menghapus data ini?")) {
                // Implement delete functionality here
                console.log("Deleting company with ID:", id);
            }
        }
        
        // Attach event listeners to buttons
        document.addEventListener('DOMContentLoaded', function() {
            const editButtons = document.querySelectorAll('.btn-edit');
            const deleteButtons = document.querySelectorAll('.btn-delete');
            
            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const companyId = this.dataset.id;
                    editCompany(companyId);
                });
            });
            
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const companyId = this.dataset.id;
                    deleteCompany(companyId);
                });
            });
        });
    </script>
</body>
</html>