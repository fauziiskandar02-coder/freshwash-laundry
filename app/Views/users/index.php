<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="section-header bg-transparent shadow-none px-0 mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h1 class="text-dark font-weight-bold mb-1" style="font-size: 1.5rem;">Manajemen Pengguna (User Management)</h1>
        <p class="text-muted mb-0 small">Pengaturan otorisasi akun pengguna, peran hak akses (Admin & Kasir), serta keamanan sistem.</p>
    </div>
    <div>
        <a href="<?= base_url('users/create') ?>" class="btn btn-primary shadow-sm">
            <i class="fas fa-user-plus mr-1"></i> Tambah Pengguna Baru
        </a>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
        <h4><i class="fas fa-users-cog text-primary mr-2"></i> Daftar Pengguna Sistem</h4>
        <span class="badge badge-light"><?= count($users) ?> Akun Terdaftar</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Nama Pengguna</th>
                        <th>Email Login</th>
                        <th>Peran (Role)</th>
                        <th>Status Akun</th>
                        <th>Tanggal Dibuat</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td>
                                <strong class="text-dark"><?= esc($u['name']) ?></strong>
                                <?php if ((int)$u['id'] === (int)session()->get('user_id')): ?>
                                    <span class="badge badge-primary ml-1" style="font-size: 0.65rem;">Akun Anda</span>
                                <?php endif; ?>
                            </td>
                            <td><?= esc($u['email']) ?></td>
                            <td>
                                <?php if ($u['role'] === 'admin'): ?>
                                    <span class="badge badge-info text-uppercase font-weight-bold"><i class="fas fa-user-shield mr-1"></i> ADMIN</span>
                                <?php else: ?>
                                    <span class="badge badge-success text-uppercase font-weight-bold"><i class="fas fa-cash-register mr-1"></i> KASIR</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge badge-<?= $u['is_active'] ? 'success' : 'secondary' ?>">
                                    <?= $u['is_active'] ? 'Aktif' : 'Nonaktif' ?>
                                </span>
                            </td>
                            <td class="small text-muted"><?= $u['created_at'] ? date('d M Y H:i', strtotime($u['created_at'])) : '-' ?></td>
                            <td class="text-center">
                                <a href="<?= base_url("users/edit/{$u['id']}") ?>" class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <?php if ((int)$u['id'] !== (int)session()->get('user_id')): ?>
                                    <a href="<?= base_url("users/delete/{$u['id']}") ?>" onclick="return confirm('Hapus pengguna <?= esc($u['name']) ?>?')" class="btn btn-sm btn-outline-danger" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
