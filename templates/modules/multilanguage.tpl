
{if $module->installed}
	<div class="success-box" id="moduleSuccess"><div class="success">Module multi-language is already installed and attached to <b>{$module->page->name}.php</b>. See <b>{$module->page->name}.php</b> to modify this module.</div></div>
{else}
	{if $module->id}
		<h1>Update Multi-language module</h1>
		<p><i>All the controls for this module including languages and vocabulary management are available in the admin area of the website under Settings menu.</i></p>
	{else}

	<div class="success-box hidden" id="moduleSuccess"><div class="success">Module multi-language successfully {if $module->id}updated{else}added{/if}!</div></div>

	<form action="module.php" id="{if $module->id}edit{else}add{/if}module" method="post">

		{if !$module->id}
			<p><i>This module will add Vocabulary and Languages management to the admin area.</i></p>
		{/if}
	
		<div class="error-box hidden" id="moduleError"><div class="error">Warning title is required!</div></div>

		<input type="hidden" name="action" value="{if $module->id}edit{else}add{/if}"/>
		<input type="hidden" name="moduleid" value="{$module->id}"/>
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
			<a href="javascript:" class="btnGreen">{if $module->id}Update{else}Add{/if} Module</a>
		</div>

	</form>
	{/if}
{/if}