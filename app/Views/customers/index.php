<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="section-header bg-transparent shadow-none px-0 mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h1 class="text-dark font-weight-bold mb-1" style="font-size: 1.55rem; font-family: 'Nunito', sans-serif;">
            <i class="fas fa-users text-primary mr-2"></i> Master Member &amp; Pelanggan Laundry
        </h1>
        <p class="text-muted mb-0 small">Database pelanggan langganan, anak kost mahasiswa, keluarga, dan mitra FreshWash Laundry.</p>
    </div>
    <?php if (session()->get('user_role') === 'admin'): ?>
    <div>
        <a href="<?= base_url('customers/create') ?>" class="btn btn-laundry-primary">
            <i class="fas fa-user-plus mr-1"></i> + Tambah Member Baru
        </a>
    </div>
    <?php endif; ?>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
        <h4><i class="fas fa-id-card text-primary mr-2"></i> Daftar Member &amp; Pelanggan Laundry</h4>
        <span class="badge badge-light border text-primary font-weight-bold px-3 py-2" style="border-radius: 12px;"><?= count($customers) ?> MEMBER TERDAFTAR</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Pelanggan</th>
                        <th>No. Telepon / Kontak</th>
                        <th>Email</th>
                        <th>Alamat</th>
                        <th>Status</th>
                        <?php if (session()->get('user_role') === 'admin'): ?>
                        <th class="text-center">Aksi</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($customers)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                Belum ada pelanggan terdaftar.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($customers as $c): ?>
                            <tr>
                                <td><span class="badge badge-light font-weight-bold"><?= esc($c['code']) ?></span></td>
                                <td><strong class="text-dark"><?= esc($c['name']) ?></strong></td>
                                <td><?= esc($c['phone'] ?? '-') ?></td>
                                <td><?= esc($c['email'] ?? '-') ?></td>
                                <td class="small text-muted"><?= esc($c['address'] ?? '-') ?></td>
                                <td>
                                    <span class="badge badge-<?= $c['is_active'] ? 'success' : 'secondary' ?>">
                                        <?= $c['is_active'] ? 'Aktif' : 'Non-Aktif' ?>
                                    </span>
                                </td>
                                <?php if (session()->get('user_role') === 'admin'): ?>
                                <td class="text-center">
                                    <a href="<?= base_url("customers/edit/{$c['id']}") ?>" class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="<?= base_url("customers/delete/{$c['id']}") ?>" onclick="return confirm('Hapus data pelanggan ini?')" class="btn btn-sm btn-outline-danger" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
