var gallery = {

	init: function(){
		var popup = window.popup;
		this.addGalleryListener();
		this.dragsorting();
		this.addImgListener();
		this.deleteImgListener();
		this.editImgListener();
	},

	dragsorting: function(){
		if($("ul.gallery").length>0){
			$("ul.gallery").dragsort({ dragSelector: "div.img", dragBetween: false, dragEnd: gallery.saveOrder, placeHolderTemplate: "<li class='placeHolder'><div></div></li>" });
		}
	},

	saveOrder:function(){
		$(".gallery").each(function(){
			//getting the id of the gallery and 	making ajax request to save
			var order = $(this).find('li').map(function() { return $(this).children().find("input").attr('id').substr(6); }).get().join(',');
			var gid  = $(this).attr('id').substr(6);
			$.post('gallery.php',{action:'saveOrder', order:order, gid: gid});
		});
	},


	addGalleryListener: function(){
		$(".addGallery").click(function(){
			var btn = $(this);
			var oe = $(this).parent().prev().hasClass('even')?'odd':'even';//odd/even
			$.post("gallery.php", {action: 'addGallery', gid: $(this).attr('gid'), g2o: $(this).attr('g2o'), oe: oe}, function(data){
				//adding new gallery html to the flow
				$(btn).parent().before(data);
				//reinitializing the delete and add buttons, edit is not needed as the newly added gallery is empty
				gallery.addImgListener();
				gallery.deleteImgListener();
				//apply the dragsorting, but only to the newly added gallery
				$(btn).parent().prev().find("ul.gallery").dragsort({ dragSelector: "div.img", dragBetween: false, dragEnd: gallery.saveOrder, placeHolderTemplate: "<li class='placeHolder'><div></div></li>" });
			});
		});
	},


	addImgListener: function(){
		$(".addImg").unbind('click').click(function(){
			popup.create(this,$(this).attr('popup'),'','down',-18,-10);
			var gid = $(this).attr('gid');
			var g2o = $(this).attr('g2o');
			var uploader = new qq.FileUploader({
				element: $("#"+popup.id).find('#file-uploader')[0],
				action: 'assets/js/fileuploader/imgsuploader.php',
				allowedExtensions: ['jpg', 'jpeg', 'png', 'gif'],
				params: {"gid": gid, "g2o": g2o},
				onComplete: function(id, fileName, responseJSON){
					//here we have the image uploaded and we need to put it to the grid of thumbs with checkbox edit link etc.
					if(responseJSON.success){
						htmlcode = '<li><div class="img"><img src="'+responseJSON.imgurl+'"/><div class="draggable"><input type="checkbox" id="delimg'+responseJSON.imgid+'"/><div id="imgdata'+responseJSON.imgid+'" class="hidden"><input type="hidden" name="imgid" value="'+responseJSON.imgid+'"/><div class="row"><label>Link</label><input type="text" name="gilink"/></div>'
						for(var i=0;i<languages.length;i++){
							htmlcode += '<div class="row"><label>';
							if(languages.length>1){
								htmlcode+=languages[i].title+' title';
							}else{
								htmlcode += 'Title';
							}
							htmlcode += '</label><input type="text" name="gititle-'+languages[i].id+'"/>';
							htmlcode += '</div>';
						}
						htmlcode += '</div><a href="javascript:" class="edit editImg" popup="editPopup'+g2o+'_'+gid+'" id="editimg'+responseJSON.imgid+'" gid="'+gid+'" g2o="'+g2o+'"></a></div></div></li>';
						$("ul#gallery"+g2o+"_"+gid).append(htmlcode);
						gallery.editImgListener("gallery"+g2o+"_"+gid);//only for newly added image
					}
				}
//				,debug: true
			});
		});
	},


	deleteImgListener:function(){
		$(".delImg").unbind('click').click(function(){
			popup.create(this,$(this).attr('popup'),'','down',-18,-10);
			var gid = $(this).attr('gid');
			var g2o = $(this).attr('g2o');
			var el = this;
			var list = "";
			$("#gallery"+g2o+"_"+gid+" li").each(function(i, elm) {
				var $cb = $(elm).find("input");//checkbox
				list += $cb.attr('checked')?(list==''?'':',')+$cb.attr('id').substr(6):'';
			});
			$("#"+popup.id+" #list"+g2o+"_"+gid).val(list);
			$("#"+popup.id+" .btnRed").click(function(){
				$('#loading'+gid).show();
				$("#"+popup.id+" #delimgfrm"+g2o+"_"+gid).submit();
			});
			var deloptions = {
				target: '#gallery'+g2o+"_"+gid,
				success: function(data){
							$('#loading'+g2o+'_'+gid).hide();
							$('#gallery'+g2o+'_'+gid).html(data);
							gallery.editImgListener("gallery"+g2o+"_"+gid);
							popup.remove();
							$(el).attr('class',$(el).attr('inactiveclass'));
						},
				clearForm: true
			}; 
			$("#"+popup.id+" #delimgfrm"+g2o+"_"+gid).ajaxForm(deloptions);
		});
	},


	editImgListener: function(id){
		if(id){
			$("#"+id+" .editImg").click(function(){
				gallery.editImgHandler(this);
			});
		}else{
			$(".editImg").click(function(){
				gallery.editImgHandler(this);
			});
		}
	},


	editImgHandler: function(el){
		popup.create(el,$(el).attr('popup'),'','down',0,-10);
		var gid = $(el).attr('gid');
		var g2o = $(el).attr('g2o');
		var imgid = $(el).parent().parent().find('img').attr('src');
		imgid = imgid.split('/');
		imgid = imgid[imgid.length-1];
		imgid = imgid.split('_')[0];
		$("#"+popup.id+" .prl10").html($("#imgdata"+imgid).html());
		$("#"+popup.id+" .btnGreen").click(function(){
			$('#loading'+g2o+'_'+gid).show();
			$("#"+popup.id+" #editimgfrm"+g2o+"_"+gid).submit();
		});
		var editoptions = {
			success: function(data){
						$('#loading'+g2o+'_'+gid).hide();
						$('#imgdata'+imgid).html(data);
						gallery.editImgListener("gallery"+g2o+"_"+gid);//only for newly added image
						popup.remove();
						$(el).attr('class',$(el).attr('inactiveclass'));
					}
		}; 
		$("#"+popup.id+" #editimgfrm"+g2o+"_"+gid).ajaxForm(editoptions);
	}

}

$(document).ready(function(){
	gallery.init();
});