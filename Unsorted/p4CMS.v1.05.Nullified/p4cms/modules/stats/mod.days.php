<? 
ob_start();
error_reporting(7);
 if (!isset($HTTP_SESSION_VARS[u_loggedin]) or !($HTTP_SESSION_VARS[u_loggedin]=='yes')) {
  SessionError();
  exit;
 }
 
 if ($HTTP_SESSION_VARS[u_gid] == 1) {


mysql_connect("$sql_server","$sql_user","$sql_passwort");
mysql_select_db("$sql_db");

?>
<link href="/p4cms/style/style.css" rel="stylesheet" type="text/css">
<body class="boxstandart">
<?
$query = "SELECT DISTINCT tag FROM " .$sql_prefix. "docstats WHERE monat='$_REQUEST[monat]' and jahr='$_REQUEST[jahr]' and tag >= '1' and tag <= '31' order by tag DESC";
$res = mysql_query($query);
if(!isset($_REQUEST['anzahl'])){$_REQUEST['anzahl']="10";}
?>
<table width="100%"  border="0" cellspacing="1" cellpadding="4"> 
  <tr> 
    <td> <table width="100%"  border="0" cellspacing="0" cellpadding="0"> 
        <tr> 
          <td><b> 
            <?=$_REQUEST['write'].$_REQUEST['jahr'];?> 
            </b></td> 
          <td> <div align="right"> 
              <form name="showa" method="post" action=""> 
                <select name="anzahl"> 
                  <option value="10" <? if($_REQUEST['anzahl']=="10")echo"selected"; ?>>10</option> 
                  <option value="20" <? if($_REQUEST['anzahl']=="20")echo"selected"; ?>>20</option> 
                  <option value="50" <? if($_REQUEST['anzahl']=="50")echo"selected"; ?>>50</option> 
                  <option value="75" <? if($_REQUEST['anzahl']=="75")echo"selected"; ?>>75</option> 
                  <option value="100" <? if($_REQUEST['anzahl']=="100")echo"selected"; ?>>100</option> 
                </select> 
                pro Tag
                <input name="Submit" type="submit" class="button" value="anzeigen"> 
              </form> 
            </div></td> 
        </tr> 
      </table></td> 
  </tr> 
  <? while($row=mysql_fetch_array($res)){ ?> 
  <tr bgcolor="#FFFFFF"> 
    <td><table width=100%  border="1" cellpadding="4" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">

<tr> 
          <td class="boxheader"> <b> 
            <?=$row['tag'];?> 
            . 
            <?=$_REQUEST['write'];?> 
            </b></td> 
          <td class="boxheader"><div align="center">Abrufe</div></td> 
        </tr> 
        <?
	
	//==========================================
	//  TAGE
	//==========================================
	 $queryall = "SELECT * FROM " .$sql_prefix. "docstats WHERE tag='".$row['tag']."' and jahr='$_REQUEST[jahr]' and monat='".$_REQUEST['monat']."' order by hits DESC limit 0,$_REQUEST[anzahl]";
	 $resall = mysql_query($queryall);
	 $numall = mysql_num_rows($resall);
	 $i=1;
	 while($row=mysql_fetch_array($resall)){
	 if($i%2){$col="#ffffff";}else{$col="#F4F5F7";}
	 ?> 
        <tr> 
          <td bgcolor="<?=$col;?>"><a target="_blank" href="<?=$row['ref'];?>"> 
            <?=$row['ref'];?> 
            </a></td> 
          <td width="10%" bgcolor="<?=$col;?>">
            <div align="center"> 
              <?=$row['hits'];?> 
          </div>
          </td> 
        </tr> 
        <?
	$i++;
	 }
	?> 
      </table></td> 
  </tr> 
  <? } ?> 
</table> 
<?
 } else {
	$msg = "<center>Diese Seite darf nur von Administratoren aufgerufen werden.</center>";
	MsgBox($msg);
 }
?> 
