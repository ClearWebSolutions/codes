<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />	

	<link type="text/css" href="assets/css/style.css" rel="stylesheet"/>
	<link type="text/css" href="assets/js/jScrollPane/jquery.jscrollpane.css" rel="stylesheet" media="all" />
	<link type="text/css" href="assets/js/selectBox/jquery.selectBox.css" rel="stylesheet"/>
	<link type="text/css" href="assets/js/jqueryui/themes/base/jquery.ui.all.css" rel="stylesheet" />
	<link type="text/css" href="assets/js/jqueryui/themes/base/jquery.ui.selectmenu.css" rel="stylesheet" />

	{if $page=='site'}
		<script data-main="assets/js/scripts" src="assets/js/require-jquery.js"></script>
	{else}
		<script data-main="assets/js/first" src="assets/js/require-jquery.js"></script>
	{/if}
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
	<script type="text/javascript" src="assets/js/browser/cssBrowserDetect.js"></script>
	<script type="text/javascript" src="assets/js/ajaxform/jquery.form.js"></script>
	<script type="text/javascript" src="assets/js/jScrollPane/jquery.mousewheel.js"></script>
	<script type="text/javascript" src="assets/js/jScrollPane/jquery.jscrollpane.min.js"></script>
	<script type="text/javascript" src="assets/js/jqueryui/ui/jquery.ui.core.js"></script>
	<script type="text/javascript" src="assets/js/jqueryui/ui/jquery.ui.widget.js"></script>
	<script type="text/javascript" src="assets/js/jqueryui/ui/jquery.ui.position.js"></script>
	<script type="text/javascript" src="assets/js/jqueryui/ui/jquery.ui.selectmenu.js"></script>
</head>
<body>
<div class="bg"></div>
<div class="wrapper">
	<div class="header">
	<div class="container">
		<div class="logo"></div>
		{if $page=='site'}
			<div class="newwebsite right"><a href="javascript:">{$site->sitename}</a><a href="index.php" class="back2websites">all websites</a></div>
		{else}
			<div class="newwebsite right"><a href="javascript:">new website</a></div>
		{/if}
		<div class="clear"></div>
	</div>
	</div>
	<div class="content pages">
