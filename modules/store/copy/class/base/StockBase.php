<?
class StockBase{

	function StockBase($pid=0){//product id is the param
		global $db;
		if($pid){
			$this->pid = $pid;
			$this->entries = array();
			$i=0;
			$q = $db->query("select * from ".DBPREFIX."stock where pid='".$this->pid."' order by id");
			foreach($q->result() as $row){
			
				//getting unique option values for this product from the stock, no need for extra sql for this as we can just manage it here
				if($row->qty>0){
					$this->unique_options = array();
				}

				$this->entries[$i]['id'] = $row->id;
				$this->entries[$i]['price'] = $row->price;
				$this->entries[$i]['qty'] = $row->qty;
				//options stuff here
				$this->qty += $row->qty;
				$i++;
			}
		}
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function update($request){
		global $db;
		if($request['id']){
			if(!$this->check($request)){return false;}
			$this->id = $request['id'];
			for($i=0;$i<sizeof($request['options']);$i++){
				$options .= ", ".$request['options'][$i]['id']."='".$request['options'][$i]['value']."'";
			}
			//update stock entry (pid is product id)
			$db->query("update ".DBPREFIX."stock set price='".$request['price']."', qty='".$request['qty']."' ".$options." where id='".$this->id."'");
		}else{
			if(!$this->add($request)){
				return false;
			}
		}
		return $this->id;
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function add($request){
		global $db;
		if(!$this->check($request)){return false;}
		//insert stock entry (pid is product id)
		for($i=0;$i<sizeof($request['options']);$i++){
			$options .= ", ".$request['options'][$i]['id']."='".$request['options'][$i]['value']."'";
		}
		$db->query("insert into ".DBPREFIX."stock set pid='".$request['pid']."', price='".$request['price']."', qty='".$request['qty']."'".$options);
		$this->id = $db->insert_id();
		return true;
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function check($request){
		global $db;
		//check if we have the product id
		if(!$request['pid']){$this->error = "Product ID error";return false;}
		//check if we have the price
		if(!$request['price']&&$request['price']!=0){$this->error = "Please enter the price or 0 if it's free."; return false;}
		//check if we have the qty
		if(!$request['qty']&&$request['qty']!=0){$this->error = "Please enter quantity or 0 if no such items left in stock."; return false;}
		//options might be empty we don't care, as some products might not need them
		//but lets check if there is already a stock entry with same options then we'll deny this operation
		$sql = "";
		if($request['id']){$sql=" and id!='".$request['id']."'";}
		for($i=0;$i<sizeof($request['options']);$i++){
			$options .= "and ".$request['options'][$i]['id']."='".$request['options'][$i]['value']."'";
		}
		$q = $db->query("select * from ".DBPREFIX."stock where pid='".$request['pid']."' ".$options." ".$sql);
		if($q->num_rows()>0){$this->error = "There is already a stock entry with this options, please modify it's values or change the options."; return false;}
		return true;
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function checkStock($request){
		global $db;
		for($i=0;$i<sizeof($request['options']);$i++){
			$sql .= "and ".$request['options'][$i]['name']."='".$request['options'][$i]['value']."'";
		}
		$q = $db->query("select price, qty from ".DBPREFIX."stock where pid='".$request['pid']."' ".$sql);
		if($q->num_rows()==0){$this->error = "Out of stock."; return false;}//actually there is no such option combination  in stock at all
		$row = $q->next_row();
		if($row->qty<$request['qty']){$this->error = "Not enough items in stock."; return false;}//not enough items
		return $row->price;
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function updateQty($request){
		global $db;
		if($request['pid']&&$request['qty']){
			for($i=0;$i<sizeof($request['options']);$i++){
				$sql .= "and ".$request['options'][$i]['name']."='".$request['options'][$i]['value']."'";
			}
			$db->query("update ".DBPREFIX."stock set qty=qty-".$request['qty']." where pid='".$request['pid']."' ".$sql);
		}else{
			return false;
		}
		return true;
	}

}
?>