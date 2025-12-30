<?php
requireAdmin();
$page_title = 'Kelola User';
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
        }
        
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 1rem 1.5rem;
            border-left: 3px solid transparent;
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background: rgba(255,255,255,0.1);
            border-left-color: white;
        }
        
        .sidebar .nav-link i {
            margin-right: 10px;
        }
        
        .sidebar-brand {
            padding: 1.5rem;
            font-size: 1.5rem;
            font-weight: 800;
            color: white;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .user-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.2rem;
            flex-shrink: 0;
        }
        
        /* FIX: Prevent modal content from appearing outside */
        .modal {
            position: fixed !important;
            z-index: 9999 !important;
        }
        
        .modal-backdrop {
            z-index: 9998 !important;
        }
        
        /* Hide any orphaned user info outside table */
        body > .user-avatar,
        body > div:not(.container-fluid):not(.modal):not(.modal-backdrop):has(.user-avatar) {
            display: none !important;
        }
        
        /* Ensure table elements stay in place */
        tbody tr,
        tbody tr td {
            position: static !important;
        }
        
        .btn-group {
            position: relative !important;
        }
        
        /* Table hover effect */
        tbody tr:hover {
            background-color: rgba(102, 126, 234, 0.05);
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
                    <a class="nav-link" href="index.php?page=admin/dashboard">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                    <a class="nav-link" href="index.php?page=admin/products">
                        <i class="bi bi-box-seam"></i> Kelola Produk
                    </a>
                    <a class="nav-link active" href="index.php?page=admin/users">
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
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Kelola User Reseller</h2>
                    <div>
                        <input type="text" class="form-control" id="searchUser" placeholder="Cari user...">
                    </div>
                </div>
                
                <?php showFlashMessage(); ?>
                
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>User</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Subscription</th>
                                        <th>Total Orders</th>
                                        <th>Total Spent</th>
                                        <th>Status</th>
                                        <th>Joined</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($users)): ?>
                                    <tr>
                                        <td colspan="9" class="text-center py-4 text-muted">
                                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                            Belum ada user reseller
                                        </td>
                                    </tr>
                                    <?php else: ?>
                                        <?php foreach ($users as $user): ?>
                                        <?php 
                                        $stats = User::getStats($user['user_id']);
                                        $initial = strtoupper(substr($user['full_name'], 0, 1));
                                        ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="user-avatar me-3"><?= $initial ?></div>
                                                    <div>
                                                        <strong><?= htmlspecialchars($user['full_name']) ?></strong><br>
                                                        <small class="text-muted">ID: <?= $user['user_id'] ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?= htmlspecialchars($user['email']) ?></td>
                                            <td><?= htmlspecialchars($user['phone']) ?></td>
                                            <td>
                                                <?php if ($user['tier_name'] == 'Premium'): ?>
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="bi bi-gem"></i> Premium
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">
                                                        <i class="bi bi-box"></i> Basic
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= number_format($stats['total_orders']) ?></td>
                                            <td><strong><?= formatRupiah($stats['total_spent']) ?></strong></td>
                                            <td>
                                                <?php if ($user['status'] == 'active'): ?>
                                                    <span class="badge bg-success">Active</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Inactive</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= date('d M Y', strtotime($user['created_at'])) ?></td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button class="btn btn-outline-primary" data-bs-toggle="modal" 
                                                            data-bs-target="#viewUserModal<?= $user['user_id'] ?>"
                                                            title="Lihat Detail">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                    <?php if ($user['status'] == 'active'): ?>
                                                        <a href="index.php?page=admin/users&action=toggle-status&id=<?= $user['user_id'] ?>&status=active" 
                                                           class="btn btn-outline-danger"
                                                           onclick="return confirm('Yakin ingin menonaktifkan user ini?')"
                                                           title="Nonaktifkan">
                                                            <i class="bi bi-x-circle"></i>
                                                        </a>
                                                    <?php else: ?>
                                                        <a href="index.php?page=admin/users&action=toggle-status&id=<?= $user['user_id'] ?>&status=inactive" 
                                                           class="btn btn-outline-success"
                                                           title="Aktifkan">
                                                            <i class="bi bi-check-circle"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- User Detail Modals (Outside Table Loop) -->
    <?php if (!empty($users)): ?>
        <?php foreach ($users as $user): ?>
        <?php $stats = User::getStats($user['user_id']); ?>
        <div class="modal fade" id="viewUserModal<?= $user['user_id'] ?>" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail User - <?= htmlspecialchars($user['full_name']) ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="fw-bold mb-3">Informasi Personal</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>Nama Lengkap:</strong></td>
                                        <td><?= htmlspecialchars($user['full_name']) ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Email:</strong></td>
                                        <td><?= htmlspecialchars($user['email']) ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Phone:</strong></td>
                                        <td><?= htmlspecialchars($user['phone']) ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Alamat:</strong></td>
                                        <td><?= htmlspecialchars($user['address']) ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Status:</strong></td>
                                        <td>
                                            <span class="badge bg-<?= $user['status'] == 'active' ? 'success' : 'danger' ?>">
                                                <?= ucfirst($user['status']) ?>
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold mb-3">Statistik</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>Subscription:</strong></td>
                                        <td>
                                            <span class="badge bg-<?= $user['tier_name'] == 'Premium' ? 'warning' : 'secondary' ?>">
                                                <?= $user['tier_name'] ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total Orders:</strong></td>
                                        <td><?= number_format($stats['total_orders']) ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total Spent:</strong></td>
                                        <td><strong class="text-success"><?= formatRupiah($stats['total_spent']) ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Last Order:</strong></td>
                                        <td>
                                            <?php if ($stats['last_order_date']): ?>
                                                <?= date('d M Y', strtotime($stats['last_order_date'])) ?>
                                            <?php else: ?>
                                                <em class="text-muted">Belum pernah order</em>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Joined:</strong></td>
                                        <td><?= date('d M Y H:i', strtotime($user['created_at'])) ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                        <?php if ($user['tier_name'] == 'Premium'): ?>
                        <div class="alert alert-warning mt-3">
                            <strong><i class="bi bi-gem me-2"></i>Premium Member</strong><br>
                            <small>
                                Start: <?= $user['subscription_start'] ? date('d M Y', strtotime($user['subscription_start'])) : '-' ?><br>
                                End: <?= $user['subscription_end'] ? date('d M Y', strtotime($user['subscription_end'])) : '-' ?>
                            </small>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <a href="index.php?page=admin/orders&user_id=<?= $user['user_id'] ?>" class="btn btn-primary">
                            <i class="bi bi-cart-check me-2"></i>Lihat Orders
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Simple search functionality
        document.getElementById('searchUser').addEventListener('keyup', function() {
            const searchText = this.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchText) ? '' : 'none';
            });
        });
        
        // Force hide any orphaned user elements (safety net)
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                document.querySelectorAll('body > div:not(.container-fluid):not(.modal):not(.modal-backdrop)').forEach(el => {
                    if (el.querySelector('.user-avatar') || (el.textContent.includes('ID:') && !el.closest('.modal'))) {
                        el.remove();
                    }
                });
            }, 100);
        });
    </script>
</body>
</html>
