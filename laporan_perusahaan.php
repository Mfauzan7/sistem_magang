<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'sistem_magang');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to get active interns count
function getActiveInternsCount($conn, $company_id) {
    $current_date = date('Y-m-d');
    $sql = "SELECT COUNT(*) as count FROM Mahasiswa 
            WHERE ID_Lokasi_Magang = ? AND Mulai_Magang <= ? AND Selesai_Magang >= ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $company_id, $current_date, $current_date);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc()['count'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Perusahaan Tempat Magang</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 20px;
            color: #333;
        }
        h1 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 10px;
            border-bottom: 2px solid #3498db;
        }
        .report-container {
            background-color: #f9f9f9;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 14px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #3498db;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #e9e9e9;
        }
        .filter-section {
            background-color: #eaf2f8;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: center;
        }
        .filter-group {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        label {
            font-weight: 600;
            color: #2c3e50;
        }
        select, input {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        button {
            padding: 8px 16px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #2980b9;
        }
        .action-buttons {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        .print-btn {
            background-color: #27ae60;
        }
        .print-btn:hover {
            background-color: #219653;
        }
        .export-btn {
            background-color: #e67e22;
        }
        .export-btn:hover {
            background-color: #d35400;
        }
        .stats-container {
            margin-top: 30px;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .stat-card {
            background-color: white;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            flex: 1;
            min-width: 200px;
        }
        .stat-card h3 {
            margin-top: 0;
            color: #2c3e50;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        .stat-value {
            font-size: 24px;
            font-weight: bold;
            color: #3498db;
            margin: 10px 0;
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
            table {
                font-size: 10pt;
            }
            h1 {
                font-size: 18pt;
            }
        }
    </style>
</head>
<body>
    <div class="report-container">
        <h1>Laporan Perusahaan Tempat Magang</h1>
        
        <div class="filter-section no-print">
            <form method="get" id="filterForm">
                <div class="filter-group">
                    <label for="status">Status Perusahaan:</label>
                    <select name="status" id="status">
                        <option value="all">Semua Perusahaan</option>
                        <option value="active" <?= (isset($_GET['status']) && $_GET['status'] == 'active') ? 'selected' : '' ?>>Aktif (Memiliki Mahasiswa)</option>
                        <option value="inactive" <?= (isset($_GET['status']) && $_GET['status'] == 'inactive') ? 'selected' : '' ?>>Tidak Aktif</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="sort">Urutkan Berdasarkan:</label>
                    <select name="sort" id="sort">
                        <option value="name_asc" <?= (isset($_GET['sort']) && $_GET['sort'] == 'name_asc') ? 'selected' : '' ?>>Nama (A-Z)</option>
                        <option value="name_desc" <?= (isset($_GET['sort']) && $_GET['sort'] == 'name_desc') ? 'selected' : '' ?>>Nama (Z-A)</option>
                        <option value="interns_asc" <?= (isset($_GET['sort']) && $_GET['sort'] == 'interns_asc') ? 'selected' : '' ?>>Jumlah Mahasiswa (Sedikit-Banyak)</option>
                        <option value="interns_desc" <?= (isset($_GET['sort']) && $_GET['sort'] == 'interns_desc') ? 'selected' : '' ?>>Jumlah Mahasiswa (Banyak-Sedikit)</option>
                    </select>
                </div>
                
                <button type="submit">Terapkan Filter</button>
                <button type="button" onclick="window.location.href='laporan_perusahaan.php'">Reset</button>
            </form>
        </div>
        
        <div class="action-buttons no-print">
            <button class="print-btn" onclick="window.print()">Cetak Laporan</button>
            <button class="export-btn" onclick="exportToExcel()">Export ke Excel</button>
        </div>
        
        <?php
        // Build base query
        $query = "SELECT tm.*, 
                         COUNT(m.NIM) as total_mahasiswa,
                         MIN(m.Mulai_Magang) as earliest_start,
                         MAX(m.Selesai_Magang) as latest_end
                  FROM Tempat_Magang tm
                  LEFT JOIN Mahasiswa m ON tm.ID_Lokasi_Magang = m.ID_Lokasi_Magang";
        
        // Apply filters
        if (isset($_GET['status']) && $_GET['status'] != 'all') {
            if ($_GET['status'] == 'active') {
                $query .= " WHERE EXISTS (SELECT 1 FROM Mahasiswa WHERE Mahasiswa.ID_Lokasi_Magang = tm.ID_Lokasi_Magang)";
            } else {
                $query .= " WHERE NOT EXISTS (SELECT 1 FROM Mahasiswa WHERE Mahasiswa.ID_Lokasi_Magang = tm.ID_Lokasi_Magang)";
            }
        }
        
        $query .= " GROUP BY tm.ID_Lokasi_Magang";
        
        // Apply sorting
        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'name_asc';
        switch ($sort) {
            case 'name_desc':
                $query .= " ORDER BY tm.Nama_Perusahaan DESC";
                break;
            case 'interns_asc':
                $query .= " ORDER BY total_mahasiswa ASC";
                break;
            case 'interns_desc':
                $query .= " ORDER BY total_mahasiswa DESC";
                break;
            default:
                $query .= " ORDER BY tm.Nama_Perusahaan ASC";
        }
        
        $result = $conn->query($query);
        
        if ($result->num_rows > 0) {
            // Calculate statistics
            $total_companies = 0;
            $total_interns = 0;
            $active_companies = 0;
            $companies_with_data = [];
            
            while ($row = $result->fetch_assoc()) {
                $companies_with_data[] = $row;
                $total_companies++;
                $total_interns += $row['total_mahasiswa'];
                if ($row['total_mahasiswa'] > 0) {
                    $active_companies++;
                }
            }
            
            $avg_interns = $total_companies > 0 ? $total_interns / $total_companies : 0;
        ?>
        
        <div class="stats-container no-print">
            <div class="stat-card">
                <h3>Total Perusahaan</h3>
                <div class="stat-value"><?= $total_companies ?></div>
                <div>tempat magang terdaftar</div>
            </div>
            <div class="stat-card">
                <h3>Perusahaan Aktif</h3>
                <div class="stat-value"><?= $active_companies ?></div>
                <div>sedang menampung mahasiswa</div>
            </div>
            <div class="stat-card">
                <h3>Total Mahasiswa</h3>
                <div class="stat-value"><?= $total_interns ?></div>
                <div>yang sedang/telah magang</div>
            </div>
            <div class="stat-card">
                <h3>Rata-rata Mahasiswa</h3>
                <div class="stat-value"><?= number_format($avg_interns, 1) ?></div>
                <div>per perusahaan</div>
            </div>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Perusahaan</th>
                    <th>Alamat</th>
                    <th>Total Mahasiswa</th>
                    <th>Mahasiswa Aktif</th>
                    <th>Periode Awal</th>
                    <th>Periode Akhir</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                foreach ($companies_with_data as $company) {
                    $active_interns = getActiveInternsCount($conn, $company['ID_Lokasi_Magang']);
                    $status = $company['total_mahasiswa'] > 0 ? 
                              ($active_interns > 0 ? 'Aktif' : 'Tidak Aktif') : 'Belum Ada Mahasiswa';
                    $status_class = '';
                    
                    if ($status == 'Aktif') $status_class = 'status-active';
                    elseif ($status == 'Tidak Aktif') $status_class = 'status-inactive';
                    else $status_class = 'status-na';
                    
                    echo "<tr>
                            <td>$no</td>
                            <td>{$company['Nama_Perusahaan']}</td>
                            <td>{$company['Alamat_Perusahaan']}</td>
                            <td style='text-align: center;'>{$company['total_mahasiswa']}</td>
                            <td style='text-align: center;'>$active_interns</td>
                            <td>".($company['earliest_start'] ? date('d M Y', strtotime($company['earliest_start'])) : '-')."</td>
                            <td>".($company['latest_end'] ? date('d M Y', strtotime($company['latest_end'])) : '-')."</td>
                            <td><span class='$status_class'>$status</span></td>
                          </tr>";
                    $no++;
                }
                ?>
            </tbody>
        </table>
        
        <?php
        } else {
            echo "<p>Tidak ada data perusahaan yang ditemukan.</p>";
        }
        ?>
    </div>
    
    <script>
        // Function to export to Excel
        function exportToExcel() {
            // Create HTML table with just the data we want to export
            let html = '<table>';
            
            // Add header row
            html += '<tr>';
            html += '<th>No</th>';
            html += '<th>Nama Perusahaan</th>';
            html += '<th>Alamat</th>';
            html += '<th>Total Mahasiswa</th>';
            html += '<th>Mahasiswa Aktif</th>';
            html += '<th>Periode Awal</th>';
            html += '<th>Periode Akhir</th>';
            html += '<th>Status</th>';
            html += '</tr>';
            
            // Add data rows
            let rows = document.querySelectorAll('table tbody tr');
            rows.forEach((row, index) => {
                html += '<tr>';
                html += `<td>${index + 1}</td>`;
                
                let cells = row.querySelectorAll('td');
                // Skip first cell (we're using our own numbering)
                for (let i = 1; i < cells.length; i++) {
                    // For status column, get the text content
                    if (i === cells.length - 1) {
                        html += `<td>${cells[i].textContent}</td>`;
                    } else {
                        html += `<td>${cells[i].innerHTML}</td>`;
                    }
                }
                
                html += '</tr>';
            });
            
            html += '</table>';
            
            // Create and trigger download
            let blob = new Blob([html], {type: 'application/vnd.ms-excel'});
            let a = document.createElement('a');
            a.href = URL.createObjectURL(blob);
            a.download = 'laporan_perusahaan_magang.xls';
            a.click();
        }
    </script>
</body>
</html>