<div class="codes_bubble">
	<div class="codes_tri"></div>
	<div class="codes_cnt">
		<h2>Module {$category_title} Categories</h2>
		<p>Location: <i>templates/codes/categories.tpl</i></p>

		{section name=i loop=$categories|@count}
			<a href="?{$category_name}={$categories[i].id}">{$categories[i].title}</a><br/>
			{if $categories[i].children|@count>0}
				{include file="codes/subcategories.tpl" categories=$categories[i].children category_name=$category_name}
			{/if}
		{/section}

	</div>
</div>