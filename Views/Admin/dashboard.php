<?php
requireAdmin();
$page_title = 'Dashboard Admin';
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
        
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            padding: 0;
        }
        
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 1rem 1.5rem;
            border-left: 3px solid transparent;
            transition: all 0.3s;
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background: rgba(255,255,255,0.1);
            border-left-color: white;
        }
        
        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
        }
        
        .stat-card {
            border-radius: 15px;
            padding: 1.5rem;
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.12);
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
        
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .bg-gradient-success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }
        
        .bg-gradient-warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        
        .bg-gradient-info {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        
        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }
        
        .badge {
            padding: 0.5rem 1rem;
            font-weight: 600;
        }
        
        .sidebar-brand {
            padding: 1.5rem;
            font-size: 1.5rem;
            font-weight: 800;
            color: white;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar">
                <div class="sidebar-brand">
                    🥚 Telur Josjis
                </div>
                <nav class="nav flex-column mt-3">
                    <a class="nav-link active" href="index.php?page=admin/dashboard">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                    <a class="nav-link" href="index.php?page=admin/products">
                        <i class="bi bi-box-seam"></i> Kelola Produk
                    </a>
                    <a class="nav-link" href="index.php?page=admin/users">
                        <i class="bi bi-people"></i> Kelola User
                    </a>
                    <a class="nav-link" href="index.php?page=admin/orders">
                        <i class="bi bi-cart-check"></i> Kelola Pesanan
                    </a>
                    <hr class="my-3" style="border-color: rgba(255,255,255,0.2);">
                    <a class="nav-link" href="index.php?page=logout">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </nav>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-10 p-4">
                <!-- Header -->
                <div class="mb-4">
                    <h2 class="mb-0">Dashboard Admin</h2>
                    <p class="text-muted">Welcome back, <?= $_SESSION['full_name'] ?>!</p>
                </div>
                
                <?php showFlashMessage(); ?>
                
                <!-- Statistics Cards -->
                <div class="row g-4 mb-4">
                    <div class="col-md-3">
                        <div class="card stat-card">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-gradient-primary text-white me-3">
                                    <i class="bi bi-people-fill"></i>
                                </div>
                                <div>
                                    <h6 class="text-muted mb-1">Total Users</h6>
                                    <h3 class="mb-0"><?= number_format($stats['total_users']) ?></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="card stat-card">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-gradient-success text-white me-3">
                                    <i class="bi bi-box-seam-fill"></i>
                                </div>
                                <div>
                                    <h6 class="text-muted mb-1">Total Produk</h6>
                                    <h3 class="mb-0"><?= number_format($stats['total_products']) ?></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="card stat-card">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-gradient-warning text-white me-3">
                                    <i class="bi bi-cart-check-fill"></i>
                                </div>
                                <div>
                                    <h6 class="text-muted mb-1">Total Pesanan</h6>
                                    <h3 class="mb-0"><?= number_format($stats['total_orders']) ?></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="card stat-card">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-gradient-info text-white me-3">
                                    <i class="bi bi-cash-coin"></i>
                                </div>
                                <div>
                                    <h6 class="text-muted mb-1">Total Revenue</h6>
                                    <h3 class="mb-0"><?= formatRupiah($stats['total_revenue']) ?></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Alert Cards -->
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <div class="card border-warning">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>
                                    Pesanan Pending
                                </h5>
                                <p class="card-text mb-0">
                                    <span class="fs-3 fw-bold text-warning"><?= $stats['pending_orders'] ?></span>
                                    <span class="text-muted"> pesanan menunggu konfirmasi</span>
                                </p>
                                <a href="index.php?page=admin/orders" class="btn btn-warning btn-sm mt-3">
                                    <i class="bi bi-eye me-1"></i> Lihat Pesanan
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card border-danger">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-box-seam text-danger me-2"></i>
                                    Stok Menipis
                                </h5>
                                <p class="card-text mb-0">
                                    <span class="fs-3 fw-bold text-danger"><?= $stats['low_stock_products'] ?></span>
                                    <span class="text-muted"> produk stok < 50 kg</span>
                                </p>
                                <a href="index.php?page=admin/products" class="btn btn-danger btn-sm mt-3">
                                    <i class="bi bi-plus-circle me-1"></i> Tambah Stok
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Orders -->
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="bi bi-clock-history me-2"></i>Pesanan Terbaru
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Customer</th>
                                        <th>Tanggal</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($recent_orders)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">
                                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                            Belum ada pesanan
                                        </td>
                                    </tr>
                                    <?php else: ?>
                                        <?php foreach ($recent_orders as $order): ?>
                                        <tr>
                                            <td><strong><?= $order['order_number'] ?></strong></td>
                                            <td><?= htmlspecialchars($order['full_name']) ?></td>
                                            <td><?= date('d M Y H:i', strtotime($order['created_at'])) ?></td>
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
                                                $status_text = ucfirst($order['status']);
                                                ?>
                                                <span class="badge bg-<?= $badge_class[$order['status']] ?? 'secondary' ?>">
                                                    <?= $status_text ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="index.php?page=admin/orders&action=view&id=<?= $order['order_id'] ?>" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-white text-end">
                        <a href="index.php?page=admin/orders" class="btn btn-primary">
                            Lihat Semua Pesanan <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
