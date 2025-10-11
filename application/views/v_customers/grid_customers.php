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
				<tr class="table-primary">
					<th style="width: 20%">NO</th>
					<th style="width: 20%">ID</th>
					<th style="width: 60%">Nama</th>
					<th style="width: 60%">Nomor Telepon</th>
					<th style="width: 60%">Email</th>
					<th style="width: 60%">Alamat</th>
					<th style="width: 60%">Status Transaksi</th>
					<th style="width: 60%">Status Customer</th>
					<th style="width: 10%" class="text-center">Action</th>
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
				url: "<?= base_url('C_customers/griddata'); ?>", // URL ke controller
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