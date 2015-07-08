define(["popup"], function(popup){
	return {

		init: function(){
			//listen to the website title click
			this.addSettingsListener();
		},

		addSettingsListener:function(){
			$(".newwebsite a").click(function(){
				if(!$(this).hasClass('back2websites')){
					var left = $(this).width()/2+20;
					var popupID = popup.create(this,'websiteSettings','right','down',left,5);

					//creating the jquery ajax form submit
					var settingsfrm = {
						beforeSubmit: function(){
							if(!$("#"+popupID+" input[name='name']").val()){
								$("#"+popupID+" .error").html("Please enter site name!").show();
								return false;
							}
						},
						success: function(data){
							if(isNaN(data)){
								$("#"+popupID+" .success").hide();
								$("#"+popupID+" .error").html(data).show();
							}else{
								$("#"+popupID+" .error").hide();
								$("#"+popupID+" .success").html("Site name updated.").show();
							}
						}
					};
					$("#"+popupID+" #settingsfrm").ajaxForm(settingsfrm);
					$("#"+popupID+" #settingsfrm .btnGreen").click(function(){
						$("#"+popupID+" #settingsfrm").submit();
					});

					popup.show(popupID, this);
				}
			});
		}

	}
});