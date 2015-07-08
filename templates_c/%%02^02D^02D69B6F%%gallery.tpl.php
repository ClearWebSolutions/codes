<?php /* Smarty version 2.6.22, created on 2012-01-02 20:50:11
         compiled from gallery.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'gallery.tpl', 36, false),)), $this); ?>
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
			<table class="form">
				<tr>
					<td>Gallery name:</td>
					<td><input type="text" name="name" value="<?php echo $this->_tpl_vars['module']->title; ?>
"/></td>
					<td><i>would be used to name the menu in admin area</i></td>
				</tr>
				<tr>
					<td>Gallery folder:</td>
					<td><input type="text" name="folder" value="<?php echo $this->_tpl_vars['module']->folder; ?>
"/></td>
					<td><i>in the root of the website (e.g.: assets/imgs/gallery)</i></td>
				</tr>
				<tr>
					<td>Fancybox</td>
					<td><input type="radio" value="1" name="fancybox" <?php if ($this->_tpl_vars['module']->fancybox && $this->_tpl_vars['module']->id): ?>checked="checked"<?php elseif (! $this->_tpl_vars['module']->id): ?>checked="checked"<?php endif; ?>/> Yes &nbsp; <input type="radio" value="0" name="fancybox" <?php if (! $this->_tpl_vars['module']->fancybox && $this->_tpl_vars['module']->id): ?>checked="checked"<?php endif; ?>/> No</td>
					<td><i>uses "full" suffix images(creates them automatically)</i></td>
				</tr>
				<tr>
					<td>Amount of images to create:</td>
				<?php $this->assign('end', count($this->_tpl_vars['module']->sizes)); ?>
				<?php $this->assign('end', $this->_tpl_vars['end']-1); ?>
					<td>
						<select class="selectBox" name="amount" id="galleryamount">
								<option value=""></option>
							<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['start'] = (int)1;
$this->_sections['i']['loop'] = is_array($_loop=21) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['show'] = true;
$this->_sections['i']['max'] = $this->_sections['i']['loop'];
$this->_sections['i']['step'] = 1;
if ($this->_sections['i']['start'] < 0)
    $this->_sections['i']['start'] = max($this->_sections['i']['step'] > 0 ? 0 : -1, $this->_sections['i']['loop'] + $this->_sections['i']['start']);
else
    $this->_sections['i']['start'] = min($this->_sections['i']['start'], $this->_sections['i']['step'] > 0 ? $this->_sections['i']['loop'] : $this->_sections['i']['loop']-1);
if ($this->_sections['i']['show']) {
    $this->_sections['i']['total'] = min(ceil(($this->_sections['i']['step'] > 0 ? $this->_sections['i']['loop'] - $this->_sections['i']['start'] : $this->_sections['i']['start']+1)/abs($this->_sections['i']['step'])), $this->_sections['i']['max']);
    if ($this->_sections['i']['total'] == 0)
        $this->_sections['i']['show'] = false;
} else
    $this->_sections['i']['total'] = 0;
if ($this->_sections['i']['show']):

            for ($this->_sections['i']['index'] = $this->_sections['i']['start'], $this->_sections['i']['iteration'] = 1;
                 $this->_sections['i']['iteration'] <= $this->_sections['i']['total'];
                 $this->_sections['i']['index'] += $this->_sections['i']['step'], $this->_sections['i']['iteration']++):
$this->_sections['i']['rownum'] = $this->_sections['i']['iteration'];
$this->_sections['i']['index_prev'] = $this->_sections['i']['index'] - $this->_sections['i']['step'];
$this->_sections['i']['index_next'] = $this->_sections['i']['index'] + $this->_sections['i']['step'];
$this->_sections['i']['first']      = ($this->_sections['i']['iteration'] == 1);
$this->_sections['i']['last']       = ($this->_sections['i']['iteration'] == $this->_sections['i']['total']);
?>
								<option value="<?php echo $this->_sections['i']['index']; ?>
" <?php if ($this->_tpl_vars['end'] == $this->_sections['i']['index']): ?>selected="selected"<?php endif; ?>><?php echo $this->_sections['i']['index']; ?>
</option>
							<?php endfor; endif; ?>
						</select>
					</td>
					<td>&nbsp;</td>
				</tr>
			</table>
			<?php $this->assign('end', $this->_tpl_vars['end']+1); ?>
			<?php if ($this->_tpl_vars['module']->id && $this->_tpl_vars['end']): ?>
			<table class="galleryimgssettings">
				<tr>
					<th>Suffix</th>
					<th>Width</th>
					<th>Height</th>
					<th>Cut</th>
				</tr>
				<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['start'] = (int)1;
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['end']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['show'] = true;
$this->_sections['i']['max'] = $this->_sections['i']['loop'];
$this->_sections['i']['step'] = 1;
if ($this->_sections['i']['start'] < 0)
    $this->_sections['i']['start'] = max($this->_sections['i']['step'] > 0 ? 0 : -1, $this->_sections['i']['loop'] + $this->_sections['i']['start']);
else
    $this->_sections['i']['start'] = min($this->_sections['i']['start'], $this->_sections['i']['step'] > 0 ? $this->_sections['i']['loop'] : $this->_sections['i']['loop']-1);
if ($this->_sections['i']['show']) {
    $this->_sections['i']['total'] = min(ceil(($this->_sections['i']['step'] > 0 ? $this->_sections['i']['loop'] - $this->_sections['i']['start'] : $this->_sections['i']['start']+1)/abs($this->_sections['i']['step'])), $this->_sections['i']['max']);
    if ($this->_sections['i']['total'] == 0)
        $this->_sections['i']['show'] = false;
} else
    $this->_sections['i']['total'] = 0;
if ($this->_sections['i']['show']):

            for ($this->_sections['i']['index'] = $this->_sections['i']['start'], $this->_sections['i']['iteration'] = 1;
                 $this->_sections['i']['iteration'] <= $this->_sections['i']['total'];
                 $this->_sections['i']['index'] += $this->_sections['i']['step'], $this->_sections['i']['iteration']++):
$this->_sections['i']['rownum'] = $this->_sections['i']['iteration'];
$this->_sections['i']['index_prev'] = $this->_sections['i']['index'] - $this->_sections['i']['step'];
$this->_sections['i']['index_next'] = $this->_sections['i']['index'] + $this->_sections['i']['step'];
$this->_sections['i']['first']      = ($this->_sections['i']['iteration'] == 1);
$this->_sections['i']['last']       = ($this->_sections['i']['iteration'] == $this->_sections['i']['total']);
?>
				<tr>
					<td><input type="text" name="suffix<?php echo $this->_sections['i']['index']; ?>
" value="<?php echo $this->_tpl_vars['module']->sizes[$this->_sections['i']['index']]['suffix']; ?>
"/></td>
					<td><input type="text" name="width<?php echo $this->_sections['i']['index']; ?>
" value="<?php echo $this->_tpl_vars['module']->sizes[$this->_sections['i']['index']]['width']; ?>
"/></td>
					<td><input type="text" name="height<?php echo $this->_sections['i']['index']; ?>
" value="<?php echo $this->_tpl_vars['module']->sizes[$this->_sections['i']['index']]['height']; ?>
"/></td>
					<td><input type="radio" value="1" name="cut<?php echo $this->_sections['i']['index']; ?>
" <?php if ($this->_tpl_vars['module']->sizes[$this->_sections['i']['index']]['cut'] == 1): ?>checked="checked"<?php endif; ?>>Yes<input type="radio" value="0" <?php if ($this->_tpl_vars['module']->sizes[$this->_sections['i']['index']]['cut'] == 0): ?>checked="checked"<?php endif; ?> name="cut<?php echo $this->_sections['i']['index']; ?>
">No</td>
				</tr>
				<?php endfor; endif; ?>
			</table>
			<?php endif; ?>
		</div>

		<div class="submitLine">
			<a href="javascript:" class="btnGreen"><?php if ($this->_tpl_vars['module']->id): ?>Update<?php else: ?>Add<?php endif; ?> Module</a>
		</div>

	</form>