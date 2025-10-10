<!-- Card -->
<form id="forms_add">
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
						<label class="form-label" for="jumlah_project">Jumlah Project </label>
						<input type="number" id="jumlah_project" name="jumlah_project" class="form-control-hover-light form-control duadigit"
							placeholder="jumlah project" required="">
						<span class="text-danger err_sing_cc"></span>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="projectFormsContainer"></div>
	<!-- <div class="card mt-4 border border-secondary border-2">
		<div class="card-body">
			<div class="row">
				<h2>Project 1</h2>
			</div>
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
						<input type="file" id="saldo_awal" name="saldo_awal"
							class="form-control-hover-light form-control" data-parsley-required="true"
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
					<div class="form-check form-check-inline">
						<input type="checkbox" id="formInlineCheck1" sclass="form-check-input indeterminate-checkbox">
						<label class="form-check-label" for="formInlineCheck1">Mengurangi Biaya</label>
					</div>
					<div class="form-check form-check-inline">
						<input type="checkbox" id="formInlineCheck2" sclass="form-check-input indeterminate-checkbox">
						<label class="form-check-label" for="formInlineCheck2">Meningkatkan Produktivitas </label>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-12">
					<label class="form-label" for="desc_project">Deskripsi Project</label>
					<textarea name="desc_project" id="desc_project"
						class="form-control-hover-light form-control curency" placeholder="input deskripsi project"></textarea>
				</div>
			</div>

			<div class="container mt-5" id="tblMengurangiBiayaContainer" style="display: none;">
				<h2>Mengurangi Biaya</h2>
				<table class="table table-bordered table-thead-bordered table-sm" id="tblMengurangiBiaya" style="width: 100%;">
					<thead class="thead-light">
						<tr>
							<th style="width: 30%;">Nama Account</th>
							<th>Keterangan</th>
							<th style="width: 20%;">Jumlah</th>
							<th style="width: 5%;"><i id="addRow" class="bi bi-plus-circle text-primary fs-1"></i></th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>

			<div class="container mt-5" id="tblMeningkatkanProduktivitasContainer" style="display: none;">
				<h2>Meningkatkan Produktivitas</h2>
				<table class="table table-bordered table-thead-bordered table-sm" id="tblMeningkatkanProduktivitas" style="width: 100%;">
					<thead class="thead-light">
						<tr>
							<th>Keterangan</th>
							<th style="width: 20%;">Jumlah</th>
							<th style="width: 5%;"><i id="addRow2" class="bi bi-plus-circle text-primary fs-1"></i></th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</div> -->
</form>

<script>
	$(document).ready(function() {
		$('.curency').mask("#.##0", {
			reverse: true
		});
		$('.duadigit').mask("00");
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
<script>
	$(document).ready(function() {
		// Ketika jumlah project diinputkan
		$('#jumlah_project').on('input', function() {
			var jumlahProject = $(this).val();
			var container = $('#projectFormsContainer');

			// Kosongkan container sebelumnya
			container.empty();

			// Tambahkan form baru sebanyak jumlah yang dimasukkan
			for (var i = 1; i <= jumlahProject; i++) {
				var formProject = `
                <div class="card mt-4 border border-secondary border-2" id="project_${i}">
                    <div class="card-body">
                        <div class="row">
                            <h2>Project ${i}</h2>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label" for="project_name_${i}">Nama Project </label>
                                    <input type="text" id="project_name_${i}" name="project_name_${i}"
                                        class="form-control form-control-hover-light " required=""
                                        placeholder="Nama Project ${i}">
                                    <span class="text-danger err_namaDepartemen"></span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label" for="project_file_${i}">Unggah Proposal </label>
                                    <input type="file" id="project_file_${i}" name="project_file_${i}"
                                        class="form-control form-control-hover-light" required=""
                                        placeholder="Unggah Proposal ${i}">
                                    <span class="text-danger err_namaDepartemen"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label" for="usulan_anggaran_${i}">Usulan Anggaran </label>
                                    <input type="text" id="usulan_anggaran_${i}" name="usulan_anggaran_${i}" required=""
                                        class="form-control form-control-hover-light curency"
                                        placeholder="Usulan Anggaran ${i}">
                                    <span class="text-danger err_sing_cc"></span>
                                </div>
                            </div>
							<div class="col-6">
								<div class="mb-3">
									<label class="form-label" for="perpanjang_angaran">Tujuan Proyek </label>
								</div>
								<div class="form-check form-check-inline ">
									<input type="checkbox" id="p_angaran_${i}" class="form-check-input indeterminate-checkbox" onchange="form_angaran(this)">
									<label class="form-check-label" for="p_angaran_${i}">Mengurangi Biaya</label>
								</div>
								<div class="form-check form-check-inline">
									<input type="checkbox" id="formInlineCheck${i}" class="form-check-input indeterminate-checkbox" onchange="form_prod_aktif(this)">
									<label class="form-check-label" for="formInlineCheck${i}">Meningkatkan Produktivitas </label>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-12">
								<label class="form-label" for="desc_project">Deskripsi Project</label>
									<textarea name="project_desc_${i}" id="project_desc_${i}"
									class="form-control form-control-hover-light curency" placeholder="Deskripsi Project ${i}"></textarea>
							</div>
						</div>
						<!-- Tabel Mengurangi Biaya -->
						<div class="container mt-5" id="tblMengurangiBiayaContainer_${i}" style="display: none;">
							<h2>Mengurangi Biaya</h2>
							<input class="counta_${i}" type="hidden" value="0">
							<table class="table table-bordered table-thead-bordered table-sm" id="tblMengurangiBiaya_${i}" style="width: 100%;">
								<thead class="thead-light">
									<tr>
										<th style="width: 30%;">Nama Account</th>
										<th>Keterangan</th>
										<th style="width: 20%;">Jumlah</th>
										<th style="width: 5%;"><i id="counta_${i}" class="bi bi-plus-circle text-primary fs-1" onclick="addrow_a(this)"></i></th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
						<!-- Tabel Meningkatkan Produktivitas -->
						<div class="container mt-5" id="tblMeningkatkanProduktivitasContainer_${i}" style="display: none;">
							<h2>Meningkatkan Produktivitas</h2>
							<input class="countb_${i}" type="hidden" value="0">
							<table class="table table-bordered table-thead-bordered table-sm" id="tblMeningkatkanProduktivitas_${i}" style="width: 100%;">
								<thead class="thead-light">
									<tr>
										<th>Keterangan</th>
										<th style="width: 20%;">Jumlah</th>
										<th style="width: 5%;"><i id="countb_${i}" class="bi bi-plus-circle text-primary fs-1" onclick="addrow_b(this)"></i></th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
                    </div>
                </div>
            `;
				container.append(formProject);
			}


		});
	});

	function form_angaran(e) {
		var id = e.id; // Mengambil ID dari elemen
		var isChecked = e.checked;
		var number = id.match(/\d+$/);
		if (isChecked) {
			$("#tblMengurangiBiayaContainer_" + number).show();
		} else {
			$("#tblMengurangiBiayaContainer_" + number).hide();
			$("#tblMengurangiBiaya_" + number).find(".remove_row" + number).remove();
			$(".counta_" + number).val(0);
		}
	}

	function form_prod_aktif(e) {
		var id = e.id;
		var isChecked = e.checked;
		var number = id.match(/\d+$/);
		if (isChecked) {
			$("#tblMeningkatkanProduktivitasContainer_" + number).show();
		} else {
			$("#tblMeningkatkanProduktivitasContainer_" + number).hide();
			$("#tblMeningkatkanProduktivitas_" + number).find(".remove_row" + number).remove();

		}

	}

	function addrow_a(e) {
		var id = e.id;
		var number = parseInt(id.match(/\d+$/));
		var getcounter = $("." + id).val();
		var counter = parseInt(getcounter) + 1;
		$("." + id).val(counter);
		var newRow = `
								<tr class="${id}" id="hapus${id}${counter}">
									<td>
										<select id="account_${id}${counter}" name="account_${id}[]" class="form-control">
											<option value="Account 1">Account 1</option>
											<option value="Account 2">Account 2</option>
											<option value="Account 3">Account 3</option>
										</select>
									</td>
									<td><input id="keterangan_${id}${counter}" name="keterangan_${id}[]" type="text" class="form-control" placeholder="account_${id}[]"></td>
									<td><input id="jumlah_${id}${counter}" name="jumlah_${id}[]"  type="text" class="form-control curency" placeholder="jumlah"></td>
									<td><i id="row_${id}${counter}" class="bi bi-dash-circle text-danger fs-1" onclick="hapus_a(this)"></i> </td>
								</tr>
							`;
		// Append new row to the table
		$("#tblMengurangiBiaya_" + number).find("tbody").append(newRow);
		$('.curency').mask("#.##0", {
			reverse: true
		});
	}

	function addrow_b(e) {
		var id = e.id;
		var number = parseInt(id.match(/\d+$/));
		var getcounter = $("." + id).val();
		var counter = parseInt(getcounter) + 1;
		$("." + id).val(counter);
		var newRow = `
					<tr class="${id}" id="hapus${id}${counter}">
						<td><input id="keterangan_b_${id}${counter}" type="text" class="form-control" placeholder="Keterangan"></td>
						<td><input id="jumlah_b_${id}${counter}" type="text" class="form-control curency" placeholder="Jumlah"></td>
						<td><i id="row_${number}" class="bi bi-dash-circle deleteRow2 text-danger fs-1"  onclick="hapus_b(this)" ></i> </td>
					</tr>
							`;
		// Append new row to the table
		$("#tblMeningkatkanProduktivitas_" + number).find("tbody").append(newRow);
		$('.curency').mask("#.##0", {
			reverse: true
		});
	}

	function hapus_a(e) {
		var id = e.id;
		var number = parseInt(id.match(/\d+$/));
		$("#hapuscounta_" + number).remove();
	}
</script>
