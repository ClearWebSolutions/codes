	{if $module->id}
		<h1>Update categories module</h1>
	{/if}

	<div class="success-box hidden" id="moduleSuccess"><div class="success">Module categories successfully {if $module->id}updated{else}added{/if}!</div></div>

	<form action="module.php" id="{if $module->id}edit{else}add{/if}module" method="post">

		{if !$module->id}
			<p><i>This module will create a manageable tree of categories, which could be assigned to Complex Objects.</i></p>
		{/if}
	
		<div class="error-box hidden" id="moduleError"><div class="error">Warning title is required!</div></div>

		<input type="hidden" name="action" value="{if $module->id}edit{else}add{/if}"/>
		<input type="hidden" name="moduleid" value="{$module->id}"/>
		<input type="hidden" name="page" id="page"/>
		<input type="hidden" name="module" value="categories"/>

		<div class="rows">
			<table class="form">
				<tr>
					<td>Title:</td>
					<td><input type="text" name="title" value="{$module->title}"/></td>
					<td></td>
				</tr>
				<tr>
					<td>DB table name:</td>
					<td><input type="text" name="db_tbl" value="{$module->db_table}"/></td>
					<td></td>
				</tr>
			</table>
		</div>

		<div class="submitLine">
			<a href="javascript:" class="btnGreen">{if $module->id}Update{else}Add{/if} Module</a>
		</div>

	</form>