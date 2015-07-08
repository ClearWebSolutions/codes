define(["popup"], function(popup){
	return {
		
		selected: {name: '', id: ''},
		
		init: function(){
			//listen to the add click
			this.addNewPageListener();
		},
		
		rerender: function(){
			var h = $(window).height()-122;
			$(".ps").height(h);
			$(".ps>.psmenu").height(h-55);
		},
		
		addNewPageListener:function(){
			$("#addPage").click(function(){

				//create popup
				var popupID = popup.create(this, 'addNewPage', 'left', 'down', 105, -90);

				//creating the jquery ajax form submit
				var addpagefrm = {
					beforeSubmit: function(){
						if(!$("#"+popupID+" input[name='name']").val()){
							$("#"+popupID+" .error").html("Please enter page name!").show();
							return;
						}
						if(!$("#"+popupID+" select[name='template']").val()){
							$("#"+popupID+" .error").html("Please select template!").show();
							return;
						}
					},
					success: function(data){
						if(isNaN(data)){
							$("#"+popupID+" .error").html(data).show();
						}else{
							//add page to pages list and close popup
							var pagename = $("#"+popupID+" input[name='name']").val();
							if(pagename.length>30){
								pagename = pagename.substr(0,19)+"..."+pagename.substr(pagename.length-8, 8);
							}
							//api stands for jScrollPane api
							var api = $(".psmenu").data('jsp');
							api.getContentPane().html("<a href='javascript:' pageid='"+data+"'>"+pagename+".php</a>"+api.getContentPane().html());
							api.reinitialise();
							popup.remove();
						}
					}
				};
				$("#"+popupID+" #addpagefrm").ajaxForm(addpagefrm);
				$("#"+popupID+" #addpagefrm .btnGreen").click(function(){
					$("#"+popupID+" #addpagefrm").submit();
				});

				//display popup
				popup.show(popupID, this);
			
			});
		},
		
/*		selectListener: function(){
			var pages = this;
			$(".psmenu").click(function(e){
				e = e || window.event;
				var target = e.target || e.srcElement;
				$(".psmenu a").removeClass("selected");
				$(target).addClass("selected");
				
				pages.selected.name = $(target).html();
				pages.selected.id = $(target).attr('pageid');
				pages.trigger("changed",[]);
				
				pages.loadModules(pages.selected.id);
			});
		},*/
		
		trigger: function(evt, extra){
			$("body").trigger("pages"+evt, extra);
		},
		
		bind: function(evt, fn){
			$("body").bind("pages"+evt, fn)
		}/*,
		
		loadModules: function(pid){
			//load modules
			$.post("site.php",{action:"loadModules", pageid: pid}, function(data){
				//api stands for jScrollPane api
				var api = $(".msmenu").data('jsp');
				api.getContentPane().html(data);
				api.reinitialise();
				//modules.selectListener();
			});
		}*/


	}
});