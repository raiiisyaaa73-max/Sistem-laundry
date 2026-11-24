<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - SIM Laundry</title>
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
                    <p class="text-muted small">Registrasi Akun Baru</p>
                </div>

                <div class="card shadow-sm border">
                    <div class="card-header bg-primary text-white text-center py-2 fw-semibold">
                        Form Registrasi Akun
                    </div>
                    <div class="card-body p-4">
                        @if ($errors->any())
                            <div class="alert alert-danger py-2 small mb-3">
                                @foreach ($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif

                        <form action="{{ route('register.post') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label small fw-semibold">Nama Lengkap</label>
                                <input type="text" class="form-control form-control-sm" id="name" name="name" value="{{ old('name') }}" placeholder="Contoh: Nadia Miranti" required autofocus>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label small fw-semibold">Alamat Email</label>
                                <input type="email" class="form-control form-control-sm" id="email" name="email" value="{{ old('email') }}" placeholder="nama@gmail.com" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label small fw-semibold">Password</label>
                                <input type="password" class="form-control form-control-sm" id="password" name="password" placeholder="Minimal 6 karakter" required>
                            </div>

                            <div class="mb-3">
                                <label for="role" class="form-label small fw-semibold">Peran Akun (Role)</label>
                                <select name="role" id="role" class="form-select form-select-sm" required>
                                    <option value="kasir" {{ old('role') === 'kasir' ? 'selected' : '' }}>Kasir (Operasional Cucian)</option>
                                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin (Pengelola Penuh)</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary btn-sm w-100 py-2 fw-semibold">
                                Daftar Sekarang
                            </button>

                            <div class="text-center mt-3 small">
                                Sudah punya akun? <a href="{{ route('login') }}" class="text-decoration-none">Login di sini</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
