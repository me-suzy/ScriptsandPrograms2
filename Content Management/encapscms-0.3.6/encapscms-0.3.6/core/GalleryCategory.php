<?
class Category{
	function Category($_config,$user=''){
		$this->postget= $_POST?$_POST:$_GET;
		$this->html['postget'] = $this->postget;
		$this->postget['action'] = isset($this->postget["action"])?$this->postget['action']:'';
		
		$this->db = new DB_sql($_config["db_host"],$_config["db_user"],$_config["db_pass"],$_config["db_name"],$_config["db_type"],$_config["debug"]);
		$this->fields = array(
			'id'=>0,
			'title'=>'asdf',
			'pos'=>0,
			'vis_anons'=>''
		);
		$this->setBy($this->postget);
		$this->table = 'encapsgallery_category';
		$this->html['errors'] = array();
		$this->user = $user;
		$this->html['config'] = $_config;
	}

	//------------------------------
	function think(){
		if($this->html['config']['demo']==0)
		switch ($this->postget["action"]){
			case "upd_cat_exec":$this->upd_cat_exec();break;
		}
	}	
	
	
	function show(){
		$html = $this->html;
		$html['cats'] = $this->getCats();
		include('html/gallery_cats.html');
	}

	function show_select(){
		$html = $this->html;
		$html['cats'] = $this->getCats();
		$path = 'html/gallery_cats_select.html';
		if($this->user == 'admin')
			$path = '../'.$path;
		include($path);
	}
	
	//------------------------------
	
	function getCats(){
		$result = array();
		$sql = "SELECT * FROM $this->table ";
		if($this->user =='')$sql .= " WHERE vis_anons='checked' ";
		$sql .= "ORDER BY pos ASC";
		//var_dump($sql);
		$result = $this->db->select($sql);
		return $result;		
	}
	
	function getCat(){
		$result = $this->getCats();
		$id = $result[0][0];
		
		if(isset($this->html['postget']['cat']) )
			$id = (int)$this->html['postget']['cat'];
		
		if(! ($id >=0) || !$id )
			$id = 0;
		
		$result = $this->db->select("SELECT * FROM $this->table WHERE id=$id");
		$this->setBy($result[0]);
		return $this->fields;
	}
	
	function getById($id){
		$result = $this->db->select("SELECT * FROM $this->table WHERE id=$id");
		return $result[0];
	}
	
	function upd_cat_exec(){
		//echo 'upd_cat_exec';
		foreach ($this->postget as $key=>$value){
			if(strstr($key,'pos_'))
				$this->setBy(array('pos'=>$value));		
			if(strstr($key,'title_'))
				$this->setBy(array('title'=>$value));		
			if(strstr($key,'vis_anons_'))
				$this->setBy(array('vis_anons'=>'checked'));		

			if(strstr($key,'id_')){
				if(!strstr($key,'id_new')){
					$this->setBy(array('id'=>$value));		
					if($this->postget['del_'.$value] != 'on')
						$this->update();
						else {
						$this->delete_exec();
						//$this->postget['action'] = 'delete';
						}
				}else
					$this->insert();
				$this->reset();
			}
		}
	}	

	function insert(){
		if(trim($this->fields['title']) != '')
			$this->db->exec("INSERT INTO $this->table (title,pos,vis_anons) VALUES ('".$this->fields['title']."',".$this->fields['pos'].",'".$this->fields['vis_anons']."')");
	}

	function update(){
		$this->db->exec("UPDATE $this->table SET 
			pos=".$this->fields['pos'].",
			title='".$this->fields['title']."' ,
			vis_anons='".$this->fields['vis_anons']."' 		
			WHERE id=".$this->fields['id']."");
	}	
	
	
	function delete($id){
		$html = $this->html;
		$html['item'] = $this->fields;
		include('html/rubrik_del.html');
	}	

	function delete_exec(){
		if($this->cat_empty($this->fields['id']))
			$this->db->exec("DELETE FROM $this->table WHERE id=".$this->fields['id']."");
			else echo "<br><font color=\"red\"><h2>Deleting skipped: catagory [".$this->fields['title']."] is not empty.</h2></font>";
	}	
	
	function cat_empty($id){
		return (sizeof($this->db->select("SELECT * FROM gallery WHERE cat = $id")) == 0)?true:false;
		//var_dump($result);
		/*foreach ($result as $item){
			unlink('../rwx/'.$item['filename_normal']);
			$this->db->exec("DELETE  FROM gallery WHERE id = ".$item['id']."");
		}
		return true;*/
	}
	
	function setBy($fields){
		foreach ($this->fields as $key=>$value){
			if(isset($fields[$key]))
				$this->fields[$key] = $fields[$key];
		}
	}	
	
	function reset(){
		foreach ($this->fields as $key=>$value){
			$this->fields[$key] = '';
		}
	}	
	
}
?>