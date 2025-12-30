<?php
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../helpers/session.php';
require_once __DIR__ . '/../helpers/functions.php';

class SubscriptionController {
    
    // View Subscription
    public static function view() {
        requireLogin();
        
        $user = User::getById($_SESSION['user_id']);
        
        include __DIR__ . '/../Views/User/subscription.php';
    }
    
    // Upgrade to Premium
    public static function upgrade() {
        requireLogin();
        
        $user = User::getById($_SESSION['user_id']);
        
        // Check if already premium
        if ($user['tier_name'] == 'Premium') {
            redirect('subscription', 'Anda sudah Premium!', 'error');
            return;
        }
        
        include __DIR__ . '/../Views/User/subscription.php';
    }
    
    // Process Payment (Dummy - Real implementation needs payment gateway)
    public static function processPayment() {
        requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            redirect('subscription');
            return;
        }
        
        $months = intval($_POST['months'] ?? 1);
        $payment_method = clean($_POST['payment_method'] ?? '');
        
        // Validate
        if ($months < 1 || $months > 12) {
            redirect('subscription', 'Durasi langganan tidak valid!', 'error');
            return;
        }
        
        // Calculate total
        $price_per_month = 100000;
        $total = $price_per_month * $months;
        
        // In real app, integrate with payment gateway here
        // For demo, we'll directly upgrade
        
        $result = User::upgradeToPremium($_SESSION['user_id'], $months);
        
        if ($result['success']) {
            redirect('dashboard', 'Selamat! Anda sekarang Premium member! 🎉');
        } else {
            redirect('subscription', 'Gagal upgrade ke Premium!', 'error');
        }
    }
}
?>
