<?php
require_once __DIR__ . '/../Config/database.php';

class Product {
    
    // Get all products
    public static function getAll() {
        $sql = "SELECT p.*, c.category_name 
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.category_id
                ORDER BY p.created_at DESC";
        
        return executeQuery($sql);
    }
    
    // Get products for catalog (based on user tier)
    public static function getCatalog($tier = 'Basic') {
        if ($tier == 'Premium') {
            $sql = "SELECT p.*, c.category_name,
                    (p.stock_priority_kg + p.stock_kg) as available_stock
                    FROM products p
                    LEFT JOIN categories c ON p.category_id = c.category_id
                    WHERE p.status != 'out_of_stock'
                    ORDER BY p.product_name";
        } else {
            $sql = "SELECT p.*, c.category_name,
                    p.stock_kg as available_stock
                    FROM products p
                    LEFT JOIN categories c ON p.category_id = c.category_id
                    WHERE p.status != 'out_of_stock'
                    ORDER BY p.product_name";
        }
        
        return executeQuery($sql);
    }
    
    // Get product by ID
    public static function getById($product_id) {
        $sql = "SELECT p.*, c.category_name 
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.category_id
                WHERE p.product_id = ?";
        
        $result = executeQuery($sql, [$product_id], 'i');
        return $result[0] ?? null;
    }
    
    // Create product
    public static function create($category_id, $product_name, $description, $price_per_kg, $stock_kg) {
        $sql = "INSERT INTO products (category_id, product_name, description, price_per_kg, stock_kg) 
                VALUES (?, ?, ?, ?, ?)";
        
        return executeUpdate($sql, 
            [$category_id, $product_name, $description, $price_per_kg, $stock_kg], 
            'issdd'
        );
    }
    
    // Update product
    public static function update($product_id, $category_id, $product_name, $description, $price_per_kg) {
        $sql = "UPDATE products SET category_id = ?, product_name = ?, 
                description = ?, price_per_kg = ?, updated_at = CURRENT_TIMESTAMP 
                WHERE product_id = ?";
        
        return executeUpdate($sql, 
            [$category_id, $product_name, $description, $price_per_kg, $product_id], 
            'issdi'
        );
    }
    
    // Delete product
    public static function delete($product_id) {
        $sql = "DELETE FROM products WHERE product_id = ?";
        return executeUpdate($sql, [$product_id], 'i');
    }
    
    // Add stock (with priority allocation for Premium)
    public static function addStock($product_id, $qty) {
        // 70% to priority (Premium), 30% to regular
        $priority_qty = $qty * 0.70;
        $regular_qty = $qty * 0.30;
        
        $release_time = date('Y-m-d H:i:s', strtotime('+24 hours'));
        
        $sql = "UPDATE products SET 
                stock_priority_kg = stock_priority_kg + ?,
                stock_kg = stock_kg + ?,
                priority_release_time = ?,
                updated_at = CURRENT_TIMESTAMP
                WHERE product_id = ?";
        
        return executeUpdate($sql, [$priority_qty, $regular_qty, $release_time, $product_id], 'ddsi');
    }
    
    // Reduce stock (when order is placed)
    public static function reduceStock($product_id, $qty, $tier) {
        if ($tier == 'Premium') {
            // Try to reduce from priority stock first
            $sql = "UPDATE products SET 
                    stock_priority_kg = GREATEST(0, stock_priority_kg - ?),
                    stock_kg = GREATEST(0, stock_kg - GREATEST(0, ? - stock_priority_kg))
                    WHERE product_id = ?";
            return executeUpdate($sql, [$qty, $qty, $product_id], 'ddi');
        } else {
            // Basic only reduces regular stock
            $sql = "UPDATE products SET 
                    stock_kg = GREATEST(0, stock_kg - ?)
                    WHERE product_id = ?";
            return executeUpdate($sql, [$qty, $product_id], 'di');
        }
    }
}
?>
