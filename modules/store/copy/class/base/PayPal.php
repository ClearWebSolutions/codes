<?
class PayPal{
/*
	PayPal includes the following API Signature for making API calls to my PayPal sandbox:

	API Username 	info_api1.clear-web-solutions.com
	API Password 	5SEPFYEA86AYTRCM
	API Signature 	A8S0d4LkbYVOgVEpUcflSVfoQCoZAU9owhiMYHywuZLnkceNcyCkNpkj

	To obtain this api info login to your paypal, goto My Account > Profile > Account Information::API Access > Request API credentials to create your own API username and password.
	follow a few instructions and it all would be created.
*/

	var $API_UserName = 'info_1337148238_biz_api1.clear-web-solutions.com';
	var $API_Password = '1337148298';
	var $API_Signature = 'A-U7Uq6v0quU4z5avXaWkqk9.bcVAWprVrNxcmmYuxlbXVun9VEEbCup';
	var $API_Endpoint = '';
	var $PayPal_URL = '';
	var $version = '88.0';
	var $Use_Proxy = FALSE;
	var $Proxy_Host = '127.0.0.1';
	var $Proxy_Port = '808';
	var $error = '';
	var $currencyCode  = "USD";//"EUR";
	var $storeurl = URL;
	var $return_url = "/store.api.php";
	var $cancel_url = "/store.api.php";

	function PayPal($username, $password, $signature){
		$this->API_UserName = $username?$username:$this->API_UserName;
		$this->API_Password = $password?$password:$this->API_Password;
		$this->API_Signature = $signature?$signature:$this->API_Signature;

		$this->return_url = $this->storeurl.$this->return_url;
		$this->cancel_url = $this->storeurl.$this->cancel_url;

		//checking for test account or live
		if($this->API_UserName=='info_1337148238_biz_api1.clear-web-solutions.com'){
			$this->API_Endpoint  = 'https://api-3t.sandbox.paypal.com/nvp'; 
			$this->PayPal_URL    = 'https://www.sandbox.paypal.com/webscr&cmd=_express-checkout&token=';
		}else{
			$this->API_Endpoint  = 'https://api-3t.paypal.com/nvp';
			$this->PayPal_URL    = 'https://www.paypal.com/webscr&cmd=_express-checkout&token=';
		}
	}

////////////////////////////////////////////////////////////////////////////////////////////
//2DO test and review this one
	function DoDirectPayment($cart){
		 //NVP method for doing DirectPayment
		// Get required parameters for the request 
		$cc_type = urlencode($cart->billing_details['cc_type']);
		$cc_number = urlencode($cart->billing_details['cc_number']);
		$cc_expm = urlencode($cart->billing_details['cc_expm']);
		$cc_expm = str_pad($cc_expm, 2, '0', STR_PAD_LEFT);// Month must be padded with leading zero
		$cc_expy = urlencode($cart->billing_details['cc_expy']);
		$cc_cvv = urlencode($cart->billing_details['cc_cvv']);
		$cc_fname = urlencode($cart->billing_details['cc_fname']);
		$cc_lname = urlencode($cart->billing_details['cc_lname']);

		//Get billing address info
		$address1 = urlencode($cart->billing_details['address1']);
		$address2 = urlencode($cart->billing_details['address2']);
		$city = urlencode($cart->billing_details['city']);
		$state = urlencode($cart->billing_details['state']);if($state==''){$state='CA';}
		$zip = urlencode($cart->billing_details['zip']);
		if(!$zip){$zip='12345';}
		$country = urlencode($cart->billing_details['country']);


		//Construct the request string that will be sent to PayPal. The variable $nvpstr contains all the variables and is a name value pair string with & as a delimiter
		$nvpstr = "&PAYMENTACTION=Sale&AMT=".$cart->getTotal()."&CURRENCYCODE=".$this->currencyCode."&IPADDRESS=".$_SERVER['REMOTE_ADDR']."&CREDITCARDTYPE=".$cc_type."&ACCT=".$cc_number."&EXPDATE=". $cc_expm.$cc_expy."&CVV2=".$cc_cvv."&FIRSTNAME=".$cc_fname."&LASTNAME=".$cc_lname."&STREET=".$address1."&CITY=".$city."&STATE=".$state."&ZIP=".$zip."&COUNTRYCODE=".$country;
//echo $nvpstr;exit;
		//Make the API call to PayPal, using API signature. The API response is stored in an associative array called $resArray
		$resArray = $this->hash_call("DoDirectPayment",$nvpstr);

		//Display the API response back to the browser. If the response from PayPal was a success, display the response parameters. If the response was an error, display the errors.
		$ack = strtoupper($resArray["ACK"]);
		if($ack=="SUCCESS")  {
			$this->transactionid = $resArray["TRANSACTIONID"];
			return true;
		}else{
			if($this->error){
				//curl error already set by hash_call
			}else{
				//PayPal API error
				$count=0;
				while (isset($resArray["L_SHORTMESSAGE".$count])) {
					$errorCode    = $resArray["L_ERRORCODE".$count]."<br/>";
					$shortMessage = $resArray["L_SHORTMESSAGE".$count]."<br/>";
					$longMessage  .= $resArray["L_LONGMESSAGE".$count]."<br/>"; 
					$count=$count+1; 
				}
				$this->error = "Error #".$errorCode." ".$longMessage;
//				$this->error = $longMessage;
			}
			return false;
		}
	 }

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function hash_call($methodName,$nvpStr){
		/*	hash_call: Function to perform the API call to PayPal using API signature
			@methodName is name of API method.
			@nvpStr is nvp string.
			returns an associtive array containing the response from the server.
		*/
	
		//setting the curl parameters
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$this->API_Endpoint);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);

		//turning off the server and peer verification(TrustManager Concept).
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POST, 1);

		//if Use_Proxy set to TRUE, then only proxy will be enabled.
		//Set proxy name to PROXY_HOST and port number to PROXY_PORT in constants.php 
		if($this->Use_Proxy)
		curl_setopt ($ch, CURLOPT_PROXY, $this->Proxy_Host.":".$this->Proxy_Port); 

		//NVPRequest for submitting to server
		$nvpreq="METHOD=".urlencode($methodName)."&VERSION=".urlencode($this->version)."&PWD=".urlencode($this->API_Password)."&USER=".urlencode($this->API_UserName)."&SIGNATURE=".urlencode($this->API_Signature).$nvpStr;
//echo $nvpreq."<br/>".$this->API_Endpoint."<br/>";
		//setting the nvpreq as POST FIELD to curl
		curl_setopt($ch,CURLOPT_POSTFIELDS,$nvpreq);

		//getting response from server
		$response = curl_exec($ch);

		//converting NVPResponse to an Associative Array
		$nvpResArray = $this->deformatNVP($response);

		//$_SESSION['nvpReqArray'] = $nvpReqArray;

		if (curl_errno($ch)) {
			// return curl errors
			$this->error = "Error #".curl_errno($ch)." <br/>".curl_error($ch);
		} else {
			//closing the curl
			curl_close($ch);
		}
		return $nvpResArray;
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function deformatNVP($nvpstr){
		/*	This function will take NVPString and convert it to an Associative Array and it will decode the response.
			It is usefull to search for a particular key and displaying arrays.
			@nvpstr is NVPString.
			@nvpArray is Associative Array.
		*/

		$intial=0;
		$nvpArray = array();

		while(strlen($nvpstr)){
			//postion of Key
			$keypos= strpos($nvpstr,'=');
			//position of value
			$valuepos = strpos($nvpstr,'&') ? strpos($nvpstr,'&'): strlen($nvpstr);
			//getting the Key and Value values and storing in a Associative Array
			$keyval=substr($nvpstr,$intial,$keypos);
			$valval=substr($nvpstr,$keypos+1,$valuepos-$keypos-1);
			//decoding the respose
			$nvpArray[urldecode($keyval)] =urldecode( $valval);
			$nvpstr=substr($nvpstr,$valuepos+1,strlen($nvpstr));
		}
		return $nvpArray;
	}

////////////////////////////////////////////////////////////////////////////////////////////

	function SetExpressCheckout($cart){

		//Construct the request string that will be sent to PayPal. The variable $nvpstr contains all the variables and is a name value pair string with & as a delimiter
		$nvpstr = "&PAYMENTREQUEST_0_AMT=".$cart->getTotal()."&PAYMENTREQUEST_0_CURRENCYCODE=".$this->currencyCode."&PAYMENTREQUEST_0_PAYMENTACTION=Sale&RETURNURL=".urlencode($this->return_url)."&CANCELURL=".urlencode($this->cancel_url);

		//Make the API call to PayPal, using API signature. The API response is stored in an associative array called $resArray
		$resArray = $this->hash_call("SetExpressCheckout",$nvpstr);

		//Display the API response back to the browser. If the response from PayPal was a success, display the response parameters. If the response was an error, display the errors.
		$ack = strtoupper($resArray["ACK"]);
		if($ack=="SUCCESS"){
			$this->token = $resArray["TOKEN"];
			header("Location: ".$this->PayPal_URL.$this->token);
		}else{
			$this->getError($resArray);
			return false;
		}
	 }

////////////////////////////////////////////////////////////////////////////////////////////

	function GetExpressCheckoutDetails($token){
		$nvpstr = "&TOKEN=".$token;

		//Make the API call to PayPal, using API signature. The API response is stored in an associative array called $resArray
		$resArray = $this->hash_call("GetExpressCheckoutDetails",$nvpstr);

		//Display the API response back to the browser. If the response from PayPal was a success, display the response parameters. If the response was an error, display the errors.
		$ack = strtoupper($resArray["ACK"]);
		if($ack=="SUCCESS")  {
			//get details and return them as array
			return $resArray;
		}else{
			$this->getError($resArray);
			return false;
		}
	}

////////////////////////////////////////////////////////////////////////////////////////////

	function DoExpressCheckoutPayment($token, $payerID, $cart){
		$nvpstr = "&TOKEN=".$token."&PAYERID=".$payerID."&PAYMENTREQUEST_0_AMT=".$cart->getTotal()."&PAYMENTREQUEST_0_CURRENCYCODE=".$this->currencyCode;

		//Make the API call to PayPal, using API signature. The API response is stored in an associative array called $resArray
		$resArray = $this->hash_call("DoExpressCheckoutPayment",$nvpstr);

		//Display the API response back to the browser. If the response from PayPal was a success, display the response parameters. If the response was an error, display the errors.
		$ack = strtoupper($resArray["ACK"]);
		if($ack=="SUCCESS")  {
			return true;
		}else{
			$this->getError($resArray);
			return false;
		}
	}

////////////////////////////////////////////////////////////////////////////////////////////

	function getError($resArray){
		if($this->error){
			//curl error already set by hash_call
		}else{
			//PayPal API error
			$count=0;
			while (isset($resArray["L_SHORTMESSAGE".$count])) {
				$errorCode    = $resArray["L_ERRORCODE".$count]."<br/>";
				$shortMessage = $resArray["L_SHORTMESSAGE".$count]."<br/>";
				$longMessage  .= $resArray["L_LONGMESSAGE".$count]."<br/>"; 
				$count=$count+1; 
			}
			$this->error = "Error #".$errorCode." ".$longMessage;
		}
	}

}
?>