<?php
class db{
var $db;
var $from;
var $col;
  function db(){
$this->db = mysql_connect('localhost','YOUR_DB_USERNAME','YOUR_DB_PASSWORD')or die("db server not found");
mysql_select_db('YOUR_DB_NAME', $this->db)or die("Unable to find db table on ".$this->db);
    return $this->db;
  }
  function set($table,$field){
$this->from=$table;
 IF($field > 1){
$this->col=$field;
    return true;
 }
 ELSE
$this->col='*';
   return false;
  }
  function stash($str){
html_entities(add_slashes($str));
    return $str;
  }
  function dash($str){
strip_slashes($str);
    return $str;
  }
  function ezq($where,$int){
$sql=mysql_query("SELECT $this->col FROM $this->from WHERE $where");
 IF($int != 12){
$que=mysql_fetch_assoc($sql);
    return $que;
 }
 ELSE
    return $sql;
  }
  function inq($into,$arr){
$rts=implode(",",$arr);
$sql="INSERT INTO $into VALUES $rts";
$ins=mysql_query($sql);
    return $ins;
  }
  function upq($where,$arr){
$rts=implode(",",$arr);
$sql="UPDATE $this->from SET ($rts) WHERE $where";
$upd=mysql_query($sql);
    return $upd;
  }
   }
?>