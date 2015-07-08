<?php
class Site{

	function Site($id=0){
		global $db;
		if($id){
			$this->id = $id;
			$db->query("use codes");
			$q = $db->query("select * from sites where id='".$this->id."'");
			$row = $q->next_row();
			$this->sitename = stripslashes($row->sitename);
			$this->dir  = "../".normalize($this->sitename);
			$this->db_name = $row->db_name;
			$this->db_prefix = $row->db_prefix;
			$this->db_user = $row->db_user;
			$this->db_password = $row->db_password;
			$this->db_host = $row->db_host;
			$this->admin_first_page = $row->admin_first_page;
			//getting all the templates of this site excluding the header.tpl and footer.tpl
			if($handle = @opendir($this->dir."/templates/")) {
				while (false !== ($file = readdir($handle))) {
					if ($file != "." && $file != ".."&&$file!="header.tpl"&&$file!="footer.tpl"&&$file!=".DS_Store"&&$file!="codes") {
						$this->templates[] = $file;
					}
				}
				closedir($handle);
			}
			//getting all the site pages
			$q = $db->query("select id, name from pages where siteid='".$this->id."'");
			foreach($q->result() as $row){
				$name = $row->name;
				if(strlen($name)>30){
					$name = substr($name, 0, 19)."...".substr($name, strlen($name)-8, 8);
				}
				$this->pages[] = array("id"=>$row->id, "name"=>$name);
			}
			//getting all the site modules
			$q = $db->query("select m.module as module, m.serialized as serialized, m.title as title from modules as m, pages as p where m.pageid=p.id and p.siteid='".$this->id."'");
			foreach($q->result() as $row){
				$m = unserialize(base64_decode($row->serialized));
				if(gettype($m)!='object') $m = unserialize($row->serialized); //fix for websites already using old serialize
				$this->modules[] = array("module"=>$row->module, "title"=>$row->title, "m"=>$m);
				if($row->module=='categories'){
					$this->categories[] = array("title"=>$row->title, "table"=>$this->db_prefix.$m->db_table, "m"=>$m);
				}
				if($row->module=='complexObject'){
					$this->complexObjects[] = array("title"=>$row->title, "table"=>$this->db_prefix.$m->tbl,"m"=>$m);
				}

			}

		}
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function add($request){
		global $db;
		if(!$request['sitename']||!$request['dbname']){
			$this->error = "Something is wrong!"; return false;
		}
		$this->sitename = $request['sitename'];
		$this->dir = "../".normalize($this->sitename);
		$this->db_name = $request['dbname'];
		$this->db_prefix = $request['dbprefix'];
		$u = new AdminUser(1);
		$this->db_user = $u->db_user;
		$this->db_password = $u->db_password;
		$this->db_host = $u->db_host;
		$this->client_username = $u->client_username;
		$this->client_password = $u->client_password;

		//db check
		$q = $db->query("select * from sites where sitename='".$this->sitename."'");
		if($q->num_rows()){
			$this->error='There is already a site with such name!'; return false;
		}
		$q = $db->query("select * from sites where db_name='".$this->db_name."'");
		if($q->num_rows()){
			$this->error='There is already a database with such name!'; return false;
		}

		//check if such db exists
		$q = $db->query("select SCHEMA_NAME from INFORMATION_SCHEMA.SCHEMATA where SCHEMA_NAME = '".$this->db_name."'");
		if($q->num_rows()){
			$this->error = 'There is already a database with such name!'; return false;
		}

		//check if such directory exists
		if(is_dir($this->dir)&&$request['error']!="There is already a dir with such name!<br/>Click Add once again to proceed anywhere. Remember such subfolders would be created:<br/>/admin, /assets, /class, /templates, /templates_c"){
			$this->error = "There is already a dir with such name!<br/>Click Add once again to proceed anywhere. Remember such subfolders would be created:<br/>/admin, /assets, /class, /templates, /templates_c"; return false;
		}


		//copy site backbone files to new site dir
		@mkdir($this->dir);
		dir_copy("modules/default",$this->dir);

		//create the new site's db
		$db->query("CREATE DATABASE ".$this->db_name." CHARACTER SET utf8 COLLATE utf8_general_ci");

		//switch to it
		$db->query("use ".$this->db_name);

		//execute the SQL for base database
		$query = '';
		$lines = file($this->dir.'/db.sql');
		//removing BOM if exists
		if(substr($lines[0],0,3) == pack("CCC",0xef,0xbb,0xbf)){ $lines[0]=substr($lines[0], 3); }
		foreach($lines as $line){
			// Skip it if it's a comment
			if(substr($line,0,1) == '#' || $line == '' || substr($line, 0, 2) == '/*'||substr($line,0,2)=='--')
				continue;
			// Add this line to the current segment
			$query .= $line;
			// If it has a semicolon at the end, it's the end of the query
			if (substr(trim($line), -1, 1) == ';'){
				$query = str_replace("DBPREFIX", $this->db_prefix, $query);
				$db->query($query);
				$query = "";
			}
		}

		//deleting the sql as it's not more needed
		unlink($this->dir."/db.sql");

		//creating the default admin record
		$db->query("insert into ".$this->db_prefix."admin set id='1', username='".$this->client_username."', password='".$this->client_password."'");

		//setting back to codes database
		$db->query("use codes");

		//update the new includes settings with the appropriate constants
		$search[0] = "define('BASEPATH','');";
		$search[1] = "define('URL','');";
		$search[2] = "define('DBPREFIX','');";
		$search[3] = "\$db_setup['hostname'] = '';";
		$search[4] = "\$db_setup['username'] = '';";
		$search[5] = "\$db_setup['password'] = '';";
		$search[6] = "\$db_setup['database'] = '';";
		$basepath = substr(BASEPATH, 0,-strlen(strrchr(BASEPATH, "/")))."/".normalize($this->sitename);
		$replace[0] = "define('BASEPATH','".$basepath."');";
		$baseurl = substr(URL, 0,-strlen(strrchr(URL, "/")))."/".normalize($this->sitename);
		$replace[1] = "define('URL','".$baseurl."');";
		$replace[2] = "define('DBPREFIX','".$this->db_prefix."');";
		$replace[3] = "\$db_setup['hostname'] = '".$this->db_host."';";
		$replace[4] = "\$db_setup['username'] = '".$this->db_user."';";
		$replace[5] = "\$db_setup['password'] = '".$this->db_password."';";
		$replace[6] = "\$db_setup['database'] = '".$this->db_name."';";
		file_replace($this->dir."/class/includes/include.php", $search, $replace);

		//updating .htaccess
		unset($search);
		unset($replace);
		$base_arr = explode("/",URL);
		$base = '';
		for($i=3;$i<sizeof($base_arr)-1;$i++){
			$base .= $base_arr[$i]."/";
		}
//		$base = substr($base, 0,-strlen(strrchr($base, "/")))."/".normalize($this->sitename);
		$base = normalize($this->sitename);
		$search[0] = "codes";
		$replace[0] = $base;
		file_replace($this->dir."/.htaccess",$search, $replace);

		//if all fine add site to codes db
		$db->query("insert into sites set sitename='".$this->sitename."', db_name='".$this->db_name."', db_prefix='".$this->db_prefix."', db_user='".$this->db_user."', db_password='".$this->db_password."', db_host='".$this->db_host."'");
		$this->id = $db->insert_id();

		return $this->id;
	}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function addPage($request){
		global $db;
		if(!$request['name']){ $this->error = "Please enter page name!";return false;}
		if(strlen($request['name'])<3){ $this->error = "Page name should be 3 characters or longer!";return false;}
		$q = $db->query("select id from pages where name='".$request['name']."' and siteid='".$this->id."'");
		if($q->num_rows()>0){ $this->error = "There is already a page with such name!"; return false;}
		if(!$request['template']){ $this->error="Please select template!"; return false;}
		if($handle = @opendir($this->dir)) {
			while (false !== ($file = readdir($handle))) {
				if($request['name'].".php"==$file){ $this->error="Warning! There is already a page with this name in site's directory!"; return false;}
			}
			closedir($handle);
		}
		$request['name'] = normalize($request['name']);
		//all fine adding a new page
		$file = $this->dir."/".$request['name'].".php";
		$fp = fopen($file, 'w');
		$code = "<?php\n";
		$code .= "include(\"header.php\");\n\n";
		$code .= "\$smarty->assign(\"page\",\"".$request['name']."\");\n\n";
		$code .= "\$smarty->display(\"".$request['template']."\");\n";
		$code .= "?>";
		fwrite($fp,$code); 
		fclose($fp);
		//adding page to codes db
		$db->query("insert into pages set name='".$request['name']."', template='".$request['template']."', siteid='".$this->id."'");
		return $db->insert_id();
	}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function updateSettings($request){
		global $db;
		if($this->sitename!=$request['name']){
			//check if the name is not empty
			if($request['name']==''){$this->error = "Site name can't be empty! It's used as a folder name!";return false;}
			//check the folders if such already exists
			$dir = "../".normalize($request['name']);
			if(is_dir($dir)){	$this->error = "There is already a folder with such name!";return false;}
			//check the db if there is already a project with such name
			$q = $db->query("select id from sites where sitename='".$request['name']."'");
			if($q->num_rows()>0){$this->error = "There is already a website with such name in the system, please choose another name!";return false;}
			//rename the directory
			if(!rename($this->dir, $dir)){$this->error = "Can't rename the folder.";return false;}
			$this->dir = $dir;
			//update the includes.php BASEPATH and URL
			$search[0] = "define('BASEPATH','".substr(BASEPATH, 0,-strlen(strrchr(BASEPATH, "/")))."/".normalize($this->sitename)."');";
			$search[1] = "define('URL','".substr(URL, 0,-strlen(strrchr(URL, "/")))."/".normalize($this->sitename)."');";
			$basepath = substr(BASEPATH, 0,-strlen(strrchr(BASEPATH, "/")))."/".normalize($request['name']);
			$replace[0] = "define('BASEPATH','".$basepath."');";
			$baseurl = substr(URL, 0,-strlen(strrchr(URL, "/")))."/".normalize($request['name']);
			$replace[1] = "define('URL','".$baseurl."');";
			file_replace($this->dir."/class/includes/include.php", $search, $replace);
			//update this
			$this->sitename = $request['name'];
			//update db
			$db->query("update sites set sitename='".$request['name']."' where id='".$this->id."'");
			return 1;
		}
		if($this->admin_first_page!=$request['admin_first_page']){
			//check if the admin_first_page is not empty just in case...
			//update this
			//update db
		}
	}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/*	function getAdminPages(){
		global $db;
		if($this->id){
			//default page always
			echo "<option value=\"account.php\">Account<option/>";
			$q = $db->query("select m.module as module, m.title as title from modules as m, pages as p where m.pageid=p.id and p.siteid = '".$this->id."'");
			foreach($q->result() as $row){
				switch($row->module){
					case 'gallery':	echo "<option value=\"gallery.php?id=\">".$row->title."</option>";	break;
				}
			}
		}
	}*/

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function delete(){
		global $db;
		if($this->id){
			//delete db
			$db->query("drop database IF EXISTS ".$this->db_name);
			//delete files
			advanced_rmdir($this->dir);
			//delete records from codes db
			$db->query("delete from modules where pageid in (select id from pages where siteid='".$this->id."')");
			$db->query("delete from pages where siteid='".$this->id."'");
			$db->query("delete from sites where id='".$this->id."'");
		}
	}

}
?>