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

## Validate that the user is an ADMIN or log them out
if (!testlogin()||!$this_admin||$this_user)  { Header("Location: http://$standard_url?op=logout"); exit; }

if ($this_admin[admin_level]<9&&($db_table=="config"||$db_table=="admin"||$db_table=="authnet_batch"))
{
    start_short_html($title);
    echo "&nbsp;&nbsp;&nbsp;&nbsp;<b>".MFB.ACCESSDENIED.EF."</b>&nbsp;&nbsp;&nbsp;&nbsp;";
    stop_short_html();
    exit;
}
/* ---- VALIDATE DELETE WITH SPECIAL PASSWORD ----*/
        // validate admin password
        $result = mysql_query("SELECT * FROM admin WHERE admin_username='".$this_admin["admin_username"]."' AND admin_password='".md5(strip_tags($password))."'",$dbh) or die (mysql_error());
        if (!$result || mysql_num_rows($result) != 1) $oops = "[".ERROR."] ".INVALIDPASSWORD."!<br>";
           if(isset($vars)&&!$oops){
           $vars=explode("|",$vars);

           $db_table=$vars[0];
           validate_table($db_table,1); if(isset($error)) return;
           ${$vars[1]}=$vars[2];

           include("include/db_attributes.inc.php");
           for($i=0;$i<=count($delete_sql);$i++){
            $result = mysql_query($delete_sql[$i],$dbh);
           }
           if ($uri) {
                $url = "$page?$uri&".session_id();
           } elseif ($session_from) {
                $url = "$page?$session_from&".session_id();
           } else {
               switch ($from) {
                      case package_admin: $url = "$page?op=menu&tile=package&".session_id(); break;
                      default:            $url = "$page?op=view&db_table=$vars[0]&tile=$tile&".session_id(); break;
               }
           }
           Header("Location: $url");
        } else {
           start_html();
           admin_heading($tile);
           start_table(VERIFYDEL,$a_tile_width);
               echo "<tr><td align=center>".SFB.ACCESSDENIED."!".EF."</td></tr>";
           stop_table();
           stop_html();
           return;
        }
?>