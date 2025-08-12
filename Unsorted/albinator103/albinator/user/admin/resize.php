<?php
	$dirpath = "$Config_rootdir"."../../";
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

 	$result = queryDB( "SELECT * FROM $tbl_userinfo WHERE uid = '$uid' && admin !='0'" );
	$nr = mysql_num_rows( $result );

	if(!$nr)
	{
	 if($Config_makelogs == "1")
	 { $csr->MakeAdminLogs( $uid, "Denid Access to the Admin Panel :: $SCRIPT_NAME", "2"); }

       $usr->Header($Config_SiteTitle ." :: $strAdminstration");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin.gif>&nbsp;</div><br>");
       $csr->customMessage( 'noadmin' );
   	 $usr->Footer();

	 mysql_free_result( $result );	

	 closeDB();
	 exit;
	}

	mysql_free_result( $result );	

	if($sort)
	$sort_order = "ORDER BY u.$sort";

	$rs = new PagedResultSet("SELECT *,COUNT(a.aid) as acount FROM $tbl_userinfo as u,$tbl_albumlist as a WHERE a.uid=u.uid GROUP BY u.uid $sort_order",25);
	$nr = mysql_num_rows( $rs->result );
	$nav = $rs->getPageNav();

      $usr->Header($Config_SiteTitle ." :: $strAdminstration :: Resize");
      echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin.gif>&nbsp;</div><br>");
?>

<script language="Javascript">
<!--
function SetChecked(val) 
{
	dml=document.userList;
	len = dml.elements.length;

	var i=0;

	for( i=0 ; i<len ; i++) 
	{
		if (dml.elements[i].name=='uid_list[]')
		{
			dml.elements[i].checked=val;
		}
	}
}
//-->
</script>

<div align="center" class='ts'><?php echo($nav."<br>"); ?><a href=javascript:SetChecked(1)>Check All</a> ~ <a href=javascript:SetChecked(0)>UnCheck All</a></div>

<form action="resize_final.php" method="post" name="userList">
<table width="80%" border="0" cellspacing="0" cellpadding="4" align="center">
  <tr class="tn">

<?php echo("
    <td width=\"5%\">&nbsp;</td>
    <td width=\"30%\"><b><a href=resize.php?sort=uid>$strUsername</a></b></td>
    <td width=\"30%\"><b><a href=resize.php?sort=uname>$strName</a></b></td>
    <td width=\"35%\"><b>$strAlbum$strPuralS</b></td>
"); ?>

  </tr>

<?php

$i = 0;

	while($row = $rs->fetchArray())
	{
	if($i == 1)
	{ $i=0; $rowcolor = "#dddddd"; }
	else
	{ $i++; $rowcolor = "#eeeeee"; }

?>

  <tr class="tn" bgcolor="<?php echo $rowcolor ?>">
    <td><input type="checkbox" name="uid_list[]" value="<?php echo($row[uid]); ?>"></td>
    <td><?php echo("<a href=usrmngt.php?username=$row[uid]&dowhat=show class=noundertn>$row[uid]</a>"); ?></td>
    <td><?php echo("$row[uname]"); ?></td>
    <td><?php echo("$row[acount]"); ?></td>
  </tr>

<?php
	}			
?>

  <tr class="tn">
    <td colspan="4" align="right"><input type="submit" name="resize" value="resize &gt;"></td>
  </tr>


<?php
	echo("</table>");
?>

	</form>
<div align="center" class='ts'><?php echo($nav."<br>"); ?><a href=javascript:SetChecked(1)>Check All</a> ~ <a href=javascript:SetChecked(0)>UnCheck All</a></div>


<?
	$usr->Footer(); 
?>