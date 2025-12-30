<?php
// Load helpers first
require_once 'helpers/session.php';
require_once 'helpers/functions.php';

// Simple routing
$page = $_GET['page'] ?? 'home';
$action = $_GET['action'] ?? '';

// Route handling
switch ($page) {
    // Landing page (home)
    case 'home':
        include 'Views/landing.php';
        break;
        
    // Auth routes
    case 'login':
        require_once 'Controller/AuthController.php';
        if ($action == 'process') {
            AuthController::processLogin();
        } else {
            // Redirect to landing page with login tab
            header('Location: index.php#login-tab');
            exit;
        }
        break;
        
    case 'register':
        require_once 'Controller/AuthController.php';
        if ($action == 'process') {
            AuthController::processRegister();
        } else {
            // Redirect to landing page with register tab
            header('Location: index.php#register-tab');
            exit;
        }
        break;
        
    case 'logout':
        require_once 'Controller/AuthController.php';
        AuthController::logout();
        break;
        
    // ==================== USER DASHBOARD ====================
    case 'dashboard':
        requireLogin();
        if (isAdmin()) {
            header('Location: index.php?page=admin/dashboard');
            exit;
        }
        require_once 'Controller/UserController.php';
        UserController::dashboard();
        break;
        
    // ==================== USER/RESELLER ROUTES ====================
    case 'catalog':
        requireLogin();
        require_once 'Controller/ProductController.php';
        if ($action == 'detail') {
            ProductController::detail();
        } else {
            ProductController::catalog();
        }
        break;
        
    case 'cart':
        requireLogin();
        require_once 'Controller/CartController.php';
        if ($action == 'add') {
            CartController::addToCart();
        } elseif ($action == 'update') {
            CartController::updateCart();
        } elseif ($action == 'remove') {
            CartController::removeFromCart();
        } elseif ($action == 'clear') {
            CartController::clearCart();
        } elseif ($action == 'checkout') {
            CartController::checkout();
        } elseif ($action == 'process-order') {
            CartController::processOrder();
        } else {
            CartController::viewCart();
        }
        break;

case 'orders':
    requireLogin();
    require_once 'Controller/OrderController.php';
    if ($action == 'detail') {
        OrderController::detail();
    } elseif ($action == 'cancel') {
        OrderController::cancel();
    } elseif ($action == 'download-pdf') {
        require_once 'Controller/PDFController.php';
        PDFController::downloadOrderPDF();
    } else {
        OrderController::myOrders();
    }
    break;

        
    case 'profile':
        requireLogin();
        require_once 'Controller/ProfileController.php';
        if ($action == 'edit') {
            ProfileController::edit();
        } elseif ($action == 'change-password') {
            ProfileController::changePassword();
        } else {
            ProfileController::view();
        }
        break;
        
    case 'subscription':
        requireLogin();
        require_once 'Controller/SubscriptionController.php';
        if ($action == 'payment') {
            SubscriptionController::processPayment();
        } else {
            SubscriptionController::view();
        }
        break;

            case 'checkout':
        // Redirect to cart checkout
        header('Location: index.php?page=cart&action=checkout');
        exit;
        break;

        
    // ==================== ADMIN ROUTES ====================
    case 'admin/dashboard':
        requireAdmin();
        require_once 'Controller/AdminController.php';
        AdminController::dashboard();
        break;
        
    case 'admin/products':
        requireAdmin();
        require_once 'Controller/AdminController.php';
        if ($action == 'add') {
            AdminController::addProduct();
        } elseif ($action == 'edit') {
            AdminController::editProduct();
        } elseif ($action == 'delete') {
            AdminController::deleteProduct();
        } elseif ($action == 'add-stock') {
            AdminController::addStock();
        } else {
            AdminController::products();
        }
        break;
        
    case 'admin/users':
        requireAdmin();
        require_once 'Controller/AdminController.php';
        if ($action == 'view') {
            AdminController::viewUser();
        } elseif ($action == 'toggle-status') {
            AdminController::toggleUserStatus();
        } else {
            AdminController::users();
        }
        break;
        
    case 'admin/orders':
        requireAdmin();
        require_once 'Controller/AdminController.php';
        if ($action == 'view') {
            AdminController::viewOrder();
        } elseif ($action == 'update-status') {
            AdminController::updateOrderStatus();
        } else {
            AdminController::orders();
        }
        break;

    default:
        header('HTTP/1.0 404 Not Found');
        echo '<h1>404 - Page Not Found</h1>';
        echo '<a href="index.php">Back to Home</a>';
}
?>
