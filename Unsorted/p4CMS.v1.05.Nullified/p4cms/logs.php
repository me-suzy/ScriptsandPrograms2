<?
 include("include/include.inc.php");
 $modnav="logs";
 if (!isset($HTTP_SESSION_VARS[u_loggedin]) or !($HTTP_SESSION_VARS[u_loggedin]=='yes')) {
  SessionError();
  exit;
 }
 
 if ($HTTP_SESSION_VARS[u_gid] != 1) {
 	StyleSheet();
	$msg = "<center>Sie besitzen nicht die Rechte, diese Aktion auszuführen.</center>";
	MsgBox($msg);
	exit;
 }
 
 if ($_REQUEST['action']=="del") {
 	if (isset($_REQUEST['monat'])) {
 		$biszeit = mktime(0, 0, 0, $_REQUEST['monat'], $_REQUEST['tag'], $_REQUEST['jahr']);
		$sql =& new MySQLq();
		$sql->Query("DELETE FROM " . $sql_prefix . "logs WHERE datum < $biszeit");
		$sql->Close();
		unset($_REQUEST['monat']);		
 	}
 }
?>
<html>
<head>
<? StyleSheet(); ?>
<link rel="stylesheet" href="style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251"></head>
<link rel="stylesheet" href="include/dynCalendar.css" type="text/css" media="screen">
<script src="include/kalender.js" type="text/javascript" language="javascript"></script>
<body bgcolor="" background="/p4cms/gfx/main/bgbody.gif">

              <table width=100%  border="1" cellpadding="4" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">
 <tr bgcolor="#EAEBEE">
   <td height="17" colspan="3"><b>Log-Browser</b></td>
 </tr>
 <tr bgcolor="#FAFAFB">
   <td height="17" colspan="3">
     <table width="100%" border="0" cellpadding="0" cellspacing="0">
       <tr>
         <td align="right">
           <form style="display:inline;" action="logs.php?d4sess=<?=$sessid;?>" method="post">
             Zeige Logs vom:
               <select name="tag" class="inputfield">
                 <?
                for ($i=1;$i<=31;$i++) {
                	if (strlen($i) < 2) {
                		$w = "0" . $i;
                	} else {
                		$w = $i;
                	}
                	if (date("d") == $i) {
                		$sel = " selected";
                	} else {
                		$sel = "";
                	}
                	?>
                 <option value="<?=$w;?>"<?=$sel;?>>
                 <?=$w;?>
                 </option>
                 <?
                }
     			?>
               </select>
               <select name="monat" class="inputfield">
                 <?
                for ($i=1;$i<=12;$i++) {
                	if (strlen($i) < 2) {
                		$w = "0" . $i;
                	} else {
                		$w = $i;
                	}
                	if (date("m") == $i) {
                		$sel = " selected";
                	} else {
                		$sel = "";
                	}
                	?>
                 <option value="<?=$w;?>"<?=$sel;?>>
                 <?=$w;?>
                 </option>
                 <?
                }
     			?>
               </select>
               <select name="jahr" class="inputfield">
                 <?
     			
if(!isset($_REQUEST['jahr']))
	{
	$_REQUEST['jahr']=date("Y");
	} 

for ($jahrz=2003; $jahrz<=date("Y"); $jahrz++)
	{
	if($_REQUEST['jahr']==$jahrz)
		{
		echo "<option value=".$jahrz." selected>".$jahrz."</option>";} else {
		echo "<option value=".$jahrz.">".$jahrz."</option>"; }
	}
     			
                if (!isset($_REQUEST['monat'])) {
                	$_REQUEST['tag'] = date("d");
                	$_REQUEST['monat'] = date("m");
                	$_REQUEST['jahr'] = date("Y");
                }

                $abzeit = mktime(0, 0, 0, $_REQUEST['monat'], $_REQUEST['tag'], $_REQUEST['jahr']);
				$biszeit = mktime(23, 59, 59, $_REQUEST['monat'], $_REQUEST['tag'], $_REQUEST['jahr']);
     			?>
               </select>
        /
        <?
				if(!isset($_REQUEST['anz'])){$_REQUEST['anz']="20";} 
				$limitpage = $_REQUEST['anz'];
				?>
        <select name="anz" id="anz">
          <option value="20" <? if($_REQUEST['anz']==20)echo"selected";?>>20 pro Seite</option>
          <option value="40" <? if($_REQUEST['anz']==40)echo"selected";?>>40 pro Seite</option>
          <option value="60" <? if($_REQUEST['anz']==60)echo"selected";?>>60 pro Seite</option>
          <option value="80" <? if($_REQUEST['anz']==80)echo"selected";?>>80 pro Seite</option>
          <option value="100" <? if($_REQUEST['anz']==100)echo"selected";?>>100 pro Seite</option>
          <option value="150" <? if($_REQUEST['anz']==150)echo"selected";?>>150 pro Seite</option>
        </select>
        <input type="submit" class="button" value="anzeigen">
           </form>
         </td>
       </tr>
     </table>
     <?
				  $wf = "#eeeeee";
                $sql =& new MySQLq();
                $sql->Query("SELECT * FROM " . $sql_prefix . "logs WHERE datum > $abzeit AND datum < $biszeit ORDER BY datum DESC");
                $number = $sql->Numrows();
				$wieviele = $number;
				$sql->Close();
				
				if($_REQUEST['start']==""){
				$start=0;}
				  
				if($_REQUEST['start']!=""){
				$start=$_REQUEST['start'];}
				$chk_start=$start;
				
				$sql =& new MySQLq();
                $sql->Query("SELECT * FROM " . $sql_prefix . "logs WHERE datum > $abzeit AND datum < $biszeit ORDER BY datum DESC LIMIT $start,$_REQUEST[anz]");
				$number = $sql->Numrows();
				?>
     <table width="100%">
       <tr>
         <td> Angezeigt werden Logs vom <b>
           <?=$_REQUEST['tag'].'.'.$_REQUEST['monat'].'.'.$_REQUEST['jahr'];?>
         </b></td>
         <td align="right">
           <? if($wieviele > $limitpage){$nav="news";include("nextfor.php");}  ?>
         </td>
       </tr>
     </table>
   </td>
   </tr>
 <tr>
                <td height="17" class="boxheader">&nbsp;Zeit</td>
                <td height="17" class="boxheader">&nbsp;Typ</td>
                <td height="17" class="boxheader">&nbsp;Eintrag</td>
                </tr>
                <?
                
				
				while ($row = $sql->FetchRow()) {
                	if ($wf == "#ffffff") {
                		$wf = "#F4F5F7";
                	} else {
                		$wf = "#ffffff";
                	}
                	?>
                	<tr>
                	<td height="17" bgcolor="<?=$wf;?>">&nbsp;<?=date("H:i:s", $row->datum);?></td>
                	<td height="17" bgcolor="<?=$wf;?>">&nbsp;<?
                	if ($row->typ=="system") {
                		echo "System";
                	} else {
                		echo "Benutzer";
                	} ?></td>
                    <td height="17" bgcolor="<?=$wf;?>">&nbsp;<?=stripslashes($row->zeile);?></td>
                	</tr>
                	<?
                }
                $sql->Close();
                ?>
                </table>
                <br>
				
				
				<br>
                <center>
                Alle Logs vor dem
                  <form action="logs.php?d4sess=<?=$sessid;?>&action=del" method="post" style="display:inline;">         <select name="tag">
                <?
                for ($i=1;$i<=31;$i++) {
                	if (strlen($i) < 2) {
                		$w = "0" . $i;
                	} else {
                		$w = $i;
                	}
                	if (date("d") == $i) {
                		$sel = " selected";
                	} else {
                		$sel = "";
                	}
                	?>
                	<option value="<?=$w;?>"<?=$sel;?>><?=$w;?></option>
                	<?
                }
     			?>
     			</select> 
                    <select name="monat">
                <?
                for ($i=1;$i<=12;$i++) {
                	if (strlen($i) < 2) {
                		$w = "0" . $i;
                	} else {
                		$w = $i;
                	}
                	if (date("m") == $i) {
                		$sel = " selected";
                	} else {
                		$sel = "";
                	}
                	?>
                	<option value="<?=$w;?>"<?=$sel;?>><?=$w;?></option>
                	<?
                }
     			?>
     			</select> 
     			<select name="jahr">
     			<?
     			/*
				$jahr = date("Y");
     			for ($i=1; $i<=10; $i++) {
     				$abswert = $jahr + (-10 + $i);
     				if ($jahr == $abswert) {
     					$sel = " selected";
     				} else {
     					$sel = "";
     				}
     				?>
     				<option value="<?=$abswert;?>"<?=$sel;?>><?=$abswert;?></option>
     				<?
     			}
				*/
if(!isset($_REQUEST['jahr']))
	{
	$_REQUEST['jahr']=date("Y");
	} 

for ($jahrz=2003; $jahrz<=date("Y"); $jahrz++)
	{
	if($_REQUEST['jahr']==$jahrz)
		{
		echo "<option value=".$jahrz." selected>".$jahrz."</option>";} else {
		echo "<option value=".$jahrz.">".$jahrz."</option>"; }
	}
       			?>
     			</select> 
     			 
     			<input type="submit" class="button" value="löschen">
                </form>
                </center>
              <br>   