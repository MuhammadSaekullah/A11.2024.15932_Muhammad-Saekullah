<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashData('success')) : ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashData('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="row">
    <?php if (!empty($products)) : ?>
        <?php foreach ($products as $key => $item) : ?>         
            <div class="col-lg-6 mb-4">
                <?= form_open('keranjang/add') ?>
                
                <?= form_hidden([
                    'id'    => $item['id'],
                    'nama'  => $item['nama'],
                    'harga' => $item['harga'],
                    'foto'  => $item['foto']
                ]) ?>
                
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body text-center pt-4">
                        <div class="mb-3" style="height: 180px; display: flex; align-items: center; justify-content: center;">
                            <img src="<?= base_url() . "img/" . $item['foto'] ?>" alt="<?= esc($item['nama']) ?>" style="max-height: 100%; max-width: 80%; object-fit: contain;">
                        </div>
                        <h5 class="card-title text-dark fw-bold mb-1"><?= esc($item['nama']) ?></h5>
                        <p class="text-primary fw-semibold mb-3"><?= number_to_currency($item['harga'], 'IDR') ?></p>
                        <button type="submit" class="btn btn-info rounded-pill px-4 text-white fw-semibold">
                            <i class="bi bi-cart-plus-fill me-1"></i> Beli
                        </button>
                    </div>
                </div>
                
                <?= form_close() ?>
            </div> 
        <?php endforeach ?> 
    <?php else : ?>
        <div class="col-12 text-center py-5">
            <p class="text-muted">Belum ada produk yang tersedia.</p>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>