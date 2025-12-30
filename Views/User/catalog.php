<?php
requireLogin();
$page_title = 'Catalog Produk';
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
        
        .product-card {
            border-radius: 15px;
            border: 2px solid #e9ecef;
            transition: all 0.3s;
            height: 100%;
        }
        
        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            border-color: var(--primary-color);
        }
        
        .product-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 15px 15px 0 0;
            background: linear-gradient(135deg, #667eea20 0%, #764ba220 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 5rem;
        }
        
        .badge-stock {
            position: absolute;
            top: 10px;
            right: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Navbar (sama dengan dashboard) -->
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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-shop me-2"></i>Catalog Produk</h2>
            <?php if ($is_premium): ?>
                <span class="badge badge-premium fs-6">
                    <i class="bi bi-gem me-1"></i>Premium Access
                </span>
            <?php endif; ?>
        </div>

        <?php showFlashMessage(); ?>

        <?php if ($is_premium): ?>
            <div class="alert alert-success">
                <i class="bi bi-star-fill me-2"></i>
                <strong>Premium Benefit:</strong> Anda mendapatkan akses ke stok prioritas 24 jam lebih awal dan diskon 10% untuk pembelian >100kg!
            </div>
        <?php endif; ?>

        <!-- Products Grid -->
        <div class="row g-4">
            <?php if (empty($products)): ?>
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="bi bi-inbox display-1 text-muted"></i>
                        <p class="text-muted mt-3">Belum ada produk</p>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($products as $product): ?>
                <?php 
                $available_stock = $is_premium ? 
                    ($product['stock_priority_kg'] + $product['stock_kg']) : 
                    $product['stock_kg'];
                $is_available = $available_stock > 0;
                ?>
                <div class="col-md-4">
                    <div class="card product-card position-relative">
                        <?php if ($is_available): ?>
                            <span class="badge bg-success badge-stock">Available</span>
                        <?php else: ?>
                            <span class="badge bg-danger badge-stock">Out of Stock</span>
                        <?php endif; ?>
                        
                        <div class="product-img">
                            🥚
                        </div>
                        
                        <div class="card-body">
                            <span class="badge bg-secondary mb-2"><?= htmlspecialchars($product['category_name']) ?></span>
                            <h5 class="card-title"><?= htmlspecialchars($product['product_name']) ?></h5>
                            <p class="text-muted small"><?= htmlspecialchars(substr($product['description'], 0, 80)) ?>...</p>
                            
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h4 class="text-primary mb-0"><?= formatRupiah($product['price_per_kg']) ?></h4>
                                    <small class="text-muted">per kg</small>
                                </div>
                                <div class="text-end">
                                    <small class="text-muted">Stok:</small><br>
                                    <strong class="<?= $available_stock < 50 ? 'text-danger' : 'text-success' ?>">
                                        <?= number_format($available_stock, 1) ?> kg
                                    </strong>
                                </div>
                            </div>
                            
                            <?php if ($is_available): ?>
                                <a href="index.php?page=catalog&action=detail&id=<?= $product['product_id'] ?>" 
                                   class="btn btn-primary w-100">
                                    <i class="bi bi-cart-plus me-2"></i>Beli Sekarang
                                </a>
                            <?php else: ?>
                                <button class="btn btn-secondary w-100" disabled>
                                    <i class="bi bi-x-circle me-2"></i>Stok Habis
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
