<div class="codes_bubble">
	<div class="codes_tri"></div>
	<div class="codes_cnt">
		<h2>Shopping Cart</h2>
		<p>Location: <i>templates/codes/store_shopping_cart.tpl</i></p>

		Items in cart: <span class="sc_items_ttl">{$cart->getTotalItems()}</span><br/>
		Total price: $<span class="sc_price_ttl">{$cart->getTotal()}</span><br/><br/>

		<h3>Cart details <span class="fs10">(updates only on page refresh)</span></h3>
		{section name=i loop=$cart->items|@count}
			<div class="sc_item" pid="{$cart->items[i]->product->id}">
				<a href="index.php?products_id={$cart->items[i]->product->id}"><img src="{$cart->items[i]->product->galleries.0->imgs.0.url.admin}" border="0"/></a><br/>
				<a href="index.php?products_id={$cart->items[i]->product->id}">{$cart->items[i]->product->title}</a><br/>
				<div class="sc_details">
					{section name=j loop=$cart->items[i]->options|@count}
						{$cart->items[i]->options[j].name|ucfirst}: {$cart->items[i]->options[j].value}
						<br/>
					{/section}
					<a href="javascript:" class="sc_delete" pid="{$cart->items[i]->product->id}">Delete item</a>
					<input type="text" value="{$cart->items[i]->qty}" class="sc_qty" pid="{$cart->items[i]->product->id}" original="{$cart->items[i]->qty}" sci="{$smarty.section.i.index}"/>x $<span class="sc_item_price" pid="{$cart->items[i]->product->id}">{$cart->items[i]->price}</span> <b>= $<span class="sc_item_ttl" pid="{$cart->items[i]->product->id}">{$cart->items[i]->price*$cart->items[i]->qty}</span></b>
				</div>
			</div>
		{/section}
		<div class="sc_total">
			TOTAL: $<span class="sc_ttl">{$cart->getTotal()}</span><br/><br/>
			<a href="store.api.php?action=CheckOut" class="btn">Checkout</a><br/><br/>
			- or -<br/><br/>
			<a href="store.api.php?action=ExpressPayPalCheckout"><img src="https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif"/></a><br/>
		</div>
		<div class="clear"></div>
	</div>
</div>