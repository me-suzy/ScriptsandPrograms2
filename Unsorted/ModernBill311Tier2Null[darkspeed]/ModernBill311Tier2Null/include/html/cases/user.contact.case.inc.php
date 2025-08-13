       <form method=post action=<?=$page?>?tile=contact>
       <input type=hidden name=step value=next>
        <tr>
          <td align=center>
            <table cellpadding=0 cellspacing=0 border=0 align=center width=100%>
             <tr><td><?=LFH?><b><?=CONTACTINFO?>:</b><?=EF?></td><td><?=LFH?><b><?=HELPDOCS?>:</b><?=EF?></td></tr>
             <tr>
               <td width=50% valign=top>
                 <table>
                  <tr><td><?=MFB.nl2br($user_contact_info).EF?></td>
                   </tr>
                  </table>
               </td>
               <td width=50% valign=top><?=MFB.nl2br($user_help_docs).EF?></td>
             </tr>
            </table>
           <?
           switch ($step) {

            /* --- SEND EMAIL ---*/
            case next:
            GLOBAL $success;
            $email_cc=$this_user["client_email"];
            $email_subject=trim($user_email_subject);
            $email_from=trim($user_email_from);
            send_email($email_to,$email_cc,$email_priority,$email_subject,$email_body,$email_from)
          ?>
           <hr size=1 width=98%>
            <table cellpadding=0 cellspacing=0 border=0 align=center width=100%>
             <tr><td><?=LFH?><b><?=EMAILSTATUS?>:</b><?=EF?></td></tr>
             <tr>
               <td valign=top>
                 <table>
                  <tr><td>
                      <? if ($success["sent"]==1) {
                          echo MFB.EMAILSUCCESS1."<i>$email_to</i>.<br>".EMAILSUCCESS2.EF;
                         } elseif ($success["failed"]==1) {
                          echo MFB.EMAILERRORMSG.EF;
                         }
                      ?>
                      </td>
                   </tr>
                  </table>
               </td>
             </tr>
            </table>
          <? break;

             default:
          ?>
           <hr size=1 width=98%>
            <table cellpadding=2 cellspacing=2 border=0 align=center width=100%>
             <tr><td colspan=2><?=LFH?><b><?=SENDEMAIL?>:</b><?=EF?></td></tr>
             <tr><td width=15% align=right><input type=hidden name=email_type value=user_contact>
                 <?=SFB?><b><?=TO?>:</b><?=EF?>
                 </td>
                 <td align=left>
                 <?=$email_to_menu?>
                 </td>
              </tr>
             <tr><td width=15% align=right>
                 <?=SFB?><b><?=FROM?>:</b><?=EF?>
                 </td>
                 <td align=left>
                 <input type=hidden name=user_email_from value="<?=$this_user["client_fname"]." ".$this_user["client_lname"]." <".$this_user["client_email"].">" ?>"> <?=MFB.$this_user["client_fname"]." ".$this_user["client_lname"]." &lt;".$this_user["client_email"]."&gt;".EF?>
                 </td>
              </tr>
             <tr><td width=15% align=right>
                 <?=SFB?><b><?=PRIORITY?>:</b><?=EF?>
                 </td>
                 <td align=left>
                 <select name=email_priority>
                 <option value=3 SELECTED><?=NORMAL?></option>
                 <option value=1><?=HIGH?></option>
                 <option value=5><?=LOW?></option>
                 </select>
                 </td>
              </tr>
             <tr><td width=15% align=right>
                 <?=SFB?><b><?=SUBJECT?>:</b><?=EF?>
                 </td>
                 <td align=left>
                 <?=$email_subject_menu?>
                 </td>
              </tr>
             <tr><td width=15% align=right>
                 <?=SFB?><b><?=EMAILMSG?>:</b><?=EF?>
                 </td>
                 <td align=left>
                 <textarea name=email_body rows=10 cols=50 VIRTUAL maxlength=2000></textarea>
                 </td>
              </tr>
             <tr><td colspan=2 align=center><input type=submit name=submit value="<?=SENDEMAIL?>"></td></tr>
             </table>
          <? break;
           }
          ?>
          </td>
        </tr>
       </form>
