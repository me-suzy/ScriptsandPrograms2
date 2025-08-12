<?
  include "http://hooplar.com/adminoptions2.html";
?>

<html>
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
  <title>Default Banner/URL</title>
  <link rel="stylesheet" href="../style.css">
 </head>
<body bgcolor=FFFFFF class=bodytext link=0 vlink=0 alink=0 text=0>
<table width="100%" border="0" cellspacing="1" cellpadding="1" height="100%">
  <tr>
    <td align="center"> 
      <table width="468" border="0" cellspacing="1" cellpadding="1">
        <tr> 
          <td>
            <br>
            <?
  require("../conf/sys.conf");
  require("bots/errbot");
  require("bots/mcbot");
  require("bots/genbot");



  $src=$spec;
  if(!isset($sc)) _fatal("Access denied!","Please, authorize before access this page!");
?>
            <? 
	if($action == "change" && $burl != ""){
	 	$f = @fopen("../".$DBAN_P,"w");
		if(!$f) echo "Error: falied to save information!<br>See your sys.conf file!<br><br>";
		else{
		  fwrite($f, $burl, strlen($burl));
		  fclose($f);
		}
	}

	if($action == "change" && $durl != ""){
	 	$f = @fopen("../".$DURL_P,"w");
		if(!$f) echo "Error: falied to save information!<br>See your sys.conf file!<br><br>";
		else{
		  fwrite($f, $durl, strlen($durl));
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

	$defb = info_there("../".$DBAN_P);
	if(!file_exists("../".$DBAN_P) || $defb == ""){
		echo "<b>There is no default banner url !</b><br>";
	}

	$defu = info_there("../".$DURL_P);
	if(!file_exists("../".$DURL_P) || $defu == ""){
		echo "<b>There is no default url !</b><br>";
	}

	if ($defb) if(@fopen($defb,"r")) echo "Default banner<br><img src=$defb><br>"; else echo "Banner could not be loaded from '$defb' !";
?>
            <br><br>DEFAULT
 	<form action=dban.php mehtod=post>
              <table align=center border="0" cellspacing="1" cellpadding="1" height="40">
                <tr align="center" bgcolor="#EBEBEB"> 
                  <td>Banner url:</td>
                  <td> 
                    <input value="<? echo $defb ?>" name=burl size=80>
                  </td>
				  </tr>
                <tr align="center" bgcolor="#EBEBEB"> 
                  <td>Exchange url:</td>
                  <td> 
                    <input value="<? echo $defu ?>" name=durl size=80>
                  </td>
				  </tr>
				  <tr>
				  <td colspan=2 align=right> 
                    <input type=submit value="Process data &gt;&gt;" name="submit">
                  </td>
                </tr>
              </table>
              <input type=hidden name=action value=change>
            </form>
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
