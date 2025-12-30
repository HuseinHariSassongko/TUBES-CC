<?php
require_once __DIR__ . '/../Models/Product.php';
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../helpers/session.php';
require_once __DIR__ . '/../helpers/functions.php';

class ProductController {
    
    // Product Catalog
    public static function catalog() {
        requireLogin();
        
        $user = User::getById($_SESSION['user_id']);
        $is_premium = ($user['tier_name'] == 'Premium');
        
        // Get all products
        $products = Product::getAll();
        
        // Get categories
        $categories = self::getCategories();
        
        include __DIR__ . '/../Views/User/catalog.php';
    }
    
    // Product Detail
    public static function detail() {
        requireLogin();
        
        $product_id = $_GET['id'] ?? 0;
        $product = Product::getById($product_id);
        
        if (!$product) {
            redirect('catalog', 'Produk tidak ditemukan!', 'error');
            return;
        }
        
        $user = User::getById($_SESSION['user_id']);
        
        include __DIR__ . '/../Views/User/product_detail.php';
    }
    
    private static function getCategories() {
        $sql = "SELECT * FROM categories ORDER BY category_name";
        return executeQuery($sql);
    }
}
?>
