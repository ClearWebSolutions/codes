<div class="codes_bubble">
	<div class="codes_tri"></div>
	<div class="codes_cnt codes_store">
		<h2>Store Checkout</h2>
		<p>Location: <i>templates/codes/store_checkout.tpl</i></p>

		{if $cart->getTotalItems()==0}
			Your cart is empty.
		{else}
		<form id="checkoutFrm" action="store.api.php" method="post">
			<input type="hidden" name="action" value="ReviewOrder"/>
			<h3>Card details</h3>

			{if $billing_error}<span class="error">{$billing_error}</span><br/>{/if}

			<label>Card Type <span class="error">*</span></label>
			<select name="cc_type" >
				<option value="">Please Select</option>
				<option value="VISA" {if $post.cc_type=='VISA'||$cart->billing_details.cc_type=='VISA'}selected="selected"{/if}>VISA</option>
				<option value="MasterCard" {if $post.cc_type=='MasterCard'||$cart->billing_details.cc_type=='MasterCard'}selected="selected"{/if}>MasterCard</option>
				<option value="AmericanExpress" {if $post.cc_type=='AmericanExpress'||$cart->billing_details.cc_type=='AmericanExpress'}selected="selected"{/if}>AmericanExpress</option>
			</select>

			<br/><br/>

			<label>Cardholder's Name <span class="error">*</span></label>
			<input type="text" name="cc_fname"  placeholder="First name" value="{if $post.cc_fname}{$post.cc_fname}{elseif $cart->billing_details.cc_fname}{$cart->billing_details.cc_fname}{/if}"/>
			<input type="text" name="cc_lname"  placeholder="Last name" value="{if $post.cc_lname}{$post.cc_lname}{elseif $cart->billing_details.cc_lname}{$cart->billing_details.cc_lname}{/if}"/>

			<br/><br/>

			<label>Car Number <span class="error">*</span></label>
			<input type="text" name="cc_number" value="{if $post.cc_number}{$post.cc_number}{elseif $cart->billing_details.cc_number}{$cart->billing_details.cc_number}{/if}"/>

			<br/><br/>

			<label>Expiration date <span class="error">*</span></label>
			<select name="cc_expm">
				<option value="">Select Month</option>
				<option value="1" {if $post.cc_expm=='1'}selected='true'{elseif $cart->billing_details.cc_expm=='1'}selected='true'{/if}>Jan</option>
				<option value="2" {if $post.cc_expm=='2'}selected='true'{elseif $cart->billing_details.cc_expm=='2'}selected='true'{/if}>Feb</option>
				<option value="3" {if $post.cc_expm=='3'}selected='true'{elseif $cart->billing_details.cc_expm=='3'}selected='true'{/if}>Mar</option>
				<option value="4" {if $post.cc_expm=='4'}selected='true'{elseif $cart->billing_details.cc_expm=='4'}selected='true'{/if}>Apr</option>
				<option value="5" {if $post.cc_expm=='5'}selected='true'{elseif $cart->billing_details.cc_expm=='5'}selected='true'{/if}>May</option>
				<option value="6" {if $post.cc_expm=='6'}selected='true'{elseif $cart->billing_details.cc_expm=='6'}selected='true'{/if}>Jun</option>
				<option value="7" {if $post.cc_expm=='7'}selected='true'{elseif $cart->billing_details.cc_expm=='7'}selected='true'{/if}>Jul</option>
				<option value="8" {if $post.cc_expm=='8'}selected='true'{elseif $cart->billing_details.cc_expm=='8'}selected='true'{/if}>Aug</option>
				<option value="9" {if $post.cc_expm=='9'}selected='true'{elseif $cart->billing_details.cc_expm=='9'}selected='true'{/if}>Sep</option>
				<option value="10" {if $post.cc_expm=='10'}selected='true'{elseif $cart->billing_details.cc_expm=='10'}selected='true'{/if}>Oct</option>
				<option value="11" {if $post.cc_expm=='11'}selected='true'{elseif $cart->billing_details.cc_expm=='11'}selected='true'{/if}>Nov</option>
				<option value="12" {if $post.cc_expm=='12'}selected='true'{elseif $cart->billing_details.cc_expm=='12'}selected='true'{/if}>Dec</option>
			</select>
			<select name="cc_expy">
				<option value="">Select Year</option>
				{assign var=now value=$smarty.now|date_format:'%Y'}
				{section name=y start=$now loop=$now+10}
					<option value="{$smarty.section.y.index}" {if $post.cc_expy==$smarty.section.y.index}selected='true'{elseif $cart->billing_details.cc_expy==$smarty.section.y.index}selected='true'{/if}>{$smarty.section.y.index}</option>
				{/section}
			</select>

			<br/><br/>

			<label>CVV2 <span class="error">*</span></label>
			<input type="text" name="cc_cvv"/>

			<h3>Billing address</h3>

			<div class="billing_address">
				<label>Address 1 <span class="error">*</span></label>
				<input type="text" name="baddress1" value="{if $post.baddress1}{$post.baddress1}{else}{$cart->billing_details.address1}{/if}"/>

				<br/><br/>

				<label>Address 2</label>
				<input type="text" name="baddress2" value="{if $post.baddress2}{$post.baddress2}{else}{$cart->billing_details.address2}{/if}"/>

				<br/><br/>

				<label>City <span class="error">*</span></label>
				<input type="text" name="bcity" value="{if $post.bcity}{$post.bcity}{else}{$cart->billing_details.city}{/if}"/>

				<br/><br/>

				<label>Country <span class="error">*</span></label>
				<select name="bcountry">
					{section name=c loop=$cart->countries|@count}
					<option value="{$cart->countries[c].code}" {if $post.bcountry==$cart->countries[c].code||$cart->billing_details.country==$cart->countries[c].code}selected="selected"{/if}{if !$cart->billing_details.country&&!$post.bcountry&&$cart->countries[c].code=='US'}selected="selected"{/if}>{$cart->countries[c].name}</option>
					{/section}
				</select>

				<br/><br/>

				<label>State/Province</label>
				<select name="bstate">
					<option value=""></option>
					{section name=s loop=$cart->states|@count}
					<option value="{$cart->states[s].code}" {if $post.bstate==$cart->states[s].code||$cart->billing_details.state==$cart->states[s].code}selected="selected"{/if}>{$cart->states[s].name}</option>
					{/section}
				</select>

				<br/><br/>

				<label>Zip / Postal code <span class="error">*</span></label>
				<input type="text" name="bzip" value="{if $post.bzip}{$post.bzip}{else}{$cart->billing_details.zip}{/if}"/>
			</div>

			<hr/>

			<h3>Shipping details <span class="fs10"><input type="checkbox" name="same_as_billing" value="1" {if ($post.same_as_billing&&$post.same_as_billing!=1)||($cart->same_as_billing&&$cart->same_as_billing!=1)}{else}checked="checked"{/if}/> same as billing</span></h3>

				{if $shipping_error}<span class="error">{$shipping_error}</span><br/>{/if}

				<label>Email Address <span class="error">*</span></label>
				<input type="text" name="email" value="{if $post.email}{$post.email}{else}{$cart->shipping_details.email}{/if}"/>

				<br/><br/>

				<label>Phone <span class="error">*</span></label>
				<input type="text" name="phone"  value="{if $post.phone}{$post.phone}{else}{$cart->shipping_details.phone}{/if}"/>

				<br/><br/>

			<div class="shipping_address">
				<label>Ship to Name <span class="error">*</span></label>
				<input type="text" name="fname" placeholder="First name"/>
				<input type="text" name="lname" placeholder="Last name"/>

				<br/><br/>

				<label>Address 1 <span class="error">*</span></label>
				<input type="text" name="address1" value="{if $post.address1}{$post.address1}{else}{$cart->shipping_details.address1}{/if}"/>

				<br/><br/>

				<label>Address 2</label>
				<input type="text" name="address2" value="{if $post.address2}{$post.address2}{else}{$cart->shipping_details.address2}{/if}"/>

				<br/><br/>

				<label>City <span class="error">*</span></label>
				<input type="text" name="city" value="{if $post.city}{$post.city}{else}{$cart->shipping_details.city}{/if}"/>

				<br/><br/>

				<label>Country <span class="error">*</span></label>
				<input type="text" name="country" value="{if $post.country}{$post.country}{else}{$cart->shipping_details.country}{/if}"/>

				<br/><br/>

				<label>State/Province</label>
				<input type="text" name="state" value="{if $post.state}{$post.state}{else}{$cart->shipping_details.state}{/if}"/>

				<br/><br/>

				<label>Zip / Postal code <span class="error">*</span></label>
				<input type="text" name="zip" value="{if $post.zip}{$post.zip}{else}{$cart->shipping_details.zip}{/if}"/>
			</div>

			<hr/>

			<input type="submit" name="sbm" value="Continue to review page" class="btn continue_checkout"/>
		</form>
		{/if}

	</div>
</div>

