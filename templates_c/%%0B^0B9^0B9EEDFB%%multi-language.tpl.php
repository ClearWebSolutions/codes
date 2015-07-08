<?php /* Smarty version 2.6.22, created on 2012-01-03 02:10:55
         compiled from modules/multi-language.tpl */ ?>
	<div class="success-box hidden" id="moduleSuccess"><div class="success">Module multi-language successfully <?php if ($this->_tpl_vars['module']->id): ?>updated<?php else: ?>added<?php endif; ?>!</div></div>

	<form action="module.php" id="<?php if ($this->_tpl_vars['module']->id): ?>edit<?php else: ?>add<?php endif; ?>module" method="post">

		<?php if (! $this->_tpl_vars['module']->id): ?>
			<p><i>This module will add Vocabulary and languages management to the admin area.</i></p>
		<?php endif; ?>
	
		<div class="error-box hidden" id="moduleError"><div class="error">Warning title is required!</div></div>

		<input type="hidden" name="action" value="<?php if ($this->_tpl_vars['module']->id): ?>edit<?php else: ?>add<?php endif; ?>"/>
		<input type="hidden" name="moduleid" value="<?php echo $this->_tpl_vars['module']->id; ?>
"/>
		<input type="hidden" name="page" id="page"/>
		<input type="hidden" name="module" value="multilanguage"/>

		<div class="rows">
			<table class="form">
				<tr>
					<td>Language 1:</td>
					<td><input type="text" name="language1id" class="languageid" value="en" readonly="readonly"/></td>
					<td><input type="text" name="language1name" value="English" readonly="readonly"/></td>
				</tr>
			</table>
			<table class="form">
				<tr>
					<td>&nbsp;</td>
					<td><a href="javascript:" class="btnGrey">Add Language</a></td>
				</tr>
			</table>
		</div>

		<div class="submitLine">
			<a href="javascript:" class="btnGreen"><?php if ($this->_tpl_vars['module']->id): ?>Update<?php else: ?>Add<?php endif; ?> Module</a>
		</div>

	</form>