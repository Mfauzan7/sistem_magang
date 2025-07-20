<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard PJ Magang - Sistem Informasi Magang</title>
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
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .welcome-icon {
            font-size: 2.5rem;
            color: var(--company-color);
        }
        
        .welcome-text h3 {
            font-size: 1.3rem;
            margin-bottom: 10px;
            color: var(--dark);
        }
        
        .welcome-text p {
            color: var(--gray);
        }
        
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        
        .action-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-align: center;
            border-top: 3px solid var(--company-color);
        }
        
        .action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .action-icon {
            font-size: 2rem;
            color: var(--company-color);
            margin-bottom: 15px;
        }
        
        .action-card h3 {
            font-size: 1.1rem;
            margin-bottom: 10px;
            color: var(--dark);
        }
        
        .action-link {
            display: inline-block;
            margin-top: 15px;
            padding: 8px 20px;
            background: var(--company-color);
            color: white;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 500;
            transition: background 0.3s ease;
            font-size: 0.9rem;
        }
        
        .action-link:hover {
            background: #8e44ad;
        }
        
        .recent-activities {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
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
        
        .activity-list {
            list-style: none;
        }
        
        .activity-item {
            padding: 15px 0;
            border-bottom: 1px solid #eee;
            display: flex;
            gap: 15px;
        }
        
        .activity-item:last-child {
            border-bottom: none;
        }
        
        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #f0e6f5;
            color: var(--company-color);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .activity-content h4 {
            font-size: 1rem;
            margin-bottom: 5px;
            color: var(--dark);
        }
        
        .activity-content p {
            color: var(--gray);
            font-size: 0.85rem;
        }
        
        .activity-time {
            color: var(--gray);
            font-size: 0.8rem;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 5px;
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
            
            .welcome-card {
                flex-direction: column;
                text-align: center;
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
                </ul>
            </nav>
        </div>
    </header>
    
    <main class="container">
        <div class="dashboard-title">
            <div>
                <h2>Welcome</h2>
                <p>Dashboard pengelolaan magang untuk mahasiswa</p>
            </div>
        </div>
        
        <div class="welcome-card">
            <div class="welcome-icon">
                <i class="fas fa-info-circle"></i>
            </div>
            <div class="welcome-text">
                <h3>Selamat datang di Sistem PJ Magang</h3>
            </div>
        </div>
        
        <div class="quick-actions">
            <div class="action-card">
                <div class="action-icon">
                    <i class="fas fa-building"></i>
                </div>
                <h3>Profil Perusahaan</h3>
                <p>Kelola data perusahaan tempat magang</p>
                <a href="tempat_form.php" class="action-link">Kelola</a>
            </div>
            
            <div class="action-card">
                <div class="action-icon">
                    <i class="fas fa-edit"></i>
                </div>
                <h3>Input Nilai</h3>
                <p>Input nilai magang mahasiswa</p>
                <a href="nilai_form.php" class="action-link">Input</a>
            </div>
            
            <div class="action-card">
                <div class="action-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3>Daftar Mahasiswa</h3>
                <p>Lihat mahasiswa yang magang di perusahaan Anda</p>
                <a href="mahasiswa_pj.php" class="action-link">Lihat</a>
            </div>
        </div>
    </main>
    
    <footer>
        <div class="container footer-content">
            <p class="copyright">&copy; 2023 Sistem Informasi Magang. Hak Cipta Dilindungi.</p>
        </div>
    </footer>
    
    <script>
        // Dynamic elements can be added here
        document.addEventListener('DOMContentLoaded', function() {
            // Example: Mark activities as read
            const activities = document.querySelectorAll('.activity-item');
            activities.forEach(activity => {
                activity.addEventListener('click', function() {
                    this.style.opacity = '0.7';
                });
            });
        });
    </script>
</body>
</html>