{include file="header.tpl"}



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
				<input type="text" value="{$site->sitename}" name="name"/>
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
						{section name=t loop=$site->templates|@count}
							<option value="{$site->templates[t]}">{$site->templates[t]}</option>
						{/section}
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
				{section name=p loop=$site->pages|@count}
					<a href="javascript:" pageid="{$site->pages[p].id}">{$site->pages[p].name}.php</a>
				{/section}
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
				{if $site->pages|@count}
				<div class="welcome">
					<h1>Select page and add or edit modules.</h1>
				</div>
				{/if}
				{if $site->pages|@count==0}
				<div class="welcome">
					<h1>Well done! <a href="{$site->dir}" target="_blank" class="link">Website</a> created, time to add pages.</h1>
					It's easy, if you have finished your <a href="https://github.com/ClearWebSolutions/codes/wiki/HTML-templates-setup" class="link">templates setup</a> you can start adding pages.<br/>
				</div>
				{/if}
			</div>
		</div>
		<div class="clear"></div>

{include file="footer.tpl"}