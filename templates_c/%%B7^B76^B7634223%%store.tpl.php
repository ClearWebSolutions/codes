<?php /* Smarty version 2.6.22, created on 2012-07-14 20:48:54
         compiled from modules/store.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'modules/store.tpl', 34, false),array('modifier', 'cat', 'modules/store.tpl', 69, false),)), $this); ?>
<?php if ($this->_tpl_vars['module']->installed): ?>
	<div class="success-box" id="moduleSuccess"><div class="success">Module Store is already installed and attached to <b><?php echo $this->_tpl_vars['module']->page->name; ?>
.php</b>. See <b><?php echo $this->_tpl_vars['module']->page->name; ?>
.php</b> to modify this module.</div></div>
<?php else: ?>
	<?php if ($this->_tpl_vars['module']->id): ?>
		<h1>Update module Store</h1>
	<?php endif; ?>
	<div class="success-box hidden" id="moduleSuccess"><div class="success">Module Store successfully <?php if ($this->_tpl_vars['module']->id): ?>updated<?php else: ?>added<?php endif; ?>!</div></div>

	<form action="module.php" id="<?php if ($this->_tpl_vars['module']->id): ?>edit<?php else: ?>add<?php endif; ?>module" method="post">

		<?php if (! $this->_tpl_vars['module']->id): ?>
			<p><i>This module would create a shopping cart that would be present on each page.<br/>
			An ability to edit the stock of the products, orders management and PayPal integration(both express and regular checkout).<br/>
			To avoid code duplication it's good to apply this module to the same page where your products(Complex Object) module is installed.<br/>
			Module would be applied to the <span id="selectedpage"></span> page.</i></p>
			<p><i>PayPal access details are in the class/base/ShoppingCartBase.php, by default they are empty and PayPal class applies the test API and details.</i></p>
		<?php endif; ?>

		<div class="error-box hidden" id="moduleError"><div class="error"></div></div>

		<input type="hidden" name="action" value="<?php if ($this->_tpl_vars['module']->id): ?>edit<?php else: ?>add<?php endif; ?>"/>
		<input type="hidden" name="moduleid" value="<?php echo $this->_tpl_vars['module']->id; ?>
"/>
		<input type="hidden" name="page" id="page"/>
		<input type="hidden" name="module" value="store"/>
		<input type="hidden" name="warning" id="warning" value=""/>

		<div class="rows">
			<table class="form">
				<tr>
					<td>Products object:</td>
					<td width="200">
						<select name="products">
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
								<?php if ($this->_tpl_vars['site']->modules[$this->_sections['i']['index']]['module'] == 'complexObject'): ?>
									<option value="<?php echo $this->_tpl_vars['site']->modules[$this->_sections['i']['index']]['m']->tbl; ?>
" <?php if ($this->_tpl_vars['module']->id && $this->_tpl_vars['module']->products_module): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['site']->modules[$this->_sections['i']['index']]['title']; ?>
</option>
								<?php endif; ?>
							<?php endfor; endif; ?>
						</select>
					</td>
					<td><i>products could be any Complex Object module you have already installed</i></td>
				</tr>
				<tr>
					<td>Checkout page template:</td>
					<td width="200">
						<select name="checkout">
							<option></option>
							<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=count($this->_tpl_vars['site']->templates)) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
								<option value="<?php echo $this->_tpl_vars['site']->templates[$this->_sections['i']['index']]; ?>
" <?php if ($this->_tpl_vars['module']->id && $this->_tpl_vars['module']->checkout): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['site']->templates[$this->_sections['i']['index']]; ?>
</option>
							<?php endfor; endif; ?>
						</select>
					</td>
					<td></td>
				</tr>

			</table>



			<h3 class="pl40">Product options (like size, color etc.), leave blank if no options. Quantity is not an option, it's already included in the module.</h3>
			<div class="properties optionsTable">
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
							<label>Option title</label>
							<?php $this->assign('index', ((is_array($_tmp='title')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_sections['i']['index']) : smarty_modifier_cat($_tmp, $this->_sections['i']['index']))); ?>
							<input type="text" name="title<?php echo $this->_sections['i']['index']; ?>
" class="fieldtitle" value="<?php echo $this->_tpl_vars['module']->request[$this->_tpl_vars['index']]; ?>
"/><br/>
						</td>
						<td>
							<label>Input type</label>
							<?php $this->assign('tindex', ((is_array($_tmp='type')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_sections['i']['index']) : smarty_modifier_cat($_tmp, $this->_sections['i']['index']))); ?>
							<select name="type<?php echo $this->_sections['i']['index']; ?>
" class="inputType">
								<option value="text" <?php if ($this->_tpl_vars['module']->request[$this->_tpl_vars['tindex']] == 'text'): ?>selected="selected"<?php endif; ?>>text</option>
								<option value="select" <?php if ($this->_tpl_vars['module']->request[$this->_tpl_vars['tindex']] == 'select'): ?>selected="selected"<?php endif; ?>>select</option>
							</select>
						</td>
						<td>
							<?php if ($this->_tpl_vars['module']->request[$this->_tpl_vars['tindex']] == 'select'): ?>
								<?php $this->assign('oindex', ((is_array($_tmp='optionsttl')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_sections['i']['index']) : smarty_modifier_cat($_tmp, $this->_sections['i']['index']))); ?>
								<span class="optionsTitle options"><input type="hidden" name="optionsttl<?php echo $this->_sections['i']['index']; ?>
" value="<?php echo $this->_tpl_vars['module']->request[$this->_tpl_vars['oindex']]; ?>
" class="optionsttl"/>Select Options:
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
							<?php if ($this->_tpl_vars['module']->request[$this->_tpl_vars['tindex']] == 'select'): ?>
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
						<td class="fs10" width="100px">
							<a class="deleteSmall"></a>
						</td>
					</tr>
					<?php endfor; endif; ?>
					<?php else: ?>
					<tr>
						<td width="20%">
							<label>Option title</label>
							<input type="text" name="title1" class="fieldtitle"/><br/>
						</td>
						<td width="100">
							<label>Input type</label>
							<select name="type1" class="inputType">
								<option value="text">text</option>
								<option value="select">select</option>
							</select>
						</td>
						<td></td>
						<td></td>
						<td class="fs10" width="100px">
							<a class="deleteSmall"></a>
						</td>
					</tr>
					<?php endif; ?>
				</table>
				<br/>
				<a href="javascript:" class="btnGrey addField">Add One More Option</a>
				<br/><br/>
		</div>




		</div>

		<div class="submitLine">
			<a href="javascript:" class="btnGreen"><?php if ($this->_tpl_vars['module']->id): ?>Update<?php else: ?>Add<?php endif; ?> Module</a>
		</div>

	</form>
<?php endif; ?>