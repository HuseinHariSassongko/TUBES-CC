<?php
require_once __DIR__ . '/../Config/database.php';

class Order {
    
    // Get all orders
    public static function getAll() {
        $sql = "SELECT o.*, u.full_name, u.email,
                COUNT(oi.order_item_id) as total_items
                FROM orders o
                LEFT JOIN users u ON o.user_id = u.user_id
                LEFT JOIN order_items oi ON o.order_id = oi.order_id
                GROUP BY o.order_id
                ORDER BY o.created_at DESC";
        return executeQuery($sql);
    }
    
    // Get order by ID
    public static function getById($order_id) {
        $sql = "SELECT o.*, u.full_name, u.email, u.phone, u.address
                FROM orders o
                LEFT JOIN users u ON o.user_id = u.user_id
                WHERE o.order_id = ?";
        $result = executeQuery($sql, [$order_id], 'i');
        return $result[0] ?? null;
    }
    
    // Get order items
    public static function getItems($order_id) {
        $sql = "SELECT oi.*, p.product_name, p.price_per_kg
                FROM order_items oi
                JOIN products p ON oi.product_id = p.product_id
                WHERE oi.order_id = ?";
        return executeQuery($sql, [$order_id], 'i');
    }
    
    // Get orders by user ID
    public static function getByUserId($user_id) {
        $sql = "SELECT o.*, COUNT(oi.order_item_id) as total_items
                FROM orders o
                LEFT JOIN order_items oi ON o.order_id = oi.order_id
                WHERE o.user_id = ?
                GROUP BY o.order_id
                ORDER BY o.created_at DESC";
        return executeQuery($sql, [$user_id], 'i');
    }
    
    // Get recent orders
    public static function getRecent($limit = 5) {
        $sql = "SELECT o.*, u.full_name
                FROM orders o
                LEFT JOIN users u ON o.user_id = u.user_id
                ORDER BY o.created_at DESC
                LIMIT ?";
        return executeQuery($sql, [$limit], 'i');
    }
    
    // Update order status
    public static function updateStatus($order_id, $status) {
        $sql = "UPDATE orders SET status = ?, updated_at = CURRENT_TIMESTAMP 
                WHERE order_id = ?";
        return executeUpdate($sql, [$status, $order_id], 'si');
    }
    
    // ========== METHOD BARU DITAMBAHKAN DI BAWAH INI ==========
    
    // Create new order
    public static function create($user_id, $total_qty, $subtotal, $discount, $total_amount) {
        $order_number = 'ORD-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        
        $sql = "INSERT INTO orders (order_number, user_id, total_quantity_kg, subtotal, discount, total_amount, status) 
                VALUES (?, ?, ?, ?, ?, ?, 'pending')";
        
        return executeUpdate($sql, 
            [$order_number, $user_id, $total_qty, $subtotal, $discount, $total_amount], 
            'sidddd'
        );
    }
    
    // Add order item
    public static function addItem($order_id, $product_id, $qty, $price, $subtotal) {
        $sql = "INSERT INTO order_items (order_id, product_id, quantity_kg, price_per_kg, subtotal) 
                VALUES (?, ?, ?, ?, ?)";
        
        return executeUpdate($sql, [$order_id, $product_id, $qty, $price, $subtotal], 'iiddd');
    }
}
?>
