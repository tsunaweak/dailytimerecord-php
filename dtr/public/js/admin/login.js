$(document).ready(function(){
	$(document).on('submit', '#loginForm', function(e){
		e.preventDefault();
		let uname  = $('#username').val();
		let pword = $('#password').val();
		let action = 'login';
		$.ajax({
			url:"login",
			method: "POST",
			data:{uname:uname, pword:pword, action:action},
			dataType:"JSON",
			success:function(data){
				if(data.error != ""){
					$('#message').html(data.error);
				}else{
					window.location.href = data.message;
				}
			}
		});
	});
});