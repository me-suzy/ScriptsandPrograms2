<?php
/**
*    
*    @project   newsletter
*    @file      users_list.php
*    @version   1.0
*    @author    Konstantin Atanasov
*
*
NO WARRANTY
 BECAUSE THE PROGRAM IS LICENSED FREE OF CHARGE, 
 THERE IS NO WARRANTY FOR THE PROGRAM, TO THE EXTENT PERMITTED BY APPLICABLE LAW. EXCEPT WHEN OTHERWISE STATED IN WRITING THE COPYRIGHT HOLDERS AND/OR OTHER PARTIES 
 PROVIDE THE PROGRAM "AS IS" WITHOUT WARRANTY OF ANY KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING, BUT NOT LIMITED TO,
 THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE. 
 THE ENTIRE RISK AS TO THE QUALITY AND PERFORMANCE OF THE PROGRAM IS WITH YOU. SHOULD THE PROGRAM PROVE DEFECTIVE, YOU ASSUME THE COST OF ALL NECESSARY SERVICING, 
 REPAIR OR CORRECTION.
*/
      global $con;
      include_once "rowset_pager.php";

      $sql = "SELECT id,article,status,datetime_posted,datetime_send,article_type FROM newsletter ORDER BY ID DESC";
      
      $rs = @mysql_query($sql,$con);
      $rp = @new rowset_pager($rs);
      
?>
<BR>
<TABLE cellspacing="0" cellpadding="0" class='table' width="100%" align='left' >
<TR><TD colspan=6 class=th style='background-color:white'>&nbsp;news articles list</TD></TR>

<TR>
    <TD class=th width=5%>&nbsp;</TD>
    <TD class=th width=5%>&nbsp;</TD>
    <TD class=th width=10% align=center >article</TD>
    <TD width=25% width=10% class=th align=center >type</TD>
    <TD width=25% width=10% class=th align=center >status</TD>
    <TD width=20% width=30% class=th align=center >date posted</TD>    
    <TD width=20% width=5%  class=th  align=center >date send</TD>
   
</TR>
<?php
    while($row = $rp->fetch_array()) {
        $id  = $row['id'];
        $article   = $row['article'];
        $article_type   = $row['article_type'];
        $preview_text = getPreviewContent($article);
        
        $date_posted  = $row['datetime_posted'];
        $status = $row['status'];
        if ($status == 0) {
            $status = "not send";
        } else {
            $status = "send";
        }
     
        $date_send   = $row['datetime_send'];
?>
<TR>
    <TD align=center><A href='admin.php?cmd=delart&artid=<?=$id?>'>delete</A></TD>
    <TD align=center><A href='admin.php?cmd=send&artid=<?=$id?>'>send</A></TD>
    <TD align=center ><?=$preview_text?></TD>
    <TD align=center ><?=$article_type?></TD>
    <TD align=center ><?=$status?></TD>
    <TD align=center ><?=$date_posted?></TD>
    <TD align=center ><?=$date_send?></TD>
</TR>
<?php } ?>
<TR><TD colspan=6 ><?php $rp->showPageNavigator("admin.php"); ?></TD></TR>
<TR><TD colspan=6 style='border-top:1px solid gray'>&nbsp;</TD></TR>
</TABLE>
