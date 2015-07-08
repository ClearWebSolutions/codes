	//STORE FEATURES
	$(".add2cart").click(function(){
		var pid = $(this).attr('pid');
		var qty = $("#qty"+pid).val();
		//get options
		var s = $("[option][pid='"+pid+"']");
		var options = new Array();
		for(var i=0;i<s.length;i++){
			options.push({name: $(s[i]).attr("option"), value: $(s[i]).val()});
		}
		$.post(siteurl+"store.api.php", {action:'add2cart', pid:pid, qty:qty, options:options}, function(data){
			//update cart on page
			if(data.success==1){
				$(".sc_items_ttl").html(data.items_ttl);
				$(".sc_price_ttl").html(data.ttl);
			}else{
				alert(data.error_msg);
			}
		}, 'json');
	});
	$(".sc_delete").click(function(){
		var pid = $(this).attr("pid");
		$.post(siteurl+"store.api.php",{action:'delete', pid:pid}, function(data){
			$(".sc_item[pid="+pid+"]").remove();
			$(".sc_ttl").html(data.ttl);
			$(".sc_items_ttl").html(data.items_ttl);
			$(".sc_price_ttl").html(data.ttl);
		}, 'json');
	});
	$("input.sc_qty").change(function(){
		//check if integer
		var qty = $(this).val();
		var pid = $(this).attr("pid");
		var index = $(this).attr("sci");
		if((parseFloat(qty) == parseInt(qty)) && !isNaN(qty)){}else{$(this).val($(this).attr('original'));qty = $(this).attr('original');return;}
		updateSCQty(pid, qty, index);
	});
	function updateSCQty(pid, qty, index){
		$.post(siteurl+"store.api.php", {action: 'update', pid: pid, qty:qty, index:index}, function(data){
			if(data.error==1){
				alert(data.error_msg);
				//update to original
				$(".sc_qty[pid="+pid+"]").val($(".sc_qty[pid="+pid+"]").attr("original"));
			}else{
				$(".sc_item_ttl[pid="+pid+"]").html(qty*data.price);
				$(".sc_ttl").html(data.ttl);
				$(".sc_items_ttl").html(data.items_ttl);
				$(".sc_price_ttl").html(data.ttl);
				$(".sc_qty[pid="+pid+"]").attr("original", qty);
			}
		}, 'json');
	}
	$("input[name='same_as_billing']").click(function(){
		if($(this).attr("checked")){
			$(".shipping_address").hide();
		}else{
			$(".shipping_address").show();
		}
	});


	var that = this;
	$("input.qty").change(function(){
		that.updatePrice($(this).attr("pid"));
	});
	$("[option]").change(function(){
		that.updatePrice($(this).attr("pid"));
	});
	this.updatePrice = function(pid){
		//check if qty is integer
		var qty = $("#qty"+pid).val();
		if((parseFloat(qty) == parseInt(qty)) && !isNaN(qty)){
			//get options
			var s = $("[option][pid='"+pid+"']");
			var options = new Array();
			for(var i=0;i<s.length;i++){
				options.push({name: $(s[i]).attr("option"), value: $(s[i]).val()});
			}
			$.post(siteurl+"store.api.php", {action:'checkStock', pid:pid, qty:qty, options: options}, function(data){
				if(data.error){
					alert(data.error_msg);
				}else{
					//update price
					$("#ttl_price"+pid).html(qty*data.price);
				}
			}, 'json');
		}else{
			$(this).val(1);
		}
	};