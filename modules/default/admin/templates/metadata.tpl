{include file="header.tpl"}

		<h1>Metadata</h1>

		<div class="outerRow {if !$success&&!$error}hidden{/if}">
			<div class="success-box {if !$success}hidden{/if}">
				<div class="success">Metadata updated!</div>
			</div>
			<div class="error-box {if !$error}hidden{/if}">
				<div class="error">{$error}</div>
			</div>
		</div>

		<form action="metadata.php" method="post" id="metadatafrm">
		<input type="hidden" name="sbm" value="1"/>
		<div class="cnt ">
				<div class="frmRow">
					<label>Meta title</label>
					<div class="input">
						<input type="text" name="meta_title" value="{$settings->meta_title}"/>
					</div>
				</div>
				<div class="frmRow even">
					<label>Meta keywords</label>
					<div class="input">
						<input type="text" name="meta_keywords" value="{$settings->meta_keywords}"/>
					</div>
				</div>
				<div class="frmRow">
					<label>Meta description</label>
					<div class="input">
						<input type="text" name="meta_description" value="{$settings->meta_description}"/>
					</div>
				</div>
		</div>
		</form>
		
		<div class="frmRow">
			<label class="error">&nbsp;</label>
			<div class="input">
				<div class="submitLine">
					<a href="javascript:submitFrm('metadatafrm')" class="btnGreen">Update</a>
				</div>
			</div>
		</div>

{include file="footer.tpl"}