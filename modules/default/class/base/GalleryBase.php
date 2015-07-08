<?
set_time_limit(0);
ini_set("memory_limit","320M");

class GalleryBase{

	function GalleryBase($id=0, $g2o=0){
		$this->g2o = $g2o;//gallery <-to-> object id
		if($id){
			$this->id = $id;
			$this->init();
		}
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function init(){
		global $db, $settings;

		$q = $db->query("select folder, sizes, title from ".DBPREFIX."galleries where id='".$this->id."'");
		$row = $q->next_row();
		$this->title = stripslashes($row->title);
		$this->folder = BASEPATH."/".$row->folder;
		if($this->g2o) $this->folder.="/".$this->g2o;
		$this->url = URL."/".$row->folder;
		if($this->g2o) $this->url.="/".$this->g2o;

		// sizes field in DB is a CSV list of sizes to create with cut or not option in the following format
		// Example thumb:128:128:1; means create file with suffix _thumb 128x128 pixels and cut it to this size  ; is one block separator and : is property separator,  default and always present is admin:127:95:1;
		$sizes = explode(";",$row->sizes); 
		for($i=0;$i<sizeof($sizes);$i++){
			if(strstr($sizes[$i],":")){
				$arr = explode(":",$sizes[$i]);
				$this->sizes[] = array('suffix'=>$arr[0], 'width'=>$arr[1], 'height'=>$arr[2], 'cut'=>$arr[3]);
			}
		}

		$ttl = $db->query("select id, link, extension from ".DBPREFIX."images where gallery_id='".$this->id."' and g2o_id='".$this->g2o."' and language='en' order by ordr");
		$j=0;
		foreach($ttl->result() as $image){
			for($i=0;$i<sizeof($settings->languages);$i++){//this extra cycle ensures that we have the array values even empty ones for all the images
				$iq = $db->query("select title from ".DBPREFIX."images where lang_parent='".$image->id."' and language='".$settings->languages[$i]['id']."' order by ordr");
				$title = $iq->next_row();
				$this->imgs4admin[$settings->languages[$i]['id']][$j] = array('id'=>$image->id, 'title'=>fix_quotes($title->title), 'link'=>fix_quotes($image->link), 'ext'=>$image->extension);//for admin zone where multilanguage editing is required
				if($settings->languages[$i]['id']==$settings->language){
					$this->imgs[$j] = array('id'=>$image->id, 'title'=>stripslashes($title->title), 'link'=>stripslashes($image->link), 'ext'=>$image->extension);//for frontend
					for($k=0;$k<sizeof($this->sizes);$k++){
						$this->imgs[$j]['url'][$this->sizes[$k]['suffix']] = $this->url."/".$image->id."_".$this->sizes[$k]['suffix'].".".$image->extension;
					}
				}
			}
			$j++;
		}
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function addNew(){
		global $db;
		$q = $db->query("select * from ".DBPREFIX."gallery2object where id='".$this->g2o."'");
		if($q->num_rows()>0){
			$row = $q->next_row();
			$q1 = $db->query("select max(ordr) as ordr from ".DBPREFIX."gallery2object where object_id='".$row->object_id."' and object_table='".$row->object_table."'");
			$row1 = $q1->next_row();
			$ordr = $row1->ordr+1;
			$db->query("insert into ".DBPREFIX."gallery2object set gallery_id='".$this->id."', object_id='".$row->object_id."', object_table='".$row->object_table."', ordr='".$ordr."', multi='0', locked='0', gallery_title='".$row->gallery_title."'");
			$this->g2o = $db->insert_id();
			$this->title = $row->gallery_title;
			$this->imgs4admin=array();
			$this->imgs=array();
			return true;
		}
		return false;
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	// $input parameter could be xhr or form
	// depending on it we'll do one or another way of file uploading
	function addImg($input_type, $ext){
		if($input_type){
			global $db;
			//insert img and data to db
			$ext = strtolower($ext); if($ext=='jpeg'){$ext="jpg";}
			$ordr = sizeof($this->imgs)+1;
			$db->query("insert into ".DBPREFIX."images set gallery_id='".$this->id."', g2o_id='".$this->g2o."', language='en', extension='".$ext."', ordr='".$ordr."'");
			$imgid = $db->insert_id();
			//below update is required because lang_parent is the field which holds the id which is used in image filename
			$db->query("update ".DBPREFIX."images set lang_parent='".$imgid."' where id='".$imgid."'");
			@mkdir($this->folder, 0755, true);

			// XHR upload
			if($input_type=='xhr'){
				$input = fopen("php://input", "r");
				$fp = fopen($this->folder."/".$imgid."_original.".$ext, "w");
				while ($data = fread($input, 1024)){
					fwrite($fp,$data);
				}
				fclose($fp);
				fclose($input);
			}

			// or FORM upload
			if($input_type=='form'){
				move_uploaded_file($_FILES['qqfile']['tmp_name'], $this->folder."/".$imgid."_original.".$ext);
			}

			//now we create all the resized copies
			//WARNING would not resize images smaller than the new width because we have CSS browser resize for this and there is no point to keep larger files of bad quality
			//it will still create the appropriate copies with suffixes of course
			for($i=0;$i<sizeof($this->sizes);$i++){
				$this->resizeImg(	$this->folder."/".$imgid."_original.".$ext, 
									$this->folder."/".$imgid."_".$this->sizes[$i]["suffix"].".".$ext,
									$this->sizes[$i]["width"],
									$this->sizes[$i]["height"],
									$this->sizes[$i]["cut"]);
			}
			return array(imgid=>$imgid, imgurl=>$this->url."/".$imgid."_admin.".$ext);
		}
		return false;
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function updateImg($request){
		global $db, $settings;
		for($i=0;$i<sizeof($settings->languages);$i++){
			$t = $db->query("select id from ".DBPREFIX."images where language='".$settings->languages[$i]['id']."' and lang_parent='".$request['imgid']."'");
			if($t->num_rows()>0){
				$db->query("update ".DBPREFIX."images set link='".addslashes($request['gilink'])."', title='".addslashes($request['gititle-'.$settings->languages[$i]['id']])."' where lang_parent='".$request['imgid']."' and language='".$settings->languages[$i]['id']."'");
			}else{
				$db->query("insert into ".DBPREFIX."images set link='".addslashes($request['gilink'])."', title='".addslashes($request['gititle-'.$settings->languages[$i]['id']])."', lang_parent='".$request['imgid']."', language='".$settings->languages[$i]['id']."', gallery_id='".$this->id."', g2o_id='".$this->g2o."'");
			}
		}
		return true;
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function updateOrder($order){
		global $db;
		$arr = explode(",",$order);
		for($i=0;$i<sizeof($arr);$i++){
			$k = $i+1;
			if($arr[$i]) $db->query("update ".DBPREFIX."images set ordr='".$k."' where id='".$arr[$i]."'");
		}
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function deleteImgs($list){
		global $db;

		$arr = explode(",",$list);
		for($i=0;$i<sizeof($arr);$i++){
			if($arr[$i]){
				$e = $db->query("select extension from ".DBPREFIX."images where lang_parent='".$arr[$i]."'");
				$ext = $e->next_row();
				for($j=0;$j<sizeof($this->sizes);$j++){
					@unlink($this->folder."/".$arr[$i]."_".$this->sizes[$j]["suffix"].".".$ext->extension);
				}
				$db->query("delete from ".DBPREFIX."images where lang_parent='".$arr[$i]."'");
			}
		}
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function resizeImg($original, $destination, $width, $height, $cut){
		//WARNING would not resize images smaller than the new width because we have CSS browser resize for this and there is no point to keep larger files of bad quality
		if($cut){
			$this->crop($original, $destination, $width, $height);
		}else{
			$this->resize($original, $destination, $width, $height);
		}
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////

	function resize($original, $destination, $width, $height){
		//WARNING would not resize images smaller than the new width because we have CSS browser resize for this and there is no point to keep larger files of bad quality
		$pathinfo = pathinfo($original);
		$ext = $pathinfo['extension'];
		switch($ext) {
			case 'gif':		$src_img = @imagecreatefromgif($original);		break;
			case 'jpg':		$src_img = @imagecreatefromjpeg($original);		break;
			case 'png':		$src_img = @imagecreatefrompng($original);		break;
		}
		list($wo, $ho) = getimagesize($original);
		$arr = $this->proportionalResize($wo,$ho,$width,$height); 
		$new_w = $arr[0]; 
		$new_h = $arr[1];
		$dst_img = @imagecreatetruecolor($new_w,$new_h);
		if($ext=='png'||$ext=='gif'){
			@imagealphablending($dst_img, false);
			@imagesavealpha($dst_img, true);
			$transparent = @imagecolorallocatealpha($dst_img, 255, 255, 255, 127);
			@imagefilledrectangle($dst_img, 0, 0, $new_w, $new_h, $transparent);
		}
		
		@imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $new_w, $new_h, $wo, $ho);
		switch($ext) {
			case 'gif':		@imagegif($dst_img, $destination);			break;
			case 'jpg':		@imagejpeg($dst_img, $destination, 100);		break;
			case 'png':		@imagepng($dst_img, $destination, 0);		break;
		}
		//clearing memory
		@imagedestroy($src_img);
		@imagedestroy($dst_img);
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////

	function proportionalResize($wo,$ho,$width, $height){
		//wo and ho is the original width and height
		if($wo>$width){//resize if the image is bigger than max allowed width
			$new_w = $width;
			$new_h = $width*$ho/$wo;
			if($new_h>$height){
				$new_h = $height;
				$new_w = $height*$wo/$ho;
			}
		}else{//resize if the image height is bigger than the max allowed height
			$new_h = $height;
			$new_w = $height*$wo/$ho;
			if($new_w>$width){
				$new_w = $width;
				$new_h = $width*$ho/$wo;
			}

		}
		$new_h = (int) $new_h;
		$new_w = (int) $new_w;
		$result = array($new_w,$new_h);
		return $result;
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////

	function crop($original, $destination, $width, $height){
		//WARNING would not resize images smaller than the new width because we have CSS browser resize for this and there is no point to keep larger files of bad quality
		$pathinfo = pathinfo($original);
		$ext = $pathinfo['extension'];
		switch($ext){
			case 'gif':		$src_img = @imagecreatefromgif($original);		break;
			case 'jpg':		$src_img = @imagecreatefromjpeg($original);		break;
			case 'png':		$src_img = @imagecreatefrompng($original);		break;
		}
		list($wo, $ho) = getimagesize($original);
		$dst_img = @imagecreatetruecolor($width, $height);

		//resizing the source (only numbers not creating the real image) to temp image that has one of the sides equal to the required crop area
		$tmp_w = $width;
		$tmp_h = $tmp_w*$ho/$wo;
		$tmp_h = round($tmp_h);
		if($tmp_h<$height){
			$tmp_h = $height;
			$tmp_w = $tmp_h*$wo/$ho;
			$tmp_w = round($tmp_w);
		}
		if($tmp_w>$width){
			$src_tmp_x = $tmp_w/2-$width/2;
			$src_tmp_x = round($src_tmp_x);
			$src_tmp_y = 0;
		}else{
			$src_tmp_x = 0;
			$src_tmp_y = $tmp_h/2-$height/2;
			$src_tmp_y = round($src_tmp_y);
		}
		//resizing the cut rectangle area back to big image to cut and copy resized from it
		$src_x = $src_tmp_x*$wo/$tmp_w; $src_x = round($src_x);
		$src_y = $src_tmp_y*$ho/$tmp_h; $src_y = round($src_y);
		$src_w = $width*$wo/$tmp_w;  $src_w = round($src_w);
		$src_h =  $height*$ho/$tmp_h; $src_h = round($src_h);

		if($ext=='png'||$ext=='gif'){
			@imagealphablending($dst_img, false);
			@imagesavealpha($dst_img, true);
			$transparent = @imagecolorallocatealpha($dst_img, 255, 255, 255, 127);
			@imagefilledrectangle($dst_img, 0, 0, $width, $height, $transparent);
		}
		@imagecopyresampled($dst_img, $src_img, 0, 0, $src_x, $src_y, $width, $height, $src_w, $src_h);

		switch($ext) {
			case 'gif':		@imagegif($dst_img, $destination);			break;
			case 'jpg':		@imagejpeg($dst_img, $destination, 100);		break;
			case 'png':		@imagepng($dst_img, $destination, 0);		break;
		}
		//clearing memory
		@imagedestroy($src_img);
		@imagedestroy($dst_img);
	}


}
?>