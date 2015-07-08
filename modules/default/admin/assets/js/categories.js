var categories = {
	hide: true,
	init: function(){
		var popup = window.popup;

		this.selectListener();

		if($("ul.categories").length>0){
			if(!this.dragsort) this.dragsort = $("ul.categories").dragsort({ dragSelector: "div.draggable", dragBetween: false, dragEnd: categories.saveOrder, placeHolderTemplate: "<li class='placeHolder'><div></div></li>" });
			if(!$("ul.categories").parent().hasClass('frmRow')){
				categories.adjustZIndex();
			}
		}
		
		//we need to add extra padding to the bottom to make sure the add/edit popups will display down!!!
		//approximate popup height
		var height = 140+languages.length*40
		$(".cats>ul.categories").css({"paddingBottom":height+"px"});


		$('.categories li .cat .title').unbind('click').click(function() {
			$(this).parent().parent().find(">.subcats").animate({
				opacity: 1,
				height: 'toggle'
			}, 400, 'linear');
			
			if($(this).parent().hasClass("catSelected")){
				$(this).parent().removeClass("catSelected");
			}else{
				$(this).parent().addClass("catSelected");
			}
			return false;
		});

		if(this.hide){ 
			$(".categories li .cat").each(function(){
				if($(this).hasClass("catSelected")){
					$(this).parent().find(">.subcats").animate({
						opacity: 1,
						height: 'toggle'
					}, 400, 'linear');
					$(this).removeClass("catSelected");
				}
			});
			$('.subcats').hide();
		}

		$(".cats .add").click(function(){
			popup.create(this,'addPopup','','down',-18,-10);
			categories.addListener();
		});
		$(".categories .add").click(function(){
			popup.create(this,'addPopup','right','down',-13,-21);
			categories.addListener();
		});
		$(".categories .edit").click(function(){
			popup.create(this,'editPopup','right','down',-13,-21);
			categories.editListener();
		});
		$(".categories .delete").click(function(){
			popup.create(this,'deletePopup','','down',-13,-21);
			categories.deleteListener();
		});
	},

	addListener: function(){
			//AJAX
			var parent_id = $(popup.caller).attr("parent_id");
			$popup = $("#"+popup.id);
			$popup.find("input[name='parent_id']").val(parent_id);
			var addOptions = {
				dataType: 'json',
				success: function(data){
						if(data.error){
							$popup.find(".error").show();
						}else{
							popup.remove();
							if($("ul.categories[parent_id=\""+parent_id+"\"] li").length<=0){$("ul.categories[parent_id=\""+parent_id+"\"]").html("");}
							$("ul.categories[parent_id=\""+parent_id+"\"]").prepend("<li cat_id=\""+data.id+"\"><div class=\"cat\"><div class=\"clear\"></div><div class=\"title\"><span class=\"icon\"></span><span class=\"titleTxt\">"+data.title+"</span></div><div class=\"actions\"><div class=\"action\"><a href=\"javascript:\" class=\"addCategory add\" parent_id=\""+data.id+"\"></a></div><div class=\"action\"><a href=\"javascript:\" class=\"edit\" cat_id=\""+data.id+"\"></a></div><div class=\"action\"><a href=\"javascript:\" class=\"delete\" cat_id=\""+data.id+"\"></a></div><div class=\"action\"><div class=\"draggable\"></div></div></div><div class=\"clear\"></div></div><div class=\"subcats hidden\"><ul class=\"categories\" parent_id=\""+data.id+"\"><span class=\"nosubcats\">No subcategories.</span></ul></div></li>");
							if(parent_id==0){categories.hide = true;}else{categories.hide=false;}
							categories.init();
						}
					}
			}; 
			$("#"+popup.id+" #addFrm").ajaxForm(addOptions);
			$("#"+popup.id+" .btnGreen").click(function(){$("#"+popup.id+" #addFrm").submit();});
	},

	editListener: function(){
			//AJAX
			var cat_id = $(popup.caller).attr("cat_id");
			$popup = $("#"+popup.id);
			$popup.find("#cat_id").val(cat_id);
			var tbl = $popup.find("input[name=\"tbl\"]").val();
			$popup.find(".loading").show();
			$.post("categories.php",{action:"getDetails", cat_id:cat_id, tbl: tbl}, function(data){
				for(i in languages){
					$popup.find("input[name='title-"+languages[i].id+"']").val(data.titles4admin[languages[i].id]);
				}
				$popup.find(".loading").hide();
			},"json");
			var editOptions = {
				dataType: 'json',
				success: function(data){
						if(data.error){
							$popup.find(".error").show();
						}else{
							popup.remove();
							$("ul.categories li[cat_id='"+cat_id+"']>.cat>.title>.titleTxt").html(data.title);
						}
					}
			}; 
			$("#"+popup.id+" #editFrm").ajaxForm(editOptions);
			$("#"+popup.id+" .btnGreen").click(function(){$("#"+popup.id+" #editFrm").submit();});
	},

	deleteListener: function(){
			//AJAX
			var cat_id = $(popup.caller).attr("cat_id");
			$popup = $("#"+popup.id);
			$popup.find("input[name='cat_id']").val(cat_id);
			var deleteOptions = {
				dataType: 'json',
				success: function(data){
							popup.remove();
							$("ul.categories li[cat_id=\""+cat_id+"\"]").remove();
					}
			}; 
			$("#"+popup.id+" #deleteFrm").ajaxForm(deleteOptions);
			$("#"+popup.id+" .btnRed").click(function(){$("#"+popup.id+" #deleteFrm").submit();});
	},

	adjustZIndex: function(){
		var i = 1000;
		$("ul.categories li").each(function(){
			i--;
			$(this).css('zIndex',i);
		});
	},

	saveOrder: function(){
		var order = $(this).parent().find("li").map(function(){return $(this).attr("cat_id");}).get().join(',');
		$.post("categories.php",{action:'saveOrder', order:order});
		categories.adjustZIndex();
	},
	
	selectListener: function(){
		$(".frmRow .category select").unbind('change').change(function(){
			var s = this;
			var arr = $(this).attr('name').split('_');
			var sname = "";
			for(var i=0;i<arr.length-1;i++){
				sname += arr[i]+"_";
			}
			sname = sname.substr(0, sname.length-1);
			var tbl = $($(this).parent().find("input")[1]).val();
			$cntr = $($(this).parent().find("input")[0]);
			$.post("categories.php", {action:'getChildren', tbl: tbl,cat_id: $(this).val()}, function(data){
				//clean the unneeded selectboxes first
				$(".frmRow .category select").each(function(){
					if($(this).index()>$(s).index()){
						$(this).remove(); //remove select itself
					}
				});
				var cntr = $(s).index()/2-1;
				//creating new select if they exist
				if(data.length>0){
					cntr = cntr*1+1;
					var ns = "&nbsp;<select name=\""+sname+"_level"+cntr+"\"><option value=\"\">&nbsp;</option>";
					for(var i=0;i<data.length;i++){
						ns += "<option value=\""+data[i].id+"\">"+data[i].title+"</option>";
					}
					ns += "</select>";
					$(s).parent().append(ns);

				}
				$cntr.val(cntr);
				$("select").selectmenu();
				categories.selectListener();
			}, 'json');
		});
	}
};


$(document).ready(function(){
	categories.init();
});