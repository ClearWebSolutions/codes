$(document).ready(function(){

	//SELECT INPUT
	if($("select").length>0){
		$("select").selectmenu();
	}


	//DATE INPUT
	if($(".date").length>0){
		$(".date").datepicker({
			showOn: "both",
			buttonImage: "assets/imgs/calendar.png",
			buttonImageOnly: true,
			showAnim: "fadeIn",
			showOtherMonths: true,
			selectOtherMonths: true
		});
	}


	//POPUP LISTENERS (not needed as we are doing the more targeted listeners)
	//TABLE ACTIONS LISTENERS
	$(".datatable .delete").click(function(){
		var p = $(this).attr('popup');
		popup.create(this,p?p:'deletePopup','','down',-18,-25);
		//and ajax form submit for DATA TABLES
		$("#"+popup.id).find("#id").val($(this).attr('objectid'));
		var row = $(this).parent().parent();
		var deloptions = {
			success: function(data){
				popup.remove();
				$(row).remove();
			}
		};
		$("#"+popup.id+" #deletefrm").ajaxForm(deloptions);
		$("#"+popup.id+" .btnRed").click(function(){
			$("#"+popup.id+" #deletefrm").submit();
		});
	});
	$("input.locked").click(function(){
		var locked;
		var oid = $(this).attr('oid');//object id
		if($(this).attr('checked')=='checked'){locked=1;}else{locked=0;}
		var ajaxurl = $(this).attr('ajaxurl');
		var ajaxaction = $(this).attr('ajaxaction');
		var classname = $("input[name='classname']").val();
		$.post(ajaxurl, {action: ajaxaction, id: oid, locked: locked, classname:classname}, function(){});
	});
	$("input.ordr").change(function(){
		var oid = $(this).attr('oid');//object id
		var ajaxurl = $(this).attr('ajaxurl');
		var ajaxaction = $(this).attr('ajaxaction');
		var ordr = $(this).val();
		var classname = $("input[name='classname']").val();
		$.post(ajaxurl, {action: ajaxaction, id: oid, ordr: ordr, classname:classname}, function(){});
	});
	$("th.sortable").click(function(){
		var location = document.location.href;
		if(location.indexOf('?')!=-1){
			location = location.split('?');
			document.location.href = location[0]+"?classname="+$("input[name='classname']").val()+"&order_by="+$(this).attr("order_by");
		}else{
			document.location.href = location+"?classname="+$("input[name='classname']").val()+"&order_by="+$(this).attr("order_by");
		}
	});


	//MULTI-LANGUAGE TABS
	if($("#tabs_menu").length>0){
		$("#tabs").tabs({
			create: function(event, ui){
				var lis = $(this).find('.ui-tabs-nav li');
				var width = 0;
				for(var i=0;i<lis.length;i++){
					width+=$(lis[i]).width();
				}
				width = parseInt(width+lis.length)+10;
				$(this).find('.ui-tabs-nav').css('width',width+"px");
			}
		});
	}

	CKEditorFix();
});

function CKEditorFix(){
	if($(".cke_skin_codes").css("display")){
		$(".cke_skin_codes").css("display","block");
	}else{
		setTimeout("CKEditorFix()",100);
	}
}

//sometimes I'm nervous and I need to quickly submit the form...
function submitFrm(id){
	$("#"+id).submit();
}