<?php
requireLogin();
$page_title = 'Dashboard';
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
        
        .stat-card {
            border-radius: 15px;
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
        }
        
        .badge-premium {
            background: linear-gradient(135deg, #FFD700, #FFA500);
            color: white;
            font-weight: bold;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php?page=dashboard">
                🥚 Telur Josjis
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php?page=dashboard">
                            <i class="bi bi-speedometer2 me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=catalog">
                            <i class="bi bi-shop me-1"></i>Catalog
                        </a>
                    </li>
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
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=orders">
                            <i class="bi bi-box-seam me-1"></i>Orders
                        </a>
                    </li>
                    <?php if ($user['tier_name'] == 'Premium'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="demo_pdf.php" target="_blank">
                            <i class="bi bi-file-earmark-pdf me-1"></i>Demo PDF
                        </a>
                    </li>
                    <?php endif; ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i><?= $_SESSION['full_name'] ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="index.php?page=profile">
                                <i class="bi bi-person me-2"></i>Profile
                            </a></li>
                            <li><a class="dropdown-item" href="index.php?page=subscription">
                                <i class="bi bi-gem me-2"></i>Subscription
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="index.php?page=logout">
                                <i class="bi bi-box-arrow-right me-2"></i>Logout
                            </a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Welcome Banner -->
        <div class="card mb-4" style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); color: white; border: none;">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h2 class="mb-2">Selamat Datang, <?= htmlspecialchars($user['full_name']) ?>! 👋</h2>
                        <p class="mb-0">Tier Langganan: 
                            <?php if ($user['tier_name'] == 'Premium'): ?>
                                <span class="badge badge-premium">
                                    <i class="bi bi-gem"></i> Premium
                                </span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Basic</span>
                            <?php endif; ?>
                        </p>
                    </div>
                    <div class="col-md-4 text-end">
                        <?php if ($user['tier_name'] == 'Basic'): ?>
                            <a href="index.php?page=subscription" class="btn btn-warning btn-lg">
                                <i class="bi bi-rocket-takeoff me-2"></i>Upgrade Premium
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <?php showFlashMessage(); ?>

        <!-- Statistics -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon bg-primary bg-opacity-10 text-primary me-3">
                                <i class="bi bi-cart-check"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-0">Total Orders</h6>
                                <h3 class="mb-0"><?= $stats['total_orders'] ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon bg-warning bg-opacity-10 text-warning me-3">
                                <i class="bi bi-clock-history"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-0">Pending</h6>
                                <h3 class="mb-0"><?= $stats['pending_orders'] ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon bg-success bg-opacity-10 text-success me-3">
                                <i class="bi bi-check-circle"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-0">Completed</h6>
                                <h3 class="mb-0"><?= $stats['completed_orders'] ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon bg-info bg-opacity-10 text-info me-3">
                                <i class="bi bi-cash-coin"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-0">Total Spent</h6>
                                <h3 class="mb-0" style="font-size: 1.3rem;"><?= formatRupiah($stats['total_spent']) ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Pesanan Terbaru</h5>
            </div>
            <div class="card-body">
                <?php if (empty($recent_orders)): ?>
                    <div class="text-center py-5">
                        <i class="bi bi-inbox display-1 text-muted"></i>
                        <p class="text-muted mt-3">Belum ada pesanan</p>
                        <a href="index.php?page=catalog" class="btn btn-primary">
                            <i class="bi bi-shop me-2"></i>Mulai Belanja
                        </a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Tanggal</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_orders as $order): ?>
                                <tr>
                                    <td><strong><?= $order['order_number'] ?></strong></td>
                                    <td><?= date('d M Y', strtotime($order['created_at'])) ?></td>
                                    <td><strong><?= formatRupiah($order['total_amount']) ?></strong></td>
                                    <td>
                                        <?php
                                        $badge_class = [
                                            'pending' => 'warning',
                                            'processing' => 'info',
                                            'shipped' => 'primary',
                                            'delivered' => 'success',
                                            'cancelled' => 'danger'
                                        ];
                                        ?>
                                        <span class="badge bg-<?= $badge_class[$order['status']] ?? 'secondary' ?>">
                                            <?= ucfirst($order['status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="index.php?page=orders&action=detail&id=<?= $order['order_id'] ?>" 
                                           class="btn btn-sm btn-outline-primary"
                                           title="Lihat Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <?php if ($user['tier_name'] == 'Premium'): ?>
                                        <a href="index.php?page=orders&action=download-pdf&order_id=<?= $order['order_id'] ?>" 
                                           class="btn btn-sm btn-outline-danger"
                                           title="Download PDF"
                                           target="_blank">
                                            <i class="bi bi-file-pdf"></i>
                                        </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-end mt-3">
                        <a href="index.php?page=orders" class="btn btn-primary">
                            Lihat Semua Pesanan <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
