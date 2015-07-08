{include file="header.tpl"}

		<div id="deletePopup" class="box hidden">
			<div class="tri"></div>
			<h2>Delete Order?</h2>
			<form action="orders.php" method="post" id="deletefrm">
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
		<h1>Orders</h1>

		<div class="outerRow {if !$request.added}hidden{/if} pb0">
			<div class="success-box {if !$request.added}hidden{/if}">
			<div class="success">Order created successfully.</div>
			</div>
		</div>


			<div class="searchRow">
				<form action="orders.php" method="post" id="search">
					<input type="hidden" name="searchsbm" value="1"/>
					<label>Status: </label>
					<select name="status">
						<option {if $obj->status=='new'}selected="selected"{/if}>new</option>
						<option {if $obj->status=='shipping'}selected="selected"{/if}>shipping</option>
						<option {if $obj->status=='archived'}selected="selected"{/if}>archived</option>
					</select>
					<a href="javascript:submitFrm('search')" class="btnGrey">Search</a>
				</form>
			</div>

			<table class="datatable" cellspacing="0" cellpadding="0">
				<thead>
					<tr>
						<th><input type="checkbox" class="selectAll"/></th>
						<th class="sortable {if $obj->order_by=='id'}{$obj->order}{/if}" order_by="id">ID<div class="sort"></div></th>
						<th class="sortable {if $obj->order_by=='date'}{$obj->order}{/if}" order_by="date">Date<div class="sort"></div></th>
						<th class="sortable {if $obj->order_by=='email'}{$obj->order}{/if}" order_by="email">Contact email<div class="sort"></div></th>
						<th class="sortable {if $obj->order_by=='totalitems'}{$obj->order}{/if}" order_by="totalitems">Items ordered<div class="sort"></div></th>
						<th class="sortable {if $obj->order_by=='total'}{$obj->order}{/if}" order_by="total">Order total<div class="sort"></div></th>
						<th class="sortable {if $obj->order_by=='status'}{$obj->order}{/if}" order_by="status">Status<div class="sort"></div></th>
						<th class="center">Actions</th>
					</tr>
				</thead>
				<tbody>
					{if $objects|@count==0}<tr><td colspan="5" align="center">No Orders match your criteria.</td></tr>{/if}
					{section name=i loop=$objects|@count}
					<tr>
						<td><input type="checkbox" class="status" oid="{$objects[i].id}"/></td>
						<td align="center">{$objects[i].id}</td>
						<td>{$objects[i].date}</td>
						<td><a href="mailto:{$objects[i].email}">{$objects[i].email}</a></td>
						<td align="center">{$objects[i].total_items}</td>
						<td align="center">{$sc->currency}{$objects[i].total}</td>
						<td align="center">{$objects[i].status}</td>
						<td class="center"><a href="?action=edit&id={$objects[i].id}" class="edit"></a><a href="javascript:" class="delete" objectid="{$objects[i].id}"></a></td>
						</tr>
					{/section}
				</tbody>
				<tfoot>
					<tr>
						<td colspan="20">{$obj->pagination}</td>
					</tr>
				</tfoot>
			</table>

			<div class="searchRow">
				<form action="orders.php" method="post" id="updateStatusFrm">
					<input type="hidden" name="action" value="updateStatus"/>
					<input type="hidden" name="ids" value=""/>
					<input type="hidden" name="status" value=""/>
					<input type="hidden" name="searchsbm" value="1"/>
					<label>For selected update status to:</label>
					<select name="newstatus">
						<option>shipping</option>
						<option>archived</option>
						<option>new</option>
					</select>
					<a href="javascript:submitFrm('updateStatusFrm')" class="btnGrey">Update</a>
				</form>
			</div>

{include file="footer.tpl"}