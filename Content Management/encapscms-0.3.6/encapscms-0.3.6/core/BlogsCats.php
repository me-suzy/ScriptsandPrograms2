<?php
class Cats
{
	function Cats($config=array())
	{
		$this->htmlpath = $config["path"];
		$this->postget = $_POST?$_POST:$_GET;
		//$this->db = new DB_sql($config["mysql_host"],$config["mysql_user"],$config["mysql_pass"],$config["mysql_db"]);
		$this->db = new DB_sql($config["db_host"],$config["db_user"],$config["db_pass"],$config["db_name"],$config["db_type"],$config["debug"]);		
		
		$this->table = "blogs_cats";
		if(!isset($this->postget['action']))
			$this->postget['action'] = '';
		$this->fields = array(
			'id'=>0,
			'title'=>'',
			'pos'=>0,
			'vis'=>'',
			'anch'=>0
		);
		$this->setBy($this->postget);
		$this->html['page'] = $this->_get_page();
		$this->page_sub = $this->html['page']['id'];
	}
	
	function think()
	{
		if($this->html['config']['demo']==0)
			switch($this->postget['action'])
			{
				case 'cats_update':$this->cats_update();break;
			}
	}
	
	function show($file='cats_manage.html')
	{
		$this->html['page'] = $this->_get_page();
		$this->html['cats'] = $this->get_items();
		$html = $this->html;
		include($this->htmlpath.$file);
	}
	
	function get_items($anch=0){
		$query = "SELECT * FROM $this->table WHERE anch=$anch ORDER BY pos ;";
		//WHERE anch=$anch 
		$result = $this->db->select($query);
		for($i=0;$i<sizeof($result);$i++){
			$result[$i]['childs'] = $this->get_items($result[$i]['id']);
		}
		return $result;
	}
	
	function _get_page(){
		$result = $this->db->select("SELECT * FROM $this->table ORDER BY pos");
		//var_dump($result);
		if(isset($this->postget["page_id"])){
			foreach ($result as $item)
				if($item['id'] == $this->postget["page_id"])
					return $item;
		}else
			return $result[0];
	}	

	function cats_update()
	{
		//echo 'function cats_update()';
		foreach ($this->postget as $key=>$value)
		{
			if(strstr($key,'pos_'))
				$this->setBy(array('pos'=>$value));		
			if(strstr($key,'title_'))
				$this->setBy(array('title'=>$value));		
			if(strstr($key,'vis_'))
				$this->setBy(array('vis'=>$value));		
			if(strstr($key,'anch_'))
				$this->setBy(array('anch'=>$value));		

			if(strstr($key,'id_'))
			{
				//var_dump($this->fields);
				if(!strstr($key,'id_new'))
				{
					$this->setBy(array('id'=>$value));		
					if($this->postget['del_'.$value] != 'on')
						$this->update_exec();
						else 
							$this->delete_exec();
				}else
					$this->insert_exec();
				$this->reset();
			}
		}
	}	
	
	function insert_exec()
	{
		//echo 'function insert_exec()';
		if(trim($this->fields['title']) != '')
		{
			echo '<hr>'.$query = "INSERT INTO $this->table (title,pos,vis,anch) VALUES ('".$this->fields['title']."',".$this->fields['pos'].",'".$this->fields['vis']."',".$this->fields['anch'].")";
			$this->db->exec($query);
		}
		else 
		{
			//echo '<hr>';
			//var_dump($this->fields['title']);
		}
	}

	function update_exec()
	{
		//echo 'function update_exec()';
		$query = "UPDATE $this->table SET 
			title='".$this->fields['title']."' ,
			pos=".$this->fields['pos'].",
			vis='".$this->fields['vis']."',
			anch=".$this->fields['anch']."
			WHERE id=".$this->fields['id']."";
		if($this->fields['id']!=$this->fields['anch'])
			$this->db->exec($query);
	}	
	
	function delete_exec()
	{
		$this->db->exec("DELETE FROM $this->table WHERE id=".$this->fields['id']."");
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