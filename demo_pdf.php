<?php
session_start();
require_once 'Config/database.php';
require_once 'helpers/PDFGenerator.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?page=login');
    exit;
}


// Generate PDF
PDFGenerator::generateOrderPDF($dummy_order, $dummy_items);
?>
