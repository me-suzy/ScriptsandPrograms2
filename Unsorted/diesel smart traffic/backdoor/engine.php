<html>
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
  <title>Exchange System</title>
  <link rel="stylesheet" href="../style.css">
 </head>
<body bgcolor=FFFFFF class=bodytext link=0 vlink=0 alink=0 text=0>
<?
  require("../conf/sys.conf");
  require("../lib/ban.lib");
  require("bots/errbot");
  require("bots/mcbot");
  require("bots/genbot");
  require("bots/grbot");


  $src=$spec;
  if(!$spec || !file_exists($src)) _fatal("Fatal error!","Module <b>[$spec]</b> not found on the server.");
  if(!isset($sc)) _fatal("Access denied!","Please, authorize before access this page!");

  $db=con_srv();
  require($src);


  if($pm=="inew"){
   if(!check_interface(1)) echo "<center><b><font size=4>Wrong input data!</font></b></center><br>\n";
   else insert_interface();
   $pm="";
  }
  $pi_flag=0;
  if($pm=="pinfo"){
   $pi_flag=1;
   $pm="";
  }


  if($pm=="ied"){
   if(!check_interface(0)) echo "<center><b><font size=4>Wrong input data!</font></b></center><br>\n";
   else{
    _query("DELETE FROM $spec WHERE id=$id");
    insert_interface_ex($id);
   }
   $pm="";
  }

  if($pm=="update"){
   if(!check_interface(0)) echo "<center><b><font size=4>Wrong input data!</font></b></center><br>\n";
   else{
    insert_interface_ex($id);
   }
   $pm="";
  }

  if($pm=="Nied"){
   if(!check_interface(0)) echo "<center><b><font size=4>Wrong input data!</font></b></center><br>\n";
   else{
    insert_interface_ex($id);
   }
   $pm="";
  }


  if($pm=="drop"){
   if($id==0 || $id)_query("DELETE FROM $spec WHERE id=$id");
   $pm="";
  }
  
  if ($pm=="rdrop"){
   rdrop($id);
   $pm="";
  }


  if($pm=="gdall"){
   _droper();
   $pm="";
  }

  if($pm=="new") get_ninterface($spec,1);
  if($pm=="edit") get_ninterface($spec,0);


  if($pm=="dall"){
   _query("DELETE FROM $spec WHERE id=id");
   $pm="";
  }

  if($pm=="appr"){
   get_apprinterface($spec,0);
   $pm="";
  }

  if($pm=="disable"){
   get_opsinterface(0);
   $pm="";
  }
  if($pm=="enable"){
   get_opsinterface(1);
   $pm="";
  }
 
  if($pm == "drop_item"){
     get_dropinterface();
     $pm = ""; 
  }

  if($pm == "freeze_item"){
     get_frinterface(1);
     $pm = ""; 
  }

  if($pm == "unfreeze_item"){
     get_frinterface(0);
     $pm = ""; 
  }
  if($pm == "change_policy"){
     call_policy_service($action,$id);
     $pm = ""; 
  }

  if($pm == "stat"){
     get_statinterface();
     $pm = ""; 
  }

  if(!$pm){
    if(!isset($st)) $st="id";
    $r=_query("SELECT * FROM $spec ORDER BY $st");
    get_interface($r,$spec);
  }
  ?>
<hr noshade align=left size=1>
<a href=index.php><font size=-1>[ < logout ]</font></a> <a href=back.php><font size=-1>[ << main menu ]</font></a> 
<?  if($pm!="") echo "<a href=engine.php?spec=$spec><font size=-1>[ <<< up to level ]</font></a>"; 
 dc_srv($db);
?>
</body>
</html>