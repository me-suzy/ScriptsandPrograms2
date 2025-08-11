<?
class Block{
	var $htpf=array();
	var $table = "";
	
	function Block($config=array(),$_htpf=array(),$_page_id,$_postget=array()){
		$this->htpf = $_htpf;
		$this->htmlpath = $config["path"];
		$this->postget = $_postget;
		$this->config=$config;
		$this->page_id = $_page_id;
		//$this->db = new DB_sql($config["mysql_host"],$config["mysql_user"],$config["mysql_pass"],$config["mysql_db"],$config["debug"]);
		$this->db = new DB_sql($config["db_host"],$config["db_user"],$config["db_pass"],$config["db_name"],$config["db_type"],$config["debug"]);
		$this->html['config'] = $config;
		if(isset($this->postget["lang"]))
			$this->lang = $this->postget["lang"];
			else
			$this->lang = "";
		$this->table = "blogs_page_block".$this->lang;
		if(isset($this->postget["action"])){
			switch ($this->postget["action"]){
				case "block_add":$this->_block_add();break;
				case "block_update":$this->_block_update();break;
			}
		}
//		echo '<hr>$_page_sub:'.$_page_sub;
//		echo '<hr>$this->postget["block_id"])'.$this->postget["block_id"];
		
	}

	function _block_add(){
		$filename_new = $this->htpf['img_file']['name'];
		if($filename_new != ""){
			$filename_new = strtotime("now")."_".$filename_new;
		if(!copy($this->htpf['img_file']['tmp_name'],"../rwx_blogs/".$filename_new))
			echo  '<br><font color="red">Íå ìîãó çàëèòü íà ñåðâåð ðèñóíîê </font>';
		else 
			echo  '<br><font color="green">Óñïåøíî çàëèò íà ñåðâåð ðèñóíîê </font>';
		}

		$filename_popup = $this->htpf['img_file_popup']['name'];
		if($filename_popup != ""){
			$filename_popup = strtotime("now")."_".$filename_popup;
		if(!copy($this->htpf['img_file_popup']['tmp_name'],"../rwx_blogs/".$filename_popup))
			echo  '<br><font color="red">Íå ìîãó çàëèòü íà ñåðâåð ðèñóíîê </font>';
		else 
			echo  '<br><font color="green">Óñïåøíî çàëèò íà ñåðâåð ðèñóíîê </font>';
		}
		
		$title = addslashes($this->postget["new_text_title"]);
		$text = addslashes(/*Misc::_parse_hrefs(Misc::_nl2br(*/$this->postget["new_text"]);
		$text_detail = addslashes(/*Misc::_parse_hrefs(Misc::_nl2br(*/$this->postget["new_text_detail"]);
		$block_position = $this->postget["new_position"];
		$img = $filename_new;
		$img_popup = $filename_popup;
		$image_position = $this->postget["new_image_position"];
		$img_visible = $this->postget["img_visible"];
		$page_sub_id = $this->page_id;
		$visible = $this->postget["new_visible"];
		$query="INSERT INTO $this->table (id,title,text,text_detail,block_position,img,img_popup,img_position,img_visible,page_sub_id,visible) VALUES(0,'$title','$text','$text_detail',$block_position,'$img','$img_popup','$image_position','$img_visible',$page_sub_id,'$visible');";
		$this->db->exec($query);
				
	}
	
	function get_blocks($_user=""){
		$query = "SELECT * FROM $this->table".$lang." WHERE page_sub_id=".$this->page_id." ORDER BY block_position";
		$result = $this->db->select($query);
		for ($i=0;$i<sizeof($result);$i++){
			$result[$i]['title'] = stripslashes($result[$i]['title']);
			$result[$i]['text'] = stripslashes($result[$i]['text']);
			$result[$i]['text_detail'] = stripslashes($result[$i]['text_detail']);
			//var_dump($result[$i]);

			//$this->block_sub = new Block_sub($this->config,array(),$result[$i]["id"],array("lang"=>$this->lang));
			//$result[$i]["block_sub"]=$this->block_sub->get_blocks_sub($result[$i]["id"]);
			
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
		
/*		$result['title'] = stripslashes($result['title']);
		$result['text'] = stripslashes($result['text']);
		$result['text_detail'] = stripslashes($result['text_detail']);
		*///var_dump($result);
		
		return $result;
	}

	function get_block($_id){
		$query = "SELECT * FROM $this->table WHERE id=$_id ";
		$result = $this->db->select($query);
		$result = $result[0];
		//var_dump($result);
		$result['title'] = stripslashes($result['title']);
		$result['text'] = stripslashes($result['text']);
		$result['text_detail'] = stripslashes($result['text_detail']);
		return $result;
	}
		
	function _block_update(){
		if(!isset($this->postget["block_visible"]))$this->postget["block_visible"]="off";
		if(!isset($this->postget["img_visible"]))$this->postget["img_visible"]="off";

		$query = "UPDATE $this->table SET text='".addslashes(/*Misc::_nl2br(*/$this->postget["text"])."',text_detail='".addslashes(/*Misc::_nl2br(*/$this->postget["text_detail"])."',title='".addslashes($this->postget["title"])."',block_position=".$this->postget["block_position"].",visible='".$this->postget["block_visible"]."',img_position='".$this->postget["img_position"]."',img_visible='".$this->postget["img_visible"]."'";
		
		$filename_new = $this->htpf['img_file']['name'];
		if($filename_new != ""){
			$filename_new = strtotime("now")."_".$filename_new;
			$query.=",img='$filename_new'";

			if(!copy($this->htpf['img_file']['tmp_name'],"../rwx_blogs/".$filename_new))
				echo  '<br><font color="red">Íå ìîãó çàëèòü íà ñåðâåð ðèñóíîê </font>';
			else 
				echo  '<br><font color="green">Óñïåøíî çàëèò íà ñåðâåð ðèñóíîê </font>';
				
			$this->delete_img_block($this->postget["block_id"]);
		}		
		
		$filename_new_popup = $this->htpf['img_file_popup']['name'];
		if($filename_new_popup != ""){
			$filename_new_popup = strtotime("now")."_".$filename_new_popup;
			$query.=",img_popup='$filename_new_popup'";

			if(!copy($this->htpf['img_file_popup']['tmp_name'],"../rwx_blogs/".$filename_new_popup))
				echo  '<br><font color="red">Íå ìîãó çàëèòü íà ñåðâåð ðèñóíîê </font>';
			else 
				echo  '<br><font color="green">Óñïåøíî çàëèò íà ñåðâåð ðèñóíîê </font>';
			
			$this->delete_img_popup_block($this->postget["block_id"]);
		}		
		
		if(!isset($this->postget["delete_block"])){
			$query.=" WHERE id=".$this->postget["block_id"].";";
			$this->db->exec($query);
		
		}else
		{
			$this->delete_img_block($this->postget["block_id"]);
			$this->delete_img_popup_block($this->postget["block_id"]);
			//$this->block_sub = new Block_sub($this->config,array(),$this->postget["block_id"],array("lang"=>$this->lang));
			//$this->block_sub->delete_by_parent($this->postget["block_id"]);
			$query = "DELETE FROM $this->table WHERE id = ".$this->postget["block_id"].";";
			$this->db->exec($query);

		}
		
		if(isset($this->postget["img_delete"])){
			$this->delete_img_block($this->postget["block_id"]);
		}
		if(isset($this->postget["img_popup_delete"])){
			$this->delete_img_popup_block($this->postget["block_id"]);
		}

	}
	
	function delete_img_block($id){
		//echo "Óäàëÿþ image èç áëîêà";
		$query = "SELECT img FROM $this->table WHERE id = $id;";
		$result=$this->db->select($query);
		//var_dump($result);
		@unlink("../rwx_blogs/".$result[0]['img']);
		$query = "UPDATE $this->table SET img='' WHERE id = $id";
		$this->db->exec($query);
		
	}
	function delete_img_popup_block($id){
		//echo "Óäàëÿþ popup_image èç áëîêà";
		$query = "SELECT img_popup FROM $this->table WHERE id = $id;";
		$result=$this->db->select($query);
		//var_dump($result[0][0]);
		@unlink("../rwx_blogs/".$result[0]['img_popup']);
		$query = "UPDATE $this->table SET img_popup='' WHERE id = $id;";
		$this->db->exec($query);
		
	}
}
?>