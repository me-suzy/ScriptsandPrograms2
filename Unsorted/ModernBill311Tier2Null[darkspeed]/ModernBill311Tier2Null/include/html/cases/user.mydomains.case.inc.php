<?
            if(!$dbh)dbconnect();
            $details_view=1;
            $this_num_exp_domains=$next_num_exp_domains=$num_domains=0;
            list($num_domains)=mysql_fetch_row(mysql_query("SELECT count(domain_id) FROM domain_names WHERE client_id=$this_user[0]"));

            /* --- THIS MONTH --*/
            $first_stamp = mktime(0,0,0,date("m"),1,date("Y"));
            $last_stamp  = mktime(0,0,0,date("m")+1,-1,date("Y"));
            $this_WHERE  = "WHERE ( domain_expires <= $last_stamp AND domain_expires >= $first_stamp ) AND client_id=$this_user[0] ORDER BY domain_name";
            $sql         = "SELECT domain_id,domain_name,registrar_id FROM domain_names $this_WHERE";
            $result      = mysql_query($sql,$dbh);
            $this_exp_domain_list="<table border=1 width=70%>
                                    <tr>
                                     <td width=40%>".MFB."<b>".DOMAIN."</b>".EF."</td>
                                     <td width=20% align=center>".MFB."<b>".WHOIS."</b>".EF."</td>
                                     <td width=40% align=center>".MFB."<b>".REGISTRAR."</b>".EF."</td>
                                    </tr>";
            while(list($domain_id,$this_domain_name,$registrar_id)=mysql_fetch_array($result)) {
            $this_num_exp_domains++;
            $this_exp_domain_list .= "<tr>
                                       <td>".SFB."<a href=$page?op=details&db_table=domain_names&id=domain_id|$domain_id>$this_domain_name</a>".EF."</td>
                                       <td align=center>".SFB."<a href=http://www.netsol.com/cgi-bin/whois/whois?STRING=$this_domain_name&SearchType=do target=_blank>[?]</a>".EF."</td>
                                       <td align=right>".SFB.registrar_select_box($registrar_id).EF."</td>
                                     </tr>";
            }
            $this_exp_domain_list = (!$this_domain_name) ? "<tr><td colspan=3 align=center>".SFB."[".NONE."]".EF."</td></tr>" : $this_exp_domain_list ;

            /* --- NEXT MONTH --*/
            $first_stamp = mktime(0,0,0,date("m")+1,1,date("Y"));
            $last_stamp  = mktime(0,0,0,date("m")+2,-1,date("Y"));
            $next_WHERE  = "WHERE ( domain_expires <= $last_stamp AND domain_expires >= $first_stamp ) AND client_id=$this_user[0] ORDER BY domain_name";
            $sql         = "SELECT domain_id,domain_name,registrar_id FROM domain_names $next_WHERE";
            $result2     = mysql_query($sql,$dbh);
            $next_exp_domain_list="<table border=1 width=70%>
                                    <tr>
                                     <td width=40%>".MFB."<b>".DOMAIN."</b>".EF."</td>
                                     <td width=20% align=center>".MFB."<b>".WHOIS."</b>".EF."</td>
                                     <td width=40% align=center>".MFB."<b>".REGISTRAR."</b>".EF."</td>
                                    </tr>";
            while(list($domain_id,$next_domain_name,$registrar_id)=mysql_fetch_array($result2)) {
            $next_num_exp_domains++;
            $next_exp_domain_list .= "<tr>
                                       <td>".SFB."<a href=$page?op=details&db_table=domain_names&id=domain_id|$domain_id>$next_domain_name</a>".EF."</td>
                                       <td align=center>".SFB."<a href=http://www.netsol.com/cgi-bin/whois/whois?STRING=$next_domain_name&SearchType=do target=_blank>[?]</a>".EF."</td>
                                       <td align=right>".SFB.registrar_select_box($registrar_id).EF."</td>
                                      </tr>";
            }
            $next_exp_domain_list = (!$next_domain_name) ? "<tr><td colspan=3 align=center>".SFB."[".NONE."]".EF."</td></tr>" : $next_exp_domain_list ;

      ?>
        <tr>
          <td>
            <table cellpadding=0 cellspacing=0 border=0 align=center width=100%>
             <tr>
              <td><?=LFH?><b><?=DOMAINSTATS?>:</b><?=EF?></td>
              <td>&nbsp;</td>
             </tr>
             <tr>
              <td colspan=2>
               <table cellpadding=2 cellspacing=2>
               <tr>
               <td width=50% valign=top>
                       <?=MFB?>
                       <?=MYDOMAINS?>:<br>
                       <?=EXPTHISMONTH?>:<br>
                       <?=EXPNEXTMONTH?>:<br>
                       <?=EF?>
               </td>
               <td align=right>
                       <?=MFB?>
                       [<a href=<?=$page?>?op=view&tile=<?=$tile?>><?=$num_domains?></a>]<br>
                       [<a href=<?=$page?>?op=view&tile=<?=$tile?>&where=<?=urlencode($this_WHERE)?>><?=$this_num_exp_domains?></a>]<br>
                       [<a href=<?=$page?>?op=view&tile=<?=$tile?>&where=<?=urlencode($next_WHERE)?>><?=$next_num_exp_domains?></a>]<br>
                       <?=EF?>
               </td>
               </tr>
               </table>
              </td>
             </tr>
             <tr><td colspan=2><hr size=1 width=98%></td></tr>
             <tr><td colspan=2><?=LFH?><b><?=EXPIRING?>:</b><?=EF?> <?=SFB?>[<?=THISMONTH?> <?=stamp_to_date(mktime());?>]<?=EF?></td></tr>
             <tr><td colspan=2 valign=top align=center><?=$this_exp_domain_list?></td></tr>
             <tr><td colspan=2><hr size=1 width=98%></td></tr>
             <tr><td colspan=2><?=LFH?><b><?=EXPIRING?>:</b><?=EF?> <?=SFB?>[<?=NEXTMONTH?> <?=stamp_to_date(mktime(0,0,0,date("m")+1,1,date("Y")));?>]<?=EF?></td></tr>
             <tr><td colspan=2 valign=top align=center><?=$next_exp_domain_list?></td></tr>
            </table>
          </td>
        </tr>
