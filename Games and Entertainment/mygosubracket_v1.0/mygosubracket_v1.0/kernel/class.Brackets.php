<?php
# ^brackets.class.php^ by cagrET

class brackets
{
 var $db; //db class reference
 var $prefix; //table prefix
 var $show; //show x brackets per page
 var $is_admin = false;
 
 function brackets($db, $prefix, $show, $is_admin)
 {
   #-- initial variables
   $this->db     = &$db;
   $this->prefix = $prefix;
   $this->show   = $show;
   $this->is_admin = $is_admin;

   $mod    = @$_GET['mod'];
   $bid    = (int) @$_GET['bid'];
   $tbl_de = array();
   
   #-- switch
   if($mod == 'edit' && $bid)       $this->brackets_edit($bid);
   elseif($mod == 'show' && $bid)   $this->brackets_show($bid);
   elseif($mod == 'delete' && $bid) $this->brackets_delete($bid);
   elseif($mod == 'add')            $this->brackets_add();
   elseif($mod == 'account') include 'templates/brackets/account.tpl';

   if(!$mod) $this->brackets_list();

 } //end func
 
 function brackets_list()
 {
   $page = (int) @$_GET['page'];

   #-- counting pages
   if($page < 1) $page = 1;

   $this->db->query("select COUNT(*) from ".$this->prefix."brackets");
   $this->db->next_record();
   $pages = ceil($this->db->Record['COUNT(*)'] / $this->show);
   $num = $this->db->Record['COUNT(*)'] + $this->show - $page * $this->show;

   if($page > $pages) $page = $pages;
   $start = $page * $this->show - $this->show;
   if($start < 0) $start = 0;

   #-- query for brackets
   $this->db->query("select bid,name from ".$this->prefix."brackets order by bid desc limit $start,{$this->show}");
   $tbl_de = array();
   while($this->db->next_record()) {
     $tbl_de[] = $this->db->Record;
   }
   include 'templates/brackets/brackets.tpl';
   
 } //end func

 function brackets_edit($bid)
 {
   # testing if bracket exists
   $this->db->query("select * from ".$this->prefix."brackets where bid='$bid'");
   if($this->db->num_rows() != 1) $this->brackets_header();
   $this->db->next_record();
   
   #-- testing auth   
   if(!$this->is_admin)
   {
     $this->brackets_header(); return 0;
   }

   $type  = $this->db->Record['type'];
   $name  = $this->db->Record['name'];
   $top1  = $this->db->Record['top1'];
   $top2  = $this->db->Record['top2'];
   $top3  = $this->db->Record['top3'];
   $DATA  = (string) $this->db->Record['DATA'];

   # initializing $this->de
   $this->init($type);

   # edycja bracketu i przekierowanie
   if(@$_POST['submit_brackets_edit'])
   {
     $post_name = trim(@$_POST['name']);
     $top1 = trim(@$_POST['top1']);
     $top2 = trim(@$_POST['top2']);
     $top3 = trim(@$_POST['top3']);

     if(isset($_POST['de'])) $this->data2de($_POST['de']);
     $this->de = addslashes(serialize($this->de));

     $this->db->query("update ".$this->prefix."brackets set name='$post_name', top1='$top1', top2='$top2', top3='$top3', DATA='{$this->de}' where bid='$bid'");

     # if edit ok - header
     Header("Location: ".basename($_SERVER['PHP_SELF'])."?mod=show&bid=$bid");
     exit; return 1;
   }

   # this->de
   $this->data2de(unserialize($DATA));
   $this->de2input();

   switch($type) {
     case 'se4':   include 'templates/brackets/edit_se4.tpl';   break;
     case 'se8':   include 'templates/brackets/edit_se8.tpl';   break;
     case 'se16':  include 'templates/brackets/edit_se16.tpl';  break;
     case 'de4':   include 'templates/brackets/edit_de4.tpl';   break;
     case 'de8':   include 'templates/brackets/edit_de8.tpl';   break;
     case 'de16':  include 'templates/brackets/edit_de16.tpl';  break;
   }
 } //end func

 function data2de(&$data)
 /* after init of de - now comparing de with data - if isset data[x][y] then de[x][y] = data[x][y] */
 {
   foreach($this->de as $key=>$value) {
     foreach($value as $key2=>$value2) {
       if(isset($data[$key][$key2]) && $data[$key][$key2]) {
         $this->de[$key][$key2] = $data[$key][$key2];
       }
     }
   }
 } //end funnc

 function de2input()
 /* after de_init() and data2de() - changing this->de to inputs with keys and values of this->de */
 {
   $text = @file('templates/brackets/input.tpl');
   $text = implode('', $text);
   
   foreach($this->de as $key=>$value) {
     foreach($value as $key2=>$value2) {
       $this->de[$key][$key2] = sprintf($text, $key,$key2,htmlspecialchars($value2));
     }
   }
 } //end func

 function brackets_show($bid)
 {
   # if bracket exists
   $this->db->query("select * from ".$this->prefix."brackets where bid='$bid'");
   if($this->db->num_rows() != 1) $this->brackets_header();
   $this->db->next_record();

   $type = $this->db->Record['type'];
   $name = $this->db->Record['name'];
   $top1 = $this->db->Record['top1'];
   $top2 = $this->db->Record['top2'];
   $top3 = $this->db->Record['top3'];
   $DATA = (string) $this->db->Record['DATA'];
   
   # this->de
   $this->init($type);
   $this->data2de(unserialize($DATA));

   switch($type) {
     case 'se4':   include 'templates/brackets/show_se4.tpl';   break;
     case 'se8':   include 'templates/brackets/show_se8.tpl';   break;
     case 'se16':  include 'templates/brackets/show_se16.tpl';  break;
     case 'de4':   include 'templates/brackets/show_de4.tpl';   break;
     case 'de8':   include 'templates/brackets/show_de8.tpl';   break;
     case 'de16':  include 'templates/brackets/show_de16.tpl';  break;
   }

 } //end func

 function brackets_add()
 {
   $submit = @$_POST['submit_brackets_add'];

   if($submit)
   {
     if(!$this->is_admin) { $this->brackets_header(); return 0;}

     $error = 0;

     $post_type = htmlspecialchars(@$_POST['type']);
     $post_name = htmlspecialchars(@$_POST['name']);

     if(!$post_type || !$post_name) $error = 1;

     # if double
     $this->db->query("select * from ".$this->prefix."brackets where type='$post_type' and name='$post_name'");
     if($this->db->num_rows() != 0) $error = 1;

     # add bracket
     if(!$error) {
       $this->db->query("insert into ".$this->prefix."brackets set type='$post_type', name='$post_name'");
       $this->brackets_header();
     }

     if($error) $submit = '';
   }

   if(!$submit) include 'templates/brackets/brackets_add.tpl';
   
 } //end func

 function brackets_delete($bid)
 {
   #-- testing auth
   $this->db->query("select * from ".$this->prefix."brackets where bid='$bid'");
   if($this->db->num_rows() != 1) { $this->brackets_header(); return 0; }
   $this->db->next_record();

   if(!$this->is_admin)
   {
     $this->brackets_header(); return 0;
   }

   $this->db->query("delete from ".$this->prefix."brackets where bid='$bid'");
   $this->brackets_header();
   
 } //end func

 function brackets_header()
 {
   Header("Location: ".basename($_SERVER['PHP_SELF']));
   exit;
 } //end func

 function php2js()
 {
   $tmp = '';
   foreach($this->de as $key=>$value) {
     foreach($value as $key2=>$value2) {
       $tmp .= 'de['. $key .']['. $key2 .'] = "'. $value2 .'"'."\r\n";
     }
   }
   return $tmp;
 } //end func

 function init($type)
 {
   switch($type) {
     case 'se4':   $this->se4_init();   break;
     case 'se8':   $this->se8_init();   break;
     case 'se16':  $this->se16_init();  break;
     case 'de4':   $this->de4_init();   break;
     case 'de8':   $this->de8_init();   break;
     case 'de16':  $this->de16_init();  break;
   }
 } //end func

 function se4_init()
 {
   $this->de = array();
   $this->de[1] = $this->value2key(array(1,2,3,4));
   $this->de[2] = $this->value2key(array(2,3));
   $this->de[3] = $this->value2key(array(2));
 } //end func
 
 function se8_init()
 {
   $this->de = array();
   $this->de[1] = $this->value2key(array(1,2,3,4,5,6,7,8));
   $this->de[2] = $this->value2key(array(2,3,6,7));
   $this->de[3] = $this->value2key(array(4,5));
   $this->de[4] = $this->value2key(array(4));
 } //end func
 
 function se16_init()
 {
   $this->de = array();
   $this->de[1] = $this->value2key(array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16));
   $this->de[2] = $this->value2key(array(2,3,6,7,10,11,14,15));
   $this->de[3] = $this->value2key(array(4,5,12,13));
   $this->de[4] = $this->value2key(array(8,9));
   $this->de[5] = $this->value2key(array(8));
 } //end func

 function de4_init()
 {
   $this->de = array();
   $this->de[1] = $this->value2key(array(1,2,3,4,6,7));
   $this->de[2] = $this->value2key(array(2,3,6,7));
   $this->de[3] = $this->value2key(array(4,5));
   $this->de[4] = $this->value2key(array(4,5));
   $this->de[5] = $this->value2key(array(4));
 } //end func

 function de8_init()
 {
   $this->de = array();
   $this->de[1] = $this->value2key(array(1,2,3,4,5,6,7,8,9,10,11,12));
   $this->de[2] = $this->value2key(array(2,3,6,7,9,10,11,12));
   $this->de[3] = $this->value2key(array(10,11));
   $this->de[4] = $this->value2key(array(4,5,10,11));
   $this->de[5] = $this->value2key(array(7,8));
   $this->de[6] = $this->value2key(array(7,8));
   $this->de[7] = $this->value2key(array(7));
 } //end func

 function de16_init()
 {
   $this->de = array();
   $this->de[1] = $this->value2key(array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24));
   $this->de[2] = $this->value2key(array(2,3,6,7,10,11,14,15,17,18,19,20,21,22,23,24));
   $this->de[3] = $this->value2key(array(18,19,22,23));
   $this->de[4] = $this->value2key(array(4,5,12,13,18,19,22,23));
   $this->de[5] = $this->value2key(array(20,21));
   $this->de[6] = $this->value2key(array(8,9,20,21));
   $this->de[7] = $this->value2key(array(14,15));
   $this->de[8] = $this->value2key(array(14,15));
   $this->de[9] = $this->value2key(array(14));
 } //end func
 
 function value2key($array)
 {
   $tmp = array();

   foreach($array as $value) {
     $tmp[$value] = '';
   }
   
   return $tmp;

 } //end func

} //end class

?>