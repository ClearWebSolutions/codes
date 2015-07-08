{include file="header.tpl"}

		<h1>{$request.action|ucfirst} Page {if $request.action=='edit'}> {$page->title}{/if}</h1>

		<form action="pages.php" method="post" id="actionfrm">
		<input type="hidden" name="sbm" value="1"/>
		<input type="hidden" name="id" value="{$page->id}"/>
		<input type="hidden" name="action" value="{$request.action}"/>

		<div class="cnt" id="tabs">
			{if $settings->languages|@count>1}
			<ul id="tabs_menu">
				{section name=i loop=$settings->languages|@count}
				<li><a href="#tabs-{$settings->languages[i].id}">{$settings->languages[i].title}</a></li>
				{/section}
			</ul>
			{/if}

			<div class="outerRow {if !$error&&!$success}hidden{/if}">
				<div class="success-box {if !$success}hidden{/if}">
					<div class="success">{$success}</div>
				</div>
				<div class="error-box {if !$error}hidden{/if}">
					<div class="error">{$error}</div>
				</div>
			</div>


			{if $page->parent_id!=0}
			<div class="frmRow">
				<label>Title</label>
				<div class="input">
					<input type="text" name="title" value="{$page->title}"/>
				</div>
			</div>
			{/if}

			{section name=i loop=$settings->languages|@count}
			{assign var=lang value=$settings->languages[i].id}
			<div id="tabs-{$lang}">
				{section name=c start=1 loop=$page->content_areas+1}
				<div class="frmRow {if ($smarty.section.c.index%2==0&&$page->parent_id==0)||($smarty.section.c.index%2!=0&&$page->parent_id!=0)}even{/if}">
					<label>{$page->content[c].title}</label>
					<div class="input">
						{$page->content[$lang][c]}
					</div>
				</div>
				{/section}
			</div>
			{/section}
			
			{if $page->parent_id!=0}
				{if ($page->content_areas+1)%2==0}{assign var=oe value='odd'}{else}{assign var=oe value='even'}{/if}
			{else}
				{if ($page->content_areas+1)%2==0}{assign var=oe value='even'}{else}{assign var=oe value='odd'}{/if}
			{/if}
			
			{section name=j loop=$page->galleries}
			{assign var=gallery value=$page->galleries[j]}
				{include file="gallery_inc.tpl"}
				{if $gallery->multi}
					{assign var=addonemore value="true"}
					{assign var=gid value=$gallery->id}
					{assign var=g2o value=$gallery->g2o}
				{/if}
			{/section}
			{if $addonemore=="true"}
				<div class="frmRow"><a href="javascript:" class="btnGrey addGallery" gid="{$gid}" g2o="{$g2o}">Add One More Gallery</a></div>
			{/if}

			{section name=i loop=$settings->languages|@count}
			{assign var=lang value=$settings->languages[i].id}
			<div id="tabs-{$lang}">
				<div class="frmRow">
				<ul class="categories">
					<li>
						<div class="cat">
							<div class="title"><span class="icon"></span>Page Metadata</div>
							<div class="clear"></div>
						</div>
						<div class="subcats">
								<div class="metaRow">
									<div class="w96">
										<div class="left">
											<label>Meta Title</label>
											<input type="text" name="meta_title{$lang}" value="{$page->meta[$lang].title}"/>
										</div>
										<div class="left">
											<label>Meta Description</label>
											<input type="text" name="meta_description{$lang}" value="{$page->meta[$lang].description}"/>
										</div>
										<div class="left">
											<label>Meta Keywords</label>
											<input type="text" name="meta_keywords{$lang}" value="{$page->meta[$lang].keywords}"/>
										</div>
									</div>
										<div class="clear"></div>
								</div>
						</div>
					</li>
				</ul>
				</div>
			</div>
			{/section}


		</div>
		</form>
		
		<div class="frmRow">
			<label class="error">&nbsp;</label>
			<div class="input">
				<div class="submitLine">
					<a href="javascript:submitFrm('actionfrm')" class="btnGreen">{if $request.action=='edit'}Update{else}Add{/if}</a>
				</div>
			</div>
		</div>

{include file="footer.tpl"}