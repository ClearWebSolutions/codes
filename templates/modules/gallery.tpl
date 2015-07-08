	{if $module->id}
		<h1>Update Gallery module</h1>
	{/if}
	<div class="success-box hidden" id="moduleSuccess"><div class="success">Module Gallery successfully {if $module->id}updated{else}added{/if}!</div></div>

	<form action="module.php" id="{if $module->id}edit{else}add{/if}module" method="post">

		{if !$module->id}
			<p><i>This module will create a manageable gallery for the <span id="selectedpage"></span> page.</i></p>
			<p><i>Amount of images here is the amount of copies of each uploaded image you would like to have, with the suffix and sizes you define.</i></p>
		{/if}
	
		<div class="error-box hidden" id="moduleError"><div class="error">Warning title is required!</div></div>

		<input type="hidden" name="action" value="{if $module->id}edit{else}add{/if}"/>
		<input type="hidden" name="moduleid" value="{$module->id}"/>
		<input type="hidden" name="page" id="page"/>
		<input type="hidden" name="module" value="gallery"/>

		<div class="rows">
			{assign var=gallery value=$module}
			{include file="modules/gallery.frm.tpl"}
		</div>

		<div class="submitLine">
			<a href="javascript:" class="btnGreen">{if $module->id}Update{else}Add{/if} Module</a>
		</div>

	</form>