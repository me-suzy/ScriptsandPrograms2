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

GLOBAL $this_num_results,$selectlimit;

##<-- DEFINE DISPLAY OPTIONS -->##

$display_account_pops    = ($display_account_pops)    ? $display_account_pops    : TRUE ;
$display_account_details = ($display_account_details) ? $display_account_details : TRUE ;
$display_account_dbs     = ($display_account_dbs)     ? $display_account_dbs     : TRUE ;
$display_client_domains  = ($display_client_domains)  ? $display_client_domains  : TRUE ;
$display_client_packages = ($display_client_packages) ? $display_client_packages : TRUE ;
$display_client_invoices = ($display_client_invoices) ? $display_client_invoices : TRUE ;
$display_client_notes    = ($display_client_notes)    ? $display_client_notes    : TRUE ;
$display_client_credits  = ($display_client_credits)  ? $display_client_credits  : TRUE ;

##<-- DEFINE SELECT LIMITS -->##

$limit_account_pops    = ($limit_account_pops)    ? $limit_account_pops    : 5 ;
$limit_account_details = ($limit_account_details) ? $limit_account_details : 5 ;
$limit_account_dbs     = ($limit_account_dbs)     ? $limit_account_dbs     : 5 ;
$limit_client_domains  = ($limit_client_domains)  ? $limit_client_domains  : 5 ;
$limit_client_packages = ($limit_client_packages) ? $limit_client_packages : 5 ;
$limit_client_invoices = ($limit_client_invoices) ? $limit_client_invoices : 12 ;
$limit_client_notes    = ($limit_client_notes)    ? $limit_client_notes    : 10 ;
$limit_client_credits  = ($limit_client_credits)  ? $limit_client_credits  : 5 ;

##<-- START DISPLAY -->##
$id  = explode("|",$id);
$sql = "SELECT * FROM $db_table WHERE $id[0]=$id[1]";
if($debug)echo SFB.$sql.EF."<br>";
list($num_invoices_paid,$amount_invoices_paid)=mysql_fetch_row(mysql_query("SELECT count(client_id),sum(invoice_amount) FROM client_invoice WHERE invoice_date_paid!=0 AND $id[0]=$id[1]"));
list($num_invoices_unpaid,$amount_invoices_unpaid)=mysql_fetch_row(mysql_query("SELECT count(client_id),sum(invoice_amount) FROM client_invoice WHERE invoice_date_paid=0 AND $id[0]=$id[1]"));

start_html();
admin_heading($tile);
?>
<table width="100%" border=0 align=center cellspacing=0 cellpadding=4 bgcolor=FFFFFF>
  <tr valign=top>
    <td colspan=2 align=center>
    <?
    addslashes($result = mysql_query($sql,$dbh));
    $this_client = mysql_fetch_array($result);
    echo MFB."<b>".CLIENTID." [<a href=$page?op=client_details&db_table=$db_table&tile=$tile&id=$id[0]|$id[1]>".$id[1]."</a>]: ".$this_client['client_fname']." ".$this_client['client_lname']."</b> &lt;<a href=mailto:".$this_client['client_email'].">".$this_client['client_email']."</a>&gt;".EF;
    ?>
    </td>
  </tr>
  <tr valign=top>
    <td width=35%>
    <?
        addslashes($result = mysql_query($sql,$dbh));
        start_table(NULL,"100%");
        build_form($args,$result);
               echo "<tr><td colspan=2 valign=top align=center>";
               echo SFB."<a href=\"$page?op=form&db_table=$db_table&tile=$tile&id=$id[0]|$id[1]\">".EDIT_IMG."</a>".EF;
               echo "&nbsp;";
               echo SFB."<a href=\"$page?op=delete&db_table=$db_table&tile=$tile&id=$id[0]|$id[1]\">".DELETE_IMG."</a>".EF;
               echo "</td></tr>";
        stop_table();
    ?>
    </td>
    <td>
      <table width="100%" border=0 align=center cellspacing=0 cellpadding=2>
        <tr>
          <td>
              <table width="100%" border=0 align=center cellspacing=2 cellpadding=2>
              <tr>
               <form method=post action=<?=$admin_page?>>
               <td valign=top>
               <?
               echo MFH."<b>".STATS.":</b>".EF."<br>";
               echo SFB.INVOICESPAID.": $num_invoices_paid = ".display_currency($amount_invoices_paid).EF."<br>";
               echo SFB.INVOICESDUE.": $num_invoices_unpaid = ".display_currency($amount_invoices_unpaid).EF."<br>";
               ?>
               <input type=hidden name=op value="quick_status_update">
               <input type=hidden name=client_id value="<?=$this_client['client_id']?>">
               <input type=hidden name=tile value="<?=$tile?>">
               <input type=hidden name=this_status_id value="<?=$this_client['client_status']?>">
               <?
               echo MFH."<b>".MISC.":</b>".EF."<br>";
               echo SFB.UPDATESTATUS.":".EF."<br>";
               $details_view = 0;
               echo status_select_box($this_client['client_status'],"new_status_id")."&nbsp;";
               $details_view = 1;
               echo GO_IMG;
               ?>
               </td>
               </form>
               <td valign=top>
               <?
               echo MFH."<b>".ACTION.":</b>".EF."<br>";
               echo SFB."<a href=# onClick=OpenWindow('$page?op=view_cc&db_table=$db_table&tile=$tile&id=$id[0]|$id[1]','SecureOnlinePayment','toolbar=no,location=no,status=yes,menubar=no,scrollbars=no,resizable=no,width=500,height=400')>".VIEWCC."</a>".EF."<br>";
               echo SFB."<a href=$page?op=update_cc&db_table=$db_table&tile=$tile&id=$id[0]|$id[1]>".UPDATECC."</a>".EF."<br>";
               echo SFB."<a href=$page?op=change_pw&db_table=$db_table&tile=$tile&id=$id[0]|$id[1]>".UPDATEPW."</a>".EF."<br>";
               echo SFB."<a href=$page?op=client_login&id=$id[1]>".LOGINAS." ".$this_client['client_fname']." ".$this_client['client_lname']."</a>".EF."<br>";
               ?>
               </td>
               <form method=post action=<?=$admin_page?>>
               <td valign=top>
               <input type=hidden name=op value="mail">
               <input type=hidden name=id value="<?=$this_client['client_id']?>">
               <input type=hidden name=tile value="mail">
               <input type=hidden name=step value="2">
               <input type=hidden name=email_type value="package_welcome">
               <input type=hidden name=this_status_id value="<?=$this_client['client_status']?>">
               <? echo MFH."<b>Send \"Welcome Email\":</b><br>".EF;
               $details_view = 0;
               echo SFB."Select Email Template:".EF."<br>";
               echo email_signup_select_box(NULL,"email_id")."<br>";
               echo SFB."Select Package:".EF."<br>";
               echo cp_select_box($this_client['client_id'],NULL)."&nbsp;";
               $details_view = 1;
               echo GO_IMG;
               ?>
               </td>
               </form>
              </tr>
            </table><hr>
          </td>
        </tr>
<? if ($display_client_packages) { ?>
        <tr>
          <td>
          <?
             $recursive = 1;
             $selectlimit = $limit_client_packages;
             $where     = "WHERE $id[0]=$id[1] ";
             $db_table  = "client_package";
             include("include/db_attributes.inc.php");
             echo SFB."[<a href=$page?op=form&db_table=$db_table&tile=$tile&$id[0]=$id[1]&from=client_id><b>".ADD."</b></a>] ".EF.MFH."<b>$title</b>".EF;
             echo SFB." (<a href=$page?op=mail&tile=mail&step=2&email_type=package_summary&id=$id[1]>".SENDPACKSUM."</a>)".EF."<br>";
             start_table(NULL,"100%");
             echo "<tr><td>";
             display_list($args,$select_sql,$where,$db_table,$order,$sort,$offset,$limit);
             echo "</td></tr>";
             if ($this_num_results > $selectlimit) echo "<tr><td align=center><b>".MFB."<a href=$page?op=view&db_table=$db_table&tile=$tile&where=".urlencode($where).">".VIEWALL." $this_num_results</a>".EF."</b></td></tr>";
             stop_table();
             $recursive=$recursive_sql=$this_num_results=NULL;
          ?>
          </td>
        </tr>
<? } ?>
<? if ($display_client_domains) { ?>
        <tr>
          <td>
          <?
             $recursive = 1;
             $selectlimit = $limit_client_domains;
             $where     = "WHERE $id[0]=$id[1] ";
             $db_table  = "domain_names";
             include("include/db_attributes.inc.php");
             echo SFB."[<a href=$page?op=form&db_table=$db_table&tile=$tile&$id[0]=$id[1]&from=client_id><b>".ADD."</b></a>] ".EF.MFH."<b>$title</b>".EF;
             echo SFB." (<a href=$page?op=mail&tile=mail&step=2&email_type=domain_summary&id=$id[1]>".SENDDOMSUM."</a>)".EF."<br>";
             start_table(NULL,"100%");
             echo "<tr><td>";
             display_list($args,$select_sql,$where,$db_table,$order,$sort,$offset,$limit);
             echo "</td></tr>";
             if ($this_num_results > $selectlimit) echo "<tr><td align=center><b>".MFB."<a href=$page?op=view&db_table=$db_table&tile=$tile&where=".urlencode($where).">".VIEWALL." $this_num_results</a>".EF."</b></td></tr>";
             stop_table();
             $recursive=$recursive_sql=NULL;
          ?>
          </td>
        </tr>
<? } ?>
<? if ($display_account_details) { ?>
        <tr>
          <td>
          <?
             $recursive = 1;
             $selectlimit = $limit_account_details;
             $where     = "WHERE $id[0]=$id[1] ";
             $db_table  = "account_details";
             $details_view = 1;
             include("include/db_attributes.inc.php");
             echo SFB."[<a href=$page?op=form&db_table=$db_table&tile=$tile&$id[0]=$id[1]&from=client_id><b>".ADD."</b></a>] ".EF.MFH."<b>$title</b>".EF;
             echo SFB." (<a href=$page?op=mail&tile=mail&step=2&email_type=account_details&id=$id[1]>".SENDACTDETAILS."</a>)".EF."<br>";
             start_table(NULL,"100%");
             echo "<tr><td>";
             display_list($args,$select_sql,$where,$db_table,$order,$sort,$offset,$limit);
             echo "</td></tr>";
             if ($this_num_results > $selectlimit) echo "<tr><td align=center><b>".MFB."<a href=$page?op=view&db_table=$db_table&tile=$tile&where=".urlencode($where).">".VIEWALL." $this_num_results</a>".EF."</b></td></tr>";
             stop_table();
             $recursive=$recursive_sql=NULL;
          ?>
          </td>
        </tr>
<? } ?>
<? if ($display_account_dbs) { ?>
        <tr>
          <td>
          <?
             $recursive = 1;
             $selectlimit = $limit_account_dbs;
             $where     = "WHERE $id[0]=$id[1] ";
             $db_table  = "account_dbs";
             $details_view = 1;
             include("include/db_attributes.inc.php");
             echo SFB."[<a href=$page?op=form&db_table=$db_table&tile=$tile&$id[0]=$id[1]&from=client_id><b>".ADD."</b></a>] ".EF.MFH."<b>$title</b>".EF;
             //echo SFB." (<a href=$page?op=mail&tile=mail&step=2&email_type=account_details&id=$id[1]>".SENDACTDETAILS."</a>)".EF."<br>";
             start_table(NULL,"100%");
             echo "<tr><td>";
             display_list($args,$select_sql,$where,$db_table,$order,$sort,$offset,$limit);
             echo "</td></tr>";
             if ($this_num_results > $selectlimit) echo "<tr><td align=center><b>".MFB."<a href=$page?op=view&db_table=$db_table&tile=$tile&where=".urlencode($where).">".VIEWALL." $this_num_results</a>".EF."</b></td></tr>";
             stop_table();
             $recursive=$recursive_sql=NULL;
          ?>
          </td>
        </tr>
<? } ?>
<? if ($display_account_pops) { ?>
        <tr>
          <td>
          <?
             $recursive = 1;
             $selectlimit = $limit_account_pops;
             $where     = "WHERE $id[0]=$id[1] ";
             $db_table  = "account_pops";
             $details_view = 1;
             include("include/db_attributes.inc.php");
             echo SFB."[<a href=$page?op=form&db_table=$db_table&tile=$tile&$id[0]=$id[1]&from=client_id><b>".ADD."</b></a>] ".EF.MFH."<b>$title</b>".EF;
             //echo SFB." (<a href=$page?op=mail&tile=mail&step=2&email_type=account_details&id=$id[1]>".SENDACTDETAILS."</a>)".EF."<br>";
             start_table(NULL,"100%");
             echo "<tr><td>";
             display_list($args,$select_sql,$where,$db_table,$order,$sort,$offset,$limit);
             echo "</td></tr>";
             if ($this_num_results > $selectlimit) echo "<tr><td align=center><b>".MFB."<a href=$page?op=view&db_table=$db_table&tile=$tile&where=".urlencode($where).">".VIEWALL." $this_num_results</a>".EF."</b></td></tr>";
             stop_table();
             $recursive=$recursive_sql=NULL;
          ?>
          </td>
        </tr>
<? } ?>
      </table>
    </td>
  </tr>
  <tr valign=top>
    <td colspan=2>
      <table width="100%" border=0 align=center cellspacing=0 cellpadding=4>
<? if ($display_client_invoices) { ?>
        <tr>
          <td>
          <?
             $recursive   = 1;
             $selectlimit = $limit_client_invoices;
             $where       = "WHERE $id[0]=$id[1] ORDER BY invoice_id DESC";
             $db_table    = "client_invoice";
             include("include/db_attributes.inc.php");
             echo SFB."[<a href=$page?op=form&db_table=$db_table&tile=$tile&$id[0]=$id[1]&from=client_id><b>".ADD."</b></a>] ".EF.MFH."<b>$title</b>".EF;
             echo SFB." (<a href=$page?op=mail&tile=mail&step=2&email_type=inv_summary&id=$id[1]>".SENDINVHISTORY."</a>)".EF."<br>";
             start_table(NULL,"100%");
             echo "<tr><td>";
             display_list($args,$select_sql,$where,$db_table,$order,$sort,$offset,$limit);
             echo "</td></tr>";
             if ($this_num_results > $selectlimit) echo "<tr><td align=center><b>".MFB."<a href=$page?op=view&db_table=$db_table&tile=$tile&where=".urlencode("WHERE $id[0]=$id[1] ").">".VIEWALL." $this_num_results</a>".EF."</b></td></tr>";
             stop_table();
             $recursive=$recursive_sql=NULL;
          ?>
          </td>
        </tr>
<? } ?>
<? if ($display_client_credits) { ?>
        <tr>
          <td>
          <?
             $recursive = 1;
             $selectlimit = $limit_client_credits;
             $where     = "WHERE $id[0]=$id[1] ORDER BY credit_id DESC";
             $db_table  = "client_credit";
             include("include/db_attributes.inc.php");
             echo SFB."[<a href=$page?op=form&db_table=$db_table&tile=$tile&$id[0]=$id[1]&from=client_id><b>".ADD."</b></a>] ".EF.MFH."<b>$title</b>".EF."<br>";
             start_table(NULL,"100%");
             echo "<tr><td>";
             display_list($args,$select_sql,$where,$db_table,$order,$sort,$offset,$limit);
             echo "</td></tr>";
             if ($this_num_results > $selectlimit) echo "<tr><td align=center><b>".MFB."<a href=$page?op=view&db_table=$db_table&tile=$tile&where=".urlencode("WHERE $id[0]=$id[1] ").">".VIEWALL." $this_num_results</a>".EF."</b></td></tr>";
             stop_table();
             $recursive=$recursive_sql=NULL;
          ?>
          </td>
        </tr>
<? } ?>
<? if ($display_client_notes) { ?>
        <tr>
          <td>
          <?
             $recursive   = 1;
             $selectlimit = $limit_client_notes;
             $where       = "WHERE $id[0]=$id[1] ORDER BY log_id DESC";
             $db_table    = "event_log";
             $sort        = "DESC";
             include("include/db_attributes.inc.php");
             echo SFB."[<a href=$page?op=form&db_table=$db_table&tile=$tile&$id[0]=$id[1]&from=client_id><b>".ADD."</b></a>] ".EF.MFH."<b>$title</b>".EF."<br>";
             start_table(NULL,"100%");
             echo "<tr><td>";
             display_list($args,$select_sql,$where,$db_table,$order,$sort,$offset,$limit);
             echo "</td></tr>";
             if ($this_num_results > $selectlimit) echo "<tr><td align=center><b>".MFB."<a href=$page?op=view&db_table=$db_table&tile=$tile&where=".urlencode("WHERE $id[0]=$id[1] ").">".VIEWALL." $this_num_results</a>".EF."</b></td></tr>";
             stop_table();
             $recursive=$recursive_sql=NULL;
          ?>
          </td>
        </tr>
<? } ?>
    </table>

    </td>
  </tr>
</table>
<?
stop_html();
?>
