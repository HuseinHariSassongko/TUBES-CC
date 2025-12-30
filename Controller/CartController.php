<?php
require_once __DIR__ . '/../Models/Product.php';
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Models/Order.php';
require_once __DIR__ . '/../helpers/session.php';
require_once __DIR__ . '/../helpers/functions.php';

class CartController {
    
    public static function viewCart() {
        requireLogin();
        
        $cart = $_SESSION['cart'] ?? [];
        $cart_items = [];
        $subtotal = 0;
        
        foreach ($cart as $product_id => $quantity) {
            $product = Product::getById($product_id);
            if ($product) {
                $product['quantity'] = $quantity;
                $product['item_total'] = $product['price_per_kg'] * $quantity;
                $subtotal += $product['item_total'];
                $cart_items[] = $product;
            }
        }
        
        $user = User::getById($_SESSION['user_id']);
        $discount = 0;
        $total_kg = array_sum(array_column($cart_items, 'quantity'));
        
        if ($user['tier_name'] == 'Premium' && $total_kg > 100) {
            $discount = $subtotal * 0.10;
        }
        
        $total = $subtotal - $discount;
        
        include __DIR__ . '/../Views/User/cart.php';
    }
    
    public static function addToCart() {
        requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            redirect('catalog');
            return;
        }
        
        $product_id = intval($_POST['product_id']);
        $quantity = floatval($_POST['quantity']);
        
        $product = Product::getById($product_id);
        
        if (!$product) {
            redirect('catalog', 'Produk tidak ditemukan!', 'error');
            return;
        }
        
        $user = User::getById($_SESSION['user_id']);
        $is_premium = ($user['tier_name'] == 'Premium');
        $available_stock = $is_premium ? ($product['stock_priority_kg'] + $product['stock_kg']) : $product['stock_kg'];
        
        if ($quantity > $available_stock) {
            redirect('catalog&action=detail&id=' . $product_id, 'Stok tidak mencukupi!', 'error');
            return;
        }
        
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = $quantity;
        }
        
        redirect('cart', 'Produk berhasil ditambahkan ke keranjang!');
    }
    
    public static function updateCart() {
        requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            redirect('cart');
            return;
        }
        
        $product_id = intval($_POST['product_id']);
        $quantity = floatval($_POST['quantity']);
        
        if ($quantity <= 0) {
            unset($_SESSION['cart'][$product_id]);
        } else {
            $_SESSION['cart'][$product_id] = $quantity;
        }
        
        redirect('cart', 'Keranjang diupdate!');
    }
    
    public static function removeFromCart() {
        requireLogin();
        
        $product_id = intval($_GET['id'] ?? 0);
        
        if (isset($_SESSION['cart'][$product_id])) {
            unset($_SESSION['cart'][$product_id]);
            redirect('cart', 'Produk dihapus dari keranjang!');
        } else {
            redirect('cart', 'Produk tidak ditemukan!', 'error');
        }
    }
    
    public static function clearCart() {
        requireLogin();
        $_SESSION['cart'] = [];
        redirect('cart', 'Keranjang dikosongkan!');
    }
    
    public static function checkout() {
        requireLogin();
        
        $cart = $_SESSION['cart'] ?? [];
        
        if (empty($cart)) {
            redirect('catalog', 'Keranjang Anda kosong!', 'error');
            return;
        }
        
        $cart_items = [];
        $subtotal = 0;
        
        foreach ($cart as $product_id => $quantity) {
            $product = Product::getById($product_id);
            if ($product) {
                $product['quantity'] = $quantity;
                $product['item_total'] = $product['price_per_kg'] * $quantity;
                $subtotal += $product['item_total'];
                $cart_items[] = $product;
            }
        }
        
        $user = User::getById($_SESSION['user_id']);
        $discount = 0;
        $total_kg = array_sum(array_column($cart_items, 'quantity'));
        
        if ($user['tier_name'] == 'Premium' && $total_kg > 100) {
            $discount = $subtotal * 0.10;
        }
        
        $total = $subtotal - $discount;
        
        include __DIR__ . '/../Views/User/checkout.php';
    }
    
    public static function processOrder() {
        requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            redirect('cart');
            return;
        }
        
        $cart = $_SESSION['cart'] ?? [];
        
        if (empty($cart)) {
            redirect('catalog', 'Keranjang kosong!', 'error');
            return;
        }
        
        $subtotal = 0;
        $total_kg = 0;
        $cart_items = [];
        
        foreach ($cart as $product_id => $quantity) {
            $product = Product::getById($product_id);
            if ($product) {
                $item_total = $product['price_per_kg'] * $quantity;
                $subtotal += $item_total;
                $total_kg += $quantity;
                $cart_items[] = [
                    'product_id' => $product_id,
                    'quantity' => $quantity,
                    'price' => $product['price_per_kg'],
                    'subtotal' => $item_total
                ];
            }
        }
        
        $user = User::getById($_SESSION['user_id']);
        $discount = 0;
        
        if ($user['tier_name'] == 'Premium' && $total_kg > 100) {
            $discount = $subtotal * 0.10;
        }
        
        $total = $subtotal - $discount;
        
        $order_result = Order::create($_SESSION['user_id'], $total_kg, $subtotal, $discount, $total);
        
        if (!$order_result['success']) {
            redirect('cart', 'Gagal membuat pesanan!', 'error');
            return;
        }
        
        $order_id = $order_result['insert_id'];
        
        foreach ($cart_items as $item) {
            Order::addItem($order_id, $item['product_id'], $item['quantity'], $item['price'], $item['subtotal']);
            Product::reduceStock($item['product_id'], $item['quantity'], $user['tier_name'] == 'Premium');
        }
        
        $_SESSION['cart'] = [];
        
        redirect('orders&action=detail&id=' . $order_id, 'Pesanan berhasil dibuat! Silakan lakukan pembayaran.');
    }
}
