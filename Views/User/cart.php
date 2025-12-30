<?php
requireLogin();
require_once __DIR__ . '/../../Config/midtrans_config.php';
$page_title = 'Keranjang Belanja';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?> - Telur Josjis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- Midtrans Snap.js -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="<?= MIDTRANS_CLIENT_KEY ?>">
    </script>

    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
        }

        .navbar-custom {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        }

        .cart-item {
            border-radius: 10px;
            border: 1px solid #e9ecef;
            transition: all 0.3s;
        }

        .cart-item:hover {
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
                    <li class="nav-item"><a class="nav-link active" href="index.php?page=cart"><i class="bi bi-cart3 me-1"></i>Cart</a></li>
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
        <h2 class="mb-4"><i class="bi bi-cart3 me-2"></i>Keranjang Belanja</h2>

        <?php showFlashMessage(); ?>

        <?php if (empty($cart_items)): ?>
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-cart-x display-1 text-muted"></i>
                    <h4 class="mt-3">Keranjang Anda Kosong</h4>
                    <p class="text-muted">Belum ada produk di keranjang Anda</p>
                    <a href="index.php?page=catalog" class="btn btn-primary mt-3">
                        <i class="bi bi-shop me-2"></i>Mulai Belanja
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="row">
                <div class="col-lg-8">
                    <?php foreach ($cart_items as $item): ?>
                    <div class="card cart-item mb-3">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-2 text-center">
                                    <div style="font-size: 3rem;">🥚</div>
                                </div>
                                <div class="col-md-4">
                                    <h5><?= htmlspecialchars($item['product_name']) ?></h5>
                                    <p class="text-muted mb-0"><?= formatRupiah($item['price_per_kg']) ?> / kg</p>
                                </div>
                                <div class="col-md-3">
                                    <form action="index.php?page=cart&action=update" method="POST" class="d-flex align-items-center">
                                        <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                                        <input type="number" 
                                               name="quantity" 
                                               class="form-control form-control-sm" 
                                               value="<?= $item['quantity'] ?>" 
                                               min="0.5" 
                                               step="0.5" 
                                               onchange="this.form.submit()">
                                        <span class="ms-2">kg</span>
                                    </form>
                                </div>
                                <div class="col-md-2 text-end">
                                    <h5 class="text-primary mb-0"><?= formatRupiah($item['item_total']) ?></h5>
                                </div>
                                <div class="col-md-1 text-end">
                                    <a href="index.php?page=cart&action=remove&id=<?= $item['product_id'] ?>" 
                                       class="btn btn-sm btn-outline-danger"
                                       onclick="return confirm('Hapus produk dari keranjang?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>

                    <div class="d-flex justify-content-between">
                        <a href="index.php?page=catalog" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Lanjut Belanja
                        </a>
                        <a href="index.php?page=cart&action=clear" 
                           class="btn btn-outline-danger"
                           onclick="return confirm('Kosongkan keranjang?')">
                            <i class="bi bi-trash me-2"></i>Kosongkan Keranjang
                        </a>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Ringkasan Pesanan -->
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Ringkasan Pesanan</h5>
                            <hr>

                            <div class="d-flex justify-content-between mb-2">
                                <span>Total Berat:</span>
                                <strong><?= number_format($total_kg, 1) ?> kg</strong>
                            </div>

                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span><?= formatRupiah($subtotal) ?></span>
                            </div>

                            <?php if ($discount > 0): ?>
                            <div class="d-flex justify-content-between mb-2 text-success">
                                <span>
                                    <i class="bi bi-gem me-1"></i>Diskon Premium (10%):
                                </span>
                                <span>-<?= formatRupiah($discount) ?></span>
                            </div>
                            <?php endif; ?>

                            <hr>

                            <div class="d-flex justify-content-between mb-3">
                                <h5>Total:</h5>
                                <h5 class="text-primary"><?= formatRupiah($total) ?></h5>
                            </div>

                            <?php if ($user['tier_name'] == 'Premium' && $total_kg > 100): ?>
                                <div class="alert alert-success py-2">
                                    <small><i class="bi bi-check-circle me-1"></i>Diskon 10% diterapkan!</small>
                                </div>
                            <?php elseif ($user['tier_name'] == 'Premium' && $total_kg <= 100): ?>
                                <div class="alert alert-info py-2">
                                    <small><i class="bi bi-info-circle me-1"></i>Beli >100kg untuk diskon 10%!</small>
                                </div>
                            <?php elseif ($user['tier_name'] == 'Basic'): ?>
                                <div class="alert alert-warning py-2">
                                    <small><i class="bi bi-gem me-1"></i>
                                        <a href="index.php?page=subscription">Upgrade Premium</a> untuk diskon!
                                    </small>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- FORM CHECKOUT MIDTRANS -->
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-3">
                                <i class="bi bi-credit-card me-2"></i>Checkout
                            </h5>

                            <form id="checkout-form">
                                <div class="mb-3">
                                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           name="name" 
                                           id="customer-name"
                                           class="form-control" 
                                           placeholder="Nama lengkap" 
                                           value="<?= htmlspecialchars($user['full_name'] ?? '') ?>"
                                           required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" 
                                           name="email" 
                                           id="customer-email"
                                           class="form-control" 
                                           placeholder="email@example.com" 
                                           value="<?= htmlspecialchars($user['email'] ?? '') ?>"
                                           required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">No. Telepon <span class="text-danger">*</span></label>
                                    <input type="tel" 
                                           name="phone" 
                                           id="customer-phone"
                                           class="form-control" 
                                           placeholder="08xxxxxxxxxx" 
                                           pattern="[0-9]{10,15}"
                                           value="<?= htmlspecialchars($user['phone'] ?? '') ?>"
                                           required>
                                    <small class="text-muted">Format: 08xxx (10-15 digit)</small>
                                </div>

                                <!-- Hidden fields untuk cart data -->
                                <input type="hidden" 
                                       name="items" 
                                       id="cart-items" 
                                       value='<?= htmlspecialchars(json_encode(array_map(function($item) {
                                           return [
                                               'id' => $item['product_id'],
                                               'name' => $item['product_name'],
                                               'price' => $item['price_per_kg'],
                                               'qty' => $item['quantity']
                                           ];
                                       }, $cart_items))) ?>'>
                                
                                <input type="hidden" 
                                       name="total" 
                                       id="cart-total" 
                                       value="<?= $total ?>">

                                <!-- Checkout Button -->
                                <button type="button" 
                                        id="checkout-btn" 
                                        class="btn btn-primary w-100 btn-lg">
                                    <i class="bi bi-credit-card me-2"></i>Bayar Sekarang
                                </button>

                                <small class="text-muted d-block mt-2 text-center">
                                    <i class="bi bi-shield-check me-1"></i>Pembayaran aman dengan Midtrans
                                </small>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Midtrans Cart Script -->
    <script src="assets/js/midtrans_cart.js"></script>
</body>
</html>