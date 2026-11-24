<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="section-header bg-transparent shadow-none px-0 mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h1 class="text-dark font-weight-bold mb-1" style="font-size: 1.5rem;">Pengaturan Pajak & Profil UMKM</h1>
        <p class="text-muted mb-0 small">Konfigurasi status PKP global, tarif PPN standar, profil bisnis, dan bagan akun akuntansi.</p>
    </div>
</div>

<div class="row">
    <!-- Form Pengaturan -->
    <div class="col-lg-7 col-md-12">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-transparent">
                <h4 class="text-dark"><i class="fas fa-sliders-h text-primary mr-2"></i> Konfigurasi Bisnis & Perpajakan</h4>
            </div>
            <div class="card-body">
                <form action="<?= base_url('settings/update') ?>" method="POST">
                    <?= csrf_field() ?>

                    <h6 class="text-primary font-weight-bold text-uppercase small mb-3">1. Kebijakan Pajak Pertambahan Nilai (PPN)</h6>

                    <div class="p-3 bg-light rounded mb-3 border">
                        <div class="custom-control custom-switch mb-2">
                            <input type="checkbox" class="custom-control-input" id="is_pkp" name="is_pkp" value="1" <?= ($settings['is_pkp'] ?? '1') === '1' ? 'checked' : '' ?>>
                            <label class="custom-control-label font-weight-bold text-dark" for="is_pkp">
                                Status Pengusaha Kena Pajak (PKP) Global
                            </label>
                        </div>
                        <small class="text-muted d-block">
                            Jika aktif, setiap pembuatan faktur penjualan secara default akan memungut PPN Keluaran. Kasir tetap dapat mengubah status per-faktur (Hybrid Mode).
                        </small>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label font-weight-bold">Tarif PPN Standar (%)</label>
                        <div class="input-group" style="max-width: 200px;">
                            <input type="number" step="0.5" min="0" max="100" class="form-control font-weight-bold" name="default_tax_rate" value="<?= esc($settings['default_tax_rate'] ?? '12.00') ?>" required>
                            <div class="input-group-append">
                                <span class="input-group-text font-weight-bold">%</span>
                            </div>
                        </div>
                        <small class="text-muted">Tarif PPN berlaku di Indonesia (misal 11% atau 12%).</small>
                    </div>

                    <h6 class="text-primary font-weight-bold text-uppercase small mb-3 mt-4">2. Identitas Perusahaan & Kop Faktur</h6>

                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold">Nama Perusahaan / Bisnis UMKM <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="company_name" value="<?= esc($settings['company_name'] ?? 'Web UMKM Jasa') ?>" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label class="form-label font-weight-bold">Nomor Telepon Kantor</label>
                            <input type="text" class="form-control" name="company_phone" value="<?= esc($settings['company_phone'] ?? '') ?>">
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label class="form-label font-weight-bold">Email Bisnis</label>
                            <input type="email" class="form-control" name="company_email" value="<?= esc($settings['company_email'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label font-weight-bold">Alamat Usaha (Tampil di Kop Faktur)</label>
                        <textarea class="form-control" name="company_address" rows="3"><?= esc($settings['company_address'] ?? '') ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary px-4 font-weight-bold shadow-sm">
                        <i class="fas fa-save mr-1"></i> Simpan Pengaturan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bagan Akun Standar (COA) Preview -->
    <div class="col-lg-5 col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                <h4 class="text-dark"><i class="fas fa-book text-success mr-2"></i> Bagan Akun Standar (COA)</h4>
                <span class="badge badge-light">AIS Siklus Pendapatan</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama Akun</th>
                                <th>Klasifikasi</th>
                                <th>Saldo Normal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($coa as $acc): ?>
                                <tr>
                                    <td><strong class="text-primary"><?= esc($acc['account_code']) ?></strong></td>
                                    <td><?= esc($acc['account_name']) ?></td>
                                    <td>
                                        <span class="badge badge-light text-uppercase" style="font-size: 0.72rem;"><?= esc($acc['account_type']) ?></span>
                                    </td>
                                    <td>
                                        <span class="small font-weight-bold text-uppercase <?= $acc['normal_balance'] === 'debit' ? 'text-primary' : 'text-success' ?>">
                                            <?= esc($acc['normal_balance']) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-light py-2 small text-muted">
                <i class="fas fa-info-circle mr-1"></i> Akun-akun ini digunakan oleh <code>JournalService</code> untuk memproses debit dan kredit secara otomatis.
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
