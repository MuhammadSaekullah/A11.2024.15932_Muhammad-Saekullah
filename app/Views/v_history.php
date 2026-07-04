<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
History Transaksi Pembelian <strong><?= $username ?></strong>
<hr>
<div class="table-responsive">
    <!-- Table with stripped rows -->
    <table class="table datatable">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">ID Pembelian</th>
                <th scope="col">Waktu Pembelian</th>
                <th scope="col">Total Bayar</th>
                <th scope="col">Alamat</th>
                <th scope="col">Status</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($transactions)) :
                foreach ($transactions as $index => $item) :
            ?>
                    <tr>
                        <th scope="row"><?= $index + 1 ?></th>
                        <td><?= $item['id'] ?></td>
                        <td><?= $item['created_at'] ?></td>
                        <td><?= number_to_currency($item['total_harga'], 'IDR') ?></td>
                        <td><?= $item['alamat'] ?></td>
                        <td>
                            <?= ($item['status'] == "1")
                                ? '<span class="badge bg-success">Sudah Selesai</span>'
                                : '<span class="badge bg-warning">Belum Selesai</span>' ?>
                        </td>
                        <td>
                            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#detailModal-<?= $item['id'] ?>">
                                Detail
                            </button>
                        </td>
                    </tr> 
            <?php
                endforeach;
            endif;
            ?>
        </tbody>
    </table>
    <!-- End Table with stripped rows -->
</div>

<?php if (!empty($transactions)) : ?>
    <?php foreach ($transactions as $item) : ?>
        <!-- Detail Modal Begin -->
        <div class="modal fade" id="detailModal-<?= $item['id'] ?>" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail Transaksi #<?= $item['id'] ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body"> 
                        <?php if (!empty($products[$item['id']])) : ?>
                            <?php foreach ($products[$item['id']] as $index2 => $item2) : ?>
                                <?= $index2 + 1 . ")" ?>
                                
                                <?php
                                $imagePath = FCPATH . 'img/' . $item2['foto'];

                                if (!empty($item2['foto']) && file_exists($imagePath)) :
                                ?>
                                    <div class="my-2">
                                        <img src="<?= base_url('img/' . $item2['foto']) ?>" width="100" class="img-thumbnail">
                                    </div>
                                <?php endif; ?>

                                <strong><?= $item2['nama'] ?></strong>
                                <?= number_to_currency($item2['harga'], 'IDR') ?>
                                <br>
                                <?= "(" . $item2['jumlah'] . " pcs)" ?><br>
                                <?= number_to_currency($item2['subtotal_harga'], 'IDR') ?>
                                <hr>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <!-- ==== TAMBAHAN: breakdown voucher, biaya jasa, free mouse, dan grand total ==== -->
                        <?php
                            // Hitung ulang subtotal barang dari daftar produk transaksi ini
                            $subtotalBarang = 0;
                            if (!empty($products[$item['id']])) {
                                foreach ($products[$item['id']] as $p) {
                                    $subtotalBarang += $p['subtotal_harga'];
                                }
                            }
                        ?>
                        <div class="d-flex justify-content-between">
                            <span>Subtotal Barang</span>
                            <span><?= number_to_currency($subtotalBarang, 'IDR') ?></span>
                        </div>

                        <?php if (!empty($item['kode_voucher'])) : ?>
                            <div class="d-flex justify-content-between text-danger">
                                <span>Diskon Voucher (<?= esc($item['kode_voucher']) ?>)</span>
                                <span>-<?= number_to_currency($item['diskon_voucher'] ?? 0, 'IDR') ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($item['biaya_jasa'])) : ?>
                            <div class="d-flex justify-content-between">
                                <span>Biaya Jasa</span>
                                <span><?= number_to_currency($item['biaya_jasa'], 'IDR') ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($item['free_mouse_diskon'])) : ?>
                            <div class="d-flex justify-content-between text-success">
                                <span>Free Mouse</span>
                                <span>-<?= number_to_currency($item['free_mouse_diskon'], 'IDR') ?></span>
                            </div>
                        <?php endif; ?>

                        <div class="d-flex justify-content-between">
                            <span>Ongkir</span>
                            <span><?= number_to_currency($item['ongkir'], 'IDR') ?></span>
                        </div>

                        <hr>
                        <div class="d-flex justify-content-between fw-bold">
                            <span>Grand Total</span>
                            <span><?= number_to_currency($item['total_harga'], 'IDR') ?></span>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- Detail Modal End -->
    <?php endforeach; ?>
<?php endif; ?>
<?= $this->endSection() ?>