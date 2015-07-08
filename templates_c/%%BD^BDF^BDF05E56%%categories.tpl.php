<?php /* Smarty version 2.6.22, created on 2012-02-13 02:46:25
         compiled from modules/categories.tpl */ ?>
	<?php if ($this->_tpl_vars['module']->id): ?>
		<h1>Update categories module</h1>
	<?php endif; ?>

	<div class="success-box hidden" id="moduleSuccess"><div class="success">Module categories successfully <?php if ($this->_tpl_vars['module']->id): ?>updated<?php else: ?>added<?php endif; ?>!</div></div>

	<form action="module.php" id="<?php if ($this->_tpl_vars['module']->id): ?>edit<?php else: ?>add<?php endif; ?>module" method="post">

		<?php if (! $this->_tpl_vars['module']->id): ?>
			<p><i>This module will create a manageable tree of categories, which could be assigned to Complex Objects.</i></p>
		<?php endif; ?>
	
		<div class="error-box hidden" id="moduleError"><div class="error">Warning title is required!</div></div>

		<input type="hidden" name="action" value="<?php if ($this->_tpl_vars['module']->id): ?>edit<?php else: ?>add<?php endif; ?>"/>
		<input type="hidden" name="moduleid" value="<?php echo $this->_tpl_vars['module']->id; ?>
"/>
		<input type="hidden" name="page" id="page"/>
		<input type="hidden" name="module" value="categories"/>

		<div class="rows">
			<table class="form">
				<tr>
					<td>Title:</td>
					<td><input type="text" name="title" value="<?php echo $this->_tpl_vars['module']->title; ?>
"/></td>
					<td></td>
				</tr>
				<tr>
					<td>DB table name:</td>
					<td><input type="text" name="db_tbl" value="<?php echo $this->_tpl_vars['module']->db_table; ?>
"/></td>
					<td></td>
				</tr>
			</table>
		</div>

		<div class="submitLine">
			<a href="javascript:" class="btnGreen"><?php if ($this->_tpl_vars['module']->id): ?>Update<?php else: ?>Add<?php endif; ?> Module</a>
		</div>

	</form>