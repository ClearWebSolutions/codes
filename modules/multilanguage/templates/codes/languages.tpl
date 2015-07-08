<div class="codes_bubble">
	<div class="codes_tri"></div>
	<div class="codes_cnt">
		<h2>Module Languages</h2>
		<p>Location: <i>templates/codes/languages.tpl</i></p>

		{section name=i loop=$settings->languages|@count}
			<a href="javascript:" class="language_switcher {if $settings->language==$settings->languages[i].id}selected{/if}" lid="{$settings->languages[i].id}">{$settings->languages[i].id}</a>
		{/section}

	</div>
</div>