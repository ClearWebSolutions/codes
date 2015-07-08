{include file="header.tpl"}

	<h1>Order details</h1>

	<form action="orders.php" method="post" id="actionfrm">
		<input type="hidden" name="sbm" value="1"/>
		<input type="hidden" name="id" value="{$obj->id}"/>
		<input type="hidden" name="action" value="{$request.action}"/>

			<div class="outerRow {if !$error&&!$success}hidden{/if}">
				<div class="success-box {if !$success}hidden{/if}">
					<div class="success">{$success}</div>
				</div>
				<div class="error-box {if !$error}hidden{/if}">
					<div class="error">{$error}</div>
				</div>
			</div>
<br/>
			<div class="frmRow orderDetails">
				<div class="left">
					<h3>Order summary</h3>
					<label>Total:</label> <b>{$cart->currency}{$obj->total}</b><br/>
					<label>Items:</label> {$obj->totalItems}<br/>
					<label>Date:</label> {$obj->date}<br/>
					<label>Email:</label> <a href="mailto:{$obj->email}">{$obj->email}</a><br/>
					<label>Phone:</label> {$obj->shipping.phone}<br/>
					<label>Order status:</label>
					<select name="newstatus">
						<option {if $obj->status=='shipping'}selected="selected"{/if}>shipping</option>
						<option {if $obj->status=='archived'}selected="selected"{/if}>archived</option>
						<option {if $obj->status=='new'}selected="selected"{/if}>new</option>
					</select>
					<a href="javascript:" class="btnGrey" id="updateStatus">Update</a>
				</div>
				<div class="left">
					<h3>Shipping details</h3>
					{$obj->shipping.name}<br/>
					{$obj->shipping.street},<br/>
					{if $obj->shipping.street2}{$obj->shipping.street2},<br/>{/if}
					{$obj->shipping.city}{if $obj->shipping.state} {$obj->shipping.state}{/if} {$obj->shipping.zip}<br/>
					{$obj->shipping.country}
				</div>
				<div class="clear"></div>
			</div>
	</form>

			<table class="datatable" cellspacing="0" cellpadding="0">
				<thead>
					<tr>
						<th>&nbsp;</th>
						<th>Title</th>
						<th>Price</th>
						<th>Qty ordered</th>
						<th>Options</th>
						<th>Details</th>
					</tr>
				</thead>
				<tbody>
					{section name=i loop=$obj->items|@count}
					<tr>
						<td><img src="{$obj->items[i].product->galleries.0->imgs.0.url.admin}" border="0"/></td>
						<td>{$obj->items[i].product->title}</td>
						<td>{$obj->items[i].price}</td>
						<td>{$obj->items[i].qty}</td>
						<td>
							{section name=j loop=$obj->items[i].options}
								{$obj->items[i].options[j].name|ucfirst}: {$obj->items[i].options[j].value}<br/>
							{/section}
						</td>
						<td class="center"><a href="co.php?classname=Cars&action=edit&id={$obj->items[i].product->id}" class="edit"></a></td>
					</tr>
					{/section}
				</tbody>
				<tfoot>
					<tr>
						<td colspan="20">{$obj->pagination}</td>
					</tr>
				</tfoot>
			</table>

{include file="footer.tpl"}