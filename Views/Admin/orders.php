<?php
requireAdmin();
$page_title = 'Kelola Pesanan';
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
        
        .filter-tabs .nav-link {
            border: none;
            color: #666;
        }
        
        .filter-tabs .nav-link.active {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
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
                    <a class="nav-link" href="index.php?page=admin/users">
                        <i class="bi bi-people"></i> Kelola User
                    </a>
                    <a class="nav-link active" href="index.php?page=admin/orders">
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
                <h2 class="mb-4">Kelola Pesanan</h2>
                
                <?php showFlashMessage(); ?>
                
                <!-- Filter Tabs -->
                <ul class="nav nav-pills filter-tabs mb-4">
                    <li class="nav-item">
                        <a class="nav-link active" href="#" data-status="all">
                            <i class="bi bi-list-ul me-2"></i>Semua
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-status="pending">
                            <i class="bi bi-clock me-2"></i>Pending
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-status="processing">
                            <i class="bi bi-gear me-2"></i>Processing
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-status="shipped">
                            <i class="bi bi-truck me-2"></i>Shipped
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-status="delivered">
                            <i class="bi bi-check-circle me-2"></i>Delivered
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-status="cancelled">
                            <i class="bi bi-x-circle me-2"></i>Cancelled
                        </a>
                    </li>
                </ul>
                
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="ordersTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Customer</th>
                                        <th>Tanggal</th>
                                        <th>Items</th>
                                        <th>Total Qty</th>
                                        <th>Total Amount</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($orders)): ?>
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-muted">
                                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                            Belum ada pesanan
                                        </td>
                                    </tr>
                                    <?php else: ?>
                                        <?php foreach ($orders as $order): ?>
                                        <tr data-status="<?= $order['status'] ?>">
                                            <td><strong><?= $order['order_number'] ?></strong></td>
                                            <td>
                                                <?= htmlspecialchars($order['full_name']) ?><br>
                                                <small class="text-muted"><?= htmlspecialchars($order['email']) ?></small>
                                            </td>
                                            <td><?= date('d M Y H:i', strtotime($order['created_at'])) ?></td>
                                            <td><?= $order['total_items'] ?> item(s)</td>
                                            <td><?= number_format($order['total_quantity_kg'], 1) ?> kg</td>
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
                                                <button class="btn btn-sm btn-outline-primary" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#orderDetailModal<?= $order['order_id'] ?>">
                                                    <i class="bi bi-eye"></i> Detail
                                                </button>
                                            </td>
                                        </tr>
                                        
                                        <!-- Modal Order Detail -->
                                        <div class="modal fade" id="orderDetailModal<?= $order['order_id'] ?>" tabindex="-1">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Order Detail - <?= $order['order_number'] ?></h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <?php 
                                                        $order_detail = Order::getById($order['order_id']); 
                                                        $order_items = Order::getItems($order['order_id']);
                                                        ?>
                                                        
                                                        <div class="row mb-4">
                                                            <div class="col-md-6">
                                                                <h6 class="fw-bold">Customer Information</h6>
                                                                <table class="table table-sm">
                                                                    <tr>
                                                                        <td><strong>Name:</strong></td>
                                                                        <td><?= htmlspecialchars($order_detail['full_name']) ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><strong>Email:</strong></td>
                                                                        <td><?= htmlspecialchars($order_detail['email']) ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><strong>Phone:</strong></td>
                                                                        <td><?= htmlspecialchars($order_detail['phone']) ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><strong>Address:</strong></td>
                                                                        <td><?= htmlspecialchars($order_detail['address']) ?></td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <h6 class="fw-bold">Order Information</h6>
                                                                <table class="table table-sm">
                                                                    <tr>
                                                                        <td><strong>Order Date:</strong></td>
                                                                        <td><?= date('d M Y H:i', strtotime($order_detail['created_at'])) ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><strong>Status:</strong></td>
                                                                        <td>
                                                                            <span class="badge bg-<?= $badge_class[$order_detail['status']] ?? 'secondary' ?>">
                                                                                <?= ucfirst($order_detail['status']) ?>
                                                                            </span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><strong>Payment:</strong></td>
                                                                        <td>
                                                                            <?php if ($order_detail['payment_status'] == 'paid'): ?>
                                                                                <span class="badge bg-success">Paid</span>
                                                                            <?php else: ?>
                                                                                <span class="badge bg-warning">Unpaid</span>
                                                                            <?php endif; ?>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        
                                                        <h6 class="fw-bold">Order Items</h6>
                                                        <div class="table-responsive">
                                                            <table class="table table-sm table-bordered">
                                                                <thead class="table-light">
                                                                    <tr>
                                                                        <th>Product</th>
                                                                        <th>Price/kg</th>
                                                                        <th>Quantity</th>
                                                                        <th>Subtotal</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php foreach ($order_items as $item): ?>
                                                                    <tr>
                                                                        <td><?= htmlspecialchars($item['product_name']) ?></td>
                                                                        <td><?= formatRupiah($item['price_per_kg']) ?></td>
                                                                        <td><?= number_format($item['quantity_kg'], 1) ?> kg</td>
                                                                        <td><strong><?= formatRupiah($item['subtotal']) ?></strong></td>
                                                                    </tr>
                                                                    <?php endforeach; ?>
                                                                </tbody>
                                                                <tfoot>
                                                                    <tr>
                                                                        <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                                                                        <td><strong><?= formatRupiah($order_detail['subtotal']) ?></strong></td>
                                                                    </tr>
                                                                    <?php if ($order_detail['discount'] > 0): ?>
                                                                    <tr>
                                                                        <td colspan="3" class="text-end"><strong>Discount (10%):</strong></td>
                                                                        <td class="text-success"><strong>-<?= formatRupiah($order_detail['discount']) ?></strong></td>
                                                                    </tr>
                                                                    <?php endif; ?>
                                                                    <tr class="table-primary">
                                                                        <td colspan="3" class="text-end"><strong>TOTAL:</strong></td>
                                                                        <td><strong><?= formatRupiah($order_detail['total_amount']) ?></strong></td>
                                                                    </tr>
                                                                </tfoot>
                                                            </table>
                                                        </div>
                                                        
                                                        <h6 class="fw-bold mt-4">Update Status</h6>
                                                        <form method="POST" action="index.php?page=admin/orders&action=update-status&id=<?= $order['order_id'] ?>">
                                                            <div class="input-group">
                                                                <select name="status" class="form-select" required>
                                                                    <option value="pending" <?= $order_detail['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                                                    <option value="processing" <?= $order_detail['status'] == 'processing' ? 'selected' : '' ?>>Processing</option>
                                                                    <option value="shipped" <?= $order_detail['status'] == 'shipped' ? 'selected' : '' ?>>Shipped</option>
                                                                    <option value="delivered" <?= $order_detail['status'] == 'delivered' ? 'selected' : '' ?>>Delivered</option>
                                                                    <option value="cancelled" <?= $order_detail['status'] == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                                                </select>
                                                                <button type="submit" class="btn btn-primary">
                                                                    <i class="bi bi-check-circle me-2"></i>Update Status
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Filter by status
        document.querySelectorAll('.filter-tabs .nav-link').forEach(tab => {
            tab.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Update active tab
                document.querySelectorAll('.filter-tabs .nav-link').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                
                const status = this.dataset.status;
                const rows = document.querySelectorAll('#ordersTable tbody tr');
                
                rows.forEach(row => {
                    if (status === 'all' || row.dataset.status === status) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
    </script>
</body>
</html>
