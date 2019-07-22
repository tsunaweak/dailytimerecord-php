$(document).ready(function(){
	var action = '';
	var select_id = '';
	traineeTable();	
	function traineeTable(){
		action = 'display_trainee';
		$('#trainee_table').DataTable({
			"proccessing":true,
            "serverSide":true,
            "searching":true,
            "order":[],
            "ajax":{
                url:"display/trainee",
                type:"POST",
                data:{action:action}
            },
            "columnDefs":[
                {
                    "targets":[4, 5, 6],
                    "orderable":false,
                },
            ],
            "pageLength": 10,
            "bDestroy": true
		});
	}
	$(document).on('click', '#btn_add_trainee', function(){
		$('#add_trainee_modal').modal('show');
		$("#btn_save_trainee").html("Save");
		$('#nhrs').val('');
		$('#uname').val('');
		$('#fname').val('');	
	});
	//save trainee
	$(document).on('click', '#btn_save_trainee', function(){
		let nhrs = $('#nhrs').val();
		let uname = $('#uname').val();
		let fname = $('#fname').val();
		if(nhrs == '' || uname == '' | fname == ''){
			$('#modal_message_trainee').html('<div class="alert alert-danger">Please input required data.</div>');
		}else{
			if($(this).html() == "Save"){
				action = 'add_trainee';
				$.ajax({
					url: "save/trainee",
					method: "POST",
					data:{action:action, uname:uname, fname:fname, nhrs:nhrs},
					dataType:"JSON",
					success:function(data){
						if(data.error != ""){
							$('#modal_message_trainee').html(data.error);
						}else{
							traineeTable();
							$('#add_trainee_modal').modal('hide');
							$('#table_message').html(data.message);
						}
					}
				});
			}
		}
	});
	//delete trainee
	$(document).on('click', '.delete', function(){
		select_id = $(this).attr('id');
		action = 'delete_trainee';
		$('#pop_modal').modal('show');
	});
	$(document).on('click', '#btn_delete_trainee', function(){
		if(select_id != ''){
			$.ajax({
				url:"delete/trainee",
				method:"POST",
				data:{action:action, select_id:select_id},
				dataType:"JSON",
				success:function(data){
					if(data.error != ""){
						$('#pop_modal').modal('hide');
						$('#table_message').html(data.error);		
					}else{
						traineeTable();
						$('#pop_modal').modal('hide');
						$('#table_message').html(data.message);
					}
				}
			});
		}else{
			$('#table_message').html('<div class="alert alert-warning">Please select data to delete.</div>');
		}
	});
	//get trainee data
	$(document).on('click', '.edit', function(){
		select_id = $(this).attr('id');
		action = 'get_trainee';
		$.ajax({
			url:    "get/trainee",
			method: "POST",
			dataType:"JSON",
			data:{action:action, select_id:select_id},
			success:function(data){
				$('#edit_fname').val(data.fname);
				$('#edit_uname').val(data.uname);
				$('#edit_cnhrs').val(data.nhrs);
				$('#edit_nhrs').val('0');
				$('#modal_message_trainee_edit').html('');
				$("#edit_trainee_modal").modal('show');
			}
		});
	});
	//update trainee data
	$(document).on('click', '#btn_update_trainee', function(){
		action = 'update_trainee';
		let edit_type = $('#edit_type').val();
		let edit_fname = $('#edit_fname').val();
		let edit_uname = $('#edit_uname').val();
		let edit_nhrs = $('#edit_nhrs').val();
		$.ajax({
			url: "update/trainee",
			method : "POST",
			data: {action:action, select_id:select_id, edit_type:edit_type, edit_fname:edit_fname, edit_uname:edit_uname, edit_nhrs},
			dataType:"JSON",
			success:function(data){
				if(data.error != ''){
					$('#modal_message_edit').html(data.error);
				}else{
					traineeTable();
					$('#table_message').html(data.message);
					$("#edit_trainee_modal").modal('hide');
				}
			}
		});
	});
	//view trainee data
	$(document).on('click', '.view', function(){
		select_id = $(this).attr('id');
		action = 'get_records';
		$.ajax({
			url: "get/records",
			method: "POST",
			data:{select_id:select_id, action:action},
			dataType: "JSON",
			success:function(data){
				if(data.error != ""){
					$('#table_message').html(data.error);
					console.log(data.error);
				}else{
					$('#records_modal').modal('show');
					$('#records_body').html(data.tbody);
					$('#record_fname').html(data.fname);
					$('#record_remt').html(data.rem_time);
					$('#record_rent').html(data.ren_time);
				}
			}
		});
	});
	//edit trainee record
	$(document).on('click', '.record_update', function(e){
		select_id = $(this).attr('id');
		action = 'get_records_data';
		$.ajax({
			url: "get/recorddata",
			method: "POST",
			data: {action:action, select_id:select_id},
			dataType: "JSOn",
			success:function(data){
				$('#start').val(data.start);
				$('#end').val(data.end);
				$('#update_record_modal').modal('show');
				$('#records_modal').modal('hide');
			}
		});
	});
	//update record data
	$(document).on('click', '#btn_update_record', function(){
		let start = $('#start').val();
		let end = $('#end').val();
		action = 'update_record';
		$.ajax({
			url: "update/recorddata",
			method: "POST",
			data:{action:action,select_id:select_id, start:start, end:end},
			dataType:"JSON",
			success:function(data){
				if(data.error != ""){
					$('#modal_message_record').html(data.error);
				}else{
					traineeTable();
					$('#update_record_modal').modal('hide');
					$('#table_message').html(data.message);
				}
			}
		});
	});
	//show modal to delete trainee record
	$(document).on('click', '.record_delete', function(){
		select_id = $(this).attr('id');
		$('#records_modal').modal('hide');
		$('#pop_modal_record').modal('show');
	});
	//delete trainee record
	$(document).on('click', '#btn_delete_record', function(){
		action = 'delete_record';
		$.ajax({
			url:"delete/records",
			method: "POST",
			data:{action:action, select_id:select_id},
			dataType:"JSON",
			success:function(data){
				if(data.error != ""){
					$('#table_message').html(data.error);
					$('#pop_modal_record').modal('hide');
				}else{
					traineeTable();
					$('#pop_modal_record').modal('hide');
					$('#table_message').html(data.message);
				}
			}
		});
	});
});