<?php
// File: Config/midtrans_config.php
// Midtrans Configuration - SANDBOX MODE

require_once __DIR__ . '/../vendor/autoload.php';

// ===============================
// KONFIGURASI MIDTRANS (SANDBOX)
// ===============================

// WAJIB: isi via .env saat local, via server saat deploy
\Midtrans\Config::$serverKey = getenv('MIDTRANS_SERVER_KEY') ?: '';
\Midtrans\Config::$isProduction = getenv('MIDTRANS_IS_PRODUCTION') === 'false';
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;

// Snap URL helper
define(
    'MIDTRANS_SNAP_URL',
    \Midtrans\Config::$isProduction
        ? 'https://app.midtrans.com/snap/snap.js'
        : 'https://app.sandbox.midtrans.com/snap/snap.js'
);
