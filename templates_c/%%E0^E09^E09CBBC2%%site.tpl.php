<?php /* Smarty version 2.6.22, created on 2012-10-16 15:28:31
         compiled from site.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'site.tpl', 46, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>



		<!-- this is a standard inline popup box that would be used by scripts to display -->

		<div id="websiteSettings" class="box hidden addNewWebsite">
			<div class="tri"></div>
			<div class="error hidden"></div>
			<div class="success hidden"></div>
			<form action="site.php" method="post" id="settingsfrm">
			<input type="hidden" name="action" value="updateSettings"/>
			<div class="rows">
			<div class="row">
				<label>Site name:</label>
				<input type="text" value="<?php echo $this->_tpl_vars['site']->sitename; ?>
" name="name"/>
			</div>
			</div>
			<div class="warning">
				<h3>Warning!</h3>
				Changing site name will rename the site's folder<br/>and would update includes.php<br/>
				<div class="bshdw"></div>
			</div>
			<div class="submitLine">
				<a href="javascript:" class="btnGreen">Update</a>
				<a href="javascript:" class="btnCancel">Cancel</a>
			</div>
			</form>
		</div>


		<div id="addNewPage" class="box hidden addNewPage">
			<div class="tri"></div>
			<div class="error hidden"></div>
			<form action="site.php" method="post" id="addpagefrm">
			<input type="hidden" name="action" value="addPage"/>
			<div class="rows">
				<div class="row">
					<label>Page:</label>
					<input type="text" name="name" value=""/> <i>.php</i>
				</div>
				<div class="row">
					<label>Template:</label>
					<select id="template" name="template">
						<option></option>
						<?php unset($this->_sections['t']);
$this->_sections['t']['name'] = 't';
$this->_sections['t']['loop'] = is_array($_loop=count($this->_tpl_vars['site']->templates)) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['t']['show'] = true;
$this->_sections['t']['max'] = $this->_sections['t']['loop'];
$this->_sections['t']['step'] = 1;
$this->_sections['t']['start'] = $this->_sections['t']['step'] > 0 ? 0 : $this->_sections['t']['loop']-1;
if ($this->_sections['t']['show']) {
    $this->_sections['t']['total'] = $this->_sections['t']['loop'];
    if ($this->_sections['t']['total'] == 0)
        $this->_sections['t']['show'] = false;
} else
    $this->_sections['t']['total'] = 0;
if ($this->_sections['t']['show']):

            for ($this->_sections['t']['index'] = $this->_sections['t']['start'], $this->_sections['t']['iteration'] = 1;
                 $this->_sections['t']['iteration'] <= $this->_sections['t']['total'];
                 $this->_sections['t']['index'] += $this->_sections['t']['step'], $this->_sections['t']['iteration']++):
$this->_sections['t']['rownum'] = $this->_sections['t']['iteration'];
$this->_sections['t']['index_prev'] = $this->_sections['t']['index'] - $this->_sections['t']['step'];
$this->_sections['t']['index_next'] = $this->_sections['t']['index'] + $this->_sections['t']['step'];
$this->_sections['t']['first']      = ($this->_sections['t']['iteration'] == 1);
$this->_sections['t']['last']       = ($this->_sections['t']['iteration'] == $this->_sections['t']['total']);
?>
							<option value="<?php echo $this->_tpl_vars['site']->templates[$this->_sections['t']['index']]; ?>
"><?php echo $this->_tpl_vars['site']->templates[$this->_sections['t']['index']]; ?>
</option>
						<?php endfor; endif; ?>
					</select>
				</div>
				<div class="row"></div>
			</div>
			<div class="submitLine">
				<a href="javascript:" class="btnGreen">Add</a>
				<a href="javascript:" class="btnCancel">Cancel</a>
			</div>
			</form>
		</div>

		<div id="addModuleErrorPopup" class="box hidden trileft">
			<div class="tri"></div>
			<div class="moduleerror">First select the page to add the module to</div>
			<div class="submitLine">
				<a href="javascript:" class="btnCancel">Close</a>
			</div>
		</div>


		<div class="hat">
			<div class="hatshdw"></div>
		</div>
		<div class="lshdw"></div>
		<div class="rshdw"></div>

		<div class="psshdw"></div>
		<div class="msshdw"></div>

		<div class="ps">
			<h1>Pages</h1>
			<a href="javascript:" class="add" id="addPage"></a>
			<div class="psmenu scroll-pane">
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
					<a href="javascript:" pageid="<?php echo $this->_tpl_vars['site']->pages[$this->_sections['p']['index']]['id']; ?>
"><?php echo $this->_tpl_vars['site']->pages[$this->_sections['p']['index']]['name']; ?>
.php</a>
				<?php endfor; endif; ?>
			</div>
		</div>
		<div class="ms">
			<h1>Modules</h1>
			<a href="javascript:" class="add" id="addModule"></a>
			<div class="msmenu scroll-pane">

			</div>
		</div>
		
		<div class="selectmodule" id="selectmodule"></div>

		<div class="hidden" id="selectmoduleoptions">
			<div class="bbb"></div>
			<div class="tri"></div>
			<span class="title">Add Module </span>
			<select>
				<option></option>
				<option value="categories">Categories</option>
				<option value="complexObject">Complex Object</option>
				<option value="content">Content</option>
				<option value="gallery">Gallery</option>
				<option value="multilanguage">Multi-Language</option>
				<option value="store">Store</option>
			</select>
		</div>


		<div class="m">
			<div class="scroll-pane" id="scroll">
				<?php if (count($this->_tpl_vars['site']->pages)): ?>
				<div class="welcome">
					<h1>Select page and add or edit modules.</h1>
				</div>
				<?php endif; ?>
				<?php if (count($this->_tpl_vars['site']->pages) == 0): ?>
				<div class="welcome">
					<h1>Well done! Project created, time to add pages.</h1>
					It's easy, if you have finished your <a href="#" class="link">templates setup</a> you can start adding pages.<br/>
				</div>
				<?php endif; ?>
			</div>
		</div>
		<div class="clear"></div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>