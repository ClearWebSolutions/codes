<div class="frmRow {if $oe=='odd'}{cycle values=',even'}{else}{cycle values='even,'}{/if}">
	<label>{$gallery->title}</label>
	<div class="input">
		{include file="gallery_module.tpl"}
	</div>
</div>