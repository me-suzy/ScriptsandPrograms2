<?php



require("./DbSql.inc.php");

Class NewsSQL extends DBSQL
{
   // the constructor
   function NewsSQL($DBName = "")
   {
      $this->DBSQL($DBName);
   }

   function getchildcatalog($catid)
   {
      $start = $page*$record;
      $sql = "select catalogid,catalogname from catalog where parentid='$catid' order by catalogid";
      $result = $this->select($sql);
      return $result;
   }
   
   function getnewsbycatid($page,$record,$catid)
   {
      $start = $page*$record;
      $sql = "select newsid,title from news where catalogid='$catid' and isdisplay=1 order by newsid DESC LIMIT $start,$record";
      $result = $this->select($sql);
      return $result;
   }
      
   function getlatestonhome($record)
   {      
      $sql = "select newsid,title from news where isdisplay=1 order by newsid DESC LIMIT 0,$record";
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
   
   function getnewsbykeyword($page,$record,$keyword)
   {
      $start = $page*$record;
      $sql = "select newsid,title from news where ((title like '%$keyword%') or (content like '%$keyword%')) and isdisplay=1 order by newsid DESC LIMIT $start,$record";
      $result = $this->select($sql);
      return $result;
   }
   
   function set_Rating($newsid,$Rating)
   {
      $sql = "select rating,ratenum from news where newsid=$newsid";
      $result = $this->select($sql);
      $OldR = $result[0]["rating"];
      $OldN = $result[0]["ratenum"];
      $NewN = $OldN+1;
      $NewR = ($OldR*$OldN+$Rating)/$NewN;
      
      $sql = "update news set rating=$NewR,ratenum=$NewN where newsid=$newsid";
      $result = $this->update($sql);
      return $result;
   }
   
   function getnewsbyid($newsid)
   {      
      $sql = "select * from news where newsid='$newsid'";
      $result = $this->select($sql);      
      return $result;
   }
   
   function addhit($viewnum,$newsid)
   {
      $sql = "update news set viewnum=$viewnum+1 where newsid=$newsid";
      $result = $this->update($sql);
      return $result;
   }
   
   function getname($newsid)
   {
      $sql = "select title from news where newsid=$newsid";
      $result = $this->select($sql);
      $title = $result[0]["title"];       
      return $title;
   }
   
}

?>