<?
 include("../../include/config.inc.php"); 
 include("../../include/mysql-class.inc.php");
 include("../../include/functions.inc.php");
?>
<table width="100%">
                 <tr>
                 <?
                    $offset = 0;
                 	$sql =& new MySQLq();
                 	$sql->Query("SELECT * FROM " . $sql_prefix . "buttons");
                 	while ($row = $sql->FetchRow()) { 
                      	$offset++;
						if ($offset == 4) {
							$offset = 1;
							echo "</tr><tr>";
						}
						echo "<td align=center><a href=\"javascript:parent.document.all.bbutton.value=$row->id;parent.document.all.al.value=$row->l;parent.document.all.bl.value=$row->l;parent.document.all.at.value=$row->t;parent.document.all.bt.value=$row->t;parent.document.all.bfarbe.value='$row->farbe';parent.document.all.afont.value='$row->font';parent.document.all.bfont.value='$row->font';parent.ref();\"><img src=\"" . $row->bild ."\" border=\"0\"></a></td>";
					}
					$sql->Close();
                 ?>
                 </tr>
</table>