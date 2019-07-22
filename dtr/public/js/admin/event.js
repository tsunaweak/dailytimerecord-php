$(document).ready(function(){
	var action = '';
	var select_id = '';
	eventTable();
	function eventTable(){
		action = 'display_event'
		$('#event_table').dataTable({
			"proccessing":true,
            "serverSide":true,
            "searching":false,
            "order":[],
            "ajax":{
                url:"display/event",
                type:"POST",
                data:{action:action}
            },
            "columnDefs":[
                {
                    "targets":[2, 3, 4],
                    "orderable":false,
                },
            ],
            "pageLength": 10,
            "bDestroy": true
		});	
	}
	$(document).on('click', '#btn_add_event', function(){
		$('#add_event_modal').modal('show');
		$('#event_start').val('');
		$('#event_end').val('');
		$('#event_add').hide();
        $('#modal_message_event').html('');
		$('#event_modal_title').html("Add Event");
		$('#btn_save_event').html("Save");
		$('#event_type').val('double');
	});
	$(document).on('change', '#event_type', function(){
		if($(this).val() == 'double'){
			$('#event_add').hide();
		}else{
			$('#event_add').show();
		}	
	});
	$('#event_start').datetimepicker({ 
      	modal: true,
      	uiLibrary: 'bootstrap4',
      	footer: true,
      	format: 'mmm dd, yyyy hh:MM TT'        
    });
    $('#event_end').datetimepicker({ 
       	modal: true,
      	uiLibrary: 'bootstrap4',
      	footer: true,
      	format: 'mmm dd, yyyy hh:MM TT'       
    });
    $(document).on('click', '#btn_save_event', function(){
    	let start = $('#event_start').val();
    	let end = $('#event_end').val();
    	let type = $('#event_type').val();
    	let btn = $(this).html();
    	if(type == 'additional'){
    		var add = $('#event_add_time').val();
    	}
    	if(btn == "Save"){
    		action = 'add_event';
    		$.ajax({
	    		url: "save/event",
	    		method:"POST",
	    		data:{action:action, start:start, end:end, type:type, add:add},
	    		dataType:"JSON",
	    		success:function(data){
	    			if(data.error != ''){
	    				$('#modal_message_event').html(data.error);
	    			}else{
	    				eventTable();
	    				$('#add_event_modal').modal('hide');
						$('#event_table_message').html(data.message);
                    }
	    		}
	    	});
    	}else if(btn == "Update"){
    		action = 'update_event';
    		$.ajax({
	    		url: "update/event",
	    		method:"POST",
	    		data:{action:action, start:start, end:end, type:type, add:add, select_id:select_id},
	    		dataType:"JSON",
	    		success:function(data){
	    			if(data.error != ''){
	    				$('#modal_message_event').html(data.error);
	    			}else{
	    				eventTable();
	    				$('#add_event_modal').modal('hide');
						$('#event_table_message').html(data.message);
                    }
	    		}
	    	});
    	}else{
    		$('#modal_message_event').html('<div class="alert alert-danger">Data failed to save.');	
    	}
    	
    });
    //show modal for confirmation to delete.
    $(document).on('click', '.delete', function(){
    	select_id = $(this).attr('id');
    	$('#pop_modal').modal('show');
    });
    //if the confirmation has approved
    $(document).on('click', '#btn_delete_trainee', function(){
    	action = 'delete_event';
    	$.ajax({
    		url: "delete/event",
    		method: "POST",
    		data:{action:action, select_id:select_id},
    		dataType:"JSON",
    		success:function(data){
    			if(data.error != ""){
					$('#pop_modal').modal('hide');
					$('#event_table_message').html(data.error);		
				}else{
					eventTable();
					$('#pop_modal').modal('hide');
					$('#event_table_message').html(data.message);
				}
    		}
    	});
    });
    //get data of the selected item
    $(document).on('click', '.edit', function(){
        select_id = $(this).attr('id');
    	$('#modal_message_event').html('');
        action = 'get_event';
    	$.ajax({
    		url: "get/event",
    		method: "POST",
    		data:{action:action, select_id:select_id},
    		dataType:"JSON",
    		success:function(data){
                $('#add_event_modal').modal('show');
				$('#event_modal_title').html("Update Event");
				$('#btn_save_event').html("Update");
				$('#event_start').val(data.event_start);
				$('#event_end').val(data.event_end);
				if(data.event_type != "1"){
					$('#event_type').val('additional');
					$('#event_add_time').val(data.event_add);
					$('#event_add').show();
				}else{
					$('#event_type').val('double');
					$('#event_add').hide();
				}		
    		}
    	});
    });
});