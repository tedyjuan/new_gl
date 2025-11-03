<!-- Card -->
<form id="forms_add" method="post" enctype="multipart/form-data">
	<div class="card">
		<div class="card-header">
			<div class="row align-items-center mb-2">
				<div class="col-md-12 d-flex justify-content-between">
					<h2 class="mb-0"><?= $judul ?></h2>
					<div class="div ">
						<button type="button" class="btn btn-sm btn-primary" onclick="loadform('<?= $load_grid ?>')"><i
								class="bi bi-arrow-left-circle"></i> Kembali</button>
						<button type="button" class="btn btn-sm btn-outline-primary" onclick="loadform('<?= $load_back ?>')">
							<i class="bi bi-arrow-clockwise"></i> Refresh</button>
					</div>
				</div>
			</div>
		</div>
		<div class="card-body">
			<div class="row">
				<div class="col-6">
					<div class="mb-3">
						<label class="form-label" for="perusahaan">Perusahaan </label>
						<input type="text" readonly id="perusahaan" value="<?= $data->code_company . ' - ' . $data->company_name ?>" class="form-control-hover-light form-control">
					</div>
				</div>
				<div class="col-6">
					<div class="mb-3">
						<label class="form-label" for="department">Departemen </label>
						<input type="text" readonly id="department" value="<?= $data->department_name; ?>" class="form-control-hover-light form-control curency">
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-6">
					<div class="mb-3">
						<label class="form-label" for="saldo_awal">Saldo Awal</label>
						<input type="text" readonly id="saldo_awal" value="<?= $data->opening_balance; ?>" class="form-control-hover-light form-control curency">
					</div>
				</div>
				<div class="col-3">
					<div class="mb-3">
						<label class="form-label" for="perpanjang_angaran">Perpanjangan Anggaran </label>
						<input type="text" readonly id="perpanjang_angaran" value="<?= $data->extend_budget; ?>" class="form-control-hover-light form-control curency">
					</div>
				</div>
				<div class="col-3">
					<div class="mb-3">
						<label class="form-label" for="jumlah_project">Jumlah Project </label>
						<input type="number" readonly id="jumlah_project" value="<?= $data->project_amount; ?>" class="form-control-hover-light  form-control curency">
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
	$no = 1;
	foreach ($data_project as $key => $row) { ?>
		<div class="card mt-4 border border-secondary border-1" id="project_<?= $no; ?>">
			<div class="card-body">
				<div class="row">
					<h2>Project <?= $no; ?></h2>
				</div>
				<div class="row">
					<div class="col-6">
						<div class="mb-3">
							<label class="form-label" for="project_name<?= $no; ?>">Nama Project </label>
							<input readonly type="text" id="project_name<?= $no; ?>" value="<?= $row['project_name']; ?>" class="form-control form-control-hover-light ">
						</div>
					</div>
					<div class="col-6">
						<div class="mb-3">
							<label class="form-label" for="project_file_<?= $no; ?>">Unggah Proposal</label>
							<input readonly type="text" id="project_file_<?= $no; ?>" value="<?= $row['filename']; ?>" class="form-control form-control-hover-light ">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-6">
						<div class="mb-3">
							<label class="form-label" for="usulan_anggaran_<?= $no; ?>">Usulan Anggaran </label>
							<input readonly type="text" id="usulan_anggaran_<?= $no; ?>" value="<?= $row['budget_proposal']; ?>" class="form-control form-control-hover-light curency">
						</div>
					</div>
					<div class="col-6">
						<div class="s">
							<label class="form-label" for="perpanjang_angaran">Tujuan Proyek </label>
						</div>
						<div class="form-check form-check-inline">
							<input type="checkbox" disabled id="p_angaran_<?= $no; ?>" class="form-check-input indeterminate-checkbox"
								<?php if ($row['goal_project'] == 'ALL' || $row['goal_project'] == 'OPEX') echo 'checked'; ?>>
							<label>Mengurangi Biaya (OPEX)</label>
						</div>
						<div class="form-check form-check-inline">
							<input type="checkbox" disabled id="meningkatkan_produktifitas<?= $no; ?>" class="form-check-input indeterminate-checkbox"
								<?php if ($row['goal_project'] == 'ALL' || $row['goal_project'] == 'CAPEX') echo 'checked'; ?>>
							<label>Meningkatkan Produktivitas (CAPEX)</label>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-12">
						<label class="form-label" for="desc_project">Deskripsi Project</label>
						<textarea readonly name="project_desc_<?= $no; ?>" id="project_desc_<?= $no; ?>" class="form-control form-control-hover-light "><?= $row['project_desc']; ?></textarea>
					</div>
				</div>
				<!-- Tabel Mengurangi Biaya -->
				<?php if (!empty($row['item_opex'])) { ?>
					<div class="container mt-5" id="tblMengurangiBiayaContainer_<?= $no; ?>">
						<h2>Mengurangi Biaya</h2>
						<input class="counta_<?= $no; ?>" type="hidden" value="0">
						<table class="table table-bordered table-thead-bordered table-sm" id="tblMengurangiBiaya_<?= $no; ?>" style="width: 100%;">
							<thead class="thead-light">
								<tr>
									<th style="width: 35%;">Nama Account</th>
									<th>Keterangan</th>
									<th style="width: 20%;">Jumlah</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($row['item_opex'] as $ia) { ?>
									<tr>
										<td style="width: 35%;"><?= $ia['account_number'] . ' - ' .  $ia['name'] ?></td>
										<td><?= $ia['desc']; ?></td>
										<td class="curency" style="width: 20%;"><?= $ia['amount']; ?></td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				<?php  } ?>
				<?php if (!empty($row['item_capex'])) { ?>
					<!-- Tabel Meningkatkan Produktivitas -->
					<div class="container mt-5" id="tblMeningkatkanProduktivitasContainer_<?= $no; ?>">
						<h2>Meningkatkan Produktivitas</h2>
						<input class="countb_<?= $no; ?>" type="hidden" value="0">
						<table class="table table-bordered table-thead-bordered table-sm" id="tblMeningkatkanProduktivitas_<?= $no; ?>" style="width: 100%;">
							<thead class="thead-light">
								<tr>
									<th>Keterangan</th>
									<th style="width: 20%;">Jumlah</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($row['item_capex'] as $ib) { ?>
									<tr>
										<td><?= $ib['desc']; ?></td>
										<td class="curency" style="width: 20%;"><?= $ib['amount']; ?></td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				<?php  } ?>
			</div>
		</div>
		<?php $no++; ?>
	<?php } ?>
	<style>
		.foot {
			position: fixed;
			bottom: 0;
			width: 77%;
			z-index: 100;
		}

		.form-check-input {
			transform: scale(1.3);
		}
	</style>
	<div style="margin-bottom: 5%;"></div>
	<div class="foot" id="foter_new">
		<div class="card">
			<div class="card-header">
				<div class="row">
					<div class="col-lg-3 col-md-6 col-12">
						<label class="form-label">Saldo Awal </label>
						<input type="text" value="<?= $summary['opening_balance'];?>" readonly id="f_saldo" name="f_saldo" class="form-control curency" placeholder="0">
					</div>
					<div class="col-lg-3 col-md-6 col-12">
						<label class="form-label" for="f_opex">Total Biaya</label>
						<input type="text" value="<?= $summary['opex'];?>" readonly id="f_opex" name="f_opex" class="form-control curency" placeholder="0">
					</div>
					<div class="col-lg-3 col-md-6 col-12">
						<label class="form-label" for="f_capex">Total Produktivitas</label>
						<input type="text" value="<?= $summary['capex'];?>" readonly id="f_capex" name="f_capex" class="form-control curency" placeholder="0">
					</div>
					<div class="col-lg-3 col-md-6 col-12">
						<label class="form-label" for="f_selisih">Selisih</label>
						<input type="text" value="<?= $summary['difference'];?>" readonly id="f_selisih" name="f_selisih" class="form-control curency" placeholder="0">
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
<script>
	$(document).ready(function() {
		$('.curency').mask("#.##0", {
			reverse: true
		});
	});
</script>