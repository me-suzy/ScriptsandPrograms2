<?php

require("./DbSql.inc.php");

Class NewsSQL extends DBSQL
{
   // the constructor
   function NewsSQL($DBName = "")
   {
      $this->DBSQL($DBName);
   }

   function getallcatalog($page,$record)
   {
      $start = $page*$record;
      $sql = "select * from catalog order by catalogid DESC LIMIT $start,$record";
      $result = $this->select($sql);
      return $result;
   }
   
   function getcatalognamebyid($catalogid)
   {      
      $sql = "select catalogname from catalog where catalogid='$catalogid'";
      $result = $this->select($sql);
      $parentname = $result[0]["catalogname"];
      return $parentname;
   }
   
   function getallcatalogname()
   {      
      $sql = "select catalogid,catalogname from catalog";
      $result = $this->select($sql);      
      return $result;
   }
   
   function getcatalogbyid($catalogid)
   {      
      $sql = "select * from catalog where catalogid='$catalogid'";
      $result = $this->select($sql);      
      return $result;
   }
   
   function addcatalog($catalogname,$description,$parentid)
   {      
      global $admin_catalogalreadyexist;
      $sql = "select * from catalog where catalogname='$catalogname' and parentid='$parentid'";
      $result = $this->select($sql); 
      if (!empty($result)){
      print "$admin_catalogalreadyexist";
      return;
      }     
      $sql = "insert into catalog (catalogname,description,parentid) values ('$catalogname','$description','$parentid')";      
      $results = $this->insert($sql);
      return $results;
   }
   
   function editcatalog($catalogname,$description,$parentid,$catid)
   {
      $sql = "update catalog set catalogname='$catalogname',description='$description',parentid='$parentid' where catalogid='$catid'";      
      $results = $this->update($sql);
      return $results;
   }
   
   function delcatalog($catid,$PicturePath)
   {
      $sql = "delete from catalog where catalogid=$catid";
      $result = $this->delete($sql);
      $sql = "select newsid from news where catalogid=$catid";
      $result = $this->select($sql);
      if (!empty($result)) {
      	while ( list($key,$val)=each($result) ) {
      		$newsid = stripslashes($val["newsid"]);
      		$this->delnews($newsid,$PicturePath);
      	}
      }
   } 
   
   function getallnews($page,$record)
   {
      $start = $page*$record;
      $sql = "select * from news order by newsid DESC LIMIT $start,$record";
      $result = $this->select($sql);
      return $result;
   }
   
   function getcatalognews($page,$record,$catid)
   {
      $start = $page*$record;
      $sql = "select * from news where catalogid='$catid' order by newsid DESC LIMIT $start,$record";
      $result = $this->select($sql);
      return $result;
   }  	   
   
   function addnews($catalogid,$title,$content,$viewnum,$rating,$ratenum,$source,$sourceurl,$isdisplay)
   {        
      $adddate = date("y-m-d");
      $sql = "insert into news (catalogid,title,content,viewnum,adddate,rating,ratenum,source,sourceurl,isdisplay) values ('$catalogid','$title','$content','$viewnum','$adddate','$rating','$ratenum','$source','$sourceurl','$isdisplay')";      
      $results = $this->insert($sql);
      return $results;
   }
   
   function add_Picture($newsid,$userfile_name,$PicturePath)
   {
      $sql = "select picture from news where newsid='$newsid'";
      $result = $this->select($sql);
      $picture = $result[0]["picture"];
      if (!empty($picture)){
      $file = $PicturePath.$picture;
      unlink($file);
      }
      $sql = "UPDATE news SET picture=\"$userfile_name\" WHERE newsid='$newsid'";
      $result = $this->update($sql);
      return $result;
   }
   
   function delnews($newsid,$PicturePath)
   {
      $sql = "select picture from news where newsid='$newsid'";
      $result = $this->select($sql);
      $picture = $result[0]["picture"];      
      if (!empty($picture)){
      $file = $PicturePath.$picture;
      unlink($file);
      }      
      $sql = "DELETE FROM news where newsid='$newsid'";
      $result = $this->delete($sql);      
      return $result;      
   }
   
   function getnewsbyid($newsid)
   {      
      $sql = "select * from news where newsid='$newsid'";
      $result = $this->select($sql);      
      return $result;
   }
   
   function editnews($catalogid,$title,$content,$viewnum,$rating,$ratenum,$source,$sourceurl,$isdisplay,$newsid)
   {
      $sql = "update news set catalogid='$catalogid',title='$title',content='$content',viewnum='$viewnum',rating='$rating',ratenum='$ratenum',source='$source',sourceurl='$sourceurl',isdisplay='$isdisplay' where newsid='$newsid'";      
      $results = $this->update($sql);
      return $results;
   }
   
   function del_Picture($newsid,$PicturePath)
   {
      $sql = "select picture from news where newsid='$newsid'";
      $result = $this->select($sql);
      $picture = $result[0]["picture"];      
      if (!empty($picture)){
      $file = $PicturePath.$picture;
      unlink($file);
      }
      $sql = "UPDATE news SET picture=\"\" WHERE newsid='$newsid'";
      $result = $this->update($sql);
      return $result;
   }
}

?>