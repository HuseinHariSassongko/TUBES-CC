<?php
// File: Config/midtrans_config.php
// Midtrans Configuration - SANDBOX MODE

// Midtrans Credentials - SANDBOX (untuk testing)
define('MIDTRANS_SERVER_KEY', 'Mid-server-0EphlkeSMfvsmTWjmgKvnMye');
define('MIDTRANS_CLIENT_KEY', 'Mid-client-OxadXOtHQQY8YXpe');

// Environment - SANDBOX MODE (Testing dengan uang palsu)
define('MIDTRANS_IS_PRODUCTION', false); // false = sandbox/testing

// Snap URL - Sandbox
define('MIDTRANS_SNAP_URL', MIDTRANS_IS_PRODUCTION 
    ? 'https://app.midtrans.com/snap/snap.js' 
    : 'https://app.sandbox.midtrans.com/snap/snap.js'
);

// Notification URL (untuk webhook callback)
define('MIDTRANS_NOTIFICATION_URL', 'http://localhost/TUBES%20CC/api/midtrans_notification.php');
?>
