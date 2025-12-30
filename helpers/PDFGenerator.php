<?php
class PDFGenerator {
    
    public static function generateOrderPDF($order, $order_items) {
        // Set headers for PDF
        header('Content-Type: text/html; charset=utf-8');
        
        // Generate HTML for PDF
        $html = self::getOrderHTML($order, $order_items);
        
        echo $html;
        exit;
    }
    
    private static function getOrderHTML($order, $order_items) {
        $html = '<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - ' . htmlspecialchars($order['order_number']) . '</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, Helvetica, sans-serif;
            padding: 30px;
            background: white;
        }
        
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #667eea;
            padding-bottom: 20px;
        }
        
        .company-name {
            font-size: 36px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 5px;
        }
        
        .company-tagline {
            color: #999;
            font-size: 14px;
            margin-bottom: 15px;
        }
        
        .invoice-title {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }
        
        .info-section {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }
        
        .info-left, .info-right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding: 10px;
        }
        
        .info-box {
            margin-bottom: 15px;
        }
        
        .info-label {
            font-weight: bold;
            color: #666;
            font-size: 12px;
            margin-bottom: 5px;
        }
        
        .info-value {
            color: #333;
            font-size: 14px;
        }
        
        .premium-badge {
            background: linear-gradient(135deg, #FFD700, #FFA500);
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            font-weight: bold;
            display: inline-block;
            margin-top: 10px;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .items-table thead {
            background: #667eea;
            color: white;
        }
        
        .items-table th {
            padding: 12px;
            text-align: left;
            font-weight: bold;
        }
        
        .items-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #ddd;
        }
        
        .items-table tbody tr:hover {
            background: #f5f5f5;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .total-section {
            margin-top: 20px;
            float: right;
            width: 50%;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 15px;
            border-bottom: 1px solid #eee;
        }
        
        .total-row.grand-total {
            background: #f0f0f0;
            font-weight: bold;
            font-size: 16px;
            color: #667eea;
            border: 2px solid #667eea;
            margin-top: 10px;
        }
        
        .total-row.discount {
            color: #28a745;
            font-weight: bold;
        }
        
        .notes-section {
            clear: both;
            margin-top: 50px;
            padding: 20px;
            background: #f8f9fa;
            border-left: 4px solid #667eea;
        }
        
        .notes-title {
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }
        
        .notes-content {
            font-size: 12px;
            color: #666;
            line-height: 1.8;
        }
        
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #999;
            font-size: 11px;
        }
        
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #667eea;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0,0,0,0.2);
        }
        
        .print-button:hover {
            background: #5568d3;
        }
        
        @media print {
            .print-button {
                display: none;
            }
            
            body {
                padding: 0;
            }
            
            .invoice-container {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <button class="print-button" onclick="window.print()">🖨️ Print / Save PDF</button>
    
    <div class="invoice-container">
        <!-- HEADER -->
        <div class="header">
            <div class="company-name">🥚 TELUR JOSJIS</div>
            <div class="company-tagline">Distributor Telur Berkualitas</div>
            <div class="invoice-title">INVOICE</div>
        </div>
        
        <!-- INFO SECTION -->
        <div class="info-section">
            <div class="info-left">
                <div class="info-box">
                    <div class="info-label">Invoice Number</div>
                    <div class="info-value" style="font-weight: bold; font-size: 16px;">' . htmlspecialchars($order['order_number']) . '</div>
                </div>
                
                <div class="info-box">
                    <div class="info-label">Tanggal Order</div>
                    <div class="info-value">' . date('d F Y, H:i', strtotime($order['created_at'])) . '</div>
                </div>
                
                <div class="info-box">
                    <div class="info-label">Status</div>
                    <div class="info-value" style="text-transform: uppercase; color: #667eea; font-weight: bold;">' . htmlspecialchars($order['status']) . '</div>
                </div>
            </div>
            
            <div class="info-right">
                <div class="info-box">
                    <div class="info-label">Customer</div>
                    <div class="info-value" style="font-weight: bold;">' . htmlspecialchars($order['full_name']) . '</div>
                    <div class="info-value" style="color: #666;">' . htmlspecialchars($order['email']) . '</div>
                    <div class="info-value" style="color: #666;">' . htmlspecialchars($order['phone']) . '</div>
                </div>
                
                <div class="info-box">
                    <div class="info-label">Alamat Pengiriman</div>
                    <div class="info-value" style="color: #666;">' . nl2br(htmlspecialchars($order['address'])) . '</div>
                </div>';
        
        if ($order['tier_name'] == 'Premium') {
            $html .= '<div class="premium-badge">⭐ PREMIUM MEMBER</div>';
        }
        
        $html .= '
            </div>
        </div>
        
        <!-- ITEMS TABLE -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 8%;">No</th>
                    <th style="width: 40%;">Produk</th>
                    <th style="width: 20%;" class="text-right">Harga/kg</th>
                    <th style="width: 12%;" class="text-center">Qty</th>
                    <th style="width: 20%;" class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>';
        
        $no = 1;
        foreach ($order_items as $item) {
            $html .= '
                <tr>
                    <td class="text-center">' . $no++ . '</td>
                    <td><strong>' . htmlspecialchars($item['product_name']) . '</strong></td>
                    <td class="text-right">Rp ' . number_format($item['price_per_kg'], 0, ',', '.') . '</td>
                    <td class="text-center">' . number_format($item['quantity_kg'], 1) . ' kg</td>
                    <td class="text-right"><strong>Rp ' . number_format($item['subtotal'], 0, ',', '.') . '</strong></td>
                </tr>';
        }
        
        $html .= '
            </tbody>
        </table>
        
        <!-- TOTAL SECTION -->
        <div class="total-section">
            <div class="total-row">
                <span>Total Berat:</span>
                <strong>' . number_format($order['total_quantity_kg'], 1) . ' kg</strong>
            </div>
            
            <div class="total-row">
                <span>Subtotal:</span>
                <span>Rp ' . number_format($order['subtotal'], 0, ',', '.') . '</span>
            </div>';
        
        if ($order['discount'] > 0) {
            $html .= '
            <div class="total-row discount">
                <span>Diskon Premium (10%):</span>
                <span>- Rp ' . number_format($order['discount'], 0, ',', '.') . '</span>
            </div>';
        }
        
        $html .= '
            <div class="total-row grand-total">
                <span>TOTAL:</span>
                <span>Rp ' . number_format($order['total_amount'], 0, ',', '.') . '</span>
            </div>
        </div>
        
        <!-- NOTES -->
        <div class="notes-section">
            <div class="notes-title">Catatan:</div>
            <div class="notes-content">
                ✓ Invoice ini digenerate secara otomatis oleh sistem Telur Josjis<br>
                ✓ Untuk pertanyaan, hubungi customer service kami<br>
                ✓ Terima kasih atas kepercayaan Anda menggunakan layanan kami
            </div>
        </div>
        
        <!-- FOOTER -->
        <div class="footer">
            © 2025 Telur Josjis - All Rights Reserved
        </div>
    </div>
    
    <script>
        // Auto print when page loads (optional - uncomment to enable)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>';
        
        return $html;
    }
}
?>
