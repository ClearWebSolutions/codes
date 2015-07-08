{include file="header.tpl"}

		<h1>{$request.action|ucfirst} Label</h1>

		<form action="vocabulary.php" method="post" id="actionfrm">
		<input type="hidden" name="sbm" value="1"/>
		{if $request.id}<input type="hidden" name="id" value="{$request.id}"/>{/if}
		<input type="hidden" name="action" value="{$request.action}"/>

		<div id="tabs" class="cnt">
			{if $settings->languages|@count>1}
			<ul id="tabs_menu">
				{section name=i loop=$settings->languages|@count}
				<li><a href="#tabs-{$settings->languages[i].id}">{$settings->languages[i].title}</a></li>
				{/section}
			</ul>
			{/if}

			<div class="outerRow {if !$error&&!$success}hidden{/if}">
				<div class="success-box {if !$success}hidden{/if}">
					<div class="success">{$success}</div>
				</div>
				<div class="error-box {if !$error}hidden{/if}">
					<div class="error">{$error}</div>
				</div>
			</div>


			<div class="frmRow">
				<label>Label</label>
				<div class="input">
					<input type="text" name="label" value="{$vocabulary->label}"/>
				</div>
			</div>

			{section name=i loop=$settings->languages|@count}
			{assign var=lang value=$settings->languages[i].id}
			<div id="tabs-{$lang}">
				<div class="frmRow even">
					<label>Phrase</label>
					<div class="input">
						<input type="text" name="phrase-{$lang}" value="{$vocabulary->phrase[$lang]}"/>
					</div>
				</div>
			</div>
			{/section}

		</div>
		</form>
		
		<div class="frmRow">
			<label class="error">&nbsp;</label>
			<div class="input">
				<div class="submitLine">
					<a href="javascript:submitFrm('actionfrm')" class="btnGreen">{if $request.action=='edit'}Update{else}Add{/if}</a>
				</div>
			</div>
		</div>

{include file="footer.tpl"}