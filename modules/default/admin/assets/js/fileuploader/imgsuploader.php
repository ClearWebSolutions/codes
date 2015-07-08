<?php
include('../../../../class/includes/include.php');
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

class UploadFileXhr {
	function save($gid, $g2o, $ext){
		$gallery = new Gallery($gid,$g2o);
		$result = $gallery->addimg('xhr', $ext);
		return $result;
	}
	function getName(){
		return $_GET['qqfile'];
	}
	function getSize(){
		//$headers = apache_request_headers();
		//return (int)$headers['Content-Length'];
		return $_SERVER['Content_Length'];
	}
}

class UploadFileForm {	
	function save($gid, $g2o, $ext){
		$gallery = new Gallery($gid,$g2o);
		$result = $gallery->addimg('form', $ext);
		return $result;
	}
	function getName(){
		return $_FILES['qqfile']['name'];
	}
	function getSize(){
		return $_FILES['qqfile']['size'];
	}
}

function handleUpload(){
//	$uploaddir = $_REQUEST['folder'];
	if(isset($_GET['qqfile'])){
		$file = new UploadFileXhr();
	}elseif(isset($_FILES['qqfile'])){
		$file = new UploadFileForm();
	} else {
		return array(success=>false);
	}
	$pathinfo = pathinfo($file->getName());
	$filename = $pathinfo['filename'];
	$ext = $pathinfo['extension'];
	$result = $file->save($_REQUEST['gid'], $_REQUEST['g2o'], $ext);
	return array(success=>true, imgid=>$result['imgid'], imgurl=>$result['imgurl']);
}

$result = handleUpload();

// to pass data through iframe you will need to encode all html tags
echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
?>