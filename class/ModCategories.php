<?php
class ModCategories{

	function ModCategories($id=0){
		global $db, $_SESSION;
		//$this->site = new Site($_SESSION['siteid']);
		$site = new Site($_SESSION['siteid']);
		$this->site = new stdClass();
		$this->site->db_name = $site->db_name;
		$this->site->db_prefix = $site->db_prefix;
		$this->site->dir = $site->dir;
		if($id){
			$this->id = $id;
			$db->query("use codes");
			$q = $db->query("select * from modules where id='".$this->id."'");
			$row = $q->next_row();
			$m = unserialize(base64_decode($row->serialized));
			if(gettype($m)!='object') $m = unserialize($row->serialized); //fix for websites already using old serialize
			$this->page = $m->page;
			$this->db_table = $m->db_table;
			$this->title = stripslashes($m->title);
		}
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function add($request){
		global $db, $_SESSION;
		if(!$this->check($request)) return false;

		//initializing page where the code would be added
		$this->page = new Page($request['page']);
		$this->title = $request['title'];
		$this->db_table = normalize($request['db_tbl']);

		//switch to site's db
		$db->query("use ".$this->site->db_name);

		//create the DB table
		$db->query("create table ".$this->site->db_prefix.$this->db_table."(id int(11) NOT NULL AUTO_INCREMENT, parent_id int(11) DEFAULT 0, lang_parent int(11) DEFAULT NULL, language varchar(2) DEFAULT NULL, ordr int(11) DEFAULT 0, title varchar(255) DEFAULT NULL, PRIMARY KEY (id)) engine=InnoDB default charset=utf8");

		//add files to admin
		dir_copy("modules/categories",$this->site->dir);

		//update classes.php
		$classes = file($this->site->dir.'/class/includes/classes.php');
		$code =  "require_once(BASEPATH.'/class/base/CategoryBase.php');\n";
		$code .= "require_once(BASEPATH.'/class/Category.php');\n";
		$updated_classes = "";
		for($i=0;$i<sizeof($classes);$i++){
			if($i==sizeof($classes)-2) $updated_classes .= $code;
			$updated_classes .= $classes[$i];
		}
		file_put_contents($this->site->dir."/class/includes/classes.php", $updated_classes);

		//update admin/templates/menu.tpl
		$menu = file($this->site->dir.'/admin/templates/menu.tpl');
		$newitem = "\t\t<li {if \$menu=='categories'&&\$category->tbl=='".$this->db_table."'}class=\"selected1\"{/if}><a href=\"categories.php?tbl=".$this->db_table."\">".ucfirst($this->title)."<br/><div class=\"tri\"></div></a></li>\n";
		$done = false;
		for($i=0;$i<sizeof($menu);$i++){
			if(strstr($menu[$i], '<ul>')&&!$done){
				$menu[$i] .= $newitem;
				$done = true;
			}
			$new_menu .= $menu[$i];
		}
		file_put_contents($this->site->dir.'/admin/templates/menu.tpl', $new_menu);

		//setting back to codes database
		$db->query("use codes");

		//add module and it's data to codes db
		$db->query("insert into modules set pageid='".$this->page->id."', module='categories', title='".addslashes($this->title)."', serialized='".base64_encode(serialize($this))."'");
		$this->id = $db->insert_id();

		return true;
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function check($request){
		global $db;
		if(!$request['page']&&$request['action']=='add'){		$this->error = "Please select the page first!";return false;}
		if(!$request['title']){		$this->error = "Please enter the title!";return false;}
		if(!$request['db_tbl']){	$this->error = "Please enter the DB table name!";return false;}
		$db_tbl = normalize($request['db_tbl']);
		if($this->db_table!=$db_tbl){
			$db->query("use ".$this->site->db_name);
			$q = $db->query("show tables like '".$this->site->db_prefix.$db_tbl."'");
			if($q->num_rows()>0){	$this->error = "Such DB table already exists please select another name!"; return false;}
			$db->query("use codes");
		}
		return true;
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function update($request){
		global $db;
		if(!$this->check($request)) return false;

		if($this->db_table!=normalize($request['db_tbl'])){
			//switch to site's db
			$db->query("use ".$this->site->db_name);
			$db->query("rename table ".$this->site->db_prefix.$this->db_table." to ".$this->site->db_prefix.normalize($request['db_tbl']));

			//update admin/templates/menu.tpl
			$search[0] = "&&\$category->tbl=='".$this->db_table."'";
			$search[1] = "categories.php?tbl=".$this->db_table."\"";
			$replace[0] = "&&\$category->tbl=='".normalize($request['db_tbl'])."'";
			$replace[1] = "categories.php?tbl=".normalize($request['db_tbl'])."\"";
			file_replace($this->site->dir.'/admin/templates/menu.tpl', $search, $replace);
		}

		if($this->title!=$request['title']){
			//update the admin menu title
			$search[0] = ">".ucfirst($this->title)."<";
			$replace[0] = ">".ucfirst($request['title'])."<";
			file_replace($this->site->dir.'/admin/templates/menu.tpl', $search, $replace);
		}

		//setting back to codes database
		$db->query("use codes");
		$this->title = $request['title'];
		$this->db_table = normalize($request['db_tbl']);
		//add module and it's data to codes db
		$db->query("update modules set title='".addslashes($this->title)."', serialized='".base64_encode(serialize($this))."' where id='".$this->id."'");

		return true;
	}

}
?>