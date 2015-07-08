<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<!-- styles -->
	<link rel="stylesheet" type="text/css" href="assets/css/style.css"/>
	<!-- jquery -->
	<script type="text/javascript" src="assets/js/jquery-1.6.2.min.js"></script>
	<script type="text/javascript" src="assets/js/jqueryui/js/jquery-ui-1.8.15.min.js"></script>
<!--	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.15/jquery-ui.min.js"></script>-->
	<link type="text/css" href="assets/js/jqueryui/css/ui-lightness/jquery-ui-1.8.14.custom.css" rel="stylesheet" />
	<script type="text/javascript" src="assets/js/jqueryui/js/jquery.ui.selectmenu.js"></script>
	<!-- browser detect -->
	<script type="text/javascript" src="assets/js/browser/cssBrowserDetect.js"></script>
	<!-- menu-->
	<script type="text/javascript" src="assets/js/menu/jqueryslidemenu.js"></script>
	<link rel="stylesheet" type="text/css" href="assets/js/menu/jqueryslidemenu.css"/>
	<!-- dragsort -->
	<script type="text/javascript" src="assets/js/dragSort/jquery.dragsort-0.4.3.min.js"></script>
	<!-- file uploader -->
	<script type="text/javascript" src="assets/js/fileuploader/fileuploader.js"></script>
	<link rel="stylesheet" type="text/css" href="assets/js/fileuploader/fileuploader.css"/>
	<!-- ajax form -->
	<script type="text/javascript" src="assets/js/ajaxform/jquery.form.js"></script>
	<!-- selecBox plugin for the editor-->
	<link type="text/css" href="assets/js/selectBox/jquery.selectBox.css" rel="stylesheet" />
	<script type="text/javascript" src="assets/js/selectBox/jquery.selectBox.js"></script>
	<!-- CKeditor -->
	<script type="text/javascript" src="editor/ckeditor.js"></script>
	<!-- scripts -->
	<script language="javascript">
		{literal}var languages = [{/literal}{section name=l loop=$settings->languages|@count}{literal}{'id':'{/literal}{$settings->languages[l].id}', 'title':'{$settings->languages[l].title}{literal}'}{/literal}{if $smarty.section.l.index!=$settings->languages|@count-1},{/if}{/section}{literal}];{/literal}
	</script>
	<script type="text/javascript" src="assets/js/popup.js"></script>
	<script type="text/javascript" src="assets/js/scripts.js"></script>
	<script type="text/javascript" src="assets/js/login.js"></script>
	<script type="text/javascript" src="assets/js/categories.js"></script>
	<script type="text/javascript" src="assets/js/gallery.js"></script>

</head>
<body>
<div class="bg"></div>
<div class="wrapper">
	<div class="header">
		<div class="container">
			<div class="logo"></div>
			{if $menu!='index'}
				{include file='menu.tpl'}
			{/if}
			<div class="clear"></div>
		</div>
	</div>
	<div class="content">
		<div class="hat">
			<div class="hatshdw"></div>
		</div>
		<div class="lshdw"></div>
		<div class="rshdw"></div>