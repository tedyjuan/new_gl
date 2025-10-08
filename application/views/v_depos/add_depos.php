<!-- Card -->
<div class="card">
	<div class="card-header">
		<div class="row align-items-center mb-2">
			<div class="col-md-12 d-flex justify-content-between">
				<h2 class="mb-0"><?= $judul ?></h2>
				<div class="div ">
					<button class="btn btn-sm btn-primary" onclick="loadform('<?= $load_grid ?>')"><i
							class="bi bi-arrow-left-circle"></i> Kembali</button>
					<a href="javascript:void(0)" class="btn btn-sm btn-outline-primary"
						onclick="loadform('<?= $load_back ?>')">
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
						<label class="form-label" for="kode_depo">Kode Depo</label>
						<input type="text" id="kode_depo" name="kode_depo" data-parsley-required="true"
							data-parsley-errors-container=".err_kodedepo" required=""
							class="form-control-hover-light form-control" placeholder="input kode depo">
						<span class="text-danger err_kodedepo"></span>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-6">
					<div class="mb-3">
						<label class="form-label" for="nama_depo">Nama Depo</label>
						<input type="text" id="nama_depo" name="nama_depo"
							class="form-control-hover-light form-control string" data-parsley-required="true"
							data-parsley-errors-container=".err_namadepo" required="" placeholder="input nama depo">
						<span class="text-danger err_namadepo"></span>
					</div>
				</div>
				<div class="col-6">
					<div class="mb-3">
						<label class="form-label" for="kd_depo_cost_center">Kode Depo Cost Center</label>
						<input type="text" id="kd_depo_cost_center" name="kd_depo_cost_center"
							data-parsley-required="true" data-parsley-errors-container=".err_depo_cc" required=""
							class="form-control-hover-light form-control" placeholder="input kode depo cost center">
						<span class="text-danger err_depo_cc"></span>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-6">
					<div class="mb-3">
						<label class="form-label" for="singkatan_cost_center">Singkatan Cost Center</label>
						<input type="text" id="singkatan_cost_center" name="singkatan_cost_center"
							data-parsley-required="true" data-parsley-errors-container=".err_sing_cc" required=""
							class="form-control-hover-light form-control" placeholder="input singkatan cost center">
						<span class="text-danger err_sing_cc"></span>
					</div>
				</div>
				<div class="col-3">
					<div class="mb-3">
						<label class="form-label" for="npwp">NPWP</label>
						<input type="text" id="npwp" name="npwp" data-parsley-required="true"
							data-parsley-errors-container=".err_npwp" required=""
							class="form-control-hover-light form-control" placeholder="input npwp">
						<span class="text-danger err_npwp"></span>
					</div>
				</div>
				<div class="col-3">
					<div class="mb-3">
						<label class="form-label" for="status_depo">Status Depo</label>
						<select name="status_depo" id="status_depo" class="form-control-hover-light form-control"
							data-parsley-required="true" data-parsley-errors-container=".err_status_depo"
							required="">
							<option value="">-- Pilih --</option>
							<option value="depo">DEPO</option>
							<option value="pusat">PUSAT</option>
						</select>
						<span class="text-danger err_status_depo"></span>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-6">
					<div class="mb-3">
						<label class="form-label" for="kota">Kota</label>
						<input type="text" id="kota" name="kota" data-parsley-required="true"
							data-parsley-errors-container=".err_kota" required=""
							class="form-control-hover-light form-control" placeholder="input kota">
						<span class="text-danger err_kota"></span>
					</div>
				</div>
				<div class="col-3">
					<div class="mb-3">
						<label class="form-label" for="kode_pos">Kode Pos</label>
						<input type="text" id="kode_pos" name="kode_pos" data-parsley-required="true"
							data-parsley-errors-container=".err_kodepos" required=""
							class="form-control-hover-light form-control" placeholder="input kode pos">
						<span class="text-danger err_kodepos"></span>
					</div>
				</div>
				<div class="col-3">
					<div class="mb-3">
						<label class="form-label" for="nomor_hp">Nomor Handphone</label>
						<input type="text" id="nomor_hp" name="nomor_hp" data-parsley-required="true"
							data-parsley-errors-container=".err_no_hp" required=""
							class="form-control-hover-light form-control" placeholder="input nomor handphone">
						<span class="text-danger err_no_hp"></span>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-12">
					<div class="mb-3">
						<label class="form-label" for="alamat">Alamat</label>
						<textarea class="form-control-hover-light form-control" name="alamat" id="alamat" cols="2" rows="4"
							data-parsley-required="true" data-parsley-errors-container=".err_alamat" required=""
							placeholder="input alamat"></textarea>
						<span class="text-danger err_alamat"></span>
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
		form.parsley().validate();
		if (form.parsley().isValid()) {
			$.ajax({
				url: "<?= base_url('C_depos/simpandata') ?>",
				type: 'POST',
				method: 'POST',
				dataType: 'JSON',
				data: form.serialize(),
				beforeSend: function() {
					showLoader();
				},
				success: function(data) {
					if (data.hasil == 'true') {
						swet_sukses(data.pesan);
						loadform('<?= $load_grid;?>');
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
		}
	});

	$(document).ready(function() {
		$('#kd_depo_cost_center').on('keyup', function() {
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
		$('#nama_depo').on('keyup', function() {
			var value = $(this).val();
			var formattedValue = value.replace(/\b\w/g, function(char) {
				return char.toUpperCase();
			}).replace(/\B\w/g, function(char) {
				return char.toLowerCase();
			});
			$('#nama_depo').val(formattedValue);
		});

		$('#kode_depo').mask('000');
		$('#kode_pos').mask('00000');
		$('#nomor_hp').mask("#-###0", {
			reverse: true
		});
		$('#npwp').mask('99.999.999.9-999.999');
		$('#singkatan_cost_center').on('input', function(e) {
			this.value = this.value.replace(/[^a-zA-Z0-9 ]/g, '').toUpperCase();
		});
		$('.string').on('input', function(e) {
			this.value = this.value.replace(/[^a-zA-Z0-9 ]/g, '');
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
