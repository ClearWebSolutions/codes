<?php /* Smarty version 2.6.22, created on 2012-02-09 02:21:22
         compiled from modules/content.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'modules/content.tpl', 36, false),)), $this); ?>
<?php if ($this->_tpl_vars['module']->installed): ?>
	<div class="success-box" id="moduleSuccess"><div class="success">There is already a Content module installed for this page. Select the Content module from the list on the left to edit it.</div></div>
<?php else: ?>
	<?php if ($this->_tpl_vars['module']->id): ?>
		<h1>Update module Content</h1>
	<?php endif; ?>
	<div class="success-box hidden" id="moduleSuccess"><div class="success">Module Content successfully <?php if ($this->_tpl_vars['module']->id): ?>updated<?php else: ?>added<?php endif; ?>!</div></div>

	<form action="module.php" id="<?php if ($this->_tpl_vars['module']->id): ?>edit<?php else: ?>add<?php endif; ?>module" method="post">

		<?php if (! $this->_tpl_vars['module']->id): ?>
			<p><i>This module will create an editable content areas and gallery if required. Module would be applied to the <span id="selectedpage"></span> page.</i></p>
		<?php endif; ?>
	
		<div class="error-box hidden" id="moduleError"><div class="error"></div></div>

		<input type="hidden" name="action" value="<?php if ($this->_tpl_vars['module']->id): ?>edit<?php else: ?>add<?php endif; ?>"/>
		<input type="hidden" name="moduleid" value="<?php echo $this->_tpl_vars['module']->id; ?>
"/>
		<input type="hidden" name="page" id="page"/>
		<input type="hidden" name="module" value="content"/>
		<input type="hidden" name="warning" id="warning" value=""/>

		<div class="rows">
			<table class="form">
				<tr>
					<td>Page title:</td>
					<td><input type="text" name="title" value="<?php echo $this->_tpl_vars['module']->title; ?>
"/></td>
					<td><i>would be used to name the menu in admin area</i></td>
				</tr>

				<tr>
					<td>Parent:</td>
					<td>
						<select class="selectBox" name="parent">
							<option value="0"></option>
							<?php unset($this->_sections['p']);
$this->_sections['p']['name'] = 'p';
$this->_sections['p']['loop'] = is_array($_loop=count($this->_tpl_vars['site']->pages)) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['p']['show'] = true;
$this->_sections['p']['max'] = $this->_sections['p']['loop'];
$this->_sections['p']['step'] = 1;
$this->_sections['p']['start'] = $this->_sections['p']['step'] > 0 ? 0 : $this->_sections['p']['loop']-1;
if ($this->_sections['p']['show']) {
    $this->_sections['p']['total'] = $this->_sections['p']['loop'];
    if ($this->_sections['p']['total'] == 0)
        $this->_sections['p']['show'] = false;
} else
    $this->_sections['p']['total'] = 0;
if ($this->_sections['p']['show']):

            for ($this->_sections['p']['index'] = $this->_sections['p']['start'], $this->_sections['p']['iteration'] = 1;
                 $this->_sections['p']['iteration'] <= $this->_sections['p']['total'];
                 $this->_sections['p']['index'] += $this->_sections['p']['step'], $this->_sections['p']['iteration']++):
$this->_sections['p']['rownum'] = $this->_sections['p']['iteration'];
$this->_sections['p']['index_prev'] = $this->_sections['p']['index'] - $this->_sections['p']['step'];
$this->_sections['p']['index_next'] = $this->_sections['p']['index'] + $this->_sections['p']['step'];
$this->_sections['p']['first']      = ($this->_sections['p']['iteration'] == 1);
$this->_sections['p']['last']       = ($this->_sections['p']['iteration'] == $this->_sections['p']['total']);
?>
								<option value="<?php echo $this->_tpl_vars['site']->pages[$this->_sections['p']['index']]['id']; ?>
" <?php if ($this->_tpl_vars['module']->parent == $this->_tpl_vars['site']->pages[$this->_sections['p']['index']]['id']): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['site']->pages[$this->_sections['p']['index']]['name']; ?>
.php</option>
							<?php endfor; endif; ?>
						</select>
					</td>
					<td><i>if not selected, page would be added directly to Pages menu in admin area</i></td>
				</tr>
				<tr>
					<td>This page is template:</td>
					<td><input type="radio" name="is_template" value="1" <?php if ($this->_tpl_vars['module']->is_template == 1): ?>checked="checked"<?php endif; ?>/>Yes <input type="radio" name="is_template" value="0" <?php if ($this->_tpl_vars['module']->is_template != 1): ?>checked="checked"<?php endif; ?>/>No</td>
					<td>defines the template for the pages created by user via Add New menu, Parent page is required</td>
				</tr>
				<tr>
					<td>Amount of editing zones:</td>
					<td>
						<select class="selectBox" name="content_areas" id="content_areas">
							<option value=""></option>
							<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['start'] = (int)1;
$this->_sections['i']['loop'] = is_array($_loop=6) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
" <?php if ($this->_tpl_vars['module']->content_areas == $this->_sections['i']['index']): ?>selected="selected"<?php endif; ?>><?php echo $this->_sections['i']['index']; ?>
</option>
							<?php endfor; endif; ?>
						</select>
					</td>
					<td>&nbsp;</td>
				</tr>
				<?php if ($this->_tpl_vars['module']->content_areas): ?>
					<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['start'] = (int)1;
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['module']->content_areas+1) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
						<td>Content <?php echo $this->_sections['i']['index']; ?>
:</td>
						<td><input type="text" name="content<?php echo $this->_sections['i']['index']; ?>
" value="<?php echo $this->_tpl_vars['module']->content[$this->_sections['i']['index']]['title']; ?>
"/></td>
						<td></td>
					</tr>
					<?php endfor; endif; ?>
				<?php endif; ?>
			</table>




			<div class="row1">
			<ul class="categories">
				<li>
					<div class="cat">
						<div class="clear"></div>
						<div class="title"><span class="icon"></span>Gallery<?php echo $this->_tpl_vars['module']->gallery; ?>
</div>
						<div><input type="radio" name="gallery" value="1" <?php if ($this->_tpl_vars['module']->gallery == 1): ?>checked="checked"<?php endif; ?>/> Yes &nbsp; <input type="radio" name="gallery" value="0" <?php if ($this->_tpl_vars['module']->gallery == 0): ?>checked="checked"<?php elseif ($this->_tpl_vars['module']->gallery != 1): ?>checked="checked"<?php endif; ?>/> No</div>
						<div class="clear"></div>
					</div>
					<div class="subcats">
						<table class="form">
							<tr>
								<td>Allow multi-galleries:</td>
								<td width="215"><input type="radio" name="multi_galleries" value="1" <?php if ($this->_tpl_vars['module']->galleries_multi == 1): ?>checked="checked"<?php endif; ?>/> Yes &nbsp; <input type="radio" name="multi_galleries" value="0" <?php if ($this->_tpl_vars['module']->galleries_multi != 1): ?>checked="checked"<?php endif; ?>/> No</td>
								<td><i>last gallery settings below would be used as multi</i></td>
							</tr>
							<tr id="exact_galleries">
								<td>and/or Have exactly:</td>
								<td>
									<select name="galleries_amnt" id="galleries_amnt">
										<?php $this->assign('end', count($this->_tpl_vars['module']->galleries)); ?>
										<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['start'] = (int)1;
$this->_sections['i']['loop'] = is_array($_loop=6) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
									</select> &nbsp; galleries</td>
								<td></td>
							</tr>
						</table>
						<div class="galleries">
							<?php if ($this->_tpl_vars['end'] > 0): ?>
								<?php $this->assign('end', $this->_tpl_vars['end']+1); ?>
								<?php unset($this->_sections['j']);
$this->_sections['j']['name'] = 'j';
$this->_sections['j']['start'] = (int)1;
$this->_sections['j']['loop'] = is_array($_loop=$this->_tpl_vars['end']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['j']['show'] = true;
$this->_sections['j']['max'] = $this->_sections['j']['loop'];
$this->_sections['j']['step'] = 1;
if ($this->_sections['j']['start'] < 0)
    $this->_sections['j']['start'] = max($this->_sections['j']['step'] > 0 ? 0 : -1, $this->_sections['j']['loop'] + $this->_sections['j']['start']);
else
    $this->_sections['j']['start'] = min($this->_sections['j']['start'], $this->_sections['j']['step'] > 0 ? $this->_sections['j']['loop'] : $this->_sections['j']['loop']-1);
if ($this->_sections['j']['show']) {
    $this->_sections['j']['total'] = min(ceil(($this->_sections['j']['step'] > 0 ? $this->_sections['j']['loop'] - $this->_sections['j']['start'] : $this->_sections['j']['start']+1)/abs($this->_sections['j']['step'])), $this->_sections['j']['max']);
    if ($this->_sections['j']['total'] == 0)
        $this->_sections['j']['show'] = false;
} else
    $this->_sections['j']['total'] = 0;
if ($this->_sections['j']['show']):

            for ($this->_sections['j']['index'] = $this->_sections['j']['start'], $this->_sections['j']['iteration'] = 1;
                 $this->_sections['j']['iteration'] <= $this->_sections['j']['total'];
                 $this->_sections['j']['index'] += $this->_sections['j']['step'], $this->_sections['j']['iteration']++):
$this->_sections['j']['rownum'] = $this->_sections['j']['iteration'];
$this->_sections['j']['index_prev'] = $this->_sections['j']['index'] - $this->_sections['j']['step'];
$this->_sections['j']['index_next'] = $this->_sections['j']['index'] + $this->_sections['j']['step'];
$this->_sections['j']['first']      = ($this->_sections['j']['iteration'] == 1);
$this->_sections['j']['last']       = ($this->_sections['j']['iteration'] == $this->_sections['j']['total']);
?>
									<?php $this->assign('i', $this->_sections['j']['index']); ?>
									<?php $this->assign('gallery', $this->_tpl_vars['module']->galleries[$this->_sections['j']['index']]); ?>
									<div class="separator"></div>
									<div class="gal"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/gallery.frm.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></div>
								<?php endfor; endif; ?>
							<?php else: ?>
								<div class="separator"></div>
								<?php $this->assign('i', 1); ?>
								<div class="gal"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/gallery.frm.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></div>
							<?php endif; ?>
						</div>
					</div>
				</li>
			</ul>
			</div>


		</div>

		<div class="submitLine">
			<a href="javascript:" class="btnGreen"><?php if ($this->_tpl_vars['module']->id): ?>Update<?php else: ?>Add<?php endif; ?> Module</a>
		</div>

	</form>
<?php endif; ?>