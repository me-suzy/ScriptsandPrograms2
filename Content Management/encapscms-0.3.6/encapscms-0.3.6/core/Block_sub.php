<?
class Block_sub{
	var $htpf=array();
	var $table = "";
	
	function Block_sub($config=array(),$_htpf=array(),$_block_id,$_postget=array()){
		$this->htpf = $_htpf;
//		$this->htmlpath = "html/";
		$this->postget = $_postget;
		
		$this->db = new DB_sql($config["mysql_host"],$config["mysql_user"],$config["mysql_pass"],$config["mysql_db"],$config["debug"]);
		
		if(isset($this->postget["lang"]))
			$this->lang = $this->postget["lang"];
			else
			$this->lang = "";
		$this->table = "agro_page_sub_block_sub".$this->lang;
		if(isset($this->postget["action"])){
			switch ($this->postget["action"]){
				case "block_sub_add":$this->_block_sub_add($_block_id);break;
				case "block_sub_update":$this->_block_sub_update($_block_id);break;
			}
		}
		
	}

	function _block_sub_add($_block_id){
		$filename_new = $this->htpf['img_file']['name'];
		if($filename_new != ""){
			$filename_new = strtotime("now")."_".$filename_new;
		if(!copy($this->htpf['img_file']['tmp_name'],"../rwx/".$filename_new))
			echo  '<br><font color="red">Íå ìîãó çàëèòü íà ñåðâåð ðèñóíîê </font>';
		else 
			echo  '<br><font color="green">Óñïåøíî çàëèò íà ñåðâåð ðèñóíîê </font>';
		}

		$filename_popup = $this->htpf['img_file_popup']['name'];
		if($filename_popup != ""){
			$filename_popup = strtotime("now")."_".$filename_popup;
		if(!copy($this->htpf['img_file_popup']['tmp_name'],"../rwx/".$filename_popup))
			echo  '<br><font color="red">Íå ìîãó çàëèòü íà ñåðâåð ðèñóíîê </font>';
		else 
			echo  '<br><font color="green">Óñïåøíî çàëèò íà ñåðâåð ðèñóíîê </font>';
		}

		$title = addslashes($this->postget["new_text_title"]);
		$text = addslashes(Misc::_parse_hrefs(Misc::_nl2br($this->postget["new_text"])));
		$text_detail = addslashes(Misc::_nl2br($this->postget["new_text_detail"]));
		$block_position = $this->postget["new_position"];
		$img = $filename_new;
		$img_popup = $filename_popup;
		$image_position = $this->postget["new_image_position"];
		$img_visible = $this->postget["img_visible"];
		$block_id = $_block_id;
		$visible = $this->postget["new_visible"];
		$query="INSERT INTO $this->table (id,title,text,text_detail,block_position,img,img_popup,img_position,img_visible,block_id,visible) VALUES(0,'$title','$text','$text_detail',$block_position,'$img','$img_popup','$image_position','$img_visible',$block_id,'$visible');";
		$this->db->exec($query);
				
	}
	
	function get_subtitle($_block_id){
		$query = "SELECT * FROM $this->table WHERE block_id=$_block_id ORDER BY block_position";
		$result = $this->db->select($query);	
		//var_dump($result[0]["title"]);
		return $result[0]["title"];
	}
	
	function get_blocks_sub($_block_id,$_user=""){
		$query = "SELECT * FROM $this->table WHERE block_id=$_block_id ORDER BY block_position";
		$result = $this->db->select($query);
		for ($i=0;$i<sizeof($result);$i++){
			$result[$i]['title'] = stripslashes($result[$i]['title']);
			$result[$i]['text'] = stripslashes($result[$i]['text']);
			$result[$i]['text_detail'] = stripslashes($result[$i]['text_detail']);

			if(
			   !isset($result[$i]["img_popup"]) 
				//&& trim($result[$i]["img_popup"])==""
				|| strlen($result[$i]["img_popup"])==0
				//&& sizeof($result[$i]["img_popup"])==0
				){
				continue;
				}
			
			//echo "<hr><hr>";var_dump($result[$i]["img_popup"]);
			
			if($_user=="admin")$prefix="../";else $prefix="./";
			$size = @getimagesize($prefix."rwx/".$result[$i]["img_popup"]);
			$result[$i]["img_popup_w"] = $size[0];
			$result[$i]["img_popup_h"] = $size[1];
		}
		return $result;
	}

	function get_block($_id){
		$query = "SELECT * FROM $this->table WHERE id=$_id ";
		$result = $this->db->select($query);
		return $result[0];
	}
		
	function _block_sub_update($_block_id){
		if(!isset($this->postget["block_visible"]))$this->postget["block_visible"]="off";
		if(!isset($this->postget["img_visible"]))$this->postget["img_visible"]="off";

		$query = "UPDATE $this->table SET text='".addslashes(Misc::_nl2br($this->postget["block_text"]))."',text_detail='".addslashes(Misc::_nl2br($this->postget["block_text_detail"]))."',title='".addslashes($this->postget["block_title"])."',text_detail='".addslashes(Misc::_nl2br($this->postget["block_text_detail"]))."',block_position=".$this->postget["block_position"].",visible='".$this->postget["block_visible"]."',img_position='".$this->postget["block_image_position"]."',img_visible='".$this->postget["img_visible"]."'";
		
		$filename_new = $this->htpf['img_file']['name'];
		if($filename_new != ""){
			$filename_new = strtotime("now")."_".$filename_new;
			$query.=",img='$filename_new'";

			if(!copy($this->htpf['img_file']['tmp_name'],"../rwx/".$filename_new))
				echo  '<br><font color="red">Íå ìîãó çàëèòü íà ñåðâåð ðèñóíîê </font>';
			else 
				echo  '<br><font color="green">Óñïåøíî çàëèò íà ñåðâåð ðèñóíîê </font>';
				
			$this->delete_img_block_sub($this->postget["block_id"]);				
		}		
		
		$filename_new_popup = $this->htpf['img_file_popup']['name'];
		if($filename_new_popup != ""){
			$filename_new_popup = strtotime("now")."_".$filename_new_popup;
			$query.=",img_popup='$filename_new_popup'";

			if(!copy($this->htpf['img_file_popup']['tmp_name'],"../rwx/".$filename_new_popup))
				echo  '<br><font color="red">Íå ìîãó çàëèòü íà ñåðâåð ðèñóíîê </font>';
			else 
				echo  '<br><font color="green">Óñïåøíî çàëèò íà ñåðâåð ðèñóíîê </font>';
				
			$this->delete_img_popup_block_sub($this->postget["block_id"]);
		}		
		
		if(!isset($this->postget["delete_block"])){
			$query.=" WHERE id=".$this->postget["block_id"].";";
			$this->db->exec($query);
		
		}else
		{
			//var_dump($this->postget);
			$this->delete_by($this->postget["block_id"]);
			//$query = "DELETE FROM $this->table WHERE id = ".$this->postget["block_id"].";";
			//$this->db->exec($query);
			//$this->delete_img_block_sub($this->postget["block_id"]);
			//$this->delete_img_popup_block_sub($this->postget["block_id"]);
		}
		
		if(isset($this->postget["img_delete"]))
			$this->delete_img_block_sub($this->postget["block_id"]);
		if(isset($this->postget["img_popup_delete"]))
			$this->delete_img_popup_block_sub($this->postget["block_id"]);

	}
	
	function delete_img_block_sub($id){
		echo "delete image from block";
		$query = "SELECT img FROM $this->table WHERE id = $id;";
		$result=$this->db->select($query);
		@unlink("../rwx/".$result[0][0]);
		$query = "UPDATE $this->table SET img='' WHERE id = $id";
		$this->db->exec($query);
	}

	function delete_img_popup_block_sub($id){
		echo "delete popup image from block";
		$query = "SELECT img_popup FROM $this->table WHERE id = $id;";
		$result=$this->db->select($query);
		@unlink("../rwx/".$result[0][0]);
		$query = "UPDATE $this->table SET img_popup='' WHERE id = $id;";
		$this->db->exec($query);
	}	
	
	function delete_by($id){
		$query="SELECT * FROM $this->table WHERE id=$id";
		$result = $this->db->select($query);
		//var_dump($result);
		//echo"<hr>";var_dump($result);echo"<hr>";
		foreach($result as $item){
			//echo"<hr>";var_dump($item);echo"<hr>";
			$this->delete_img_block_sub($item["id"]);
			$this->delete_img_popup_block_sub($item["id"]);
			$query="DELETE FROM $this->table WHERE id =".$item["id"].";";
			$this->db->exec($query);
		
		}

	}
	
	function delete_by_parent($id){
//		echo"<hr>";var_dump($id);echo"<hr>";
		$query="SELECT * FROM $this->table WHERE block_id=$id";
		$result = $this->db->select($query);
		
		foreach($result as $item){
			$this->delete_by($item["id"]);
		}
		
	}
}

?>