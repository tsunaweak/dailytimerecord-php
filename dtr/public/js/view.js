$(document).ready(function(){
	var action = '';
	var select_id = '';
	$(document).on('click', '#btn-view', function(){
		let uname = $('#username').val();
		action = 'view';
		$.ajax({
			url: "view/records",
			method: "POST",
			data:{action:action, uname:uname},
			dataType:"JSON",
			success:function(data){
				if(data.error != ""){
					$('#view_result').html(data.error);
				}else{
					$("#view_result").html(data.output);
				}
			}
		});
	});
	// $(document).on('click', '.export', function(){
	// 	action = 'export_excel';
	// 	select_id = $(this).attr('id');
	// 	$.ajax({
	// 		url: "export/excel",
	// 		method: "POST",
	// 		data:{action:action,  select_id:select_id},
			
	// 	})
	// });
});