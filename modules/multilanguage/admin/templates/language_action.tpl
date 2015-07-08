{include file="header.tpl"}

		<h1>{$request.action|ucfirst} Language</h1>

		<form action="languages.php" method="post" id="actionfrm">
		<input type="hidden" name="sbm" value="1"/>
		<input type="hidden" name="action" value="{$request.action}"/>

			<div class="outerRow {if !$error&&!$success}hidden{/if}">
				<div class="success-box {if !$success}hidden{/if}">
					<div class="success">{$success}</div>
				</div>
				<div class="error-box {if !$error}hidden{/if}">
					<div class="error">{$error}</div>
				</div>
			</div>

			<div class="frmRow">
				<label>ID</label>
				<div class="input">
					{if $request.action=='edit'}
						<input type="hidden" name="id" value="{$language->id}"/>
						<i class="desc">{$language->id}</i>
					{else}
						<input type="text" name="id" value="{$language->id}" class="w320"/>
						<i class="desc">must be a 2 letter index (e.g. en, es, fr)</i>
					{/if}
				</div>
			</div>
			<div class="frmRow even">
				<label>Language</label>
				<div class="input">
					<input type="text" name="language" value="{$language->language}" class="w320"/>
				</div>
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