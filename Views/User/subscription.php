<?php
requireLogin();
require_once __DIR__ . '/../../Config/midtrans_config.php'; // ← TAMBAHAN BARU
$page_title = 'Langganan Premium';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?> - Telur Josjis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- ========================================== -->
    <!-- MIDTRANS SNAP.JS (SANDBOX) - TAMBAHAN BARU -->
    <!-- ========================================== -->
    <script 
        type="text/javascript"
        src="<?= MIDTRANS_SNAP_URL ?>"
        data-client-key="<?= MIDTRANS_CLIENT_KEY ?>">
    </script>
    <!-- ========================================== -->
    
    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
        }
        
        .navbar-custom {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        }
        
        .pricing-card {
            border-radius: 15px;
            transition: all 0.3s;
        }
        
        .pricing-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
        }
        
        .premium-card {
            border: 3px solid #FFD700;
            background: linear-gradient(135deg, #FFF9E6 0%, #FFFAEB 100%);
        }
        
        .feature-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        
        .feature-box {
            padding: 2rem 1rem;
            border-radius: 10px;
            transition: all 0.3s;
        }
        
        .feature-box:hover {
            background: #f8f9fa;
            transform: translateY(-5px);
        }
    </style>
</head>
<body class="bg-light">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php?page=dashboard">🥚 Telur Josjis</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php?page=dashboard"><i class="bi bi-speedometer2 me-1"></i>Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php?page=catalog"><i class="bi bi-shop me-1"></i>Catalog</a></li>
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="index.php?page=cart">
                            <i class="bi bi-cart3 me-1"></i>Cart
                            <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    <?= count($_SESSION['cart']) ?>
                                </span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="index.php?page=orders"><i class="bi bi-box-seam me-1"></i>Orders</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle active" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i><?= $_SESSION['full_name'] ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="index.php?page=profile"><i class="bi bi-person me-2"></i>Profile</a></li>
                            <li><a class="dropdown-item active" href="index.php?page=subscription"><i class="bi bi-gem me-2"></i>Subscription</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="index.php?page=logout"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4 mb-5">
        <div class="text-center mb-5">
            <h2><i class="bi bi-gem me-2"></i>Paket Langganan</h2>
            <p class="text-muted">Pilih paket yang sesuai dengan kebutuhan bisnis Anda</p>
        </div>

        <?php showFlashMessage(); ?>

        <div class="row justify-content-center">
            <!-- Basic Plan -->
            <div class="col-lg-4 mb-4">
                <div class="card pricing-card h-100">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <h4 class="mb-2">Basic</h4>
                            <h2 class="mb-0">GRATIS</h2>
                            <p class="text-muted">Selamanya</p>
                        </div>

                        <ul class="list-unstyled mb-4">
                            <li class="mb-3">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                Akses stok regular
                            </li>
                            <li class="mb-3">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                Harga normal
                            </li>
                            <li class="mb-3">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                Order tracking
                            </li>
                            <li class="mb-3 text-muted">
                                <i class="bi bi-x-circle me-2"></i>
                                Stok prioritas
                            </li>
                            <li class="mb-3 text-muted">
                                <i class="bi bi-x-circle me-2"></i>
                                Diskon 10%
                            </li>
                        </ul>

                        <?php if ($user['tier_name'] == 'Basic'): ?>
                            <button class="btn btn-secondary w-100" disabled>
                                <i class="bi bi-check-circle me-2"></i>Paket Aktif
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Premium Plan -->
            <div class="col-lg-4 mb-4">
                <div class="card pricing-card premium-card h-100">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <span class="badge bg-warning text-dark mb-2">RECOMMENDED</span>
                            <h4 class="mb-2"><i class="bi bi-gem me-2"></i>Premium</h4>
                            <h2 class="mb-0">Rp 100.000</h2>
                            <p class="text-muted">Per bulan</p>
                        </div>

                        <ul class="list-unstyled mb-4">
                            <li class="mb-3">
                                <i class="bi bi-check-circle-fill text-warning me-2"></i>
                                <strong>Semua fitur Basic</strong>
                            </li>
                            <li class="mb-3">
                                <i class="bi bi-check-circle-fill text-warning me-2"></i>
                                <strong>Akses stok prioritas 24 jam lebih awal</strong>
                            </li>
                            <li class="mb-3">
                                <i class="bi bi-check-circle-fill text-warning me-2"></i>
                                <strong>Diskon 10% untuk pembelian >100kg</strong>
                            </li>
                            <li class="mb-3">
                                <i class="bi bi-check-circle-fill text-warning me-2"></i>
                                Priority customer support
                            </li>
                            <li class="mb-3">
                                <i class="bi bi-check-circle-fill text-warning me-2"></i>
                                Laporan pembelian
                            </li>
                        </ul>

                        <?php if ($user['tier_name'] == 'Premium'): ?>
                            <button class="btn btn-success w-100" disabled>
                                <i class="bi bi-check-circle me-2"></i>Paket Aktif
                            </button>
                            
                            <?php if(isset($user['subscription_expiry']) && $user['subscription_expiry']): ?>
                                <div class="alert alert-info mt-3 mb-0 text-center">
                                    <small>
                                        <i class="bi bi-calendar-event"></i> Berlaku hingga:<br>
                                        <strong><?= date('d M Y', strtotime($user['subscription_expiry'])) ?></strong>
                                    </small>
                                </div>
                            <?php else: ?>
                                <small class="text-muted d-block text-center mt-2">
                                    <i class="bi bi-info-circle"></i> Tanggal tidak tersedia
                                </small>
                            <?php endif; ?>
                            
                        <?php else: ?>
                            <!-- ========================================== -->
                            <!-- BUTTON UPGRADE MIDTRANS (TANPA MODAL)      -->
                            <!-- ========================================== -->
                            <button 
                                type="button" 
                                class="btn btn-warning w-100 btn-upgrade" 
                                data-months="1" 
                                data-price="100000">
                                <i class="bi bi-rocket-takeoff me-2"></i>Upgrade Sekarang (1 Bulan)
                            </button>

                            <!-- Pilihan Paket Lainnya -->
                            <div class="mt-3">
                                <small class="text-muted d-block mb-2">Pilih paket lainnya:</small>
                                <div class="d-grid gap-2">
                                    <button class="btn btn-outline-warning btn-sm btn-upgrade" data-months="3" data-price="270000">
                                        <i class="bi bi-calendar3 me-2"></i>3 Bulan - Rp 270.000 (Hemat 10%)
                                    </button>
                                    <button class="btn btn-outline-warning btn-sm btn-upgrade" data-months="6" data-price="510000">
                                        <i class="bi bi-calendar3 me-2"></i>6 Bulan - Rp 510.000 (Hemat 15%)
                                    </button>
                                    <button class="btn btn-outline-warning btn-sm btn-upgrade" data-months="12" data-price="960000">
                                        <i class="bi bi-calendar3 me-2"></i>12 Bulan - Rp 960.000 (Hemat 20%)
                                    </button>
                                </div>
                            </div>
                            <!-- ========================================== -->
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kenapa Pilih Telur Josjis Section -->
    <div class="container mt-5 mb-5">
        <div class="text-center mb-5">
            <h3 class="fw-bold">Kenapa Pilih Telur Josjis?</h3>
            <p class="text-muted">Platform reseller telur modern dengan fitur lengkap</p>
        </div>
        
        <div class="row g-4">
            <!-- Feature 1: Kualitas Terjamin -->
            <div class="col-md-4">
                <div class="feature-box text-center">
                    <i class="bi bi-shield-check feature-icon" style="color: #667eea;"></i>
                    <h5 class="fw-bold mb-3">Kualitas Terjamin</h5>
                    <p class="text-muted">Telur segar dari peternakan terpercaya dengan sertifikasi halal</p>
                </div>
            </div>
            
            <!-- Feature 2: Pengiriman Cepat -->
            <div class="col-md-4">
                <div class="feature-box text-center">
                    <i class="bi bi-lightning-charge-fill feature-icon" style="color: #667eea;"></i>
                    <h5 class="fw-bold mb-3">Pengiriman Cepat</h5>
                    <p class="text-muted">Sistem logistik terintegrasi untuk pengiriman hari yang sama</p>
                </div>
            </div>
            
            <!-- Feature 3: Harga Kompetitif -->
            <div class="col-md-4">
                <div class="feature-box text-center">
                    <i class="bi bi-graph-up-arrow feature-icon" style="color: #667eea;"></i>
                    <h5 class="fw-bold mb-3">Harga Kompetitif</h5>
                    <p class="text-muted">Dapatkan harga terbaik langsung dari distributor tanpa perantara</p>
                </div>
            </div>
            
            <!-- Feature 4: Pembayaran Mudah -->
            <div class="col-md-4">
                <div class="feature-box text-center">
                    <i class="bi bi-credit-card-2-front-fill feature-icon" style="color: #764ba2;"></i>
                    <h5 class="fw-bold mb-3">Pembayaran Mudah</h5>
                    <p class="text-muted">Integrasi payment gateway untuk transaksi aman dan mudah</p>
                </div>
            </div>
            
            <!-- Feature 5: Sales Analytics -->
            <div class="col-md-4">
                <div class="feature-box text-center">
                    <i class="bi bi-bar-chart-line-fill feature-icon" style="color: #764ba2;"></i>
                    <h5 class="fw-bold mb-3">Sales Analytics</h5>
                    <p class="text-muted">Dashboard analytics untuk monitoring performa bisnis secara real-time</p>
                </div>
            </div>
            
            <!-- Feature 6: Support 24/7 -->
            <div class="col-md-4">
                <div class="feature-box text-center">
                    <i class="bi bi-headset feature-icon" style="color: #764ba2;"></i>
                    <h5 class="fw-bold mb-3">Support 24/7</h5>
                    <p class="text-muted">Tim customer service siap membantu kapanpun Anda membutuhkan</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Alpine.js (optional) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- ========================================== -->
    <!-- MIDTRANS SUBSCRIPTION SCRIPT (BARU)        -->
    <!-- ========================================== -->
    <script src="assets/js/subscription_payment.js"></script>
    <!-- ========================================== -->
</body>
</html>
