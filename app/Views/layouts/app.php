<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title><?= esc($title ?? 'FreshWash Laundry') ?> &mdash; Sistem Informasi Akuntansi Laundry UMKM</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🧼</text></svg>">

    <!-- General CSS Files -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Stisla Core CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/stisla@2.3.0/assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/stisla@2.3.0/assets/css/components.css">

    <!-- FreshWash Laundry Theme Layer (Bubbles & Soap Vibes) -->
    <link rel="stylesheet" href="<?= base_url('css/antigravity.css') ?>">

    <!-- Select2 Enhancements -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
</head>

<body>
    <div id="app">
        <div class="main-wrapper">
            <!-- Topbar Navigation -->
            <?= $this->include('layouts/topbar') ?>

            <!-- Sidebar Navigation -->
            <?= $this->include('layouts/sidebar') ?>

            <!-- Main Content Area -->
            <div class="main-content">
                <section class="section">
                    <!-- Flash Messages -->
                    <div class="row">
                        <div class="col-12">
                            <?php if (session()->getFlashdata('success')): ?>
                                <div class="alert alert-success alert-dismissible show fade shadow-sm" style="border-radius: 14px; background: #ecfdf5; border: 1px solid #a7f3d0; color: #059669;">
                                    <div class="alert-body font-weight-bold">
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                        <i class="fas fa-check-circle mr-2"></i> <?= session()->getFlashdata('success') ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if (session()->getFlashdata('error')): ?>
                                <div class="alert alert-danger alert-dismissible show fade shadow-sm" style="border-radius: 14px; background: #fff1f2; border: 1px solid #fecdd3; color: #e11d48;">
                                    <div class="alert-body font-weight-bold">
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                        <i class="fas fa-exclamation-triangle mr-2"></i> <?= session()->getFlashdata('error') ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?= $this->renderSection('content') ?>
                </section>
            </div>

            <!-- Footer -->
            <?= $this->include('layouts/footer') ?>

            <!-- Floating Laundry Mascot Button with Interactive Speech Bubble (Bottom Right) -->
            <div class="floating-laundry-mascot-wrapper">
                <div class="mascot-speech-bubble" id="mascotSpeechBubble">
                    <span id="mascotSpeechText">Halo! Cucian wangi &amp; rapi siap diproses! 🫧</span>
                    <button type="button" class="bubble-close-btn" onclick="event.stopPropagation(); hideMascotSpeech();">&times;</button>
                </div>
                <div class="floating-laundry-mascot" onclick="handleMascotClick()" title="Klik Si Wangi untuk tips & putar mesin cuci!">
                    <div class="mascot-avatar-circle">
                        🧼
                    </div>
                    <div>
                        <div class="mascot-info-title">Si Wangi (Washy)</div>
                        <div class="mascot-info-status">
                            <i class="fas fa-tint text-primary mr-1"></i> Busa Melimpah &bull; Siap Cuci
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- General JS Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/stisla@2.3.0/assets/js/stisla.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/stisla@2.3.0/assets/js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Three.js CDN & GLTF Loader for 3D Mascot -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/loaders/GLTFLoader.js"></script>
    <script src="<?= base_url('js/washing-machine-3d.js') ?>"></script>

    <!-- Floating Soap Bubbles Layer -->
    <script src="<?= base_url('js/laundry-bubbles.js') ?>"></script>

    <!-- Interactive Mascot Speech Bubble Logic -->
    <script>
        const mascotQuotes = [
            "Halo! Cucian wangi & rapi siap diproses! 🫧",
            "Klik aku untuk memutar mesin cuci 3D lebih cepat! 🌀",
            "Ada garansi cuci ulang 100% jika kurang bersih loh! ✨",
            "Deterjen higienis anti-bakteri siap melindungi pakaian! 👕",
            "Sudah cek nota belum lunas hari ini? Yuk pantau piutang! 📋",
            "Struk kasir POS 58mm/80mm kini siap dicetak instan! 🧾",
            "Semangat melayani pelanggan setia FreshWash hari ini! 🌟"
        ];
        let currentQuoteIndex = 0;

        function handleMascotClick() {
            if (window.triggerWashSpin) {
                window.triggerWashSpin();
            }
            // Rotate speech quote
            currentQuoteIndex = (currentQuoteIndex + 1) % mascotQuotes.length;
            const textEl = document.getElementById('mascotSpeechText');
            const bubbleEl = document.getElementById('mascotSpeechBubble');
            if (textEl && bubbleEl) {
                textEl.innerHTML = mascotQuotes[currentQuoteIndex];
                bubbleEl.style.display = 'block';
                bubbleEl.style.animation = 'none';
                void bubbleEl.offsetWidth; // trigger reflow
                bubbleEl.style.animation = 'bubblePopIn 0.35s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
            }
        }

        function hideMascotSpeech() {
            const bubbleEl = document.getElementById('mascotSpeechBubble');
            if (bubbleEl) {
                bubbleEl.style.display = 'none';
            }
        }

        // Auto change quote periodically
        setInterval(function() {
            const bubbleEl = document.getElementById('mascotSpeechBubble');
            const textEl = document.getElementById('mascotSpeechText');
            if (bubbleEl && textEl && bubbleEl.style.display !== 'none') {
                currentQuoteIndex = (currentQuoteIndex + 1) % mascotQuotes.length;
                textEl.innerHTML = mascotQuotes[currentQuoteIndex];
            }
        }, 14000);
    </script>

    <?= $this->renderSection('scripts') ?>
</body>
</html>
