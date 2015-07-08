{include file="header.tpl"}

		<!-- this is a standard inline popup box that would be used by scripts to display -->

		<div id="editPopup" class="box hidden">
			<div class="tri"></div>
			<h2>Edit category</h2>
			<form action="categories.php" method="post" id="editFrm">
			<div class="loading">Loading...</div>
			<div class="error hidden">Warning title is required!</div>
			<input type="hidden" name="tbl" value="{$category->tbl}"/>
			<input type="hidden" name="action" value="edit"/>
			<input type="hidden" name="cat_id" id="cat_id" value=""/>
			<div class="prl10">
				{section name=i loop=$settings->languages}
					<div class="row">
						<label>{if $settings->languages|@count>1}{$settings->languages[i].title|ucfirst} title{else}Title{/if}</label><input type="text" name="title-{$settings->languages[i].id}"/>
					</div>
				{/section}
			</div>
			<div class="submitLine">
				<a href="javascript:" class="btnGreen">Update</a>
				<a href="javascript:" class="btnCancel">Cancel</a>
			</div>
			</form>
		</div>

		<div id="deletePopup" class="box hidden">
			<div class="tri"></div>
			<h2>Delete category?</h2>
			<form action="categories.php" method="post" id="deleteFrm">
			<input type="hidden" name="tbl" value="{$category->tbl}"/>
			<input type="hidden" name="action" value="delete"/>
			<input type="hidden" name="cat_id" id="cat_id" value=""/>
			<div class="submitLine">
				<a href="javascript:" class="btnRed">Delete</a>
				<a href="javascript:" class="btnCancel">Cancel</a>
			</div>
			</form>
		</div>

		<div id="addPopup" class="box hidden">
			<div class="tri"></div>
			<h2>Add category</h2>
			<div class="prl10">
			<form action="categories.php" id="addFrm">
			<div class="error hidden">Warning title is required!</div>
			<input type="hidden" name="tbl" value="{$category->tbl}"/>
			<input type="hidden" value="0" name="parent_id"/>
			<input type="hidden" name="add" value="1"/>
				{section name=i loop=$settings->languages}
					<div class="row">
						<label>{if $settings->languages|@count>1}{$settings->languages[i].title|ucfirst} title{else}Title{/if}</label><input type="text" name="title-{$settings->languages[i].id}"/>
					</div>
				{/section}
			</form>
			</div>
			<div class="submitLine">
				<a href="javascript:" class="btnGreen">Add</a>
				<a href="javascript:" class="btnCancel">Cancel</a>
			</div>
		</div>



<h1>Categories</h1>

	<div class="cats">
		<div class="success-box hidden">
			<div class="success">Success message!</div>
		</div>
		<br/>
		<div class="error-box hidden">
			<div class="error">Warning title is required!</div>
		</div>
		
		<div class="actions">
			<a href="javascript:" class="addCategory add" popup="addPopup" parent_id="0"></a>
		</div>
		
<ul class="categories" parent_id="0">
{if $categories|@count}
		{foreach from=$categories item=category} 
			{include file="category.tpl" category=$category}
		{/foreach}
{else}
		There are no categories yet.
{/if}
</ul>
		<div class="clear"></div>
	</div>



{include file="footer.tpl"}