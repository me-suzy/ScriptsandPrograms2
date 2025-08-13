<?php
// +----------------------------------------------------------------------+
// | ModernBill [TM] .:. Client Billing System                            |
// +----------------------------------------------------------------------+
// | Copyright (c) 2001-2002 ModernGigabyte, LLC                          |
// +----------------------------------------------------------------------+
// | This source file is subject to the ModernBill End User License       |
// | Agreement (EULA), that is bundled with this package in the file      |
// | LICENSE, and is available at through the world-wide-web at           |
// | http://www.modernbill.com/extranet/LICENSE.txt                       |
// | If you did not receive a copy of the ModernBill license and are      |
// | unable to obtain it through the world-wide-web, please send a note   |
// | to license@modernbill.com so we can email you a copy immediately.    |
// +----------------------------------------------------------------------+
// | Authors: ModernGigabyte, LLC <info@moderngigabyte.com>               |
// | Support: http://www.modernsupport.com/modernbill/                    |
// +----------------------------------------------------------------------+
// | ModernGigabyte and ModernBill are trademarks of ModernGigabyte, LLC. |
// +----------------------------------------------------------------------+

## Must be included ONLY once!
include_once("include/functions.inc.php");

## Validate that the user
if (!testlogin()||!$this_user)  { Header("Location: http://$standard_url?op=logout"); exit; }

   switch ($tile) {
           case myinfo:
                $db_table="client_info";
                $where="WHERE client_id=$this_user[0]";
                $edit_link="<tr><td colspan=2 valign=top align=center>".SFB."<a href=$page?op=form&tile=myinfo>".EDIT_IMG."</a>".EF."</td></tr>";
           break;

           case myinvoices:
                $db_table="client_invoice";
                $id=explode("|",$id);
                $where="WHERE $id[0]=$id[1]";
           break;

           case mypackages:
                $db_table = ($db_table) ? $db_table : "client_package"; ;
                $id=explode("|",$id);
                $where="WHERE $id[0]=$id[1]";
           break;

           case mydomains:
                $db_table="domain_names";
                $id=explode("|",$id);
                $where="WHERE $id[0]=$id[1]";
           break;

           case package:
                $db_table="package_type";
                $id=explode("|",$id);
                $where="WHERE p.pack_id = c.pack_id AND p.pack_id = $id[1] AND c.client_id = $this_user[0]";
                ## VORTECH TYPE1
                $this_vortech_config=mysql_fetch_array(mysql_query("SELECT * FROM config WHERE config_type = 'vortech_type1' "));
                $table_width           = $this_vortech_config["config_35"];
                $contact_final_width   = $this_vortech_config["config_36"];
                $outerborder           = $this_vortech_config["config_37"];
                $innerborder           = $this_vortech_config["config_38"];
                $headercolor           = $this_vortech_config["config_39"];
                $headertextcolor       = $this_vortech_config["config_40"];
                $tablebgcolor          = $this_vortech_config["config_41"];
                $tablebgcolor2         = $this_vortech_config["config_42"];

                $package_type1  = "<table cellpadding=0 cellspacing=0 border=0 bgcolor=$outerborder align=center width=400>";
                $package_type1 .= "<tr><td>";
                $package_type1 .= "<table cellpadding=3 cellspacing=1 border=0 width=100%>";
                $package_type1 .= "<tr bgcolor=$headercolor><td align=center><font color=$headertextcolor><b>".PACKAGE."</b></font></td>
                                       <td align=center><font color=$headertextcolor><b>".PRICE."</b></font></td>
                                       <td align=center><font color=$headertextcolor><b>".SETUP."</b></font></td>
                                       <td align=center><font color=$headertextcolor><b>".BILLINGCYCLE."</b></font></td>
                                   </tr>";
                if (!$dbh) dbconnect();
                $result = mysql_query("SELECT DISTINCT p.pack_id,p.pack_name,p.pack_price,p.pack_setup,c.cp_billing_cycle FROM package_type p, client_package c $where");
                while(list($pack_id,$pack_name,$pack_price,$pack_setup,$cp_billing_cycle) = mysql_fetch_array($result))
                {
                      $package_type1 .= "<tr><td bgcolor=$tablebgcolor><b>$pack_name</b></td>
                                             <td bgcolor=$tablebgcolor align=center>".display_currency(split_price($pack_price,$cp_billing_cycle,"price"))."/".MONTHLY."</td>
                                             <td bgcolor=$tablebgcolor align=center>".display_currency(split_price($pack_setup,$cp_billing_cycle,"setup"))."/".ONETIME."</td>
                                             <td bgcolor=$tablebgcolor align=center>".$cycle_types[$cp_billing_cycle]."</td>
                                         </tr>";

                      $result2 = mysql_query("SELECT feature_name, feature_comments FROM package_feature WHERE pack_id='$pack_id' ORDER BY feature_name");
                      $package_type1 .= "<tr><td colspan=4 bgcolor=$tablebgcolor><ul>";
                      while(list($feature_name, $feature_comments) = mysql_fetch_array($result2))
                      {
                            $package_type1 .= "<li><b>$feature_name:</b> $feature_comments</li>";
                      }

                      $package_type1 .= "</ul></td></tr>";
                      $there_are_1_packages = TRUE;
                }

                if (!$there_are_1_packages) $package_type1 .= "<tr><td colspan=3 align=center>".NONE."</td></tr>";
                $package_type1 .= "</table></td></tr></table><br>";
           break;
    }

include("include/db_attributes.inc.php");
validate_table($db_table,1); if(isset($error)) return;
$sql="SELECT * FROM $db_table $where";
if($debug)echo SFB.$sql.EF."<br>";
addslashes($result = mysql_query($sql,$dbh));

start_html();
user_heading($tile);
start_table(NULL,$u_tile_width,"center","#999999");
if ($db_table=="package_type") {
    echo "<tr><td colspan=2>";
    echo "$package_type1";
    echo "</td></tr>";
} else {
    build_form($args,$result);
    if($edit_link) echo $edit;
}
stop_table();
stop_html();
?>