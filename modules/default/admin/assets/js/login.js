$(document).ready(function(){

	//login form submit
	$("#loginBtn").click(function(){
		if(!$("#username").val()||!$("#password").val()){
			$("#loginErr").html(" Please enter your username and password!");
			$("#loginErr").show();
			return;
		}
		$("form#loginFrm").submit();
	});

	//forgot password
	$("#forgotPassForm .btnCancel").click(function(){
		$("#forgotPassForm").hide();
		$("#login").show();
	});
	$("#forgotBtn").click(function(){
		if(!$("#email").val()){
			$("#message").css("color","#ff0000");
			return;
		}
		$.post("index.php",{email: $("#email").val()} , function(data){
			if(data){
				$("#message").css("color","#555555");
				$("#forgotError").html(data).show();
			}else{
				$("#forgotPassForm .form").hide();
				$("#forgotPassForm .success").show();
				$("#forgotPassForm").addClass("forgotPassFormSuccess");
			}
		});
	});

	$("#forgotAccess").click(function(){
		$("#login").hide();
		$("#forgotPassForm").show();
	});

	$("#password").keyup(function(e){
		var key = e.which;
		if(key==13){
			if(!$("#username").val()||!$("#password").val()){
				$("#loginErr").html(" Please enter your username and password!");
				$("#loginErr").show();
				return;
			}
			$("form#loginFrm").submit();
		}
	});
});