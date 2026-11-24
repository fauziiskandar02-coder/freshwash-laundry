<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="section-header bg-transparent shadow-none px-0 mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h1 class="text-dark font-weight-bold mb-1" style="font-size: 1.5rem;"><?= esc($title) ?></h1>
        <p class="text-muted mb-0 small">Tentukan tarif standar layanan agar faktur terisi otomatis.</p>
    </div>
    <div>
        <a href="<?= base_url('items') ?>" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-7 col-md-10">
        <div class="card shadow-sm">
            <div class="card-header bg-transparent">
                <h4 class="text-dark"><i class="fas fa-concierge-bell text-primary mr-2"></i> Formulir Layanan Jasa</h4>
            </div>
            <div class="card-body">
                <form action="<?= $item ? base_url("items/update/{$item['id']}") : base_url('items/store') ?>" method="POST">
                    <?= csrf_field() ?>

                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold">Kode Layanan (Opsional)</label>
                        <input type="text" class="form-control" name="code" value="<?= old('code', $item['code'] ?? '') ?>" placeholder="Kosongkan untuk auto-generate (SRV-001)">
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold">Nama Layanan Jasa <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" value="<?= old('name', $item['name'] ?? '') ?>" placeholder="Misal: Jasa Desain UI/UX & Website" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label class="form-label font-weight-bold">Tarif / Harga Jual Standar (Rp) <span class="text-danger">*</span></label>
                            <input type="number" step="1000" min="0" class="form-control font-weight-bold" name="sell_price" value="<?= old('sell_price', (float)($item['sell_price'] ?? 0)) ?>" required>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label class="form-label font-weight-bold">Satuan Layanan</label>
                            <input type="text" class="form-control" name="unit" value="<?= old('unit', $item['unit'] ?? 'transaksi') ?>" placeholder="transaksi / paket / sesi / bulan">
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold">Deskripsi / Ruang Lingkup Layanan</label>
                        <textarea class="form-control" name="description" rows="3" placeholder="Rincian lingkup pekerjaan jasa..."><?= old('description', $item['description'] ?? '') ?></textarea>
                    </div>

                    <div class="form-group mb-4">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" <?= old('is_active', $item['is_active'] ?? 1) ? 'checked' : '' ?>>
                            <label class="custom-control-label font-weight-bold" for="is_active">Layanan Aktif (Dapat dipilih di faktur)</label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end" style="gap: 10px;">
                        <a href="<?= base_url('items') ?>" class="btn btn-light px-4">Batal</a>
                        <button type="submit" class="btn btn-primary px-4 font-weight-bold shadow-sm">
                            <i class="fas fa-save mr-1"></i> Simpan Layanan Jasa
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
