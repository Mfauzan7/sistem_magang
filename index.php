<?php
session_start();
// Check if user is logged in (you can implement this later)
$logged_in = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistem Informasi Magang Mahasiswa</title>
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
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .logo-text p {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        nav ul {
            display: flex;
            list-style: none;
            gap: 20px;
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
        }
        
        .btn-outline {
            background: transparent;
            border: 2px solid white;
            color: white;
        }
        
        .btn-outline:hover {
            background: white;
            color: var(--primary);
        }
        
        .btn-solid {
            background: white;
            color: var(--primary);
        }
        
        .btn-solid:hover {
            background: rgba(255, 255, 255, 0.9);
        }
        
        main {
            padding: 40px 0;
        }
        
        .dashboard-title {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .dashboard-title h2 {
            font-size: 2.2rem;
            color: var(--dark);
            margin-bottom: 10px;
        }
        
        .dashboard-title p {
            color: var(--gray);
            max-width: 700px;
            margin: 0 auto;
        }
        
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 50px;
        }
        
        .feature-card {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-align: center;
            border-top: 4px solid var(--primary);
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .feature-icon {
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: 20px;
        }
        
        .feature-card h3 {
            font-size: 1.3rem;
            margin-bottom: 15px;
            color: var(--dark);
        }
        
        .feature-card p {
            color: var(--gray);
            margin-bottom: 20px;
        }
        
        .feature-link {
            display: inline-block;
            padding: 8px 20px;
            background: var(--primary);
            color: white;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 500;
            transition: background 0.3s ease;
        }
        
        .feature-link:hover {
            background: var(--primary-dark);
        }
        
        .quick-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        
        .stat-1 { background-color: #e3f2fd; color: var(--primary); }
        .stat-2 { background-color: #e8f5e9; color: var(--secondary); }
        .stat-3 { background-color: #fff3e0; color: var(--warning); }
        .stat-4 { background-color: #ffebee; color: var(--danger); }
        
        .stat-info h3 {
            font-size: 1.8rem;
            margin-bottom: 5px;
        }
        
        .stat-info p {
            color: var(--gray);
            font-size: 0.9rem;
        }
        
        footer {
            background: var(--dark);
            color: white;
            padding: 30px 0;
            text-align: center;
            margin-top: 50px;
        }
        
        .footer-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }
        
        .social-links {
            display: flex;
            gap: 15px;
        }
        
        .social-links a {
            color: white;
            font-size: 1.2rem;
            transition: color 0.3s ease;
        }
        
        .social-links a:hover {
            color: var(--primary);
        }
        
        .copyright {
            font-size: 0.9rem;
            opacity: 0.8;
        }
        
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 20px;
                text-align: center;
            }
            
            nav ul {
                flex-direction: column;
                gap: 10px;
            }
            
            .features {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container header-content">
            <div class="logo">
                <i class="fas fa-graduation-cap"></i>
                <div class="logo-text">
                    <h1>Sistem Informasi Magang</h1>
                    <p>Institut Teknologi PLN</p>
                </div>
            </div>
            
            <nav>
                <ul>
                    <li><a href="mahasiswa_form.php"><i class="fas fa-user-graduate"></i> Mahasiswa</a></li>
                    <li><a href="laporan_nilai.php"><i class="fas fa-chart-bar"></i> Laporan</a></li>
                </ul>
            </nav>
        </div>
    </header>
    
    <main class="container">
        <div class="dashboard-title">
            <h2>Selamat Datang di Sistem Informasi Magang</h2>
            <p>Kelola data magang mahasiswa, perusahaan mitra, dan penilaian magang secara terintegrasi dalam satu platform</p>
        </div>
        
        <div class="features">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <h3>Pendaftaran Mahasiswa</h3>
                <p>Kelola data mahasiswa yang akan melaksanakan magang, termasuk informasi pribadi dan perusahaan tujuan.</p>
                <a href="mahasiswa_form.php" class="feature-link">Akses Modul</a>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3>Laporan Nilai</h3>
                <p>Generate laporan statistik nilai mahasiswa</p>
                <a href="laporan_nilai.php" class="feature-link">Akses Laporan</a>
            </div>
        </div>
    </main>
    
    <footer>
        <div class="container footer-content">
            <p class="copyright">&copy; 2023 Sistem Informasi Magang Mahasiswa. All rights reserved.</p>
        </div>
    </footer>
    
    <script>
        // Dynamic stats loading (you can replace with actual AJAX calls)
        document.addEventListener('DOMContentLoaded', function() {
            // This is just for demo - replace with actual data loading
            setTimeout(() => {
                document.querySelectorAll('.stat-info h3').forEach((el, index) => {
                    const target = [124, 28, 96, 87][index];
                    let current = 0;
                    const increment = target / 20;
                    
                    const timer = setInterval(() => {
                        current += increment;
                        if (current >= target) {
                            clearInterval(timer);
                            el.textContent = index === 3 ? target + '%' : target;
                        } else {
                            el.textContent = index === 3 ? Math.floor(current) + '%' : Math.floor(current);
                        }
                    }, 50);
                });
            }, 500);
        });
    </script>
</body>
</html>