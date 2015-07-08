define(["popup", "pages"], function(popup, pages){
	return {
	
		init: function(){
			//listen to the add click
			this.addNewModuleListener();
			
			//listen to the select click
			this.selectListener();
			
			//listen to page select
			this.selectPageListener();
			
		},

		selectPageListener: function(){
			var modules = this;
			$(".psmenu").click(function(e){
				e = e || window.event;
				var target = e.target || e.srcElement;
				
				if($(target).attr('pageid')){
					$(".psmenu a").removeClass("selected");
					$(target).addClass("selected");
					
					pages.selected.name = $(target).html();
					pages.selected.id = $(target).attr('pageid');
					pages.trigger("changed",[]);
					
					modules.loadPageModules(pages.selected.id);

					//clearing the right side
					var api = $("#scroll").data('jsp');
					api.getContentPane().html('');
					api.reinitialise();
					//in case we are in the add module setting the selectbox to empty
					$("#selectmodule select").val('');
					$("#selectmodule select").selectmenu();

				}
			});
		},

		loadPageModules: function(pid){
			var modules = this;
			//load modules
			$.post("site.php",{action:"loadModules", pageid: pid}, function(data){
				//api stands for jScrollPane api
				var api = $(".msmenu").data('jsp');
				api.getContentPane().html(data);
				api.reinitialise();
				modules.selectListener();
			});
		},

		rerender: function(){
			var h = $(window).height()-122;
			$(".ms").height(h);
			$(".ms>.msmenu").height(h-55);
			if($("#selectmodule").css("display")=="block"){
				$(".m").height(h-10-65);
			}else{
				$(".m").height(h-10-10);
			}
			var w = parseInt($(window).width()*0.9-420-20-14+4);
			$(".m").width(w);
			if($('.scroll-pane').length>0){
				$('.scroll-pane').jScrollPane({verticalDragMinHeight: 50, verticalDragMaxHeight: 50});
			}
		},
		
		addNewModuleListener: function(){
			var modules = this;
			$("#addModule").click(function(){

				//check if any page is selected, display error if not
				if(pages.selected.id==false){
					var popupID = popup.create(this, 'addModuleErrorPopup', 'left', 'down', 105, -90);
					popup.show(popupID, this);
					return;
				}
				$(".msmenu a").removeClass("selected");
				$("#selectmodule").html($("#selectmoduleoptions").html());
				$("#selectmodule select").selectmenu({width:200});
				$("#selectmodule").show();
				modules.rerender();

				//api stands for jScrollPane api
				var api = $("#scroll").data('jsp');

				api.getContentPane().html('');
				api.reinitialise();

				$("#selectmodule select").change(function(){
					modules.loadNewModule($(this).val());
				});
			});
			
		},
		
		selectListener: function(){
			var modules = this;
			$(".msmenu a").click(function(){
				$(".msmenu a").removeClass("selected");
				$(this).addClass("selected");
				modules.loadModule($(this).attr('moduleid'), $(this).attr('modulename'));
			});
		},
		
		loadModule: function(mid, name){
			var modules = this;
			$.post("module.php", {action: "loadModule",mid:mid}, function(data){
				//if module add dialog open remove it
				$("#selectmodule").hide();
				
				var api = $("#scroll").data('jsp');
				api.getContentPane().html(data);
				api.reinitialise();
				$("#page").val(pages.selected.id);
				$("#scroll select").selectmenu();
				//loading the module's specific script
				var mn ="modules/"+name+"/"+name; 
				require([mn],function(module){
					module.init();
				});

				//ajax form submit for the edit module form
				var editmodulefrmoptions = {
					dataType: 'json',//return datatype
					success: function(data){
						if(data.success){
							$("#moduleSuccess").show();
							$("#editmodule").hide();
						}else{
							$("#moduleError>.error").html(data.error);
							if(data.warning){$("#warning").val(1);}
							$("#moduleError").show();
						}
						var api = $("#scroll").data('jsp');
						api.reinitialise();
						api.scrollToY(0);
					}
				}; 
				$("#editmodule").ajaxForm(editmodulefrmoptions);
				$("#editmodule .btnGreen").click(function(){
					$("#editmodule").submit();
				});
			});
		},
		
		loadNewModule: function(name){
			var modules = this;
			if(name==''){
				var api = $("#scroll").data('jsp');
				api.getContentPane().html('');
				api.reinitialise();
			}else{
			$.post("module.php",{action: "new", module:name, pageid: pages.selected.id}, function(data){
					//api stands for jScrollPane api
					var api = $("#scroll").data('jsp');
					api.getContentPane().html(data);
					api.reinitialise();
					//listening to page change to update the selected page name for #selectedpage span
					$("#selectedpage").html(pages.selected.name);
					$("#page").val(pages.selected.id);
					pages.bind("changed",function(){
						$("#selectedpage").html(pages.selected.name);
						$("#page").val(pages.selected.id);
					});
					$("#scroll select").selectmenu();
					//ajax form submit for the add module form
					var addmodulefrmoptions = {
						dataType: 'json',//return datatype
						success: function(data){
							if(data.success){
								$("#moduleSuccess").show();
								modules.loadPageModules(pages.selected.id);
								$("#addmodule").hide();
							}else{
								$("#moduleError>.error").html(data.error);
								if(data.warning){$("#warning").val(1);}
								$("#moduleError").show();
							}
							var api = $("#scroll").data('jsp');
							api.reinitialise();
							api.scrollToY(0);
						}
					}; 
					$("#addmodule").ajaxForm(addmodulefrmoptions);
					$("#addmodule .btnGreen").click(function(){
						$("#addmodule").submit();
					});
					
					//loading the module's specific script
					var mn ="modules/"+name+"/"+name; 
					require([mn],function(module){
						module.init();
					});
			});
			}

		}
	}
});