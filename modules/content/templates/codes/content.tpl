<div class="codes_bubble">
	<div class="codes_tri"></div>
	<div class="codes_cnt">
		<h2>Module Content</h2>
		<p>Location: <i>templates/codes/content.tpl</i></p>


		<p><b>{$p->content.1.title}</b><br/>{$p->content1}</p><hr/>
		{if $p->content_areas>=2}<p><b>{$p->content.2.title}</b><br/>{$p->content2}</p><hr/>{/if}
		{if $p->content_areas>=3}<p><b>{$p->content.3.title}</b><br/>{$p->content3}</p><hr/>{/if}
		{if $p->content_areas>=4}<p><b>{$p->content.4.title}</b><br/>{$p->content4}</p><hr/>{/if}
		{if $p->content_areas>=5}<p><b>{$p->content.5.title}</b><br/>{$p->content5}</p><hr/>{/if}

		<p><b>Galleries:</b></p>
		{section name=j loop=$p->galleries|@count}
			{section name=i loop=$p->galleries[j]->imgs|@count}
				<a href="{$p->galleries[j]->imgs[i].url.full}" rel="gallery" class="fancybox" title="{$p->galleries[j]->imgs[i].title}"><img src="{$p->galleries[j]->imgs[i].url.admin}" border="0"/></a>
			{/section}
			<hr/>
		{/section}


	</div>
</div>