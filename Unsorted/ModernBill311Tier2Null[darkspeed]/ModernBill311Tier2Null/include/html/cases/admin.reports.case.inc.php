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
            $details_view=1;
            $num_exp_domains=$this_num_exp_domains=$next_num_exp_domains=$num_domains=$next_exp_domain_list=0;
            list($num_domains)=mysql_fetch_row(mysql_query("SELECT count(domain_id) FROM domain_names"));

            /* --- THIS MONTH (30)--*/
            $first_stamp = mktime(0,0,0,date("m"),1,date("Y"));
            $last_stamp = mktime(0,0,0,date("m")+1,-1,date("Y"));
            $this_WHERE = "WHERE ( domain_expires <= $last_stamp AND domain_expires >= $first_stamp ) AND monitor=1";
            $sql="SELECT domain_id,domain_name,registrar_id FROM domain_names $this_WHERE ORDER BY domain_name";
            $result=mysql_query($sql,$dbh);
            $this_exp_domain_list="<table width=70%><tr><td width=40%>".MFB."<b>".DOMAIN."</b>".EF."</td>
                                               <td width=20% align=center>".MFB."<b>".WHOIS."</b>".EF."</td>
                                               <td width=40% align=center>".MFB."<b>".REGISTRAR."</b>".EF."</td></tr>";
            while(list($domain_id,$domain_name,$registrar_id)=mysql_fetch_array($result)) {
                $this_num_exp_domains++;
                if ($this_num_exp_domains<=5)
                {
                    $this_exp_domain_list .= "<tr><td>".SFB."<a href=$page?op=details&db_table=domain_names&tile=$tile&id=domain_id|$domain_id >$domain_name</a>".EF."</td>
                                     <td align=center>".SFB."<a href=http://www.netsol.com/cgi-bin/whois/whois?STRING=$domain_name&SearchType=do target=_blank>[?]</a>".EF."</td>
                                     <td align=right>".SFB.registrar_select_box($registrar_id).EF."</td></tr>";
                }
            }
            if ($this_num_exp_domains > 5) $this_exp_domain_list .= "<tr><td align=center colspan=3><b>".MFB."<a href=$page?op=view&db_table=domain_names&tile=reports&where=".urlencode($this_WHERE).">".VIEWALL." $this_num_exp_domains</a>".EF."</b></td></tr>";
            $this_exp_domain_list = ($this_exp_domain_list=="") ? "<tr><td colspan=3 align=center>".SFB."[".NONE."]".EF."</td></tr></table>" : $this_exp_domain_list."</table>" ;

            /* --- NEXT MONTH (60)--*/
            $first_stamp = mktime(0,0,0,date("m")+1,1,date("Y"));
            $last_stamp = mktime(0,0,0,date("m")+2,-1,date("Y"));
            $next_WHERE = "WHERE ( domain_expires <= $last_stamp AND domain_expires >= $first_stamp ) AND monitor=1";
            $sql="SELECT domain_id,domain_name,registrar_id FROM domain_names $next_WHERE ORDER BY domain_name";
            $result2=mysql_query($sql,$dbh);
            $next_exp_domain_list="<table width=70%><tr><td width=40%>".MFB."<b>".DOMAIN."</b>".EF."</td>
                                               <td width=20% align=center>".MFB."<b>".WHOIS."</b>".EF."</td>
                                               <td width=40% align=center>".MFB."<b>".REGISTRAR."</b>".EF."</td></tr>";
            while(list($domain_id,$domain_name,$registrar_id)=mysql_fetch_array($result2)) {
            $next_num_exp_domains++;
                if ($next_num_exp_domains<=5)
                {
                    $next_exp_domain_list .= "<tr><td>".SFB."<a href=$page?op=details&db_table=domain_names&tile=$tile&id=domain_id|$domain_id >$domain_name</a>".EF."</td>
                                     <td align=center>".SFB."<a href=http://www.netsol.com/cgi-bin/whois/whois?STRING=$domain_name&SearchType=do target=_blank>[?]</a>".EF."</td>
                                     <td align=right>".SFB.registrar_select_box($registrar_id).EF."</td></tr>";
                }
            }
            if ($next_num_exp_domains > 5) $next_exp_domain_list .= "<tr><td align=center colspan=3><b>".MFB."<a href=$page?op=view&db_table=domain_names&tile=reports&where=".urlencode($next_WHERE).">".VIEWALL." $next_num_exp_domains</a>".EF."</b></td></tr>";
            $next_exp_domain_list = ($next_exp_domain_list=="") ? "<tr><td colspan=3 align=center>".SFB."[".NONE."]".EF."</td></tr></table>" : $next_exp_domain_list."</table>" ;
      ?>
        <tr>
          <td>
            <table cellpadding=0 cellspacing=0 border=0 align=center width=100%>
             <tr><td><?=LFH?><b><?=DOMAINSTATS?>:</b><?=EF?></td><td><?=LFH?><b><?=SELECTREPORT?>:</b><?=EF?></td></tr>
             <tr>
               <td width=50% valign=top>
                 <table>
                  <tr><td>
                       <?=MFB?>
                       <?=TTLDOMS?>:<br>
                       <?=EXPTHISMONTH?>:<br>
                       <?=EXPNEXTMONTH?>:<br>
                       <?=EF?>
                      </td>
                      <td align=right>
                       <?=MFB?>
                       [<a href=<?=$page?>?op=view&db_table=domain_names&tile=<?=$tile?>><?=$num_domains?></a>]<br>
                       [<a href=<?=$page?>?op=view&db_table=domain_names&tile=<?=$tile?>&where=<?=urlencode($this_WHERE)?>><?=$this_num_exp_domains?></a>]<br>
                       [<a href=<?=$page?>?op=view&db_table=domain_names&tile=<?=$tile?>&where=<?=urlencode($next_WHERE)?>><?=$next_num_exp_domains?></a>]<br>
                       <?=EF?>
                      </td>
                      <td align=right>
                       <?=MFB?>
                       [<a href=<?=$page?>?op=form&db_table=domain_names&tile=<?=$tile?>><b><?=ADD?></b></a>]<br>
                       &nbsp;<br>
                       &nbsp;<br>
                       <?=EF?>
                      </td>
                   </tr>
                  </table>
               </td>
               <td width=50% valign=top>
                  <table cellpadding=2 cellspacing=0 border=0>
                   <form method=post action=<?=$page?>>
                   <input type=hidden name=op value=reports>
                   <input type=hidden name=tile value=<?=$tile?>>
                    <tr><td><? $details_view=0; ?><?=report_select_box();?></td><td><?=GO_IMG?></td></tr>
                    <tr><td colspan=2><input type=checkbox name=print value=1> <?=SFB.CHECKFORPRINT.EF?></td></tr>
                   </form>
                  </table>
               </td>
             </tr>
            </table>
           <hr size=1 width=98%>
            <table cellpadding=0 cellspacing=0 border=0 align=center width=100%>
             <tr><td><?=LFH?><b><?=EXPIRING?>:</b><?=EF?> <?=SFB?>[<?=$this_num_exp_domains?> <?=THISMONTH?>: <?=date("m/Y")?>]<?=EF?></td></tr>
             <tr><td width=100% valign=top align=center><? if ($this_num_exp_domains) { echo $this_exp_domain_list; } else { echo "&nbsp;"; } ?></td></tr>
            </table>
           <hr size=1 width=98%>
            <table cellpadding=0 cellspacing=0 border=0 align=center width=100%>
             <tr><td><?=LFH?><b><?=EXPIRING?>:</b><?=EF?> <?=SFB?>[<?=$next_num_exp_domains?> <?=NEXTMONTH?>: <?=date("m/Y",mktime(0,0,0,date("m")+1,1,date("Y")));?>]<?=EF?></td></tr>
             <tr><td width=100% valign=top align=center><? if ($next_exp_domain_list) { echo $next_exp_domain_list; } else { echo "&nbsp;"; } ?></td></tr>
            </table>
          </td>
        </tr>