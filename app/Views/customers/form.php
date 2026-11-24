<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="section-header bg-transparent shadow-none px-0 mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h1 class="text-dark font-weight-bold mb-1" style="font-size: 1.5rem;"><?= esc($title) ?></h1>
        <p class="text-muted mb-0 small">Lengkapi informasi identitas dan kontak pelanggan.</p>
    </div>
    <div>
        <a href="<?= base_url('customers') ?>" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-7 col-md-10">
        <div class="card shadow-sm">
            <div class="card-header bg-transparent">
                <h4 class="text-dark"><i class="fas fa-id-card text-primary mr-2"></i> Formulir Pelanggan</h4>
            </div>
            <div class="card-body">
                <form action="<?= $customer ? base_url("customers/update/{$customer['id']}") : base_url('customers/store') ?>" method="POST">
                    <?= csrf_field() ?>

                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold">Kode Pelanggan (Opsional)</label>
                        <input type="text" class="form-control" name="code" value="<?= old('code', $customer['code'] ?? '') ?>" placeholder="Kosongkan untuk auto-generate (CUST-001)">
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold">Nama Lengkap / Perusahaan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" value="<?= old('name', $customer['name'] ?? '') ?>" placeholder="Misal: PT Maju Bersama Digital" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label class="form-label font-weight-bold">Nomor Kontak / WhatsApp</label>
                            <input type="text" class="form-control" name="phone" value="<?= old('phone', $customer['phone'] ?? '') ?>" placeholder="0812-xxxx-xxxx">
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label class="form-label font-weight-bold">Alamat Email</label>
                            <input type="email" class="form-control" name="email" value="<?= old('email', $customer['email'] ?? '') ?>" placeholder="kontak@perusahaan.com">
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold">Alamat Lengkap</label>
                        <textarea class="form-control" name="address" rows="3" placeholder="Alamat penagihan atau domisili klien..."><?= old('address', $customer['address'] ?? '') ?></textarea>
                    </div>

                    <div class="form-group mb-4">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" <?= old('is_active', $customer['is_active'] ?? 1) ? 'checked' : '' ?>>
                            <label class="custom-control-label font-weight-bold" for="is_active">Status Pelanggan Aktif</label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end" style="gap: 10px;">
                        <a href="<?= base_url('customers') ?>" class="btn btn-light px-4">Batal</a>
                        <button type="submit" class="btn btn-primary px-4 font-weight-bold shadow-sm">
                            <i class="fas fa-save mr-1"></i> Simpan Data Pelanggan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
