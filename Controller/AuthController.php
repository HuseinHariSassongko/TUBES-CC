<?php
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../helpers/session.php';
require_once __DIR__ . '/../helpers/functions.php';

class AuthController {
    
    // Show login form
    public static function showLogin() {
        if (isLoggedIn()) {
            header('Location: index.php?page=dashboard');
            exit;
        }
        include __DIR__ . '/../Views/Auth/login.php';
    }
    
    // Process login
    public static function processLogin() {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            redirect('login');
        }
        
        $email = clean($_POST['email']);
        $password = $_POST['password'];
        
        $user = User::login($email, $password);
        
        if ($user) {
            setUserSession($user);
            
            if ($user['role'] == 'admin') {
                redirect('admin/dashboard', 'Login berhasil!');
            } else {
                redirect('dashboard', 'Login berhasil!');
            }
        } else {
            redirect('login', 'Email atau password salah!', 'error');
        }
    }
    
    // Show register form
    public static function showRegister() {
        if (isLoggedIn()) {
            header('Location: index.php?page=dashboard');
            exit;
        }
        include __DIR__ . '/../Views/Auth/register.php';
    }
    
    // Process registration
    public static function processRegister() {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            redirect('register');
        }
        
        $email = clean($_POST['email']);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        $full_name = clean($_POST['full_name']);
        $phone = clean($_POST['phone']);
        $address = clean($_POST['address']);
        
        // Validation
        if ($password != $confirm_password) {
            redirect('register', 'Password tidak cocok!', 'error');
        }
        
        if (strlen($password) < 6) {
            redirect('register', 'Password minimal 6 karakter!', 'error');
        }
        
        $result = User::register($email, $password, $full_name, $phone, $address);
        
        if ($result['success']) {
            redirect('login', 'Registrasi berhasil! Silakan login.');
        } else {
            redirect('register', 'Email sudah terdaftar!', 'error');
        }
    }
    
    // Logout
    public static function logout() {
        destroySession();
        redirect('login', 'Logout berhasil!');
    }
}
?>
