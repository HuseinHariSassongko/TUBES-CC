<?php
require_once __DIR__ . '/../Models/Product.php';
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Models/Order.php';
require_once __DIR__ . '/../helpers/session.php';
require_once __DIR__ . '/../helpers/functions.php';

class AdminController {
    
    // Dashboard Overview
    public static function dashboard() {
        requireAdmin();
        
        // Get statistics
        $stats = [
            'total_users' => self::getTotalUsers(),
            'total_products' => self::getTotalProducts(),
            'total_orders' => self::getTotalOrders(),
            'total_revenue' => self::getTotalRevenue(),
            'pending_orders' => self::getPendingOrders(),
            'low_stock_products' => self::getLowStockProducts()
        ];
        
        // Recent orders
        $recent_orders = Order::getRecent(5);
        
        include __DIR__ . '/../Views/Admin/dashboard.php';
    }
    
    // Products Management
// Products Management
public static function products() {
    requireAdmin();
    
    // Query langsung dengan ORDER BY
    $sql = "SELECT p.*, c.category_name 
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.category_id
            ORDER BY p.product_id ASC"; // ← FIX: TAMBAH ORDER BY
    
    $products = executeQuery($sql);
    
    include __DIR__ . '/../Views/Admin/products.php';
}

    
    public static function addProduct() {
        requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $category_id = clean($_POST['category_id']);
            $product_name = clean($_POST['product_name']);
            $description = clean($_POST['description']);
            $price_per_kg = floatval($_POST['price_per_kg']);
            $stock_kg = floatval($_POST['stock_kg']);
            
            $result = Product::create($category_id, $product_name, $description, $price_per_kg, $stock_kg);
            
            if ($result['success']) {
                redirect('admin/products', 'Produk berhasil ditambahkan!');
            } else {
                redirect('admin/products', 'Gagal menambahkan produk!', 'error');
            }
        }
    }
    
    public static function editProduct() {
        requireAdmin();
        $product_id = $_GET['id'] ?? 0;
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $category_id = clean($_POST['category_id']);
            $product_name = clean($_POST['product_name']);
            $description = clean($_POST['description']);
            $price_per_kg = floatval($_POST['price_per_kg']);
            
            $result = Product::update($product_id, $category_id, $product_name, $description, $price_per_kg);
            
            if ($result['success']) {
                redirect('admin/products', 'Produk berhasil diupdate!');
            } else {
                redirect('admin/products', 'Gagal update produk!', 'error');
            }
        }
    }
    
    public static function deleteProduct() {
        requireAdmin();
        $product_id = $_GET['id'] ?? 0;
        
        $result = Product::delete($product_id);
        
        if ($result['success']) {
            redirect('admin/products', 'Produk berhasil dihapus!');
        } else {
            redirect('admin/products', 'Gagal menghapus produk!', 'error');
        }
    }
    
    public static function addStock() {
        requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $product_id = intval($_POST['product_id']);
            $qty = floatval($_POST['qty']);
            
            $result = Product::addStock($product_id, $qty);
            
            if ($result['success']) {
                redirect('admin/products', 'Stok berhasil ditambahkan!');
            } else {
                redirect('admin/products', 'Gagal menambahkan stok!', 'error');
            }
        }
    }
    
    // Users Management
    public static function users() {
        requireAdmin();
        $users = User::getAllResellers();
        include __DIR__ . '/../Views/Admin/users.php';
    }
    
    public static function viewUser() {
        requireAdmin();
        $user_id = $_GET['id'] ?? 0;
        $user = User::getById($user_id);
        $orders = Order::getByUserId($user_id);
        include __DIR__ . '/../Views/Admin/user_detail.php';
    }
    
    public static function toggleUserStatus() {
        requireAdmin();
        $user_id = $_GET['id'] ?? 0;
        $status = $_GET['status'] ?? 'active';
        
        $new_status = ($status == 'active') ? 'inactive' : 'active';
        $result = User::updateStatus($user_id, $new_status);
        
        if ($result['success']) {
            redirect('admin/users', 'Status user berhasil diubah!');
        } else {
            redirect('admin/users', 'Gagal mengubah status!', 'error');
        }
    }
    
    // Orders Management
    public static function orders() {
        requireAdmin();
        $orders = Order::getAll();
        include __DIR__ . '/../Views/Admin/orders.php';
    }
    
    public static function viewOrder() {
        requireAdmin();
        $order_id = $_GET['id'] ?? 0;
        $order = Order::getById($order_id);
        $order_items = Order::getItems($order_id);
        include __DIR__ . '/../Views/Admin/order_detail.php';
    }
    
    public static function updateOrderStatus() {
        requireAdmin();
        $order_id = $_GET['id'] ?? 0;
        $status = $_POST['status'] ?? '';
        
        $result = Order::updateStatus($order_id, $status);
        
        if ($result['success']) {
            redirect('admin/orders&action=view&id=' . $order_id, 'Status pesanan berhasil diubah!');
        } else {
            redirect('admin/orders', 'Gagal mengubah status!', 'error');
        }
    }
    
    
    // ========== HELPER METHODS (SUDAH DIPERBAIKI) ==========
    
    private static function getTotalUsers() {
        $sql = "SELECT COUNT(*) as total FROM users WHERE role = 'reseller'";
        $result = executeQuery($sql);
        return $result[0]['total'] ?? 0;
    }
    
    private static function getTotalProducts() {
        $sql = "SELECT COUNT(*) as total FROM products";
        $result = executeQuery($sql);
        return $result[0]['total'] ?? 0;
    }
    
    private static function getTotalOrders() {
        // Cek apakah tabel orders ada
        $check = executeQuery("SHOW TABLES LIKE 'orders'");
        if (empty($check)) return 0;
        
        $sql = "SELECT COUNT(*) as total FROM orders";
        $result = executeQuery($sql);
        return $result[0]['total'] ?? 0;
    }
    
    private static function getTotalRevenue() {
        // Cek apakah tabel orders ada
        $check = executeQuery("SHOW TABLES LIKE 'orders'");
        if (empty($check)) return 0;
        
        $sql = "SELECT SUM(total_amount) as revenue FROM orders WHERE status IN ('completed', 'delivered')";
        $result = executeQuery($sql);
        return $result[0]['revenue'] ?? 0;
    }
    
    private static function getPendingOrders() {
        // Cek apakah tabel orders ada
        $check = executeQuery("SHOW TABLES LIKE 'orders'");
        if (empty($check)) return 0;
        
        $sql = "SELECT COUNT(*) as total FROM orders WHERE status = 'pending'";
        $result = executeQuery($sql);
        return $result[0]['total'] ?? 0;
    }
    
    private static function getLowStockProducts() {
        $sql = "SELECT COUNT(*) as total FROM products WHERE (stock_kg + stock_priority_kg) < 50";
        $result = executeQuery($sql);
        return $result[0]['total'] ?? 0;
    }
    
    private static function getDailyRevenue() {
        // Cek apakah tabel orders ada
        $check = executeQuery("SHOW TABLES LIKE 'orders'");
        if (empty($check)) return [];
        
        $sql = "SELECT DATE(created_at) as date, SUM(total_amount) as revenue 
                FROM orders 
                WHERE status IN ('completed', 'delivered') 
                AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                GROUP BY DATE(created_at) 
                ORDER BY date DESC";
        return executeQuery($sql);
    }
    
    private static function getMonthlyRevenue() {
        // Cek apakah tabel orders ada
        $check = executeQuery("SHOW TABLES LIKE 'orders'");
        if (empty($check)) return [];
        
        $sql = "SELECT DATE_FORMAT(created_at, '%Y-%m') as month, SUM(total_amount) as revenue 
                FROM orders 
                WHERE status IN ('completed', 'delivered')
                GROUP BY DATE_FORMAT(created_at, '%Y-%m') 
                ORDER BY month DESC 
                LIMIT 12";
        return executeQuery($sql);
    }
    
    private static function getTopProducts() {
        // Cek apakah tabel order_items ada
        $check = executeQuery("SHOW TABLES LIKE 'order_items'");
        if (empty($check)) return [];
        
        $sql = "SELECT p.product_name, SUM(oi.quantity_kg) as total_sold, SUM(oi.subtotal) as revenue
                FROM order_items oi
                JOIN products p ON oi.product_id = p.product_id
                JOIN orders o ON oi.order_id = o.order_id
                WHERE o.status IN ('completed', 'delivered')
                GROUP BY p.product_id
                ORDER BY total_sold DESC
                LIMIT 10";
        
        $result = executeQuery($sql);
        return $result ?? [];
    }
    
    private static function getTopCustomers() {
        // Cek apakah tabel orders ada
        $check = executeQuery("SHOW TABLES LIKE 'orders'");
        if (empty($check)) return [];
        
        $sql = "SELECT u.full_name, u.email, COUNT(o.order_id) as total_orders, SUM(o.total_amount) as total_spent
                FROM orders o
                JOIN users u ON o.user_id = u.user_id
                WHERE o.status IN ('completed', 'delivered')
                GROUP BY u.user_id
                ORDER BY total_spent DESC
                LIMIT 10";
        
        $result = executeQuery($sql);
        return $result ?? [];
    }
}
?>
