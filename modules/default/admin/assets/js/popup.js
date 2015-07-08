var popup = {

	uid: 0,
	caller: null,

	triydelta: 11,//the delta padding for triangle of the popup
	trixdelta: 70,//the x position of the triangle in the popup
	triwidth: 14,
	

	create: function(el, popupTemplateID, positionx, positiony, gammax, gammay){
		this.caller = el;
		if($(this.caller).attr('href')!="javascript:"&&$(this.caller).attr('href')!=""&&$(this.caller).attr('href')!="#"){return;}

		var gammax = gammax?gammax:0;
		var gammay = gammay?gammay:0;
		var positionx = positionx?positionx:'left';
		var positiony = positiony?positiony:'down';

		//remove any opened popup
		this.remove();

		//creating and placing the popup code
		this.uid++;
		this.id = "popup"+this.uid;
		var popup = "<div class='"+$("#"+popupTemplateID).attr('class')+" popup' id='"+this.id+"'>"+$("#"+popupTemplateID).html()+"</div>";
		$(this.caller).after(popup);
		$popup =  $("#"+this.id);
		$(this.caller).attr('callerOf', this.id);

		//close method for popup
		var that = this;
		$("#"+this.id+" .btnCancel").click(function(){
			that.remove();
		});

		//position the popup properly
		if($popup.height()+$(this.caller).offset().top+100>$(document).height()){ positiony='up'; }
		if($(this.caller).offset().top-$popup.height()<0){ positiony='down';}
		if(positiony=='top'||positiony=='up'||positiony=='bottom'||positiony=='down'){
			if($('body').hasClass('ie7')){
				if($(this.caller).hasClass('deleteSmall')||$(this.caller).hasClass('deleteSmallSelected')){
					gammax=-this.trixdelta-this.triwidth+1;gammay=this.triydelta+4;
				}else{
					gammax = -25; gammay = this.triydelta+4;
				}
			}
			var left = $(this.caller).offset().left-$(this.caller).parent().offset().left-this.trixdelta-this.triwidth/2+gammax;
			if($(this.caller).parent().offset().left+left+$popup.width()-gammax<$(document).width()&&positionx!='right'){
				positionx='left';
			}else{
				left = $(this.caller).offset().left-$(this.caller).parent().offset().left-$popup.width()+this.trixdelta+this.triwidth+gammax;
				positionx='right';
			}
			var f = positionx.charAt(0).toUpperCase();
			var triPos = f + positionx.substr(1);
			if(positiony=='top'||positiony=='up'){
				var top = ($(this.caller).offset().top-$(this.caller).parent().offset().top)-$popup.height()-$(this.caller).height()-this.triydelta+gammay;
				$popup.find(">.tri").addClass('triDown'+triPos);
			}
			if(positiony=='bottom'||positiony=='down'){
				var top = ($(this.caller).offset().top-$(this.caller).parent().offset().top)+this.triydelta+gammay;
				$popup.find(">.tri").addClass('triUp'+triPos);
			}
		}
		$popup.css({"marginTop":top+"px", "marginLeft":left+"px"});

		//show popup
		this.show();
	},
	
	show: function(){
		$("#"+this.id).show();
		$(this.caller).attr('inactiveclass',$(this.caller).attr('class'));
		$(this.caller).attr('class',$(this.caller).attr('class')+'Selected');
	},

	remove: function(){
		//allow only one popup on page
		$(".popup").each(function(){
			var $caller = $("a[callerOf='"+$(this).attr('id')+"']");
			$caller.attr('class', $caller.attr('inactiveclass'));
			$caller.attr('callerOf','');
			$(this).remove();
		});
	}

};