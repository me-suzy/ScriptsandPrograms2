<?
/*
** ModernBill [TM] (Copyright::2001)
** Questions? webmaster@modernbill.com
**
**
**          Always save a backup before your upgrade!
**          Proceed with caution. You have been warned.
*/


## Must be included ONLY once!
include_once("include/functions.inc.php");

## Validate that the user is an ADMIN or log them out
if (!testlogin()||!$this_admin||$this_user)  { Header("Location: http://$standard_url?op=logout"); exit; }

if(!$dbh)dbconnect();
$db_table  = "support_desk";

if(isset($submit)&&$mytype == "insert_log") {
    $insert_sql = "INSERT INTO support_log (log_id,
                                            call_id,
                                            log_event,
                                            call_technician,
                                            log_stamp) VALUES (NULL,
                                                              '$call_id',
                                                              '$log_event',
                                                              '$this_admin[admin_id]',
                                                              '".mktime()."')";
    if ($debug) echo SFB.$insert_sql.EF."<br>";
    if (!mysql_query($insert_sql,$dbh)) { echo mysql_errno(). ": ".mysql_error(). "<br>"; }

    list($this_client_id) = mysql_fetch_row(mysql_query("SELECT client_id FROM support_desk WHERE call_id='$call_id'"));
    $this_client    = load_client_array($this_client_id);
    $email_to       = array($this_client_id);
    $email_subject  = trim(SUPPORTDESK.": ".ID."#$call_id: ".UPDATE);
    $email_body     = STAMP.": ".date("Y/m/d: h:i:s")."\n\n";
    $email_body    .= NAME.": ".$this_client[client_fname]." ".$this_client[client_lname]."\n";
    $email_body    .= EMAIL.": ".$this_client[client_email]."\n";
    $email_body    .= COMPANY.": ".$this_client[client_company]."\n\n";
    $email_body    .= str_pad(" ".ID."#$call_id: ".UPDATE." ",40,"=",STR_PAD_BOTH)."\n";
    $email_body    .= $log_event."\n\n";
    $email_body    .= "-- ".$this_admin[admin_realname]."\n";
    $email_body    .= "-- ".$this_admin[admin_email]."\n\n";
    $email_from     = "$this_admin[admin_realname] <$this_admin[admin_email]>";
    send_email($email_to,$email_cc,$email_priority,$email_subject,$email_body,$email_from);
}
if(isset($submit)&&isset($check_id)&&isset($new_status_id))
{
   for($i=0;$i<=count($check_id)-1;$i++){
       $old_status = mysql_one_data("SELECT call_status FROM support_desk WHERE call_id = '$check_id[$i]'");
       $result     = mysql_query("UPDATE support_desk SET call_status = '$new_status_id',call_technician='$this_admin[admin_id]' WHERE call_id = '$check_id[$i]'");
       $details_view = 1;
       $this_log_event = UPDATESTATUS.": ".$call_status_types[$old_status]." - ".$call_status_types[$new_status_id]." - ".$this_admin['admin_realname'];
       $insert_sql = "INSERT INTO support_log (log_id,
                                               call_id,
                                               log_event,
                                               call_technician,
                                               log_stamp) VALUES (NULL,
                                                              '$check_id[$i]',
                                                              '$this_log_event',
                                                              '$this_admin[admin_id]',
                                                              '".mktime()."')";
       $result = mysql_query($insert_sql);
       $details_view = 0;

       list($this_client_id) = mysql_fetch_row(mysql_query("SELECT client_id FROM support_desk WHERE call_id='$check_id[$i]'"));
       $this_client    = load_client_array($this_client_id);
       $email_to       = array($this_client_id);
       $email_subject  = trim(SUPPORTDESK.": ".ID."#$check_id[$i]: ".UPDATE);
       $email_body     = STAMP.": ".date("Y/m/d: h:i:s")."\n\n";
       $email_body    .= NAME.": ".$this_client[client_fname]." ".$this_client[client_lname]."\n";
       $email_body    .= EMAIL.": ".$this_client[client_email]."\n";
       $email_body    .= COMPANY.": ".$this_client[client_company]."\n\n";
       $email_body    .= str_pad(" ".ID."#$check_id[$i]: ".UPDATE." ",40,"=",STR_PAD_BOTH)."\n";
       $email_body    .= $this_log_event."\n\n";
       $email_body    .= "-- ".$this_admin[admin_realname]."\n";
       $email_body    .= "-- ".$this_admin[admin_email]."\n\n";
       $email_from     = "$this_admin[admin_realname] <$this_admin[admin_email]>";
       send_email($email_to,$email_cc,$email_priority,$email_subject,$email_body,$email_from);
   }

}
if(isset($submit)&&$mytype == "insert_call") {
    $call_status = 1;
    $submit = 1;
    $client_id = $this_user[0];
    include("include/db_attributes.inc.php");
    if ($debug) echo SFB.$insert_sql.EF."<br>";
    if (!mysql_query($insert_sql,$dbh)) { echo mysql_errno(). ": ".mysql_error(). "<br>"; }

}
if(isset($submit)&&$mytype == "update_call") {
    list($old_call_response) = mysql_fetch_row(mysql_query("SELECT call_response FROM support_desk WHERE call_id='$call_id'"));
    $new_call_response  = ($old_call_response) ? "\n$old_call_response\n\n" : NULL ;
    $new_call_response .= "$call_response\n\n".str_pad(" ".date("Y/m/d: h:i:s")." ",40,"=",STR_PAD_BOTH);
    $new_call_response  = addslashes($new_call_response);
    $update_sql = "UPDATE support_desk SET call_response = '$new_call_response' WHERE call_id='$call_id'";
    if ($debug) echo SFB.$update_sql.EF."<br>";
    if (!mysql_query($update_sql,$dbh)) { echo mysql_errno(). ": ".mysql_error(). "<br>"; }
}

$result = mysql_query("SELECT call_status, COUNT(*) FROM support_desk GROUP BY call_status");
while(list($call_status,$call_num)=mysql_fetch_array($result))
      $call_stats[$call_status] = $call_num;

?>
<tr>
  <td>
    <table cellpadding=0 cellspacing=0 border=0 align=center width=100%>
     <tr><td><?=LFH?><b><?=SUPPORTDESK?>:</b><?=EF?> [<a href=<?=$page?>?op=menu&tile=<?=$db_table?>&type=add><b><?=ADD?></b></a>]</td>
         <td><?=LFH?><b><?=SUPPORTSEARCH?>:</b><?=EF.SFB?> [<a href=<?=$page?>?op=view&db_table=<?=$db_table?>&tile=<?=$tile?>><b><?=VIEWALL?></b></a>]<?=EF?></td></tr>
     <tr>
       <td width=50% valign=top>
         <table>
          <tr><td>
               <?=MFB?>
               <?=NEW_t?>:<br>
               <?=OPEN?>:<br>
               <?=CLOSED?>:<br>
               <?=EF?>
              </td>
              <td align=right>
               <?=MFB?>
               [<a href=<?=$page?>?op=menu&tile=support_desk&type=view&id=1><?=($call_stats[1])?$call_stats[1]:0;?></a>]<br>
               [<a href=<?=$page?>?op=menu&tile=support_desk&type=view&id=2><?=($call_stats[2])?$call_stats[2]:0;?></a>]<br>
               [<a href=<?=$page?>?op=view&db_table=support_desk&tile=<?=$tile?>&where=<?=urlencode("WHERE call_status=3")?>><?=($call_stats[3])?$call_stats[3]:0;?></a>]<br>
               <?=EF?>
              </td>
              <td align=right>
               <?=MFB?>
               <br>
               &nbsp;<br>
               &nbsp;<br>
               &nbsp;<br>
               <?=EF?>
              </td>
           </tr>
          </table>
       </td>
       <td width=50% valign=top>
          <table>
          <form method=post action=<?=$page?>>
          <input type=hidden name=op value=view>
          <input type=hidden name=search value=1>
          <input type=hidden name=tile value=<?=$tile?>>
          <input type=hidden name=db_table value=<?=$db_table?>>
          <tr><td colspan=2><?=support_search_select_box();?></td></tr>
          <tr><td><input type=text name=query size=15 maxlength=25></td><td><?=GO_IMG?></td></tr>
          </form>
          </table>
          <br>

       </td>
     </tr>
    </table>
   <hr size=1 width=98%>
  </td>
</tr>

<tr>
  <td>
  <?
  $details_view = 1;
  switch ($type) {
          case view:
               switch ($id) {
                  case 1:
                       $call_status = $id;
                       $title = NEW_t;
                       $this_hidden_field  = "<input type=hidden name=new_status_id value=2>";
                       $this_submit_button = "<input type=submit name=submit value=\"".ASSIGNTOME."\">";
                  break;
                  case 2:
                       $call_status = $id;
                       $title = OPEN;
                       $this_hidden_field  = "<input type=hidden name=new_status_id value=3>";
                       $this_submit_button = "<input type=submit name=submit value=\"".CLOSECALL."\">";
                  break;
                  default:
                       $call_status = 3;
                       $title = CLOSED;
                  break;
               }
               $num = ($call_stats[$call_status]) ? $call_stats[$call_status] : 0 ;
               start_box($num." ".$title);
               $sql = "SELECT * FROM support_desk WHERE call_status = $call_status ORDER BY call_priority,call_stamp";
               $result = mysql_query($sql,$dbh);
               ?>
                <table cellpadding=0 cellspacing=0 border=0 width=100%><tr><td bgcolor=DDDDDD>
                <table cellpadding=2 cellspacing=1 border=0 width=100%>
                <form method=post action=<?=$page?>?op=menu&tile=support_desk&type=view&id=<?=$call_status?>>
                <?=$this_hidden_field?>
                  <tr>
                     <td bgcolor=DDDDDD align=center width=6%><nobr><b><?=SFB.CALLID.EF?></b></nobr></td>
                     <td bgcolor=DDDDDD align=center width=7%><b><?=SFB.PRIORITY.EF?></b></td>
                     <td bgcolor=DDDDDD align=left width=15%><b><?=SFB.CLIENT.EF?></b></td>
                     <td bgcolor=DDDDDD align=left width=35%><b><?=SFB.SUBJECT.EF?></b></td>
                     <td bgcolor=DDDDDD align=center width=7%><b><?=SFB.STATUS.EF?></b></td>
                     <td bgcolor=DDDDDD align=center width=15%><b><?=SFB.TECH.EF?></td>
                     <td bgcolor=DDDDDD align=center width=15%><b><?=SFB.TIMESTAMP.EF?></b></td>
                 </tr>
               <?
               while($this_support_call = mysql_fetch_array($result))
               {
                 $this_tech = ($this_support_call[call_technician]) ? admin_select_box($this_support_call[call_technician],"call_technician") : "-" ;
                 ?>
                 <tr>
                     <td bgcolor=FFFFFF align=left><input type=checkbox name=check_id[] value=<?=$this_support_call[call_id]?>> <?=SFB.$this_support_call[call_id].EF?></td>
                     <td bgcolor=FFFFFF align=center><?=SFB.priority_select_box($this_support_call[call_priority]).EF?></td>
                     <td bgcolor=FFFFFF align=left><?=SFB.client_select_box($this_support_call[client_id]).EF?></td>
                     <td bgcolor=FFFFFF align=left><?=SFB?><a href=<?=$page?>?op=menu&tile=support_desk&type=details&id=<?=$this_support_call[call_id]?>><?=$this_support_call[call_subject]?></a><?=EF?></td>
                     <td bgcolor=FFFFFF align=center><?=SFB.call_status_select_box($this_support_call[call_status]).EF?></td>
                     <td bgcolor=FFFFFF align=center><?=SFB.$this_tech.EF?></td>
                     <td bgcolor=FFFFFF align=center><nobr><?=SFB.date("Y/m/d: h:i:s",$this_support_call[call_stamp]).EF?></nobr></td>
                 </tr>
                 <?
               }
               ?>
                <tr><td colspan=7 align=center bgcolor=FFFFFF><?=$this_submit_button?></td></tr>
                </form>
                </table>
                </td></tr></table>
               <?
               stop_box();
          break;

          case details:
               start_box(CALLDETAILS);
               $sql = "SELECT * FROM support_desk WHERE call_id = $id";
               $result = mysql_query($sql,$dbh);
               $this_support_call = mysql_fetch_array($result);
               $this_tech = ($this_support_call[call_technician]) ? admin_select_box($this_support_call[call_technician],"call_technician") : "-" ;
               ?>
               <table cellpadding=0 cellspacing=0 border=0 width=100%>
               <tr><td bgcolor=DDDDDD>
                <table cellpadding=2 cellspacing=1 border=0 width=100%>
                  <tr>
                     <td bgcolor=DDDDDD align=center width=6%><nobr><b><?=SFB.CALLID.EF?></b></nobr></td>
                     <td bgcolor=DDDDDD align=center width=7%><b><?=SFB.PRIORITY.EF?></b></td>
                     <td bgcolor=DDDDDD align=left width=15%><b><?=SFB.CLIENT.EF?></b></td>
                     <td bgcolor=DDDDDD align=left width=35%><b><?=SFB.SUBJECT.EF?></b></td>
                     <td bgcolor=DDDDDD align=center width=7%><b><?=SFB.STATUS.EF?></b></td>
                     <td bgcolor=DDDDDD align=center width=15%><b><?=SFB.TECH.EF?></td>
                     <td bgcolor=DDDDDD align=center width=15%><b><?=SFB.TIMESTAMP.EF?></b></td>
                 </tr>
                 <tr>
                     <td bgcolor=FFFFFF align=center valign=top><?=SFB.$this_support_call[call_id].EF?></td>
                     <td bgcolor=FFFFFF align=center><?=SFB.priority_select_box($this_support_call[call_priority]).EF?></td>
                     <td bgcolor=FFFFFF align=left><?=SFB.client_select_box($this_support_call[client_id]).EF?></td>
                     <td bgcolor=FFFFFF align=left valign=top><?=SFB?><a href=<?=$page?>?op=menu&tile=support_desk&type=details&id=<?=$this_support_call[call_id]?>><?=$this_support_call[call_subject]?></a><?=EF?></td>
                     <td bgcolor=FFFFFF align=center valign=top><?=SFB.call_status_select_box($this_support_call[call_status]).EF?></td>
                     <td bgcolor=FFFFFF align=center><?=SFB.$this_tech.EF?></td>
                     <td bgcolor=FFFFFF align=center valign=top><nobr><?=SFB.date("Y/m/d: h:i:s",$this_support_call[call_stamp]).EF?></nobr></td>
                 </tr>
                 </table>
               </td></tr>
               <tr><td bgcolor=DDDDDD>
                 <table cellpadding=2 cellspacing=1 border=0 width=100%>
                 <tr>
                  <td bgcolor=FFFFFF align=right width=25% valign=top><b><?=SFB.TYPE.":".EF?></b></td>
                  <td bgcolor=FFFFFF valign=top><?=support_type_menu($this_support_call[call_type])?></td>
                 </tr>
                 <tr>
                  <td bgcolor=FFFFFF align=right valign=top><b><?=SFB.PRIORITY.":".EF?></b></td>
                  <td bgcolor=FFFFFF valign=top><?=priority_select_box($this_support_call[call_priority])?></td>
                 </tr>
                 <tr>
                  <td bgcolor=FFFFFF align=right valign=top><b><?=SFB.QUESTION.":".EF?></b></td>
                  <td bgcolor=FFFFFF valign=top><?=$this_support_call[call_question]?></td>
                 </tr>
                 <tr>
                  <td bgcolor=FFFFFF align=right valign=top><b><?=SFB.str_replace("<br>"," ",CUTERROR).":".EF?></b></td>
                  <td bgcolor=FFFFFF valign=top><?=$this_support_call[call_error]?></td>
                 </tr>
                 <tr>
                  <td bgcolor=FFFFFF align=right valign=top><b><?=SFB.CLIENTRESPONSE.":".EF?></b></td>
                  <td bgcolor=FFFFFF valign=top>
                      <?=nl2br(htmlspecialchars(stripslashes($this_support_call[call_response])))?>
                  </td>
                 </tr>
                 </table>
               </td></tr>
               <tr><td bgcolor=DDDDDD>
                 <table cellpadding=2 cellspacing=1 border=0 width=100%>
                 <tr>
                     <td bgcolor=DDDDDD align=left><b><?=SFB.UPDATE.EF?></b></td>
                     <td bgcolor=DDDDDD align=center><b><?=SFB.TECH.EF?></b></td>
                     <td bgcolor=DDDDDD align=center><b><?=SFB.TIMESTAMP.EF?></b></td>
                 </tr>
               <?
               $sql = "SELECT * FROM support_log WHERE call_id = $id";
               $result = mysql_query($sql,$dbh);
               while($this_support_log = mysql_fetch_array($result))
               {
                 $this_tech = ($this_support_log[call_technician]) ? admin_select_box($this_support_log[call_technician],"call_technician") : "-" ;
                 ?>
                 <tr>
                     <td bgcolor=FFFFFF align=left width=70% valign=top><?=SFB?><?=$this_support_log[log_event]?><?=EF?></td>
                     <td bgcolor=FFFFFF align=center width=15% valign=top><nobr><?=SFB.$this_tech.EF?></nobr></td>
                     <td bgcolor=FFFFFF align=center width=15% valign=top><nobr><?=SFB.date("Y/m/d: h:i:s",$this_support_log[log_stamp]).EF?></nobr></td>
                 </tr>
                 <?
               }
               ?>
                 <form method=post action=<?=$page?>?op=menu&tile=support_desk&type=details&id=<?=$id?>>
                 <input type=hidden name=mytype value=insert_log>
                 <input type=hidden name=call_id value=<?=$id?>>
                 <input type=hidden name=check_id[] value=<?=$id?>>
                 <tr>
                     <td bgcolor=FFFFFF align=center width=100% valign=top colspan=2>
                      <textarea name=log_event rows=5 cols=80 maxlength=5000 wrap=VIRTUAL></textarea>
                     </td>
                     <td bgcolor=FFFFFF align=center width=100% valign=middle>
                      <input type=submit name=submit value="<?=UPDATE?>">
                      <br><br>
                      <input type=checkbox name=new_status_id value=3> <?=SFB.CLOSECALL.EF?>
                     </td>
                 </tr>
                 </form>
                 </table>
               </td></tr></table>
               <?
               stop_box();
          break;

          case add:
               $details_view = NULL;
               start_box(NEWCALL);
               ?>
               <table>
               <form method=post action=<?=$page?>?op=<?=$op?>&tile=<?=$tile?>>
               <input type=hidden name=mytype value=insert_call>
               <tr>
               <td align=right width=25%><b><?=SFB.TYPE.":".EF?></b></td>
               <td><?=support_type_menu($call_type)?></td>
               </tr>
               <tr>
               <td align=right><b><?=SFB.PRIORITY.":".EF?></b></td>
               <td><?=priority_select_box($call_priority)?></td>
               </tr>
               <tr>
               <td align=right><b><?=SFB.SUBJECT.":".EF?></b></td>
               <td><input type=text name=call_subject value="<?=$call_subject?>" size=40 maxlength=255></td>
               </tr>
               <tr>
               <td align=right><b><?=SFB.QUESTION.":".EF?></b></td>
               <td><textarea name=call_question rows=12 cols=80 maxlength=2000 wrap=VIRTUAL><?=$call_subject?></textarea></td>
               </tr>
               <tr>
               <td align=right><b><?=SFB.CUTERROR.":".EF?></b></td>
               <td><textarea name=call_error rows=12 cols=80 maxlength=2000 wrap=VIRTUAL><?=$call_error?></textarea></td>
               </tr>
               <tr>
               <td align=right>&nbsp;</td>
               <td><input type=submit name=submit value="<?=CREATECALL?>">&nbsp;<input type=reset value="<?=CLEAR?>"></td>
               </tr>
               </form>
               </table>
               <?
               stop_box();
          break;
  }
  ?>
  </td>
</tr>