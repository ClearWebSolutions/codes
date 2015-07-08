define(function(){
	return {

		popupID: 0,

		create: function(el, popup, positionx, positiony, gammax, gammay){

			var triydelta = 11; //the delta padding for triangle of the popup
			var trixdelta = 70;//the x position of the triangle in the popup
			var triwidth = 14;
			var gammax = gammax?gammax:0;
			var gammay = gammay?gammay:0;
			var positionx = positionx?positionx:'left';
			var positiony = positiony?positiony:'down';

			//allow only one popup on page (callerOf is required to decorate the caller)
			$(".popup").each(function(){
				var $caller = $("a[callerOf='"+$(this).attr('id')+"']");
				$caller.attr('class', $caller.attr('inactiveclass'));
				$caller.attr('callerOf','');
				$(this).remove();
			});
			this.popupID++;
			var popup = "<div class='" + $("#"+popup).attr('class') + " popup' id='popup"+this.popupID+"'>" + $("#"+popup).html() + "</div>";
			$(el).after(popup);
//			if($("#popup"+this.popupID+" select").length>0) $("#popup"+this.popupID+" select").selectBox();
			if($("#popup"+this.popupID+" select").length>0) $("#popup"+this.popupID+" select").selectmenu();
			$popup =  $("#popup"+this.popupID);

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
			$(el).attr('callerOf',"popup"+this.popupID);
			$("#popup"+this.popupID+" .btnCancel").click(function(){
				$popup.remove();
				$(el).attr('class',$(el).attr('inactiveclass'));
			});
			return "popup"+this.popupID;
		},

		show: function(id, el){
			$("#"+id).show();
			$(el).attr('inactiveclass',$(el).attr('class'));
			$(el).attr('class',$(el).attr('class')+'Selected');
		},
		
		remove: function(){
			$(".popup").each(function(){
				var $caller = $("a[callerOf='"+$(this).attr('id')+"']");
				$caller.attr('class', $caller.attr('inactiveclass'));
				$caller.attr('callerOf','');
				$(this).remove();
			});
		}

	}
	
	
	
});