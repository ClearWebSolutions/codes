<div class="store">
	<div class="store_title">Store:</div>
	<div class="clear"></div>

	{assign var="stock" value=$cart->loadStock($id)}

	<div class="store_options">

		<!--store options-->

	</div>
	<div class="store_qty">Quantity: <input type="text" id="qty{$id}" class="qty" pid="{$id}" value="1"/></div>
	<div class="store_price">$<span id="ttl_price{$id}">{$stock->entries[0].price}</span></div>
	<div class="store_add">
		{if $stock->qty}<a href="javascript:" class="add2cart" pid="{$id}">Add to cart</a>{else}Out of Stock{/if}
	</div>
	<input type="hidden" id="price{$id}" value="{$stock->entries[0].price}"/>

	<div class="clear"></div>
</div>