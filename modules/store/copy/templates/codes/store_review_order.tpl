<div class="codes_bubble">
	<div class="codes_tri"></div>
	<div class="codes_cnt">
		<h2>Review Order</h2>
		<p>Location: <i>templates/codes/store_review_order.tpl</i></p>

		Total items: <span class="sc_items_ttl">{$cart->getTotalItems()}</span><br/>
		Total price: $<span class="sc_price_ttl">{$cart->getTotal()}</span><br/><br/>

		<h3>Order details</h3>
		{section name=i loop=$cart->items|@count}
			<div class="sc_item">
				<a href="index.php?products_id={$cart->items[i]->product->id}"><img src="{$cart->items[i]->product->galleries.0->imgs.0.url.admin}" border="0"/></a><br/>
				<a href="index.php?products_id={$cart->items[i]->product->id}">{$cart->items[i]->product->title}</a><br/>
				<div class="sc_details">
					<div class="priceqty">{$cart->items[i]->qty} x ${$cart->items[i]->price} <b>= ${$cart->items[i]->price*$cart->items[i]->qty}</b></div>
				</div>
			</div>
		{/section}
		<div class="sc_total">
			TOTAL: ${$cart->getTotal()}<br/><br/>
		</div>

		<h3>Billing details</h3>
		{$cart->billing_details.cc_fname} {$cart->billing_details.cc_lname}<br/>
		**** **** **** {$cart->billing_details.cc_number|substr:12}<br/>
		{$cart->billing_details.address1}<br/>
		{if $cart->billing_details.address2}{$cart->billing_details.address2}<br/>{/if}
		{$cart->billing_details.city}, {$cart->billing_details.state}, {$cart->billing_details.zip}<br/>
		{$cart->billing_details.country}

		<h3>Shipping details</h3>
		{$cart->shipping_details.fname} {$cart->shipping_details.lname}<br/>
		{$cart->shipping_details.email}<br/>
		{$cart->shipping_details.phone}<br/>
		{$cart->shipping_details.address1}<br/>
		{if $cart->shipping_details.address2}{$cart->shipping_details.address2}<br/>{/if}
		{$cart->shipping_details.city}, {$cart->shipping_details.state}, {$cart->shipping_details.zip}<br/>
		{$cart->shipping_details.country}

		<div class="right"><a href="store.api.php?action=PlaceOrder" class="btn">Place Order</a></div><br/><br/>
		<div class="clear"></div>
	</div>
</div>