<?php
require_once __DIR__ . '/../Config/database.php';

class User {
    
    // Register new user
    public static function register($email, $password, $full_name, $phone, $address) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO users (email, password, full_name, phone, address, role, subscription_id) 
                VALUES (?, ?, ?, ?, ?, 'reseller', 1)";
        
        return executeUpdate($sql, 
            [$email, $hashed_password, $full_name, $phone, $address], 
            'sssss'
        );
    }
    
    // Login user
    public static function login($email, $password) {
        $sql = "SELECT * FROM users WHERE email = ? AND status = 'active'";
        $result = executeQuery($sql, [$email], 's');
        
        if (empty($result)) return false;
        
        $user = $result[0];
        
        if (password_verify($password, $user['password'])) {
            return $user;
        }
        
        return false;
    }
    
    // Get user by ID
    public static function getById($user_id) {
        $sql = "SELECT u.*, s.tier_name, s.discount_percentage 
                FROM users u
                LEFT JOIN subscriptions s ON u.subscription_id = s.subscription_id
                WHERE u.user_id = ?";
        
        $result = executeQuery($sql, [$user_id], 'i');
        return $result[0] ?? null;
    }
    
    // Update profile
    public static function updateProfile($user_id, $full_name, $phone, $address) {
        $sql = "UPDATE users SET full_name = ?, phone = ?, address = ?, 
                updated_at = CURRENT_TIMESTAMP 
                WHERE user_id = ?";
        
        return executeUpdate($sql, [$full_name, $phone, $address, $user_id], 'sssi');
    }
    
    // Change password
    public static function changePassword($user_id, $new_password) {
        $hashed = password_hash($new_password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password = ? WHERE user_id = ?";
        return executeUpdate($sql, [$hashed, $user_id], 'si');
    }
    
    // Upgrade to Premium
    public static function upgradeToPremium($user_id, $months = 1) {
        $start = date('Y-m-d');
        $end = date('Y-m-d', strtotime("+$months months"));
        
        $sql = "UPDATE users SET subscription_id = 2, 
                subscription_start = ?, subscription_end = ? 
                WHERE user_id = ?";
        
        return executeUpdate($sql, [$start, $end, $user_id], 'ssi');
    }
    
    // Downgrade to Basic (expired Premium)
    public static function downgradeToBasic($user_id) {
        $sql = "UPDATE users SET subscription_id = 1, 
                subscription_start = NULL, subscription_end = NULL 
                WHERE user_id = ?";
        
        return executeUpdate($sql, [$user_id], 'i');
    }
    
    // Get all resellers (for admin)
    public static function getAllResellers() {
        $sql = "SELECT u.*, s.tier_name 
                FROM users u
                LEFT JOIN subscriptions s ON u.subscription_id = s.subscription_id
                WHERE u.role = 'reseller'
                ORDER BY u.created_at DESC";
        
        return executeQuery($sql);
    }
    
    // ========== METHOD BARU UNTUK ADMIN ==========
    
    // Update user status (admin feature)
    public static function updateStatus($user_id, $status) {
        $sql = "UPDATE users SET status = ?, updated_at = CURRENT_TIMESTAMP 
                WHERE user_id = ?";
        return executeUpdate($sql, [$status, $user_id], 'si');
    }
    
    // Get user statistics
    public static function getStats($user_id) {
        $sql = "SELECT 
                COUNT(o.order_id) as total_orders,
                SUM(o.total_amount) as total_spent,
                MAX(o.created_at) as last_order_date
                FROM orders o
                WHERE o.user_id = ? AND o.status IN ('completed', 'delivered')";
        
        $result = executeQuery($sql, [$user_id], 'i');
        return $result[0] ?? ['total_orders' => 0, 'total_spent' => 0, 'last_order_date' => null];
    }
}
?>
