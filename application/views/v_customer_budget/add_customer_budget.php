<!-- Card -->
<div class="card">
    <div class="card-header">
        <div class="row align-items-center mb-2">
            <div class="col-md-12 d-flex justify-content-between">
                <h2 class="mb-0"><?= $judul ?></h2>
                <div>
                    <button class="btn btn-sm btn-primary" onclick="loadform('<?= $load_back ?>')">
                        <i class="bi bi-arrow-left-circle"></i> Kembali
                    </button>
                    <a href="javascript:void(0)" class="btn btn-sm btn-outline-primary" onclick="loadform('<?= $load_refresh ?>')">
                        <i class="bi bi-arrow-clockwise"></i> Refresh
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <form id="form_customers_budget" data-parsley-validate>
            <div class="row">

                <!-- Customer -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="customer_id">Customer</label>
                        <select id="customer_id" name="customer_id" class="form-select" data-parsley-required="true" data-parsley-errors-container=".err_customer">
                            <option value="">Pilih Customer</option>
                            <!-- option diisi dari DB -->
                        </select>
                        <span class="text-danger err_customer"></span>
                    </div>
                </div>

                <!-- Project Name -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="project_name">Nama Proyek</label>
                        <input type="text" id="project_name" name="project_name" class="form-control" placeholder="Masukkan nama proyek" data-parsley-required="true" data-parsley-errors-container=".err_project_name">
                        <span class="text-danger err_project_name"></span>
                    </div>
                </div>

                <!-- Description -->
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label" for="description">Deskripsi</label>
                        <textarea id="description" name="description" class="form-control" rows="2" placeholder="Deskripsi singkat proyek" data-parsley-required="true" data-parsley-errors-container=".err_description"></textarea>
                        <span class="text-danger err_description"></span>
                    </div>
                </div>

                <!-- Budget -->
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label" for="total_budget">Total Budget</label>
                        <input readonly type="text" id="total_budget" name="total_budget" class="form-control bg-soft-secondary text-end" placeholder="0" data-parsley-required="true" data-parsley-errors-container=".err_total_budget">
                        <span class="text-danger err_total_budget"></span>
                    </div>
                </div>


                <!-- Start Timeline -->
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label" for="start_timeline">Mulai</label>
                        <div id="startTimelinePicker" class="js-flatpickr flatpickr-custom input-group" data-hs-flatpickr-options='{
                "dateFormat": "d/m/Y",
                "wrap": true
            }'>
                            <div class="input-group-prepend input-group-text" data-bs-toggle>
                                <i class="bi-calendar-week"></i>
                            </div>
                            <input type="text" name="start_timeline" class="flatpickr-custom-form-control form-control" placeholder="Pilih tanggal mulai" data-input data-parsley-required="true" data-parsley-errors-container=".err_start_timeline">
                        </div>
                        <span class="text-danger err_start_timeline"></span>
                    </div>
                </div>

                <!-- End Timeline -->
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label" for="end_timeline">Selesai</label>
                        <div id="endTimelinePicker" class="js-flatpickr flatpickr-custom input-group" data-hs-flatpickr-options='{
                "dateFormat": "d/m/Y",
                "wrap": true
            }'>
                            <div class="input-group-prepend input-group-text" data-bs-toggle>
                                <i class="bi-calendar-week"></i>
                            </div>
                            <input type="text" name="end_timeline" class="flatpickr-custom-form-control form-control" placeholder="Pilih tanggal selesai" data-input data-parsley-required="true" data-parsley-errors-container=".err_end_timeline">
                        </div>
                        <span class="text-danger err_end_timeline"></span>
                    </div>
                </div>

                <!-- Notes -->
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label" for="notes">Catatan</label>
                        <textarea id="notes" name="notes" class="form-control" rows="2" placeholder="Tambahkan catatan jika ada"></textarea>
                    </div>
                </div>

                <!-- Quotation Items -->
                <div class="col-md-12 mt-4">
                    <h5 class="fw-bold">Item Budget</h5>
                    <table class="table table-bordered align-middle" id="tableItems">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 10%;">Tipe</th>
                                <th>Nama Item</th>
                                <th style="width: 10%;">Qty</th>
                                <th style="width: 10%;">Satuan</th>
                                <th style="width: 15%;">Harga Satuan</th>
                                <th style="width: 15%;">Subtotal</th>
                                <th>Catatan</th>
                                <th style="width: 5%;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select name="item_type[]" class="form-select select2" data-parsley-required="true" data-parsley-errors-container=".err_item_type_0">
                                        <option value="">Pilih</option>
                                        <option value="product">Product</option>
                                        <option value="service">Service</option>
                                    </select>
                                    <span class="text-danger err_item_type_0"></span>
                                </td>
                                <td>
                                    <input type="text" name="item_name[]" class="form-control" placeholder="Nama item" data-parsley-required="true" data-parsley-errors-container=".err_item_name_0">
                                    <span class="text-danger err_item_name_0"></span>
                                </td>
                                <td>
                                    <input type="text" name="qty[]" class="form-control text-end qty" placeholder="0" data-parsley-required="true" data-parsley-errors-container=".err_qty_0">
                                    <span class="text-danger err_qty_0"></span>
                                </td>
                                <td>
                                    <select name="unit[]" class="form-select select2" data-parsley-required="true" data-parsley-errors-container=".err_unit_0">
                                        <option value="">Pilih</option>
                                        <option value="pcs">pcs</option>
                                        <option value="box">box</option>
                                        <option value="meter">meter</option>
                                        <option value="set">set</option>
                                        <option value="unit">unit</option>
                                    </select>
                                    <span class="text-danger err_unit_0"></span>
                                </td>
                                <td>
                                    <input disabled type="text" name="unit_price[]" class="form-control text-end unit_price bg-soft-secondary" placeholder="0" data-parsley-required="true" data-parsley-errors-container=".err_price_0">
                                    <span class="text-danger err_price_0"></span>
                                </td>
                                <td>
                                    <input type="text" name="subtotal[]" class="form-control text-end subtotal bg-light" readonly placeholder="0">
                                </td>
                                <td>
                                    <input type="text" name="notes[]" class="form-control" placeholder="Catatan optional">
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-danger btn-sm btn-delete-row">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <button type="button" id="btnAddRow" class="btn btn-sm btn-success mt-2">
                        <i class="bi bi-plus-circle"></i> Tambah Item
                    </button>
                </div>


                <!-- Submit -->
                <div class="col-md-12 d-flex justify-content-end mt-4">
                    <div>
                        <button type="button" id="btnsubmit" class="btn btn-sm btn-primary">
                            <i class="bi bi-send"></i> Simpan
                        </button>
                        <button type="reset" class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-eraser-fill"></i> Reset
                        </button>
                    </div>
                </div>

            </div>
        </form>
    </div>

</div>
<!-- JS for Add/Delete Rows -->
<script>
    (function() {
        // INITIALIZATION OF FLATPICKR
        // =======================================================
        HSCore.components.HSFlatpickr.init('.js-flatpickr')
    })();


    $('.select2').select2({
        placeholder: 'Pilih',
        allowClear: true,
        width: '100%'
    });
</script>


<script>
    $(document).ready(function() {

        // Format angka ribuan (versi Indonesia)
        function formatNumber(num) {
            if (!num) return '0';
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        // Hapus titik buat hitung angka
        function unformatNumber(num) {
            return parseFloat(num.replace(/\./g, '')) || 0;
        }

        // Recalculate subtotal tiap baris & total keseluruhan
        function recalcTotals() {
            let totalBudget = 0;

            $('#tableItems tbody tr').each(function() {
                let qty = unformatNumber($(this).find('.qty').val());
                let price = unformatNumber($(this).find('.unit_price').val());
                let subtotal = qty * price;

                $(this).find('.subtotal').val(formatNumber(subtotal));
                totalBudget += subtotal;
            });

            $('#total_budget').val(formatNumber(totalBudget));
        }

        // Handler untuk input Qty
        $(document).on('input', '.qty', function() {
            let val = $(this).val().replace(/[^0-9]/g, '');
            $(this).val(formatNumber(val));

            let qty = unformatNumber($(this).val());
            let row = $(this).closest('tr');
            let priceInput = row.find('.unit_price');

            // Qty kosong atau 0 → disable harga
            if (qty <= 0) {
                priceInput.val('').prop('disabled', true);
                priceInput.addClass('bg-soft-secondary');
                row.find('.subtotal').val('0');
            } else {
                priceInput.prop('disabled', false);
                priceInput.removeClass('bg-soft-secondary');

            }

            recalcTotals();
        });

        // Handler untuk input Harga Satuan
        $(document).on('input', '.unit_price', function() {
            let row = $(this).closest('tr');
            let qty = unformatNumber(row.find('.qty').val());
            let val = $(this).val().replace(/[^0-9]/g, '');
            $(this).val(formatNumber(val));

            // Cegah input harga kalau qty belum ada
            if (qty <= 0) {
                $(this).val('');
                swet_gagal('Isi Qty dulu sebelum memasukkan harga!');

                return;
            }

            recalcTotals();
        });

        // Tambah baris baru
        $('#btnAddRow').on('click', function() {
            let row = $('#tableItems tbody tr:first').clone();

            // reset value dan disable harga
            row.find('input').val('');
            row.find('select').val('');
            row.find('.unit_price').prop('disabled', true);
            $('#tableItems tbody').append(row);
        });

        // Hapus baris
        $(document).on('click', '.btn-delete-row', function() {
            if ($('#tableItems tbody tr').length > 1) {
                $(this).closest('tr').remove();
                recalcTotals();
            } else {
                swet_gagal('Minimal 1 item harus ada.');

            }
        });

        // Format otomatis huruf besar di awal nama
        $('#project_name').on('keyup', function() {
            let val = $(this).val();
            $(this).val(val.replace(/\b\w/g, char => char.toUpperCase()));
        });

        // Submit handler
        $('#btnsubmit').click(function(e) {
            e.preventDefault();
            let form = $('#form_customers_budget');
            form.parsley().validate();

            if (form.parsley().isValid()) {
                $.ajax({
                    url: "<?= base_url('C_customers_budget/simpandata') ?>",
                    type: 'POST',
                    dataType: 'JSON',
                    data: form.serialize(),
                    beforeSend: function() {
                        showLoader();
                    },
                    success: function(data) {
                        if (data.hasil === 'true') {
                            swet_sukses(data.pesan);
                            loadform('<?= $load_back ?>');
                        } else {
                            swet_gagal(data.pesan);
                            hideLoader();
                        }
                    },
                    error: function(xhr) {
                        console.error("ERROR:", xhr);
                        swet_gagal("Terjadi kesalahan server (" + xhr.status + ")");
                        hideLoader();
                    }
                });
            }
        });
    });
</script>