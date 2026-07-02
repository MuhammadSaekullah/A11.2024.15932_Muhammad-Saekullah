<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<div class="pagetitle">
    <h1>Checkout</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Home</a></li>
            <li class="breadcrumb-item active">Checkout</li>
        </ol>
    </nav>
</div><section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm border-0">
                <div class="card-body pt-3">
                    <h5 class="card-title opacity-75 mb-4">Checkout</h5>

                    <?= form_open('buy', ['class' => 'row g-4', 'id' => 'form-checkout']) ?>
                    <?= form_hidden('username', session()->get('username')) ?>
                    <input type="hidden" name="total_harga" id="total_harga" value="<?= $total ?>">

                    <div class="col-lg-6">
                        <div class="pe-lg-3">
                            <div class="mb-3">
                                <label for="nama" class="form-label text-secondary small fw-bold">Nama</label>
                                <?= form_input([
                                    'name'     => 'nama',
                                    'id'       => 'nama',
                                    'class'    => 'form-control form-control-sm',
                                    'value'    => session()->get('username'),
                                    'readonly' => true
                                ]) ?>
                            </div>

                            <div class="mb-3">
                                <label for="alamat" class="form-label text-secondary small fw-bold">Alamat</label>
                                <?= form_input([
                                    'name'        => 'alamat',
                                    'id'          => 'alamat',
                                    'class'       => 'form-control form-control-sm',
                                    'placeholder' => 'Masukkan nama jalan atau nomor rumah'
                                ]) ?>
                            </div>

                            <div class="mb-3">
                                <label for="kelurahan" class="form-label text-secondary small fw-bold">Kelurahan</label>
                                <select name="destination_id" id="kelurahan" class="form-select form-select-sm select2-searchable">
                                    <option value="">Ketik nama kelurahan...</option>
                                    <?php 
                                    if (!empty($response) && isset($response['data']) && is_array($response['data'])) : 
                                        foreach ($response['data'] as $wilayah) : 
                                    ?>
                                        <option value="<?= esc($wilayah['id']) ?>">
                                            <?= esc($wilayah['label']) ?>
                                        </option>
                                    <?php 
                                        endforeach;
                                    endif; 
                                    ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="layanan" class="form-label text-secondary small fw-bold">Layanan</label>
                                <?= form_dropdown('layanan', [], '', ['id' => 'layanan', 'class' => 'form-select form-select-sm']) ?>
                            </div>

                            <div class="mb-4">
                                <label for="ongkir" class="form-label text-secondary small fw-bold">Ongkir</label>
                                <?= form_input([
                                    'name'     => 'ongkir',
                                    'id'       => 'ongkir',
                                    'class'    => 'form-control form-control-sm bg-light fw-semibold text-dark',
                                    'value'    => 0,
                                    'readonly' => true
                                ]) ?>
                            </div>

                            <div class="pt-2">
                                <button type="submit" class="btn btn-primary rounded-1 btn-sm px-4 fw-medium" style="background-color: #0d6efd; border-color: #0d6efd;">
                                    Buat Pesanan
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="table-responsive border-start ps-lg-4">
                            <table class="table align-middle text-dark small table-sm">
                                <thead class="table-transparent fw-bold text-secondary border-bottom">
                                    <tr>
                                        <th scope="col" class="py-2">Nama</th>
                                        <th scope="col" class="py-2 text-end" width="25%">Harga</th>
                                        <th scope="col" class="py-2 text-center" width="15%">Jumlah</th>
                                        <th scope="col" class="py-2 text-end" width="25%">Sub Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($items)) : ?>
                                        <?php foreach ($items as $item) : ?>
                                            <tr class="border-bottom-dashed">
                                                <td class="py-3 fw-medium">
                                                    <?= esc($item['name']) ?>
                                                </td>
                                                <td class="py-3 text-end text-muted">
                                                    <?= number_to_currency($item['price'], 'IDR') ?>
                                                </td>
                                                <td class="py-3 text-center">
                                                    <?= esc($item['qty']) ?>
                                                </td>
                                                <td class="py-3 text-end fw-bold">
                                                    <?= number_to_currency($item['price'] * $item['qty'], 'IDR') ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>

                                    <tr>
                                        <td colspan="3" class="pt-4 text-end text-secondary fw-medium">Subtotal</td>
                                        <td class="pt-4 text-end fw-bold text-dark">
                                            <?= number_to_currency($total, 'IDR') ?>
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <td colspan="3" class="py-2 text-end text-secondary fw-medium">Ongkir</td>
                                        <td class="py-2 text-end fw-bold text-dark" id="tampil_ongkir">
                                            IDR 0
                                        </td>
                                    </tr>

                                    <tr class="border-top">
                                        <td colspan="3" class="pt-3 text-end text-dark fw-bold fs-6">Total</td>
                                        <td class="pt-3 text-end text-primary fw-extrabold fs-5" id="total">
                                            IDR 0
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <?= form_close() ?>

                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
let ongkir = 0;
let subtotal = <?= $total ?>;

$(document).ready(function() {
    hitungTotal();

    $('.select2-searchable').select2({
        theme: 'bootstrap-5',
        placeholder: 'Ketik nama kelurahan...',
        allowClear: true
    });

    // EVENT DETEKSI PERUBAHAN KELURAHAN
    $("#kelurahan").on('change', function () {
        let id_kelurahan = $(this).val();

        $("#layanan").empty();
        ongkir = 0;
        hitungTotal(); 

        if (!id_kelurahan) {
            return;
        }

        $('#layanan').html('<option value="">Memuat layanan kurir...</option>');
        $("#ongkir").val("Menghitung...");
        $("#tampil_ongkir").text("Menghitung...");
        $("#total").text("Menghitung...");

        // DIUBAH MENJADI GET AGAR SESUAI DENGAN TRACKING LOG INSPECT NETWORK ANDA
        $.ajax({
            url: "<?= site_url('ajax/costs') ?>", 
            type: "GET", 
            dataType: "json",
            data: {
                destination: id_kelurahan
            },
            success: function (data) { 
                $("#layanan").empty();
                
                if (data && data.length > 0) {
                    data.forEach(function (item) {
                        // Merender teks label dengan format: Nama Layanan (Kode) : estimasi waktu kirim
                        let estimasiText = item.etd ? ` : estimasi ${item.etd}` : '';
                        $("#layanan").append(
                            $('<option>', {
                                value: item.cost,
                                text: `${item.description} (${item.service})${estimasiText}`
                            })
                        );
                    });

                    // Set nilai awal dari opsi kurir teratas yang berhasil dimuat
                    ongkir = parseInt($("#layanan").val()) || 0;
                    hitungTotal();
                } else {
                    $("#layanan").html('<option value="0">Layanan tidak tersedia</option>');
                    ongkir = 0;
                    hitungTotal();
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", error);
                $("#layanan").html('<option value="0">Gagal memuat data (Sistem Error)</option>');
                ongkir = 0;
                hitungTotal();
            }
        });
    });

    // EVENT SAAT USER MENGGANTI PILIHAN LAYANAN
    $('#layanan').on('change', function() {
        ongkir = parseInt($(this).val()) || 0;
        hitungTotal();
    });
});

function hitungTotal() {
    let total = subtotal + ongkir;

    $("#ongkir").val(ongkir);
    $("#tampil_ongkir").text(`IDR ${ongkir.toLocaleString('id-ID')}`);
    $("#total").text(`IDR ${total.toLocaleString('id-ID')}`);
    $("#total_harga").val(total);
}
</script>

<style>
.border-bottom-dashed { border-bottom: 1px dashed #efefef; }
.fw-extrabold { font-weight: 800; }
.select2-container--bootstrap-5 .select2-selection { border-color: #dee2e6; height: calc(1.5em + 0.5rem + 2px); font-size: 0.875rem; }
</style>

<?= $this->endSection() ?>