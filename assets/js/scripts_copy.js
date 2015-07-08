var popupID = 0;

$(document).ready(function(){

	//SITES page code
	//delete box for deleting website
/*	$(".deleteSmall").click(function(){
		var id = createPopup(this,'deleteWebsitePopup','','up', 5,-2);
		showPopup(id, this);
	});*/

	//SITE page code
	//page select listener
/*	$(".psmenu").click(function(e){
		e = e || window.event;
		var target = e.target || e.srcElement;
		$(".psmenu a").removeClass("selected");
		$(target).addClass("selected");
		if($("#selectedpage").length>0){
			$("#selectedpage").html($(target).html());
			$("#page").val($(target).html());
		}
		//load modules
		$.post("site.php",{action:"loadModules", pageid: $(target).attr("pageid")}, function(data){
			//api stands for jScrollPane api
			var api = $(".msmenu").data('jsp');
			api.getContentPane().html(data);
			api.reinitialise();
		});
	});*/

/*	$(".newwebsite a").click(function(){
		if(!$(this).hasClass('back2websites')){
			left = $(this).width()/2+20;
			var id = createPopup(this,'addNewWebsite','right','down',left,5);
			showPopup(id, this);
		}
	});*/
/*	$("#addPage").click(function(){
		var id = createPopup(this,'addNewPage','left','down',105,-90);
		showPopup(id, this);
		//instantiate the selectbox in just created popup
	});
	$("#addModule").click(function(){
		//check if any page is selected, display error if not
		if($(".psmenu a.selected").length==0){
			var id = createPopup(this,'addModuleErrorPopup','left','down', 105,-90);
			showPopup(id, this);
			return;
		}
		if($("#selectmodule a.selectBox").length){
			$("#selectmodule").find("select").selectBox('destroy');
		}
		$("#selectmodule").html($("#selectmoduleoptions").html());
		setcolumns();
		$("#selectmodule").show();
		$("#selectmodule").find("select").selectBox();
		//api stands for jScrollPane api
		var api = $("#scroll").data('jsp');
		api.getContentPane().html('');
		api.reinitialise();
		$("#selectmodule select").selectBox().change(function(){
			loadModule($(this).val());
		});
	});*/


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
/*	$("input[name='protect']").click(function(){
		if($(this).val()==1){
			$("#protect2").removeAttr("checked");
			$("#protect1").attr("checked","checked");
			$("#username").val("");
			$("#password").val("");
			$("#password2").val("");
			$("#protect").show();
		}else{
			$("#protect1").removeAttr("checked");
			$("#protect2").attr("checked","checked");
			$("#protect").hide();
		}
	});*/
	//settings form
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

	//BOTH PAGES need this
/*	setcolumns(0);
	$(window).resize(function(){
		setcolumns();
	});*/
});


/* FUNCTIONS #######################################################*/
/*function setcolumns(initial){
	if(initial!=0){initial=1;}
	var h = $(window).height()-122;
	$(".ps").height(h);
	$(".ps>.psmenu").height(h-55);
	$(".ms").height(h);
	$(".ms>.msmenu").height(h-55);
	if($("#selectmodule").html()){
		$(".m").height(h-10-65);
	}else{
		$(".m").height(h-10-10);
	}
	var w = parseInt($(window).width()*0.9-420-20-initial*14+4);

	$(".m").width(w);
	if($('.scroll-pane').length>0){
		$('.scroll-pane').jScrollPane({verticalDragMinHeight: 50, verticalDragMaxHeight: 50});
	}

	//if settings is open we need to adjust it's height as well...
	var h = $(document).height()-122;
	$("#settings").height(h);
}

function showPopup(id, el){
	$("#"+id).show();
	$(el).attr('inactiveclass',$(el).attr('class'));
	$(el).attr('class',$(el).attr('class')+'Selected');
}

function createPopup(el, popup, positionx, positiony, gammax, gammay){

	var triydelta = 11; //the delta padding for triangle of the popup
	var trixdelta = 70;//the x position of the triangle in the popup
	var triwidth = 14;
	var gammax = gammax?gammax:0;
	var gammay = gammay?gammay:0;
	var positionx = positionx?positionx:'left';
	var positiony = positiony?positiony:'down';

	//allow only one popup on page
	$(".popup").each(function(){
		var $caller = $("a[callerOf='"+$(this).attr('id')+"']");
		$caller.attr('class', $caller.attr('inactiveclass'));
		$caller.attr('callerOf','');
		$(this).remove();
	});
	popupID++;
	var popupClass = $("#"+popup).attr('class');
	var popupTemplate = $("#"+popup).html();
	var popup = "<div class='"+popupClass+" popup' id='popup"+popupID+"'>"+popupTemplate+"</div>";
	$(el).after(popup);
	if($("#popup"+popupID+" select").length>0) $("#popup"+popupID+" select").selectBox();
	$popup =  $("#popup"+popupID);

		//if the popup is for adding a page
		if($popup.hasClass("addNewPage")){
			var addpagefrm = {
				beforeSubmit: function(){
					if(!$("#popup"+popupID+" input[name='name']").val()){
						$("#popup"+popupID+" .error").html("Please enter page name!").show();
						return;
					}
					if(!$("#popup"+popupID+" select[name='template']").val()){
						$("#popup"+popupID+" .error").html("Please select template!").show();
						return;
					}
				},
				success: function(data){
					if(isNaN(data)){
						$("#popup"+popupID+" .error").html(data).show();
					}else{
						//add page to pages list and close popup
						var pagename = $("#popup"+popupID+" input[name='name']").val();
						if(pagename.length>30){
							pagename = pagename.substr(0,19)+"..."+pagename.substr(pagename.length-8, 8);
						}
						$(".psmenu")
						//api stands for jScrollPane api
						var api = $(".psmenu").data('jsp');
						api.getContentPane().html("<a href='javascript:' pageid='"+data+"'>"+pagename+".php</a>"+api.getContentPane().html());
						api.reinitialise();
						$popup.remove();
					}
				}
			};
			$("#popup"+popupID+" #addpagefrm").ajaxForm(addpagefrm);
			$("#popup"+popupID+" #addpagefrm .btnGreen").click(function(){
				$("#popup"+popupID+" #addpagefrm").submit();
			});
		}

		//if the popup is to delete the website
		if($popup.hasClass('deleteWebsitePopup')){
			var id = $(el).prev().attr('href').split("?id=");
			id = id[1];
			$("#popup"+popupID+" input[name='id']").val(id);
			$("#popup"+popupID+" .btnRed").click(function(){
				$("#popup"+popupID+" #deletesitefrm").submit();
			});
		}

		//if the popup is for adding the new site
		$("#popup"+popupID+" #dbprefix").val($("#db_prefix").val());
		$(".sitename").html("sitename");
		$(".dbname").html("DB name");
		$("#popup"+popupID+" #sitename").keyup(function(){
			$(".sitename").html($(this).val());
		});
		$("#popup"+popupID+" #dbname").keyup(function(){
			$(".dbname").html($(this).val());
		});
		var addnewsitefrm = {
			beforeSubmit: function(){
				//check for the entered info
				var sitename = $("#popup"+popupID+" #sitename").val();
				if(!sitename){
					$("#popup"+popupID+" #error").html("Please enter site name!").show();
					return;
				}else{
					if(sitename.indexOf(' ') >= 0){
						$("#popup"+popupID+" #error").html("Site name can not contain spaces!").show();
						return;
					}
				}
				//check if the db_name was entered
				if(!$("#popup"+popupID+" #dbname").val()){
						$("#popup"+popupID+" #error").html("Please enter DB name!").show();
						return;
				}else{
					if(sitename.indexOf(' ') >= 0){
						$("#popup"+popupID+" #error").html("DB name can't have spaces!").show();
						return;
					}
				}
				//display loading
				$("#popup"+popupID+" .loading").show();
			},
			success: function(data){
				if(isNaN(data)){
					$("#popup"+popupID+" .loading").hide();
					$("#popup"+popupID+" #error").html(data).show();
				}else{
					//goto just created site page
					window.location.href = 'site.php?id='+data;
				}
			}
		}; 
		$("#popup"+popupID+" #addnewsitefrm").ajaxForm(addnewsitefrm);
		$("#popup"+popupID+" #addnewsitefrm .btnGreen").click(function(){
			$("#addnewsitefrm").submit();
		});


	//position the popup properly
		if($popup.height()+$(el).offset().top+100>$(document).height()){
			positiony='up';
		}
		if($(el).offset().top-$popup.height()<0){
			positiony='down';
		}
		if(positiony=='top'||positiony=='up'||positiony=='bottom'||positiony=='down'){
			if($('body').hasClass('ie7')){
				if($(el).hasClass('deleteSmall')||$(el).hasClass('deleteSmallSelected')){
					gammax=-trixdelta-triwidth+1;gammay=triydelta+4;
				}else{
					gammax = -25; gammay = triydelta+4;
				}
			}
			var left = $(el).offset().left-$(el).parent().offset().left-trixdelta-triwidth/2+gammax;
			if($(el).parent().offset().left+left+$popup.width()-gammax<$(document).width()&&positionx!='right'){
				positionx='left';
			}else{
				left = $(el).offset().left-$(el).parent().offset().left-$popup.width()+trixdelta+triwidth+gammax;
				positionx='right';
			}
			var f = positionx.charAt(0).toUpperCase();
			var triPos = f + positionx.substr(1);
			if(positiony=='top'||positiony=='up'){
				var top = ($(el).offset().top-$(el).parent().offset().top)-$popup.height()-$(el).height()-triydelta+gammay;
				$popup.find(">.tri").addClass('triDown'+triPos);
			}
			if(positiony=='bottom'||positiony=='down'){
				var top = ($(el).offset().top-$(el).parent().offset().top)+triydelta+gammay;
				$popup.find(">.tri").addClass('triUp'+triPos);
			}
		}
		$popup.css({"marginTop":top+"px", "marginLeft":left+"px"});

	//close method for popup
	$(el).attr('callerOf',"popup"+popupID);
	$("#popup"+popupID+" .btnCancel").click(function(){
		$popup.remove();
		$(el).attr('class',$(el).attr('inactiveclass'));
	});
	return "popup"+popupID;
}*/

/*function hideSettings(){
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
}*/
/*
function loadModule(name){
	$.post("module.php",{action: "new", module:name}, function(data){
			//api stands for jScrollPane api
			var api = $("#scroll").data('jsp');
			api.getContentPane().html(data);
			api.reinitialise();
			$("#selectedpage").html($(".psmenu a.selected").html());
			$("#page").val($(".psmenu a.selected").html());
			$("select.selectBox").selectBox();
			//ajax form submit for the add module form
			var addmodulefrmoptions = {
				success: function(data){
					if(data=='success'){
						$("#moduleSuccess").show();
						$("#addmodule").hide();
					}else{
						$("#moduleError>.error").html(data);
						$("#moduleError").show();
					}
				}
			}; 
			$("#addmodule").ajaxForm(addmodulefrmoptions);
			$("#addmodule .btnGreen").click(function(){
				$("#addmodule").submit();
			});
				//////////////////////////////////////////////////////////////////////////////
				//MODULES SPECIFIC
				//GALLERY
				if(name=='gallery'){
					$("#galleryamount").change(function(){
						//check if the rows were already added before
						if($('.galleryimgssettings').length>0){
							if($(this).val()>=$(".galleryimgssettings tr").length){
								//add more rows
								var rows ='';
								for(var i=$(".galleryimgssettings tr").length;i<=$(this).val();i++){
									rows += "<tr><td><input type='text' name='suffix"+i+"'/></td><td><input type='text' name='width"+i+"'/></td><td><input type='text' name='height"+i+"'/></td><td><input type='radio' name='cut"+i+"' value='1'/>Yes<input type='radio' name='cut"+i+"' checked='checked' value='0'/>No</td></tr>";
								}
								$('.galleryimgssettings').append(rows);
							}else{
								//remove extra rows
								var deletefrom = $(this).val();
								var currentrow = 0;
								$('.galleryimgssettings tr').each(function(){
									if(currentrow>deletefrom){
										$(this).remove();
									}
									currentrow++;
								});
							}
						}else{
							var rows = "<table class='galleryimgssettings'><tr><th>Suffix</th><th>Width</th><th>Height</th><th>Cut</th></tr>";
							for(var i=1;i<=$(this).val();i++){
								rows += "<tr><td><input type='text'name='suffix"+i+"'/></td><td><input type='text' name='width"+i+"'/></td><td><input type='text' name='height"+i+"'/></td><td><input type='radio' name='cut"+i+"' value='1'/>Yes<input type='radio' name='cut"+i+"' checked='checked' value='0'/>No</td></tr>";
							}
							rows += "</table>";
							$("table.form").after(rows);
						}
					});
				}

	})
}

function submitFrm(id){
	$("#"+id).submit();
}*/