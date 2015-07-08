{include file="header.tpl"}

		<!-- this is a standard inline popup box that would be used by scripts to display -->

		<div id="deletePopup" class="box hidden">
			<div class="tri"></div>
			<h2>Delete language?</h2>
			<form action="languages.php" method="post" id="deletefrm">
				<input type="hidden" name="id" id="id" value=""/>
				<input type="hidden" name="action" value="delete"/>
			<div class="submitLine">
					<a href="javascript:" class="btnRed" id="btnRed">Delete</a>
					<a href="javascript:" class="btnCancel">Cancel</a>
			</div>
			</form>
		</div>


		<div class="hat">
			<div class="hatshdw"></div>
		</div>
		<div class="lshdw"></div>
		<div class="rshdw"></div>
		<h1>Languages</h1>

		<div class="outerRow {if !$request.added}hidden{/if} pb0">
			<div class="success-box {if !$request.added}hidden{/if}">
				<div class="success">Item added successfully.</div>
			</div>
		</div>


		{if $languages|@count||$no_results}
			<div class="searchRow">
				<form action="languages.php" method="post" id="search">
					<input type="text" name="search" value="{$language->search}" class="search ml20" placeholder="Keyword"/>
					<a href="javascript:submitFrm('search')" class="btnGrey">Search</a>
				</form>
			</div>
		
			<table class="datatable" cellspacing="0" cellpadding="0">
				<thead>
					<tr>
						<th class="sortable {if $language->order_by=='id'}{$language->order}{/if}" order_by="id">ID<div class="sort"></div></th>
						<th class="sortable {if $language->order_by=='language'}{$language->order}{/if}" order_by="language">Label<div class="sort"></div></th>
						<th class="sortable {if $language->order_by=='locked'}{$language->order}{/if}" width="60" order_by="locked">Locked<div class="sort"></div></th>
						<th class="sortable {if $language->order_by=='ordr'}{$language->order}{/if}" width="60" order_by="ordr">Order<div class="sort"></div></th>
						<th class="center">Actions</th>
					</tr>
				</thead>
				<tbody>
					{if $no_results}<tr><td colspan="5" align="center">No languages match your criteria.</td></tr>{/if}
					{section name=i loop=$languages|@count}
					<tr>
						<td>{$languages[i].id}</td>
						<td>{$languages[i].language}</td>
						<td align="center"><input type="checkbox" class="locked" oid="{$languages[i].id}" ajaxurl="languages.php" ajaxaction="updateLocked" {if $languages[i].locked}checked="checked"{/if}/></td>
						<td align="center"><input type="text" class="ordr" oid="{$languages[i].id}" ajaxurl="languages.php" ajaxaction="updateOrdr" value="{$languages[i].ordr}" class="ordr"/></td>
						<td class="center">{if $languages[i].id!='en'}<a href="?action=edit&id={$languages[i].id}" class="edit"></a><a href="javascript:" class="delete" objectid="{$languages[i].id}"></a>{else}&nbsp;{/if}</td>
					</tr>
					{/section}
				</tbody>
				<tfoot>
					<tr>
						<td colspan="20">{$language->pagination}</td>
					</tr>
				</tfoot>
			</table>
		{else}
			<div class="row1">No items in Languages yet.</div>
		{/if}

{include file="footer.tpl"}