<?php
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

// THIS IS JUST AN EXAMPLE, PLEASE DO NOT USE
// IN PRODUCTION WITHOUT CHECKING/MODIFICATIONS
class UploadFileXhr {
	function save($path){
		$input = fopen("php://input", "r");
		$fp = fopen($path, "w");
		while ($data = fread($input, 1024)){
			fwrite($fp,$data);
		}
		fclose($fp);
		fclose($input);
	}
	function getName(){
		return $_GET['qqfile'];
	}
	function getSize(){
//		$headers = apache_request_headers();
//		return (int)$headers['Content-Length'];
		return $_SERVER['Content_Length'];
	}
}

class UploadFileForm {	
	function save($path){
		move_uploaded_file($_FILES['qqfile']['tmp_name'], $path);
	}
	function getName(){
		return $_FILES['qqfile']['name'];
	}
	function getSize(){
		return $_FILES['qqfile']['size'];
	}
}

function handleUpload(){
	$uploaddir = $_REQUEST['folder'];
//	$maxFileSize = 500000;
	if (isset($_GET['qqfile'])){
		$file = new UploadFileXhr();
	}elseif(isset($_FILES['qqfile'])){
		$file = new UploadFileForm();
	} else {
		return array(success=>false);
	}
	$size=1;
//AY commented because Content_Length can't be determined in many cases...
/*	$size = $file->getSize();
	if ($size == 0){
		return array(success=>false, error=>"File is empty.");
	}				
	if ($size > $maxFileSize){
		return array(success=>false, error=>"File is too large.");
	}*/
	$pathinfo = pathinfo($file->getName());
	$filename = $pathinfo['filename'];
	$ext = $pathinfo['extension'];
	// if you limit file extensions on the client side,
	// you should check file extension here too
	while (file_exists($uploaddir . $filename . '.' . $ext)){
		$filename .= rand(10, 99);
	}	
	$filename .= rand(10, 99);

		
	$file->save($uploaddir . $filename . '.' . $ext);
	
	return array(success=>true);
}


$result = handleUpload();

// to pass data through iframe you will need to encode all html tags
echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
?>