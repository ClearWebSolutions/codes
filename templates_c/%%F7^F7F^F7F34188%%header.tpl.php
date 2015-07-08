<?php /* Smarty version 2.6.22, created on 2012-11-14 17:10:44
         compiled from header.tpl */ ?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />	

	<link type="text/css" href="assets/css/style.css" rel="stylesheet"/>
	<link type="text/css" href="assets/js/jScrollPane/jquery.jscrollpane.css" rel="stylesheet" media="all" />
	<link type="text/css" href="assets/js/selectBox/jquery.selectBox.css" rel="stylesheet"/>
	<link type="text/css" href="assets/js/jqueryui/themes/base/jquery.ui.all.css" rel="stylesheet" />
	<link type="text/css" href="assets/js/jqueryui/themes/base/jquery.ui.selectmenu.css" rel="stylesheet" />

	<?php if ($this->_tpl_vars['page'] == 'site'): ?>
		<script data-main="assets/js/scripts" src="assets/js/require-jquery.js"></script>
	<?php else: ?>
		<script data-main="assets/js/first" src="assets/js/require-jquery.js"></script>
	<?php endif; ?>
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
		<?php if ($this->_tpl_vars['page'] == 'site'): ?>
			<div class="newwebsite right"><a href="javascript:"><?php echo $this->_tpl_vars['site']->sitename; ?>
</a><a href="index.php" class="back2websites">all websites</a></div>
		<?php else: ?>
			<div class="newwebsite right"><a href="javascript:">new website</a></div>
		<?php endif; ?>
		<div class="clear"></div>
	</div>
	</div>
	<div class="content pages">