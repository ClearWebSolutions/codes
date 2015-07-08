define(["./../../app"], function(app){
	return {
		init: function(){
			//everything about the fields 
			this.fieldRowsHandlers();
			this.addOptionsListener();
			this.deleteOptionListener();
		},

		fieldRowsHandlers:function(){
			this.addFieldListener();
			this.changeTypeListener();
			this.deleteFieldListener();
		},

		deleteFieldListener: function(){
			$("td:nth-child(5) .deleteSmall").unbind("click").click(function(){
				$(this).parent().parent().remove();
				//reindex
				var rows = $("#fieldsTable tr");
				for(var i=0;i<rows.length;i++){
					var index =i+1;
					$(rows[i]).find("input.fieldtitle").attr("name","title"+index);
					$(rows[i]).find("input.optionsttl").attr("name","optionsttl"+index);
					$(rows[i]).find("select.inputType").attr("name","type"+index).selectmenu();
					var optionvalues = $(rows[i]).find("td:nth-child(4) .options input");
					for(var j=0;j<optionvalues.length;j++){
						var innerindex = j+1;
						$(optionvalues[j]).attr("name","optionvalue"+index+"_"+innerindex);
					}
				}
				$("input[name='ttl']").val($("input[name='ttl']").val()*1-1);
			});
		},

		addFieldListener: function(){
			var that = this;
			$(".addField").unbind("click").click(function(){
				var index = $(this).parent().find("table tr:last-child>td>input").attr("name").substr(5)*1+1;
				$("input[name='ttl']").val(index);
				var row = "<tr>"+$(this).parent().find("table tr").html()+"</tr>";
				//replace all the input names
				row = row.replace("title1","title"+index);
				row = row.replace("type1","type"+index);
				//include the code
				$(this).parent().find("table").append(row);
				//update with default/empty values
				var rows = $(this).parent().find("table tr");
				$row = $(rows[rows.length-1]);
				$row.find("td:nth-child(2)>select").next().remove();
				$row.find(".options").remove();
				$row.find("select").selectmenu();
				app.rerender();
				that.fieldRowsHandlers();
			});
		},

		changeTypeListener: function(){
			var that = this;
			$(".inputType").unbind("change").change(function(){
				var type = $(this).val();
				var index = $(this).attr('name').substr(4);
				$row = $(this).parent().parent();
				$row.find(".options").remove();
				if(type=='select'){
					$row.find("td:nth-child(3)").append("<span class=\"optionsTitle options\"><input type=\"hidden\" name=\"optionsttl"+index+"\" value=\"1\" class=\"optionsttl\"/>Select Options:<a class=\"deleteSmall\"></a></span>");
					$row.find("td:nth-child(4)").append("<span class=\"options\"><input type=\"text\" placeholder=\"option\" class=\"mt5\" name=\"optionvalue"+index+"_1\"/><br/><a href=\"javascript:\" class=\"addOneMore\">Add One More Option</a></span>");
				}
				//attach addonemoreoption listener
				that.addOptionsListener();
			});
		},

		addOptionsListener: function(){
			var that = this;
			$(".addOneMore").unbind("click").click(function(){
				$(this).parent().parent().css("width",$(this).parent().parent().width()+"px");
				var collection = $(this).parent().find("input");
				var arr = $(collection[collection.length-1]).attr('name').split("_");
				var name = arr[0];
				var index = arr[1]*1+1;
				var code = "<input type=\"text\" placeholder=\"option value\" class=\"mt5\" name=\""+name+"_"+index+"\"/>";
				$(this).parent().parent().parent().find("td:nth-child(3) .options").append("<a class=\"deleteSmall\"></a>");
				$(this).parent().find("br").before(code);
				//update counter
				$(this).parent().parent().parent().find("td:nth-child(3) .options input").val($(this).parent().parent().parent().find("td:nth-child(3) .options input").val()*1+1);
				app.rerender();
				that.deleteOptionListener();
			});
		},

		deleteOptionListener:function(){
			$(".options .deleteSmall").unbind("click").click(function(){
				var index = $(this).parent().find(".deleteSmall").index($(this));
				console.log(index);
				$($(this).parent().parent().parent().find("td:nth-child(4) .options input")[index]).remove();
				//reindex option names
				var inputs = $(this).parent().parent().parent().find("td:nth-child(4) .options input");
				for(var i=0; i<inputs.length;i++){
					var v = inputs[i];
					var j = i+1;
					$(v).attr("name", $(v).attr('name').split("_")[0]+"_"+j);
				}
				//update counter
				$(this).parent().parent().parent().find("td:nth-child(3) .options input").val($(this).parent().parent().parent().find("td:nth-child(3) .options input").val()*1-1);
				$(this).remove();
			});
		}



	}
});