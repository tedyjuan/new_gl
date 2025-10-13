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

<!-- STATUS + FILE SECTION (flex container) -->
<div class="d-flex justify-content-between align-items-start mb-4 flex-wrap">
  
  <!-- STATUS -->
  <div>
    <h5 class="fw-bold mb-2">Status Proyek</h5>
    <?php 
      $status = $data->status ?? 'pending';
      $statusClass = [
        'approved' => 'success',
        'rejected' => 'danger',
        'pending'  => 'warning',
      ][$status] ?? 'secondary';
    ?>
    <span class="badge bg-<?= $statusClass ?> px-3 py-2 text-uppercase">
      <?= ucfirst($status) ?>
    </span>

    <?php if (!empty($data->status_notes)) : ?>
      <div class="mt-2 text-muted">
        <strong>Catatan:</strong> <?= htmlspecialchars($data->status_notes) ?>
      </div>
    <?php endif; ?>

    <?php if (!empty($data->status_at)) : ?>
      <div class="text-muted">
        <small>Terakhir diupdate: <?= date('d/m/Y H:i:s', strtotime($data->status_at)) ?></small>
      </div>
    <?php endif; ?>
  </div>

  <!-- FILE LINKS -->
  <div class="text-end">
    <?php if (!empty($data->path_pdf)) : ?>
      <a href="<?= base_url($data->path_pdf) ?>" target="_blank" 
         class="btn btn-outline-secondary btn-sm mb-2">
        <i class="bi bi-file-earmark-pdf"></i> Lihat Proposal PDF
      </a><br>
    <?php endif; ?>

    <?php if (!empty($data->path_archive)) : ?>
      <a href="<?= base_url($data->path_archive) ?>" target="_blank" 
         class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-archive"></i> Lihat Arsip TTD
      </a>
    <?php endif; ?>
  </div>
</div>

<!-- FORM VIEW ONLY -->
<form id="form_customers_budget_view">
          <div class="row">

              <!-- Customer -->
              <div class="col-md-6 mb-3">
                  <label class="form-label">Customer</label>
                  <input type="text" class="form-control bg-light" value="<?= $data->customer_id . ' - ' . $data->customer_name ?>" readonly>
              </div>

              <!-- Project Name -->
              <div class="col-md-6 mb-3">
                  <label class="form-label">Nama Proyek</label>
                  <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($data->project_name) ?>" readonly>
              </div>

              <!-- Description -->
              <div class="col-md-12 mb-3">
                  <label class="form-label">Deskripsi</label>
                  <textarea class="form-control bg-light" rows="2" readonly><?= htmlspecialchars($data->description) ?></textarea>
              </div>

              <!-- Budget -->
              <div class="col-md-4 mb-3">
                  <label class="form-label">Total Budget</label>
                  <input type="text" class="form-control bg-light text-end" value="<?= number_format($data->total_budget, 0, ',', '.') ?>" readonly>
              </div>

              <!-- Timeline -->
              <div class="col-md-4 mb-3">
                  <label class="form-label">Mulai</label>
                  <input type="text" class="form-control bg-light" value="<?= date('d/m/Y', strtotime($data->start_timeline)) ?>" readonly>
              </div>

              <div class="col-md-4 mb-3">
                  <label class="form-label">Selesai</label>
                  <input type="text" class="form-control bg-light" value="<?= date('d/m/Y', strtotime($data->end_timeline)) ?>" readonly>
              </div>

            

              <!-- Notes -->
              <div class="col-md-12 mb-3">
                  <label class="form-label">Catatan</label>
                  <textarea class="form-control bg-light" rows="2" readonly><?= htmlspecialchars($data->notes ?? '-') ?></textarea>
              </div>

              <!-- Item Budget -->
              <div class="col-md-12 mt-4">
                  <h5 class="fw-bold">Item Budget</h5>
                  <table class="table table-bordered align-middle mt-2">
                      <thead class="table-light">
                          <tr>
                              <th>Tipe</th>
                              <th>Nama Item</th>
                              <th class="text-end">Qty</th>
                              <th>Satuan</th>
                              <th class="text-end">Harga Satuan</th>
                              <th class="text-end">Subtotal</th>
                              <th>Catatan</th>
                          </tr>
                      </thead>
                      <tbody>
                          <?php if (!empty($items)) : ?>
                              <?php foreach ($items as $item) : ?>
                                  <tr>
                                      <td><?= ucfirst($item->item_type) ?></td>
                                      <td><?= htmlspecialchars($item->item_name) ?></td>
                                      <td class="text-end"><?= number_format($item->qty, 0, ',', '.') ?></td>
                                      <td><?= htmlspecialchars($item->unit) ?></td>
                                      <td class="text-end"><?= number_format($item->unit_price, 0, ',', '.') ?></td>
                                      <td class="text-end"><?= number_format($item->qty * $item->unit_price, 0, ',', '.') ?></td>
                                      <td><?= htmlspecialchars($item->notes_item ?? '-') ?></td>
                                  </tr>
                              <?php endforeach; ?>
                          <?php else : ?>
                              <tr><td colspan="7" class="text-center text-muted">Belum ada item budget</td></tr>
                          <?php endif; ?>
                      </tbody>
                  </table>
              </div>
          </div>
      </form>
  </div>
</div>
</div>




<script>
    (function() {
        // INITIALIZATION OF FLATPICKR
        HSCore.components.HSFlatpickr.init('.js-flatpickr');
    })();

    // Init Select2 Global
    function initSelect2(target) {
        target.find('.select2').select2({
            placeholder: 'Pilih',
            allowClear: true,
            width: '100%'
        });
    }

    $(document).ready(function() {
        // panggil init awal
        initSelect2($(document));

        // Format angka ribuan (versi Indonesia)
        function formatNumber(num) {
            if (!num) return '0';
            num = num.toString().replace(/^0+/, ''); // hapus nol di depan
            if (num === '') num = '0';
            return num.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        // Hapus titik buat hitung angka
        function unformatNumber(num) {
            num = num.replace(/\./g, ''); // hilangkan titik ribuan
            num = num.replace(/^0+/, ''); // hapus nol di depan
            return parseFloat(num) || 0;
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

            if (qty <= 0) {
                priceInput.val('').prop('disabled', true).addClass('bg-soft-secondary');
                row.find('.subtotal').val('0');
            } else {
                priceInput.prop('disabled', false).removeClass('bg-soft-secondary');
            }

            recalcTotals();
        });

        // Handler untuk input Harga Satuan
        $(document).on('input', '.unit_price', function() {
            let row = $(this).closest('tr');
            let qty = unformatNumber(row.find('.qty').val());
            let val = $(this).val().replace(/[^0-9]/g, '');
            $(this).val(formatNumber(val));

            if (qty <= 0) {
                $(this).val('');
                swet_gagal('Isi Qty dulu sebelum memasukkan harga!');
                return;
            }

            recalcTotals();
        });

        // Tambah baris baru
        $('#btnAddRow').on('click', function() {
            let lastRow = $('#tableItems tbody tr:last');

            // Hapus select2 instance dari row terakhir (biar yang ke-clone clean)
            lastRow.find('.select2').each(function() {
                if ($(this).hasClass('select2-hidden-accessible')) {
                    $(this).select2('destroy');
                }
            });

            // Clone row terakhir
            let newRow = lastRow.clone();

            // Reset semua value input di row baru
            newRow.find('input').val('');
            newRow.find('select').val('').trigger('change');
            newRow.find('.unit_price').prop('disabled', true).addClass('bg-soft-secondary');
            newRow.find('.subtotal').val('0');

            // Append row baru ke tbody
            $('#tableItems tbody').append(newRow);

            // Reinit select2 di row lama + row baru
            initSelect2(lastRow);
            initSelect2(newRow);
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

        // Customer select2 AJAX
        $("#customer_id").select2({
            placeholder: 'Nama Customer Or ID Customer',
            minimumInputLength: 1,
            allowClear: true,
            ajax: {
                url: "<?= base_url('C_customers/search') ?>",
                dataType: "json",
                delay: 250,
                data: function(params) {
                    return {
                        getCustomers: params.term
                    };
                },
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                id: item.customer_id,
                                text: item.customer_id + ' - ' + item.name
                            };
                        })
                    };
                }
            }
        });

        // Auto kapital nama proyek
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
                // Nambah revisi
                let currentRev = parseInt($('#revised_version').val() || 0);
                $('#revised_version').val(currentRev + 1);

                // Minta alasan revisi
                Swal.fire({
                    title: "Alasan Revisi",
                    input: "textarea",
                    inputPlaceholder: "Tuliskan alasan revisi di sini...",
                    showCancelButton: true,
                    confirmButtonText: "Kirim",
                    cancelButtonText: "Batal",
                }).then((result) => {
                    if (result.isConfirmed && result.value.trim() !== "") {
                        $('#revised_notes').val(result.value);

                        $.ajax({
                            url: "<?= base_url('C_customers_budget/updatedata') ?>",
                            type: 'POST',
                            dataType: 'JSON',
                            data: form.serialize(),
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
                    } else {
                        Swal.fire({
                            icon: "info",
                            title: "Dibatalkan",
                            text: "Update dibatalkan karena tidak ada alasan revisi."
                        });
                    }
                });
            }
        });

    });
</script>