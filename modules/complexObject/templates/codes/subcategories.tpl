<div style="padding-left:10px;">
{foreach from=$categories item=category}
	<a href="?{$category_name}={$category.id}">{$category.title}</a><br/>
	{if $category.children|@count>0}
		{include file="co_test_subcategories.tpl" categories=$category.children category_name=$category_name}
	{/if}
{/foreach}
</div>
