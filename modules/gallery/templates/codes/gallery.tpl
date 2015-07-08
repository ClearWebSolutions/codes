<div class="codes_bubble">
	<div class="codes_tri"></div>
	<div class="codes_cnt">
		<h2>Module Gallery</h2>
		<p>Location: <i>templates/codes/gallery.tpl</i></p>

		{section name=i loop=$gallery->imgs|@count}
			<a href="{$gallery->imgs[i].url.full}" rel="gallery" class="fancybox" title="{$gallery->imgs[i].title}"><img src="{$gallery->imgs[i].url.admin}" border="0"/></a>
		{/section}

	</div>
</div>