$(document).ready(function(){
	var action = '';
	$('#event_container').hide();
	setInterval(getDate, 900);
	getEvent();
	function getDate(){
		action = 'get_date';
		$.ajax({
			url:    "get/date",
			method: "POST",
			data:{action:action},
			dataType: "JSON",
			success:function(data){
				$('#dateNow').html(data.datenow);
			}
		});
	}
	function getEvent(){
		action = 'get_eventlist';
		$.ajax({
			url: "get/eventlist",
			method:"POST",
			data:{action:action},
			dataType:"JSON",
			success:function(data){
				if(data.row > 0){
					$('#event_container').show();
					$('#table_body').html(data.table);
				}else{
					$('#margin').css('margin-top', 200);
				}
			}
		});
	}
	$(document).on('click', '#check', function(){
		action = 'check';
		let uname = $('#username').val();
		$.ajax({
			url: "action/check",
			method: "POST",
			data:{action:action, uname:uname},
			dataType: "JSON",
			success:function(data){
				if(data.error != ""){
					$('#indexMessage').html(data.error);
				}else{
					$('#indexMessage').html(data.message);
					$('#username').val('');
				}
			}
		});
	});
});