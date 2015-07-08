<?
class SettingsBase{

	function SettingsBase(){
		global $db, $_SESSION, $_REQUEST;

			//email where to send messages
			$q = $db->query("select email, meta_title, meta_description, meta_keywords from ".DBPREFIX."admin where id='1'");
			$row = $q->next_row();
			$this->email = $row->email;
			$this->meta_title = $row->meta_title;
			$this->meta_description = fix_quotes($row->meta_description);
			$this->meta_keywords = fix_quotes($row->meta_keywords);

			//ability for admin to be able to add subpages 
			//if set to false would not display the Add New menu item under Pages menu in admin
			$this->add_pages = true;

			//site URL
			$this->url = URL;
	
			//setting default language for admin
			if(strstr($_SERVER['REQUEST_URI'], "/admin/")){
				$this->language = 'en';
			}else{
			//picked language
				if($_REQUEST['lang']){
					$this->language = $_REQUEST['lang'];
					$_SESSION['lang'] = $this->language;
				}elseif(isset($_SESSION['lang'])){
					$this->language = $_SESSION['lang'];
				}else{
					$this->language = 'en';
				}
			}

			//all languages, warning Language class is not yet initialized, so we can't use it, also it uses settings instance
			$q = $db->query("select * from ".DBPREFIX."languages where locked!='1' order by ordr asc");
			foreach($q->result() as $language){
				$this->languages[] = array('id'=>$language->id, 'title'=>$language->language);
			}

			//rows per page
			$this->rpp = 10;
			//admin rows per page
			$this->arpp = 10;


	}

}
?>