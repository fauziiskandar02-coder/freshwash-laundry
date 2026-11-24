<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Masuk &mdash; FreshWash Laundry UMKM</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🧼</text></svg>">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/stisla@2.3.0/assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/stisla@2.3.0/assets/css/components.css">
    <link rel="stylesheet" href="<?= base_url('css/antigravity.css') ?>">

    <style>
        /* Auth Clean Laundry Atmosphere */
        body.auth-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            background: #f0f6fb !important;
            background-image:
                radial-gradient(circle at 15% 15%, rgba(56, 189, 248, 0.15) 0%, transparent 45%),
                radial-gradient(circle at 85% 75%, rgba(14, 165, 233, 0.10) 0%, transparent 45%) !important;
            overflow-x: hidden;
            padding: 30px 15px;
        }

        /* Clothesline Illustration at the top */
        .clothesline-wrapper {
            position: absolute;
            top: 20px;
            left: 0;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            pointer-events: none;
            z-index: 2;
        }

        .clothesline-wire {
            position: relative;
            width: 80%;
            max-width: 580px;
            height: 38px;
            border-bottom: 2px dashed rgba(2, 132, 199, 0.35);
            border-radius: 50%;
            display: flex;
            justify-content: space-around;
            padding: 0 40px;
        }

        .hanging-cloth {
            font-size: 1.55rem;
            position: relative;
            top: 20px;
            transform-origin: top center;
            animation: clothSway 3.5s ease-in-out infinite alternate;
        }

        .hanging-cloth:nth-child(2) { animation-delay: 0.6s; animation-duration: 4s; }
        .hanging-cloth:nth-child(3) { animation-delay: 1.2s; animation-duration: 3.2s; }
        .hanging-cloth:nth-child(4) { animation-delay: 0.3s; animation-duration: 4.5s; }

        @keyframes clothSway {
            0% { transform: rotate(-7deg); }
            100% { transform: rotate(7deg); }
        }

        /* Solid Crisp Auth Card (Zero Blur / Zero Lag) */
        .auth-card-glass {
            background: #ffffff !important;
            border: 2px solid #bae6fd !important;
            border-radius: 28px !important;
            box-shadow: 0 16px 36px -10px rgba(2, 132, 199, 0.16), 0 4px 12px rgba(15, 23, 42, 0.04) !important;
            position: relative;
            overflow: hidden;
            transition: all 0.25s ease;
        }

        .auth-avatar-mascot {
            width: 82px;
            height: 82px;
            border-radius: 26px;
            background: linear-gradient(135deg, #0284c7 0%, #38bdf8 100%);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2.4rem;
            color: #ffffff;
            box-shadow: 0 12px 25px rgba(2, 132, 199, 0.35);
            animation: bobbing 3.5s ease-in-out infinite;
            position: relative;
        }

        .auth-avatar-mascot::after {
            content: '🫧';
            position: absolute;
            top: -8px;
            right: -8px;
            font-size: 1.2rem;
            animation: bubblePulse 2s infinite;
        }

        @keyframes bobbing {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }

        @keyframes bubblePulse {
            0%, 100% { transform: scale(1); opacity: 0.9; }
            50% { transform: scale(1.3); opacity: 1; }
        }

        .form-floating-clean .input-group-text {
            background-color: #f0f9ff !important;
            border-color: #bae6fd !important;
            color: #0284c7 !important;
            border-radius: 14px 0 0 14px !important;
            font-size: 1rem;
        }

        .form-floating-clean .form-control {
            border-color: #bae6fd !important;
            border-radius: 0 14px 14px 0 !important;
            font-size: 0.92rem;
            padding: 12px 14px;
            background: #ffffff !important;
            transition: all 0.25s ease;
        }

        .form-floating-clean .form-control:focus {
            border-color: #0284c7 !important;
            box-shadow: 0 0 0 4px rgba(2, 132, 199, 0.15) !important;
        }

        .btn-login-submit {
            background: linear-gradient(135deg, #0284c7 0%, #0ea5e9 100%) !important;
            color: #ffffff !important;
            font-weight: 800;
            padding: 13px 20px;
            border-radius: 16px;
            border: none;
            box-shadow: 0 8px 20px rgba(2, 132, 199, 0.35);
            transition: all 0.25s;
            letter-spacing: 0.02em;
        }

        .btn-login-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(2, 132, 199, 0.45);
            color: #ffffff !important;
        }
    </style>
</head>

<body class="auth-page">

    <!-- Clothesline Animation at Top -->
    <div class="clothesline-wrapper d-none d-md-flex">
        <div class="clothesline-wire">
            <span class="hanging-cloth" title="Bersih">👕</span>
            <span class="hanging-cloth" title="Wangi">👗</span>
            <span class="hanging-cloth" title="Rapi">🧦</span>
            <span class="hanging-cloth" title="Higienis">👔</span>
        </div>
    </div>

    <!-- Main Auth Form Container -->
    <div class="container" style="position: relative; z-index: 10;">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-5">

                <!-- Brand Header -->
                <div class="text-center mb-4">
                    <div class="auth-avatar-mascot mb-3">
                        🧼
                    </div>
                    <div class="d-flex justify-content-center mb-2" style="gap: 8px;">
                        <span class="laundry-badge-pill laundry-badge-clean font-weight-bold shadow-sm">
                            🫧 100% Bersih, Wangi &amp; Rapi
                        </span>
                        <a href="<?= base_url('info') ?>" class="laundry-badge-pill font-weight-bold shadow-sm text-decoration-none" style="background: #e0f2fe; color: #0284c7; border: 1px solid #bae6fd;">
                            <i class="fas fa-users mr-1"></i> Info Kelompok 5
                        </a>
                    </div>
                    <h2 class="font-weight-bold text-dark mb-1" style="font-family: 'Nunito', sans-serif; font-size: 2rem;">
                        FreshWash <span class="text-primary">Laundry</span>
                    </h2>
                    <p class="text-muted small mb-0 font-weight-600">Sistem Informasi Akuntansi Siklus Pendapatan UMKM</p>
                    <p class="text-primary small font-weight-bold">School of Vocational Studies &mdash; IPB University</p>
                </div>

                <!-- Login Glass Card -->
                <div class="card auth-card-glass p-4 p-md-5">
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                        <h4 class="font-weight-bold text-dark mb-0" style="font-size: 1.25rem;">
                            <i class="fas fa-soap text-primary mr-1"></i> Masuk Akun Kasir
                        </h4>
                        <span class="badge badge-light border text-primary font-weight-bold px-2 py-1" style="border-radius: 8px;">PORTAL</span>
                    </div>

                    <!-- Auto-Fill Quick Notice -->
                    <div class="p-2 mb-3 d-flex align-items-center shadow-sm" style="background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 12px; color: #0369a1; font-size: 0.8rem;">
                        <i class="fas fa-bolt text-warning mr-2" style="font-size: 1rem;"></i>
                        <div>
                            <b>Akun Siap Pakai:</b> Email &amp; Password otomatis terisi. Tinggal klik tombol <b>Masuk ke Toko Laundry</b>!
                        </div>
                    </div>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger py-2 small shadow-sm" style="border-radius: 12px; background: #fff1f2; border-color: #fecdd3; color: #e11d48;">
                            <i class="fas fa-exclamation-circle mr-1"></i> <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success py-2 small shadow-sm" style="border-radius: 12px; background: #ecfdf5; border-color: #a7f3d0; color: #059669;">
                            <i class="fas fa-check-circle mr-1"></i> <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="<?= base_url('login') ?>">
                        <?= csrf_field() ?>

                        <div class="form-group mb-3">
                            <label class="font-weight-bold small text-muted">EMAIL OPERATOR / KASIR</label>
                            <div class="input-group form-floating-clean">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                </div>
                                <input type="email" name="email" id="loginEmail" class="form-control" placeholder="nama@umkm.id" value="admin@umkm.id" required autofocus>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label class="font-weight-bold small text-muted">KATA SANDI</label>
                            <div class="input-group form-floating-clean">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                </div>
                                <input type="password" name="password" id="loginPassword" class="form-control" placeholder="••••••••" value="admin123" required autocomplete="current-password">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-login-submit btn-block shadow-sm">
                            <i class="fas fa-sign-in-alt mr-2"></i> Masuk ke Toko Laundry
                        </button>
                    </form>

                    <!-- Info Kelompok 5 Link Button -->
                    <div class="mt-3">
                        <a href="<?= base_url('info') ?>" class="btn btn-outline-info btn-block font-weight-bold py-2" style="border-radius: 14px; font-size: 0.85rem; border-color: #7dd3fc; color: #0284c7;">
                            <i class="fas fa-users-cog mr-1"></i> Lihat Info Anggota Kelompok 5 &bull; Kelas C/P2
                        </a>
                    </div>

                    <!-- Quick Demo Credentials -->
                    <div class="mt-3 pt-3 border-top text-center">
                        <span class="small text-muted font-weight-bold d-block mb-2">⚡ Pilihan Cepat Role Kasir:</span>
                        <div class="d-flex justify-content-center" style="gap: 8px;">
                            <button type="button" class="btn btn-sm btn-outline-primary font-weight-bold px-3 py-2" style="border-radius: 12px;" onclick="fillLogin('admin@umkm.id', 'admin123')">
                                <i class="fas fa-user-shield mr-1"></i> Owner (Admin)
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-success font-weight-bold px-3 py-2" style="border-radius: 12px;" onclick="fillLogin('kasir@umkm.id', 'kasir123')">
                                <i class="fas fa-cash-register mr-1"></i> Kasir Cuci
                            </button>
                        </div>
                    </div>

                    <!-- Register Link -->
                    <div class="mt-3 pt-2 text-center">
                        <span class="small text-muted font-weight-600">Belum punya akun kasir?</span>
                        <a href="<?= base_url('register') ?>" class="small font-weight-bold text-primary ml-1">
                            Daftar di sini &rarr;
                        </a>
                    </div>
                </div>

                <div class="text-center text-muted small mt-4 font-weight-600">
                    Tema FreshWash Laundry &bull; Tugas PJBL Komak TI SV IPB
                </div>
            </div>
        </div>
    </div>

    <!-- Interactive Floating Soap Bubbles Layer -->
    <script src="<?= base_url('js/laundry-bubbles.js') ?>"></script>

    <script>
        function fillLogin(email, pass) {
            document.querySelector('input[name="email"]').value = email;
            document.querySelector('input[name="password"]').value = pass;
        }

        window.addEventListener('DOMContentLoaded', function() {
            var emailInput = document.getElementById('loginEmail');
            var passInput = document.getElementById('loginPassword');
            if (emailInput && (!emailInput.value || emailInput.value.trim() === '')) {
                emailInput.value = 'admin@umkm.id';
            }
            if (passInput && (!passInput.value || passInput.value.trim() === '')) {
                passInput.value = 'admin123';
            }
        });
    </script>
</body>
</html>