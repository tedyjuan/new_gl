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
						<label class="form-label" for="code_department">Department</label>
						<select id="code_department" name="code_department" class="form-control-hover-light form-control select2"
							data-parsley-required="true" data-parsley-errors-container=".err_code_department" required="">
							<option value="">Select..</option>
							<option value="All">All</option>
							<option value="General">General</option>
						</select>
						<span class="text-danger err_coade_department"></span>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-6">
					<div class="mb-3">
						<label class="form-label" for="start_account">From Account</label>
						<input type="text" id="start_account" name="start_account"
							class="form-control-hover-light form-control kapital" data-parsley-required="true"
							data-parsley-errors-container=".err_start_account" required=""
							placeholder="input start account">
						<span class="text-danger err_start_account"></span>
					</div>
				</div>
				<div class="col-6">
					<div class="mb-3">
						<label class="form-label" for="alias">To Account</label>
						<input type="text" id="alias" name="alias" data-parsley-required="true"
							data-parsley-errors-container=".err_sing_cc" required=""
							class="form-control-hover-light form-control kapital"
							placeholder="input singkatan cost center">
						<span class="text-danger err_sing_cc"></span>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-6">
					<div class="mb-3">
						<label class="form-label" for="periode">Periode</label>
						<select id="periode" name="periode" class="form-control-hover-light form-control select2"
							data-parsley-required="true" data-parsley-errors-container=".err_periode" required="">
							<option value="YTD">Year To Date</option>
							<option value="MTD">Month To Date</option>
						</select>
						<span class="text-danger err_periode"></span>
					</div>
				</div>
				<div class="col-6">
					<div class="mb-3">
						<label class="form-label" for="year">Year</label>
						<select id="year" name="year" class="form-control-hover-light form-control select2"
							data-parsley-required="true" data-parsley-errors-container=".err_year" required="">
							<option value="YTD">Year To Date</option>
							<option value="MTD">Month To Date</option>
						</select>
						<span class="text-danger err_year"></span>
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
