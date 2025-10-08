<!-- Card -->
<div class="card">
	<div class="card-header">
		<div class="row align-items-center mb-2">
			<div class="col-md-12 d-flex justify-content-between">
				<h2 class="mb-0"><?= $judul ?></h2>
				<div class="div ">
					<button class="btn btn-sm btn-primary" onclick="loadform('<?= $load_grid ?>')"><i
							class="bi bi-arrow-left-circle"></i> Back</button>
					<a href="javascript:void(0)" class="btn btn-sm btn-outline-primary"
						onclick="loadform('<?= $load_refresh ?>')">
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
						<label class="form-label" for="perusahaan">Perusahaan</label>
						<select id="perusahaan" name="perusahaan" class="form-control-hover-light form-control select2"
							data-parsley-required="true" data-parsley-errors-container=".err_name" required="">
							<option value="">Pilih</option>
						</select>
						<span class="text-danger err_name"></span>
					</div>
				</div>
				<div class="col-6">
					<div class="mb-3">
						<label class="form-label" for="kode_department">Kode Departement</label>
						<input type="text" id="kode_department" name="kode_department" data-parsley-required="true"
							data-parsley-errors-container=".err_kodedepartment" required=""
							value="<?= $data->code_department ?>" class="form-control-hover-light form-control"
							placeholder="input kode department max:2 karakter">
						<span class="text-danger err_kodedepartment"></span>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-6">
					<div class="mb-3">
						<label class="form-label" for="nama_department">Nama Divisi</label>
						<input type="text" id="nama_department" name="nama_department" value="<?= $data->name ?>"
							class="form-control-hover-light form-control kapital" data-parsley-required="true"
							data-parsley-errors-container=".err_namadepartment" required=""
							placeholder="input nama department">
						<span class="text-danger err_namadepartment"></span>
					</div>
				</div>
				<div class="col-6">
					<div class="mb-3">
						<label class="form-label" for="alias">Alias</label>
						<input type="text" id="alias" name="alias" data-parsley-required="true"
							data-parsley-errors-container=".err_sing_cc" required="" value="<?= $data->alias ?>"
							class="form-control-hover-light form-control kapital"
							placeholder="input singkatan cost center">
						<span class="text-danger err_sing_cc"></span>
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
<script>
	$('#btnsubmit').click(function(e) {
		e.preventDefault();
		let form = $('#forms_add');
		var uuid = "<?= ($uuid) ?>";
		form.parsley().validate();
		if (form.parsley().isValid()) {
			$.ajax({
				url: "<?= base_url('C_department/update') ?>",
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
			});
		}
	});

	$(document).ready(function() {
		$("#perusahaan").empty().append(`<option class='form-control' value="<?= $data->code_company ?>"><?= $data->code_company ?> - <?= $data->nm_company ?></option>`).val("<?= $data->code_company ?>").trigger('change');
		$('#kode_department').on('keyup', function() {
			var currentValue = $(this).val();
			currentValue = currentValue.replace(/[^0-9]/g, '');
			if (currentValue === '') {
				var incrementedValue = 0;
			} else {
				var incrementedValue = parseInt(currentValue);
			}
			var formattedValue = String(incrementedValue).padStart(2, '0');
			if (formattedValue.length > 2) {
				$(this).val(formattedValue.slice(0, 2));
			} else {
				$(this).val(formattedValue).trigger("input");
			}
		});
		$('.kapital').on('input', function(e) {
			this.value = this.value.replace(/[^a-zA-Z0-9 /-]/g, '').toUpperCase();
		});
		$("#perusahaan").select2({
			placeholder: 'Cari kode atau nama',
			minimumInputLength: 1,
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
	})
</script>
