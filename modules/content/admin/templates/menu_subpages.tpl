<ul>
	{if $settings->add_pages}<li><a href="pages.php?action=add&parent_id={$parent}">Add new</a></li>{/if}
	{foreach from=$element item=element} 
		<li><a href="pages.php?action=edit&id={$element.id}">{$element.title}</a>
		{if $element.children}
			{include file="menu_subpages.tpl" element=$element.children parent=$element.id}
		{/if}
	{/foreach}
</ul>
