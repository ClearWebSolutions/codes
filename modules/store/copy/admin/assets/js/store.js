$(document).ready(function(){

	var that = this;

	this.updateStockListener = function(){
		$(".updateStock").unbind('click').click(function(){
			//get stockid if exists
			var id = $(this).parent().parent().attr("stockid");
			//get price
			var price = $(this).parent().parent().find("td:first-child input").val();
			//get qty
			var qty = $(this).parent().parent().find("td:nth-child(2) input").val();
			//get options
			var tds = $(this).parent().parent().find("td");
			var options = [];
			for(var i=2;i<tds.length-1;i++){
				var option = $(tds[i]).find("select").length?$(tds[i]).find("select"):$(tds[i]).find("input");
				options.push({id: $(option).attr("option"), value:$(option).val()});
			}
			//do the update request
			var td = $(this).parent();
			$(this).remove();
			$(td).html("<span class=\"loading\"></span>");
			var pid = $("input[name='id']").val();
			$.post("stock.api.php", {action:'update', id: id, pid:pid, price:price, qty:qty, options: options}, function(data){
				if(data.error){
					alert(data.errorMsg);
				}else{
					$(td).parent().attr("stockid", data.stockid);
				}
				$(td).html("<a href=\"javascript:\" class=\"btnGrey updateStock\">Update</a>");
				that.updateStockListener();
			}, 'json');
		});
	};
	
	$(".stockAdd").click(function(){
		var tds = $(".stocktable tbody tr:last-child td");
		var html = '';
		for(var i=0;i<tds.length;i++){
			if($(tds[i]).find("select").length>0){
				html += "<td><select option=\""+$(tds[i]).find("select").attr("option")+"\">"+$(tds[i]).find("select").html()+"</td>";
			}else{
				if(i==tds.length-1){
					html += "<td><a href=\"javascript:\" class=\"btnGrey updateStock\">Add</a></td>";
				}else{
					html += "<td>"+$(tds[i]).html()+"</td>";
				}
			}
		}
		$(".stocktable tbody").append("<tr stockid=\"\">"+html+"</tr>");
		$(".stocktable tbody tr:last-child td:nth-child(n) input").val("");
		$(".stocktable tbody tr:last-child td:nth-child(n) select").val("");
		$(".stocktable tbody tr:last-child td:nth-child(n) select").removeAttr("style");
		$(".stocktable tbody tr:last-child td:nth-child(n) select").selectmenu();
		that.updateStockListener();
	});

	$("#updateStatusFrm .btnGrey").click(function(){
		var ids = '';
		var cbs = $(".status");//checkboxes
		for(var i=0;i<cbs.length;i++){
			ids += $(cbs[i]).attr("checked")?$(cbs[i]).attr("oid")+",":"";
		}
		ids = ids?ids.substring(0, ids.length-1):'';
		$("#updateStatusFrm input[name='ids']").val(ids);
		$("#updateStatusFrm input[name='status']").val($("select[name='status']").val());
		$("#updateStatusFrm").submit();
	});

	$("#updateStatus").click(function(){
		$(this).removeClass("btnGrey").addClass("loading").html("Updating...");
		$.post("orders.php", {action:"updateStatus", ids:$("input[name='id']").val(), newstatus:$("select[name='newstatus']").val()}, function(){
			$("#updateStatus").removeClass("loading").addClass("btnGrey").html("Update");
		});

	});

	this.updateStockListener();

});