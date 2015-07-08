<?php
class EmailBase{

	function EmailBase(){
	}

	function validate($email){
		return filter_var($email, FILTER_VALIDATE_EMAIL);
	}

	function send($to, $from, $subject, $msg){
		mail($to, $subject, $msg, "From: ".$from."\nContent-Type: text/html; charset=windows-1251");
	}

}
?>