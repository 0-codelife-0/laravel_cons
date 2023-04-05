<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Document</title>
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
	{{-- Modal --}}
	<div class="modal fade" id="modal">
		<div class="modal-dialog">
			<form action="" method="" id="form">
				@csrf
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title">Todo Modal</h4>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						{{-- Content Here --}}
						<label for="todo">Todo</label>
						<div class="col-lg-12">
							<div class="input-group">
								<input type="text" name="todo" class="form-control rounded-0" id="todo" placeholder="Todo">
							</div>
						</div>
					</div>
					<div class="modal-footer justify-content-between">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Save</button>
					</div>
				</div>
			</form>
		</div>
	</div>

	{{-- Datatable --}}
	<div class="card card-primary card-outline">
		<div class="card-header">
			<button class="btn col-1 float-right" id="add">Add</button>
		</div>
		<div class="card-body">
			<table id="datatable" class="display" style="width:100%">
				<thead>
					<tr>
						<th>Todo</th>
						<th>Action</th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
</body>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script>
	$(document).ready(function() {
		var base_url = window.location.origin + '/todos/';
		var url = '';

		var datatable_instance = $("#datatable").DataTable({
			"order": [
				[0, 'Asc']
			],
			"ajax": {
				url: base_url + 'index',
				method: 'GET',
				dataType: 'JSON',
			},
			"columns": [{
				data: 'todo'
			}, {
				data: 'id',
				render: function(data, meta, row) {
					return '<a data-id="' + data +
						'" title="Edit" style="cursor: pointer;" class="edit">Edit</a> | ' +
						'<a data-id="' + data +
						'" title="Delete" style="cursor: pointer;" class="delete">Delete</a>';
				}
			}],
		});

		// On click Add
		$('#add').on('click', function(e) {
			e.preventDefault();
			url = base_url + 'add';
			$('#modal').modal('show');
		});

		// On Form submit
		$(document).on('submit', '#form', function(e) {
			e.preventDefault();
			var data = new FormData(this);
			$.ajax({
				url: url,
				method: 'POST',
				data: data,
				type: 'json',
				contentType: false,
				processData: false,
				cache: false,
				success: function(data, textStatus, jqXHR) {
					Swal.fire({
						icon: data.icon,
						title: data.icon,
						text: data.title
					});$('#modal').modal('hide');
					datatable_instance.ajax.reload();
				},
				error: function(xhr, status, error) {
					Swal.fire({
						icon: data.icon,
						title: data.icon,
						text: data.title
					});$('#modal').modal('hide');
					datatable_instance.ajax.reload();
				}
			});
		});

		// On Click Edit
		datatable_instance.on('click', '.edit', function(e) {
			e.preventDefault();
			var dataId = $(this).attr('data-id');
			url = base_url + 'edit/' + dataId;
			$.ajax({
				url: url,
				method: 'GET',
				dataType: 'JSON',
				success: function(data, textStatus, jqXHR) {
					$('#todo').val(data.todo);
					$('#modal').modal('show');
				},
				error: function(xhr, status, error) {
					Swal.fire({
						icon: data.icon,
						title: data.icon,
						text: data.title
					});
                    $('#modal').modal('hide');
					datatable_instance.ajax.reload();
				}
			});
		});

		// On Delete Function
		datatable_instance.on('click', '.delete', function(e) {
			e.preventDefault();
			var dataId = $(this).attr('data-id');
			var href = base_url + 'delete/' + dataId;
			Swal.fire({
				title: 'Delete Todo?',
				text: 'Are You Sure you want to delete this Todo?',
				icon: 'warning',
				animation: true,
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes',
				closeOnConfirm: false,
				closeOnCancel: false,
			}).then(function(result) {
				if (result.isConfirmed) {
					$.ajaxSetup({
						headers: {
							'X-CSRF-TOKEN': '{{ csrf_token() }}'
						}
					});
					$.ajax({
						url: href,
						type: 'DELETE',
						method: 'DELETE',
						dataType: 'JSON',
						success: function(data, textStatus, jqXHR) {
							Swal.fire({
								icon: data.icon,
								title: data.icon,
								text: data.title
							});
							datatable_instance.ajax.reload();
						},
						error: function(xhr, status, error) {
							Swal.fire({
								icon: data.icon,
								title: data.icon,
								text: data.title
							});
							datatable_instance.ajax.reload();
						}
					});
				}
			});
		});
	});
</script>

</html>
