<?
  include "http://hooplar.com/adminoptions2.html";
?>

<html>
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
  <title>Admin :: Rate</title>
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


	function info_there($p){
		$f = @fopen($p,"r");
		if(!$f) return "";
		$ch = "";
		while(!feof($f)){
		  $ch .= fgetc($f);
		}
		return $ch;
	}



	if($action == "change" && $v1 > 0 && $v2 > 0){
	 	$f = @fopen("../".$DRATE_P,"w");
		if(!$f) echo "Error: falied to save information!<br>See your sys.conf file!<br><br>";
		else{
		  fwrite($f, "$v1/$v2", strlen($v1) + strlen($v2) + 1);
		  fclose($f);
		}
	}

	$drate = info_there("../$DRATE_P");
	$drate = split("/",$drate);	
	$v1 = $drate[0];
	$v2 = $drate[1];


	if(!$v1 || !$v2){
		$v1 = 3;
		$v2 = 2;
	}

	if(!file_exists("../".$DRATE_P))
		echo "<b>There is no default value!</b><br>";
?>

            <table border=0 cellpadding="1" cellspacing="1" width="468" height="125">
              <form action=def_rate.php mehtod=post>
		<tr><td colspan=2>Default exchange rate:</td></tr>
                <tr bgcolor="#EBEBEB" align="center"> 
                  <td colspan=2>For 1 preview generated, receiver loses 1 credit and provider gets 
                    <? echo sprintf("%0.2f",$v2/$v1); ?> credits.
                  </td>
                </tr>
                <tr bgcolor="#EBEBEB" align="center"> 
                  <td>Other site/Provider's site: </td>
                  <td> 
                    <input value="<? echo $v1; ?>" name=v1 size=4>
                    <input value="<? echo $v2; ?>" name=v2 size=4>
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
