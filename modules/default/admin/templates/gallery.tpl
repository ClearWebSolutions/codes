{include file="header.tpl"}

<h1>{$gallery->title|ucfirst}</h1>

<div class="prl20">
	<div class="success-box hidden">
		<div class="success">Success message!</div>
	</div>
	<br/>
	<div class="error-box hidden">
		<div class="error">Warning title is required!</div>
	</div>

	{include file="gallery_module.tpl"}

</div>

{include file="footer.tpl"}