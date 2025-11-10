<!-- Card -->
<div class="card">
	<div class="card-header">
		<div class="row align-items-center mb-2">
			<div class="col-md-12 d-flex justify-content-between">
				<h2 class="mb-0"><?= $judul ?></h2>
				<div class="div">
					<button class="btn btn-sm btn-primary" onclick="loadform('<?= $load_grid ?>')"><i class="bi bi-arrow-left-circle"></i> Kembali</button>
					<a href="javascript:void(0)" class="btn btn-sm btn-outline-primary" onclick="loadform('<?= $load_back ?>')">
						<i class="bi bi-arrow-clockwise"></i> Refresh
					</a>
				</div>
			</div>
		</div>
	</div>
	<div class="card-body p-4 p-md-5">
		<form id="voucherForm">
			<div class="row g-4 g-md-5 mb-4 mb-md-5">
				<div class="col-md-6">
					<div class="mb-3">
						<label class="form-label ">Proveniance</label>
						<div class="d-flex gap-4 mt-2">
							<div class="form-check">
								<input class="form-check-input" type="radio" name="proveniance" id="cash" value="cash" checked>
								<label class="form-check-label cursor-pointer" for="cash">Cash</label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="proveniance" id="bank" value="bank">
								<label class="form-check-label cursor-pointer" for="bank">Bank</label>
							</div>
						</div>
					</div>
					<div class="mb-3">
						<label class="form-label mt-4">Flow</label>
						<div class="d-flex gap-4 ">
							<div class="form-check">
								<input class="form-check-input" type="radio" name="flow" id="in" value="in" checked>
								<label class="form-check-label cursor-pointer" for="in">In</label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="flow" id="out" value="out">
								<label class="form-check-label cursor-pointer" for="out">Out</label>
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-6">
					<div class="mb-3">
						<label for="voucherNo" class="form-label ">Voucher No</label>
						<input type="text" class="form-control" id="voucherNo" name="voucherNo" placeholder="Enter voucher number">
					</div>

					<div class="mb-3">
						<label for="date" class="form-label ">Date</label>
						<div class="input-group">
							<span class="input-group-text">
								<i class="bi bi-calendar"></i>
							</span>
							<input type="text" class="form-control border-start-0 flatpiker" id="date" name="date" placeholder="Pick a date">
						</div>
					</div>
				</div>
			</div>



			<!-- Bank Details Table -->
			<div class="mb-4 mb-md-5">
				<div class="table-responsive border rounded overflow-hidden">
					<table class="table mb-0" id="bankDetailsTable">
						<thead class="table-light">
							<tr>
								<th>Account No.</th>
								<th style="width: 35%;">Bank</th>
								<th style="width: 24%;">Due Date</th>
								<th style="width: 5%;">Action</th>
							</tr>
						</thead>
						<tbody id="bankDetailsBody">
							<tr data-index="0" class="align-middle">
								<td class="p-2">
									<select style="width: 100%;" name="lineAccountNo[]" data-parsley-required="true" data-parsley-errors-container=".err_akun1" required=""
										class="form-control select_account" style="width:100%">
										<option value="">pilih</option>
									</select>
									<span class="text-danger err_akun1"></span>
								</td>
								<td class="p-2"><input type="text" class="form-control " placeholder="Bank name" name="bank[]"></td>
								<td class="p-2"><input type="text" class="form-control flatpiker" placeholder="Pick a date" name="dueDate[]"></td>
								<td class="p-2 text-center"><i class="bi bi-trash text-danger fs-1 btnDeleteBank"></i></td>
							</tr>
						</tbody>
					</table>
					<div class="mt-2 d-flex justify-content-end mb-4">
						<button type="button" id="addBankBtn" class="btn btn-sm btn-success"> <i class="bi bi-plus-circle"></i> Add Bank Account</button>
					</div>
				</div>
			</div>

			<!-- Line Items Table -->
			<div class="mb-4 mb-md-5">
				<div class="table-responsive border rounded overflow-hidden">
					<table class="table mb-0" id="lineItemsTable">
						<thead class="table-light">
							<tr>
								<th>No. Account</th>
								<th style="width: 25%;">Description</th>
								<th style="width: 17%;">Debit</th>
								<th style="width: 17%;">Credit</th>
								<th style="width: 5%;">Action</th>
							</tr>
						</thead>
						<tbody id="lineItemsBody">
							<tr data-index="0" class="align-middle">
								<td class="p-2">
									<select name="lineAccountNo[]" data-parsley-required="true" data-parsley-errors-container=".err_akunline" required=""
										class="form-control select_account" style="width:100%">
										<option value="">pilih</option>
									</select>
									<span class="text-danger err_akunline"></span>
								</td>
								<td class="p-2"><input type="text" class="form-control" placeholder="Description" name="description[]"></td>
								<td class="p-2"><input type="text" class="form-control currency debit-input" placeholder="0" name="debit[]"></td>
								<td class="p-2"><input type="text" class="form-control currency credit-input" placeholder="0" name="credit[]"></td>
								<td class="p-2 text-center"><i class="bi bi-trash text-danger fs-1 btnDeleteLineItem"></i></td>
							</tr>
						</tbody>
						<tfoot>
							<tr class="bg-soft-primary">
								<th colspan="2" class="text-end p-2 fw-bold">Total :</th>
								<th class="p-2 fw-bold ps-3" id="totalDebit">0.00</th>
								<th class="p-2 fw-bold ps-3" id="totalCredit">0.00</th>
								<th class="p-2"></th>
							</tr>
						</tfoot>
					</table>
					<div class="mt-2 d-flex justify-content-end mb-4">
						<button type="button" id="addLineItemBtn" class="btn btn-sm btn-success"> <i class="bi bi-plus-circle"></i> Add Line Item</button>
					</div>
				</div>
			</div>

			<!-- Action Buttons -->
			<div class="d-flex justify-content-end gap-2 pt-3">
				<button type="button" id="btnsubmit" class="btn btn-sm btn-primary" ><i class="bi bi-send"></i> Simpan</button>
				<button type="reset" id="resetBtn" class="btn btn-sm btn-outline-danger"><i class="bi bi-eraser-fill"></i> Reset</button>
			</div>
		</form>
	</div>
</div>

<script>
	$(document).ready(function() {
		$(".flatpiker").flatpickr({
			dateFormat: "Y-m-d"
		});
		$('.currency').mask("#.##0", {
			reverse: true
		});

		select_account();
		$('#addBankBtn').on('click', function() {
			var id = Date.now();
			var newRow = `
                <tr data-index="${$('#bankDetailsTable tbody tr').length}" class="align-middle">
                    <td class="p-2">
						<select  name="accountNo[]" data-parsley-required="true" data-parsley-errors-container=".err_akun${id}" required=""
								class="form-control select_account" style="width:100%">
							<option value="">pilih</option>
						</select>
						<span class="text-danger err_akun${id}"></span>
					</td>
                    <td class="p-2"><input type="text" class="form-control " placeholder="Bank name" name="bank[]"></td>
                    <td class="p-2"><input type="text" class="form-control flatpiker " placeholder="Pick a date" readonly name="dueDate[]"></td>
                    <td class="p-2  text-center"><i class="bi bi-trash text-danger fs-1 btnDeleteBank "></i></td>
                </tr>
            `;
			$('#bankDetailsBody').append(newRow);
			select_account();
			$(".flatpiker").flatpickr({
				dateFormat: "Y-m-d"
			});

		});

		// Add new line item row
		$('#addLineItemBtn').on('click', function() {
			var ll = Date.now();
			var newRow = `
                <tr data-index="${$('#lineItemsTable tbody tr').length}" class="align-middle">
                    <td class="p-2">
						<select  name="lineAccountNo[]" data-parsley-required="true" data-parsley-errors-container=".err_akun${ll}" required=""
							class="form-control select_account" style="width:100%">
						<option value="">pilih</option>
						</select>
						<span class="text-danger err_akun${ll}"></span>
					</td>
                    <td class="p-2"><input type="text" class="form-control " placeholder="Description" name="description[]"></td>
                    <td class="p-2"><input type="text" class="form-control currency debit-input" placeholder="0" name="debit[]"></td>
                    <td class="p-2"><input type="text" class="form-control currency credit-input" placeholder="0" name="credit[]"></td>
                    <td class="p-2 text-center"><i class="bi bi-trash text-danger fs-1 btnDeleteLineItem "></i></td>
                </tr>
            `;
			$('#lineItemsBody').append(newRow);
			select_account();
			updateTotal();
			$('.currency').mask("#.##0", {
				reverse: true
			});

		});

		// Update total debit and credit when line items change
		$('#lineItemsBody').on('input', '.debit-input, .credit-input', function() {
			updateTotal();
		});

		// Function to calculate total debit and credit
		function updateTotal() {
			var totalDebit = 0;
			var totalCredit = 0;

			$('.debit-input').each(function() {
				totalDebit += parseFloat($(this).val()) || 0;
			});

			$('.credit-input').each(function() {
				totalCredit += parseFloat($(this).val()) || 0;
			});

			$('#totalDebit').text(totalDebit);
			$('#totalCredit').text(totalCredit);
		}
		// Delete bank row (with restriction for single row)
		$('#bankDetailsBody').on('click', '.btnDeleteBank', function() {
			// Check if there is only one row left
			if ($('#bankDetailsTable tbody tr').length > 1) {
				$(this).closest('tr').remove();
			} else {
				swet_gagal("You cannot delete the last row.");
			}
		});

		// Delete line item row (with restriction for single row)
		$('#lineItemsBody').on('click', '.btnDeleteLineItem', function() {
			// Check if there is only one row left
			if ($('#lineItemsTable tbody tr').length > 1) {
				$(this).closest('tr').remove();
				updateTotal(); // Recalculate total after deleting a row
			} else {
				swet_gagal("You cannot delete the last row.");
			}
		});

	});

	function select_account() {
		var code_company = $('#perusahaan').val();
		$(".select_account").select2({
			placeholder: 'Search account',
			minimumInputLength: 1,
			allowClear: true,
			ajax: {
				url: "<?= base_url('C_petty_cash/Coa_all') ?>",
				dataType: "json",
				delay: 250,
				data: function(params) {
					return {
						cari: params.term,
						code_company: code_company,
					};
				},
				processResults: function(data) {
					var results = [];
					$.each(data, function(index, item) {
						results.push({
							id: item.account_number,
							text: item.account_number + ' - ' + item.name,
						});
					});
					return {
						results: results
					};
				}
			}
		});
	}
</script>
