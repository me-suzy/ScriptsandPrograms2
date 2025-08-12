<?

include_once($DOCUMENT_ROOT . "/includes/config.inc.php");
include($DOCUMENT_ROOT . "/includes/header.php");
if($deflang)
{
	include("../includes/language/lang-".$deflang.".php");
}
else
{
    include("../includes/language/lang-english.php");
}
?>
<TABLE BORDER="0" ALIGN="CENTER" VALIGN="TOP" WIDTH="750" CELLSPACING="0" CELLPADDING="0" BORDER="0">
<TR><TD>&nbsp;</TD></TR>
<TR>
<TD ALIGN="CENTER">

<TABLE ALIGN="CENTER" VALIGN="TOP" WIDTH="100%" CELLPADDING="2" BORDER="1" BORDERCOLOR="#000000" BORDERCOLORLIGHT="#000000" BORDERCOLORDARK="#000000">
<? printabout(10); ?>
<TR>
	<TD ALIGN="CENTER" COLSPAN="10">
	<A HREF="admin/check.list.php"><? echo GTGP_SET_RETURN_C; ?></A>
	<? echo "<BR><font color=red>$message</center><BR>"; ?>	
	</TD>
</TR>
<?
if ($killer) {
   foreach ($clean_delete as $key => $value) {
      $Query = "DELETE FROM tblTgp WHERE id='$key'";
         $result = mysql_query($Query, $conn);
     }
	 $pdeleted = "<TR><TD ALIGN=\"CENTER\" COLSPAN=\"10\"><FONT COLOR=\"red\">". GTGP_ADMIN_CL_DELETED ."</FONT></TD></TR>";
	 die($pdeleted);
  }

if ($choice) {
   $query = "select * from tblTgp WHERE category='$choice' AND accept='yes' ORDER BY date DESC Limit 150";

   $result = mysql_query ($query)
           or die ("Query failed");
}

if ($result) {
echo "<form name=\"form1\" method=\"post\" action=\"$PHP_SELF\">";
echo "<tr><td colspan=10 align=left><font size=1><B>" . GTGP_ADMIN_CL_RES ."</B></font></td></tr>";
echo "<tr>
      <td align=right><font size=1><b>" . GTGP_ADMIN_CL_URL   . "</b></font></td>
      <td align=right><font size=1><b>" . GTGP_ADMIN_CL_RURL  . "</b></font></td>
	  <td align=right><font size=1><b>" . GTGP_ADMIN_CL_BW    . "</b></font></td>
      <td align=right><font size=1><b>" . GTGP_ADMIN_CL_POPUP . "</b></font></td>
      <td align=right><font size=1><b>" . GTGP_ADMIN_CL_JAVA . "</b></font></td>
      <td align=right><font size=1><b>" . GTGP_ADMIN_CL_FL . "</b></font></td>
      <td align=right><font size=1><b>" . GTGP_ADMIN_CL_IFRAME . "</b></font></td>
      <td align=right><font size=1><b>" . GTGP_ADMIN_CL_OBJECT . "</b></font></td>
      <td align=right><font size=1><b>" . GTGP_ADMIN_CL_DATE  . "</b></font></td>
      <td align=right><font size=1><b>" . GTGP_ADMIN_CL_DEL   . "</b></font></td>
      </tr>";
         while ($r = mysql_fetch_array($result)) { 
            $url = $r["url"];
            $date = $r["date"];
            $id =  $r["id"];

         $open = @fopen("$url", "r");
               if(!$open){ 
                  $msg1 = "404";
         }else{ /* else 1 */
               
			$read = fread($open, 5000);
			fclose($open);
			$read = strtolower($read);
			// $recipek = "<a href=\"$recip\"";
            $recipek = "$recip";
			$recipcheck= substr_count($read, "$recipek");
            
			if(!$recipcheck){
				$msg1 = "<font color=red>" . GTGP_NO1 . "</font>";
			}
			else
			{
				$msg1 = GTGP_YES1;
			}
			$msg2 = GTGP_ADMIN_CL_CLEAN;
			$msg3 = GTGP_ADMIN_CL_CLEAN;
			$msg4 = GTGP_ADMIN_CL_CLEAN;
			$msg5 = GTGP_ADMIN_CL_CLEAN;
			$msg6 = GTGP_ADMIN_CL_CLEAN;
			$msg7 = GTGP_ADMIN_CL_CLEAN;
			if ($badwordcheck == 'Yes'){
				$ckbad = explode(",", "$badword");
				while(list($v) = each($ckbad))
				{
				if($ckbad[$v])
				{
					$ckbad[$v] = trim($ckbad[$v]);
					$badcheck= substr_count($read, "$ckbad[$v]");
					if($badcheck)
					{
						$msg2 = "<font color=red>" . GTGP_ADMIN_CL_JBW ."</font>"; 
					}
				}
				}
			}

/*			if ($popcheck == 'No'){ */
				$badpop = substr_count($read, "$popup");
				if($badpop)
				{
					$msg3 = "<font color=red>" . GTGP_ADMIN_CL_JP . "</red>";
				}
/*			} */
/*			if($javacheck == 'No'){ */
				$javascr = substr_count($read, "$java");
				if($javascr)
				{
					$msg4 = "<font color=red>" . GTGP_ADMIN_CL_JJ . "</red>";
				}
/*			}*/
/*			if($flcheck == 'No'){ */
				$flashlink = substr_count($read, "$flcode");
				if($flashlink)
				{
					$msg5 = "<font color=red>" . GTGP_ADMIN_CL_JFL . "</red>";
				}
/*			} */
/*			if($iframecheck == 'No'){ */
				$iframe = substr_count($read, "$iframecode");
				if($iframe)
				{
					$msg6 = "<font color=red>" . GTGP_ADMIN_CL_JIF . "</red>";
				}
/*			} */
/*			if($objectcheck == 'No'){ */
				$object = substr_count($read, "$objectcode");
				if($object)
				{
					$msg7 = "<font color=red>" . GTGP_ADMIN_CL_JIF . "</red>";
				}
/*			} */
        } /* end else 1 */
   echo "<tr><td class=\"tabelek\">";
   echo "<a href=\"$url\" target=\"_blank\"><font size=1>$url</font></a>";
   echo "</td>
      <td class=\"tabelek\" align=right><font size=1>$msg1&nbsp</font></td>
      <td class=\"tabelek\" align=right><font size=1>$msg2&nbsp</font></td>
      <td class=\"tabelek\" align=right><font size=1>$msg3&nbsp</font></td>
      <td class=\"tabelek\" align=right><font size=1>$msg4&nbsp</font></td>
      <td class=\"tabelek\" align=right><font size=1>$msg5&nbsp</font></td>
      <td class=\"tabelek\" align=right><font size=1>$msg6&nbsp</font></td>
      <td class=\"tabelek\" align=right><font size=1>$msg7&nbsp</font></td>
      <td class=\"tabelek\" align=right><font size=1>$date&nbsp</font></td>
      <td class=\"tabelek\" align=right><input type=\"checkbox\" name=\"clean_delete[$id]\" value=\"checkbox\"></td>
      </tr>";
      } /* end while loop */
      
   } /* end result if */
echo "<tr> 
      <td colspan=10 align=center>
      <input type=\"submit\" name=\"killer\" value=\"" . GTGP_ADMIN_CL_DELP ."\">
      </td>
      </tr>";
echo "</form>";
?>

</TABLE>
</TD>
</TR>
</TABLE>
</body>
</html>

