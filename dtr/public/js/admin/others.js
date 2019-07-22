$(document).ready(function(){
	var action = '';
	getOthers();
	function getOthers(){
		action = 'get_others';
		$.ajax({
			url: "get/others",
			method: "POST",
			data: {action:action},
			dataType:"JSON",
			success:function(data){
				$('#timeDeduct').val(data.deduct_time);
				$('#afterLogin').val(data.after_login);
				$('#afterLogout').val(data.after_logout);
			}
		});
	}
	//update time deduction
	$(document).on('click', '#btntimeDeduct', function(){
		let time = $('#timeDeduct').val();
		action = 'update_deduct';
		$.ajax({
			url:"update/deduct",
			method:"POST",
			data:{action:action, time:time},
			dataType:"JSON",
			success:function(data){
				if(data.error != ""){
					$('#configMessage').html(data.error);
				}else{
					$('#configMessage').html(data.message);
					getOthers();
				}	
			}
		});
	});
	//update after login
	$(document).on('click', '#btnafterLogin', function(){
		let time = $('#afterLogin').val();
		action = 'update_afterlogin';
		$.ajax({
			url:"update/afterlogin",
			method:"POST",
			data:{action:action, time:time},
			dataType:"JSON",
			success:function(data){
				if(data.error != ""){
					$('#configMessage').html(data.error);
				}else{
					$('#configMessage').html(data.message);
					getOthers();
				}
			}
		});
	});
	//update after logout
	$(document).on('click', '#btnafterLogout', function(){
		let time = $('#afterLogout').val();
		action = 'update_afterlogout';
		$.ajax({
			url:"update/afterlogout",
			method:"POST",
			data:{action:action, time:time},
			dataType:"JSON",
			success:function(data){
				if(data.error != ""){
					$('#configMessage').html(data.error);
				}else{
					$('#configMessage').html(data.message);
					getOthers();
				}
			}
		});
	});
	//show modal
	$(document).on('click' , '#show_modal', function(){
		$('#account_modal').modal('show');
		$('#username').val('');
		$('#password').val('');
	});
	//change password
	$(document).on('click' , '#btn_save_account', function(){
		action = 'account';
		let uname = $('#username').val();
		let pword = $('#password').val();
		$.ajax({
			url: "account",
			method : "POST",
			data:{uname:uname, pword:pword, action:action},
			dataType:"JSON",
			success:function(data){
				if(data.error != ""){
					$('#message').html(data.error);
				}else{
					$('#username').val('');
					$('#password').val('');	
					$('#message').html(data.message);
				}
			}
		});
	});
	//solo login 
	$(document).on('click', '#btn-check', function(){
		var uname = $('#solouser').val();
		var stamp = $('#solostart').val();
		action = 'solo_check';
		$.ajax({
			url: "solocheck",
			method: "POST",
			data:{uname:uname, stamp:stamp, action:action},
			dataType: "JSON",
			success:function(data){
				if(data.error != ""){
					$('#checkMessage').html(data.error);
				}else{
					$('#checkMessage').html(data.message);
					$('#solostart').val('');
				}
			}
		});
	});
	//upload excel record sheet
	$(document).on('click', '#btn_bulk', function(){
		action = 'bulk_check';
		uname = $('#bulkUname').val();
		let file = $('#datasheet').prop('files')[0];
		let formData = new FormData();
		formData.append('file', file);
		formData.append('action', action);
		formData.append('uname', uname);
		$.ajax({
			url: "upload/datasheet",
			dataType: "JSON",
			cache: false,
			contentType: false,
			processData: false,
			data: formData,
			type: "POST",
			beforeSend:function(){
				$('#checkMessage').html('<div class="alert alert-primary"><i class="fas fa-spinner fa-spin"></i> Reading the file...</div>');
			},
			success:function(data){
				if(data.error != ""){
					$('#checkMessage').html(data.error);
				}else{
					$('#checkMessage').html(data.message);
					$('#bulkUname').val('');
				}
			}
		});
	});
;});