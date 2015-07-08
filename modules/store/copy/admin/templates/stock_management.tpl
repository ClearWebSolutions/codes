<div class="frmRow"><h3>Stock</h3></div>
<table class="datatable stocktable" cellspacing="0" cellpadding="0">
	<thead>
		<tr>
			<th width="100">Price ({$cart->currency})</th>
			<th width="100" class="tac">Qty</th>
			<th>Color</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
	
		{if $obj->stock->entries|@count>0}
			{section name=s loop=$obj->stock->entries|@count}
				<tr stockid="{$obj->stock->entries[s].id}">
					<td><input type="text" value="{$obj->stock->entries[s].price}"/></td>
					<td><input type="text" value="{$obj->stock->entries[s].qty}"/></td>
					<td>stock options</td>
					<td><a href="javascript:" class="btnGrey updateStock">Update</a></td>
				</tr>
			{/section}
		{else}
			<tr stockid="">
				<td><input type="text"/></td>
				<td><input type="text" /></td>
				<td>options default</td>
				<td><a href="javascript:" class="btnGrey updateStock">Add</a></td>
			</tr>
		{/if}
		
	</tbody>
</table>
<br/><a href="javascript:" class="btnGrey stockAdd">Add Row</a><br/><br/><br/><br/>