/**
 * Midtrans Subscription Payment Handler
 * Telur Josjis E-commerce Platform
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('Subscription payment script loaded');
    
    const upgradeButtons = document.querySelectorAll('.btn-upgrade');
    
    if (upgradeButtons.length === 0) {
        console.warn('No upgrade buttons found');
        return;
    }
    
    console.log('Found ' + upgradeButtons.length + ' upgrade buttons');
    
    upgradeButtons.forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const months = this.getAttribute('data-months');
            const price = this.getAttribute('data-price');
            
            console.log('Upgrade clicked:', { months: months, price: price });
            
            const originalHTML = this.innerHTML;
            const originalButton = this;
            
            this.disabled = true;
            this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
            
            // Prepare JSON data
            const requestData = {
                months: parseInt(months),
                price: parseInt(price)
            };
            
            console.log('Sending JSON:', requestData);
            
            // Request dengan JSON (BUKAN form-urlencoded!)
            fetch('API/create_subscription_token.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',  // ← UBAH INI!
                },
                body: JSON.stringify(requestData)  // ← UBAH INI!
            })
            .then(function(response) {
                console.log('Response status:', response.status);
                
                // Check if response is JSON
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    throw new TypeError("Server tidak return JSON!");
                }
                
                return response.json();
            })
            .then(function(data) {
                console.log('Server response:', data);
                
                if (data.success && data.snap_token) {
                    console.log('Opening Midtrans Snap with token:', data.snap_token);
                    
                    snap.pay(data.snap_token, {
                        onSuccess: function(result) {
                            console.log('Payment success:', result);
                            alert('Pembayaran berhasil! Akun Anda akan diupgrade.');
                            window.location.href = 'index.php?page=subscription&success=1';
                        },
                        onError: function(result) {
                            console.error('Payment error:', result);
                            alert('Pembayaran gagal! Silakan coba lagi.');
                            originalButton.disabled = false;
                            originalButton.innerHTML = originalHTML;
                        },
                        onClose: function() {
                            console.log('Snap popup closed');
                            originalButton.disabled = false;
                            originalButton.innerHTML = originalHTML;
                        }
                    });
                } else {
                    console.error('Backend error:', data.message);
                    alert('Error: ' + (data.message || 'Terjadi kesalahan'));
                    originalButton.disabled = false;
                    originalButton.innerHTML = originalHTML;
                }
            })
            .catch(function(error) {
                console.error('Fetch error:', error);
                alert('Terjadi kesalahan: ' + error.message);
                originalButton.disabled = false;
                originalButton.innerHTML = originalHTML;
            });
        });
    });
});
