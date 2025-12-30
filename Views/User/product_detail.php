<?php
requireLogin();
$page_title = 'Detail Produk';

// Calculate available stock
$is_premium = ($user['tier_name'] == 'Premium');
$available_stock = $is_premium ? 
    ($product['stock_priority_kg'] + $product['stock_kg']) : 
    $product['stock_kg'];
$is_available = $available_stock > 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product['product_name']) ?> - Telur Josjis</title>
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
        
        .product-image {
            width: 100%;
            height: 400px;
            background: linear-gradient(135deg, #667eea20 0%, #764ba220 100%);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10rem;
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
                    <li class="nav-item"><a class="nav-link active" href="index.php?page=catalog"><i class="bi bi-shop me-1"></i>Catalog</a></li>
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
                <li class="breadcrumb-item"><a href="index.php?page=catalog">Catalog</a></li>
                <li class="breadcrumb-item active"><?= htmlspecialchars($product['product_name']) ?></li>
            </ol>
        </nav>

        <?php showFlashMessage(); ?>

        <div class="row">
            <div class="col-md-6">
                <div class="product-image">
                    🥚
                </div>
            </div>

            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <span class="badge bg-secondary mb-2"><?= htmlspecialchars($product['category_name']) ?></span>
                        <h2 class="mb-3"><?= htmlspecialchars($product['product_name']) ?></h2>
                        
                        <div class="mb-4">
                            <h3 class="text-primary"><?= formatRupiah($product['price_per_kg']) ?></h3>
                            <p class="text-muted">per kilogram</p>
                        </div>

                        <div class="mb-4">
                            <h6>Deskripsi:</h6>
                            <p class="text-muted"><?= nl2br(htmlspecialchars($product['description'])) ?></p>
                        </div>

                        <div class="mb-4">
                            <h6>Stok Tersedia:</h6>
                            <p class="<?= $available_stock < 50 ? 'text-danger' : 'text-success' ?> fw-bold fs-4">
                                <?= number_format($available_stock, 1) ?> kg
                            </p>
                            <?php if ($is_premium): ?>
                                <small class="text-muted">
                                    <i class="bi bi-star-fill text-warning"></i> 
                                    Termasuk stok prioritas premium
                                </small>
                            <?php endif; ?>
                        </div>

                        <?php if ($is_available): ?>
                            <form action="index.php?page=cart&action=add" method="POST">
                                <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Jumlah (kg):</label>
                                    <input type="number" 
                                           name="quantity" 
                                           class="form-control form-control-lg" 
                                           min="1" 
                                           max="<?= $available_stock ?>" 
                                           step="0.5" 
                                           value="1" 
                                           required>
                                    <small class="text-muted">Max: <?= number_format($available_stock, 1) ?> kg</small>
                                </div>

                                <button type="submit" class="btn btn-primary btn-lg w-100 mb-2">
                                    <i class="bi bi-cart-plus me-2"></i>Tambah ke Keranjang
                                </button>
                            </form>
                        <?php else: ?>
                            <button class="btn btn-secondary btn-lg w-100" disabled>
                                <i class="bi bi-x-circle me-2"></i>Stok Habis
                            </button>
                        <?php endif; ?>

                        <a href="index.php?page=catalog" class="btn btn-outline-secondary w-100 mt-2">
                            <i class="bi bi-arrow-left me-2"></i>Kembali ke Catalog
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
