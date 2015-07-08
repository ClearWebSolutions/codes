{include file="header.tpl"}
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
						<input type="hidden" value="{$user->protect}" id="realprotect"/>
						<i class="georgia"><input type="radio" name="protect" id="protect1" value="1" {if $user->protect=='1'}checked="checked"{/if}/> Yes &nbsp;&nbsp;&nbsp;<input type="radio" id="protect2" name="protect" {if $user->protect!='1'}checked="checked"{/if} value="0"/> No</i>
						<div id="protect" class="protect {if $user->protect!='1'}hidden{/if}">
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
						<input type="text" name="client_email" value="{$user->client_email}"/>
					</div>
					<div class="row even">
						<label>Default client admin username</label>
						<input type="text" name="client_username" value="{$user->client_username}"/>
					</div>
					<div class="row">
						<label>Default client admin password</label>
						<input type="text" name="client_password" value="{$user->client_password}"/>
					</div>
					<div class="row even">
						<label>Default DB prefix</label>
						<input type="text" name="db_prefix" id="db_prefix" value="{$user->db_prefix}"/>
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
			{if $sites|@count==0}<div class="nowebsites">No websites yet.</div>{/if}
			{section name=sites loop=$sites|@count}
			{if $smarty.section.sites.index%5==0}{if $smarty.section.sites.index!=0}<div class="clear"></div></div>{/if}<div class="row {if $smarty.section.sites.index%10!=0}even{/if}">{/if}
				<div class="page"><a href="site.php?id={$sites[sites].id}">{$sites[sites].name}</a><a href="javascript:" class="deleteSmall"></a></div>
			{/section}
			<div class="clear"></div></div>
		</div>
{include file="footer.tpl"}