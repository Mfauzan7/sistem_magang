<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'sistem_magang');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to calculate grade letter
function getGradeLetter($score) {
    if ($score >= 85) return 'A';
    if ($score >= 75) return 'B';
    if ($score >= 65) return 'C';
    if ($score >= 55) return 'D';
    return 'E';
}

// Function to get grade color class
function getGradeColor($score) {
    if ($score >= 85) return 'grade-A';
    if ($score >= 75) return 'grade-B';
    if ($score >= 65) return 'grade-C';
    if ($score >= 55) return 'grade-D';
    return 'grade-E';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Nilai Magang Mahasiswa</title>
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
        
        .report-container {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }
        
        .report-title {
            font-size: 1.8rem;
            color: var(--dark);
            margin-bottom: 20px;
            text-align: center;
            padding-bottom: 15px;
            border-bottom: 2px solid #eee;
        }
        
        .filter-section {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            border-left: 4px solid var(--primary);
        }
        
        .filter-form {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: center;
        }
        
        .filter-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        label {
            font-weight: 500;
            color: var(--dark);
        }
        
        select, input {
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-family: 'Poppins', sans-serif;
        }
        
        button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background-color: var(--primary);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
        }
        
        .btn-secondary {
            background-color: var(--gray);
            color: white;
        }
        
        .btn-secondary:hover {
            background-color: #7f8c8d;
        }
        
        .action-buttons {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .btn-print {
            background-color: var(--secondary);
            color: white;
        }
        
        .btn-print:hover {
            background-color: #27ae60;
        }
        
        .btn-excel {
            background-color: #2ecc71;
            color: white;
        }
        
        .btn-excel:hover {
            background-color: #27ae60;
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        .data-table th, .data-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        .data-table th {
            background-color: var(--primary);
            color: white;
            font-weight: 500;
        }
        
        .data-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .data-table tr:hover {
            background-color: #f1f1f1;
        }
        
        .grade-A { color: #27ae60; font-weight: bold; }
        .grade-B { color: #2ecc71; font-weight: bold; }
        .grade-C { color: #f39c12; font-weight: bold; }
        .grade-D { color: #e67e22; font-weight: bold; }
        .grade-E { color: #e74c3c; font-weight: bold; }
        
        .stats-container {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-top: 30px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        
        .stat-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
        }
        
        .stat-title {
            font-size: 1rem;
            color: var(--gray);
            margin-bottom: 10px;
        }
        
        .stat-value {
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--primary);
        }
        
        .stat-detail {
            font-size: 0.9rem;
            color: var(--gray);
            margin-top: 5px;
        }
        
        @media print {
            .no-print {
                display: none;
            }
            body {
                font-size: 12pt;
                padding: 0;
            }
            .report-container {
                box-shadow: none;
                padding: 0;
            }
            .data-table {
                font-size: 10pt;
            }
        }
        
        @media (max-width: 768px) {
            .filter-form {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .filter-group {
                width: 100%;
            }
            
            select, input {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container header-content">
            <div class="logo">
                <i class="fas fa-chart-bar"></i>
                <div class="logo-text">
                    <h1>Laporan Nilai Magang</h1>
                </div>
            </div>
            <nav>
                <a href="index.php"><i class="fas fa-arrow-left"></i> Kembali</a>
            </nav>
        </div>
    </header>
    
    <main class="container">
        <div class="report-container">
            <h2 class="report-title">
                <i class="fas fa-file-alt"></i> Laporan Nilai Magang Mahasiswa
            </h2>
            
            <div class="filter-section no-print">
                <form method="get" class="filter-form">
                    <div class="filter-group">
                        <label for="perusahaan"><i class="fas fa-building"></i> Perusahaan:</label>
                        <select name="perusahaan" id="perusahaan">
                            <option value="">Semua Perusahaan</option>
                            <?php
                            $companies = $conn->query("SELECT ID_Lokasi_Magang, Nama_Perusahaan FROM Tempat_Magang");
                            while ($company = $companies->fetch_assoc()) {
                                $selected = (isset($_GET['perusahaan']) && $_GET['perusahaan'] == $company['ID_Lokasi_Magang']) ? 'selected' : '';
                                echo "<option value='{$company['ID_Lokasi_Magang']}' $selected>{$company['Nama_Perusahaan']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="min_nilai"><i class="fas fa-filter"></i> Nilai Minimal:</label>
                        <input type="number" name="min_nilai" id="min_nilai" min="0" max="100" 
                               value="<?= isset($_GET['min_nilai']) ? $_GET['min_nilai'] : '' ?>" placeholder="0-100">
                    </div>
                    
                    <div class="filter-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Filter
                        </button>
                        <button type="button" onclick="window.location.href='laporan_nilai.php'" class="btn btn-secondary">
                            <i class="fas fa-sync-alt"></i> Reset
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="action-buttons no-print">
                <button onclick="window.print()" class="btn btn-print">
                    <i class="fas fa-print"></i> Cetak Laporan
                </button>
                <button onclick="exportToExcel()" class="btn btn-excel">
                    <i class="fas fa-file-excel"></i> Export ke Excel
                </button>
            </div>
            
            <?php
            // Build the query with filters
            $query = "SELECT m.NIM, m.Nama_Mahasiswa, tm.Nama_Perusahaan, 
                             kn.Nilai_MK_Etika_Profesi, kn.Nilai_Magang
                      FROM Mahasiswa m
                      LEFT JOIN Konversi_Nilai kn ON m.NIM = kn.NIM
                      LEFT JOIN Tempat_Magang tm ON m.ID_Lokasi_Magang = tm.ID_Lokasi_Magang
                      WHERE 1=1";
            
            // Add filters if set
            if (isset($_GET['perusahaan']) && !empty($_GET['perusahaan'])) {
                $query .= " AND m.ID_Lokasi_Magang = " . intval($_GET['perusahaan']);
            }
            
            if (isset($_GET['min_nilai']) && !empty($_GET['min_nilai'])) {
                $min_nilai = floatval($_GET['min_nilai']);
                $query .= " AND (kn.Nilai_MK_Etika_Profesi >= $min_nilai OR kn.Nilai_Magang >= $min_nilai)";
            }
            
            $query .= " ORDER BY tm.Nama_Perusahaan, m.Nama_Mahasiswa";
            
            $result = $conn->query($query);
            
            if ($result->num_rows > 0) {
                // Calculate statistics
                $total_students = 0;
                $total_etika = 0;
                $total_magang = 0;
                
                // Store data for display and calculations
                $students = [];
                while ($row = $result->fetch_assoc()) {
                    $students[] = $row;
                    if (!is_null($row['Nilai_MK_Etika_Profesi'])) {
                        $total_etika += $row['Nilai_MK_Etika_Profesi'];
                    }
                    if (!is_null($row['Nilai_Magang'])) {
                        $total_magang += $row['Nilai_Magang'];
                    }
                    $total_students++;
                }
                
                $avg_etika = $total_students > 0 ? $total_etika / $total_students : 0;
                $avg_magang = $total_students > 0 ? $total_magang / $total_students : 0;
                $avg_total = ($avg_etika + $avg_magang) / 2;
            ?>
            
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIM</th>
                            <th>Nama Mahasiswa</th>
                            <th>Perusahaan</th>
                            <th>Etika Profesi</th>
                            <th>Nilai Magang</th>
                            <th>Rata-rata</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        foreach ($students as $student) {
                            $avg = (!is_null($student['Nilai_MK_Etika_Profesi']) && !is_null($student['Nilai_Magang'])) 
                                   ? ($student['Nilai_MK_Etika_Profesi'] + $student['Nilai_Magang']) / 2 
                                   : null;
                            
                            $etika_grade = !is_null($student['Nilai_MK_Etika_Profesi']) ? getGradeLetter($student['Nilai_MK_Etika_Profesi']) : '-';
                            $magang_grade = !is_null($student['Nilai_Magang']) ? getGradeLetter($student['Nilai_Magang']) : '-';
                            
                            echo "<tr>
                                    <td>$no</td>
                                    <td>{$student['NIM']}</td>
                                    <td>{$student['Nama_Mahasiswa']}</td>
                                    <td>{$student['Nama_Perusahaan']}</td>
                                    <td class='".(!is_null($student['Nilai_MK_Etika_Profesi']) ? getGradeColor($student['Nilai_MK_Etika_Profesi']) : '')."'>
                                        ".(!is_null($student['Nilai_MK_Etika_Profesi']) ? $student['Nilai_MK_Etika_Profesi']." ($etika_grade)" : '-')."
                                    </td>
                                    <td class='".(!is_null($student['Nilai_Magang']) ? getGradeColor($student['Nilai_Magang']) : '')."'>
                                        ".(!is_null($student['Nilai_Magang']) ? $student['Nilai_Magang']." ($magang_grade)" : '-')."
                                    </td>
                                    <td class='".(!is_null($avg) ? getGradeColor($avg) : '')."'>
                                        ".(!is_null($avg) ? number_format($avg, 2)." (".getGradeLetter($avg).")" : '-')."
                                    </td>
                                  </tr>";
                            $no++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            
            <div class="stats-container no-print">
                <div class="stat-card">
                    <div class="stat-title">Total Mahasiswa</div>
                    <div class="stat-value"><?= $total_students ?></div>
                    <div class="stat-detail">yang terdaftar dalam sistem</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-title">Rata-rata Etika Profesi</div>
                    <div class="stat-value <?= getGradeColor($avg_etika) ?>"><?= number_format($avg_etika, 2) ?></div>
                    <div class="stat-detail">Nilai huruf: <?= getGradeLetter($avg_etika) ?></div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-title">Rata-rata Magang</div>
                    <div class="stat-value <?= getGradeColor($avg_magang) ?>"><?= number_format($avg_magang, 2) ?></div>
                    <div class="stat-detail">Nilai huruf: <?= getGradeLetter($avg_magang) ?></div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-title">Rata-rata Total</div>
                    <div class="stat-value <?= getGradeColor($avg_total) ?>"><?= number_format($avg_total, 2) ?></div>
                    <div class="stat-detail">Nilai huruf: <?= getGradeLetter($avg_total) ?></div>
                </div>
            </div>
            
            <?php
            } else {
                echo "<div style='padding: 20px; text-align: center; background: #f8f9fa; border-radius: 8px;'>
                        <i class='fas fa-info-circle' style='font-size: 2rem; color: var(--gray); margin-bottom: 15px;'></i>
                        <p style='font-size: 1.1rem;'>Tidak ada data nilai yang ditemukan</p>
                      </div>";
            }
            ?>
        </div>
    </main>
    
    <script>
        // Function to export to Excel
        function exportToExcel() {
            let html = document.querySelector('.table-responsive').innerHTML;
            let blob = new Blob([`<table>${html}</table>`], {type: 'application/vnd.ms-excel'});
            let a = document.createElement('a');
            a.href = URL.createObjectURL(blob);
            a.download = 'laporan_nilai_magang.xls';
            a.click();
        }
    </script>
</body>
</html>