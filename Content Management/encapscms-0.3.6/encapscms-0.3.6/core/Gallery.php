<?
class Gallery{
	function Gallery($config=array(),$_htpf=array(),$user=''){
		$this->htpf = $_htpf;
		$this->html = array();
		$this->html['path'] = $config['path'];
		$this->html['page'] = (isset($this->postget["page"]))?$this->postget["page"]:'';
		$this->html['config'] = $config;

		$this->postget = $_POST?$_POST:$_GET;
		$this->html['postget'] = $this->postget;
		$this->postget['action'] = isset($this->postget["action"])?$this->postget['action']:'';

		//$this->db = new DB_sql($config["mysql_host"],$config["mysql_user"],$config["mysql_pass"],$config["mysql_db"],$config["debug"]);
		$this->db = new DB_sql($config["db_host"],$config["db_user"],$config["db_pass"],$config["db_name"],$config["db_type"],$config["debug"]);		
		$this->table = "gallery";
		$this->cats = new Category($config,$user);
		$this->html['cats'] = $this->cats->getCats();
		$this->html['cat'] = $this->cats->getCat();
		//echo '$this->html[\'cat\']:';var_dump($this->html['cat']);
		
		if($config['demo'] == 0)
		switch ($this->postget["action"]){
			case "gallery_add":$this->_gallery_add();break;
			case "gallery_update":$this->_gallery_update();break;
			case "gallery_update_custom":$this->gallery_update_custom();break;
		}
	}
	
	function show($admin=''){
			switch ($this->postget["action"]){
				case "show_custom":$this->show_custom($admin);break;
				case "gallery_update_custom":$this->show_custom($admin);break;
				default:$this->_show($admin);
			}
	}
	
	function _show($admin){
		$html = $this->html;

		$pager = new Pager($this->html['config']['pager_items_per_page'],$this->getCount());
		$html['pager'] = $pager->html;
		$html["items"] = $this->_get_items($pager->offset,$pager->rows,$admin);
		//var_dump($html["items"]);
		include($html['path']."gallery.html");			
	}
	
	function _gallery_add(){
		echo $filename_normal = $this->htpf['img_file_normal']['name'];
		if($filename_normal  != ""){
			echo $filename_normal = strtotime("now")."_".$filename_normal;
		if(!copy($this->htpf['img_file_normal']['tmp_name'],"../rwx_gallery/".$filename_normal))
			echo  '<br><font color="red">Error with image uploading</font>';
		else 
			echo  '<br><font color="green">Image uploaded successfull</font>';
		}
		
		$position = $this->postget["img_position"];
		$cat = $this->postget["cat"];
		$visible = $this->postget["img_visible"];
		$title = $this->postget["title"];
		$comment = $this->postget["comment"];
		
		$query="INSERT INTO $this->table (filename_normal,title,comment,position,cat,visible)VALUES('$filename_normal','$title','$comment',$position,$cat,'on');";
		$this->db->exec($query);		
	}
	
	function _get_items($offset=0,$limit=0,$admin){
		//echo "<hr>\$offset:$offset,\$limit:$limit";
		if(!isset($offset))
			$offset = 0;
		if(!isset($limit))
			$limit = 0;

		$query = "SELECT * FROM $this->table WHERE cat=".$this->html['cat']['id'];
		if($admin=='') $query .= " AND visible='on' ";
		$query .= " ORDER BY position ";
		if($admin=='') {
			if($this->html['config']['db_type'] == "mysql")
				$query .= " LIMIT $offset,$limit;";
			if($this->html['config']['db_type'] == "pgsql")
				$query .= " LIMIT $limit OFFSET $offset;";
		}
		$result = $this->db->select($query);
		for($i=0;$i<sizeof($result);$i++){
			$result[$i]['title'] = stripslashes($result[$i]['title']);
			$result[$i]['comment'] = stripslashes(Misc::_nl2br($result[$i]['comment']));
		}
		return $result;
	}
	
	function getCount(){
		$query = "SELECT * FROM $this->table  WHERE visible='on' AND cat=".$this->html['cat']['id'].";";
		return sizeof($this->db->select($query));
	}
	
 	function _gallery_update(){
 		foreach ($this->postget as $key=>$value){
 			if(strstr($key,"title_"))$title  = $value;
 			if(strstr($key,"comment_"))$comment  = Misc::_nl2br($value);
 			if(strstr($key,"position_"))$position  = $value;
 			if(strstr($key,"cat_"))$cat  = $value;
 			if(strstr($key,"visible_"))$visible  = $value;
 			if(strstr($key,"delete_"))$delete  = $value;

 			if(strstr($key,"id")){
 				$id = $value;
 				//$this->_update_position($id,$position,$title,$comment,$visible,$delete);
		 		$query = "UPDATE $this->table SET title='$title',comment='$comment',position=$position,cat=$cat,visible='$visible' WHERE id=$id";
		 		$this->db->exec($query);
		 		if($delete == "on")$this->_delete($id); 				
 				
 				$id = "";
 				$title='';
 				$comment='';
 				$position="";
 				$cat='';
	 			$visible = "";			
 				$delete="";
	 			

 			}
 		}
 	}
 		
 	function _delete($id){
 		//echo "DELETING $id";
 		$query = "SELECT * FROM $this->table WHERE id = $id;";
 		$result = $this->db->select($query);
 		if(sizeof($result)>0){
		if($result[0]["filename_normal"] != "") 			
 			unlink("../rwx_gallery/".$result[0]["filename_normal"]);
 		}
 		$query = "DELETE FROM $this->table WHERE id = $id;";
 		$result = $this->db->exec($query);
 				
 	}
 	
 	function show_custom($admin){
 		$html = $this->html;
 		$id = isset($this->postget["id"])?$this->postget["id"]:$this->get_max_id();
		$html["item"] = $this->get_custom($id);
		$html["id"] = $this->_get_id_next($id,$admin);
 		include($html['path']."gallery_custom.html");
 	}
 	
 	function get_max_id(){
 		$result = $this->db->select("SELECT MAX(id) FROM $this->table");
 		var_dump($result);
 		return $result[0][0];
 	}
 	
 	function get_custom($id){
 		if(! $id>0)return;
 		$query = "SELECT * FROM $this->table WHERE id=$id ";
 		$result = $this->db->select($query);
		return $result[0]; 		
 	}

 	function _get_id_next($_id,$admin){
 		$id["prev"] = "";
 		$id["next"] = "";
 		$id["curr"] = $_id;

 		$sql = "SELECT id FROM $this->table WHERE cat=".$this->html['cat']['id'];
 		if($admin=='')$sql .= " AND visible='on' ";
 		$sql .= " ORDER BY position";
 		
 		$result = $this->db->select($sql);
		//var_dump($result);
 		
 		for($i=0;$i<sizeof($result);$i++)
 			if($result[$i]["id"]==$_id){
 				if($i>0)$id["prev"] = $result[$i-1]["id"];
 				if($i<sizeof($result)-1)$id["next"] = $result[$i+1]["id"];
 			}
 		return $id;
 	}	
 	
 	function gallery_update_custom(){
 		//var_dump($this->postget);
			$filename_new = $this->htpf['img_file']['name'];
		if($filename_new != ""){
			$filename_new = strtotime("now")."_".$filename_new;

		if(!copy($this->htpf['img_file']['tmp_name'],"../rwx_gallery/".$filename_new))
			echo  '<br><font color="red">Image upload failed</font>';
		else 
			echo  '<br><font color="green">Image uploaded succesfull</font>';
		
		$query = "SELECT * FROM $this->table WHERE id=".$this->postget["id"].";";
		$result=$this->db->select($query);
		if( $result[0]["filename_normal"]!=""){
			echo  '<br><font color="">Old image removed</font>';			
			unlink("../rwx_gallery/".$result[0]["filename_normal"]);
			}

		$query = "UPDATE $this->table SET filename_normal='$filename_new',title='".$this->postget["title"]."',comment='".$this->postget["comment"]."' WHERE id=".$result[0]["id"].";";
		$this->db->exec($query);
				}	 		
//		$position = $this->postget["img_position"];
//		$visible = $this->postget["img_visible"];
		$title = $this->postget["title"];
		$title_en = $this->postget["title_en"];
		$comment = $this->postget["comment"];
		$comment_en = $this->postget["comment_en"];
		$query = "UPDATE $this->table SET title='$title',comment='$comment' WHERE id=".$this->postget["id"].";";
		$this->db->exec($query);
 		
 	}
 	
 	function check_updates(){
 		$dir = '../rwx_gallery/';
 		$count = 0;
 		echo "<h3>Scanning [$dir] folder for new images...</h3>";
		$folder_content=opendir($dir);
		while($item=readdir($folder_content))
			if($item != "." && $item != ".."){
				//addIfNotPresent($item);
				$result = $this->db->select("SELECT * FROM $this->table WHERE filename_normal='$item';");
 				if(sizeof($result)==0){
 					//get MAX(position)
			 		$result = $this->db->select("SELECT MAX(position) FROM $this->table");
			 		$position = $result[0][0] + 1;
 					$this->db->exec("INSERT INTO $this->table (filename_normal,position,cat) VALUES('$item',$position,".$this->html['cat']['id'].");");
 					echo "<br>new image file [$item] added to the category [".$this->html['cat']['title']."]";
 					$count++;
 				}
			}
		echo "<h3>...scan complete, $count items added to the database</h3>";
 	}
	
}
?>