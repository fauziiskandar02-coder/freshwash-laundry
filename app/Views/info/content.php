<!-- Header Info Banner (Custom Non-Card Container to Avoid Stisla Override) -->
<div class="mb-4 shadow p-4 p-md-5" style="background: linear-gradient(135deg, #0369a1 0%, #0284c7 60%, #0ea5e9 100%) !important; border-radius: 22px; color: #ffffff !important; overflow: hidden; position: relative; border: 2px solid #bae6fd;">
    <div style="position: absolute; right: -10px; bottom: -20px; font-size: 8.5rem; opacity: 0.18; pointer-events: none;">
        🧺
    </div>
    <div class="position-relative" style="z-index: 2;">
        <div class="d-flex flex-wrap align-items-center mb-3" style="gap: 10px;">
            <span class="badge font-weight-bold px-3 py-2 shadow-sm" style="border-radius: 12px; font-size: 0.84rem; background: #ffffff !important; color: #0284c7 !important;">
                <i class="fas fa-graduation-cap mr-1"></i> TUGAS BESAR KELOMPOK 5 &bull; KELAS C/P2
            </span>
            <span class="badge font-weight-bold px-3 py-2 shadow-sm" style="border-radius: 12px; font-size: 0.84rem; background: #fef08a !important; color: #854d0e !important;">
                <i class="fas fa-check-double mr-1"></i> KOMAK TI &amp; AABW
            </span>
            <span class="badge font-weight-bold px-3 py-2 shadow-sm" style="border-radius: 12px; font-size: 0.84rem; background: rgba(255,255,255,0.25) !important; color: #ffffff !important; border: 1px solid rgba(255,255,255,0.4);">
                <i class="fas fa-university mr-1"></i> Sekolah Vokasi &mdash; IPB University
            </span>
        </div>
        <h1 class="font-weight-bold mb-2" style="font-size: 1.85rem; font-family: 'Nunito', sans-serif; color: #ffffff !important; text-shadow: 0 2px 4px rgba(0,0,0,0.15);">
            Profil Kelompok 5 &amp; Informasi Sistem FreshWash Laundry
        </h1>
        <p class="mb-0" style="font-size: 0.98rem; max-width: 860px; line-height: 1.6; color: #f0f9ff !important; font-weight: 500;">
            Aplikasi Sistem Informasi Akuntansi (SIA) Siklus Pendapatan untuk UMKM Usaha Jasa Laundry &amp; Dry Cleaning. Dilengkapi otomatisasi penjurnalan double-entry kasir POS, cetak struk nota thermal, dan monitoring piutang member berbasis web cloud.
        </p>
    </div>
</div>

<!-- ========================================================
     DAFTAR ANGGOTA KELOMPOK 5 (BERDERET & RATA TENGAH DENGAN FOTO AVATAR)
     ======================================================== -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="font-weight-bold text-dark mb-0" style="font-size: 1.35rem;">
            <i class="fas fa-users text-primary mr-2"></i> Susunan Anggota Tim Kelompok 5
        </h3>
        <p class="text-muted small mb-0">Disusun berderet rapi di tengah beserta NIM, peran teknis, dan avatar karakter khusus tim:</p>
    </div>
    <span class="badge badge-pill badge-primary px-3 py-2 font-weight-bold d-none d-sm-inline-block" style="border-radius: 20px;">
        5 Anggota Terdaftar
    </span>
</div>

<!-- 5 Member Cards Centered -->
<div class="d-flex flex-wrap justify-content-center align-items-stretch mb-4" style="gap: 16px;">
    <?php foreach ($members as $idx => $m): ?>
    <div class="mb-3" style="flex: 1 1 200px; max-width: 235px; min-width: 195px;">
        <div class="card h-100 border-0 shadow-sm text-center member-card" style="border-radius: 20px; transition: transform 0.25s ease, box-shadow 0.25s ease; border: 2px solid <?= $m['color'] ?>40; background: #ffffff;">
            <div class="card-body p-3 d-flex flex-column align-items-center justify-content-between text-center">
                <div class="w-100 d-flex flex-column align-items-center">
                    <!-- Avatar Image Centered -->
                    <div class="position-relative mb-3 mt-2 mx-auto" style="width: 110px; height: 110px;">
                        <img src="<?= base_url('images/team/' . $m['image']) ?>" 
                             alt="<?= esc($m['name']) ?>" 
                             class="rounded-circle shadow" 
                             style="width: 110px; height: 110px; object-fit: cover; border: 4px solid <?= $m['color'] ?>; padding: 2px; background: #ffffff; display: block; margin: 0 auto;">
                        <span class="position-absolute" style="bottom: -2px; right: 2px; background: <?= $m['color'] ?>; color: #fff; width: 26px; height: 26px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: bold; border: 2px solid #fff;">
                            <?= $idx + 1 ?>
                        </span>
                    </div>

                    <!-- Member Name & NIM Centered -->
                    <h5 class="font-weight-bold text-dark mb-1 text-center" style="font-size: 1.02rem; min-height: 48px; display: flex; align-items: center; justify-content: center; width: 100%;">
                        <?= esc($m['name']) ?>
                    </h5>
                    
                    <div class="mb-2 text-center w-100">
                        <span class="badge font-weight-bold px-2 py-1" style="background: #e0f2fe; color: #0284c7; border-radius: 8px; font-size: 0.78rem; letter-spacing: 0.5px;">
                            <i class="fas fa-id-card-alt mr-1"></i> <?= esc($m['nim']) ?>
                        </span>
                    </div>

                    <!-- Role Badge -->
                    <div class="mb-2 text-center w-100">
                        <span class="badge text-white font-weight-600 px-2 py-1" style="background: <?= $m['color'] ?>; border-radius: 6px; font-size: 0.7rem;">
                            <?= esc($m['badge']) ?>
                        </span>
                    </div>

                    <!-- Task Scope -->
                    <p class="text-muted small mb-3 text-center px-1" style="font-size: 0.74rem; line-height: 1.45; background: #f8fafc; border-radius: 10px; padding: 8px; width: 100%;">
                        <?= esc($m['task']) ?>
                    </p>
                </div>

                <!-- Avatar Personality Label -->
                <div class="w-100 pt-2 border-top text-center">
                    <span class="text-muted" style="font-size: 0.72rem; font-weight: 600;">
                        <?= esc($m['avatar']) ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- ========================================================
     INFORMASI AKADEMIK & MATA KULIAH
     ======================================================== -->
<div class="row mb-4">
    <div class="col-lg-6 col-md-12 mb-3">
        <div class="card h-100 border-0 shadow-sm" style="border-radius: 18px; border-left: 5px solid #0284c7;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center mr-3 shadow-sm" style="width: 48px; height: 48px; background: #e0f2fe; color: #0284c7; font-size: 1.3rem;">
                        <i class="fas fa-book-reader"></i>
                    </div>
                    <div>
                        <h4 class="font-weight-bold text-dark mb-0" style="font-size: 1.15rem;">Informasi Mata Kuliah</h4>
                        <span class="text-muted small">Program PJBL Sekolah Vokasi IPB</span>
                    </div>
                </div>

                <table class="table table-sm table-borderless mb-0">
                    <tbody>
                        <tr>
                            <td class="text-muted font-weight-bold px-0" style="width: 130px;">Mata Kuliah</td>
                            <td class="font-weight-600 text-dark px-1">: Komputerisasi Akuntansi (KOMAK TI) &amp; AABW</td>
                        </tr>
                        <tr>
                            <td class="text-muted font-weight-bold px-0">Kelas / Sesi</td>
                            <td class="font-weight-600 text-dark px-1">: <span class="badge badge-primary font-weight-bold" style="border-radius: 6px;">Kelas C / P2</span></td>
                        </tr>
                        <tr>
                            <td class="text-muted font-weight-bold px-0">Institusi</td>
                            <td class="font-weight-600 text-dark px-1">: Sekolah Vokasi &mdash; Institut Pertanian Bogor (IPB)</td>
                        </tr>
                        <tr>
                            <td class="text-muted font-weight-bold px-0">Objek Studi</td>
                            <td class="font-weight-600 text-dark px-1">: FreshWash Laundry &amp; Dry Cleaning (Jasa UMKM)</td>
                        </tr>
                        <tr>
                            <td class="text-muted font-weight-bold px-0">Siklus Akuntansi</td>
                            <td class="font-weight-600 text-dark px-1">: Revenue Cycle (Order, Invoicing, Cash Receipt, Sales Return)</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6 col-md-12 mb-3">
        <div class="card h-100 border-0 shadow-sm" style="border-radius: 18px; border-left: 5px solid #10b981;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center mr-3 shadow-sm" style="width: 48px; height: 48px; background: #d1fae5; color: #059669; font-size: 1.3rem;">
                        <i class="fas fa-server"></i>
                    </div>
                    <div>
                        <h4 class="font-weight-bold text-dark mb-0" style="font-size: 1.15rem;">Arsitektur Cloud &amp; Framework</h4>
                        <span class="text-muted small">Modern High-Availability Serverless Stack</span>
                    </div>
                </div>

                <table class="table table-sm table-borderless mb-0">
                    <tbody>
                        <tr>
                            <td class="text-muted font-weight-bold px-0" style="width: 130px;">Framework Web</td>
                            <td class="font-weight-600 text-dark px-1">: <b>CodeIgniter 4.7.4</b> (MVC Architecture)</td>
                        </tr>
                        <tr>
                            <td class="text-muted font-weight-bold px-0">Runtime PHP</td>
                            <td class="font-weight-600 text-dark px-1">: <b>PHP 8.3</b> Serverless Lambda (Vercel)</td>
                        </tr>
                        <tr>
                            <td class="text-muted font-weight-bold px-0">Database Cloud</td>
                            <td class="font-weight-600 text-dark px-1">: <b>TiDB Cloud Serverless</b> (Port 4000, SSL Encrypted)</td>
                        </tr>
                        <tr>
                            <td class="text-muted font-weight-bold px-0">Frontend Engine</td>
                            <td class="font-weight-600 text-dark px-1">: Stisla Bootstrap 4 + Three.js 3D WebGL Canvas</td>
                        </tr>
                        <tr>
                            <td class="text-muted font-weight-bold px-0">Source Code Repo</td>
                            <td class="font-weight-600 text-dark px-1">: <a href="https://github.com/fauziiskandar02-coder/freshwash-laundry" target="_blank" class="text-primary font-weight-bold"><i class="fab fa-github"></i> GitHub (Public Repo)</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ========================================================
     FITUR UNGGULAN & AVATAR TRIVIA
     ======================================================== -->
<div class="card border-0 shadow-sm mb-4" style="border-radius: 18px; background: #fdfdfd; border: 1px solid #e2e8f0;">
    <div class="card-body p-4">
        <h4 class="font-weight-bold text-dark mb-3" style="font-size: 1.15rem;">
            <i class="fas fa-star text-warning mr-2"></i> Keunggulan Sistem &amp; Estetika Avatar Khusus
        </h4>
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="d-flex">
                    <div class="mr-3 text-primary" style="font-size: 1.25rem;"><i class="fas fa-print"></i></div>
                    <div>
                        <h6 class="font-weight-bold text-dark mb-1">Cetak Struk Thermal 58mm / 80mm</h6>
                        <p class="small text-muted mb-0">Format struk kasir mini kompatibel printer bluetooth/thermal langsung dari browser tanpa driver tambahan.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="d-flex">
                    <div class="mr-3 text-success" style="font-size: 1.25rem;"><i class="fas fa-balance-scale"></i></div>
                    <div>
                        <h6 class="font-weight-bold text-dark mb-1">Jurnal Umum Double-Entry Otomatis</h6>
                        <p class="small text-muted mb-0">Setiap faktur, pembayaran cicilan kas, dan retur cuci ulang otomatis diposting ke jurnal akuntansi berimbang (Debit/Kredit).</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="d-flex">
                    <div class="mr-3 text-info" style="font-size: 1.25rem;"><i class="fas fa-cube"></i></div>
                    <div>
                        <h6 class="font-weight-bold text-dark mb-1">Maskot Mesin Cuci 3D Interaktif Three.js</h6>
                        <p class="small text-muted mb-0">Stage 3D dinamis yang dapat dirotasi secara langsung di dashboard utama lengkap dengan efek gelembung sabun animasi.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="d-flex">
                    <div class="mr-3 text-primary" style="font-size: 1.25rem;"><i class="fas fa-user-astronaut"></i></div>
                    <div>
                        <h6 class="font-weight-bold text-dark mb-1">Edisi Avatar Khusus &amp; Identitas Tim</h6>
                        <p class="small text-muted mb-0">Setiap anggota tim dipersonalisasi dengan avatar karakter anime unik sesuai dengan peran teknis masing-masing di kelompok.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.member-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 16px 28px rgba(2, 132, 199, 0.16) !important;
    border-color: #0284c7 !important;
}
</style>
