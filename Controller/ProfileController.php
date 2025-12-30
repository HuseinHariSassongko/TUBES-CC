<?php
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../helpers/session.php';
require_once __DIR__ . '/../helpers/functions.php';

class ProfileController {
    
    // View Profile
    public static function view() {
        requireLogin();
        
        $user = User::getById($_SESSION['user_id']);
        
        include __DIR__ . '/../Views/User/profile.php';
    }
    
    // Edit Profile
    public static function edit() {
        requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $full_name = clean($_POST['full_name']);
            $phone = clean($_POST['phone']);
            $address = clean($_POST['address']);
            
            $result = User::updateProfile($_SESSION['user_id'], $full_name, $phone, $address);
            
            if ($result['success']) {
                $_SESSION['full_name'] = $full_name; // Update session
                redirect('profile', 'Profil berhasil diupdate!');
            } else {
                redirect('profile', 'Gagal update profil!', 'error');
            }
        } else {
            $user = User::getById($_SESSION['user_id']);
            include __DIR__ . '/../Views/User/profile.php';
        }
    }
    
    // Change Password
    public static function changePassword() {
        requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $current_password = $_POST['current_password'];
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];
            
            // Get user
            $user = User::getById($_SESSION['user_id']);
            
            // Verify current password
            if (!password_verify($current_password, $user['password'])) {
                redirect('profile', 'Password lama salah!', 'error');
                return;
            }
            
            // Validate new password
            if (strlen($new_password) < 6) {
                redirect('profile', 'Password baru minimal 6 karakter!', 'error');
                return;
            }
            
            if ($new_password !== $confirm_password) {
                redirect('profile', 'Konfirmasi password tidak cocok!', 'error');
                return;
            }
            
            // Update password
            $result = User::changePassword($_SESSION['user_id'], $new_password);
            
            if ($result['success']) {
                redirect('profile', 'Password berhasil diubah!');
            } else {
                redirect('profile', 'Gagal mengubah password!', 'error');
            }
        }
    }
}
?>
