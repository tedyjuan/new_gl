<!-- Card -->
<style>
	input[type="search"].form-control {
		margin-bottom: 10px;
		/* Menambahkan margin bawah 10px */
	}
</style>
<div class="card">
	<div class="card-header">
		<div class="row align-items-center mb-2">
			<div class="col-md-12 d-flex justify-content-between">
				<h2 class="mb-0"><?= $judul ?></h2>
				<div class="div ">
					<button class="btn btn-sm btn-primary" onclick="loadform('<?= $load_grid ?>')"><i
							class="bi bi-arrow-left-circle"></i> Kembali</button>
					<a href="javascript:void(0)" class="btn btn-sm btn-outline-primary" onclick="loadform('<?= $load_refresh ?>')">
						<i class="bi bi-arrow-clockwise"></i> Refresh
					</a>
				</div>
			</div>
		</div>
	</div>
	<div class="card-body">
		<form id="forms_add">
			<div class="row">
				<div class="col-6">
					<h2>Informasi Header</h2>
					<table class="table table-bordered">
						<tbody>
							<tr>
								<th style="width: 35%">ID Akun Header</th>
								<td><?= $data->account_number; ?></td>
							</tr>
							<tr>
								<th style="width: 35%">Nama</th>
								<td><?= $data->name_coa; ?></td>
							</tr>
							<tr>
								<th style="width: 35%">Company </th>
								<td><?= $data->code_company; ?> - <?= $data->name_company; ?> </td>
							</tr>
							<tr>
								<th style="width: 35%">Debit / Credit</th>
								<td><?= $data->account_method ?></td>
							</tr>
							<tr>
								<th style="width: 35%">Tipe Account</th>
								<td><?= $data->account_type  ?></td>
							</tr>
							<tr>
								<th style="width: 35%">COA Group</th>
								<td><?= $data->account_group  ?></td>
							</tr>
							<tr>
								<th style="width: 35%">Tbag 1</th>
								<td><?= $data->code_trialbalance1 ?> - <?= $data->tbag1 ?></td>
							</tr>
							<tr>
								<th style="width: 35%">Tbag 2</th>
								<td><?= $data->code_trialbalance2 ?> - <?= $data->tbag2 ?> </td>
							</tr>
							<tr>
								<th style="width: 35%">Tbag 3</th>
								<td><?= $data->code_trialbalance3 ?> - <?= $data->tbag3 ?></td>
							</tr>
							<tr>
								<th style="width: 35%">Deskripsi</th>
								<td> <?= $data->des ?></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="col-6 mt-1">
					<div class="mb-3">
						<label class="form-label" for="id_number_ledger">ID Akun Ledger</label>
						<div class="input-group mb-3">
							<span class="input-group-text" id="id-number-ledger"><?= $data->account_number; ?></span>
							<input type="text" class="form-control" id="id_number_ledger" name="id_number_ledger" style="width: 80%;"
								data-parsley-required="true" data-parsley-errors-container=".err_id_akun_ledger" required=""
								placeholder="input 2 number" aria-describedby="id-number-ledger">
							<span class="text-danger err_id_akun_ledger"></span>
						</div>
					</div>
					<div class="mb-3">
						<label class="form-label" for="nama_akun_ledger">Nama Ledger</label>
						<input type="text" id="nama_akun_ledger" name="nama_akun_ledger" data-parsley-required="true"
							data-parsley-errors-container=".err_nama_akun_ledger" required=""
							class="form-control-hover-light form-control kapital"
							placeholder="input nama akun ledger">
						<span class="text-danger err_nama_akun_ledger"></span>
					</div>
					<div class="mb-3">
						<label class="form-label" for="type_costcenter">Type Cost Center</label>
						<div class="row">
							<div class="col-sm mb-2 mb-sm-0">
								<label class="form-control" for="formControlRadioEg1">
									<span class="form-check">
										<input type="radio" class="form-check-input" onclick="showtabel('depo')" name="formControlRadioEg" id="formControlRadioEg1">
										<span class="form-check-label">Depo</span>
									</span>
								</label>
							</div>
							<div class="col-sm mb-2 mb-sm-0">
								<label class="form-control" for="formControlRadioEg2">
									<span class="form-check">
										<input type="radio" class="form-check-input" onclick="showtabel('satuan')" name="formControlRadioEg" id="formControlRadioEg2">
										<span class="form-check-label">Satuan</span>
									</span>
								</label>
							</div>
						</div>
					</div>
					<table class="table table-sm  table-bordered" id="resulttabel_satuan" style="width: 100%; display: none;">
						<thead>
							<tr class="table-primary">
								<th style="width: 50%">Start CC Satuan</th>
								<th style="width: 50%">End CC Satuan</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td style="width: 50%">
									<input type="text" id="cc_start" onclick="tabeldata('<?= $data->code_company ?>', 'cc_satuan_start')"
										name="cc_start" data-parsley-required="true" required="" readonly
										data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"
										data-parsley-errors-container=".err_cc_start" class="form-control-hover-light form-control" placeholder="-">
									<span class="text-danger err_cc_start"></span>
								</td>
								<td style="width: 50%">
									<input type="text" id="cc_end" onclick="tabeldata('<?= $data->code_company ?>', 'cc_satuan_end')"
										name="cc_end" data-parsley-required="true" required="" readonly
										data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"
										data-parsley-errors-container=".err_cc_end" class="form-control-hover-light form-control" placeholder="-">
									<span class="text-danger err_cc_end"></span>
								</td>
							</tr>
						</tbody>
					</table>
					<table class="table table-sm  table-bordered" id="resulttabel_depo" style="width: 100%; display: none;">
						<thead>
							<tr class="table-primary">
								<th style="width: 100%">Cost Center Depo</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td style="width: 50%">
									<input type="text" id="cc_depo" onclick="tabeldata('<?= $data->code_company ?>', 'cc_depo')"
										name="cc_depo" data-parsley-required="true" required="" readonly
										data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"
										data-parsley-errors-container=".err_cc_depo" class="form-control-hover-light form-control" placeholder="-">
									<span class="text-danger err_cc_depo"></span>
								</td>
							</tr>
						</tbody>
					</table>
					<div class="mb-3">
						<label class="form-label" for="deskripsi">Deskripsi</label>
						<input type="text" id="deskripsi" name="deskripsi" data-parsley-required="true"
							data-parsley-errors-container=".err_deskripsi" required=""
							class="form-control-hover-light form-control" placeholder="-">
						<span class="text-danger err_deskripsi"></span>
					</div>
				</div>
			</div>
			<div class="col-md-12 d-flex justify-content-end">
				<div></div>
				<div>
					<button type="button" id="btnsubmit" class="btn btn-sm btn-primary"><i class="bi bi-send"></i>
						Simpan</button>
					<button type="reset" class="btn btn-sm btn-outline-danger"><i class="bi bi-eraser-fill"></i>
						Reset</button>
				</div>
			</div>
		</form>
	</div>
</div>
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
	<div class="offcanvas-header">
		<h5 id="offcanvasRightLabel">Type Cost Center</h5>
		<button type="button" class="btn-close" data-bs-dismiss="offcanvas" id="canvas_close" aria-label="Close"></button>
	</div>
	<div class="offcanvas-body" style=" padding-top: 0; padding-right: 10px;  padding-left: 10px;">
		<table class="table table-sm table-striped table-hover table-bordered" id="mytable" style="width: 100%;">
			<thead>
				<tr class="table-primary">
					<th style="width: 5%">Pilih</th>
					<th style="width: 95%">Cost Center</th>
				</tr>
			</thead>
		</table>
	</div>
</div>
<script>
	$(document).ready(function() {
		$(".select2").select2();
		$('#id_number_ledger').mask('00');
		$('#id_number_ledger').on('blur', function() {
			let inputValue = $(this).val();
			if (inputValue.length === 1) {
				inputValue = inputValue + '0';
			}
			if (inputValue == '00') {
				$(this).val('01');
			} else {
				$(this).val(inputValue);
			}
		});

		$('.kapital').on('input', function(e) {
			this.value = this.value.replace(/[^a-zA-Z0-9 /-]/g, '').toUpperCase();
		});

	})
</script>
<script>
	$('#perusahaan').on('change', function() {
		var companyCode = $(this).val();
		if (companyCode == '') {
			$('#akun_type').empty().append('<option value="">Pilih company dahulu</option>');
			$('#tbag1').empty().append('<option value="">Pilih Type dahulu</option>');
			$('#tbag2').empty().append('<option value="">Pilih group 1 dahulu</option>');
			$('#tbag3').empty().append('<option value="">Pilih group 2 dahulu</option>');
		} else {
			$('#akun_type').empty().append('<option value="">Pilih</option>');
		}
		if (companyCode) {
			$.ajax({
				url: '<?= base_url('C_chart_of_account/get_tbag1'); ?>',
				method: 'POST',
				dataType: 'JSON',
				data: {
					code_company: companyCode,
				},
				success: function(data) {
					data.forEach(function(coa) {
						$('#akun_type').append('<option value="' + coa.account_type + '" data-company="' + companyCode + '" >' + coa.account_type + '</option>');
					});
				}
			});

		}
	});
	$('#akun_type').on('change', function() {
		var akun_type = $(this).val();
		if (akun_type == '') {
			$('#tbag1').empty().append('<option value="">Pilih Type dahulu</option>');
			$('#tbag2').empty().append('<option value="">Pilih group 1 dahulu</option>');
			$('#tbag3').empty().append('<option value="">Pilih group 2 dahulu</option>');
		} else {
			$('#tbag1').empty().append('<option value="">Pilih</option>');
		}
		var company = $('#akun_type option:selected').data('company');
		if (akun_type) {
			$.ajax({
				url: '<?= base_url('C_chart_of_account/get_tbag1'); ?>',
				method: 'POST',
				dataType: 'JSON',
				data: {
					akun_type: akun_type,
					code_company: company,
				},
				success: function(data) {
					data.forEach(function(coa) {
						$('#tbag1').append('<option value="' + coa.code_trialbalance1 + '" data-company="' + company + '" >' + '(' + coa.code_trialbalance1 + ') ' + coa.description + '</option>');
					});
				}
			});

		}
	});
	$('#tbag1').on('change', function() {
		var val_tbag1 = $(this).val();
		if (val_tbag1 == '') {
			$('#tbag2').empty().append('<option value="">Pilih group 1 dahulu</option>');
			$('#tbag3').empty().append('<option value="">Pilih group 2 dahulu</option>');
		} else {
			$('#tbag2').empty().append('<option value="">Pilih</option>');
		}
		var company = $('#tbag1 option:selected').data('company');
		if (val_tbag1) {
			$.ajax({
				url: '<?= base_url('C_chart_of_account/get_tbag2'); ?>',
				method: 'POST',
				dataType: 'JSON',
				data: {
					tbag1: val_tbag1,
					code_company: company,
				},
				success: function(data) {
					data.forEach(function(tbg2) {
						$('#tbag2').append('<option value="' + tbg2.code_trialbalance2 + '" data-company="' + company + '" >' + '(' + tbg2.code_trialbalance2 + ') ' + tbg2.description + '</option>');
					});
				}
			});

		}
	});
	$('#tbag2').on('change', function() {
		var val_tbag2 = $(this).val();
		if (val_tbag2 == '') {
			$('#tbag3').empty().append('<option value="">Pilih group 2 dahulu</option>');
		} else {
			$('#tbag3').empty().append('<option value="">Pilih</option>');
		}
		if (val_tbag2) {
			var company = $('#tbag2 option:selected').data('company');
			$.ajax({
				url: '<?= base_url('C_chart_of_account/get_tbag3'); ?>',
				method: 'POST',
				dataType: 'JSON',
				data: {
					tbag2: val_tbag2,
					code_company: company,
				},
				success: function(data) {
					data.forEach(function(tbg3) {
						$('#tbag3').append('<option value="' + tbg3.code_trialbalance3 + '" data-company="' + company + '" >' + '(' + tbg3.code_trialbalance3 + ') ' + tbg3.description + '</option>');
					});
				}
			});

		}
	});
</script>
<script>
	$('#btnsubmit').click(function(e) {
		e.preventDefault();
		let form = $('#forms_add');
		var uuid = "<?= ($uuid) ?>";
		form.parsley().validate();
		console.log("test");

		if (form.parsley().isValid()) {
			console.log("ok");

			$.ajax({
				url: "<?= base_url('C_chart_of_account/simpanLedger') ?>",
				type: 'POST',
				method: 'POST',
				dataType: 'JSON',
				data: form.serialize() + '&uuid=' + uuid,
				beforeSend: function() {
					showLoader();
				},
				success: function(data) {
					if (data.hasil == 'true') {
						swet_sukses(data.pesan);
						loadform('<?= $load_grid ?>');
					} else {
						swet_gagal(data.pesan);
						hideLoader();
					}
				},
				error: function(xhr) {
					console.log("ERROR:", xhr);
					if (xhr.status === 422) {
						let errors = xhr.responseJSON.errors;
						$.each(errors, function(key, value) {
							$(`.err_${key}`).html(value[0]);
						});
					} else {
						swet_gagal("Terjadi kesalahan server (" + xhr.status + ")");
					}
				},
			});
		} else {
			console.log("tidak ok");
		}
	});


	function tabeldata(data_company, type) {
		// kalau sebelumnya sudah ada instance, hancurkan dulu
		if (window.mytableDT && $.fn.dataTable.isDataTable('#mytable')) {
			window.mytableDT.clear().destroy();
			window.mytableDT = null;
		}
		var url = '';
		if (type === 'cc_depo') {
			url = "<?= base_url('C_chart_of_account/griddata_depo') ?>"; // Ganti dengan URL untuk depo
		} else {
			url = "<?= base_url('C_chart_of_account/griddata_cc') ?>"; // Ganti dengan URL untuk cost center
		}
		window.mytableDT = $('#mytable').DataTable({
			processing: true,
			serverSide: true,
			destroy: true,
			retrieve: true,
			pageLength: 100,
			info: false,
			lengthChange: false,
			ajax: {
				url: url,
				type: "POST",
				data: {
					company_id: data_company,
				},
			},
			columnDefs: [{
					orderable: false,
					targets: 0
				},
				{
					orderable: true,
					targets: 1
				}
			],
		});
	}

	function pilihdepo(param) {
		$("#canvas_close").trigger('click');
		$('#forms_add').parsley().reset();
		$('#cc_depo').val(param);
	}

	function pilihsatuan(param) {
		$("#canvas_close").trigger('click');
		$('#forms_add').parsley().reset();
		var statr = $('#cc_start').val();
		var end = $('#cc_end').val();
		if (statr == '') {
			$('#cc_start').val(param);
		} else {
			if (statr != '' && end != '') {
				$('#cc_start').val(param);
				$('#cc_end').val('');
			} else {
				$('#cc_end').val(param);
			}
		}
	}


	function showtabel(type) {
		// Sembunyikan kedua tabel
		$('#resulttabel_satuan, #resulttabel_depo').hide();

		// Siapkan reference input
		const $satuan = $('#cc_start, #cc_end');
		const $depo = $('#cc_depo');

		// Bersihkan error Parsley (kalau dipakai)
		if ($('#myForm').length && $('#myForm').parsley) {
			$('#myForm').parsley().reset();
		}

		// Matikan kewajiban pada semua input dulu
		$satuan.prop('required', false).removeAttr('data-parsley-required').prop('disabled', true);
		$depo.prop('required', false).removeAttr('data-parsley-required').prop('disabled', true);

		// (opsional) kosongkan nilai & error message yang tidak dipakai
		$satuan.val('');
		$('.err_cc_start, .err_cc_end').text('');
		$depo.val('');
		$('.err_cc_depo').text('');

		if (type === 'satuan') {
			$('#resulttabel_satuan').show();
			// Aktifkan required utk Satuan
			$satuan.prop('disabled', false).prop('required', true).attr('data-parsley-required', 'true');
		} else if (type === 'depo') {
			$('#resulttabel_depo').show();
			// Aktifkan required utk Depo
			$depo.prop('disabled', false).prop('required', true).attr('data-parsley-required', 'true');
		}
	}
</script>
