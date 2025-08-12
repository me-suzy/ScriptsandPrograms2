<?php
session_start();
header("Cache-control: private");
$table='users'; // mysql table name
include ("header.php");
// check_login; will go to login if admin authentication enabled in config.php
$admin_check = 1;
include ('interface_creator/check_login.php');
if ($enable_admin_authentication ===1 and $current_user_is_administrator ===0)
{
 // get asked for current url
 $_SERVER['FULL_URL'] = 'http';
 if($_SERVER['HTTPS']=='on'){$_SERVER['FULL_URL'] .=  's';}
 $_SERVER['FULL_URL'] .=  '://';
 if($_SERVER['SERVER_PORT']!='80'){$_SERVER['FULL_URL'] .=  $_SERVER['HTTP_HOST'].':'.$_SERVER['SERVER_PORT'].$_SERVER['SCRIPT_NAME'];}
 else{$_SERVER['FULL_URL'] .=  $_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];}
 if($_SERVER['QUERY_STRING']>' '){$_SERVER['FULL_URL'] .=  '?'.$_SERVER['QUERY_STRING'];}
 // end get url
 header ('Location: '.$site_url.$dadabik_login_file.'?function=check_admin_login&go_to=('.rawurlencode($_SERVER['FULL_URL']).')');
 die();
}
//
$date = date("l, F j, Y");
echo ('<span style="color:#dcdcdc;">'.$log_status);
if (!($all_affect_items == "no") or ($all_affect_items == "no" and $client == "allowed")){
echo ('
<a>Add </a><a href="'.$site_url.'index_short.php?function=show_insert_form&amp;table_name=item" onclick="return popitup(\''.$site_url.'index_short.php?function=show_insert_form&amp;table_name=item\')">item</a> / <a href="'.$site_url.'index_short.php?function=show_insert_form&amp;table_name=vendor" onclick="return popitup(\''.$site_url.'index_short.php?function=show_insert_form&amp;table_name=vendor\')">vendor</a> || <a href="orders.php">View/adjust past orders</a> / <a href="vendors.php">vendors</a> || ');}
echo ('<a href="help/help.htm" onclick="return popitup(\'help/help.htm\')">Help</a> || <a>'.$date.'</a></span></p></div>
<div style="padding-left: 5px;">');
echo ('
<table width="750" summary="top" cellpadding="5" cellspacing="1" style="background-color:#efefef; border:0;"><tr><td><b>User management</b><br /><br />');
// new user
echo ('<form name="new_users_form" id="new_users_form" action="interface_creator/index_short.php" method="GET" target="poppedfirst" onsubmit="popitup(\'interface_creator/index_short.php\')"><input type="hidden" name="table_name" id="table_name" value="'.$users_table_name.'" /><input type="hidden" name="function" id="function" value="show_insert_form" /><input type="submit" name="none" id="none" value="Create new user account" /></form>');
// user edit etc
echo ('<form name="users_form" id="users_form" action="interface_creator/index_short.php" method="GET" target="poppedfirst" onsubmit="popitup(\'interface_creator/index_short.php\')"><input type="hidden" name="where_field" id="where_field" value="ID_user" /><input type="hidden" name="table_name" id="table_name" value="'.$users_table_name.'" /><p>Choose a user account to edit</p><select name="where_value" id="where_value" single="single">');
// get user list
$query = "SELECT `ID_user`, `name`, `group`, `username`, `status` FROM `".$users_table_name."` ORDER BY `group`, `name`"; 
$result = mysql_query($query);
$user_options = ""; 
while ($row=mysql_fetch_array($result)) {  
$user_options.="<option value=\"" . $row[0] . "\">" . $row[1] . " (" . $row[3] . ") " . $row[2] . " - " . $row[4] . '</option>'; }
echo ( $user_options . '</select><input type="submit" name="function" id="function" value="details" /><input type="submit" name="function" id="function" value="edit" /><input type="submit" name="function" id="function" value="delete" onclick="return confirm(\'Are you sure?\')" /></form>');
// Excel export options
echo ('<form action="export.php" method="POST">
<select single="single" name="parameter" id="parameter">
<option value="Excel llll  ORDER BY '.$users_table_username_field.'">Export user account data in Excel format, or...</option>
<option value="CSV llll  ORDER BY '.$users_table_username_field.'">... in CVS format</option>');
echo ('</select>');
   // hidden values to pass the mysql query and table name
 echo ('
<input type="hidden" name="table" id="table" value="`'.$users_table_name.'`" />
<input type="submit" name="export" id="export" value="Export" /><a href="help/help.htm#export" onclick="return popitup(\'help/help.htm#export\')">?</a>
 </form>');
// end export options
echo ('<p>If you have a fully-enabled user-based system (see \'Security / authorization\' below) and need to re-assign record-ownerships, go to <a href="interface_creator/admin.php#id_user">the Interface creator &raquo;</a></p></td></tr></table><br />');
echo ('<table width="750" summary="top" cellpadding="5" cellspacing="1" style="background-color:#efefef; border:0;"><tr><td><b>Interface creator</b><p>The Interface creator section of OrderSys is its back-end. If you modify the MySQL tables, or add new ones or delete existing ones, you have to \'put in\' the changes in Interface creator. In addition, the Interface creator is used if you need to modify the forms that are used in OrderSys (e.g., to make certain fields \'required\', or to change the displayed options in a menu).<br /><br /><a href="interface_creator/admin.php">The Interface creator page &raquo;</a></p></td></tr></table><br />');
echo ('<table width="750" summary="top" cellpadding="5" cellspacing="1" style="background-color:#efefef; border:0;"><tr><td><b>Current security / authorization settings</b><p>These settings can be changed by editing the <i>config.php</i> setting file in the OrderSys web folder using a plain text or code editor.<br /><br /><table width="650" summary="top" cellpadding="5" cellspacing="1" style="background-color:#efefef; border:0;"><tr style="background-color:#ffffff; vertical-align:top;"><td style="background-color:#ffffff; vertical-align:top;">Login needed for administration ($enable_admin_authentication)</td><td style="background-color:#ffffff; vertical-align:top;">');
if ($enable_admin_authentication ===1){echo ('Yes');}else{echo ('No');}
echo ('</td><td style="background-color:#ffffff; vertical-align:top;">This page and the admin section of the Interface creator section are protected by a login system if enabled. The login account must be an administrative one.</td></tr></td></tr><tr style="background-color:#ffffff;"><td style="background-color:#ffffff; vertical-align:top;">Login needed for usage ($enable_authentication)</td><td style="background-color:#ffffff; vertical-align:top;">');
if ($enable_authentication ===1){echo ('Yes');}else{echo ('No');}
echo ('</td><td style="background-color:#ffffff; vertical-align:top;">All pages are protected if enabled. Users must log in.</td></tr></td></tr><tr style="background-color:#ffffff;"><td style="background-color:#ffffff; vertical-align:top;">Users see details of / edit / delete only the records they \'own\' ($enable_ browse / update / delete _authorization)</td><td style="background-color:#ffffff; vertical-align:top;">');
if ($enable_browse_authorization ===1){echo ('Yes / ');}else{echo ('No / ');}
if ($enable_update_authorization ===1){echo ('Yes / ');}else{echo ('No / ');}
if ($enable_delete_authorization ===1){echo ('Yes / ');}else{echo ('No / ');}
echo ('</td><td style="background-color:#ffffff; vertical-align:top;">With \'Login needed for usage\' enabled (the parameter above), a user may view details of and/or affect only those records that are \'owned\' by him/her.</td></tr><tr style="background-color:#ffffff;"><td style="background-color:#ffffff; vertical-align:top;">IP address-based restriction on display of tables ($all_see_tables)</td><td style="background-color:#ffffff; vertical-align:top;">');
if ($all_see_tables == 'no'){echo ('Yes');}else{echo ('No');}
echo ('</td><td style="background-color:#ffffff; vertical-align:top;">If user is not at an allowed IP address, the tables will not be displayed.</td></tr><tr style="background-color:#ffffff;"><td style="background-color:#ffffff; vertical-align:top;">IP address-based restriction on display of links for adding or affecting items ($all_affect_items)</td><td style="background-color:#ffffff; vertical-align:top;">');
if ($all_affect_items == 'no'){echo ('Yes');}else{echo ('No');}
echo ('</td><td style="background-color:#ffffff; vertical-align:top;">If user is not at an allowed IP address, the links will not be displayed.</td></tr><tr style="background-color:#ffffff;"><td style="background-color:#ffffff; vertical-align:top;">IP address-based restriction on orders being added to order history table ($all_order_history)</td><td style="background-color:#ffffff; vertical-align:top;">');
if ($all_order_history == 'no'){echo ('Yes');}else{echo ('No');}
echo ('</td><td style="background-color:#ffffff; vertical-align:top;">If user is not at an allowed IP address, any generated order will not be added to the order history table.</td></tr><tr style="background-color:#ffffff;"><td style="background-color:#ffffff; vertical-align:top;">IP address-based restriction on expenditure display ($all_see_expenditure)</td><td style="background-color:#ffffff; vertical-align:top;">');
if ($all_see_expenditure == 'no'){echo ('Yes');}else{echo ('No');}
echo ('</td><td style="background-color:#ffffff; vertical-align:top;">If user is not at an allowed IP address, expenditure will not be displayed</td></tr></table><br />Note that some of these settings may work in parallel or in series. You also may have other restrictions, such as <a href="http://stanxterm.aecom.yu.edu/wiki/index.php?page=Web_serving_-_access_control">.htaccess</a> file-based ones, in affect.</td></tr></table><br />');
echo ('<table width="750" summary="top" cellpadding="5" cellspacing="1" style="background-color:#efefef; border:0;"><tr><td><b>Help</b><p>General administrative information is available in the <a href="help/readme.txt">readme</a> file that came with this installation. The Interface Creator section has its own administrative information in a <a href="interface_creator/readme.txt">readme</a> file and on the admin <a href="interface_creator/help.htm" onclick="window.open(this.href); return false;" onkeypress="window.open(this.href); return false;">help</a> page. Some helpful guidelines are also provided in the <i>config.php</i> setting file in the OrderSys web folder.</p><p>For latest version of OrderSys, check the developer\'s site at <a href="http://stanxterm.aecom.yu.edu/secondary/ordering/index.php">http://stanxterm.aecom.yu.edu/secondary/ordering/index.php</a>.</p></td></tr></table>');
include ("footer.php");
?>