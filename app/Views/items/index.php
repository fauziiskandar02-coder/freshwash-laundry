<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="section-header bg-transparent shadow-none px-0 mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h1 class="text-dark font-weight-bold mb-1" style="font-size: 1.55rem; font-family: 'Nunito', sans-serif;">
            <i class="fas fa-tshirt text-primary mr-2"></i> Paket &amp; Layanan Cuci Laundry
        </h1>
        <p class="text-muted mb-0 small">Katalog tarif cuci kiloan, dry clean, bed cover, dan setrika uap untuk kasir FreshWash Laundry.</p>
    </div>
    <?php if (session()->get('user_role') === 'admin'): ?>
    <div>
        <a href="<?= base_url('items/create') ?>" class="btn btn-laundry-primary">
            <i class="fas fa-plus mr-1"></i> + Tambah Paket Cuci
        </a>
    </div>
    <?php endif; ?>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
        <h4><i class="fas fa-soap text-primary mr-2"></i> Daftar Paket &amp; Tarif Cuci</h4>
        <span class="badge badge-light border text-primary font-weight-bold px-3 py-2" style="border-radius: 12px;"><?= count($items) ?> PAKET TERSEDIA</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Layanan Jasa</th>
                        <th>Deskripsi</th>
                        <th>Satuan</th>
                        <th class="text-right">Tarif / Harga Jual</th>
                        <th>Status</th>
                        <?php if (session()->get('user_role') === 'admin'): ?>
                        <th class="text-center">Aksi</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($items)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                Belum ada data layanan jasa.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($items as $item): ?>
                            <tr>
                                <td><span class="badge badge-light font-weight-bold"><?= esc($item['code']) ?></span></td>
                                <td><strong class="text-dark"><?= esc($item['name']) ?></strong></td>
                                <td class="small text-muted"><?= esc($item['description'] ?? '-') ?></td>
                                <td><span class="badge badge-light text-uppercase"><?= esc($item['unit']) ?></span></td>
                                <td class="text-right font-weight-bold text-dark"><?= format_rupiah($item['sell_price']) ?></td>
                                <td>
                                    <span class="badge badge-<?= $item['is_active'] ? 'success' : 'secondary' ?>">
                                        <?= $item['is_active'] ? 'Aktif' : 'Non-Aktif' ?>
                                    </span>
                                </td>
                                <?php if (session()->get('user_role') === 'admin'): ?>
                                <td class="text-center">
                                    <a href="<?= base_url("items/edit/{$item['id']}") ?>" class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="<?= base_url("items/delete/{$item['id']}") ?>" onclick="return confirm('Hapus layanan jasa ini?')" class="btn btn-sm btn-outline-danger" title="Hapus">
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
