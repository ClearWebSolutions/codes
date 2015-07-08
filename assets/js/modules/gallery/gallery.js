define(["./../../app"], function(app){
	return {
		init: function(){
							$(".galleryamount").change(function(){
								var id = $(this).attr('id').substr(1,$(this).attr('id').length);
								//check if the rows were already added before
								if($('#galleryimgssettings'+id).length>0){
									if($(this).val()>=$("#galleryimgssettings"+id+" tr").length){
										//add more rows
										var rows ='';
										for(var i=$("#galleryimgssettings"+id+" tr").length;i<=$(this).val();i++){
											rows += "<tr><td><input type='text' name='suffix"+id+"_"+i+"'/></td><td><input type='text' name='width"+id+"_"+i+"'/></td><td><input type='text' name='height"+id+"_"+i+"'/></td><td><input type='radio' name='cut"+id+"_"+i+"' value='1'/>Yes<input type='radio' name='cut"+id+"_"+i+"' checked='checked' value='0'/>No</td></tr>";
										}
										$('#galleryimgssettings'+id).append(rows);
									}else{
										//remove extra rows
										var deletefrom = $(this).val();
										var currentrow = 0;
										$('#galleryimgssettings'+id+' tr').each(function(){
											if(currentrow>deletefrom){
												$(this).remove();
											}
											currentrow++;
										});
									}
								}else{
									var rows = "<table class='galleryimgssettings' id='galleryimgssettings"+id+"'><tr><th>Suffix</th><th>Width</th><th>Height</th><th>Cut</th></tr>";
									for(var i=1;i<=$(this).val();i++){
										rows += "<tr><td><input type='text'name='suffix"+id+"_"+i+"'/></td><td><input type='text' name='width"+id+"_"+i+"'/></td><td><input type='text' name='height"+id+"_"+i+"'/></td><td><input type='radio' name='cut"+id+"_"+i+"' value='1'/>Yes<input type='radio' name='cut"+id+"_"+i+"' checked='checked' value='0'/>No</td></tr>";
									}
									rows += "</table>";
									$("#galleryfrm"+id).after(rows);
								}
								app.rerender();
							});
			}
	}
});