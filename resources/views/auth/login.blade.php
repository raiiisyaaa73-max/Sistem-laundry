<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIM Laundry</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-8 col-md-5 col-lg-4">
                <div class="text-center mb-3">
                    <h3 class="fw-bold text-primary mb-1">
                        <i class="bi bi-water"></i> SIM LAUNDRY
                    </h3>
                    <p class="text-muted small">Sistem Informasi Manajemen Laundry</p>
                </div>

                <div class="card shadow-sm border">
                    <div class="card-header bg-primary text-white text-center py-2 fw-semibold">
                        Form Login
                    </div>
                    <div class="card-body p-4">
                        @if(session('error'))
                            <div class="alert alert-danger py-2 small mb-3">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if(session('success'))
                            <div class="alert alert-success py-2 small mb-3">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger py-2 small mb-3">
                                @foreach ($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif

                        <form action="{{ route('login.post') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label small fw-semibold">Email</label>
                                <input type="email" class="form-control form-control-sm" id="email" name="email" value="{{ old('email') }}" placeholder="admin@laundry.com" required autofocus>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label small fw-semibold">Password</label>
                                <input type="password" class="form-control form-control-sm" id="password" name="password" placeholder="••••••••" required>
                            </div>

                            <div class="mb-3 form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                <label class="form-check-label small text-muted" for="remember">
                                    Ingat saya
                                </label>
                            </div>

                            <button type="submit" class="btn btn-primary btn-sm w-100 py-2 fw-semibold">
                                Masuk
                            </button>

                            <div class="text-center mt-3 small">
                                Belum punya akun? <a href="{{ route('register') }}" class="text-decoration-none">Daftar di sini</a>
                            </div>
                        </form>

                        <!-- Pilihan Akun Demo -->
                        <div class="mt-4 pt-3 border-top text-center">
                            <span class="d-block small text-muted mb-2">Akun Pengujian (Klik untuk isi cepat):</span>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-danger btn-sm w-50" onclick="isiAkun('admin@laundry.com', 'password')">
                                    Admin
                                </button>
                                <button type="button" class="btn btn-outline-primary btn-sm w-50" onclick="isiAkun('kasir@laundry.com', 'password')">
                                    Kasir
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function isiAkun(email, pass) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = pass;
        }
    </script>
</body>
</html>
