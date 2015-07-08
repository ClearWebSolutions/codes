define(["./../../app", "../gallery/gallery"], function(app, gallery){
	return {
		init: function(){

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

			$("#content_areas").change(function(){
				var cur = $(".rows>.form tr").length-4;
				if($(this).val()>cur){
					var add = $(this).val()-cur;
					var start = cur+1;
					var add = add+start;
					for(var i=start;i<add;i++)
						$(".rows>.form").append("<tr><td>Content "+i+":</td><td><input type=\"text\" name=\"content"+i+"\"/></td><td></td></tr>");
					app.rerender();
				}else{
					var remove = cur-$(this).val();
					var rows = $(".rows>.form tr");
					var start=4+parseInt($(this).val());
					for(var i=start;i<rows.length;i++)
						$(rows[i]).remove();
				}
			});

/*			$("input[name='multi_galleries']").click(function(){
				if($(this).val()==1){
					$("#exact_galleries").hide();
					var collection = $(".galleries>div");
					for(var i=3;i<collection.length;i++)
						$(collection[i]).remove();
				}else{
					$("#exact_galleries").show();
					app.rerender();
				}
			});*/

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
			
			gallery.init();

		}
	}
});
