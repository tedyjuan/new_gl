<!-- Card -->

<div class="card">
	<div class="card-header">
		<div class="row align-items-center mb-3">
			<div class="col-md-12 d-flex justify-content-between">
				<h2 class="mb-0"><?= $judul; ?></h2>
				<div class="div">
					<a href="javascript:void(0)" class="btn btn-sm btn-outline-primary"
						onclick="loadform('<?= $load_grid ?>')">
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
					<div class="mb-3">
						<label class="form-label" for="company">Company</label>
						<select id="company" name="company" class="form-control-hover-light form-control"
							data-parsley-required="true" data-parsley-errors-container=".err_company" required="">
							<option value="">Pilih</option>
						</select>
						<span class="text-danger err_company"></span>
					</div>
				</div>
				<div class="col-6">
					<div class="mb-3">
						<label class="form-label" for="branch">Branch</label>
						<select id="branch" name="branch" class="form-control-hover-light form-control select2"
							data-parsley-required="true" data-parsley-errors-container=".err_branch" required="">
							<option value="">Pilih</option>
						</select>
						<span class="text-danger err_branch"></span>
					</div>
				</div>
				<div class="col-6">
					<div class="mb-3">
						<label class="form-label" for="year">Year</label>
						<select id="year" name="year" class="form-control-hover-light form-control select2"
							data-parsley-required="true" data-parsley-errors-container=".err_year" required="">
							<option value="">Pilih</option>
						</select>
						<span class="text-danger err_year"></span>
					</div>
				</div>
				<div class="col-6">
					<div class="mb-3">
						<label class="form-label" for="cost_center">Cost Center</label>
						<select id="cost_center" name="cost_center" class="form-control-hover-light form-control select2"
							data-parsley-required="true" data-parsley-errors-container=".err_cost_center" required="">
							<option value="">Pilih</option>
						</select>
						<span class="text-danger err_cost_center"></span>
					</div>
				</div>
			</div>
			<div class="row mt-3">
				<div class="col-4">
					<div class="mb-3">
						<div class="input-group input-group-merge input-group-sm">
							<div class="input-group-prepend input-group-text" id="searchTabelAddOn">
								<i class="bi-search"></i>
							</div>
							<input type="text" class="form-control " id="searchTabel" placeholder="search account number" aria-label="search account number" aria-describedby="searchTabelAddOn">
						</div>
						<table class="table table-bordered table-hover border-secondary  mt-2" id="tableAccount">
							<thead>
								<tr>
									<th style="width: 35%;">ACCOUNT NO</th>
									<th style="width: 65%;">DESCRIPTION</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan="2" class="text-center text-danger">Tidak ada data</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="col-8">
					<div class="row mb-1">
						<label for="inputEmail3" class="col-sm-3 col-form-label ">Opening Balance</label>
						<div class="col-sm-9">
							<div class="col-sm-9 input-group input-group-sm">
								<input type="text" id="opening_balance" class="form-control" placeholder="0" disabled>
								<span class="input-group-text bg-primary text-white" id="edit_op" title="Edit">
									<i class="bi bi-pen"></i>
								</span>
								<span class="input-group-text bg-success text-white" id="save_op" title="Save" style="display: none;">
									<i class="bi bi-save"></i>
								</span>
								<span class="input-group-text bg-danger text-white" id="edit_batal" title="Cancel" style="display: none;">
									<i class="bi bi-x-lg"></i>
								</span>
							</div>
						</div>
					</div>
					<div class="row mb-1">
						<label for="inputPassword3" class="col-sm-3 col-form-label">Closing Balance</label>
						<div class="col-sm-9 input-group-sm">
							<input type="text" class="form-control" id="inputPassword3" placeholder="0" disabled>
						</div>
					</div>
					<table class="table table-bordered border-secondary  mt-2">
						<thead>
							<tr>
								<th style="width: 10%;">Periode</th>
								<th style="width: 45%;">DEBIT</th>
								<th style="width: 45%;">CREDIT</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="text-center" class="text-center">1</td>
								<td>Otto</td>
								<td>Otto</td>
							</tr>
							<tr>
								<td class="text-center" class="text-center">2</td>
								<td>Otto</td>
								<td>Otto</td>
							</tr>
							<tr>
								<td class="text-center" class="text-center">3</td>
								<td>Otto</td>
								<td>Otto</td>
							</tr>
							<tr>
								<td class="text-center" class="text-center">4</td>
								<td>Otto</td>
								<td>Otto</td>
							</tr>
							<tr>
								<td class="text-center">5</td>
								<td>Otto</td>
								<td>Otto</td>
							</tr>
							<tr>
								<td class="text-center">6</td>
								<td>Otto</td>
								<td>Otto</td>
							</tr>
							<tr>
								<td class="text-center">7</td>
								<td>Otto</td>
								<td>Otto</td>
							</tr>
							<tr>
								<td class="text-center">8</td>
								<td>Otto</td>
								<td>Otto</td>
							</tr>
							<tr>
								<td class="text-center">9</td>
								<td>Otto</td>
								<td>Otto</td>
							</tr>
							<tr>
								<td class="text-center">10</td>
								<td>Otto</td>
								<td>Otto</td>
							</tr>
							<tr>
								<td class="text-center">11</td>
								<td>Otto</td>
								<td>Otto</td>
							</tr>
							<tr>
								<td class="text-center">12</td>
								<td>Otto</td>
								<td>Otto</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</form>
	</div>
</div>
<script>
	$(document).ready(function() {
		tahun();
		$(".select2").select2();

		$("#company").select2({
			placeholder: 'search code or name',
			allowClear: true,
			ajax: {
				url: "<?= base_url('C_company/search') ?>",
				dataType: "json",
				delay: 250,
				data: function(params) {
					return {
						getCompany: params.term
					};
				},
				processResults: function(data) {
					var results = [];
					$.each(data, function(index, item) {
						results.push({
							id: item.code_company,
							text: item.code_company + ' - ' + item.name,
						});
					});
					return {
						results: results
					};
				}
			}
		});

		$(".flatpiker-year").flatpickr({
			dateFormat: "Y",
			minDate: "1900",
			maxDate: "2100",
			onReady(_, __, fp) {
				fp.currentYearElement.removeAttribute("disabled"); // enable year select
				fp.monthElements.forEach(m => m.style.display = "none"); // hide months
			}
		});



	})
	// Klik EDIT
	$('#edit_op').on('click', function() {
		$('#opening_balance').prop('disabled', false).focus();

		// tombol tampil / sembunyi
		$('#edit_op').hide();
		$('#save_op').show();
		$('#edit_batal').show();
	});

	// Klik CANCEL
	$('#edit_batal').on('click', function() {

		// disable input lagi & kembalikan value awal
		$('#opening_balance').prop('disabled', true);

		// tombol tampil / sembunyi
		$('#edit_op').show();
		$('#save_op').hide();
		$('#edit_batal').hide();
	});

	// Klik SAVE
	$('#save_op').on('click', function() {
		let value = $('#opening_balance').val();

		// lakukan AJAX
		$.ajax({
			url: '/your-url/save-opening-balance', // ganti dengan URL kamu
			type: 'POST',
			data: {
				opening_balance: value
			},
			success: function(res) {
				// setelah sukses, kembali ke mode awal
				$('#opening_balance').prop('disabled', true);

				$('#edit_op').show();
				$('#save_op').hide();
				$('#edit_batal').hide();

				// optional alert
				console.log('Saved:', res);
			},
			error: function(err) {
				console.error('Error:', err);
				alert('Gagal menyimpan data');
			}
		});
	});

	$('#searchTabel').on('keyup', function() {
		var value = $(this).val().toLowerCase();

		$('#tableAccount tbody tr').filter(function() {
			$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
		});
	});

	$('#company').on('change', function() {
		var companyCode = $(this).val();
		$('#branch').empty().append('<option value="">Pilih</option>');
		if (companyCode) {
			$.ajax({
				url: '<?= base_url('C_global/getDepoByCompany/'); ?>' + companyCode,
				method: 'GET',
				dataType: 'JSON',
				beforeSend: function() {
					showLoader();
				},
				success: function(data) {
					hideLoader();
					data.forEach(function(depo) {
						$('#branch').append('<option value="' + depo.code_depo + '"  data-company="' + depo.code_company + '">' + depo.code_depo + '-' + depo.name + '</option>');
					});
				}
			});

		}
	});
	$('#branch').on('change', function() {
		var branch = $(this).val();
		var company = $('#branch option:selected').data('company');
		$('#cost_center').empty().append('<option value="">Pilih</option>');
		if (branch) {
			$.ajax({
				url: '<?= base_url('C_global/getCostCenterByDepo'); ?>',
				method: 'POST',
				data: {
					company: company,
					branch: branch,
				},
				dataType: 'JSON',
				beforeSend: function() {
					showLoader();
				},
				success: function(data) {
					hideLoader();
					data.forEach(function(val) {
						$('#cost_center').append('<option value="' + val.code_cost_center + '"  data-company="' + val.code_company + '">' + val.code_cost_center + ' - ' + val.group_team + '</option>');
					});
				}
			});
		}
	});



	function tahun() {

		let currentYear = new Date().getFullYear(); // tahun sekarang
		let minYear = currentYear - 2;
		let maxYear = currentYear + 2;

		// Generate option tahun
		for (let y = minYear; y <= maxYear; y++) {
			$('#year').append(`<option value="${y}">${y}</option>`);
		}

		// Set default = tahun sekarang
		$('#year').val(currentYear).trigger('change');

		// Jika pakai select2
		if ($('.select2').length) {
			$('#year').select2();
			$('#year').val(currentYear).trigger('change'); // ulangi setelah select2
		}
	}
	$('#cost_center').on('change', function() {
		var cost_center = $(this).val();
		var company = $('#cost_center option:selected').data('company');

		if (cost_center) {

			$.ajax({
				url: '<?= base_url('C_global/getAccountCenter'); ?>',
				method: 'POST',
				data: {
					company: company,
					cost_center: cost_center,
				},
				dataType: 'JSON',
				beforeSend: function() {
					showLoader();
				},
				success: function(data) {
					hideLoader();
					$('#tableAccount tbody').empty();
					if (data.length == 0) {
						$('#tableAccount tbody').append(`
							<tr>
								<td colspan="2" class="text-center text-danger">Tidak ada data</td>
							</tr>`);
						return;
					}

					// loop data & isi row tabel
					data.forEach(function(val) {
						$('#tableAccount tbody').append(`
							<tr>
								<td onclick="pilihcoa('${val.code_coa}')">${val.code_coa}</td>
                           		<td onclick="pilihcoa('${val.code_coa}')">${val.name}</td>
							</tr>`);
					});
				},
				error: function() {
					hideLoader();
					alert("Error loading data");
				}
			});

		}
	});

	function pilihcoa(coa) {
		console.log(coa);

	}
</script>
