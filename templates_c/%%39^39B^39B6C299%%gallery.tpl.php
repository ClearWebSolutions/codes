<?php /* Smarty version 2.6.22, created on 2012-02-08 13:12:14
         compiled from modules/gallery.tpl */ ?>
	<?php if ($this->_tpl_vars['module']->id): ?>
		<h1>Update Gallery module</h1>
	<?php endif; ?>
	<div class="success-box hidden" id="moduleSuccess"><div class="success">Module Gallery successfully <?php if ($this->_tpl_vars['module']->id): ?>updated<?php else: ?>added<?php endif; ?>!</div></div>

	<form action="module.php" id="<?php if ($this->_tpl_vars['module']->id): ?>edit<?php else: ?>add<?php endif; ?>module" method="post">

		<?php if (! $this->_tpl_vars['module']->id): ?>
			<p><i>This module will create a manageable gallery for the <span id="selectedpage"></span> page.</i></p>
			<p><i>Amount of images here is the amount of copies of each uploaded image you would like to have, with the suffix and sizes you define.</i></p>
		<?php endif; ?>
	
		<div class="error-box hidden" id="moduleError"><div class="error">Warning title is required!</div></div>

		<input type="hidden" name="action" value="<?php if ($this->_tpl_vars['module']->id): ?>edit<?php else: ?>add<?php endif; ?>"/>
		<input type="hidden" name="moduleid" value="<?php echo $this->_tpl_vars['module']->id; ?>
"/>
		<input type="hidden" name="page" id="page"/>
		<input type="hidden" name="module" value="gallery"/>

		<div class="rows">
			<?php $this->assign('gallery', $this->_tpl_vars['module']); ?>
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/gallery.frm.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		</div>

		<div class="submitLine">
			<a href="javascript:" class="btnGreen"><?php if ($this->_tpl_vars['module']->id): ?>Update<?php else: ?>Add<?php endif; ?> Module</a>
		</div>

	</form>