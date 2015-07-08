			<table class="form" id="galleryfrm{$i}">
				<tr>
					<td>Gallery name:</td>
					<td width="200"><input type="text" name="name{$i}" value="{$gallery->title}"/></td>
					<td><i>would be used to name the item in admin area</i></td>
				</tr>
				<tr>
					<td>Gallery folder:</td>
					<td><input type="text" name="folder{$i}" value="{if $gallery->folder}{$gallery->folder}{else}assets/imgs/{/if}"/></td>
					<td><i>in the root of the website (e.g.: assets/imgs/gallery)</i></td>
				</tr>
{*				<tr>
					<td>Fancybox</td>
					<td><input type="radio" value="1" name="fancybox{$i}" {if $gallery->fancybox&&$gallery->id}checked="checked"{elseif !$gallery->id}checked="checked"{/if}/> Yes &nbsp; <input type="radio" value="0" name="fancybox{$i}" {if !$gallery->fancybox&&$gallery->id}checked="checked"{/if}/> No</td>
					<td><i>uses "full" suffix images(creates them automatically)</i></td>
				</tr> *}
				<tr>
					<td>Amount of images to create:</td>
				{assign var=imgs_amnt value=$gallery->sizes|@count}
				{assign var=imgs_amnt value=$imgs_amnt-1}
					<td>
						<select class="selectBox galleryamount" name="amount{$i}" id="i{$i}">
								<option value=""></option>
							{section name=i start=1 loop=21}
								<option value="{$smarty.section.i.index}" {if $imgs_amnt==$smarty.section.i.index}selected="selected"{/if}>{$smarty.section.i.index}</option>
							{/section}
						</select>
					</td>
					<td>&nbsp;</td>
				</tr>
			</table>
			{assign var=imgs_amnt value=$imgs_amnt+1}
			{if $gallery->title&&$imgs_amnt}
			<table class="galleryimgssettings" id="galleryimgssettings{$i}">
				<tr>
					<th>Suffix</th>
					<th>Width</th>
					<th>Height</th>
					<th>Cut</th>
				</tr>
				{section name=i start=1 loop=$imgs_amnt}
				<tr>
					<td><input type="text" name="suffix{$i}_{$smarty.section.i.index}" value="{$gallery->sizes[i].suffix}"/></td>
					<td><input type="text" name="width{$i}_{$smarty.section.i.index}" value="{$gallery->sizes[i].width}"/></td>
					<td><input type="text" name="height{$i}_{$smarty.section.i.index}" value="{$gallery->sizes[i].height}"/></td>
					<td><input type="radio" value="1" name="cut{$i}_{$smarty.section.i.index}" {if $gallery->sizes[i].cut==1}checked="checked"{/if}>Yes<input type="radio" value="0" {if $gallery->sizes[i].cut==0}checked="checked"{/if} name="cut{$i}_{$smarty.section.i.index}">No</td>
				</tr>
				{/section}
			</table>
			{/if}<br/>