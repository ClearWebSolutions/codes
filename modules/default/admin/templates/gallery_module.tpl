<!-- POPUPS -->

<div id="addPopup{$gallery->g2o}_{$gallery->id}" class="box hidden addImagePopup">
	<div class="tri"></div>
	<h2>Add image(s)</h2>
	<div id="file-uploader"></div>
	<form>
	<input type="hidden" name="g2o" id="g2o" value="{$gallery->g2o}"/>
	</form>
	<div class="submitLine addImg">
		<a href="javascript:" class="btnCancel">Close</a>
	</div>
</div>

<div id="editPopup{$gallery->g2o}_{$gallery->id}" class="box hidden">
	<div class="tri"></div>
	<h2>Edit</h2>
	<form action="gallery.php" method="post" id="editimgfrm{$gallery->g2o}_{$gallery->id}">
		<input type="hidden" name="action" value="editImage"/>
		<input type="hidden" name="gid" value="{$gallery->id}"/>
		<input type="hidden" name="g2o" id="g2o" value="{$gallery->g2o}"/>
		<div class="prl10">
		</div>
	</form>
	<div class="submitLine">
		<a href="javascript:" class="btnGreen">Update</a>
		<a href="javascript:" class="btnCancel">Cancel</a>
	</div>
</div>

<div id="deletePopup{$gallery->g2o}_{$gallery->id}" class="box hidden">
	<div class="tri"></div>
	<h2>Delete selected images?</h2>
	<form action="gallery.php" method="post" id="delimgfrm{$gallery->g2o}_{$gallery->id}">
		<input type="hidden" name="list" id="list{$gallery->g2o}_{$gallery->id}" value=""/>
		<input type="hidden" name="gid" value="{$gallery->id}"/>
		<input type="hidden" name="g2o" value="{$gallery->g2o}"/>
		<input type="hidden" name="action" value="deleteImage"/>
	</form>
	<div class="submitLine">
		<a href="javascript:" class="btnRed">Delete</a>
		<a href="javascript:" class="btnCancel">Cancel</a>
	</div>
</div>


<!-- GALLERY itself -->

<div class="actions">
	<a href="javascript:" class="addImg add" popup="addPopup{$gallery->g2o}_{$gallery->id}" gid="{$gallery->id}" g2o="{$gallery->g2o}"></a><a href="javascript:" class="delImg delete" popup="deletePopup{$gallery->g2o}_{$gallery->id}" gid="{$gallery->id}" g2o="{$gallery->g2o}"></a><div class="loading" id="loading{$gallery->g2o}_{$gallery->id}">Loading...</div>
</div>

<ul class="gallery" id="gallery{$gallery->g2o}_{$gallery->id}">
{include file='gallery_thumbs.tpl'}
</ul>

<div class="clear"></div>


