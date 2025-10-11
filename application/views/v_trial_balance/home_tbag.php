 <!-- Card -->
 <div class="card">
 	<div class="card-header">
 		<div class="row align-items-center">
 			<div class="col-md-12 d-flex justify-content-between">
 				<h2 class="mb-0">Tiral Balance</h2>

 			</div>
 		</div>
 	</div>
 	<div class="card-body">
 		<ul class="nav nav-tabs" id="myTab" role="tablist">
 			<li class="nav-item" role="presentation">
 				<button class="nav-link active" id="tbag-tab1" data-bs-toggle="tab" data-bs-target="#group-nav1" type="button" role="tab" aria-controls="group-nav1" aria-selected="true"><i class="bi bi-diagram-3-fill"></i> Group 1</button>
 			</li>
 			<li class="nav-item" role="presentation">
 				<button class="nav-link" id="tbag-tab2" data-bs-toggle="tab" data-bs-target="#group-nav2" type="button" role="tab" aria-controls="group-nav2" aria-selected="false"><i class="bi bi-diagram-3-fill"></i> Group 2</button>
 			</li>
 			<li class="nav-item" role="presentation">
 				<button class="nav-link" id="tbag-tab3" data-bs-toggle="tab" data-bs-target="#group-nav3" type="button" role="tab" aria-controls="group-nav3" aria-selected="false"><i class="bi bi-diagram-3-fill"></i> Group 3</button>
 			</li>
 		</ul>

 		<!-- Konten Tab -->
 		<div class="tab-content" id="myTabContent">
 			<div class="tab-pane fade show active" id="group-nav1" role="tabpanel" aria-labelledby="tbag-tab1">
 				<!-- Konten akan dimuat di sini -->
 			</div>
 			<div class="tab-pane fade" id="group-nav2" role="tabpanel" aria-labelledby="tbag-tab2">
 				<!-- Konten akan dimuat di sini -->
 			</div>
 			<div class="tab-pane fade" id="group-nav3" role="tabpanel" aria-labelledby="tbag-tab3">
 				<!-- Konten akan dimuat di sini -->
 			</div>
 		</div>
 	</div>
 </div>

 <script>
 	$(document).ready(function() {
 		$('#tbag-tab1').on('click', function() {
 			$('#group-nav1').load('C_trial_balance/tbag_1');
 		});

 		$('#tbag-tab2').on('click', function() {
 			$('#group-nav2').load('C_trial_balance/tbag_2');
 		});

 		$('#tbag-tab3').on('click', function() {
 			$('#group-nav3').load('C_trial_balance/tbag_3');
 		});
 	});
 </script>
