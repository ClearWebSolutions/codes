<?php
class ModMultilanguage{

	function ModMultilanguage($id=0){
		global $db, $_SESSION;
//		$this->site = new Site($_SESSION['siteid']);
		$site = new Site($_SESSION['siteid']);
		$this->site = new stdClass();
		$this->site->db_name = $site->db_name;
		$this->site->db_prefix = $site->db_prefix;
		$this->site->dir = $site->dir;
		if($id){
			$this->id = $id;
			$q = $db->query("select * from modules where id='".$this->id."'");
			$row = $q->next_row();
			$m = unserialize(base64_decode($row->serialized));
			if(gettype($m)!='object') $m = unserialize($row->serialized); //fix for websites already using old serialize
			$this->page = $m->page;
		}
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function add($request){
		global $db, $_SESSION, $db_setup;
		if(!$this->check($request)) return false;

		//initializing page where the code would be added
		$this->page = new Page($request['page']);

		//switch to site's db
		$db->query("use ".$this->site->db_name);

		//create the DB entries
		$ids = ",";
		$q = $db->query("select id from ".$this->site->db_prefix."languages");
		foreach($q->result() as $row){
			$ids .= $row->id.",";
		}
		
		for($i=1;$i<=100;$i++){
			if($request['language'.$i.'id']&&$request['language'.$i.'name']&&!strstr($ids,",".$request['language'.$i.'id'].",")){
				$db->query("insert into ".$this->site->db_prefix."languages set id='".$request['language'.$i.'id']."', language='".$request['language'.$i.'name']."', ordr='".$i."'");
			}
		}

		//ADDING VOCABULARY AND LANGUAGES MANGEMENT
		//add vocabulary db table
		$query = '';
		$lines = file('modules/multilanguage/db.sql');
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
				$query = str_replace("DBPREFIX", $this->site->db_prefix, $query);
				$db->query($query);
				$query = "";
			}
		}

		//add vocabulary and language files to admin
		dir_copy("modules/multilanguage",$this->site->dir);
		//delete db.sql
		@unlink($this->site->dir."/db.sql");
		//update classes.php
		$classes = file($this->site->dir.'/class/includes/classes.php');
		$code =  "require_once(BASEPATH.'/class/base/VocabularyBase.php');\n";
		$code .= "require_once(BASEPATH.'/class/Vocabulary.php');\n";
		$code .= "require_once(BASEPATH.'/class/base/LanguageBase.php');\n";
		$code .= "require_once(BASEPATH.'/class/Language.php');\n";
		$updated_classes = "";
		for($i=0;$i<sizeof($classes);$i++){
			if($i==sizeof($classes)-2) $updated_classes .= $code;
			$updated_classes .= $classes[$i];
		}
		file_put_contents($this->site->dir."/class/includes/classes.php", $updated_classes);

		//update SettingsBase.php
		$class = file($this->site->dir.'/class/base/SettingsBase.php');
		$code =  "\t\t\t//all labels\n";
		$code .= "\t\t\t\$q = \$db->query(\"select * from \".DBPREFIX.\"vocabulary where language='\".\$this->language.\"'\");\n";
		$code .= "\t\t\tforeach(\$q->result() as \$vocabulary){\n";
		$code .= "\t\t\t\t\$this->vocabulary[\$vocabulary->label] = \$vocabulary->phrase;\n";
		$code .= "\t\t\t}\n\n";
		$updated_class = "";
		for($i=0;$i<sizeof($class);$i++){
			if(strstr($class[$i],"admin rows per page")) $updated_class .= $code;
			$updated_class .= $class[$i];
		}
		file_put_contents($this->site->dir."/class/base/SettingsBase.php", $updated_class);

		//update admin/templates/menu.tpl
		$search[0] = "\$menu=='metadata'";
		$replace[0] = "\$menu=='metadata'||\$menu=='languages'||\$menu=='vocabulary'";
		$search[1] = "<li><a href=\"metadata.php\">Metadata</a></li>";
		$replace[1] = "<li><a href=\"metadata.php\">Metadata</a></li>\n";
		$replace[1] .= "\t\t\t\t<li><a href=\"languages.php\">Languages</a>\n";
		$replace[1] .= "\t\t\t\t\t<ul>\n";
		$replace[1] .= "\t\t\t\t\t\t<li><a href=\"languages.php\">Browse</a></li>\n";
		$replace[1] .= "\t\t\t\t\t\t<li><a href=\"languages.php?action=add\">Add new</a></li>\n";
		$replace[1] .= "\t\t\t\t\t</ul>\n";
		$replace[1] .= "\t\t\t\t</li>\n";
		$replace[1] .= "\t\t\t\t<li><a href=\"vocabulary.php\">Vocabulary</a>\n";
		$replace[1] .= "\t\t\t\t\t<ul>\n";
		$replace[1] .= "\t\t\t\t\t\t<li><a href=\"vocabulary.php\">Browse</a></li>\n";
		$replace[1] .= "\t\t\t\t\t\t<li><a href=\"vocabulary.php?action=add\">Add new</a></li>\n";
		$replace[1] .= "\t\t\t\t\t</ul>\n";
		$replace[1] .= "\t\t\t\t</li>";
		file_replace($this->site->dir.'/admin/templates/menu.tpl', $search, $replace);


		//add vocabulary and multi-language switcher bubbles to header
		//open "header.tpl"
		$header = file_get_contents($this->site->dir.'/templates/header.tpl');
		// search for codes_header and put 2 includes in there
		if(strstr($header, "codes_header")){
			unset($search);
			unset($replace);
			$search[0] = "codes_header\">\n";
			$replace[0] = "codes_header\">\n\t\t{include file='codes/languages.tpl'}\n\t\t{include file='codes/vocabulary.tpl'}\n";
			file_replace($this->site->dir.'/templates/header.tpl', $search, $replace);
		}else{
		// if not found search for <body and put on the next row after it
			$header = file($this->site->dir.'/templates/header.tpl');
			$code =  "\t<div class=\"codes_header\">\n";
			$code .= "\t\t{include file='codes/languages.tpl'}\n";
			$code .= "\t\t{include file='codes/vocabulary.tpl'}\n";
			$code .= "\t</div>\n";
			$updated_header = "";
			for($i=0;$i<sizeof($header);$i++){
				if(strstr($header[$i],"<body")){ 
					$updated_header .= $header[$i];
					$updated_header .= $code;
				}else{
					$updated_header .= $header[$i];
				}
			}
			file_put_contents($this->site->dir."/templates/header.tpl", $updated_header);
		}

		//setting back to codes database
		$db->query("use ".$db_setup['database']);

		//add module and it's data to codes db
		$db->query("insert into modules set pageid='".$this->page->id."', module='multilanguage', title='', serialized='".base64_encode(serialize($this))."'");
		$this->id = $db->insert_id();

		return true;
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function check($request){
		global $db;
		if(!$request['page']&&$request['action']=='add'){		$this->error = "Please select the page first!";return false;}
		return true;
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function update($request){
		global $db, $db_setup;
		if(!$this->check($request)) return false;

		//switch to site's db
		$db->query("use ".$this->site->db_name);

		//update the DB entries
		$sizes = "admin:127:95:1;";
		if($request['fancybox']=='1'){$sizes.="full:1280:800:0;";$this->fancybox=1;}else{$this->fancybox=0;}
		$this->amount = $request['amount'];
		for($i=1;$i<=$request['amount'];$i++){
			$sizes .= $request['suffix'.$i].":".$request['width'.$i].":".$request['height'.$i].":".$request['cut'.$i].";";
		}
		$db->query("update ".$this->site->db_prefix."galleries set title='".$request['name']."', folder='".$request['folder']."', sizes='".$sizes."' where id='".$this->gid."'");

		//add code for the gallery to the page.php
		$search[0] = "\$".$this->code_title." = new Gallery(".$this->gid.");";
		$search[1] = "\$smarty->assign_by_ref(\"".$this->code_title."\", \$".$this->code_title.");";
		$replace[0] = "\$".normalize($request['name'])." = new Gallery(".$this->gid.");";
		$replace[1] = "\$smarty->assign_by_ref(\"".normalize($request['name'])."\", \$".normalize($request['name']).");";
		file_replace($this->site->dir.'/'.$this->page->filename, $search, $replace);

		//add code for the bubble to the page.tpl
		$search[0] = "{include file='codes/".$this->code_title.".tpl'}";
		$search[1] = "$".$this->code_title."->";
		$replace[0] = "{include file='codes/".normalize($request['name']).".tpl'}";
		$replace[1] = "$".normalize($request['name'])."->"; 
		file_replace($this->site->dir."/templates/".$this->page->template, $search, $replace);

		//update the admin menu title
		$search[0] = ">".ucfirst($this->title)."<";
		$replace[0] = ">".ucfirst($request['name'])."<";
		file_replace($this->site->dir.'/admin/templates/menu.tpl', $search, $replace);

		//update the bubble title
		$search[0] = "<h2>Module Gallery ".$this->title."</h2>";
		$search[1] = "templates/codes/".$this->code_title.".tpl";
		$search[2] = "$".$this->code_title."->";
		$replace[0] = "<h2>Module Gallery ".$request['name']."</h2>";
		$replace[1] = "templates/codes/".normalize($request['name']).".tpl";
		$replace[2] = "$".normalize($request['name'])."->";
		if($this->fancybox!=$request['fancybox']){
			if($request['fancybox']==0){
				$search[3] = "rel=\"gallery\" class=\"fancybox\"";
				$replace[3] = "";
			}else{
				$bubble_code = file_get_contents($this->site->dir."/templates/codes/".$this->code_title.".tpl");
				if(!strstr($bubble_code, "rel=\"gallery\"")){
					$search[3] = "><img src=";
					$replace[3] = " rel=\"gallery\" class=\"fancybox\"><img src=";
				}
			}
		}
		
		file_replace($this->site->dir."/templates/codes/".$this->code_title.".tpl", $search, $replace);

		//rename the bubble tpl
		@rename($this->site->dir."/templates/codes/".$this->code_title.".tpl", $this->site->dir."/templates/codes/".normalize($request['name']).".tpl");

		//setting back to codes database
		$db->query("use ".$db_setup['database']);

		$this->title = $request['name'];
		$this->folder = $request['folder'];
		$this->code_title = normalize($this->title);
		if($request['fancybox']=='1'){$this->fancybox=1;}else{$this->fancybox=0;}

		//add module and it's data to codes db
		$db->query("update modules set title='".$this->title."', serialized='".base64_encode(serialize($this))."' where id='".$this->id."'");

		return true;
	}

}
?>