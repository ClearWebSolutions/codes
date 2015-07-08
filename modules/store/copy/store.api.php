<?php
include("header.php");

if($_REQUEST['action']=='add2cart'){
	//cart is defined in header
	if($cart->add($_REQUEST)){
		$json['success'] = '1';
		$json['items_ttl'] = $cart->getTotalItems();
		$json['ttl'] = $cart->getTotal();
	}else{
		$json['error'] = '1';
		$json['error_msg'] = $cart->error;
	}
	echo json_encode($json);
}

if($_REQUEST['action']=='delete'){
	$cart->deleteItem($_REQUEST['pid']);
	$json['success'] = '1';
	$json['items_ttl'] = $cart->getTotalItems();
	$json['ttl'] = $cart->getTotal();
	echo json_encode($json);
}

if($_REQUEST['action']=='update'){
	if($cart->update($_REQUEST)){
		$json['success'] = '1';
		$json['price'] = $cart->items[$_REQUEST['index']]->price;
		$json['items_ttl'] = $cart->getTotalItems();
		$json['ttl'] = $cart->getTotal();
	}else{
		$json['error'] = '1';
		$json['error_msg'] = $cart->error;
	}
	echo json_encode($json);
}

if($_REQUEST['action']=='checkStock'){
	if($price=$cart->checkStock($_REQUEST)){
		$json['success'] = '1';
		$json['price'] = $price;
	}else{
		$json['error'] = '1';
		$json['error_msg'] = $cart->error;
	}
	echo json_encode($json);
}

//////////////////////////////////////////////////////////////////////////
//PAYPAL EXPRESS CHECKOUT
if($_REQUEST['action']=='ExpressPayPalCheckout'){
	if(!$cart->ExpressPayPalCheckout()){
		echo $cart->error;
	}
}

if($_REQUEST['token']&&$_REQUEST['PayerID']){
	//creates an order, places transaction with paypal and clears the cart
	if($cart->FinishExpressPayPalCheckout($_REQUEST)){
		//display thank you
		echo "thank you";
	}else{
		echo $cart->error;
	}
}

//////////////////////////////////////////////////////////////////////////
//REGULAR CHECKOUT
if($_REQUEST['action']=='CheckOut'){
	$smarty->display("checkout.tpl");
}

if($_REQUEST['action']=='ReviewOrder'){
	if(!$_REQUEST['same_as_billing']){$_REQUEST['same_as_billing']=0;}
	if($cart->updateBillingDetails($_REQUEST)){
		if($cart->updateShippingDetails($_REQUEST)){
			$smarty->assign("page","review_order");
			$smarty->display("checkout.tpl");
		}else{
			$smarty->assign("shipping_error", $cart->error);
		}
	}else{
		$smarty->assign("billing_error", $cart->error);
	}
	$smarty->assign("post",$_REQUEST);
	$smarty->display("checkout.tpl");
}

if($_REQUEST['action']=='PlaceOrder'){
	if($cart->RegularPayPalCheckout()){
		echo "thank you";
	}else{
		echo $cart->error;
	}
}

?>