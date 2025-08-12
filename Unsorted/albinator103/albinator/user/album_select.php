<?php
	$dirpath = "$Config_rootdir"."../";
	require_once($dirpath."essential/dbc_essential.php");
	require_once($dirpath."essential/globalfunctions.php");
	$usr = new Html();
	$ucook = new UserCookie();
	$csr = new ComFunc();

      if ( !$ucook->LoggedIn() )
      {
          $usr->HeaderOut();
	    $csr->customMessage( 'logout' );
	    $usr->FooterOut();
   
          exit;
      }

      $usr->Header($Config_SiteTitle .' :: '.$strEditing);
      echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/edit.gif>&nbsp;</div>");
      echo ("<br>");

	$result = queryDB( "SELECT * FROM $tbl_albumlist WHERE uid = '$uid'" );
	$nr = mysql_num_rows( $result );
	if(!$nr)
  	{
       $errMsg = "<b>$strNo $strAlbum, <a href=index.php>$strCreate</a></b>\n<br>";
       $usr->errMessage( $errMsg, $strNote, 'error', '70' );
   	 $usr->Footer();
	 exit;
      }
	mysql_free_result( $result );

	$result_user = queryDB( "SELECT * FROM $tbl_albumlist WHERE uid = '$uid'" );
	
?>
<br><br>
<form action="pic_edit.php" method="post">
<table width="80%" border="0" cellspacing="1" cellpadding="3" align="center" bgcolor="#000000">
  <tr bgcolor="#CCCCCC"> 
    <td> 
      <div align="center" class="tn"><?php echo($csr->LangConvert($strSelectAlbum, $strMenusEdit[$catog])); ?> 
        <select name="aid">
<?php
		while($row = mysql_fetch_array( $result_user ))
		{
			echo("<option value=$row[aid]>".stripslashes($row[aname])."</option>\n");
		}
?>
        </select>
	 <input type=submit name=submit value="<?php echo $strNext ?> &gt;" class="butfieldc">
      </div>
    </td>
  </tr>
</table>
</form>	

<p>&nbsp;</p>
<?php

$usr->Footer();

?>