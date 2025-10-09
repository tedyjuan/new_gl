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
						<label class="form-label" for="perusahaan">Perusahaan </label>
						<select id="perusahaan" name="perusahaan" class="form-control-hover-light form-control select2"
							data-parsley-required="true" data-parsley-errors-container=".err_company" required="">
							<option value="">Pilih</option>
							<?php foreach ($perusahaanList as $perusahaan) : ?>
								<option value="<?= $perusahaan->code_company ?>"><?= $perusahaan->code_company ?> -
									<?= $perusahaan->name ?>
								</option>
							<?php endforeach; ?>
						</select>
						<span class="text-danger err_company"></span>
					</div>
				</div>
				<div class="col-6">
					<div class="mb-3">
						<label class="form-label" for="Department">Departemen </label>
						<select id="Department" name="Department" class="form-control-hover-light form-control select2"
							data-parsley-required="true" data-parsley-errors-container=".err_dept" required="">
							<option value="">Pilih Perusahaan Dahulu</option>
						</select>
						<span class="text-danger err_dept"></span>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-6">
					<div class="mb-3">
						<label class="form-label" for="saldo_awal">Saldo Awal</label>
						<input type="text" id="saldo_awal" name="saldo_awal"
							class="form-control-hover-light form-control curency" data-parsley-required="true"
							data-parsley-errors-container=".err_namaDepartemen" required=""
							placeholder="input saldo awal">
						<span class="text-danger err_namaDepartemen"></span>
					</div>
				</div>
				<div class="col-3">
					<div class="mb-3">
						<label class="form-label" for="perpanjang_angaran">Perpanjangan Anggaran </label>
						<input type="text" id="perpanjang_angaran" name="perpanjang_angaran" data-parsley-required="true"
							data-parsley-errors-container=".err_sing_cc" required=""
							class="form-control-hover-light form-control curency"
							placeholder="input angaran">
						<span class="text-danger err_sing_cc"></span>
					</div>
				</div>
				<div class="col-3">
					<div class="mb-3">
						<label class="form-label" for="perpanjang_angaran">Jumlah Project </label>
						<input type="text" id="perpanjang_angaran" name="perpanjang_angaran" data-parsley-required="true"
							data-parsley-errors-container=".err_sing_cc" required=""
							class="form-control-hover-light form-control curency"
							placeholder="jumlah project">
						<span class="text-danger err_sing_cc"></span>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
<div class="card mt-4 border border-secondary border-2">
	<div class="card-body">
		<div class="row">
			<div class="col-6">
				<div class="mb-3">
					<label class="form-label" for="saldo_awal">Nama Project </label>
					<input type="text" id="saldo_awal" name="saldo_awal"
						class="form-control-hover-light form-control curency" data-parsley-required="true"
						data-parsley-errors-container=".err_namaDepartemen" required=""
						placeholder="input saldo awal">
					<span class="text-danger err_namaDepartemen"></span>
				</div>
			</div>
			<div class="col-6">
				<div class="mb-3">
					<label class="form-label" for="saldo_awal">Unggah Proposal </label>
					<input type="text" id="saldo_awal" name="saldo_awal"
						class="form-control-hover-light form-control curency" data-parsley-required="true"
						data-parsley-errors-container=".err_namaDepartemen" required=""
						placeholder="input saldo awal">
					<span class="text-danger err_namaDepartemen"></span>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-6">
				<div class="mb-3">
					<label class="form-label" for="perpanjang_angaran">Usulan Anggaran </label>
					<input type="text" id="perpanjang_angaran" name="perpanjang_angaran" data-parsley-required="true"
						data-parsley-errors-container=".err_sing_cc" required=""
						class="form-control-hover-light form-control curency"
						placeholder="input usulan angaran">
					<span class="text-danger err_sing_cc"></span>
				</div>
			</div>
			<div class="col-6">
				<div class="mb-3">
					<label class="form-label" for="perpanjang_angaran">Tujuan Proyek </label>

				</div>

				<!-- Form Check -->
				<div class="form-check form-check-inline">
					<input type="checkbox" id="formInlineCheck1" sclass="form-check-input indeterminate-checkbox">
					<label class="form-check-label" for="formInlineCheck1">Mengurangi Biaya</label>
				</div>
				<!-- End Form Check -->
				<!-- Form Check -->
				<div class="form-check form-check-inline">
					<input type="checkbox" id="formInlineCheck2" sclass="form-check-input indeterminate-checkbox">
					<label class="form-check-label" for="formInlineCheck2">Meningkatkan Produktivitas </label>
				</div>
				<!-- End Form Check -->
			</div>
		</div>

		<div class="row">
			<div class="col-12">
				<div class="mb-3">
					<label class="form-label" for="desc_project">Deskripsi Project</label>
					<textarea name="desc_project" id="desc_project"
						class="form-control-hover-light form-control curency" placeholder="input deskripsi project"></textarea>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function() {
		$('.curency').mask("#.##0", {
			reverse: true
		});
		// Ketika perusahaan dipilih
		$('#perusahaan').on('change', function() {
			var companyCode = $(this).val();
			// Mengosongkan dropdown dan reset nilai default sebelum AJAX
			$('#Department').empty().append('<option value="">Pilih</option>');
			if (companyCode) {
				// Memuat Department
				$.ajax({
					url: '<?= base_url('C_global/getDepartmentByCompany/'); ?>' + companyCode,
					method: 'GET',
					dataType: 'JSON',
					success: function(data) {
						data.forEach(function(department) {
							$('#Department').append('<option value="' + department
								.code_department + '" data-alias="' + department
								.alias + '">' + department.alias + ' : ' + department.name + '</option>');
						});
					}
				});
			}
		});
	});
</script>
