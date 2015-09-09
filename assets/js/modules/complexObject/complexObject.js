define(["./../../app", "../gallery/gallery"], function(app, gallery){
	return {
		init: function(){
			//accordion like sections for galleries and categories
			this.accordionSections();

			//ability to change/add amount of galleries
			this.galleriesInit();

			//ability to add more categories
			this.categoriesInit();

			//everything about the fields 
			this.fieldRowsHandlers();

			this.titleListener();
			this.multilanguageListener();

			var that = this;
			$(window).resize(function(){
				that.rerender();
			});
			this.rerender();
		},

		multilanguageListener:function(){
			$("input[name='multilanguage']").change(function(){
				if($(this).val()==0){
					$(".multilanguage").parent().hide();
				}else{
					$(".multilanguage").parent().show();
				}
			});
		},

		titleListener: function(){
			$("input[name='title']").keyup(function(){
				var val = $(this).val().NormalizeVar().toLowerCase();
				$("input[name='db_tbl']").val(val);
			});
			$("input[name='title']").change(function(){
				var val = $(this).val().NormalizeVar().toLowerCase();
				$("input[name='db_tbl']").val(val);
			});
		},

		accordionSections: function(){
			$('.categories li .cat .title').click(function() {
				$(this).parent().parent().find(">.subcats").animate({
					opacity: 1,
					height: 'toggle'
				}, 400, 'linear', function(){
					app.rerender();
				});
				
				if($(this).parent().hasClass("catSelected")){
					$(this).parent().removeClass("catSelected");
				}else{
					$(this).parent().addClass("catSelected");
				}
				return false;
			});
			$('.subcats').hide();
		},

		galleriesInit: function(){
			$("#galleries_amnt").change(function(){
				var new_amt = $(this).val();
				var amt = $(".galleries>div").length/2;
				if(amt>new_amt){
					//delete from bottom
					var amount = (amt-new_amt)*2;
					var collection = $(".galleries>div");
					for(var i=collection.length-amount;i<collection.length;i++){
						$(collection[i]).remove();
					}
				}else{
					//add to bottom
					amt++;
					$.post("module.php", {action:'getGalleryFrm', from: amt, amt: new_amt}, function(data){
						$(".galleries").append(data);
						$("#scroll select").selectmenu();
						gallery.init();
						app.rerender();
					});
				}
			});
			$("input[name='name1']").change(function(){
				$("input[type=radio][name=gallery][value=1]").attr("checked", "checked");
			});
			gallery.init();
		},

		categoriesInit:function(){
			var that = this;
			$(".addCategory").click(function(){
				var index = $(this).parent().find("table tr").length+1;
				var select = $(this).parent().find("table tr td select").html();
				var code = "<tr><td>Category "+index+"</td><td><select name=\"category"+index+"\">"+select+"</select></td><td><input type=\"checkbox\" value=\"1\" name=\"category"+index+"_admindisplay\" class=\"admindisplay\"/> Admin Display <input type=\"checkbox\" value=\"1\" name=\"category"+index+"_requried\" class=\"required\"/> Required <a class=\"deleteSmall ml20\"></a></td></tr>";
				$(this).parent().find("table").append(code);
				$(this).parent().find("select[name='category"+index+"']").selectmenu();
				$("input[name='categoriesttl']").val(index);
				that.deleteCategoryListener();
			});
		},

		deleteCategoryListener: function(){
			$("table.categories td:nth-child(3) .deleteSmall").unbind("click").click(function(){
				$(this).parent().parent().remove();
				//reindex
				var rows = $("table.categories").find('tr');
				for(var i=0;i<rows.length;i++){
					var index =i+1;
					$(rows[i]).find("td:first-child").html("Category "+index);
					$(rows[i]).find("select").attr("name","category"+index).selectmenu();
					$(rows[i]).find("input.admindisplay").attr("name","category"+index+"_admindisplay");
					$(rows[i]).find("input.required").attr("name","category"+index+"_required");
				}
				$("input[name='categoriesttl']").val($("input[name='categoroiesttl']").val()*1-1);
			});
		},

		fieldRowsHandlers:function(){
			this.addFieldListener();
			this.changeTypeListener();
			this.changeDBTypeListener();
			this.fieldTitleListener();
			this.deleteFieldListener();
		},

		deleteFieldListener: function(){
			$("td:nth-child(7) .deleteSmall").unbind("click").click(function(){
				$(this).parent().parent().remove();
				//reindex
				var rows = $("#fieldsTable tr");
				for(var i=0;i<rows.length;i++){
					var index =i+1;
					$(rows[i]).find("input.fieldtitle").attr("name","title"+index);
					$(rows[i]).find("input.optionsttl").attr("name","optionsttl"+index);
					$(rows[i]).find("select.inputType").attr("name","type"+index).selectmenu();
					var optionvalues = $(rows[i]).find("td:nth-child(2) .options input");
					var optionnames = $(rows[i]).find("td:nth-child(3) .options input");
					for(var j=0;j<optionvalues.length;j++){
						var innerindex = j+1;
						$(optionvalues[j]).attr("name","optionvalue"+index+"_"+innerindex);
						$(optionnames[j]).attr("name","optionname"+index+"_"+innerindex);
					}
					$(rows[i]).find("input.dbfield").attr("name","dbfield"+index);
					$(rows[i]).find("input.dbtype").attr("name","dbtype"+index).selectmenu();
					$(rows[i]).find("input.dblength").attr("name","dblength"+index);
					$(rows[i]).find("input.dbdefault").attr("name","dbdefault"+index);
					$(rows[i]).find("input.required").attr("name","required"+index);
					$(rows[i]).find("input.searchable").attr("name","searchable"+index);
					$(rows[i]).find("input.admindisplay").attr("name","admindisplay"+index);
					$(rows[i]).find("input.multilanguage").attr("name","multilanguage"+index);
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
				row = row.replace("dbfield1","dbfield"+index);
				row = row.replace("dbtype1","dbtype"+index);
				row = row.replace("dblength1","dblength"+index);
				row = row.replace("dbdefault1","dbdefault"+index);
				row = row.replace("required1","required"+index);
				row = row.replace("searchable1","searchable"+index);
				row = row.replace("admindisplay1","admindisplay"+index);
				row = row.replace("multilanguage1","multilanguage"+index);
				//include the code
				$(this).parent().find("table").append(row);
				//update with default/empty values
				var rows = $(this).parent().find("table tr");
				$row = $(rows[rows.length-1]);
				$row.find("td:nth-child(2)>select").next().remove();
				$row.find("td:nth-child(4)>select").next().remove();
				$row.find(".options").remove();
				$row.find("select").selectmenu();
				$row.find("td input").val("");
				$row.find("td input[type=checkbox]").removeAttr("checked");
				$row.find("td input[type=checkbox].multilanguage").attr("checked", "checked");
				$row.find("td select.inputType").val("text");
				$row.find("td select.dbType").val("varchar");
				$row.find("select").selectmenu();
				$row.find("td input.dblength").val("255");

				$row.find("td input.multilanguage").attr("value", "1");
				$row.find("td input.required").attr("value", "1");
				$row.find("td input.searchable").attr("value", "1");
				$row.find("td input.admindisplay").attr("value", "1");

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
				if(type=='radio'){
					$row.find("td:first-child").append("<span class=\"optionsTitle options\"><input type=\"hidden\" name=\"optionsttl"+index+"\" value=\"1\" class=\"optionsttl\"/>Radio Options:<a class=\"deleteSmall\"></a></span>");
					$row.find("td:nth-child(2)").append("<span class=\"options\"><input type=\"text\" placeholder=\"option value\" class=\"mt5\" name=\"optionvalue"+index+"_1\" /><br/><a href=\"javascript:\" class=\"addOneMore\">Add One More Option</a></span>");
					$row.find("td:nth-child(3)").append("<span class=\"options\"><input type=\"text\" placeholder=\"option name\" class=\"mt5\" name=\"optionname"+index+"_1\"/></span>");
					$row.find("td:nth-child(4)").append("<span class=\"options\"><div><input type=\"checkbox\" class=\"mt5\" name=\"optiondefault"+index+"_1\" value=\"1\"/> default value</div></span>");
				}
				if(type=='select'){
					$row.find("td:first-child").append("<span class=\"optionsTitle options\"><input type=\"hidden\" name=\"optionsttl"+index+"\" value=\"1\" class=\"optionsttl\"/>Select Options:<a class=\"deleteSmall\"></a></span>");
					$row.find("td:nth-child(2)").append("<span class=\"options\"><input type=\"text\" placeholder=\"option value\" class=\"mt5\" name=\"optionvalue"+index+"_1\"/><br/><a href=\"javascript:\" class=\"addOneMore\">Add One More Option</a></span>");
					$row.find("td:nth-child(3)").append("<span class=\"options\"><input type=\"text\" placeholder=\"option name\" class=\"mt5\" name=\"optionname"+index+"_1\"/></span>");
					$row.find("td:nth-child(4)").append("<span class=\"options\"><div><input type=\"checkbox\" class=\"mt5\" name=\"optiondefault"+index+"_1\" value=\"1\"/> default value</div></span>");
				}
				//attach addonemoreoption listener
				that.addOptionsListener();

				$dbtype = $(this).parent().next().next().find("select");
				$dblength = $(this).parent().next().next().next().find("input");
				switch(type){
					case "text": $dbtype.val("varchar"); $dblength.val("255"); break;
					case "date": $dbtype.val("date"); $dblength.val(""); break;
					case "password": $dbtype.val("varchar"); $dblength.val("255"); break;
					case "radio": $dbtype.val("tinyint"); $dblength.val("1"); break;
					case "select": $dbtype.val("varchar"); $dblength.val("255"); break;
					case "textarea": $dbtype.val("blob"); $dblength.val(""); break;
					case "html": $dbtype.val("blob"); $dblength.val(""); break;
				}
				$(this).parent().next().next().find("select").selectmenu();
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
				var code1 = "<input type=\"text\" placeholder=\"option name\" class=\"mt5\" name=\""+name.replace("value","name")+"_"+index+"\"/>"; 
				var code2 = "<div><input type=\"checkbox\" class=\"mt5\" name=\""+name.replace("value","default")+"_"+index+"\" value=\"1\"/> default value</div>"; 
				$(this).parent().parent().parent().find("td:first-child .options").append("<a class=\"deleteSmall\"></a>");
				$(this).parent().find("br").before(code);
				$(this).parent().parent().parent().find("td:nth-child(3) .options").append(code1);
				$(this).parent().parent().parent().find("td:nth-child(4) .options").append(code2);
				//update counter
				$(this).parent().parent().parent().find("td:first-child .options input").val($(this).parent().parent().parent().find("td:first-child .options input").val()*1+1);
				app.rerender();
				that.deleteOptionListener();
			});
		},

		deleteOptionListener:function(){
			$(".options .deleteSmall").unbind("click").click(function(){
				var index = $(this).parent().find(".deleteSmall").index($(this));
				$($(this).parent().parent().parent().find("td:nth-child(2) .options input")[index]).remove();
				$($(this).parent().parent().parent().find("td:nth-child(3) .options input")[index]).remove();
				$($(this).parent().parent().parent().find("td:nth-child(4) .options div")[index]).remove();
				//reindex option names
				var inputs = $(this).parent().parent().parent().find("td:nth-child(2) .options input");
				for(var i=0; i<inputs.length;i++){
					var v = inputs[i];
					var j = i+1;
					$(v).attr("name", $(v).attr('name').split("_")[0]+"_"+j);
				}
				var inputs = $(this).parent().parent().parent().find("td:nth-child(3) .options input");
				for(var i=0; i<inputs.length;i++){
					var v = inputs[i];
					var j = i+1;
					$(v).attr("name", $(v).attr('name').split("_")[0]+"_"+j);
				}
				//update counter
				$(this).parent().parent().parent().find("td:first-child .options input").val($(this).parent().parent().parent().find("td:first-child .options input").val()*1-1);
				$(this).remove();
			});
		},

		changeDBTypeListener: function(){
			$(".dbType").unbind("change").change(function(){
				$dblength = $(this).parent().next().find("input");
				switch($(this).val()){
					case "varchar": $dblength.val("255"); break;
					case "int": $dblength.val("11"); break;
					case "tinyint": $dblength.val("1"); break;
					case "double": $dblength.val(""); break;
					case "date": $dblength.val(""); break;
					case "datetime": $dblength.val(""); break;
					case "timestamp": $dblength.val(""); break;
					case "blob": $dblength.val(""); break;
				}
			});
		},

		fieldTitleListener: function(){
			$(".fieldtitle").keyup(function(){
				var val = $(this).val().NormalizeVar().toLowerCase();
				$($(this).parent().next().next().find("input")[0]).val(val);
			});
			$(".fieldtitle").change(function(){
				var val = $(this).val().NormalizeVar().toLowerCase();
				$($(this).parent().next().next().find("input")[0]).val(val);
			});
		},
		
		rerender: function(){
			$("#fieldsTable").css("width", $(".categories").width()+35+"px");
		}

	}
});