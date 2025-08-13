<?
 if (!isset($HTTP_SESSION_VARS[u_loggedin]) or !($HTTP_SESSION_VARS[u_loggedin]=='yes')) {
  SessionError();
  exit;
 }

 if ($HTTP_SESSION_VARS[u_gid] == 1) {
 	?>
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>

     
                <?
                if ($_REQUEST['action']=="step2") {
                	if (!isset($_REQUEST['nid'])) {
                		$query  = "INSERT INTO " . $sql_prefix ."navis(titel,ebene1,ebene2,ebene1a,ebene2a,vor,nach) VALUES ";
                		$query .= "('$_REQUEST[titel]','$_REQUEST[ebene1]','$_REQUEST[ebene2]','$_REQUEST[ebene1a]','$_REQUEST[ebene2a]','$_REQUEST[vor]','$_REQUEST[nach]')";
                		$sql =& new MySQLq();
						$sql->Query($query);
						$nid = $sql->IId();
						$sql->Close();
						$_REQUEST['nid'] = $nid;
                	} else {
                		$query  = "UPDATE " . $sql_prefix . "navis SET  titel='$_REQUEST[titel]', ebene1='$_REQUEST[ebene1]', ebene1a='$_REQUEST[ebene1a]', ebene2='$_REQUEST[ebene2]', ebene2a='$_REQUEST[ebene2a]', vor='$_REQUEST[vor]', nach='$_REQUEST[nach]' WHERE id='$_REQUEST[nid]'";	
                		$nid = $_REQUEST['nid'];
                		$sql =& new MySQLq();
                		$sql->Query($query);
                		$sql->Close();
                	}
                	
					$_REQUEST['action'] = "navi";
                } else {
                	$nid = $_REQUEST['nid'];
                }
                
                if ($_REQUEST['action']=="add") {
                	if ($_REQUEST['parent']==0) {
                		$parent = $_REQUEST['nid'];
                		$ebene = 1;
                	} else {
                		$parent = $_REQUEST['parent'];
                		$ebene = 2;
                	}
                	
                	$query  = "INSERT INTO " . $sql_prefix . "navi_items(titel,parent,link,target,ebene) VALUES ";
                	$query .= "('$_REQUEST[titel]','$parent','$_REQUEST[url]','$_REQUEST[target]','$ebene')";
                	$sql =& new MySQLq();
                	$sql->Query($query);
                	$sql->Close();
                	
                	$_REQUEST['action'] = "navi";
                }
                
                if ($_REQUEST['action']=="change")  {
                	if (isset($_REQUEST['del'])) { 
                		$sql =& new MySQLq();
                		$sql->Query("DELETE FROM " . $sql_prefix . "navi_items WHERE id='$_REQUEST[parent]'");
                		$sql->Close();
                	} else {
                		$sql =& new MySQLq();
                		$sql->Query("UPDATE " . $sql_prefix . "navi_items SET subrang='$_REQUEST[subrang]', rang='$_REQUEST[rang]', link='$_REQUEST[url]', titel='$_REQUEST[titel]', target='$_REQUEST[target]' WHERE id='$_REQUEST[parent]'");
                		$sql->Close();
                	}
                	$_REQUEST['action'] = "navi";
                }	
                
                if ($_REQUEST['action']=="navi") {
                	?>
                	<table width=100%  border="1" cellpadding="4" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">


                	 <tr>
                	   <td colspan="5"><div align="right"><b><a href="javascript:void(0);" onClick="MM_openBrWindow('/p4cms/modules/navigation/hilfe/hilfe_edit.php','','scrollbars=yes,resizable=yes,width=820,height=500')"><img src="/p4cms/gfx/tree/question.gif" alt="" width="18" height="18" hspace="2" border="0" align="absmiddle">Hilfe</a></b></div></td>
               	      </tr>
                	 <tr bgcolor="#EAEBEE">
                	   <td width="1%"><b>Pos.</b></td>
                	  <td><b>Titel</b></td>
                	  <td><b>URL</b></td>
                	  <td><b>Ziel</b></td>
                	  <td>&nbsp;</td>
                	 </tr>
                	 <?
   						$sql =& new MySQLq();
   						$sql->Query("SELECT * FROM " . $sql_prefix . "navi_items WHERE ebene='1' AND parent='$nid' ORDER BY rang ASC");
   						while ($row = $sql->FetchRow()) {
							?>
                	 <form style="display:inline;" action="" method="post">
                	 <input type="hidden" name="nid" value="<?=$nid;?>">
                	 <input type="hidden" name="action" value="change">
                	 <input type="hidden" name="parent" value="<?=$row->id;?>">
                	 <tr bgcolor="#FAFAFB">
                	   <td>
               	       <input name="rang" type="text" id="rang" value="<?=$row->rang;?>" size="5"></td>
                	  <td>                	  
               	       <input type="text" name="titel" style="width:150px;" value="<?=htmlentities(stripslashes($row->titel));?>"></td>
                	  <td>
               	       <input type="text" name="url" style="width:280px;" value="<?=htmlentities(stripslashes($row->link));?>"></td>
                	  <td>
               	       <input type="text" name="target" style="width:70px;"value="<?=htmlentities(stripslashes($row->target));?>"></td>
                	  <td>
               	       <input type="submit" class="button" value=" Ändern "> <input type="submit" class="button" name="del" value=" Löschen "></td>
                	 </tr>
                	 </form>							
							<?
							$sql2 =& new MySQLq();
							$sql2->Query("SELECT * FROM " . $sql_prefix . "navi_items WHERE ebene='2' AND parent='$row->id' ORDER BY subrang ASC");
							while ($row2 = $sql2->FetchRow()) {
								?>
					 <form style="display:inline;" action="" method="post">
                	 <input type="hidden" name="nid" value="<?=$nid;?>">
                	 <input type="hidden" name="action" value="change">
                	 <input type="hidden" name="parent" value="<?=$row2->id;?>">
                	 <tr bgcolor="#FAFAFB">
                	   <td>
               	       <input name="subrang" type="text" id="subrang" value="<?=$row2->subrang;?>" size="5"></td>
                	   <td>
                	     <div align="right"><nobr> <img src="/p4cms/gfx/sub.gif" width="20" height="15">               	               
               	       <input  type="text" name="titel" style="width:120px;background-color:#F5F5F5" value="<?=htmlentities(stripslashes($row2->titel));?>">
              	     </nobr></div></td>
                	   <td>
               	       <input type="text" name="url" style="width:280px;background-color:#F5F5F5" value="<?=htmlentities(stripslashes($row2->link));?>"></td>
                	   <td>
               	       <input type="text" name="target" style="width:70px;background-color:#F5F5F5" value="<?=htmlentities(stripslashes($row2->target));?>"></td>
                	   <td>
               	       <input type="submit" class="button" value=" Ändern "> <input type="submit" class="button" name="del" value=" Löschen "></td>
                	 </tr>
                	 </form>
								<?
							}
							$sql2->Close();
							?>
                	 <form style="display:inline;" action="" method="post">
                	 <input type="hidden" name="nid" value="<?=$nid;?>">
                	 <input type="hidden" name="action" value="add">
                	 <input type="hidden" name="parent" value="<?=$row->id;?>">
                	 <tr bgcolor="#FAFAFB">
                	   <td>&nbsp;</td>
                	   <td>
                	     <div align="right"><nobr>               	       <img src="/p4cms/gfx/sub.gif" width="20" height="15">
               	         <input type="text" name="titel" style="width:120px;">
              	     </nobr></div></td>
                	   <td>
               	       <input type="text" name="url" style="width:280px;"></td>
                	   <td>
               	       <input type="text" name="target" style="width:70px;"></td>
                	   <td>
               	       <input type="submit" class="button" value=" Anlegen "></td>
                	 </tr>
                	
                	 </form>					
							<?
   						}
   						$sql->Close();       	 
                	 ?> 
                	 <form style="display:inline;" action="" method="post">
                	 <input type="hidden" name="nid" value="<?=$nid;?>">
                	 <input type="hidden" name="action" value="add">
                	 <input type="hidden" name="parent" value="0">
                	 <tr bgcolor="#FAFAFB">
                	   <td>&nbsp;</td>
                	  <td>
               	       <input type="text" name="titel" style="width:150px"></td>
                	  <td>
               	       <input type="text" name="url" style="width:280px;"></td>
                	  <td>
               	       <input type="text" name="target" style="width:70px;"></td>
                	  <td>
               	       <input type="submit" class="button" value=" Anlegen "></td>
                	 </tr>
                	 </form>
               	</table>
                	<?
                }
                
                if ($_REQUEST['action']=="" || !isset($_REQUEST['action']) || $_REQUEST['action']=="edit") {
                	?>
                	<form style="display:inline;" action="" name="navi" method="post">
                	<?
                	if (isset($_REQUEST['id'])) {
                		$sql =& new MySQLq();
                		$sql->Query("SELECT * FROM " . $sql_prefix . "navis WHERE id='$_REQUEST[id]'");
                		$row = $sql->FetchRow();
                		$sql->Close();
                		$ed_titel = htmlentities(stripslashes($row->titel));
                		$ed_ebene1 = htmlentities(stripslashes($row->ebene1));
                		$ed_ebene1a = htmlentities(stripslashes($row->ebene1a));
                		$ed_ebene2 = htmlentities(stripslashes($row->ebene2));
                		$ed_ebene2a = htmlentities(stripslashes($row->ebene2a));
                		$ed_vor = htmlentities(stripslashes($row->vor));
                		$ed_nach = htmlentities(stripslashes($row->nach));
                		?>
                		<input type="hidden" name="nid" value="<?=$row->id;?>">
                		<?
                	} else {
                		$ed_titel = "";
                		$ed_ebene1 = "";
                		$ed_ebene1a = "";
                		$ed_ebene2 = "";
                		$ed_ebene2a = "";
                		$ed_vor = "";
                		$ed_nach = "";
                	}
                	?>
                	<input type="hidden" name="action" value="step2">
					<table width=100%  border="1" cellpadding="4" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">

					<tr>
					<td width="150" bgcolor="#EAEBEE">Titel:</td>
					<td bgcolor="#FAFAFB">
					  <input type="text" name="titel" size="32" style="width:98%;" value="<?=$ed_titel;?>"></td>
					</tr>
					<tr>
					<td width="150" valign="top" bgcolor="#EAEBEE">Anfangs-Code:</td>
					<td bgcolor="#FAFAFB">
					  <textarea name="vor" style="width:98%;height:150;"><?=$ed_vor;?></textarea></td>
					</tr>
					<tr>
					<td width="150" valign="top" bgcolor="#EAEBEE">Abschluss-Code:</td>
					<td bgcolor="#FAFAFB">
					  <textarea name="nach" style="width:98%;height:150;"><?=$ed_nach;?></textarea></td>
					</tr>
					<tr>
					<td width="150" valign="top" bgcolor="#EAEBEE">HTML Ebene 1:<br>
					  <br>
					<font size=1>{TITEL} - Titel des Links<br>
					{URL} - URL des Links<br>
					{TARGET} - Ziel des Links</font></td>
					<td bgcolor="#FAFAFB">
					  <textarea name="ebene1" style="width:98%;height:150;"><?=$ed_ebene1;?></textarea></td>
					</tr>
					<tr>
					<td width="150" valign="top" bgcolor="#EAEBEE">HTML Ebene 1 Aktiv:<br>
					  <br>
					<font size=1>{TITEL} - Titel des Links<br>
					{URL} - URL des Links<br>
					{TARGET} - Ziel des Links</font></td>
					<td bgcolor="#FAFAFB">
					  <textarea name="ebene1a" style="width:98%;height:150;"><?=$ed_ebene1a;?></textarea></td>
					</tr>
					<tr>
					<td width="150" valign="top" bgcolor="#EAEBEE">HTML Ebene 2:<br>
					  <br>
					<font size=1>{TITEL} - Titel des Links<br>
					{URL} - URL des Links<br>
					{TARGET} - Ziel des Links</font></td>
					<td bgcolor="#FAFAFB">
					  <textarea name="ebene2" style="width:98%;height:150;"><?=$ed_ebene2;?></textarea></td>
					</tr>
					<tr>
					<td width="150" valign="top" bgcolor="#EAEBEE">HTML Ebene 2 Aktiv:<br>
					  <br>
					<font size=1>{TITEL} - Titel des Links<br>
					{URL} - URL des Links<br>
					{TARGET} - Ziel des Links</font></td>
					<td bgcolor="#FAFAFB">
					  <textarea name="ebene2a" style="width:98%;height:150;"><?=$ed_ebene2a;?></textarea></td>
					</tr>
					<tr>
					<td bgcolor="#EAEBEE">&nbsp;</td>
					<td bgcolor="#FAFAFB">					  
					  <input type="submit" class="button" value="weiter>>">
					</td>
					</tr>
					</table>
					</form>
					<?
                }
              
 } else {
	$msg = "<center>Diese Seite darf nur von Administratoren aufgerufen werden.</center>";
	MsgBox($msg);
 }
?>

