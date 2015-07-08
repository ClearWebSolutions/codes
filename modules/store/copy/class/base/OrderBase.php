<?php
class OrderBase{

	function OrderBase($id=0){
		global $db;
		if($id){
			$this->id = $id;
			$q = $db->query("select *,  DATE_FORMAT(date, '%H:%i - %d %M %Y') as date from ".DBPREFIX."orders where id='".$this->id."'");
			$row = $q->next_row();
			$this->date = $row->date;
			$this->totalItems = $row->totalItems;
			$this->total = $row->total;
			$this->email = $row->email;
			$this->status = $row->status;
			//getting shipping details
			$q = $db->query("select *, c.name as country from ".DBPREFIX."order_shipping as os, ".DBPREFIX."countries as c where os.oid='".$this->id."' and c.iso1_code=os.shiptocountrycode");
			$row = $q->next_row();
			$this->shipping['name'] = $row->shiptoname;
			$this->shipping['street'] = $row->shiptostreet;
			$this->shipping['street2'] = $row->shiptostreet2;
			$this->shipping['city'] = $row->shiptocity;
			$this->shipping['state'] = $row->shiptostate;
			$this->shipping['zip'] = $row->shiptozip;
			$this->shipping['country'] = $row->country;
			$this->shipping['phone'] = $row->shiptophonenum;
			//get order details items, price, qty, options
			$q = $db->query("select * from ".DBPREFIX."order_details where oid='".$this->id."'");
			$i=0;
			foreach($q->result() as $row){
				$this->items[$i]['product'] = new Cars($row->pid);
				$this->items[$i]['price'] = $row->price;
				$this->items[$i]['qty'] = $row->qty;
				$this->items[$i]['options'] = unserialize($row->options);
				$i++;
			}
		}
	}

//////////////////////////////////////////////////////////////////////////////////////////

	function create($cart){
		global $db;

		$db->query("insert into ".DBPREFIX."orders set date=CURRENT_TIMESTAMP(), totalItems='".$cart->getTotalItems()."', total='".$cart->getTotal()."', email='".$cart->shipping_details['email']."', ipaddress='".$_SERVER['REMOTE_ADDR']."'");
		$this->id = $db->insert_id();

		$db->query("insert into ".DBPREFIX."order_shipping set oid='".$this->id."', shiptoname='".$cart->shipping_details['fname']." ".$cart->shipping_details['lname']."', shiptostreet='".$cart->shipping_details['address1']."', shiptostreet2='".$cart->shipping_details['address2']."', shiptocity='".$cart->shipping_details['city']."', shiptostate='".$cart->shipping_details['state']."', shiptozip='".$cart->shipping_details['zip']."', shiptocountrycode='".$cart->shipping_details['country']."', shiptophonenum='".$cart->shipping_details['phone']."'");

		if(!$this->putOrderDetails($cart)){return false;}

		return true;
	}

//////////////////////////////////////////////////////////////////////////////////////////

	function place(){
		global $db;
		$db->query("update ".DBPREFIX."orders set status='new' where id='".$this->id."'");
		return true;
	}

//////////////////////////////////////////////////////////////////////////////////////////

	function putOrderDetails($cart){
		global $db;
		$stock = new Stock();
		for($i=0;$i<sizeof($cart->items);$i++){
			$a = array();
			$a['options'] = $cart->items[$i]->options;
			$a['pid'] = $cart->items[$i]->product->id;
			if(!$price=$stock->checkStock($a)){
				$this->cancel();
				$this->error = "Some products from your cart just went out of stock.";
				return false;
			}
			$db->query("insert into ".DBPREFIX."order_details set price='".$cart->items[$i]->price."', qty='".$cart->items[$i]->qty."', pid='".$cart->items[$i]->product->id."', oid='".$this->id."', options='".serialize($cart->items[$i]->options)."'");
			$a['qty'] = $cart->items[$i]->qty;
			if(!$stock->updateQty($a)){
				$this->cancel();
				$this->error = "Some products from your cart just went out of stock.";//actually it means something is wrong
				return false;
			}
		}
		return true;
	}

//////////////////////////////////////////////////////////////////////////////////////////

	function createFromExpressCheckout($details, $cart){
		global $db;

		$db->query("insert into ".DBPREFIX."orders set date=CURRENT_TIMESTAMP(), totalItems='".$cart->getTotalItems()."', total='".$cart->getTotal()."', email='".$details['EMAIL']."', ipaddress='".$_SERVER['REMOTE_ADDR']."'");
		$this->id = $db->insert_id();

		$db->query("insert into ".DBPREFIX."order_shipping set oid='".$this->id."', shiptoname='".$details['PAYMENTREQUEST_0_SHIPTONAME']."', shiptostreet='".$details['PAYMENTREQUEST_0_SHIPTOSTREET']."', shiptostreet2='".$details['PAYMENTREQUEST_0_SHIPTOSTREET2']."', shiptocity='".$details['PAYMENTREQUEST_0_SHIPTOCITY']."', shiptostate='".$details['PAYMENTREQUEST_0_SHIPTOSTATE']."', shiptozip='".$details['PAYMENTREQUEST_0_SHIPTOZIP']."', shiptocountrycode='".$details['PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE']."', shiptophonenum='".$details['PAYMENTREQUEST_0_SHIPTOPHONENUM']."'");

		if(!$this->putOrderDetails($cart)){return false;}

		$this->place();

		return true;
	}

//////////////////////////////////////////////////////////////////////////////////////////

	function cancel(){
		$this->delete();
	}

//////////////////////////////////////////////////////////////////////////////////////////

	function delete(){
		global $db;
		$db->query("delete from ".DBPREFIX."orders where id='".$this->id."'");
		$db->query("delete from ".DBPREFIX."order_shipping where oid='".$this->id."'");
		$db->query("delete from ".DBPREFIX."order_details where oid='".$this->id."'");
	}

//////////////////////////////////////////////////////////////////////////////////////////

	function getAll($request){
		global $db, $settings;
		$a = explode($_SERVER['HTTP_HOST'], URL);
		if(strstr($_SERVER['REQUEST_URI'], $a[1].'/admin/')||strstr($_SERVER['PHP_SELF'], '/admin/')){
			$admin=true;
			$environment = 'admin';
		}else{
			$admin=false;
			$environment = 'frontend';
		}
		//if we enter the page from another page we need to clear the session
		if(!$request['order_by']&&!$request['order']&&!$request['page']&&!$request['search']) $_SESSION[$environment.'_orders'] = '';

		if($request['order_by']){
			$this->order_by = $request['order_by'];
			if($_SESSION[$environment.'_orders']['order_by']==$this->order_by&&$_SESSION[$environment.'_orders']['order']=='asc'&&!$request['order']){	$request['order']='desc';}
			if($_SESSION[$environment.'_orders']['order_by']==$this->order_by&&$_SESSION[$environment.'_orders']['order']=='desc'&&!$request['order']){	$request['order']='asc';}
		}else{
			if($_SESSION[$environment.'_orders']['order_by']){$this->order_by = $_SESSION[$environment.'_orders']['order_by'];}else{$this->order_by = 'date';}
		}
		if($request['order']){
			$this->order = $request['order'];
		}else{
			if($_SESSION[$environment.'_orders']['order']){$this->order = $_SESSION[$environment.'_orders']['order'];}else{$this->order = 'desc';}
		}
		if($request['page']){
			$this->page = $request['page'];
		}else{
			if($_SESSION[$environment.'_orders']['page']){$this->page = $_SESSION[$environment.'_orders']['page'];}else{$this->page = 1;}
		}
		if($request['status']){
			$this->search_status = $request['status'];
		}else{
			if($_SESSION[$environment.'_orders']['status']){$this->search_status = $_SESSION[$environment.'_orders']['status'];}else{$this->search_status = 'new';}
		}
		$_SESSION[$environment.'_orders']['order_by'] = $this->order_by;
		$_SESSION[$environment.'_orders']['order'] = $this->order;
		$_SESSION[$environment.'_orders']['page'] = $this->page;
		$_SESSION[$environment.'_orders']['status'] = $this->search_status;
		if($this->search_status){
			$statussql = " and status='".$this->search_status."'";
		}else{
			$statussql='';
		}
		if($admin){
			$sql = $statussql." order by ".$this->order_by." ".$this->order." limit ".($this->page-1)*$settings->arpp.", ".$settings->arpp;
		}else{
			$sql = $statussql." order by ".$this->order_by." ".$this->order." limit ".($this->page-1)*$settings->rpp.", ".$settings->rpp;
		}
		$q = $db->query("select *, DATE_FORMAT(date, '%H:%i - %d %M %Y') as date from ".DBPREFIX."orders where status!='temp' ".$sql);
		$i=0;
		foreach($q->result() as $row){
			$objects[$i]['id'] = $row->id;
			$objects[$i]['date'] = $row->date;
			$objects[$i]['total'] = $row->total;
			$objects[$i]['total_items'] = $row->totalItems;
			$objects[$i]['status'] = $row->status;
			$objects[$i]['email'] = $row->email;
			$i++;
		}

		$q = $db->query("select * from ".DBPREFIX."orders  where status!='temp' ".$searchsql);
		$this->pagination = pagination($this->page, (int)$q->num_rows(), $admin==true?$settings->arpp:$settings->rpp);
		return $objects;
	}

//////////////////////////////////////////////////////////////////////////////////////////

	function updateStatus($request){
		global $db;
		$ids = explode(',',$request['ids']);
		foreach($ids as $k=>$id){
			if($id){
				$db->query("update ".DBPREFIX."orders set status='".$request['newstatus']."' where id='".$id."'");
			}
		}
		return true;
	}
}
?>