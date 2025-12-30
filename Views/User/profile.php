<?php
requireLogin();
$page_title = 'Profil Saya';
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
                    <li class="nav-item"><a class="nav-link" href="index.php?page=orders"><i class="bi bi-box-seam me-1"></i>Orders</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle active" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i><?= $_SESSION['full_name'] ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item active" href="index.php?page=profile"><i class="bi bi-person me-2"></i>Profile</a></li>
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
        <h2 class="mb-4"><i class="bi bi-person-circle me-2"></i>Profil Saya</h2>

        <?php showFlashMessage(); ?>

        <div class="row">
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-body text-center py-4">
                        <div class="mb-3">
                            <i class="bi bi-person-circle display-1 text-primary"></i>
                        </div>
                        <h4><?= htmlspecialchars($user['full_name']) ?></h4>
                        <p class="text-muted mb-2"><?= htmlspecialchars($user['email']) ?></p>
                        <span class="badge <?= $user['tier_name'] == 'Premium' ? 'badge-premium' : 'bg-secondary' ?> fs-6">
                            <?php if ($user['tier_name'] == 'Premium'): ?>
                                <i class="bi bi-gem"></i> Premium
                            <?php else: ?>
                                Basic
                            <?php endif; ?>
                        </span>
                    </div>
                </div>

                <?php if ($user['tier_name'] == 'Basic'): ?>
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title"><i class="bi bi-rocket-takeoff me-2"></i>Upgrade ke Premium</h6>
                        <p class="small text-muted">Dapatkan benefit eksklusif dengan upgrade!</p>
                        <a href="index.php?page=subscription" class="btn btn-warning w-100">
                            <i class="bi bi-gem me-2"></i>Upgrade Sekarang
                        </a>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Informasi Akun</h5>
                    </div>
                    <div class="card-body">
                        <form action="index.php?page=profile&action=edit" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" name="full_name" class="form-control" value="<?= htmlspecialchars($user['full_name']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" readonly>
                                <small class="text-muted">Email tidak dapat diubah</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">No. Telepon</label>
                                <input type="tel" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Alamat</label>
                                <textarea name="address" class="form-control" rows="3" required><?= htmlspecialchars($user['address']) ?></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Simpan Perubahan
                            </button>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Ubah Password</h5>
                    </div>
                    <div class="card-body">
                        <form action="index.php?page=profile&action=change-password" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Password Lama</label>
                                <input type="password" name="current_password" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Password Baru</label>
                                <input type="password" name="new_password" class="form-control" minlength="6" required>
                                <small class="text-muted">Minimal 6 karakter</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Konfirmasi Password Baru</label>
                                <input type="password" name="confirm_password" class="form-control" minlength="6" required>
                            </div>

                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-shield-lock me-2"></i>Ubah Password
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
