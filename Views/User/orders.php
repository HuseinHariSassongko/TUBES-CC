<?php
requireLogin();
$page_title = 'Riwayat Pesanan';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?> - Telur Josjis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
        }
        
        .navbar-custom {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        }
        
        .order-card {
            border-radius: 10px;
            border: 1px solid #e9ecef;
            transition: all 0.3s;
        }
        
        .order-card:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
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
                    <li class="nav-item"><a class="nav-link active" href="index.php?page=orders"><i class="bi bi-box-seam me-1"></i>Orders</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i><?= $_SESSION['full_name'] ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="index.php?page=profile"><i class="bi bi-person me-2"></i>Profile</a></li>
                            <li><a class="dropdown-item" href="index.php?page=subscription"><i class="bi bi-gem me-2"></i>Subscription</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="index.php?page=logout"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4 mb-5">
        <h2 class="mb-4"><i class="bi bi-box-seam me-2"></i>Riwayat Pesanan</h2>

        <?php showFlashMessage(); ?>

        <?php if (empty($orders)): ?>
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-inbox display-1 text-muted"></i>
                    <h4 class="mt-3">Belum Ada Pesanan</h4>
                    <p class="text-muted">Anda belum memiliki riwayat pesanan</p>
                    <a href="index.php?page=catalog" class="btn btn-primary mt-3">
                        <i class="bi bi-shop me-2"></i>Mulai Belanja
                    </a>
                </div>
            </div>
        <?php else: ?>
            <?php 
            $badge_class = [
                'pending' => 'warning',
                'processing' => 'info',
                'shipped' => 'primary',
                'delivered' => 'success',
                'completed' => 'success',
                'cancelled' => 'danger'
            ];
            ?>
            
            <?php foreach ($orders as $order): ?>
            <div class="card order-card mb-3">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <h6 class="text-muted mb-1">Order ID</h6>
                            <h5 class="mb-0"><?= $order['order_number'] ?></h5>
                            <small class="text-muted"><?= date('d M Y, H:i', strtotime($order['created_at'])) ?></small>
                        </div>
                        
                        <div class="col-md-2">
                            <h6 class="text-muted mb-1">Jumlah Item</h6>
                            <h5 class="mb-0"><?= $order['total_items'] ?? 0 ?> item</h5>
                            <small class="text-muted"><?= number_format($order['total_quantity_kg'], 1) ?> kg</small>
                        </div>
                        
                        <div class="col-md-2">
                            <h6 class="text-muted mb-1">Total</h6>
                            <h5 class="text-primary mb-0"><?= formatRupiah($order['total_amount']) ?></h5>
                        </div>
                        
                        <div class="col-md-2">
                            <h6 class="text-muted mb-1">Status</h6>
                            <span class="badge bg-<?= $badge_class[$order['status']] ?? 'secondary' ?> fs-6">
                                <?= ucfirst($order['status']) ?>
                            </span>
                        </div>
                        
                        <div class="col-md-3 text-end">
                            <a href="index.php?page=orders&action=detail&id=<?= $order['order_id'] ?>" 
                               class="btn btn-primary btn-sm mb-1">
                                <i class="bi bi-eye me-1"></i>Detail
                            </a>
                            
                            <?php if ($order['status'] == 'pending'): ?>
                            <a href="index.php?page=orders&action=cancel&id=<?= $order['order_id'] ?>" 
                               class="btn btn-danger btn-sm mb-1"
                               onclick="return confirm('Batalkan pesanan ini?')">
                                <i class="bi bi-x-circle me-1"></i>Batalkan
                            </a>
                            <?php endif; ?>
                            
                            <br>
                            
                            <!-- Premium Feature: PDF Export -->
                            <?php if ($_SESSION['tier_name'] == 'Premium'): ?>
                                <a href="index.php?page=orders&action=download-pdf&order_id=<?= $order['order_id'] ?>" 
                                   class="btn btn-sm btn-danger mt-1">
                                    <i class="bi bi-file-pdf me-1"></i>PDF
                                </a>
                            <?php else: ?>
                                <button class="btn btn-sm btn-outline-secondary mt-1" disabled title="Premium Feature">
                                    <i class="bi bi-lock-fill me-1"></i>PDF
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
