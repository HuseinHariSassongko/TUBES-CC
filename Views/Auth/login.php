<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Telur Josjis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
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
            <div class="col-md-5">
                <div class="card">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <h2>🥚 Telur Josjis</h2>
                            <p class="text-muted">Sistem Reseller Telur</p>
                        </div>
                        
                        <?php showFlashMessage(); ?>
                        
                        <form action="index.php?page=login&action=process" method="POST">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Email</label>
                                <input type="email" name="email" class="form-control form-control-lg" 
                                       placeholder="nama@email.com" required>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label fw-bold">Password</label>
                                <input type="password" name="password" class="form-control form-control-lg" 
                                       placeholder="••••••••" required>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-lg w-100 mb-3">
                                Login
                            </button>
                        </form>
                        
                        <div class="text-center">
                            <small>Belum punya akun? 
                                <a href="index.php?page=register" class="fw-bold">Daftar di sini</a>
                            </small>
                        </div>
                        
                        <hr class="my-4">
                        
                        <div class="alert alert-info small mb-0">
                            <strong>🔑 Demo Admin:</strong><br>
                            Email: <code>admin@gmail.com</code><br>
                            Password: <code>admin123</code>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
