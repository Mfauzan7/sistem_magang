<?php
session_start();

// Database connection
$host = "127.0.0.1";
$username = "root";
$password = "";
$database = "sistem_magang";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get students interning at this company
$students_query = "SELECT m.*, 
                  CASE 
                      WHEN CURDATE() BETWEEN m.Mulai_Magang AND m.Selesai_Magang THEN 'Aktif'
                      WHEN CURDATE() > m.Selesai_Magang THEN 'Selesai'
                      ELSE 'Belum Mulai'
                  END AS Status_Magang
                  FROM mahasiswa m
                  ORDER BY m.Mulai_Magang DESC";
$students_result = $conn->query($students_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Mahasiswa - Sistem Informasi Magang</title>
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
            --company-color: #9b59b6; /* Purple accent for PJ */
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
            position: relative;
            z-index: 10;
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
            margin-bottom: 5px;
        }
        
        .logo-text p {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        .company-badge {
            background: rgba(255, 255, 255, 0.2);
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        nav ul {
            display: flex;
            list-style: none;
            gap: 15px;
        }
        
        nav a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            padding: 8px 15px;
            border-radius: 5px;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }
        
        nav a:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }
        
        .auth-buttons {
            display: flex;
            gap: 10px;
        }
        
        .btn {
            padding: 8px 20px;
            border-radius: 5px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            text-decoration: none;
            display: inline-block;
            font-size: 0.9rem;
        }
        
        .btn-outline {
            background: transparent;
            border: 2px solid white;
            color: white;
        }
        
        .btn-outline:hover {
            background: white;
            color: var(--company-color);
        }
        
        main {
            padding: 30px 0;
        }
        
        .dashboard-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 20px;
        }
        
        .dashboard-title h2 {
            font-size: 1.8rem;
            color: var(--dark);
        }
        
        .dashboard-title p {
            color: var(--gray);
        }
        
        .welcome-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
            border-left: 4px solid var(--company-color);
        }
        
        .section-title {
            font-size: 1.3rem;
            margin-bottom: 20px;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .section-title i {
            color: var(--company-color);
        }
        
        .student-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }
        
        .student-table th, 
        .student-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        .student-table th {
            background-color: var(--company-color);
            color: white;
            font-weight: 500;
        }
        
        .student-table tr:hover {
            background-color: #f9f9f9;
        }
        
        .student-table tr:last-child td {
            border-bottom: none;
        }
        
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .status-active {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-completed {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .status-upcoming {
            background-color: #e2e3e5;
            color: #383d41;
        }
        
        .action-link {
            display: inline-block;
            padding: 6px 12px;
            background: var(--company-color);
            color: white;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 500;
            transition: background 0.3s ease;
            font-size: 0.85rem;
            margin-right: 5px;
        }
        
        .action-link:hover {
            background: #8e44ad;
        }
        
        .action-link.view {
            background: var(--primary);
        }
        
        .action-link.view:hover {
            background: var(--primary-dark);
        }
        
        .action-link.edit {
            background: var(--warning);
        }
        
        .action-link.edit:hover {
            background: #e0a800;
        }
        
        .search-filter {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .search-box {
            position: relative;
            flex-grow: 1;
            max-width: 400px;
        }
        
        .search-box input {
            width: 100%;
            padding: 10px 15px 10px 40px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 0.9rem;
        }
        
        .search-box i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray);
        }
        
        .filter-select {
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 0.9rem;
            background: white;
        }
        
        footer {
            background: var(--dark);
            color: white;
            padding: 25px 0;
            text-align: center;
            margin-top: 50px;
        }
        
        .footer-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
        }
        
        .footer-links {
            display: flex;
            gap: 20px;
        }
        
        .footer-links a {
            color: white;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .footer-links a:hover {
            color: var(--company-color);
        }
        
        .copyright {
            font-size: 0.85rem;
            opacity: 0.8;
        }
        
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
            
            nav ul {
                flex-wrap: wrap;
                justify-content: center;
            }
            
            .student-table {
                display: block;
                overflow-x: auto;
            }
            
            .search-filter {
                flex-direction: column;
                align-items: stretch;
            }
            
            .search-box {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container header-content">
            <div class="logo">
                <i class="fas fa-user-tie"></i>
                <div class="logo-text">
                    <h1>Dashboard PJ Magang</h1>
                    <p>Institut Teknologi PLN</p>
                </div>
            </div>
            
            <nav>
                <ul>
                    <li><a href="index_pj.php"><i class="fas fa-home"></i> Beranda</a></li>
                    <li><a href="nilai_form.php"><i class="fas fa-edit"></i> Input Nilai</a></li>
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>
    
    <main class="container">
        <div class="dashboard-title">
            <div>
                <h2>Daftar Mahasiswa Magang</h2>
                <p>Data Mahasiswa magang Institut Teknologi PLN</p>
            </div>
        </div>
        
        <div class="welcome-card">
            <div class="section-title">
                <i class="fas fa-users"></i>
                <span>Daftar Mahasiswa</span>
            </div>
            
            <div class="search-filter">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" placeholder="Cari mahasiswa...">
                </div>
                <select class="filter-select" id="statusFilter">
                    <option value="all">Semua Status</option>
                    <option value="active">Aktif</option>
                    <option value="completed">Selesai</option>
                    <option value="upcoming">Belum Mulai</option>
                </select>
            </div>
            
            <table class="student-table">
                <thead>
                    <tr>
                        <th>Nama Mahasiswa</th>
                        <th>NIM</th>
                        <th>Mulai Magang</th>
                        <th>Selesai Magang</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($students_result->num_rows > 0): ?>
                        <?php while($student = $students_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($student['Nama_Mahasiswa']); ?></td>
                                <td><?php echo htmlspecialchars($student['NIM']); ?></td>
                                <td><?php echo date('d M Y', strtotime($student['Mulai_Magang'])); ?></td>
                                <td><?php echo date('d M Y', strtotime($student['Selesai_Magang'])); ?></td>
                                <td>
                                    <?php 
                                    $status_class = '';
                                    if ($student['Status_Magang'] == 'Aktif') {
                                        $status_class = 'status-active';
                                    } elseif ($student['Status_Magang'] == 'Selesai') {
                                        $status_class = 'status-completed';
                                    } else {
                                        $status_class = 'status-upcoming';
                                    }
                                    ?>
                                    <span class="status-badge <?php echo $status_class; ?>">
                                        <?php echo htmlspecialchars($student['Status_Magang']); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="nilai_form.php?nim=<?php echo $student['NIM']; ?>" class="action-link edit"><i class="fas fa-edit"></i> Nilai</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center;">Tidak ada mahasiswa yang sedang magang di perusahaan ini.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
    
    <footer>
        <div class="container footer-content">
            <p class="copyright">&copy; <?php echo date('Y'); ?> Sistem Informasi Magang. Hak Cipta Dilindungi.</p>
        </div>
    </footer>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Search functionality
            const searchInput = document.getElementById('searchInput');
            const studentRows = document.querySelectorAll('.student-table tbody tr');
            
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                
                studentRows.forEach(row => {
                    if (row.cells.length > 1) { // Skip the "no data" row
                        const studentName = row.cells[0].textContent.toLowerCase();
                        const studentNIM = row.cells[1].textContent.toLowerCase();
                        
                        if (studentName.includes(searchTerm) || studentNIM.includes(searchTerm)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    }
                });
            });
            
            // Filter functionality
            const statusFilter = document.getElementById('statusFilter');
            
            statusFilter.addEventListener('change', function() {
                const filterValue = this.value;
                
                studentRows.forEach(row => {
                    if (row.cells.length > 1) { // Skip the "no data" row
                        const status = row.cells[5].textContent.toLowerCase();
                        
                        if (filterValue === 'all' || 
                            (filterValue === 'active' && status.includes('aktif')) || 
                            (filterValue === 'completed' && status.includes('selesai')) ||
                            (filterValue === 'upcoming' && status.includes('belum mulai'))) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>
<?php
$conn->close();
?>