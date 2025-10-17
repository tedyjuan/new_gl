<!-- Card -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
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

    <div class="card-body">
        <form id="form_update_status" enctype="multipart/form-data" data-parsley-validate>
            <input type="hidden" name="uuid" id="uuid" value="<?= $data->uuid ?? '' ?>">

            <div class="row">
                <!-- Current Info -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Customer</label>
                        <p class="form-control-plaintext"><?= $data->customer_name ?? '-' ?></p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Proyek</label>
                        <p class="form-control-plaintext"><?= $data->project_name ?? '-' ?></p>
                    </div>
                </div>

                <!-- Status -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="status" class="form-label">Status Proposal <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-select select2" data-parsley-required="true" data-parsley-errors-container=".err_status">
                            <option value="">Pilih Status</option>
                            <option value="approved" <?= $data->status == 'approved' ? 'selected' : '' ?>>Disetujui</option>
                            <option value="rejected" <?= $data->status == 'rejected' ? 'selected' : '' ?>>Ditolak</option>
                        </select>
                        <span class="text-danger err_status"></span>
                    </div>
                </div>

                <!-- File Upload -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="path_archive">
                            Upload File Proposal / PDF <span class="text-danger">*</span>
                        </label>

                        <input type="file" id="path_archive" name="file_archive" class="form-control" accept=".pdf,.jpg,.jpeg,.png" data-parsley-required="true" data-parsley-filemaxmegabytes="10" data-parsley-filemimetypes="application/pdf,image/jpeg,image/png" data-parsley-errors-container=".err_path_archive">

                        <span class="text-danger err_path_archive"></span>

                        <?php if (!empty($data->path_archive) && file_exists(FCPATH . $data->path_archive)) : ?>
                            <div class="mt-2">
                                <a href="<?= base_url($data->path_archive) ?>" target="_blank" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-file-earmark-arrow-down"></i> Lihat / Download File Saat Ini
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>


                <!-- Notes -->
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label" for="status_notes">Catatan / Alasan <span class="text-danger">*</span></label>
                        <textarea id="status_notes" name="status_notes" class="form-control" rows="3" placeholder="Tambahkan catatan atau alasan perubahan status" data-parsley-required="true" data-parsley-errors-container=".err_status_notes"><?= $data->status_notes ?></textarea>
                        <span class="text-danger err_status_notes"></span>
                    </div>
                </div>

                <!-- Submit -->
                <div class="col-md-12 d-flex justify-content-end mt-4">
                    <div>
                        <button type="button" id="btnUpdateStatus" class="btn btn-sm btn-primary">
                            <i class="bi bi-save"></i> Update Status
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

<script>
    $(document).ready(function() {


        $(document).ready(function() {
            // Function untuk handle tampil/sembunyi field file
            function toggleFileField() {
                let status = $('#status').val();
                let fileField = $('#path_archive');
                let fileGroup = $('#fileArchiveGroup');

                if (status === 'approved') {
                    // Kalau disetujui → tampil dan required
                    fileGroup.show();
                    fileField.attr('data-parsley-required', 'true');
                } else {
                    // Kalau ditolak → sembunyi dan gak required
                    fileGroup.hide();
                    fileField.removeAttr('data-parsley-required');
                    fileField.val(''); // clear isi biar aman
                }
            }

            // Initial load (biar pas edit langsung sesuai kondisi)
            toggleFileField();

            // Kalau dropdown berubah → update kondisi
            $('#status').change(toggleFileField);
        });


        $('.select2').select2({
            placeholder: 'Pilih Status',
            allowClear: true,
            width: '100%'
        });


        $('#btnUpdateStatus').click(function(e) {
            e.preventDefault();
            let form = $('#form_update_status');
            form.parsley().validate();

            if (form.parsley().isValid()) {
                let formData = new FormData(form[0]); // <--- ganti serialize() jadi FormData()

                $.ajax({
                    url: "<?= base_url('C_customers_budget/updatedata_status') ?>",
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    processData: false, // <--- WAJIB (biar FormData gak diubah ke query string)
                    contentType: false, // <--- WAJIB (biar header form-data jalan)
                    beforeSend: showLoader,
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
                        swet_gagal("Terjadi kesalahan server (" + xhr.status + ")");
                        hideLoader();
                    }
                });
            }
        });

    });
</script>