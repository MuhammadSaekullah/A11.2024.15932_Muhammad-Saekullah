<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="pagetitle">
    <h1>Keranjang</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Home</a></li>
            <li class="breadcrumb-item active">Keranjang</li>
        </ol>
    </nav>
</div><section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body pt-3">
                    <h5 class="card-title opacity-75 mb-0">Keranjang</h5>

                    <?php if (session()->getFlashdata('success')) : ?>
                        <div class="alert alert-success alert-dismissible fade show my-3" role="alert">
                            <?= session()->getFlashdata('success') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($items)) : ?>
                        <?= form_open('keranjang/edit') ?>

                        <div class="d-flex justify-content-between align-items-center my-3 text-secondary small">
                            <div>
                                <label>
                                    <select class="form-select form-select-sm d-inline-block w-auto me-1">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                    </select> entries per page
                                </label>
                            </div>
                            <div>
                                <label class="d-flex align-items-center">
                                    Search... <input type="search" class="form-control form-control-sm ms-2" style="width: 150px;">
                                </label>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table align-middle table-bordered mb-0 text-dark small">
                                <thead class="table-light fw-bold text-secondary">
                                    <tr>
                                        <th scope="col" class="py-2">Nama</th>
                                        <th scope="col" class="py-2 text-center" width="15%">Foto</th>
                                        <th scope="col" class="py-2" width="15%">Harga</th>
                                        <th scope="col" class="py-2" width="20%">Jumlah</th>
                                        <th scope="col" class="py-2" width="15%">Subtotal</th>
                                        <th scope="col" class="py-2 text-center" width="8%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $i = 1;
                                    foreach ($items as $item) : 
                                    ?>
                                        <tr>
                                            <td class="fw-semibold"><?= esc($item['name']) ?></td>
                                            
                                            <td class="text-center">
                                                <img src="<?= base_url('img/' . ($item['options']['foto'] ?? 'default.jpg')) ?>" class="img-fluid border p-1" alt="..." style="max-height: 65px; object-fit: contain;">
                                            </td>
                                            
                                            <td class="text-muted">
                                                <?= number_to_currency($item['price'], 'IDR') ?>
                                            </td>
                                            
                                            <td>
                                                <input type="hidden" name="rowid[<?= $i ?>]" value="<?= $item['rowid'] ?>">
                                                <input type="number" name="qty[<?= $i ?>]" class="form-control form-control-sm" value="<?= $item['qty'] ?>" min="1">
                                            </td>
                                            
                                            <td class="fw-semibold">
                                                <?= number_to_currency($item['price'] * $item['qty'], 'IDR') ?>
                                            </td>
                                            
                                            <td class="text-center">
                                                <a href="<?= base_url('keranjang/delete/' . $item['rowid']) ?>" class="btn btn-danger btn-sm rounded-1 px-2" onclick="return confirm('Hapus produk ini dari keranjang?')" style="background-color: #dc3545; border-color: #dc3545;">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php 
                                        $i++;
                                    endforeach; 
                                    ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="text-secondary small my-3">
                            Showing 1 to <?= count($items) ?> of <?= count($items) ?> entries
                        </div>

                        <div class="alert alert-info py-2 px-3 border-0 rounded-1 text-dark fw-semibold small mb-4" style="background-color: #cff4fc;">
                            Total = <?= number_to_currency($total, 'IDR') ?>
                        </div>

                        <div class="d-flex gap-1 mb-2">
                            <button type="submit" class="btn btn-primary rounded-1 btn-sm px-3 fw-medium" style="background-color: #0d6efd; border-color: #0d6efd;">
                                Perbarui Keranjang
                            </button>

                            <a href="<?= base_url('keranjang/clear') ?>" class="btn btn-warning rounded-1 btn-sm px-3 text-dark fw-medium" onclick="return confirm('Apakah Anda yakin ingin mengosongkan seluruh keranjang?')" style="background-color: #ffc107; border-color: #ffc107;">
                                Kosongkan Keranjang
                            </a>

                            <a href="<?= base_url('keranjang/checkout') ?>" class="btn btn-success rounded-1 btn-sm px-3 fw-medium" style="background-color: #198754; border-color: #198754;">
                                Selesai Belanja
                            </a>
                        </div>

                        <?= form_close() ?>

                    <?php else : ?>
                        <div class="text-center py-5">
                            <p class="text-muted mb-3">Keranjang belanja Anda kosong.</p>
                            <a href="<?= base_url('/') ?>" class="btn btn-primary btn-sm rounded-1 px-4">
                                <i class="bi bi-arrow-left me-1"></i> Kembali Belanja
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>