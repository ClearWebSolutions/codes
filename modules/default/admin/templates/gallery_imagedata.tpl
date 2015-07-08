	<input type="hidden" name="imgid" value="{$gallery->imgs[$imgs].id}"/>
	<div class="row">
		<label>Link</label><input type="text" name="gilink" value="{$gallery->imgs[$imgs].link}"/>
	</div>
	{section name=lang loop=$settings->languages|@count}
		{assign var=l value=$settings->languages[lang].id}
		<div class="row">
			<label>{if $settings->languages|@count>1}{$settings->languages[lang].title|ucfirst} title{else}Title{/if}</label><input type="text" name="gititle-{$l}" value="{$gallery->imgs4admin[$l][$imgs].title}"/>
		</div>
	{/section}