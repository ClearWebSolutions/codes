var jquerymenu={

	animateduration: {over: 200, out: 100}, //duration of slide in/ out animation, in milliseconds
	submenucntr: 0,
	zindexcntr:10000,

	buildmenu:function(menuid){
		$(document).ready(function($){
			var $mainmenu=$("#"+menuid+">ul");
			var $headers=$mainmenu.find("ul").parent();
			$headers.each(function(i){
				var $curobj=$(this);
				var $subul=$(this).find('ul:eq(0)');
				this._dimensions={w:this.offsetWidth, h:this.offsetHeight, subulw:$subul.outerWidth(), subulh:$subul.outerHeight()}
				this.istopheader=$curobj.parent().parent().hasClass('menu')? 1 : 0;
				$subul.css({top:this.istopheader? this._dimensions.h+"px" : 0});
				//adding right arrow class to the elements with children
				if(!this.istopheader)	$curobj.children("a:eq(0)").addClass('rightArrw');
				$curobj.hover(
					function(e){
						//mouseover
						$(this).attr('over',1);
						if(!$(this).attr("submenuid")||$(this).attr("submenuid")==""){
							$(this).addClass('selected');
							var $targetul=$(this).children("ul:eq(0)");
							this._offsets={left:$(this).offset().left, top:$(this).offset().top};
							if(this.istopheader){
								menuleft = this._offsets.left-$mainmenu.parent().offset().left+this._dimensions.w/2-this._dimensions.subulw/2;
							}else{
								menuleft = this._dimensions.w + this._offsets.left-$mainmenu.parent().offset().left+this._dimensions.w/2-this._dimensions.subulw/2;
							}
							jquerymenu.submenucntr++;
							var div = "<div class='submenu' id='submenu"+jquerymenu.submenucntr+"' over='0'><ul>"+$targetul.html()+"</ul></div>";
							$mainmenu.parent().append(div);

							$targetul = $('#submenu'+jquerymenu.submenucntr).children("ul:eq(0)");
							jquerymenu.buildmenu("submenu"+jquerymenu.submenucntr);
							$(this).attr("submenuid","submenu"+jquerymenu.submenucntr);
							$targetul.parent().hover(
								function(e){
									//mouseover
									$(this).attr('over', 1);
									//$(this).parent().attr('over',1);
								},function(){
									//mouseout
									$(this).attr('over',0);
									var li = $(this).parent().find("li.selected");
									if($(li).attr("over")==0){
										$(li).removeClass("selected");
										$(li).attr("submenuid","");
										$(li).attr("over",0);
										$(this).remove();
									}
								}
							);
							if(!this.istopheader){
								menutop = this._offsets.top-$targetul.offset().top;
								$targetul.css({top:menutop+"px"});
							}
							$targetul.css({left:menuleft+"px", zIndex:jquerymenu.zindexcntr--}).show();
						}
					}, function(e){
						//mouseout
						$(this).attr("over",0);
						var id = $(this).attr("submenuid");
						var li = $(this);
						setTimeout(function(){
							//check if we still are over submenu
							if($("#"+id).attr('over')==0){
								$(li).removeClass('selected');
								$(li).attr("submenuid","");
								$("#"+id).remove();
							}
						},100);
					}
				); //end hover
			}); //end $headers.each()
			$mainmenu.find("ul").css({display:'none', visibility:'visible'});
	
		}); //end document.ready
	}

}

//build menu with ID="menu" on page:
jquerymenu.buildmenu("menu")