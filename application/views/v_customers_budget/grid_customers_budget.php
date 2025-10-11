<!-- Card -->
<div class="card">
	<div class="card-header">
		<div class="row align-items-center mb-3">
			<div class="col-md-12 d-flex justify-content-between">
				<h2 class="mb-0"><?= $judul; ?></h2>
				<div class="div">
					<button class="btn btn-sm btn-primary" onclick="loadform('<?= $load_add ?>')">
						<i class="bi bi-plus-circle"></i> Tambah data
					</button>
					<a href="javascript:void(0)" class="btn btn-sm btn-outline-primary" onclick="loadform('<?= $load_grid ?>')">
						<i class="bi bi-arrow-clockwise"></i> Refresh
					</a>
				</div>
			</div>
		</div>
	</div>
	<div class="card-body">
		<table class="table table-sm table-striped table-hover table-bordered" id="mytable" style="width: 100%">
			<thead>
				<tr class="table-primary text-center align-middle">
					<th style="width: 5%">No</th>
					<th style="width: 15%">ID Budget</th>
					<th style="width: 20%">Nama Proyek</th>
					<th style="width: 15%">Customer</th>
					<th style="width: 10%">Total Budget</th>
					<th style="width: 10%">Timeline</th>
					<th style="width: 10%">Status</th>
					<th style="width: 10%">File</th>
					<th style="width: 10%">Aksi</th>
				</tr>
			</thead>
		</table>

	</div>
</div>
<script>
	function initTable() {
		// kalau sebelumnya sudah ada instance, hancurkan dulu
		if (window.mytableDT && $.fn.dataTable.isDataTable('#mytable')) {
			window.mytableDT.clear().destroy();
			window.mytableDT = null;
		}

		window.mytableDT = $('#mytable').DataTable({
			processing: true,
			serverSide: true, // Mengaktifkan server-side processing
			ajax: {
				url: "<?= base_url('C_customers_budget/griddata'); ?>", // URL ke controller
				type: "GET", // Gunakan GET untuk request
			},
			columnDefs: [{
				orderable: false,
				targets: -1 // Menonaktifkan sorting pada kolom terakhir (aksi)
			}],
			// Opsi tambahan yang membantu saat SPA:
			destroy: true, // Auto-destroy jika di-init di elemen yang sama
			retrieve: true // Jika sudah ada instance, gunakan instance tersebut
		});
	}

	$(document).ready(function() {
		initTable();
	});

	function filters() {
		$('#btn_cancel').click();
		if (window.mytableDT) {
			window.mytableDT.ajax.reload(null, false); // Reload data tanpa reset paging
		} else {
			initTable(); // fallback kalau belum ke-init
		}
	}
</script>