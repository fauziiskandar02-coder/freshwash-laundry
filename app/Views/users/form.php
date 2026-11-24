<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="section-header bg-transparent shadow-none px-0 mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h1 class="text-dark font-weight-bold mb-1" style="font-size: 1.5rem;"><?= esc($title) ?></h1>
        <p class="text-muted mb-0 small">Atur hak akses pengguna ke aplikasi Web UMKM.</p>
    </div>
    <div>
        <a href="<?= base_url('users') ?>" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-6 col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-transparent">
                <h4 class="text-dark"><i class="fas fa-user-edit text-primary mr-2"></i> Formulir Pengguna</h4>
            </div>
            <div class="card-body">
                <form action="<?= $user ? base_url("users/update/{$user['id']}") : base_url('users/store') ?>" method="POST">
                    <?= csrf_field() ?>

                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" value="<?= old('name', $user['name'] ?? '') ?>" placeholder="Misal: Budi Santoso" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold">Alamat Email Login <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" name="email" value="<?= old('email', $user['email'] ?? '') ?>" placeholder="nama@umkm.id" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold">
                            Kata Sandi (Password) <?= $user ? '<span class="text-muted font-weight-normal">(Kosongkan jika tidak diubah)</span>' : '<span class="text-danger">*</span>' ?>
                        </label>
                        <input type="password" class="form-control" name="password" placeholder="Minimal 6 karakter" <?= $user ? '' : 'required' ?>>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold">Peran (Role Akses) <span class="text-danger">*</span></label>
                        <select class="form-control" name="role" required>
                            <option value="kasir" <?= old('role', $user['role'] ?? '') === 'kasir' ? 'selected' : '' ?>>
                                Kasir &mdash; Hanya Akses Transaksi (Faktur, Kas, Retur)
                            </option>
                            <option value="admin" <?= old('role', $user['role'] ?? '') === 'admin' ? 'selected' : '' ?>>
                                Admin &mdash; Akses Penuh (Transaksi, Master Data, Laporan, Pengaturan, User)
                            </option>
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" <?= old('is_active', $user['is_active'] ?? 1) ? 'checked' : '' ?>>
                            <label class="custom-control-label font-weight-bold" for="is_active">Status Akun Aktif (Dapat Login)</label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end" style="gap: 10px;">
                        <a href="<?= base_url('users') ?>" class="btn btn-light px-4">Batal</a>
                        <button type="submit" class="btn btn-primary px-4 font-weight-bold shadow-sm">
                            <i class="fas fa-save mr-1"></i> Simpan Pengguna
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
