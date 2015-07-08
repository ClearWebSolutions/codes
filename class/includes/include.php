<?php

//absolute path on server no ending slash!
define('BASEPATH','/Users/alex/http/codes');

//site URL no ending slash!
define('URL','http://localhost/codes');

//database config
$db_setup['hostname'] = 'localhost';
$db_setup['username'] = 'root';
$db_setup['password'] = 'root';
$db_setup['database'] = 'codes';
$db_setup['dbdriver'] = 'mysql';

//including database instance after which you can work with database via the $db object
require_once(BASEPATH.'/class/db/DB.php');

//including the array of reserved words
require_once(BASEPATH.'/class/includes/reserved_words.php');

//including all the classes
require_once(BASEPATH.'/class/includes/classes.php');

//including smarty template engine
require_once(BASEPATH.'/class/smarty/Smarty.class.php');

//initializing template engine
$smarty = new Smarty();
$smarty->compile_check = true;
$smarty->debugging = false;

//starting the session on each requested page
session_start();

//global functions for user registration check
function is_admin(){
	global $_SESSION, $smarty;
	if(isset($_SESSION["admin"])){
		$smarty->assign_by_ref("admin",$_SESSION['admin']);
		return true;
	}else{
		header("Location:".URL);
	}
}




//SOME HELPFUL FUNCTIONS THAT I DON'T WANT TO PUT INTO FRAMEWORK OR TO SOME PARTICULAR CLASSES AS I BELIEVE THEY ARE JUST WIDELY USED SMALL TOOLS
//fix quotes for inputs or attributes
function fix_quotes($str){
	return htmlentities(stripslashes($str), ENT_QUOTES, 'UTF-8');
}

//mostly those are file or string manipulation functons
function dir_copy($srcdir, $dstdir, $offset = '', $verbose = false){
	// A function to copy files from one directory to another one, including subdirectories and
	// nonexisting or newer files. Function returns number of files copied.
	if(!isset($offset)) $offset=0;
	$num = 0;
	$fail = 0;
	$sizetotal = 0;
	$fifail = '';
	if(!is_dir($dstdir)) mkdir($dstdir);
	if($curdir = opendir($srcdir)) {
		while($file = readdir($curdir)) {
			if($file != '.' && $file != '..') {
				$srcfile = $srcdir . '/' . $file;
				$dstfile = $dstdir . '/' . $file;
				if(is_file($srcfile)) {
					if(is_file($dstfile)) $ow = filemtime($srcfile) - filemtime($dstfile); else $ow = 1;
					if($ow > 0) {
						if($verbose) echo "Copying '$srcfile' to '$dstfile'...<br />";
						if(copy($srcfile, $dstfile)) {
							touch($dstfile, filemtime($srcfile)); $num++;
							chmod($dstfile, 0777);
							$sizetotal = ($sizetotal + filesize($dstfile));
							if($verbose) echo "OK\n";
						}else{
							echo "Error: File '$srcfile' could not be copied!<br />\n";
							$fail++;
							$fifail = $fifail.$srcfile.'|';
						}
					}
				}else if(is_dir($srcfile)) {
					$res = explode(',',$ret);
					$ret = dir_copy($srcfile, $dstfile, $verbose);
					$mod = explode(',',$ret);
					$imp = array($res[0] + $mod[0],$mod[1] + $res[1],$mod[2] + $res[2],$mod[3].$res[3]);
					$ret = implode(',',$imp);
				}
			}
		}
		closedir($curdir);
	}
	$red = explode(',',$ret);
	$ret = ($num + $red[0]).','.(($fail-$offset) + $red[1]).','.($sizetotal + $red[2]).','.$fifail.$red[3];
	return $ret;
}
////////////////////////////////////////////////////////////
function normalize ($string){
	$a = 'AAAAAA?CEEEEIIII?NOOOOOOUUUUY??aaaaaa?ceeeeiiii?noooooouuuyy?yRr ';
	$b = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr_';
	$string = utf8_decode($string);
	$string = strtr($string, utf8_decode($a), $b);
	$string = strtolower($string);
	$string = str_replace('`','',$string);
	$string = str_replace('’','',$string);
	$string = str_replace('„','',$string);
	$string = str_replace('‘','',$string);
	$string = str_replace('?','',$string);
	$string = str_replace(',','',$string);
	$string = str_replace('"','',$string);
	$string = str_replace("'",'',$string);
	$string = str_replace("\\",'',$string);
	$string = str_replace('/','',$string);
	$string = str_replace('*','',$string);
	$string = str_replace('#','',$string);
	$string = str_replace('$','',$string);
	$string = str_replace('%','',$string);
	$string = str_replace('^','',$string);
	$string = str_replace('&','',$string);
	$string = str_replace(':','',$string);
	$string = str_replace(';','',$string);
	$string = str_replace('&','',$string);
	$string = str_replace('(','',$string);
	$string = str_replace(')','',$string);
	return utf8_encode($string);
}
////////////////////////////////////////////////////////////
function file_replace($filesrc,$search_arr,$replace_arr){
	$f_arr = file($filesrc);
	for($i=0;$i<sizeof($f_arr);$i++){
		for($j=0;$j<sizeof($search_arr);$j++){
			$f_arr[$i] = str_replace($search_arr[$j],$replace_arr[$j],$f_arr[$i]);
		}
	}
	//opens file for writing by totally replacing it with an empty one
	$fp = fopen($filesrc,'w');
	//writing file with replaced strings
	fputs($fp,implode("",$f_arr)); 
	fclose($fp);
}
////////////////////////////////////////////////////////////
function advanced_rmdir($path){
		$origipath = $path;
		$handler = opendir($path);
		while (true) {
			$item = readdir($handler);
			if ($item == "." or $item == "..") {
				continue;
			} elseif (gettype($item) == "boolean") {
				closedir($handler);
				if (!@rmdir($path)) {
					return false;
				}
				if ($path == $origipath) {
					break;
				}
				$path = substr($path, 0, strrpos($path, "/"));
				$handler = opendir($path);
			} elseif (is_dir($path."/".$item)) {
				closedir($handler);
				$path = $path."/".$item;
				$handler = opendir($path);
			} else {
				unlink($path."/".$item);
			}
		}
		return true;
}
////////////////////////////////////////////////////////////
if (!function_exists('json_encode')){
  function json_encode($a=false)
  {
    if (is_null($a)) return 'null';
    if ($a === false) return 'false';
    if ($a === true) return 'true';
    if (is_scalar($a))
    {
      if (is_float($a))
      {
        // Always use "." for floats.
        return floatval(str_replace(",", ".", strval($a)));
      }

      if (is_string($a))
      {
        static $jsonReplaces = array(array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"'), array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"'));
        return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $a) . '"';
      }
      else
        return $a;
    }
    $isList = true;
    for ($i = 0, reset($a); $i < count($a); $i++, next($a))
    {
      if (key($a) !== $i)
      {
        $isList = false;
        break;
      }
    }
    $result = array();
    if ($isList)
    {
      foreach ($a as $v) $result[] = json_encode($v);
      return '[' . join(',', $result) . ']';
    }
    else
    {
      foreach ($a as $k => $v) $result[] = json_encode($k).':'.json_encode($v);
      return '{' . join(',', $result) . '}';
    }
  }
}
////////////////////////////////////////////////////////////
	function executeSqlFromFile($file, $db_prefix){
		global $db;
		$query = '';
		$lines = file($file);
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
				$query = str_replace("DBPREFIX", $db_prefix, $query);
				$db->query($query);
				$query = "";
			}
		}
	}





?>