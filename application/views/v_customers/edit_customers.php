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
        <form id="form_customer">
            <input type="hidden" name="uuid" value="<?= $data->uuid ?>">

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="name">Nama Customer</label>
                        <input type="text" id="name" name="name" class="form-control form-control-hover-light" placeholder="Masukkan nama customer" value="<?= htmlspecialchars($data->name) ?>" data-parsley-required="true" data-parsley-errors-container=".err_name">
                        <span class="text-danger err_name"></span>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="phone">Nomor Telepon</label>
                        <input type="text" id="phone" name="phone" class="form-control form-control-hover-light" placeholder="Masukkan nomor telepon" value="<?= htmlspecialchars($data->phone) ?>" data-parsley-pattern="^[0-9+ ]+$" data-parsley-minlength="8" data-parsley-errors-container=".err_phone">
                        <span class="text-danger err_phone"></span>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-control form-control-hover-light" placeholder="Masukkan email customer" value="<?= htmlspecialchars($data->email) ?>" data-parsley-type="email" data-parsley-errors-container=".err_email">
                        <span class="text-danger err_email"></span>
                    </div>
                </div>


                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="status">Status</label>
                        <select id="status" name="status" class="form-select select2" data-parsley-required="true" data-parsley-errors-container=".err_status">
                            <option value="">Pilih Status</option>
                            <option value="active" <?= $data->status == 'active' ? 'selected' : '' ?>>Aktif</option>
                            <option value="inactive" <?= $data->status == 'inactive' ? 'selected' : '' ?>>Nonaktif</option>
                        </select>
                        <span class="text-danger err_status"></span>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label" for="address">Alamat</label>
                        <textarea id="address" name="address" class="form-control form-control-hover-light" placeholder="Masukkan alamat customer" rows="2" data-parsley-errors-container=".err_address"><?= htmlspecialchars($data->address) ?></textarea>
                        <span class="text-danger err_address"></span>
                    </div>
                </div>


            </div>

            <div class="col-md-12 d-flex justify-content-end">
                <div>
                    <button type="button" id="btnsubmit" class="btn btn-sm btn-primary">
                        <i class="bi bi-send"></i> Simpan
                    </button>
                    <button type="reset" class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-eraser-fill"></i> Reset
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    $(document).ready(function() {
        const btnSubmit = $('#btnsubmit');


        $('.select2').select2({
            placeholder: 'Pilih Status',
            allowClear: true,
            width: '100%'
        });


        function checkFormValidity() {
            // Kalau masih ada error text yang tampil → disable tombol
            if ($('.text-danger').filter(function() {
                    return $(this).text() !== '';
                }).length > 0) {
                btnSubmit.prop('disabled', true);
            } else {
                btnSubmit.prop('disabled', false);
            }
        }

        // === VALIDASI NOMOR TELEPON ===
        $('#phone').on('keyup blur', function() {
            const phone = $(this).val().trim();
            const phoneRegex = /^\+?[0-9]{9,15}$/;
            const errorEl = $('.err_phone');

            if (phone === '') {
                errorEl.text('');
            } else if (!phoneRegex.test(phone)) {
                errorEl.text('Nomor telepon harus 9–15 digit dan boleh diawali tanda +');
            } else {
                errorEl.text('');
            }

            checkFormValidity();
        });

        // === VALIDASI EMAIL ===
        $('#email').on('keyup blur', function() {
            const email = $(this).val().trim();
            const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            const errorEl = $('.err_email');

            if (email === '') {
                errorEl.text('');
            } else if (!emailRegex.test(email)) {
                errorEl.text('Format email tidak valid');
            } else {
                errorEl.text('');
            }

            checkFormValidity();
        });

        // Inisialisasi: pastikan tombol aktif diawal
        checkFormValidity();
    });
</script>
<script>
    $(document).ready(function() {


        // Submit update
        $('#btnsubmit').click(function(e) {
            e.preventDefault();
            let form = $('#form_customer');
            form.parsley().validate();

            if (form.parsley().isValid()) {
                $.ajax({
                    url: "<?= base_url('C_customers/updatedata') ?>",
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



        checkFormValidity();
    });
</script>