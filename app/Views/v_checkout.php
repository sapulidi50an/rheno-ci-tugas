<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="row">
    <div class="col-lg-6">
        <!-- Vertical Form -->
        <?= form_open('transaksi/buy', 'class="row g-3"') ?>
        <?= form_hidden('username', session()->get('username')) ?>
        <?= form_input(['type' => 'hidden', 'name' => 'total_harga', 'id' => 'total_harga', 'value' => '']) ?>
        
        <div class="col-12">
            <label for="nama" class="form-label">Nama</label>
            <input type="text" class="form-control" id="nama" name="nama" value="<?php echo session()->get('username'); ?>">
        </div>
        
        <div class="col-12">
            <label for="alamat" class="form-label">Alamat</label>
            <input type="text" class="form-control" id="alamat" name="alamat" placeholder="Masukkan alamat lengkap" required>
        </div> 
        
        <div class="col-12">
            <label for="kelurahan" class="form-label">Kelurahan</label>
            <select class="form-control" id="kelurahan" name="kelurahan" style="width: 100%;" required>
                <option value="">Pilih nama kelurahan...</option>
            </select>
            <small class="text-muted">Ketik minimal 3 karakter untuk mencari kelurahan</small>
        </div>
        
        <div class="col-12">
            <label for="layanan" class="form-label">Layanan</label>
            <select class="form-control" id="layanan" name="layanan" style="width: 100%;" required>
                <option value="">Pilih layanan pengiriman...</option>
            </select>
            <small class="text-muted">Pilih kelurahan terlebih dahulu</small>
        </div>
        
        <div class="col-12">
            <label for="ongkir" class="form-label">Ongkir</label>
            <input type="text" class="form-control" id="ongkir" name="ongkir" value="0" readonly>
        </div>
        
        <div class="col-12">
            <button type="submit" class="btn btn-primary btn-lg w-100" id="btnSubmit">
                <i class="bi bi-cart-check"></i> Buat Pesanan
            </button>
            <button type="button" class="btn btn-secondary btn-lg w-100 mt-2" onclick="window.history.back()">
                <i class="bi bi-arrow-left"></i> Kembali ke Keranjang
            </button>
        </div>
        </form><!-- End Vertical Form -->
    </div>
    
    <div class="col-lg-6">
        <div class="col-12">
            <!-- Default Table -->
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Nama</th>
                        <th scope="col">Harga</th>
                        <th scope="col">Jumlah</th>
                        <th scope="col">Sub Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    if (!empty($items)) :
                        foreach ($items as $index => $item) :
                    ?>
                            <tr>
                                <td><?php echo $item['name'] ?></td>
                                <td><?php echo number_to_currency($item['price'], 'IDR') ?></td>
                                <td><?php echo $item['qty'] ?></td>
                                <td><?php echo number_to_currency($item['price'] * $item['qty'], 'IDR') ?></td>
                            </tr>
                    <?php
                        endforeach;
                    endif;
                    ?>
                    <tr>
                        <td colspan="2"></td>
                        <td><strong>Subtotal</strong></td>
                        <td><strong><?php echo number_to_currency($total, 'IDR') ?></strong></td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                        <td><strong>Total</strong></td>
                        <td><strong><span id="total"><?php echo number_to_currency($total, 'IDR') ?></span></strong></td>
                    </tr>
                </tbody>
            </table>
            <!-- End Default Table Example -->
        </div>
    </div>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
$(document).ready(function() {
    var ongkir = 0;
    var subtotal = <?= $total ?>;
    var total = subtotal; 
    
    hitungTotal();

    function hitungTotal() {
        total = ongkir + subtotal;
        $("#ongkir").val(formatRupiah(ongkir));
        $("#total").html(formatCurrency(total));
        $("#total_harga").val(total);
    }

    function formatRupiah(angka) {
        return "IDR " + angka.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
    }

    function formatCurrency(angka) {
        return "IDR " + angka.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
    }

    // Initialize Select2 untuk kelurahan
    $('#kelurahan').select2({
        placeholder: 'Ketik nama kelurahan...',
        allowClear: true,
        ajax: {
            url: '<?= base_url('get-location') ?>',
            dataType: 'json',
            delay: 500,
            data: function (params) {
                return {
                    search: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data.map(function(item) {
                        return {
                            id: item.id,
                            text: item.subdistrict_name + ", " + item.district_name + ", " + item.city_name + ", " + item.province_name + ", " + item.zip_code
                        };
                    })
                };
            },
            cache: true
        },
        minimumInputLength: 3
    });

    // Event handler ketika kelurahan berubah
    $("#kelurahan").on('change', function() {
        var id_kelurahan = $(this).val(); 
        
        // Reset layanan dan ongkir
        $("#layanan").empty().append('<option value="">Pilih layanan pengiriman...</option>');
        ongkir = 0;
        hitungTotal();

        if (id_kelurahan) {
            // Tampilkan loading
            $("#layanan").append('<option value="">Loading...</option>');
            
            $.ajax({
                url: "<?= site_url('get-cost') ?>",
                type: 'GET',
                data: { 
                    'destination': id_kelurahan, 
                },
                dataType: 'json',
                success: function(data) { 
                    $("#layanan").empty().append('<option value="">Pilih layanan pengiriman...</option>');
                    
                    if (data && data.length > 0) {
                        data.forEach(function(item) {
                            var text = item["description"] + " (" + item["service"] + ") : estimasi " + item["etd"] + " hari";
                            $("#layanan").append($('<option>', {
                                value: item["cost"],
                                text: text 
                            }));
                        });
                    } else {
                        $("#layanan").append('<option value="">Tidak ada layanan tersedia</option>');
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Error:', error);
                    $("#layanan").empty().append('<option value="">Error loading services</option>');
                }
            });
        }
    });

    // Event handler ketika layanan berubah
    $("#layanan").on('change', function() {
        ongkir = parseInt($(this).val()) || 0;
        hitungTotal();
    });

    // Validasi form sebelum submit
    $('form').on('submit', function(e) {
        let isValid = true;
        let errorMsg = '';

        // Reset previous error states
        $('.is-invalid').removeClass('is-invalid');

        if (!$('#alamat').val().trim()) {
            $('#alamat').addClass('is-invalid');
            errorMsg += 'Alamat harus diisi!\n';
            isValid = false;
        }

        if (!$('#kelurahan').val()) {
            $('#kelurahan').next('.select2-container').addClass('is-invalid');
            errorMsg += 'Kelurahan harus dipilih!\n';
            isValid = false;
        }

        if (!$('#layanan').val()) {
            $('#layanan').addClass('is-invalid');
            errorMsg += 'Layanan pengiriman harus dipilih!\n';
            isValid = false;
        }

        if (!isValid) {
            alert(errorMsg);
            e.preventDefault();
            return false;
        }

        // Disable submit button untuk mencegah double submit
        $('#btnSubmit').prop('disabled', true).html('<i class="bi bi-hourglass-split"></i> Memproses...');
        
        return true;
    });
});
</script>
<?= $this->endSection() ?>