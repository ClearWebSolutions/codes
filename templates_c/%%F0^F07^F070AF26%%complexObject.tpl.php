<?php /* Smarty version 2.6.22, created on 2012-11-28 15:34:57
         compiled from modules/complexObject.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'ucfirst', 'modules/complexObject.tpl', 34, false),array('modifier', 'cat', 'modules/complexObject.tpl', 52, false),array('modifier', 'count', 'modules/complexObject.tpl', 294, false),)), $this); ?>
<?php if ($this->_tpl_vars['module']->installed): ?>
	<div class="success-box" id="moduleSuccess"><div class="success">There is already a Content module installed for this page. Select the Content module from the list on the left to edit it.</div></div>
<?php else: ?>
	<?php if ($this->_tpl_vars['module']->id): ?>
		<h1>Update module Complex Object</h1>
		<p><i>Update would cause the loosing of all the data in the database as it only should be done on the development environment with development database!</i></p>
	<?php endif; ?>
	<div class="success-box hidden" id="moduleSuccess"><div class="success">Module Complex Object successfully <?php if ($this->_tpl_vars['module']->id): ?>updated<?php else: ?>added<?php endif; ?>!</div></div>

	<form action="module.php" id="<?php if ($this->_tpl_vars['module']->id): ?>edit<?php else: ?>add<?php endif; ?>module" method="post">

		<?php if (! $this->_tpl_vars['module']->id): ?>
			<p><i>This module will create an ability to add/edit/delete the objects with properties defined below. Module would be applied to the <span id="selectedpage"></span> page.</i></p>
		<?php endif; ?>
	
		<div class="error-box hidden" id="moduleError"><div class="error"></div></div>

		<input type="hidden" name="action" value="<?php if ($this->_tpl_vars['module']->id): ?>edit<?php else: ?>add<?php endif; ?>"/>
		<input type="hidden" name="moduleid" value="<?php echo $this->_tpl_vars['module']->id; ?>
"/>
		<input type="hidden" name="page" id="page"/>
		<input type="hidden" name="module" value="complexObject"/>
		<input type="hidden" name="warning" id="warning" value=""/>

		<div class="rows">
			<table class="form">
				<tr>
					<td>Title:</td>
					<td><input type="text" name="title" value="<?php echo $this->_tpl_vars['module']->title; ?>
"/></td>
					<td></td>
				</tr>
				<tr>
					<td>DB table:</td>
					<td><input type="text" name="db_tbl" value="<?php echo $this->_tpl_vars['module']->tbl; ?>
"/></td>
					<td><?php if ($this->_tpl_vars['module']->id): ?><i>Changing the table name would lead to loosing the previous table and its data!<br/>Also the <?php echo ((is_array($_tmp=$this->_tpl_vars['module']->tbl)) ? $this->_run_mod_handler('ucfirst', true, $_tmp) : ucfirst($_tmp)); ?>
Base.php as well as <?php echo ((is_array($_tmp=$this->_tpl_vars['module']->tbl)) ? $this->_run_mod_handler('ucfirst', true, $_tmp) : ucfirst($_tmp)); ?>
.php classes would be deleted!</i><?php endif; ?></td>
				</tr>
				<tr class="hidden">
					<td>Multilanguage:</td>
					<td><input type="radio" name="multilanguage" value="1" <?php if ($this->_tpl_vars['module']->id): ?><?php if ($this->_tpl_vars['module']->multilanguage == 1): ?>checked="checked"<?php endif; ?><?php else: ?>checked="checked"<?php endif; ?>/> Yes &nbsp; <input type="radio" name="multilanguage" value="0" <?php if ($this->_tpl_vars['module']->id && $this->_tpl_vars['module']->multilanguage == 0): ?>checked="checked"<?php endif; ?>/> No</td>
					<td></td>
				</tr>
			</table>


			<div class="properties">
				<input type="hidden" name="ttl" value="<?php if ($this->_tpl_vars['module']->id): ?><?php echo $this->_tpl_vars['module']->request['ttl']; ?>
<?php else: ?>1<?php endif; ?>"/>
				<table cellpadding="0" cellspacing="0" id="fieldsTable">
					<?php if ($this->_tpl_vars['module']->id): ?>
					<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['start'] = (int)1;
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['module']->request['ttl']+1) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
						<td width="20%">
							<label>Field title</label>
							<?php $this->assign('index', ((is_array($_tmp='title')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_sections['i']['index']) : smarty_modifier_cat($_tmp, $this->_sections['i']['index']))); ?>
							<input type="text" name="title<?php echo $this->_sections['i']['index']; ?>
" class="fieldtitle" value="<?php echo $this->_tpl_vars['module']->request[$this->_tpl_vars['index']]; ?>
"/><br/>
							<?php $this->assign('oindex', ((is_array($_tmp='optionsttl')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_sections['i']['index']) : smarty_modifier_cat($_tmp, $this->_sections['i']['index']))); ?>
							<?php $this->assign('tindex', ((is_array($_tmp='type')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_sections['i']['index']) : smarty_modifier_cat($_tmp, $this->_sections['i']['index']))); ?>
							<?php if ($this->_tpl_vars['module']->request[$this->_tpl_vars['tindex']] == 'select' || $this->_tpl_vars['module']->request[$this->_tpl_vars['tindex']] == 'radio'): ?>
							<span class="optionsTitle options"><input type="hidden" name="optionsttl<?php echo $this->_sections['i']['index']; ?>
" value="<?php echo $this->_tpl_vars['module']->request[$this->_tpl_vars['oindex']]; ?>
" class="optionsttl"/>Radio Options:
								<?php unset($this->_sections['j']);
$this->_sections['j']['name'] = 'j';
$this->_sections['j']['loop'] = is_array($_loop=$this->_tpl_vars['module']->request[$this->_tpl_vars['oindex']]) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['j']['show'] = true;
$this->_sections['j']['max'] = $this->_sections['j']['loop'];
$this->_sections['j']['step'] = 1;
$this->_sections['j']['start'] = $this->_sections['j']['step'] > 0 ? 0 : $this->_sections['j']['loop']-1;
if ($this->_sections['j']['show']) {
    $this->_sections['j']['total'] = $this->_sections['j']['loop'];
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
									<a class="deleteSmall"></a>
								<?php endfor; endif; ?>
							</span>
							<?php endif; ?>
						</td>
						<td>
							<label>Input type</label>
							<select name="type<?php echo $this->_sections['i']['index']; ?>
" class="inputType">
								<option value="text" <?php if ($this->_tpl_vars['module']->request[$this->_tpl_vars['tindex']] == 'text'): ?>selected="selected"<?php endif; ?>>text</option>
								<option value="date" <?php if ($this->_tpl_vars['module']->request[$this->_tpl_vars['tindex']] == 'date'): ?>selected="selected"<?php endif; ?>>date</option>
								<option value="radio" <?php if ($this->_tpl_vars['module']->request[$this->_tpl_vars['tindex']] == 'radio'): ?>selected="selected"<?php endif; ?>>radio</option>
								<option value="select" <?php if ($this->_tpl_vars['module']->request[$this->_tpl_vars['tindex']] == 'select'): ?>selected="selected"<?php endif; ?>>select</option>
								<option value="textarea" <?php if ($this->_tpl_vars['module']->request[$this->_tpl_vars['tindex']] == 'textarea'): ?>selected="selected"<?php endif; ?>>textarea</option>
								<option value="html" <?php if ($this->_tpl_vars['module']->request[$this->_tpl_vars['tindex']] == 'html'): ?>selected="selected"<?php endif; ?>>HTML editor</option>
							</select>
							<?php if ($this->_tpl_vars['module']->request[$this->_tpl_vars['tindex']] == 'select' || $this->_tpl_vars['module']->request[$this->_tpl_vars['tindex']] == 'radio'): ?>
							<span class="options">
								<?php unset($this->_sections['j']);
$this->_sections['j']['name'] = 'j';
$this->_sections['j']['start'] = (int)1;
$this->_sections['j']['loop'] = is_array($_loop=$this->_tpl_vars['module']->request[$this->_tpl_vars['oindex']]+1) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
								<?php $this->assign('index', ((is_array($_tmp='optionvalue')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_sections['i']['index']) : smarty_modifier_cat($_tmp, $this->_sections['i']['index']))); ?>
								<?php $this->assign('index', ((is_array($_tmp=$this->_tpl_vars['index'])) ? $this->_run_mod_handler('cat', true, $_tmp, '_') : smarty_modifier_cat($_tmp, '_'))); ?>
								<?php $this->assign('index', ((is_array($_tmp=$this->_tpl_vars['index'])) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_sections['j']['index']) : smarty_modifier_cat($_tmp, $this->_sections['j']['index']))); ?>
								<input type="text" placeholder="option value" class="mt5" name="optionvalue<?php echo $this->_sections['i']['index']; ?>
_<?php echo $this->_sections['j']['index']; ?>
" value="<?php echo $this->_tpl_vars['module']->request[$this->_tpl_vars['index']]; ?>
"/>
								<?php endfor; endif; ?>
								<br/><a href="javascript:" class="addOneMore">Add One More Option</a>
							</span>
							<?php endif; ?>
						</td>
						<td width="20%">
							<label>DB field name</label>
							<?php $this->assign('index', ((is_array($_tmp='dbfield')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_sections['i']['index']) : smarty_modifier_cat($_tmp, $this->_sections['i']['index']))); ?>
							<input type="text" name="dbfield<?php echo $this->_sections['i']['index']; ?>
" class="dbfield" value="<?php echo $this->_tpl_vars['module']->request[$this->_tpl_vars['index']]; ?>
"/>
							<?php if ($this->_tpl_vars['module']->request[$this->_tpl_vars['tindex']] == 'select' || $this->_tpl_vars['module']->request[$this->_tpl_vars['tindex']] == 'radio'): ?>
							<span class="options">
								<?php unset($this->_sections['j']);
$this->_sections['j']['name'] = 'j';
$this->_sections['j']['start'] = (int)1;
$this->_sections['j']['loop'] = is_array($_loop=$this->_tpl_vars['module']->request[$this->_tpl_vars['oindex']]+1) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
								<?php $this->assign('index', ((is_array($_tmp='optionname')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_sections['i']['index']) : smarty_modifier_cat($_tmp, $this->_sections['i']['index']))); ?>
								<?php $this->assign('index', ((is_array($_tmp=$this->_tpl_vars['index'])) ? $this->_run_mod_handler('cat', true, $_tmp, '_') : smarty_modifier_cat($_tmp, '_'))); ?>
								<?php $this->assign('index', ((is_array($_tmp=$this->_tpl_vars['index'])) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_sections['j']['index']) : smarty_modifier_cat($_tmp, $this->_sections['j']['index']))); ?>
								<input type="text" placeholder="option name" class="mt5" name="optionname<?php echo $this->_sections['i']['index']; ?>
_<?php echo $this->_sections['j']['index']; ?>
" value="<?php echo $this->_tpl_vars['module']->request[$this->_tpl_vars['index']]; ?>
"/>
								<?php endfor; endif; ?>
							</span>
							<?php endif; ?>
						</td>
						<td>
							<label>DB type</label>
							<select name="dbtype<?php echo $this->_sections['i']['index']; ?>
" class="dbType">
								<?php $this->assign('index', ((is_array($_tmp='dbtype')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_sections['i']['index']) : smarty_modifier_cat($_tmp, $this->_sections['i']['index']))); ?>
								<option value="varchar" <?php if ($this->_tpl_vars['module']->request[$this->_tpl_vars['index']] == 'varchar'): ?>selected="selected"<?php endif; ?>>varchar</option>
								<option value="int" <?php if ($this->_tpl_vars['module']->request[$this->_tpl_vars['index']] == 'int'): ?>selected="selected"<?php endif; ?>>int</option>
								<option value="tinyint" <?php if ($this->_tpl_vars['module']->request[$this->_tpl_vars['index']] == 'tinyint'): ?>selected="selected"<?php endif; ?>>tinyint</option>
								<option value="double" <?php if ($this->_tpl_vars['module']->request[$this->_tpl_vars['index']] == 'double'): ?>selected="selected"<?php endif; ?>>double</option>
								<option value="date" <?php if ($this->_tpl_vars['module']->request[$this->_tpl_vars['index']] == 'date'): ?>selected="selected"<?php endif; ?>>date</option>
								<option value="datetime" <?php if ($this->_tpl_vars['module']->request[$this->_tpl_vars['index']] == 'datetime'): ?>selected="selected"<?php endif; ?>>datetime</option>
								<option value="timestamp" <?php if ($this->_tpl_vars['module']->request[$this->_tpl_vars['index']] == 'timestamp'): ?>selected="selected"<?php endif; ?>>timestamp</option>
								<option value="blob" <?php if ($this->_tpl_vars['module']->request[$this->_tpl_vars['index']] == 'blob'): ?>selected="selected"<?php endif; ?>>blob</option>
							</select>
							<?php if ($this->_tpl_vars['module']->request[$this->_tpl_vars['tindex']] == 'select' || $this->_tpl_vars['module']->request[$this->_tpl_vars['tindex']] == 'radio'): ?>
							<span class="options">
								<?php unset($this->_sections['j']);
$this->_sections['j']['name'] = 'j';
$this->_sections['j']['start'] = (int)1;
$this->_sections['j']['loop'] = is_array($_loop=$this->_tpl_vars['module']->request[$this->_tpl_vars['oindex']]+1) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
								<?php $this->assign('index', ((is_array($_tmp='optiondefault')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_sections['i']['index']) : smarty_modifier_cat($_tmp, $this->_sections['i']['index']))); ?>
								<?php $this->assign('index', ((is_array($_tmp=$this->_tpl_vars['index'])) ? $this->_run_mod_handler('cat', true, $_tmp, '_') : smarty_modifier_cat($_tmp, '_'))); ?>
								<?php $this->assign('index', ((is_array($_tmp=$this->_tpl_vars['index'])) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_sections['j']['index']) : smarty_modifier_cat($_tmp, $this->_sections['j']['index']))); ?>
								<div><input type="checkbox" class="mt5" value="1" name="optiondefault<?php echo $this->_sections['i']['index']; ?>
_<?php echo $this->_sections['j']['index']; ?>
" <?php if ($this->_tpl_vars['module']->request[$this->_tpl_vars['index']] == 1): ?>checked="checked"<?php endif; ?>/> default value</div>
								<?php endfor; endif; ?>
							</span>
							<?php endif; ?>
						</td>
						<td>
							<label>DB length</label>
							<?php $this->assign('index', ((is_array($_tmp='dblength')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_sections['i']['index']) : smarty_modifier_cat($_tmp, $this->_sections['i']['index']))); ?>
							<input type="text" value="<?php echo $this->_tpl_vars['module']->request[$this->_tpl_vars['index']]; ?>
" class="smallinput dblength" name="dblength<?php echo $this->_sections['i']['index']; ?>
"/>
						</td>
						<td>
							<label>DB default</label>
							<?php $this->assign('index', ((is_array($_tmp='dbdefault')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_sections['i']['index']) : smarty_modifier_cat($_tmp, $this->_sections['i']['index']))); ?>
							<input type="text" class="smallinput dbdefault" name="dbdefault<?php echo $this->_sections['i']['index']; ?>
" value="<?php echo $this->_tpl_vars['module']->request[$this->_tpl_vars['index']]; ?>
"/>
						</td>
						<td class="fs10" width="100px">
							<a class="deleteSmall"></a>
							<?php $this->assign('index', ((is_array($_tmp='required')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_sections['i']['index']) : smarty_modifier_cat($_tmp, $this->_sections['i']['index']))); ?>
							<input type="checkbox" class="required" name="required<?php echo $this->_sections['i']['index']; ?>
" value="1" <?php if ($this->_tpl_vars['module']->request[$this->_tpl_vars['index']] == 1): ?>checked="checked"<?php endif; ?>/>Required<br/>
							<?php $this->assign('index', ((is_array($_tmp='searchable')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_sections['i']['index']) : smarty_modifier_cat($_tmp, $this->_sections['i']['index']))); ?>
							<input type="checkbox" class="searchable" name="searchable<?php echo $this->_sections['i']['index']; ?>
" value="1" <?php if ($this->_tpl_vars['module']->request[$this->_tpl_vars['index']] == 1): ?>checked="checked"<?php endif; ?>/>Searchable<br/>
							<?php $this->assign('index', ((is_array($_tmp='admindisplay')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_sections['i']['index']) : smarty_modifier_cat($_tmp, $this->_sections['i']['index']))); ?>
							<input type="checkbox" class="admindisplay" name="admindisplay<?php echo $this->_sections['i']['index']; ?>
" value="1" <?php if ($this->_tpl_vars['module']->request[$this->_tpl_vars['index']] == 1): ?>checked="checked"<?php endif; ?>/>Admin Display<br/>
							<?php $this->assign('index', ((is_array($_tmp='multilanguage')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_sections['i']['index']) : smarty_modifier_cat($_tmp, $this->_sections['i']['index']))); ?>
							<span><input type="checkbox" class="multilanguage" name="multilanguage<?php echo $this->_sections['i']['index']; ?>
" value="1" <?php if ($this->_tpl_vars['module']->request[$this->_tpl_vars['index']] == 1): ?>checked="checked"<?php endif; ?>/>Multi-language</span>
						</td>
					</tr>
					<?php endfor; endif; ?>
					<?php else: ?>
					<tr>
						<td width="20%">
							<label>Field title</label>
							<input type="text" name="title1" class="fieldtitle"/><br/>
							<!--<span class="optionsTitle options"><input type="hidden" name="optionsttl1" value="1" class="optionsttl"/>Radio Options:<a class="deleteSmall"></a></span>-->
						</td>
						<td>
							<label>Input type</label>
							<select name="type1" class="inputType">
								<option value="text">text</option>
								<option value="date">date</option>
								<option value="radio">radio</option>
								<option value="select">select</option>
								<option value="textarea">textarea</option>
								<option value="html">HTML editor</option>
							</select>
							<!--<span class="options">
								<input type="text" placeholder="option value" class="mt5" name="optionvalue1_1"/>
								<input type="text" placeholder="option value" class="mt5" name="optionvalue1_2"/>
								<br/><a href="#" class="addOneMore">Add One More Option</a>
							</span>-->
						</td>
						<td width="20%">
							<label>DB field name</label>
							<input type="text" name="dbfield1" class="dbfield"/>
							<!--<span class="options">
								<input type="text" placeholder="option name" class="mt5" name="optionname1_1"/>
								<input type="text" placeholder="option name" class="mt5" name="optionname1_2"/>
							</span>-->
						</td>
						<td>
							<label>DB type</label>
							<select name="dbtype1" class="dbType">
								<option value="varchar">varchar</option>
								<option value="int">int</option>
								<option value="tinyint">tinyint</option>
								<option value="double">double</option>
								<option value="date">date</option>
								<option value="datetime">datetime</option>
								<option value="timestamp">timestamp</option>
								<option value="blob">blob</option>
							</select>
						</td>
						<td>
							<label>DB length</label>
							<input type="text" value="255" class="smallinput dblength" name="dblength1"/>
						</td>
						<td>
							<label>DB default</label>
							<input type="text" class="smallinput dbdefault" name="dbdefault1"/>
						</td>
						<td class="fs10" width="100px">
							<a class="deleteSmall"></a>
							<input type="checkbox" class="required" name="required1" value="1"/>Required<br/>
							<input type="checkbox" class="searchable" name="searchable1" value="1"/>Searchable<br/>
							<input type="checkbox" class="admindisplay" name="admindisplay1" value="1"/>Admin Display<br/>
							<span><input type="checkbox" class="multilanguage" name="multilanguage1" value="1" checked="checked"/>Multi-language</span>
						</td>
					</tr>
					<?php endif; ?>
				</table>
<br/>
				<a href="javascript:" class="btnGrey addField">Add One More Field</a>
<br/><br/>
			</div>



			<div class="row1">
			<ul class="categories">
				<li>
					<div class="cat">
						<div class="clear"></div>
						<div class="title"><span class="icon"></span>Galleries</div>
						<div><input type="radio" name="gallery" value="1" <?php if ($this->_tpl_vars['module']->request['gallery'] == 1): ?>checked="checked"<?php endif; ?>/> Yes &nbsp; <input type="radio" name="gallery" value="0" <?php if ($this->_tpl_vars['module']->request['gallery'] == 0): ?>checked="checked"<?php elseif ($this->_tpl_vars['module']->request['gallery'] != 1): ?>checked="checked"<?php endif; ?>/> No<div class="right pr20"><input type="checkbox" name="gallery_admindisplay" value="1" <?php if ($this->_tpl_vars['module']->id && $this->_tpl_vars['module']->request['gallery_admindisplay'] == 0): ?><?php else: ?>checked="checked"<?php endif; ?>/>Admin Display</div></div>
						<div class="clear"></div>
					</div>
					<div class="subcats">
						<table class="form">
							<tr>
								<td>Allow multi-galleries:</td>
								<td width="215"><input type="radio" name="multi_galleries" value="1" <?php if ($this->_tpl_vars['module']->request['multi_galleries'] == 1): ?>checked="checked"<?php endif; ?>/> Yes &nbsp; <input type="radio" name="multi_galleries" value="0" <?php if ($this->_tpl_vars['module']->request['multi_galleries'] != 1): ?>checked="checked"<?php endif; ?>/> No</td>
								<td><i>last gallery settings below would be used as multi</i></td>
							</tr>
							<tr id="exact_galleries">
								<td>and/or Have exactly:</td>
								<td>
									<select name="galleries_amnt" id="galleries_amnt">
										<?php $this->assign('end', $this->_tpl_vars['module']->request['galleries_amnt']); ?>
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


			<div class="row1">
			<ul class="categories">
				<li>
					<div class="cat">
						<div class="clear"></div>
						<div class="title"><span class="icon"></span>Categories</div>
						<div><input type="radio" name="categories" value="1" <?php if ($this->_tpl_vars['module']->request['categories'] == 1): ?>checked="checked"<?php endif; ?>/> Yes &nbsp; <input type="radio" name="categories" value="0" <?php if ($this->_tpl_vars['module']->request['categories'] == 0): ?>checked="checked"<?php elseif ($this->_tpl_vars['module']->request['categories'] != 1): ?>checked="checked"<?php endif; ?>/> No</div>
						<div class="clear"></div>
					</div>
					<div class="subcats cats">
						<input type="hidden" name="categoriesttl" value="<?php if ($this->_tpl_vars['module']->request['categoriesttl']): ?><?php echo $this->_tpl_vars['module']->request['categoriesttl']; ?>
<?php else: ?>1<?php endif; ?>"/>
						<?php $this->assign('no_cats', 1); ?>
						<?php unset($this->_sections['c']);
$this->_sections['c']['name'] = 'c';
$this->_sections['c']['loop'] = is_array($_loop=$this->_tpl_vars['site']->modules) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['c']['show'] = true;
$this->_sections['c']['max'] = $this->_sections['c']['loop'];
$this->_sections['c']['step'] = 1;
$this->_sections['c']['start'] = $this->_sections['c']['step'] > 0 ? 0 : $this->_sections['c']['loop']-1;
if ($this->_sections['c']['show']) {
    $this->_sections['c']['total'] = $this->_sections['c']['loop'];
    if ($this->_sections['c']['total'] == 0)
        $this->_sections['c']['show'] = false;
} else
    $this->_sections['c']['total'] = 0;
if ($this->_sections['c']['show']):

            for ($this->_sections['c']['index'] = $this->_sections['c']['start'], $this->_sections['c']['iteration'] = 1;
                 $this->_sections['c']['iteration'] <= $this->_sections['c']['total'];
                 $this->_sections['c']['index'] += $this->_sections['c']['step'], $this->_sections['c']['iteration']++):
$this->_sections['c']['rownum'] = $this->_sections['c']['iteration'];
$this->_sections['c']['index_prev'] = $this->_sections['c']['index'] - $this->_sections['c']['step'];
$this->_sections['c']['index_next'] = $this->_sections['c']['index'] + $this->_sections['c']['step'];
$this->_sections['c']['first']      = ($this->_sections['c']['iteration'] == 1);
$this->_sections['c']['last']       = ($this->_sections['c']['iteration'] == $this->_sections['c']['total']);
?>
							<?php if ($this->_tpl_vars['site']->modules[$this->_sections['c']['index']]['module'] == 'categories'): ?><?php $this->assign('no_cats', 0); ?><?php endif; ?>
						<?php endfor; endif; ?>
						<?php if ($this->_tpl_vars['no_cats']): ?>
						You haven't added the categories module to this site yet.
						<?php else: ?>
						<span>Select categories table from the list to be associated with this type of objects.</span>
						<table class="form categories">
							<?php if ($this->_tpl_vars['module']->id && $this->_tpl_vars['module']->request['categories'] == 1): ?>
								<?php unset($this->_sections['j']);
$this->_sections['j']['name'] = 'j';
$this->_sections['j']['start'] = (int)1;
$this->_sections['j']['loop'] = is_array($_loop=$this->_tpl_vars['module']->request['categoriesttl']+1) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
								<tr>
									<td>Category <?php echo $this->_sections['j']['index']; ?>
:</td>
									<td width="100">
										<?php $this->assign('index', ((is_array($_tmp='category')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_sections['j']['index']) : smarty_modifier_cat($_tmp, $this->_sections['j']['index']))); ?>
										<select name="category<?php echo $this->_sections['j']['index']; ?>
">
											<option></option>
											<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=count($this->_tpl_vars['site']->modules)) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['show'] = true;
$this->_sections['i']['max'] = $this->_sections['i']['loop'];
$this->_sections['i']['step'] = 1;
$this->_sections['i']['start'] = $this->_sections['i']['step'] > 0 ? 0 : $this->_sections['i']['loop']-1;
if ($this->_sections['i']['show']) {
    $this->_sections['i']['total'] = $this->_sections['i']['loop'];
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
												<?php if ($this->_tpl_vars['site']->modules[$this->_sections['i']['index']]['module'] == 'categories'): ?>
													<?php $this->assign('table', ((is_array($_tmp=$this->_tpl_vars['site']->modules[$this->_sections['i']['index']]['m']->db_table)) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['site']->db_prefix) : smarty_modifier_cat($_tmp, $this->_tpl_vars['site']->db_prefix))); ?>
													<option value="<?php echo $this->_tpl_vars['table']; ?>
" <?php if ($this->_tpl_vars['table'] == $this->_tpl_vars['module']->request[$this->_tpl_vars['index']]): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['site']->modules[$this->_sections['i']['index']]['title']; ?>
</option>
												<?php endif; ?>
											<?php endfor; endif; ?>
										</select>
									</td>
									<?php $this->assign('index', ((is_array($_tmp='category')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_sections['j']['index']) : smarty_modifier_cat($_tmp, $this->_sections['j']['index']))); ?>
									<?php $this->assign('index', ((is_array($_tmp=$this->_tpl_vars['index'])) ? $this->_run_mod_handler('cat', true, $_tmp, '_admindisplay') : smarty_modifier_cat($_tmp, '_admindisplay'))); ?>
									<td><input type="checkbox" value="1" name="category<?php echo $this->_sections['j']['index']; ?>
_admindisplay" <?php if ($this->_tpl_vars['module']->request[$this->_tpl_vars['index']] == 1): ?>checked="checked"<?php endif; ?> class="admindisplay"/> Admin Display 
									<?php $this->assign('index', ((is_array($_tmp='category')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_sections['j']['index']) : smarty_modifier_cat($_tmp, $this->_sections['j']['index']))); ?>
									<?php $this->assign('index', ((is_array($_tmp=$this->_tpl_vars['index'])) ? $this->_run_mod_handler('cat', true, $_tmp, '_required') : smarty_modifier_cat($_tmp, '_required'))); ?>
									<input type="checkbox" value="1" name="category<?php echo $this->_sections['j']['index']; ?>
_required" <?php if ($this->_tpl_vars['module']->request[$this->_tpl_vars['index']] == 1): ?>checked="checked"<?php endif; ?> class="required"/> Required</td>
								</tr>
								<?php endfor; endif; ?>
							<?php else: ?>
							<tr>
								<td>Category 1:</td>
								<td width="100">
									<select name="category1">
										<option></option>
										<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=count($this->_tpl_vars['site']->modules)) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['show'] = true;
$this->_sections['i']['max'] = $this->_sections['i']['loop'];
$this->_sections['i']['step'] = 1;
$this->_sections['i']['start'] = $this->_sections['i']['step'] > 0 ? 0 : $this->_sections['i']['loop']-1;
if ($this->_sections['i']['show']) {
    $this->_sections['i']['total'] = $this->_sections['i']['loop'];
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
											<?php if ($this->_tpl_vars['site']->modules[$this->_sections['i']['index']]['module'] == 'categories'): ?>
												<option value="<?php echo $this->_tpl_vars['site']->db_prefix; ?>
<?php echo $this->_tpl_vars['site']->modules[$this->_sections['i']['index']]['m']->db_table; ?>
"><?php echo $this->_tpl_vars['site']->modules[$this->_sections['i']['index']]['title']; ?>
</option>
											<?php endif; ?>
										<?php endfor; endif; ?>
									</select>
								</td>
								<td><input type="checkbox" value="1" name="category1_admindisplay" class="admindisplay"/> Admin Display <input type="checkbox" value="1" name="category1_required" class="required"/> Required</td>
							</tr>
							<?php endif; ?>
						</table>
						<a href="javascript:" class="btnGrey addCategory">Add One More Category</a><br/><br/>
						<?php endif; ?>
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