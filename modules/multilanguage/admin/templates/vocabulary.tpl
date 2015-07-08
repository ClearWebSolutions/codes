{include file="header.tpl"}

		<!-- this is a standard inline popup box that would be used by scripts to display -->

		<div id="deletePopup" class="box hidden">
			<div class="tri"></div>
			<h2>Delete item?</h2>
			<form action="vocabulary.php" method="post" id="deletefrm">
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
		<h1>Vocabulary</h1>

		<div class="outerRow {if !$request.added}hidden{/if} pb0">
			<div class="success-box {if !$request.added}hidden{/if}">
				<div class="success">Item added successfully.</div>
			</div>
		</div>


		{if $vocabulary->byLabel|@count||$no_results}
			<div class="searchRow">
				<form action="vocabulary.php" method="post" id="search">
					<input type="text" name="search" value="{$vocabulary->search}" class="search ml20" placeholder="Keyword"/>
					<a href="javascript:submitFrm('search')" class="btnGrey">Search</a>
				</form>
			</div>
		
			<table class="datatable" cellspacing="0" cellpadding="0">
				<thead>
					<tr>
						<th class="sortable {if $vocabulary->order_by=='lang_parent'}{$vocabulary->order}{/if}" order_by="lang_parent">ID<div class="sort"></div></th>
						<th class="sortable {if $vocabulary->order_by=='label'}{$vocabulary->order}{/if}" width="200" order_by="label">Label<div class="sort"></div></th>
						<th class="sortable {if $vocabulary->order_by=='phrase'}{$vocabulary->order}{/if}" order_by="phrase">In English<div class="sort"></div></th>
						<th class="center">Actions</th>
					</tr>
				</thead>
				<tbody>
					{if $no_results}<tr><td colspan="5" align="center">No phrases or labels match your criteria.</td></tr>{/if}
					{section name=i loop=$vocabulary->labels|@count}
					<tr>
						<td>{$vocabulary->labels[i].id}</td>
						<td>{$vocabulary->labels[i].label}</td>
						<td>{$vocabulary->labels[i].phrase}</td>
						<td class="center"><a href="?action=edit&id={$vocabulary->labels[i].id}" class="edit"></a><a href="javascript:" class="delete" objectid="{$vocabulary->labels[i].id}"></a></td>
					</tr>
					{/section}
				</tbody>
				<tfoot>
					<tr>
						<td colspan="20">{$vocabulary->pagination}</td>
					</tr>
				</tfoot>
			</table>
		{else}
			<div class="row1">No items in Vocabulary yet.</div>
		{/if}

{include file="footer.tpl"}