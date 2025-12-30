<?php
requireLogin();
$page_title = 'Detail Pesanan';

$badge_class = [
    'pending' => 'warning',
    'processing' => 'info',
    'shipped' => 'primary',
    'delivered' => 'success',
    'completed' => 'success',
    'cancelled' => 'danger'
];
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
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php?page=orders">Pesanan</a></li>
                <li class="breadcrumb-item active"><?= $order['order_number'] ?></li>
            </ol>
        </nav>

        <?php showFlashMessage(); ?>

        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Detail Pesanan</h5>
                        <span class="badge bg-<?= $badge_class[$order['status']] ?? 'secondary' ?> fs-6">
                            <?= ucfirst($order['status']) ?>
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h6 class="text-muted">Order ID</h6>
                                <p class="mb-0"><strong><?= $order['order_number'] ?></strong></p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-muted">Tanggal Pesanan</h6>
                                <p class="mb-0"><?= date('d M Y, H:i', strtotime($order['created_at'])) ?></p>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <h6 class="mb-3">Informasi Pengiriman</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Nama:</strong></p>
                                <p><?= htmlspecialchars($order['full_name']) ?></p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Email:</strong></p>
                                <p><?= htmlspecialchars($order['email']) ?></p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Telepon:</strong></p>
                                <p><?= htmlspecialchars($order['phone']) ?></p>
                            </div>
                            <div class="col-md-12">
                                <p class="mb-1"><strong>Alamat:</strong></p>
                                <p><?= nl2br(htmlspecialchars($order['address'])) ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Item Pesanan</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Produk</th>
                                        <th class="text-end">Harga</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($order_items as $item): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div style="font-size: 2rem; margin-right: 10px;">🥚</div>
                                                <strong><?= htmlspecialchars($item['product_name']) ?></strong>
                                            </div>
                                        </td>
                                        <td class="text-end"><?= formatRupiah($item['price_per_kg']) ?></td>
                                        <td class="text-center"><?= number_format($item['quantity_kg'], 1) ?> kg</td>
                                        <td class="text-end"><strong><?= formatRupiah($item['subtotal']) ?></strong></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Ringkasan Pembayaran</h5>
                        <hr>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Berat:</span>
                            <strong><?= number_format($order['total_quantity_kg'], 1) ?> kg</strong>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span><?= formatRupiah($order['subtotal']) ?></span>
                        </div>
                        
                        <?php if ($order['discount'] > 0): ?>
                        <div class="d-flex justify-content-between mb-2 text-success">
                            <span><i class="bi bi-gem me-1"></i>Diskon:</span>
                            <span>-<?= formatRupiah($order['discount']) ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <hr>
                        
                        <div class="d-flex justify-content-between">
                            <h5>Total:</h5>
                            <h5 class="text-primary"><?= formatRupiah($order['total_amount']) ?></h5>
                        </div>
                    </div>
                </div>

                <?php if ($order['status'] == 'pending'): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <h6 class="card-title text-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>Menunggu Pembayaran
                        </h6>
                        <p class="small mb-3">Silakan lakukan pembayaran sesuai total di atas.</p>
                        
                        <a href="index.php?page=orders&action=cancel&id=<?= $order['order_id'] ?>" 
                           class="btn btn-danger w-100"
                           onclick="return confirm('Batalkan pesanan ini?')">
                            <i class="bi bi-x-circle me-2"></i>Batalkan Pesanan
                        </a>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Premium Feature: Export PDF -->
                <?php if ($_SESSION['tier_name'] == 'Premium'): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <i class="bi bi-file-earmark-pdf-fill fs-1 text-danger"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-1">
                                    <i class="bi bi-star-fill text-warning me-2"></i>Premium Feature
                                </h5>
                                <p class="text-muted mb-0 small">Download invoice dalam format PDF</p>
                            </div>
                        </div>
                        <a href="index.php?page=orders&action=download-pdf&order_id=<?= $order['order_id'] ?>" 
                           class="btn btn-danger w-100 mt-3">
                            <i class="bi bi-download me-2"></i>Download PDF
                        </a>
                    </div>
                </div>
                <?php else: ?>
                <div class="card mb-3 border-warning">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <i class="bi bi-lock-fill fs-1 text-warning"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-1">
                                    <i class="bi bi-star-fill text-warning me-2"></i>Unlock Premium
                                </h5>
                                <p class="text-muted mb-0 small">Upgrade ke Premium untuk download invoice PDF</p>
                            </div>
                        </div>
                        <a href="index.php?page=subscription" class="btn btn-warning w-100 mt-3">
                            <i class="bi bi-lightning-fill me-2"></i>Upgrade Premium
                        </a>
                    </div>
                </div>
                <?php endif; ?>

                <a href="index.php?page=orders" class="btn btn-outline-secondary w-100">
                    <i class="bi bi-arrow-left me-2"></i>Kembali ke Pesanan
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
