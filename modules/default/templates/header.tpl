<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>{if $p->meta_title}{$p->meta_title}{else}{$settings->meta_title}{/if}</title>
	<meta name="description" content="{if $p->meta_description}{$p->meta_description}{else}{$settings->meta_description}{/if}"/>
	<meta name="keywords" content="{if $p->meta_keywords}{$p->meta_keywords}{else}{$settings->meta_keywords}{/if}"/>
	<script language="javascript">
		var siteurl = "{$settings->url}/";
	</script>
	<link rel="stylesheet" type="text/css" href="{$settings->url}/assets/css/codes/codes.css"/>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
	<link rel="stylesheet" type="text/css" href="{$settings->url}/assets/js/codes/fancybox/jquery.fancybox-1.3.4.css"/>
	<script type="text/javascript" src="{$settings->url}/assets/js/codes/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
	<script type="text/javascript" src="{$settings->url}/assets/js/codes/scripts.js"></script>

</head>
<body>