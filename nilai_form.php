<?php
// Koneksi database
$conn = new mysqli('localhost', 'root', '', 'sistem_magang');
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Proses form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nim = $_POST['nim'];
    $nilai_etika = $_POST['nilai_etika'];
    $nilai_magang = $_POST['nilai_magang'];
    
    // Cek apakah mahasiswa sudah memiliki nilai
    $check = $conn->prepare("SELECT ID_Nilai FROM Konversi_Nilai WHERE NIM = ?");
    $check->bind_param("s", $nim);
    $check->execute();
    $result = $check->get_result();
    
    if ($result->num_rows > 0) {
        // Update nilai yang sudah ada
        $sql = "UPDATE Konversi_Nilai SET Nilai_MK_Etika_Profesi = ?, Nilai_Magang = ? WHERE NIM = ?";
    } else {
        // Insert nilai baru
        $sql = "INSERT INTO Konversi_Nilai (NIM, Nilai_MK_Etika_Profesi, Nilai_Magang) VALUES (?, ?, ?)";
    }
    
    $stmt = $conn->prepare($sql);
    
    if ($result->num_rows > 0) {
        $stmt->bind_param("dds", $nilai_etika, $nilai_magang, $nim);
    } else {
        $stmt->bind_param("sdd", $nim, $nilai_etika, $nilai_magang);
    }
    
    if ($stmt->execute()) {
        $success = "Data nilai berhasil disimpan!";
    } else {
        $error = "Error: " . $stmt->error;
    }
}

// Ambil data mahasiswa untuk dropdown
$students = $conn->query("SELECT m.NIM, m.Nama_Mahasiswa, tm.Nama_Perusahaan 
                         FROM Mahasiswa m
                         LEFT JOIN Tempat_Magang tm ON m.ID_Lokasi_Magang = tm.ID_Lokasi_Magang
                         ORDER BY m.Nama_Mahasiswa");

// Ambil data nilai untuk ditampilkan
$grades = $conn->query("SELECT kn.*, m.Nama_Mahasiswa, tm.Nama_Perusahaan 
                       FROM Konversi_Nilai kn
                       JOIN Mahasiswa m ON kn.NIM = m.NIM
                       LEFT JOIN Tempat_Magang tm ON m.ID_Lokasi_Magang = tm.ID_Lokasi_Magang
                       ORDER BY m.Nama_Mahasiswa");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Konversi Nilai Magang</title>
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
        
        .grade-A { color: #27ae60; font-weight: bold; }
        .grade-B { color: #2ecc71; font-weight: bold; }
        .grade-C { color: #f39c12; font-weight: bold; }
        .grade-D { color: #e67e22; font-weight: bold; }
        .grade-E { color: #e74c3c; font-weight: bold; }
        
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
        
        .preview-grade {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
            margin-left: 10px;
        }
        
        .section-title {
            font-size: 1.3rem;
            color: var(--dark);
            margin: 30px 0 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #eee;
        }
        
        .student-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-top: 10px;
            border-left: 4px solid var(--company-color);
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
                <i class="fas fa-edit"></i>
                <div class="logo-text">
                    <h1>Form Nilai Magang</h1>
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
                <i class="fas fa-calculator"></i> Input Nilai Magang
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
            
            <form method="POST" onsubmit="return validateForm()">
                <div class="form-group">
                    <label for="nim">
                        <i class="fas fa-user-graduate"></i> Pilih Mahasiswa
                    </label>
                    <select id="nim" name="nim" required onchange="loadStudentData()">
                        <option value="">-- Pilih Mahasiswa --</option>
                        <?php while($student = $students->fetch_assoc()): ?>
                            <option value="<?= $student['NIM'] ?>">
                                <?= htmlspecialchars($student['NIM']) ?> - <?= htmlspecialchars($student['Nama_Mahasiswa']) ?>
                                <?= $student['Nama_Perusahaan'] ? ' (' . htmlspecialchars($student['Nama_Perusahaan']) . ')' : '' ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                    <div id="student_info" class="student-info"></div>
                </div>
                
                <div class="form-group">
                    <label for="nilai_etika">
                        <i class="fas fa-book"></i> Nilai Mata Kuliah Etika Profesi
                    </label>
                    <input type="number" id="nilai_etika" name="nilai_etika" min="0" max="100" step="0.01" 
                           oninput="updateGradePreview()" required placeholder="Masukkan nilai 0-100">
                    <span>Nilai Huruf: <span id="preview_etika" class="preview-grade">-</span></span>
                </div>
                
                <div class="form-group">
                    <label for="nilai_magang">
                        <i class="fas fa-briefcase"></i> Nilai Magang
                    </label>
                    <input type="number" id="nilai_magang" name="nilai_magang" min="0" max="100" step="0.01" 
                           oninput="updateGradePreview()" required placeholder="Masukkan nilai 0-100">
                    <span>Nilai Huruf: <span id="preview_magang" class="preview-grade">-</span></span>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-chart-line"></i> Rata-rata Nilai</label>
                    <span id="preview_avg" class="preview-grade">-</span>
                </div>
                
                <button type="submit" class="btn">
                    <i class="fas fa-save"></i> Simpan Nilai
                </button>
            </form>
        </div>
        
        <h3 class="section-title">
            <i class="fas fa-list-ol"></i> Daftar Nilai Mahasiswa
        </h3>
        
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIM</th>
                        <th>Nama Mahasiswa</th>
                        <th>Perusahaan</th>
                        <th>Nilai Etika</th>
                        <th>Nilai Magang</th>
                        <th>Rata-rata</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($grades->num_rows > 0): ?>
                        <?php $no = 1; ?>
                        <?php while($grade = $grades->fetch_assoc()): ?>
                            <?php
                            $avg = ($grade['Nilai_MK_Etika_Profesi'] + $grade['Nilai_Magang']) / 2;
                            $gradeClass = '';
                            if ($avg >= 85) $gradeClass = 'grade-A';
                            elseif ($avg >= 75) $gradeClass = 'grade-B';
                            elseif ($avg >= 65) $gradeClass = 'grade-C';
                            elseif ($avg >= 55) $gradeClass = 'grade-D';
                            else $gradeClass = 'grade-E';
                            ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($grade['NIM']) ?></td>
                                <td><?= htmlspecialchars($grade['Nama_Mahasiswa']) ?></td>
                                <td><?= htmlspecialchars($grade['Nama_Perusahaan']) ?></td>
                                <td class="<?= $grade['Nilai_MK_Etika_Profesi'] >= 85 ? 'grade-A' : 
                                              ($grade['Nilai_MK_Etika_Profesi'] >= 75 ? 'grade-B' :
                                              ($grade['Nilai_MK_Etika_Profesi'] >= 65 ? 'grade-C' :
                                              ($grade['Nilai_MK_Etika_Profesi'] >= 55 ? 'grade-D' : 'grade-E'))) ?>">
                                    <?= $grade['Nilai_MK_Etika_Profesi'] ?>
                                </td>
                                <td class="<?= $grade['Nilai_Magang'] >= 85 ? 'grade-A' : 
                                              ($grade['Nilai_Magang'] >= 75 ? 'grade-B' :
                                              ($grade['Nilai_Magang'] >= 65 ? 'grade-C' :
                                              ($grade['Nilai_Magang'] >= 55 ? 'grade-D' : 'grade-E'))) ?>">
                                    <?= $grade['Nilai_Magang'] ?>
                                </td>
                                <td class="<?= $gradeClass ?>">
                                    <?= number_format($avg, 2) ?>
                                </td>
                                <td>
                                    <button class="action-btn btn-edit" onclick="editGrade('<?= $grade['NIM'] ?>')">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" style="text-align: center;">
                                <i class="fas fa-info-circle"></i> Belum ada data nilai
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
    
    <script>
        function getGradeLetter(score) {
            if (score >= 85) return ['A', 'grade-A'];
            if (score >= 75) return ['B', 'grade-B'];
            if (score >= 65) return ['C', 'grade-C'];
            if (score >= 55) return ['D', 'grade-D'];
            return ['E', 'grade-E'];
        }
        
        function updateGradePreview() {
            const nilaiEtika = parseFloat(document.getElementById('nilai_etika').value) || 0;
            const nilaiMagang = parseFloat(document.getElementById('nilai_magang').value) || 0;
            
            const [hurufEtika, classEtika] = getGradeLetter(nilaiEtika);
            const [hurufMagang, classMagang] = getGradeLetter(nilaiMagang);
            
            document.getElementById('preview_etika').textContent = hurufEtika;
            document.getElementById('preview_etika').className = 'preview-grade ' + classEtika;
            
            document.getElementById('preview_magang').textContent = hurufMagang;
            document.getElementById('preview_magang').className = 'preview-grade ' + classMagang;
            
            const avg = (nilaiEtika + nilaiMagang) / 2;
            const [hurufAvg, classAvg] = getGradeLetter(avg);
            document.getElementById('preview_avg').textContent = hurufAvg + ' (' + avg.toFixed(2) + ')';
            document.getElementById('preview_avg').className = 'preview-grade ' + classAvg;
        }
        
        function loadStudentData() {
            const nim = document.getElementById('nim').value;
            if (!nim) return;
            
            fetch('get_student_data.php?nim=' + nim)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('student_info').innerHTML = `
                        <strong><i class="fas fa-user"></i> Mahasiswa:</strong> ${data.Nama_Mahasiswa}<br>
                        <strong><i class="fas fa-building"></i> Perusahaan:</strong> ${data.Nama_Perusahaan || 'Belum ada data'}
                    `;
                })
                .catch(error => console.error('Error:', error));
        }
        
        function editGrade(nim) {
            document.getElementById('nim').value = nim;
            loadStudentData();
            
            fetch('get_grade_data.php?nim=' + nim)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('nilai_etika').value = data.Nilai_MK_Etika_Profesi;
                    document.getElementById('nilai_magang').value = data.Nilai_Magang;
                    updateGradePreview();
                    window.scrollTo({top: 0, behavior: 'smooth'});
                })
                .catch(error => console.error('Error:', error));
        }
        
        function validateForm() {
            const nilaiEtika = parseFloat(document.getElementById('nilai_etika').value);
            const nilaiMagang = parseFloat(document.getElementById('nilai_magang').value);
            
            if (nilaiEtika < 0 || nilaiEtika > 100) {
                alert('Nilai Etika Profesi harus antara 0-100');
                return false;
            }
            
            if (nilaiMagang < 0 || nilaiMagang > 100) {
                alert('Nilai Magang harus antara 0-100');
                return false;
            }
            
            return true;
        }
    </script>
</body>
</html>