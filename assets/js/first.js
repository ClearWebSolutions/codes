//the list are the required scripts/components to load before launchin the function
define(["popup"], function(popup) {
	$(document).ready(function(){

		//DELETE WEBSITE
		$(".deleteSmall").click(function(){
			var popupID = popup.create(this,'deleteWebsitePopup','','up', 5,-2);
			popup.show(popupID, this);
			var id = $(this).prev().attr('href').split("?id=");
			id = id[1];
			$("#"+popupID+" input[name='id']").val(id);
			$("#"+popupID+" .btnRed").click(function(){
				$("#"+popupID+" #deletesitefrm").submit();
			});
		});

		//NEW WEBSITE
		$(".newwebsite a").click(function(){
			var left = $(this).width()/2+20;
			var popupID = popup.create(this,'addNewWebsite','right','down',left,5);
			popup.show(popupID, this);
			$("#"+popupID+" #dbprefix").val($("#db_prefix").val());
			$(".sitename").html("sitename");
			$(".dbname").html("DB name");
			$("#"+popupID+" #sitename").keyup(function(){
				$(".sitename").html($(this).val());
			});
			$("#"+popupID+" #dbname").keyup(function(){
				$(".dbname").html($(this).val());
			});
			var addnewsitefrm = {
				beforeSubmit: function(){
					//check for the entered info
					var sitename = $("#"+popupID+" #sitename").val();
					if(!sitename){
						$("#"+popupID+" #error").html("Please enter site name!").show();
						return false;
					}else{
						if(sitename.indexOf(' ') >= 0){
							$("#"+popupID+" #error").html("Site name can not contain spaces!").show();
							return false;
						}
					}
					//check if the db_name was entered
					if($("#"+popupID+" #dbname").val()==''){
							$("#"+popupID+" #error").html("Please enter DB name!").show();
							return false;
					}else{
						if(sitename.indexOf(' ') >= 0){
							$("#"+popupID+" #error").html("DB name can't have spaces!").show();
							return false;
						}
					}
					//display loading
					$("#"+popupID+" #error").html("").show();
					$("#"+popupID+" .loading").show();
				},
				success: function(data){
					if(isNaN(data)){
						$("#"+popupID+" .loading").hide();
						$("#"+popupID+" #error").html(data).show();
						$("#"+popupID+" #hidden_error").val(data);
					}else{
						//goto just created site page
						window.location.href = 'site.php?id='+data;
					}
				}
			}; 
			$("#"+popupID+" #addnewsitefrm").ajaxForm(addnewsitefrm);
			$("#"+popupID+" #addnewsitefrm .btnGreen").click(function(){
				$("#addnewsitefrm").submit();
			});
		});

		//SETTINGS
		$("#settingsIcon").click(function(){
			$("#settingsfrm .error").hide();
			$("#settingsfrm .submitLine .updated").hide();
			//initial position
			$("#settings").find(".settingsCnt").css("left","480px");
			var h = $(document).height()-122;
			$("#settings").height(h);
			$("#settings").show();
			$("#settings").find(".settingsCnt").animate({left:"-=470"});
		});

		$("#settingsIconClose").click(function(){
			hideSettings();
		});

		$("#settings .btnCancel").click(function(){
			hideSettings();
		});

		var settingsfrmoptions = {
			beforeSubmit: function(){
				$("#settingsfrm .submitLine .updated").hide();
				if($("#protect1").attr("checked")=="checked"){
					if($("#password").val()!=$("#password2").val()){
						$("#settingsfrm .error").html("Passwords don't match!");
						$("#settingsfrm .error").show();
						return false;
					}
					if(!$("#username").val()){
						$("#settingsfrm .error").html("Username can't be empty!");
						$("#settingsfrm .error").show();
						return false;
					}
					if(!$("#password").val()){
						$("#settingsfrm .error").html("Password can't be empty!");
						$("#settingsfrm .error").show();
						return false;
					}
				}
			},
			success: function(data){
				$("#settingsfrm .error").hide();
				$("#settingsfrm .submitLine .updated").show();
				if($("#protect1").attr("checked")=="checked"){
					$("#realprotect").val(1);
				}else{
					$("#realprotect").val(0);
				}
			}
		}; 
		$("#settingsfrm").ajaxForm(settingsfrmoptions);
		$("#settingsfrm .btnGreen").click(function(){
			$("#settingsfrm").submit();
		});



	});
});


function hideSettings(){
	$("#settings").find(".settingsCnt").animate({left:"+=470"}, 600, function(){
			$("#settings").hide();
			$("#settingsfrm .submitLine .updated").hide();
			if($("#realprotect").val()!=1){
				$("#protect1").removeAttr("checked");
				$("#protect2").attr("checked","checked");
				$("#username").val("");
				$("#password").val("");
				$("#password2").val("");
				$("#protect").hide();
			}else{
				$("#protect").show();
				$("#protect2").removeAttr("checked");
				$("#protect1").attr("checked","checked");
			}
	});
}