<?php /* Smarty version 2.6.22, created on 2012-03-17 12:10:37
         compiled from websites.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'websites.tpl', 118, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<!-- this is a standard inline popup box that would be used by scripts to display -->

		<div id="deleteWebsitePopup" class="box hidden deleteWebsitePopup">
			<div class="tri"></div>
			<form action="index.php" method="post" id="deletesitefrm">
			<input type="hidden" name="id" value=""/>
			<input type="hidden" name="action" value="deletesite"/>
			<h2>Delete site?</h2>
			<p>This will delete site's folder and site database!</p>
			<div class="submitLine">
				<a href="javascript:" class="btnRed">Delete</a>
				<a href="javascript:" class="btnCancel">Cancel</a>
			</div>
			</form>
		</div>

		<div id="addNewWebsite" class="box hidden addNewWebsite">
			<div class="tri"></div>
			<form action="index.php" method="post" id="addnewsitefrm">
			<input type="hidden" name="action" value="addnewsite"/>
			<div class="error hidden" id="error"></div>
			<div class="loading hidden">Creating website backbone...</div>
			<div class="rows">
			<div class="row">
				<label>Site name:</label>
				<input type="text" id="sitename" name="sitename" value=""/>
			</div>
			<div class="row">
				<label>DB name:</label>
				<input type="text" id="dbname" name="dbname" value=""/>
			</div>
			<div class="row">
				<label>DB prefix:</label>
				<input type="text" id="dbprefix" name="dbprefix"/>
			</div>
			</div>
			<div class="warning">
				<h3>Warning!</h3>
				<b><i>../<span class="sitename">sitename</span></i></b> folder will be created<br/>
				<b><i class="dbname">DB name</i></b> database would be created
				<div class="bshdw"></div>
			</div>
			<div class="submitLine">
				<input type="hidden" name="error" id="hidden_error"/>
				<a href="javascript:" class="btnGreen">Add</a>
				<a href="javascript:" class="btnCancel">Cancel</a>
			</div>
			</form>
		</div>



		<a href="javascript:" class="settingsIcon" id="settingsIcon"></a>
		
		<div class="settings hidden" id="settings">
			<div class="settingsCnt">
			<div class="lshdw"></div>
				<a href="javascript:" class="settingsIcon" id="settingsIconClose"></a>
				<h1>Codes settings and defaults</h1>
				<br/><br/>
				<form action="index.php" method="post" autocomplete="false" id="settingsfrm">
				<input type="hidden" name="action" value="updateSettings"/>
				<div class="rows">
<!--					<div class="row">
						<label>Protect Master Area</label>
						<input type="hidden" value="<?php echo $this->_tpl_vars['user']->protect; ?>
" id="realprotect"/>
						<i class="georgia"><input type="radio" name="protect" id="protect1" value="1" <?php if ($this->_tpl_vars['user']->protect == '1'): ?>checked="checked"<?php endif; ?>/> Yes &nbsp;&nbsp;&nbsp;<input type="radio" id="protect2" name="protect" <?php if ($this->_tpl_vars['user']->protect != '1'): ?>checked="checked"<?php endif; ?> value="0"/> No</i>
						<div id="protect" class="protect <?php if ($this->_tpl_vars['user']->protect != '1'): ?>hidden<?php endif; ?>">
							<div class="row">
								<label>Username</label>
								<input type="text" id="username" name="username" value=""/>
							</div>
							<div class="row">
								<label>Password</label>
								<input type="password" id="password" name="password" value=""/>
							</div>
							<div class="row">
								<label>Repeat Password</label>
								<input type="password" id="password2" name="password2" value=""/>
							</div>
						</div>
					</div>-->
					<div class="row">
						<label>Default client email</label>
						<input type="text" name="client_email" value="<?php echo $this->_tpl_vars['user']->client_email; ?>
"/>
					</div>
					<div class="row even">
						<label>Default client admin username</label>
						<input type="text" name="client_username" value="<?php echo $this->_tpl_vars['user']->client_username; ?>
"/>
					</div>
					<div class="row">
						<label>Default client admin password</label>
						<input type="text" name="client_password" value="<?php echo $this->_tpl_vars['user']->client_password; ?>
"/>
					</div>
					<div class="row even">
						<label>Default DB prefix</label>
						<input type="text" name="db_prefix" id="db_prefix" value="<?php echo $this->_tpl_vars['user']->db_prefix; ?>
"/>
					</div>
					<div class="submitLine">
						<div class="updated hidden"><img src="assets/imgs/check.png" height="20"/> Updated</div>
						<div class="error hidden"></div>
						<a href="javascript:" class="btnGreen">Update</a>
						<a href="javascript:" class="btnCancel">Close</a>
					</div>
				</div>
				</form>
			</div>
		</div>

		<div class="hat">
			<div class="hatshdw"></div>
		</div>
		<div class="lshdw"></div>
		<div class="rshdw"></div>
		<h1>Websites</h1>
		<div class="rows">
			<?php if (count($this->_tpl_vars['sites']) == 0): ?><div class="nowebsites">No websites yet.</div><?php endif; ?>
			<?php unset($this->_sections['sites']);
$this->_sections['sites']['name'] = 'sites';
$this->_sections['sites']['loop'] = is_array($_loop=count($this->_tpl_vars['sites'])) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['sites']['show'] = true;
$this->_sections['sites']['max'] = $this->_sections['sites']['loop'];
$this->_sections['sites']['step'] = 1;
$this->_sections['sites']['start'] = $this->_sections['sites']['step'] > 0 ? 0 : $this->_sections['sites']['loop']-1;
if ($this->_sections['sites']['show']) {
    $this->_sections['sites']['total'] = $this->_sections['sites']['loop'];
    if ($this->_sections['sites']['total'] == 0)
        $this->_sections['sites']['show'] = false;
} else
    $this->_sections['sites']['total'] = 0;
if ($this->_sections['sites']['show']):

            for ($this->_sections['sites']['index'] = $this->_sections['sites']['start'], $this->_sections['sites']['iteration'] = 1;
                 $this->_sections['sites']['iteration'] <= $this->_sections['sites']['total'];
                 $this->_sections['sites']['index'] += $this->_sections['sites']['step'], $this->_sections['sites']['iteration']++):
$this->_sections['sites']['rownum'] = $this->_sections['sites']['iteration'];
$this->_sections['sites']['index_prev'] = $this->_sections['sites']['index'] - $this->_sections['sites']['step'];
$this->_sections['sites']['index_next'] = $this->_sections['sites']['index'] + $this->_sections['sites']['step'];
$this->_sections['sites']['first']      = ($this->_sections['sites']['iteration'] == 1);
$this->_sections['sites']['last']       = ($this->_sections['sites']['iteration'] == $this->_sections['sites']['total']);
?>
			<?php if ($this->_sections['sites']['index']%5 == 0): ?><?php if ($this->_sections['sites']['index'] != 0): ?><div class="clear"></div></div><?php endif; ?><div class="row <?php if ($this->_sections['sites']['index']%10 != 0): ?>even<?php endif; ?>"><?php endif; ?>
				<div class="page"><a href="site.php?id=<?php echo $this->_tpl_vars['sites'][$this->_sections['sites']['index']]['id']; ?>
"><?php echo $this->_tpl_vars['sites'][$this->_sections['sites']['index']]['name']; ?>
</a><a href="javascript:" class="deleteSmall"></a></div>
			<?php endfor; endif; ?>
			<div class="clear"></div></div>
		</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>