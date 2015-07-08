<?php
class ShoppingCartBase{

	//PayPal API details
	//To obtain this api info login to your paypal, goto My Account > Profile > Account Information::API Access > Request API credentials to create your own API username and password.
	//follow a few instructions and it all would be created.
	var $PayPalUsername = '';
	var $PayPalPassword = '';
	var $PayPalSignature = '';
	var $currency = "$";
	var $currencycode = "USD";

	function ShoppingCartBase(){
		global $db;
		$j = 0;
		for($i=0;$i<sizeof($_SESSION['cart']);$i++){
			if($_SESSION['cart'][$i]['pid']){
				$this->items[$j]->product = new Products($_SESSION['cart'][$i]['pid']);
				$this->items[$j]->qty = $_SESSION['cart'][$i]['qty'];
				$this->items[$j]->price = $_SESSION['cart'][$i]['price'];
				$this->items[$j]->options = $_SESSION['cart'][$i]['options'];
				$j++;
			}
		}

		$this->shipping_details = $_SESSION['cart']['shipping_details'];
		$this->billing_details = $_SESSION['cart']['billing_details'];

		if(!isset($_SESSION['cart']['countries'])){
			$q = $db->query("select * from ".DBPREFIX."countries where paypal_not_supported!='1'");
			foreach($q->result() as $row){
				$this->countries[] = array('name'=>$row->name, 'code'=>$row->iso1_code);
			}
			$_SESSION['cart']['countries'] = $this->countries;
		}else{
			$this->countries = $_SESSION['cart']['countries'];
		}

		if(!isset($_SESSION['cart']['states'])){
			$q = $db->query("select * from ".DBPREFIX."states");
			foreach($q->result() as $row){
				$this->states[] = array('name'=>$row->name, 'code'=>$row->abv);
			}
			$_SESSION['cart']['states'] = $this->states;
		}else{
			$this->states = $_SESSION['cart']['states'];
		}

	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function add($request){
		global $db;
		if(!isset($_SESSION['cart'])){$_SESSION['cart']= array();}
		//find out if we have this very same item in the cart and update the qty in request to total qty in cart + in request
		for($i=0;$i<sizeof($_SESSION['cart']);$i++){
			if($_SESSION['cart'][$i]['options']==$request['options']&&$_SESSION['cart'][$i]['pid']==$request['pid']){
				$request['qty'] += $_SESSION['cart'][$i]['qty'];
			}
		}
		//check the stock
		$stock = new Stock();
		if(!$price=$stock->checkStock($request)){
			$this->error = $stock->error;
			return false;
		}
		//if all fine add to cart
		$added=false;
		for($i=0;$i<sizeof($_SESSION['cart']);$i++){
			if($_SESSION['cart'][$i]['pid']==$request['pid']&&$_SESSION['cart'][$i]['options']==$request['options']){
				$added=true;
				$_SESSION['cart'][$i]['qty'] = $request['qty'];
				$_SESSION['cart'][$i]['price'] = $price;
			}
		}
		if(!$added){
			$_SESSION['cart'][] = array('pid'=>$request['pid'], 'qty'=>$request['qty'], 'price'=>$price, 'options'=>$request['options']);
		}

		return true;
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function getTotalItems(){
		$ttl = 0;
		for($i=0;$i<sizeof($_SESSION['cart']);$i++){
			$ttl += $_SESSION['cart'][$i]['qty'];
		}
		return $ttl;
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function getTotal(){
		$ttl = 0;
		for($i=0;$i<sizeof($_SESSION['cart']);$i++){
			$ttl += $_SESSION['cart'][$i]['qty']*$_SESSION['cart'][$i]['price'];
		}
		$ttl = round($ttl,2);
		return $ttl;
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function loadStock($pid){
		$stock = new Stock($pid);
		return $stock;
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function checkStock($request){
		$stock = new Stock($request['pid']);
		if($price = $stock->checkStock($request)){
			return $price;
		}else{
			$this->error = $stock->error;
			return false;
		}
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function deleteItem($pid){
		for($i=0;$i<sizeof($_SESSION['cart']);$i++){
			if($_SESSION['cart'][$i]['pid']==$pid){
				unset($_SESSION['cart'][$i]);
			}
		}
		return true;
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function update($request){
		global $db;
		if(!isset($_SESSION['cart'])){$this->error = "Something went wrong. Please reload the page and try again.";return false;}
		if($_SESSION['cart'][$request['index']]['pid']!=$request['pid']){$this->error = "Something went wrong. Please reload the page and try again.";return false;}
		$stock = new Stock();
		$request['options'] = $_SESSION['cart'][$request['index']]['options'];
		if(!$price=$stock->checkStock($request)){
			$this->error = $stock->error;
			return false;
		}
		$_SESSION['cart'][$request['index']]['qty'] = $request['qty'];
		return true;
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function clear(){
		unset($_SESSION['cart']);
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////
//Express paypal checkout methods

	function ExpressPayPalCheckout(){
		$this->pp = new PayPal($this->PayPalUsername, $this->PayPalPassword, $this->PayPalSignature);
		if(!$this->pp->SetExpressCheckout($this)){
			$this->error = "Some error occured. Please try again later.";
//			$this->error = $this->pp->error;
			return false;
		}
		return true;
	}

	function FinishExpressPayPalCheckout($request){
		$this->pp = new PayPal($this->PayPalUsername, $this->PayPalPassword, $this->PayPalSignature);
		$details = $this->pp->GetExpressCheckoutDetails($request['token']);
		if($details){
			$order = new Order();
			if($order->createFromExpressCheckout($details, $this)){
				if(!$this->pp->DoExpressCheckoutPayment($request['token'], $request['PayerID'], $this)){
					$this->error = "Couldn't finish your PayPal transaction, please try again later.";
					$order->cancel();
					return false;
				}
				$this->clear();
			}else{
				$this->error = $order->error;
				return false;
			}
		}else{
			$this->error = "Couldn't obtain your order details from PayPal. Your order won't be place and transaction would be canceled.";
			return false;
		}
		return true;
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////
//Regular checkout procedure

	function updateBillingDetails($request){

		$_SESSION['cart']['billing_details'] = array();

		if(!$request['cc_type']){$this->error = "Please enter all the billing details"; return false;}
		if(!$request['cc_fname']){$this->error = "Please enter all the billing details"; return false;}
		if(!$request['cc_lname']){$this->error = "Please enter all the billing details"; return false;}
		if(!$request['cc_number']){$this->error = "Please enter all the billing details"; return false;}
		if(!$request['cc_expm']){$this->error = "Please enter all the billing details"; return false;}
		if(!$request['cc_expy']){$this->error = "Please enter all the billing details"; return false;}
		if(!$request['cc_cvv']){$this->error = "Please enter all the billing details"; return false;}
		if(!$request['baddress1']){$this->error = "Please enter all the billing details"; return false;}
		if(!$request['bcity']){$this->error = "Please enter all the billing details"; return false;}
		if(!$request['bcountry']){$this->error = "Please enter all the billing details"; return false;}
		if(!$request['bzip']){$this->error = "Please enter all the billing details"; return false;}

		$_SESSION['cart']['billing_details']['cc_type'] = $request['cc_type'];
		$_SESSION['cart']['billing_details']['cc_fname'] = $request['cc_fname'];
		$_SESSION['cart']['billing_details']['cc_lname'] = $request['cc_lname'];
		$_SESSION['cart']['billing_details']['cc_number'] = $request['cc_number'];
		$_SESSION['cart']['billing_details']['cc_expm'] = $request['cc_expm'];
		$_SESSION['cart']['billing_details']['cc_expy'] = $request['cc_expy'];
		$_SESSION['cart']['billing_details']['cc_cvv'] = $request['cc_cvv'];
		$_SESSION['cart']['billing_details']['address1'] = $request['baddress1'];
		$_SESSION['cart']['billing_details']['address2'] = $request['baddress2'];
		$_SESSION['cart']['billing_details']['city'] = $request['bcity'];
		$_SESSION['cart']['billing_details']['country'] = $request['bcountry'];
		$_SESSION['cart']['billing_details']['state'] = $request['bstate'];
		$_SESSION['cart']['billing_details']['zip'] = $request['bzip'];

		$this->billing_details = $_SESSION['cart']['billing_details'];

		return true;

	}

	function updateShippingDetails($request){

		$_SESSION['cart']['shipping_details'] = array();

		if(!$request['email']){$this->error = "Please enter email!";return false;}
		if(!$request['phone']){$this->error = "Please enter phone number!";return false;}
		$e = new Email();
		if(!$e->validate($request['email'])){$this->error = "Invalid email address!";return false;}

		if($request['same_as_billing']==1){
			$_SESSION['cart']['shipping_details']['same_as_billing'] = $request['same_as_billing'];
			$_SESSION['cart']['shipping_details']['fname'] = $request['cc_fname'];
			$_SESSION['cart']['shipping_details']['lname'] = $request['cc_lname'];
			$_SESSION['cart']['shipping_details']['address1'] = $request['baddress1'];
			$_SESSION['cart']['shipping_details']['address2'] = $request['baddress2'];
			$_SESSION['cart']['shipping_details']['city'] = $request['bcity'];
			$_SESSION['cart']['shipping_details']['country'] = $request['bcountry'];
			$_SESSION['cart']['shipping_details']['state'] = $request['bstate'];
			$_SESSION['cart']['shipping_details']['zip'] = $request['bzip'];
			$_SESSION['cart']['shipping_details']['email'] = $request['email'];
			$_SESSION['cart']['shipping_details']['phone'] = $request['phone'];

			$this->shipping_details = $_SESSION['cart']['shipping_details'];

			return true;
		}
		$_SESSION['cart']['shipping_details']['same_as_billing'] = 0;
		if(!$request['fname']){$this->error = "Please enter all the shipping details"; return false;}
		if(!$request['lname']){$this->error = "Please enter all the shipping details"; return false;}
		if(!$request['address1']){$this->error = "Please enter all the shipping details"; return false;}
		if(!$request['city']){$this->error = "Please enter all the shipping details"; return false;}
		if(!$request['country']){$this->error = "Please enter all the shipping details"; return false;}
		if(!$request['zip']){$this->error = "Please enter all the shipping details"; return false;}

		$_SESSION['cart']['shipping_details']['fname'] = $request['fname'];
		$_SESSION['cart']['shipping_details']['lname'] = $request['lname'];
		$_SESSION['cart']['shipping_details']['address1'] = $request['address1'];
		$_SESSION['cart']['shipping_details']['address2'] = $request['address2'];
		$_SESSION['cart']['shipping_details']['city'] = $request['city'];
		$_SESSION['cart']['shipping_details']['country'] = $request['country'];
		$_SESSION['cart']['shipping_details']['state'] = $request['state'];
		$_SESSION['cart']['shipping_details']['zip'] = $request['zip'];
		$_SESSION['cart']['shipping_details']['email'] = $request['email'];
		$_SESSION['cart']['shipping_details']['phone'] = $request['phone'];

		$this->shipping_details = $_SESSION['cart']['shipping_details'];

		return true;
	}

	function RegularPayPalCheckout(){
		$this->pp = new PayPal($this->PayPalUsername, $this->PayPalPassword, $this->PayPalSignature);
		$order = new Order();
		if(!$order->create($this)){//this will make the order but the status would be 'temp', as the payment didn't went through yet
			$this->error = $order->error;
			return false;
		}
		if(!$this->pp->DoDirectPayment($this)){
			$order->cancel();
			$this->error = $this->pp->error;
			return false;
		}
		$order->place();//this will only place order once the payment went through ok otherwise the order would be canceled
		$this->clear();
		return true;
	}

}
?>