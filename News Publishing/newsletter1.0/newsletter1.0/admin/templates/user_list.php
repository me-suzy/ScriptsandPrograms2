<?php
/**
*    
*    @project   newsletter
*    @file      users_list.php
*    @version   1.0
*    @author    Konstantin Atanasov
*/
      global $con;
      include_once "rowset_pager.php";

      $sql = "SELECT email,name,datetime_subscribed,status FROM newsletter_users ";
      
    
      $rs = @mysql_query($sql,$con);
      $rp = @new rowset_pager($rs);
      
?>
<BR>
<TABLE cellspacing="0" cellpadding="0" class='table' width="100%" align='left' >
<TR><TD colspan=5 class=th style='background-color:white'>&nbsp;subscribed users list</TD></TR>

<TR>
    <TD class=th width=5%>&nbsp;</TD>
    <TD class=th width=10% align=center >email</TD>
    <TD width=25% width=10% class=th align=center >name</TD>
    <TD width=20% width=30% class=th align=center >date subscribed</TD>    
    <TD width=20% width=5% class=th  align=center >status</TD>
   
</TR>
<?php
    while($row = $rp->fetch_array()) {
        $name   = $row['name'];
        $email  = $row['email'];
        $status = $row['status'];
        
        if ($status == 0) {
            $status_name = "not confirmed";
        }
        if ($status == 1) {
            $status_name = "unsubscribed";
        }
        if ($status == 2) {
            $status_name = "confirmed";
        }
        $date   = $row['datetime_subscribed'];
?>
<TR>
    <TD><A href='admin.php?cmd=deluser&email=<?=$email?>'>delete</A></TD>
    <TD><?=$email?></TD>
    <TD><?=$name?></TD>
    <TD><?=$date?></TD>
    <TD><?=$status_name?></TD>
</TR>
<?php } ?>
<TR><TD colspan=5 ><?php $rp->showPageNavigator("admin.php"); ?></TD></TR>
<TR><TD colspan=5 style='border-top:1px solid gray'>&nbsp;</TD></TR>
</TABLE>
