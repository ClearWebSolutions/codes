<?php /* Smarty version 2.6.22, created on 2012-02-08 12:00:20
         compiled from modules/gallery.frm.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'modules/gallery.frm.tpl', 19, false),)), $this); ?>
			<table class="form" id="galleryfrm<?php echo $this->_tpl_vars['i']; ?>
">
				<tr>
					<td>Gallery name:</td>
					<td width="200"><input type="text" name="name<?php echo $this->_tpl_vars['i']; ?>
" value="<?php echo $this->_tpl_vars['gallery']->title; ?>
"/></td>
					<td><i>would be used to name the item in admin area</i></td>
				</tr>
				<tr>
					<td>Gallery folder:</td>
					<td><input type="text" name="folder<?php echo $this->_tpl_vars['i']; ?>
" value="<?php if ($this->_tpl_vars['gallery']->folder): ?><?php echo $this->_tpl_vars['gallery']->folder; ?>
<?php else: ?>assets/imgs/<?php endif; ?>"/></td>
					<td><i>in the root of the website (e.g.: assets/imgs/gallery)</i></td>
				</tr>
				<tr>
					<td>Amount of images to create:</td>
				<?php $this->assign('imgs_amnt', count($this->_tpl_vars['gallery']->sizes)); ?>
				<?php $this->assign('imgs_amnt', $this->_tpl_vars['imgs_amnt']-1); ?>
					<td>
						<select class="selectBox galleryamount" name="amount<?php echo $this->_tpl_vars['i']; ?>
" id="i<?php echo $this->_tpl_vars['i']; ?>
">
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
" <?php if ($this->_tpl_vars['imgs_amnt'] == $this->_sections['i']['index']): ?>selected="selected"<?php endif; ?>><?php echo $this->_sections['i']['index']; ?>
</option>
							<?php endfor; endif; ?>
						</select>
					</td>
					<td>&nbsp;</td>
				</tr>
			</table>
			<?php $this->assign('imgs_amnt', $this->_tpl_vars['imgs_amnt']+1); ?>
			<?php if ($this->_tpl_vars['gallery']->title && $this->_tpl_vars['imgs_amnt']): ?>
			<table class="galleryimgssettings" id="galleryimgssettings<?php echo $this->_tpl_vars['i']; ?>
">
				<tr>
					<th>Suffix</th>
					<th>Width</th>
					<th>Height</th>
					<th>Cut</th>
				</tr>
				<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['start'] = (int)1;
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['imgs_amnt']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
					<td><input type="text" name="suffix<?php echo $this->_tpl_vars['i']; ?>
_<?php echo $this->_sections['i']['index']; ?>
" value="<?php echo $this->_tpl_vars['gallery']->sizes[$this->_sections['i']['index']]['suffix']; ?>
"/></td>
					<td><input type="text" name="width<?php echo $this->_tpl_vars['i']; ?>
_<?php echo $this->_sections['i']['index']; ?>
" value="<?php echo $this->_tpl_vars['gallery']->sizes[$this->_sections['i']['index']]['width']; ?>
"/></td>
					<td><input type="text" name="height<?php echo $this->_tpl_vars['i']; ?>
_<?php echo $this->_sections['i']['index']; ?>
" value="<?php echo $this->_tpl_vars['gallery']->sizes[$this->_sections['i']['index']]['height']; ?>
"/></td>
					<td><input type="radio" value="1" name="cut<?php echo $this->_tpl_vars['i']; ?>
_<?php echo $this->_sections['i']['index']; ?>
" <?php if ($this->_tpl_vars['gallery']->sizes[$this->_sections['i']['index']]['cut'] == 1): ?>checked="checked"<?php endif; ?>>Yes<input type="radio" value="0" <?php if ($this->_tpl_vars['gallery']->sizes[$this->_sections['i']['index']]['cut'] == 0): ?>checked="checked"<?php endif; ?> name="cut<?php echo $this->_tpl_vars['i']; ?>
_<?php echo $this->_sections['i']['index']; ?>
">No</td>
				</tr>
				<?php endfor; endif; ?>
			</table>
			<?php endif; ?><br/>