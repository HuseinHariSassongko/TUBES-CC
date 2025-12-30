<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Telur Josjis - Sistem Reseller Telur Terpercaya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --accent-color: #f093fb;
        }
        
        html {
            scroll-behavior: smooth;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
            margin: 0;
            padding: 0;
        }
        
        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 80px 0 60px;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.1)" d="M0,96L48,112C96,128,192,160,288,186.7C384,213,480,235,576,213.3C672,192,768,128,864,128C960,128,1056,192,1152,197.3C1248,203,1344,149,1392,122.7L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
            background-size: cover;
            opacity: 0.3;
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
        }
        
        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        
        .hero-subtitle {
            font-size: 1.3rem;
            margin-bottom: 2rem;
            opacity: 0.95;
        }
        
        .egg-icon {
            font-size: 5rem;
            animation: bounce 2s infinite;
        }
        
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }
        
        /* Auth Section */
        .auth-section {
            margin-top: -50px;
            position: relative;
            z-index: 10;
            padding-bottom: 60px;
        }
        
        .auth-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
            overflow: hidden;
        }
        
        .nav-tabs {
            border-bottom: none;
            background: #f8f9fa;
        }
        
        .nav-tabs .nav-link {
            border: none;
            color: #666;
            font-weight: 600;
            padding: 1rem 2rem;
            transition: all 0.3s;
        }
        
        .nav-tabs .nav-link.active {
            background: white;
            color: var(--primary-color);
            border-bottom: 3px solid var(--primary-color);
        }
        
        .nav-tabs .nav-link:hover {
            color: var(--primary-color);
        }
        
        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            padding: 12px;
            font-weight: 600;
            transition: transform 0.3s, box-shadow 0.3s;
            color: white;
        }
        
        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }
        
        /* Pricing Section */
        .pricing-section {
            padding: 80px 0;
            background: linear-gradient(180deg, #ffffff 0%, #f8f9fa 100%);
            position: relative;
            z-index: 1;
        }
        
        .pricing-card {
            border: 2px solid #e9ecef;
            border-radius: 20px;
            padding: 2.5rem;
            transition: all 0.3s;
            height: 100%;
            background: white;
        }
        
        .pricing-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        
        .pricing-card.featured {
            border-color: var(--primary-color);
            border-width: 3px;
            transform: scale(1.05);
            box-shadow: 0 20px 40px rgba(102, 126, 234, 0.2);
        }
        
        .pricing-card.featured:hover {
            transform: scale(1.05) translateY(-10px);
        }
        
        .badge-featured {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .pricing-price {
            font-size: 3rem;
            font-weight: 800;
            color: var(--primary-color);
            margin: 1.5rem 0;
        }
        
        .pricing-features {
            list-style: none;
            padding: 0;
            margin: 2rem 0;
        }
        
        .pricing-features li {
            padding: 0.75rem 0;
            border-bottom: 1px solid #f1f1f1;
        }
        
        .pricing-features li:last-child {
            border-bottom: none;
        }
        
        .check-icon {
            color: #28a745;
            margin-right: 0.5rem;
            font-weight: bold;
        }
        
        .cross-icon {
            color: #dc3545;
            margin-right: 0.5rem;
        }
        
        /* Features Section */
        .features-section {
            padding: 80px 0 100px;
            background: white;
            position: relative;
            z-index: 1;
        }
        
        .feature-box {
            text-align: center;
            padding: 2rem;
            border-radius: 15px;
            transition: all 0.3s;
        }
        
        .feature-box:hover {
            background: #f8f9fa;
            transform: translateY(-5px);
        }
        
        .feature-icon {
            font-size: 3rem;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 1rem;
        }
        
        .section-title {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
            text-align: center;
        }
        
        .section-subtitle {
            color: #666;
            text-align: center;
            margin-bottom: 4rem;
            font-size: 1.1rem;
        }
        
        /* Footer - Gradient Ungu Cerah */
        footer {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            color: white !important;
            padding: 50px 0 !important;
            position: relative;
            z-index: 10;
            margin-top: 0;
            border-top: 4px solid rgba(240, 147, 251, 0.5);
            box-shadow: 0 -5px 20px rgba(102, 126, 234, 0.2);
        }
        
        footer h5 {
            color: #ffffff !important;
            font-weight: 700;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
            margin-bottom: 1rem;
        }
        
        footer .text-muted {
            color: rgba(255, 255, 255, 0.9) !important;
        }
        
        footer small {
            color: rgba(255, 255, 255, 0.85) !important;
        }
        
        footer p {
            margin-bottom: 0.5rem;
        }
        
        footer:hover {
            box-shadow: 0 -10px 30px rgba(102, 126, 234, 0.3);
            transition: box-shadow 0.3s ease;
        }
        
        .egg-icon-footer {
            font-size: 2rem;
            vertical-align: middle;
            display: inline-block;
            animation: rotate 3s infinite ease-in-out;
        }
        
        @keyframes rotate {
            0%, 100% { transform: rotate(0deg); }
            50% { transform: rotate(15deg); }
        }
        
        /* Utility Classes */
        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
        }
        
        .btn {
            transition: all 0.3s ease;
        }
        
        .btn:hover {
            transform: translateY(-2px);
        }
        
        .badge-premium {
            background: linear-gradient(135deg, #FFD700, #FFA500);
            color: white;
            font-weight: bold;
        }
        
        .badge-basic {
            background: #6c757d;
            color: white;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.1rem;
            }
            
            .pricing-card.featured {
                transform: scale(1);
            }
            
            .section-title {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container hero-content">
            <div class="row align-items-center">
                <div class="col-lg-6 text-center text-lg-start">
                    <div class="egg-icon mb-4">🥚</div>
                    <h1 class="hero-title">Telur Josjis</h1>
                    <p class="hero-subtitle">
                        Platform Reseller Telur Terpercaya dengan Sistem Langganan Fleksibel
                    </p>
                    <div class="d-flex gap-3 justify-content-center justify-content-lg-start flex-wrap">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle-fill text-warning me-2"></i>
                            <span>Harga Kompetitif</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle-fill text-warning me-2"></i>
                            <span>Kualitas Terjamin</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle-fill text-warning me-2"></i>
                            <span>Pengiriman Cepat</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mt-5 mt-lg-0">
                    <div class="text-center">
                        <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 400 400'%3E%3Ccircle cx='200' cy='200' r='150' fill='%23fff' opacity='0.2'/%3E%3Ctext x='200' y='230' font-size='120' text-anchor='middle' fill='%23fff'%3E🥚%3C/text%3E%3C/svg%3E" 
                             alt="Telur Icon" style="width: 300px; opacity: 0.9; max-width: 100%;">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Auth Section -->
    <section class="auth-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="auth-card">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item flex-fill" role="presentation">
                                <button class="nav-link active w-100" data-bs-toggle="tab" data-bs-target="#login-tab">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>Login
                                </button>
                            </li>
                            <li class="nav-item flex-fill" role="presentation">
                                <button class="nav-link w-100" data-bs-toggle="tab" data-bs-target="#register-tab">
                                    <i class="bi bi-person-plus me-2"></i>Daftar
                                </button>
                            </li>
                        </ul>
                        
                        <div class="tab-content p-4">
                            <!-- Login Tab -->
                            <div class="tab-pane fade show active" id="login-tab">
                                <?php if(isset($_SESSION['flash_message'])): ?>
                                    <?php showFlashMessage(); ?>
                                <?php endif; ?>
                                
                                <form action="index.php?page=login&action=process" method="POST">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">
                                            <i class="bi bi-envelope me-2"></i>Email
                                        </label>
                                        <input type="email" name="email" class="form-control form-control-lg" 
                                               placeholder="nama@email.com" required>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">
                                            <i class="bi bi-lock me-2"></i>Password
                                        </label>
                                        <input type="password" name="password" class="form-control form-control-lg" 
                                               placeholder="••••••••" required>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary-custom btn-lg w-100">
                                        <i class="bi bi-box-arrow-in-right me-2"></i>Login Sekarang
                                    </button>
                                </form>
                            </div>
                            
                            <!-- Register Tab -->
                            <div class="tab-pane fade" id="register-tab">
                                <form action="index.php?page=register&action=process" method="POST">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">
                                                <i class="bi bi-person me-2"></i>Nama Lengkap
                                            </label>
                                            <input type="text" name="full_name" class="form-control" 
                                                   placeholder="John Doe" required>
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">
                                                <i class="bi bi-envelope me-2"></i>Email
                                            </label>
                                            <input type="email" name="email" class="form-control" 
                                                   placeholder="nama@email.com" required>
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">
                                                <i class="bi bi-phone me-2"></i>Nomor Telepon
                                            </label>
                                            <input type="tel" name="phone" class="form-control" 
                                                   placeholder="08123456789" required>
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">
                                                <i class="bi bi-geo-alt me-2"></i>Alamat
                                            </label>
                                            <input type="text" name="address" class="form-control" 
                                                   placeholder="Surabaya" required>
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">
                                                <i class="bi bi-lock me-2"></i>Password
                                            </label>
                                            <input type="password" name="password" class="form-control" 
                                                   placeholder="Minimal 6 karakter" minlength="6" required>
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">
                                                <i class="bi bi-lock-fill me-2"></i>Konfirmasi Password
                                            </label>
                                            <input type="password" name="confirm_password" class="form-control" 
                                                   placeholder="Ketik ulang password" required>
                                        </div>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary-custom btn-lg w-100 mt-3">
                                        <i class="bi bi-person-plus me-2"></i>Daftar Sekarang
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section class="pricing-section" id="pricing">
        <div class="container">
            <h2 class="section-title">Pilih Paket Langganan</h2>
            <p class="section-subtitle">Dapatkan benefit lebih dengan upgrade ke Premium</p>
            
            <div class="row g-4">
                <!-- Basic Plan -->
                <div class="col-lg-6">
                    <div class="pricing-card">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="fw-bold mb-0">
                                <i class="bi bi-box text-secondary me-2"></i>Basic
                            </h3>
                        </div>
                        
                        <div class="pricing-price">
                            Gratis
                            <small class="text-muted" style="font-size: 1rem;">/ selamanya</small>
                        </div>
                        
                        <ul class="pricing-features">
                            <li>
                                <i class="bi bi-check-circle-fill check-icon"></i>
                                <strong>10 Pesanan</strong> per bulan
                            </li>
                            <li>
                                <i class="bi bi-check-circle-fill check-icon"></i>
                                Akses <strong>stok regular</strong>
                            </li>
                            <li>
                                <i class="bi bi-check-circle-fill check-icon"></i>
                                Histori pesanan <strong>3 bulan</strong>
                            </li>
                            <li>
                                <i class="bi bi-check-circle-fill check-icon"></i>
                                Notifikasi <strong>manual</strong>
                            </li>
                            <li>
                                <i class="bi bi-x-circle-fill cross-icon"></i>
                                <span class="text-muted">Tanpa diskon</span>
                            </li>
                            <li>
                                <i class="bi bi-x-circle-fill cross-icon"></i>
                                <span class="text-muted">Tanpa prioritas stok</span>
                            </li>
                            <li>
                                <i class="bi bi-x-circle-fill cross-icon"></i>
                                <span class="text-muted">Tanpa export laporan</span>
                            </li>
                        </ul>
                        
                        <button class="btn btn-outline-secondary btn-lg w-100" disabled>
                            <i class="bi bi-check-circle me-2"></i>Paket Saat Ini
                        </button>
                    </div>
                </div>
                
                <!-- Premium Plan -->
                <div class="col-lg-6">
                    <div class="pricing-card featured">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="fw-bold mb-0">
                                <i class="bi bi-gem text-warning me-2"></i>Premium
                            </h3>
                            <span class="badge-featured">🔥 RECOMMENDED</span>
                        </div>
                        
                        <div class="pricing-price">
                            Rp 100.000
                            <small class="text-muted" style="font-size: 1rem;">/ bulan</small>
                        </div>
                        
                        <ul class="pricing-features">
                            <li>
                                <i class="bi bi-check-circle-fill check-icon"></i>
                                Pesanan <strong>UNLIMITED</strong>
                            </li>
                            <li>
                                <i class="bi bi-check-circle-fill check-icon"></i>
                                <strong>Prioritas stok</strong> (24 jam lebih dulu)
                            </li>
                            <li>
                                <i class="bi bi-check-circle-fill check-icon"></i>
                                Histori pesanan <strong>12 bulan</strong>
                            </li>
                            <li>
                                <i class="bi bi-check-circle-fill check-icon"></i>
                                Notifikasi <strong>real-time</strong>
                            </li>
                            <li>
                                <i class="bi bi-check-circle-fill check-icon"></i>
                                <strong>Diskon 10%</strong> untuk order >100 kg
                            </li>
                            <li>
                                <i class="bi bi-check-circle-fill check-icon"></i>
                                <strong>Export laporan</strong> PDF & Excel
                            </li>
                            <li>
                                <i class="bi bi-check-circle-fill check-icon"></i>
                                <strong>Akses stok priority</strong> eksklusif
                            </li>
                        </ul>
                        
                        <button class="btn btn-primary-custom btn-lg w-100">
                            <i class="bi bi-rocket-takeoff me-2"></i>Upgrade ke Premium
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="container">
            <h2 class="section-title">Kenapa Pilih Telur Josjis?</h2>
            <p class="section-subtitle">Platform reseller telur modern dengan fitur lengkap</p>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h4 class="fw-bold">Kualitas Terjamin</h4>
                        <p class="text-muted">Telur segar dari peternak terpercaya dengan sertifikasi halal</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="bi bi-lightning-charge"></i>
                        </div>
                        <h4 class="fw-bold">Pengiriman Cepat</h4>
                        <p class="text-muted">Sistem logistik terintegrasi untuk pengiriman hari yang sama</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                        <h4 class="fw-bold">Harga Kompetitif</h4>
                        <p class="text-muted">Dapatkan harga terbaik langsung dari distributor</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="bi bi-credit-card"></i>
                        </div>
                        <h4 class="fw-bold">Pembayaran Mudah</h4>
                        <p class="text-muted">Integrasi payment gateway untuk transaksi aman</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="bi bi-clipboard-data"></i>
                        </div>
                        <h4 class="fw-bold">Laporan Lengkap</h4>
                        <p class="text-muted">Dashboard analytics untuk tracking pembelian real-time</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="bi bi-headset"></i>
                        </div>
                        <h4 class="fw-bold">Support 24/7</h4>
                        <p class="text-muted">Tim customer service siap membantu kapan saja</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer - Gradient Ungu Cerah -->
    <footer>
        <div class="container py-4">
            <div class="row align-items-center">
                <div class="col-md-6 mb-3 mb-md-0 text-center text-md-start">
                    <h5 class="fw-bold mb-3">
                        <span class="egg-icon-footer">🥚</span> Telur Josjis
                    </h5>
                    <p class="mb-2">Platform Reseller Telur Terpercaya</p>
                    <p class="small mb-1">
                        <i class="bi bi-geo-alt-fill me-2"></i>Jl. Contoh No. 123, Surabaya, Jawa Timur 60123
                    </p>
                    <p class="small mb-0">
                        <i class="bi bi-envelope-fill me-2"></i>info@telurjosjis.com | 
                        <i class="bi bi-telephone-fill ms-2 me-2"></i>0812-3456-7890
                    </p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <div class="mb-3">
                        <a href="#pricing" class="text-white text-decoration-none me-3 d-inline-block mb-2">
                            <i class="bi bi-tag me-1"></i>Paket Langganan
                        </a>
                        <a href="#" class="text-white text-decoration-none d-inline-block mb-2">
                            <i class="bi bi-info-circle me-1"></i>Tentang Kami
                        </a>
                    </div>
                    <hr class="my-3 opacity-25">
                    <p class="mb-1">&copy; 2025 Telur Josjis. All rights reserved.</p>
                    <small>Made with <i class="bi bi-heart-fill text-danger"></i> in Surabaya</small>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
