// Midtrans Cart Checkout Handler
document.addEventListener('DOMContentLoaded', function() {
    const checkoutBtn = document.getElementById('checkout-btn');
    const checkoutForm = document.getElementById('checkout-form');

    if (!checkoutBtn || !checkoutForm) {
        console.error('Checkout button or form not found!');
        return;
    }

    checkoutBtn.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Validasi form
        if (!checkoutForm.checkValidity()) {
            checkoutForm.reportValidity();
            return;
        }

        // Disable button
        checkoutBtn.disabled = true;
        checkoutBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';

        // Ambil data dari form
        const formData = new FormData(checkoutForm);
        const name = formData.get('name')?.trim();
        const email = formData.get('email')?.trim();
        const phone = formData.get('phone')?.trim();
        
        // Parse cart items dari hidden input
        let cartItems, total;
        try {
            const itemsInput = document.getElementById('cart-items');
            const totalInput = document.getElementById('cart-total');
            
            if (!itemsInput || !totalInput) {
                throw new Error('Hidden inputs not found');
            }
            
            cartItems = JSON.parse(itemsInput.value);
            total = parseFloat(totalInput.value);
            
            console.log('Cart Items:', cartItems);
            console.log('Total:', total);
        } catch (err) {
            console.error('Parse error:', err);
            alert('Error membaca data keranjang!');
            checkoutBtn.disabled = false;
            checkoutBtn.innerHTML = '<i class="bi bi-credit-card me-2"></i>Bayar Sekarang';
            return;
        }

        // Validasi data
        if (!name || !email || !phone || !cartItems || !total) {
            alert('Mohon lengkapi semua data!');
            checkoutBtn.disabled = false;
            checkoutBtn.innerHTML = '<i class="bi bi-credit-card me-2"></i>Bayar Sekarang';
            return;
        }

        // Siapkan data untuk API
        const requestData = {
            customer_name: name,
            customer_email: email,
            customer_phone: phone,
            items: cartItems,
            gross_amount: total
        };

        console.log('Sending request:', requestData);

        // PENTING: Sesuaikan path dengan struktur folder Anda
        // Jika file Cart.php di Views/User/, maka path ke api/ adalah ../../api/
        const apiUrl = 'api/generate_snap_token.php';
        console.log('API URL:', apiUrl);

        // Panggil API untuk generate Snap Token
        fetch(apiUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(requestData)
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            
            // Cek apakah response OK
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            
            if (data.success && data.snap_token) {
                // Panggil Midtrans Snap popup
                snap.pay(data.snap_token, {
                    onSuccess: function(result) {
                        console.log('Payment success:', result);
                        alert('Pembayaran berhasil!');
                        // Redirect ke halaman orders
                        window.location.href = 'index.php?page=orders';
                    },
                    onPending: function(result) {
                        console.log('Payment pending:', result);
                        alert('Pembayaran menunggu konfirmasi!');
                        window.location.href = 'index.php?page=orders';
                    },
                    onError: function(result) {
                        console.log('Payment error:', result);
                        
                        // Cek apakah error karena token kadaluarsa
                        if (result?.status_code === '407' || 
                            result?.status_message?.includes('Transaction not found')) {
                            alert('Token pembayaran telah kedaluwarsa. Silakan coba lagi.');
                            location.reload();
                        } else {
                            alert('Pembayaran gagal! Silakan coba lagi.');
                        }
                        
                        // Re-enable button
                        checkoutBtn.disabled = false;
                        checkoutBtn.innerHTML = '<i class="bi bi-credit-card me-2"></i>Bayar Sekarang';
                    },
                    onClose: function() {
                        console.log('Popup closed by user');
                        // Re-enable button
                        checkoutBtn.disabled = false;
                        checkoutBtn.innerHTML = '<i class="bi bi-credit-card me-2"></i>Bayar Sekarang';
                    }
                });
            } else {
                // Tampilkan error
                const errorMsg = data.message || data.error || 'Gagal generate token pembayaran';
                alert('Error: ' + errorMsg);
                console.error('Debug info:', data.debug);
                
                // Re-enable button
                checkoutBtn.disabled = false;
                checkoutBtn.innerHTML = '<i class="bi bi-credit-card me-2"></i>Bayar Sekarang';
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            alert('Terjadi kesalahan koneksi! Silakan coba lagi.');
            
            // Re-enable button
            checkoutBtn.disabled = false;
            checkoutBtn.innerHTML = '<i class="bi bi-credit-card me-2"></i>Bayar Sekarang';
        });
    });
});