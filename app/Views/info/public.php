<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title><?= esc($title ?? 'Info Kelompok 5') ?> &mdash; FreshWash Laundry</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/freshwash-theme.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/laundry-bubbles.css') ?>">

    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background: #f0f9ff;
            min-height: 100vh;
        }
        .public-navbar {
            background: #ffffff;
            border-bottom: 2px solid #e0f2fe;
            box-shadow: 0 4px 15px rgba(2, 132, 199, 0.08);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg public-navbar sticky-top py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="<?= base_url('login') ?>">
                <div class="brand-bubble-icon mr-2 shadow-sm" style="width: 36px; height: 36px; border-radius: 10px; background: linear-gradient(135deg, #0284c7 0%, #38bdf8 100%); display: inline-flex; align-items: center; justify-content: center; color: #fff;">
                    <i class="fas fa-soap"></i>
                </div>
                <span class="font-weight-bold text-dark" style="font-size: 1.25rem;">
                    FreshWash <span class="text-primary">Laundry</span>
                </span>
            </a>
            <div class="ml-auto d-flex align-items-center" style="gap: 10px;">
                <a href="<?= base_url('login') ?>" class="btn btn-primary font-weight-bold px-4 shadow-sm" style="border-radius: 12px; background: #0284c7; border: none;">
                    <i class="fas fa-sign-in-alt mr-1"></i> Masuk Akun Kasir
                </a>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <?= $this->include('info/content') ?>

        <div class="text-center py-4 text-muted small">
            &copy; <?= date('Y') ?> Kelompok 5 &bull; Kelas C/P2 KOMAK TI &bull; Sekolah Vokasi Institut Pertanian Bogor (IPB)
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
