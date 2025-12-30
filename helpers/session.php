<?php
session_start();

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Check if user is admin
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] == 'admin';
}

// Check if user is reseller
function isReseller() {
    return isset($_SESSION['role']) && $_SESSION['role'] == 'reseller';
}

// Get current user subscription tier
function getUserTier() {
    if (!isLoggedIn()) return null;
    
    require_once __DIR__ . '/../Config/database.php';
    
    $sql = "SELECT subscription_id, subscription_end 
            FROM users WHERE user_id = ?";
    $result = executeQuery($sql, [$_SESSION['user_id']], 'i');
    
    if (empty($result)) return 'Basic';
    
    $user = $result[0];
    
    // Check if Premium and not expired
    if ($user['subscription_id'] == 2) {
        if (strtotime($user['subscription_end']) >= strtotime(date('Y-m-d'))) {
            return 'Premium';
        }
    }
    
    return 'Basic';
}

// Check if user is Premium
function isPremium() {
    return getUserTier() == 'Premium';
}

// Redirect if not logged in
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: index.php?page=login');
        exit;
    }
}

// Redirect if not admin
function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        header('Location: index.php?page=dashboard');
        exit;
    }
}

// Set session data after login
function setUserSession($user) {
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['full_name'] = $user['full_name'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['subscription_id'] = $user['subscription_id'];
}

// Destroy session (logout)
function destroySession() {
    session_unset();
    session_destroy();
}
?>
