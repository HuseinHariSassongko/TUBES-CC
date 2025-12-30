<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Telur Josjis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 50px 0;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <h2>🥚 Telur Josjis</h2>
                            <p class="text-muted">Daftar Reseller Baru</p>
                        </div>
                        
                        <?php showFlashMessage(); ?>
                        
                        <form action="index.php?page=register&action=process" method="POST">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nama Lengkap</label>
                                <input type="text" name="full_name" class="form-control" 
                                       placeholder="John Doe" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Email</label>
                                <input type="email" name="email" class="form-control" 
                                       placeholder="nama@email.com" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nomor Telepon</label>
                                <input type="tel" name="phone" class="form-control" 
                                       placeholder="08123456789" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Alamat</label>
                                <textarea name="address" class="form-control" rows="2" 
                                          placeholder="Jl. Contoh No. 123, Surabaya" required></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Password</label>
                                <input type="password" name="password" class="form-control" 
                                       placeholder="Minimal 6 karakter" minlength="6" required>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label fw-bold">Konfirmasi Password</label>
                                <input type="password" name="confirm_password" class="form-control" 
                                       placeholder="Ketik ulang password" required>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-lg w-100 mb-3">
                                Daftar Sekarang
                            </button>
                        </form>
                        
                        <div class="text-center">
                            <small>Sudah punya akun? 
                                <a href="index.php?page=login" class="fw-bold">Login di sini</a>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
