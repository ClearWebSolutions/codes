{section name=imgs loop=$gallery->imgs|@count}
<li>
	<div class="img">
		<img src="{$gallery->imgs[imgs].url.admin}"/>
		<div class="draggable">
			<input type="checkbox" id="delimg{$gallery->imgs[imgs].id}"/>
			{assign var=imgs value=$smarty.section.imgs.index}
			<div id="imgdata{$gallery->imgs[$imgs].id}" class="hidden">
				{include file='gallery_imagedata.tpl'}
			</div>
			<a href="javascript:" class="editImg edit" popup="editPopup{$gallery->g2o}_{$gallery->id}" gid="{$gallery->id}" g2o="{$gallery->g2o}" id="editimg{$gallery->imgs[imgs].id}"></a>
		</div>
	</div>
</li>
{/section}