<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashData('success')) : ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashData('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?> 

<?= form_open('keranjang/edit') ?>

<table class="table datatable">
    <thead>
        <tr>
            <th scope="col">Nama</th>
            <th scope="col">Foto</th>
            <th scope="col">Harga</th>
            <th scope="col">Jumlah</th>
            <th scope="col">Subtotal</th>
            <th scope="col">Aksi</th> 
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 1;
        if (!empty($items)) :
            foreach ($items as $index => $item) :
        ?>
                <tr>
                    <td><?= $item['name'] ?></td>
                    <td>
                        <?php if (isset($item['options']['foto']) && $item['options']['foto'] != '') : ?>
                            <img src="<?= base_url("img/" . $item['options']['foto']) ?>" width="100px">
                        <?php endif; ?>
                    </td>
                    <td><?= number_to_currency($item['price'], 'IDR') ?></td>
                    <td>
                        <input type="number" min="1" name="qty<?= $i++ ?>" class="form-control" value="<?= $item['qty'] ?>" style="width: 80px;">
                    </td>
                    <td><?= number_to_currency($item['subtotal'], 'IDR') ?></td>
                    <td>
                        <a href="<?= base_url('keranjang/delete/' . $item['rowid']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus produk ini dari keranjang?')">
                            <i class="bi bi-trash"></i>
                        </a>
                    </td>
                </tr>
        <?php
            endforeach;
        else :
        ?>
            <tr>
                <td colspan="6" class="text-center">No entries found</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table> 

<div class="alert alert-info">
    <?= "Total = " . number_to_currency($total, 'IDR') ?>
</div>

<?php if (!empty($items)) : ?>
    <div class="my-3">
        <button type="submit" class="btn btn-primary">Perbarui Keranjang</button>
        
        <a class="btn btn-warning" href="<?= base_url('keranjang/clear') ?>" onclick="return confirm('Apakah Anda yakin ingin mengosongkan seluruh isi keranjang?')">
            Kosongkan Keranjang
        </a>
    </div>
<?php endif; ?>

<?= form_close() ?>

<?= $this->endSection() ?>