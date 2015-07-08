<li cat_id="{$category.id}">
	<div class="cat">
		<div class="clear"></div>
		<div class="title"><span class="icon"></span><span class="titleTxt">{$category.title}</span></div>
		<div class="actions">
			<div class="action"><a href="javascript:" class="addCategory add" parent_id="{$category.id}"></a></div>
			<div class="action"><a href="javascript:" class="edit" cat_id="{$category.id}"></a></div>
			<div class="action"><a href="javascript:" class="delete" cat_id="{$category.id}"></a></div>
			<div class="action"><div class="draggable"></div></div>
		</div>
		<div class="clear"></div>
	</div>
	<div class="subcats">
		<ul class="categories" parent_id="{$category.id}">
		{if $category.children|@count>0}
				{foreach from=$category.children item=category} 
					{include file="category.tpl" category=$category}
				{/foreach}
		{else}
			<span class="nosubcats">No subcategories.</span>
		{/if}
		</ul>
	</div>
</li>
