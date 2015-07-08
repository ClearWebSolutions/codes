{if $module->installed}
	<div class="success-box" id="moduleSuccess"><div class="success">There is already a Content module installed for this page. Select the Content module from the list on the left to edit it.</div></div>
{else}
	{if $module->id}
		<h1>Update module Content</h1>
	{/if}
	<div class="success-box hidden" id="moduleSuccess"><div class="success">Module Content successfully {if $module->id}updated{else}added{/if}!</div></div>

	<form action="module.php" id="{if $module->id}edit{else}add{/if}module" method="post">

		{if !$module->id}
			<p><i>This module will create an editable content areas and gallery if required. Module would be applied to the <span id="selectedpage"></span> page.</i></p>
		{/if}
	
		<div class="error-box hidden" id="moduleError"><div class="error"></div></div>

		<input type="hidden" name="action" value="{if $module->id}edit{else}add{/if}"/>
		<input type="hidden" name="moduleid" value="{$module->id}"/>
		<input type="hidden" name="page" id="page"/>
		<input type="hidden" name="module" value="content"/>
		<input type="hidden" name="warning" id="warning" value=""/>

		<div class="rows">
			<table class="form">
				<tr>
					<td>Page title:</td>
					<td><input type="text" name="title" value="{$module->title}"/></td>
					<td><i>would be used to name the menu in admin area</i></td>
				</tr>

				<tr>
					<td>Parent:</td>
					<td>
						<select class="selectBox" name="parent">
							<option value="0"></option>
							{section name=p loop=$site->pages|@count}
								<option value="{$site->pages[p].id}" {if $module->parent==$site->pages[p].id}selected="selected"{/if}>{$site->pages[p].name}.php</option>
							{/section}
						</select>
					</td>
					<td><i>if not selected, page would be added directly to Pages menu in admin area</i></td>
				</tr>
				<tr>
					<td>This page is template:</td>
					<td><input type="radio" name="is_template" value="1" {if $module->is_template==1}checked="checked"{/if}/>Yes <input type="radio" name="is_template" value="0" {if $module->is_template!=1}checked="checked"{/if}/>No</td>
					<td>defines the template for the pages created by user via Add New menu, Parent page is required</td>
				</tr>
				<tr>
					<td>Amount of editing zones:</td>
					<td>
						<select class="selectBox" name="content_areas" id="content_areas">
							<option value=""></option>
							{section name=i start=1 loop=6}
								<option value="{$smarty.section.i.index}" {if $module->content_areas==$smarty.section.i.index}selected="selected"{/if}>{$smarty.section.i.index}</option>
							{/section}
						</select>
					</td>
					<td>&nbsp;</td>
				</tr>
				{if $module->content_areas}
					{section name=i start=1 loop=$module->content_areas+1}
					<tr>
						<td>Content {$smarty.section.i.index}:</td>
						<td><input type="text" name="content{$smarty.section.i.index}" value="{$module->content[i].title}"/></td>
						<td></td>
					</tr>
					{/section}
				{/if}
			</table>




			<div class="row1">
			<ul class="categories">
				<li>
					<div class="cat">
						<div class="clear"></div>
						<div class="title"><span class="icon"></span>Gallery{$module->gallery}</div>
						<div><input type="radio" name="gallery" value="1" {if $module->gallery==1}checked="checked"{/if}/> Yes &nbsp; <input type="radio" name="gallery" value="0" {if $module->gallery==0}checked="checked"{elseif $module->gallery!=1}checked="checked"{/if}/> No</div>
						<div class="clear"></div>
					</div>
					<div class="subcats">
						<table class="form">
							<tr>
								<td>Allow multi-galleries:</td>
								<td width="215"><input type="radio" name="multi_galleries" value="1" {if $module->galleries_multi==1}checked="checked"{/if}/> Yes &nbsp; <input type="radio" name="multi_galleries" value="0" {if $module->galleries_multi!=1}checked="checked"{/if}/> No</td>
								<td><i>last gallery settings below would be used as multi</i></td>
							</tr>
							<tr id="exact_galleries">
								<td>and/or Have exactly:</td>
								<td>
									<select name="galleries_amnt" id="galleries_amnt">
										{assign var=end value=$module->galleries|@count}
										{section name=i start=1 loop=6}
											<option value="{$smarty.section.i.index}" {if $end==$smarty.section.i.index}selected="selected"{/if}>{$smarty.section.i.index}</option>
										{/section}
									</select> &nbsp; galleries</td>
								<td></td>
							</tr>
						</table>
						<div class="galleries">
							{if $end>0}
								{assign var=end value=$end+1}
								{section name=j start=1 loop=$end}
									{assign var=i value=$smarty.section.j.index}
									{assign var=gallery value=$module->galleries[j]}
									<div class="separator"></div>
									<div class="gal">{include file="modules/gallery.frm.tpl"}</div>
								{/section}
							{else}
								<div class="separator"></div>
								{assign var=i value=1}
								<div class="gal">{include file="modules/gallery.frm.tpl"}</div>
							{/if}
						</div>
					</div>
				</li>
			</ul>
			</div>


		</div>

		<div class="submitLine">
			<a href="javascript:" class="btnGreen">{if $module->id}Update{else}Add{/if} Module</a>
		</div>

	</form>
{/if}