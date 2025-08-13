<?
 include("include/include.inc.php");
 
 if (!isset($HTTP_SESSION_VARS[u_loggedin]) or !($HTTP_SESSION_VARS[u_loggedin]=='yes')) {
  SessionError();
  exit;
 }
 
 $grp = Gruppe($HTTP_SESSION_VARS[u_gid]);
 if ($grp['m_dokumente']=="no") {
 	StyleSheet();
	$msg = "<center>Sie besitzen nicht die Rechte, diese Aktion auszuführen.</center>";
	MsgBox($msg);
	exit;
 }
 
 if ($_REQUEST['action']=="del") {
 	$sql =& new MySQLq();
 	$sql->Query("SELECT datei FROM " . $sql_prefix . "dokumente WHERE id='$_REQUEST[id]'");
 	$row = $sql->FetchRow();
 	$sql->Close();
	
	$end = explode(".", $row->datei);
	$endung = $end[1];
	
 	@unlink(".." . $row->datei);
	@unlink(".." .  str_replace(".$endung", "_print.$endung", $row->datei));
 	$sql =& new MySQLq();
 	$sql->Query("DELETE FROM " . $sql_prefix . "dokumente WHERE id='$_REQUEST[id]'");
 	$sql->Close();
	$sql =& new MySQLq();
	$sql->Query("DELETE FROM " . $sql_prefix . "dokumente_felder WHERE dokument='$_REQUEST[id]'");
	$sql->Close();
	
    eLog("user", "$_SESSION[u_user] löscht Dokument $_REQUEST[id]");
 	?>
	<script language="javascript">
	<!--
	parent.frames['struktur'].location.href = parent.frames['struktur'].location.href;
	//-->
	</script>
 	<?
 }
 
 if ($_REQUEST['action']=="open") {
 	$pubdate = time();
 	$sql =& new MySQLq();
 	$sql->Query("UPDATE " . $sql_prefix . "dokumente SET pubdatum='$pubdate' WHERE id='$_REQUEST[id]'");
 	$sql->Close();
 	RenderPages();
    eLog("user", "$_SESSION[u_user] stellt Dokument $_REQUEST[id] online");
 	?>
	<script language="javascript">
	<!--
	parent.frames['struktur'].location.href = parent.frames['struktur'].location.href;
	//-->
	</script>
 	<?
 }

 if ($_REQUEST['action']=="close") {
 	$pubdate = str_repeat('9', 14);
 	$sql =& new MySQLq();
 	$sql->Query("SELECT datei FROM " . $sql_prefix . "dokumente WHERE id='$_REQUEST[id]'");
 	$row = $sql->FetchRow();
 	$sql->Close();
	
	$end = explode(".", $row->datei);
	$endung = $end[1];
	
 	@unlink(".." . $row->datei);
	@unlink(".." . str_replace(".$endung", "_print.$endung", $row->datei));
 	$sql =& new MySQLq();
 	$sql->Query("UPDATE " . $sql_prefix . "dokumente SET pubdatum='$pubdate', published='no' WHERE id='$_REQUEST[id]'");
 	$sql->Close();
    eLog("user", "$_SESSION[u_user] stellt Dokument $_REQUEST[id] offline");
 	RenderPages();
 	?>
	<script language="javascript">
	<!--
	parent.frames['struktur'].location.href = parent.frames['struktur'].location.href;
	//-->
	</script>
 	<?
 }
?>
 <html>
<head>
<link rel="stylesheet" href="style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251"></head>
<link rel="stylesheet" href="include/dynCalendar.css" type="text/css" media="screen">
<script src="include/kalender.js" type="text/javascript" language="javascript"></script>
<body bgcolor="" background="/p4cms/gfx/main/bgbody.gif">
<script language="javascript">
<!--
 function DelDok(id, titel) {
 	if (window.confirm('Wollen Sie das Dokument "' + titel + '" wirklich komplett löschen?')) {
 		document.location.href='dokumente_main.php?action=del&id=' + id + '&d4sess=<?=$sessid;?>';
 	}
 }
//-->
</script>
<table width=100%  border="1" cellpadding="5" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">
  <tr>
    <td bgcolor="#FAFAFB">
      <table width="100%" border="0" cellpadding="2" cellspacing="0">
      <tr>
        <td><p><b>Dokumente</b></p>
        </td>
        <td align="right"><form style="display:inline;" method="post">
        Zeige Dokumente von 
          <!-- VON -->
            <select name="tag" class="inputfield">
              <?
			  $heute = time();
			  $abzeit = $heute - (86400 * 14);
			  
                for ($i=1;$i<=31;$i++) {
                	if (strlen($i) < 2) {
                		$w = "0" . $i;
                	} else {
                		$w = $i;
                	}
                	if (date("d",$abzeit) == $i) {
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
                	if (date("m",$abzeit) == $i) {
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
     			$jahr = date("Y",$abzeit);
     			for ($i=1; $i<=10; $i++) {
     				$abswert = $jahr + (-10 + $i);
     				if ($jahr == $abswert) {
     					$sel = " selected";
     				} else {
     					$sel = "";
     				}
     				?>
              <option value="<?=$abswert;?>"<?=$sel;?>>
              <?=$abswert;?>
              </option>
              <?
     			}
     			?>
            </select>
            <!-- VON -->
        bis
        <!-- BIS -->
        <select name="tag2" class="inputfield" id="tag2">
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
        <select name="monat2" class="inputfield" id="monat2">
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
        <select name="jahr2" class="inputfield" id="jahr2">
          <?
     			$jahr2 = date("Y");
     			for ($i=1; $i<=10; $i++) {
     				$abswert2 = $jahr2 + (-10 + $i);
     				if ($jahr2 == $abswert2) {
     					$sel2 = " selected";
     				} else {
     					$sel2 = "";
     				}
     				?>
          <option value="<?=$abswert2;?>"<?=$sel2;?>>
          <?=$abswert2;?>
          </option>
          <?
     			}
     			?>
        </select>
        <!-- BIS -->
        <select name="offon" id="offon">
          <option value="alle" <? if($_REQUEST['offon']=="alle") echo "selected";?>>alle</option>
          <option value="yes" <? if($_REQUEST['offon']=="yes") echo "selected";?>>Online</option>
          <option value="no" <? if($_REQUEST['offon']=="no") echo "selected";?>>Offline</option>
		  <?
		  if($_REQUEST['offon']=="" || $_REQUEST['offon']=="alle"){$zusatz = " AND (published='yes' OR published='no') " ;
		  $statmsg = " (Off & Online)";
		  }
		  else {
		  $zusatz = " AND published='".$_REQUEST['offon']."' ";
		 if($_REQUEST['offon']=="yes"){  $statmsg = " (Online)"; } else { $statmsg = " (Offline)";}
		  }
		  ?>
        </select>
        <input name="submit" type="submit" class="button" value="anzeigen">
        </form></td>
      </tr>
    </table>       </td>
  </tr>
</table>
<br>
<table width=100%  border="1" cellpadding="4" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">
  <tr>
    <td class="boxstandart">

      <?
//------------------------------------------------------------------------
// VON
//------------------------------------------------------------------------
if (!isset($_REQUEST['monat'])) {
	$heute = time();
	$abzeit = $heute - (86400 * 14);
	$_REQUEST['tag'] = date("d", $abzeit);
	$_REQUEST['monat'] = date("m", $abzeit);
	$_REQUEST['jahr'] = date("Y", $abzeit);
} else {
	$abzeit = mktime(0, 0, 0, $_REQUEST['monat'], $_REQUEST['tag'], $_REQUEST['jahr']);
}

//------------------------------------------------------------------------
// BIS
//------------------------------------------------------------------------
if (!isset($_REQUEST['monat2'])) {
	$heute2 = time();
	$abzeit2 = $heute2;
	$_REQUEST['tag2'] = date("d", $abzeit2);
	$_REQUEST['monat2'] = date("m", $abzeit2);
	$_REQUEST['jahr2'] = date("Y", $abzeit2);
} else {
	$abzeit2 = mktime(0, 0, 0, $_REQUEST['monat2'], $_REQUEST['tag2'], $_REQUEST['jahr2']);
}

?>
Angezeigt werden alle Dokumente vom <b>
<?=$_REQUEST['tag'].'.'.$_REQUEST['monat'].'.'.$_REQUEST['jahr'];?>
</b> bis <b>
<?=$_REQUEST['tag2'].'.'.$_REQUEST['monat2'].'.'.$_REQUEST['jahr2'];?>
</b><?=$statmsg;?><br>
<br>
<?
                $sql =& new MySQLq();
                $sql->Query("SELECT * FROM " . $sql_prefix . "rubriken ORDER BY titel ASC");
                while ($row = $sql->FetchRow()) {
                	?>
<b>Rubrik:</b>
<?=stripslashes($row->titel);?>
<br>
<br>
<table width=100%  border="1" cellpadding="4" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">
  <tr bgcolor="#EAEBEE">
    <td width="165" height="17" nowrap bgcolor="#FAFAFB" class="boxheader">&nbsp;Titel</td>
    <td width="260" height="17" nowrap bgcolor="#FAFAFB" class="boxheader">&nbsp;Datei</td>
    <td width="40" height="17" nowrap bgcolor="#FAFAFB" class="boxheader">
      <div align="center">Status</div>
    </td>
    <td width="150" height="17" bgcolor="#FAFAFB" class="boxheader">
      <div align="center">Erstellung</div></td>
    <td width="180" height="17" bgcolor="#FAFAFB" class="boxheader">
      <div align="center">Updated</div>
    </td>
    <td width="90" height="17" nowrap class="boxheader">
      <div align="center">Aktionen</div></td>
  </tr>
  <?
  $titel="";
                	$sql2 =& new MySQLq();
                	$sql2->Query("SELECT * FROM " . $sql_prefix . "dokumente WHERE rubrik='$row->id' AND datum>='$abzeit' AND datum<='$abzeit2' $zusatz ORDER BY id DESC");	
                	$q="SELECT * FROM " . $sql_prefix . "dokumente WHERE rubrik='$row->id' AND datum>='".date("d.m.Y",$abzeit)."' AND datum<='".date("d.m.Y",$abzeit2)."' $zusatz  ORDER BY id DESC";
					//echo $q;
					
					while ($row2 = $sql2->FetchRow()) {
                		$titel_g = stripslashes($row2->titel);
                		if (strlen($titel_g) > 20) {
                			$titel = substr($titel_g, 0, 20) . "...";
                		} else {
                			$titel = $titel_g;
                		}
                	    $datei_g = stripslashes($row2->datei);
                		if (strlen($datei_g) > 40) {
                			$datei = substr($datei_g, 0, 37) . "...";
                		} else {
                			$datei = $datei_g;
                		}
                		?>
  <tr bgcolor="#FAFAFB">
    <td width="165" height="17" bgcolor="#FAFAFB"><?=$titel;?>
    </td>
    <td width="260" height="17" bgcolor="#FAFAFB"><a title="<?=$datei_g;?>"<?
                		if ($row2->published=="yes") {
                			echo " target=\"_blank\" href=\"$row2->datei\"";
                		}
                		?>><?=$datei;?>
    </a></td>
    <td width="40" height="17" align="center" nowrap bgcolor="#FAFAFB">
      <div align="center"><b><?
                		if ($row2->published=="no") {
                			?>
      <font color="red">Off</font>
      <?
                			$smod = "open";
                		} else {
                			?>
      <font color="green">On</font>
      <?
                			$smod = "close";
                		}
                		?>
      </b></div>
    </td>
    <td width="150" height="17" bgcolor="#FAFAFB">
      <div align="center"><? echo (date("d.m. H:i", $row2->datum));?></div>
    </td>
    <td width="180" height="17" bgcolor="#FAFAFB">	
      <div align="center"><?=(date("d.m. H:i", $row2->lastupdate));?></div>	</td>
    <td width="90" height="17" nowrap>
      <div align="center"><a href="dokumente_main.php?action=<?=$smod;?>&id=<?=$row2->id;?>&d4sess=<?=$sessid;?>"><img src="gfx/<?=$smod;?>.gif" alt="<?
                		if ($smod=="open") {
                			echo "Online schalten";
                		} else {
                			echo "Offline schalten";
                		} ?>" hspace="4" border="0" align="absmiddle"></a><a href="dokument.php?mode=edit&id=<?=$row2->id;?>&d4sess=<?=$sessid;?>"><img src="gfx/edit.gif" alt="Bearbeiten" border="0" align="absmiddle"></a> <a href="javascript:DelDok('<?=$row2->id;?>','<?=addslashes($titel_g);?>');"><img src="gfx/del.gif" alt="L&ouml;schen" border="0" align="absmiddle"></a></div>
    </td>
  </tr>
  <?
                	}
                	$sql2->Close();
                ?>
</table>
<br>
<br>
<?
                }
                ?></td>
  </tr>
</table>

