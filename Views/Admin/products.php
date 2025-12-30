<?php
requireAdmin();
$page_title = 'Kelola Produk';
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
        
        .product-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
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
                    <a class="nav-link active" href="index.php?page=admin/products">
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
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Kelola Produk</h2>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                        <i class="bi bi-plus-circle me-2"></i>Tambah Produk
                    </button>
                </div>
                
                <?php showFlashMessage(); ?>
                
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama Produk</th>
                                        <th>Kategori</th>
                                        <th>Harga/kg</th>
                                        <th>Stok Regular</th>
                                        <th>Stok Priority</th>
                                        <th>Total Stok</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($products)): ?>
                                    <tr>
                                        <td colspan="9" class="text-center py-4 text-muted">
                                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                            Belum ada produk
                                        </td>
                                    </tr>
                                    <?php else: ?>
                                        <?php foreach ($products as $product): ?>
                                        <?php 
                                        $total_stock = $product['stock_kg'] + $product['stock_priority_kg'];
                                        $stock_class = $total_stock < 50 ? 'text-danger' : ($total_stock < 100 ? 'text-warning' : 'text-success');
                                        ?>
                                        <tr>
                                            <td><?= $product['product_id'] ?></td>
                                            <td>
                                                <strong><?= htmlspecialchars($product['product_name']) ?></strong><br>
                                                <small class="text-muted"><?= htmlspecialchars(substr($product['description'], 0, 50)) ?>...</small>
                                            </td>
                                            <td><?= htmlspecialchars($product['category_name'] ?? '-') ?></td>
                                            <td><strong><?= formatRupiah($product['price_per_kg']) ?></strong></td>
                                            <td><?= number_format($product['stock_kg'], 1) ?> kg</td>
                                            <td><?= number_format($product['stock_priority_kg'], 1) ?> kg</td>
                                            <td class="<?= $stock_class ?>"><strong><?= number_format($total_stock, 1) ?> kg</strong></td>
                                            <td>
                                                <?php if ($total_stock > 0): ?>
                                                    <span class="badge bg-success">Available</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Out of Stock</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button class="btn btn-outline-success" data-bs-toggle="modal" 
                                                            data-bs-target="#addStockModal<?= $product['product_id'] ?>"
                                                            title="Tambah Stok">
                                                        <i class="bi bi-plus-circle"></i>
                                                    </button>
                                                    <button class="btn btn-outline-primary" data-bs-toggle="modal" 
                                                            data-bs-target="#editProductModal<?= $product['product_id'] ?>"
                                                            title="Edit">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <a href="index.php?page=admin/products&action=delete&id=<?= $product['product_id'] ?>" 
                                                       class="btn btn-outline-danger"
                                                       onclick="return confirm('Yakin ingin menghapus produk ini?')"
                                                       title="Hapus">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        
                                        <!-- Modal Add Stock -->
                                        <div class="modal fade" id="addStockModal<?= $product['product_id'] ?>" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Tambah Stok - <?= htmlspecialchars($product['product_name']) ?></h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form method="POST" action="index.php?page=admin/products&action=add-stock">
                                                        <div class="modal-body">
                                                            <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                                                            
                                                            <div class="alert alert-info">
                                                                <strong>Info:</strong> Stok akan dibagi otomatis:<br>
                                                                - 70% untuk Premium (prioritas 24 jam)<br>
                                                                - 30% untuk Regular
                                                            </div>
                                                            
                                                            <div class="mb-3">
                                                                <label class="form-label">Jumlah Stok (kg)</label>
                                                                <input type="number" name="qty" class="form-control" 
                                                                       step="0.1" min="0.1" required>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-primary">
                                                                <i class="bi bi-plus-circle me-2"></i>Tambah Stok
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Modal Edit Product -->
                                        <div class="modal fade" id="editProductModal<?= $product['product_id'] ?>" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Produk</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form method="POST" action="index.php?page=admin/products&action=edit&id=<?= $product['product_id'] ?>">
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label">Nama Produk</label>
                                                                <input type="text" name="product_name" class="form-control" 
                                                                       value="<?= htmlspecialchars($product['product_name']) ?>" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Deskripsi</label>
                                                                <textarea name="description" class="form-control" rows="3" required><?= htmlspecialchars($product['description']) ?></textarea>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Kategori</label>
                                                                <select name="category_id" class="form-select" required>
                                                                    <option value="1" <?= $product['category_id'] == 1 ? 'selected' : '' ?>>Telur Ayam Kampung</option>
                                                                    <option value="2" <?= $product['category_id'] == 2 ? 'selected' : '' ?>>Telur Ayam Negeri</option>
                                                                    <option value="3" <?= $product['category_id'] == 3 ? 'selected' : '' ?>>Telur Puyuh</option>
                                                                    <option value="4" <?= $product['category_id'] == 4 ? 'selected' : '' ?>>Telur Bebek</option>
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Harga per Kg</label>
                                                                <input type="number" name="price_per_kg" class="form-control" 
                                                                       step="100" min="0" value="<?= $product['price_per_kg'] ?>" required>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-primary">
                                                                <i class="bi bi-save me-2"></i>Simpan
                                                            </button>
                                                        </div>
                                                    </form>
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
    
    <!-- Modal Add Product -->
    <div class="modal fade" id="addProductModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Produk Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="index.php?page=admin/products&action=add">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Produk</label>
                            <input type="text" name="product_name" class="form-control" 
                                   placeholder="Contoh: Telur Ayam Kampung Grade A" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="description" class="form-control" rows="3" 
                                      placeholder="Deskripsi produk..." required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <select name="category_id" class="form-select" required>
                                <option value="">Pilih Kategori</option>
                                <option value="1">Telur Ayam Kampung</option>
                                <option value="2">Telur Ayam Negeri</option>
                                <option value="3">Telur Puyuh</option>
                                <option value="4">Telur Bebek</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Harga per Kg</label>
                            <input type="number" name="price_per_kg" class="form-control" 
                                   step="100" min="0" placeholder="25000" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Stok Awal (kg)</label>
                            <input type="number" name="stock_kg" class="form-control" 
                                   step="0.1" min="0" placeholder="100" required>
                            <div class="form-text">Stok akan dibagi otomatis: 70% Priority, 30% Regular</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>Tambah Produk
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
