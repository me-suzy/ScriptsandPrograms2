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
##
## [DO NOT MODIFY/REMOVE BELOW]
##
if ($DIR && ($HTTP_COOKIE_VARS[DIR] || $HTTP_POST_VARS[DIR] || $HTTP_GET_VARS[DIR] || $_COOKIE[DIR] || $_POST[DIR] || $_GET[DIR])) {
    $ip   = $HTTP_SERVER_VARS[REMOTE_ADDR];
    $host = gethostbyaddr($ip);
    $url  = $HTTP_SERVER_VARS["HTTP_HOST"].$HTTP_SERVER_VARS["REQUEST_URI"];
    $admin= ($GLOBALS[SERVER_ADMIN]) ? $GLOBALS[SERVER_ADMIN] : "security@your.server.com";
    $body = "IP:\t$ip\nHOST:\t$host\nURL:\t$url\nVER:\t$version\nTIME:\t".date("Y/m/d: h:i:s")."\n";
    @mail($admin,"Possible breakin attempt.",$body,"From: $admin\r\n");
    print str_repeat(" ", 300)."\n";
    flush();
    ?>
    <html><head><body>
    <center><h3><tt><b><font color=RED>Security violation from: <?=$ip?> @ <?=$host?></font></b></tt></h3></center>
    <hr>
    <pre><? @system("traceroute ".escapeshellcmd($ip)." 2>&1"); ?></pre>
    <hr>
    <center><h2><tt><b><font color=RED>The admin has been alerted.</font></b></tt></h2></center>
    </body></html>
    <?
    exit;
}

$this_config_type = "theme_newtop";

########################################################
####### DATABASE CONFIG -- DO NOT MODIFY START #########
########################################################
$dbh=mysql_pconnect($locale_db_host,$locale_db_login,$locale_db_pass) or die("Problem with dB connection!");
mysql_select_db($locale_db_name,$dbh) or die("Problem with dB connection!");
$this_theme_default_config=mysql_fetch_array(mysql_query("SELECT * FROM config WHERE config_type='$this_config_type'"));
## DEFINE ALTERNATING COLORS IN TABLES
$cell_color_1    = $this_theme_default_config["config_1"];
$cell_color_2    = $this_theme_default_config["config_2"];
//$head_bgcolor    = "FFFFFF"; # <-- Do Not Change
$cellspacing     = $this_theme_default_config["config_4"];
$cellpadding     = $this_theme_default_config["config_5"];
## DEFINE TABLE WIDTH FOR THE TILE NAVIGATION
$a_tile_width    = $this_theme_default_config["config_6"];
$u_tile_width    = $this_theme_default_config["config_7"];
## DEFINE TILE AND BACKGROUND COLORS
$default_bgcolor = $this_theme_default_config["config_8"];
$active_bgcolor  = $this_theme_default_config["config_9"];
$title           = $this_theme_default_config["config_10"];
$body_color      = $this_theme_default_config["config_11"];
$login_color     = $this_theme_default_config["config_13"];
$bgcolor_border_box = $this_theme_default_config["config_22"];
## DEFINE IMAGES & BUTTONS
$background      = $DIR.$this_theme_default_config["config_25"];
$logo_img        = $DIR.$this_theme_default_config["config_12"];
$delete_image    = $DIR.$this_theme_default_config["config_14"];
$edit_image      = $DIR.$this_theme_default_config["config_15"];
$desc_image      = $DIR.$this_theme_default_config["config_16"];
$asc_image       = $DIR.$this_theme_default_config["config_17"];
define(EDIT_IMG,  "<img src=$edit_image   border=0 ".GetImageSize($edit_image)." alt=\"".EDIT."\">");
define(DELETE_IMG,"<img src=$delete_image border=0 ".GetImageSize($delete_image)." alt=\"".DELETE_t."\">");
define(DESC_IMG,  "<img src=$desc_image   border=0 ".GetImageSize($desc_image).">");
define(ASC_IMG,   "<img src=$asc_image    border=0 ".GetImageSize($asc_image).">");
## DEFINE FONTS
$theme_style     = $this_theme_default_config["config_50"];
$reg_face        = $this_theme_default_config["config_18"];
$lrg_size        = $this_theme_default_config["config_19"];
$small_size      = $this_theme_default_config["config_20"];
$med_size        = $this_theme_default_config["config_21"];
$lfh_color       = $this_theme_default_config["config_23"];
$white_color     = $this_theme_default_config["config_3"];
$header_font_color = $this_theme_default_config["config_24"];
define(TFB,"<font face=Arial size=1>");
define(SFB,"<font face=\"$reg_face\" size=$small_size>");
define(SFW,"<font face=\"$reg_face\" color=$white_color size=$small_size>");
define(MFB,"<font face=\"$reg_face\" size=$med_size>");
define(MFHR,"<font face=\"$reg_face\" color=$header_font_color size=$med_size>");
define(MFH,"<font face=\"$reg_face\" color=$lfh_color size=$med_size>");
define(LFB,"<font face=\"$reg_face\" size=$lrg_size>");
define(LFH,"<font face=\"$reg_face\" color=$lfh_color size=$lrg_size>");
define(EF,"</font>");
########################################################
####### DATABASE CONFIG -- DO NOT MODIFY END ###########
########################################################



########################################################
######## HTML CONFIG -- YOU MAY MODIFY BELOW ###########
########################################################

## DEFINE FORM SUBMIT BUTTONS
define(SUBMIT_IMG,"<input type=submit name=Submit value=\"".SUBMIT."\">");
define(SEARCH_IMG,"<input type=submit name=Submit value=\"".SEARCH."!\">");
define(SEND_IMG,  "<input type=submit name=Submit value=\"".SEND."!\">");
define(CONT_IMG,  "<input type=submit name=Submit value=\"".CONTINUETOCOMPOSE."!\">");
define(CONT2_IMG, "<input type=submit name=Submit value=\"".CONTINUE_t."!\">");
define(CANCEL_IMG,"<input type=submit name=Submit value=\"".CANCEL."!\">");
define(CHARGE_IMG,"<input type=submit name=Submit value=\"".CHARGEIT."!\">");
define(GO_IMG,    "<input type=submit name=Submit value=\"".GO."!\">");

## DEFINE HTML
function start_html($title="ModernBill .:. Client Billing System",$head=NULL,$body_color=NULL,$body_vars="leftmargin=0 marginwidth=0 topmargin=0 marginheight=0")
{
         GLOBAL $start_timer,
                $submitted,
                $this_admin,
                $this_user,
                $title,
                $tile,
                $body_color,
                $theme_style,
                $background,
                $logo_img,
                $standard_url,
                $login_page,
                $https,
                $secure_url,
                $page,
                $version,
                $disable_right_click;

         $start_timer=get_microtime();
         ?>
         <? if ($this_admin&&!$this_user) {
                $welcome_name = $this_admin["admin_realname"];
                $admin_login = TRUE;
         } elseif($this_user) {
                $welcome_name = $this_user["client_fname"]." ".$this_user["client_lname"];
                $user_login = TRUE;
         } else {
                echo "<pre>".HACKERALERT."</pre>";
                custom_error_handler(1024,HACKERALERT,$errFile,$errLine,$HTTP_SERVER_VARS);
                exit();
         }
         ?>
         <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2//EN">
         <HTML dir="<?=TEXTDIRECTION?>">
         <HEAD>
         <TITLE><?=$title?></TITLE>
         <META HTTP-EQUIV="Content-Type" CONTENT="text/html;" CHARSET="<?=CHARSET?>">

         <!-- ModernBill TM .:. Client Billing System .:. Version <?=$version?> -->
         <!-- Copyright © 2001,2002 .:. ModernGigabyte, LLC .:. All Rights Reserved. -->

         <SCRIPT LANGUAGE="JavaScript">
         <!--
         var submitted = false;

             function submitCheck() {
                      if (submitted) {
                          alert("<?=ALREADYCLICKED?>");
                          return false;
                      } else {
                          submitted = true;
                          return true;
                      }
             }

             function OpenWindow(theURL,winName,features) {
                      window.open(theURL,winName,features);
             }

             function goFocus() {
                      document.login.username.focus();
             }

             function printWindow(){
                      bV = parseInt(navigator.appVersion)
                      if (bV >= 4) window.print()
             }

         var myHeight = 200;
         var isResizable = true;

             function createTarget(form) {
                      _target = form.target;
                      _colon = _target.indexOf(":");
                      if(_colon != -1) {
                         form.target = _target.substring(0,_colon);
                         form.args = _target.substring(_colon+1);
                      } else if(typeof(form.args)=="undefined") {
                         form.args = "";
                      }
                      if(form.args.indexOf("{")!=-1) {
                         _args = form.args.split("{");
                         form.args = _args[0];
                         for(var i = 1; i < _args.length;i++) {
                             _args[i] = _args[i].split("}");
                             form.args += eval(_args[i][0]) + _args[i][1];
                         }
                      }
                      form.args = form.args.replace(/ /g,"");
                      _win = window.open('',form.target,form.args);
                      if(typeof(focus)=="function") {
                         _win.focus();
                      }
                      return true;
             }

         <? if ($disable_right_click) { ?>
         var message="Function Disabled!";

             function clickIE() {
                      if (document.all) {
                          alert(message);
                          return false;
                      }
             }

             function clickNS(e) {
                      if (document.layers||(document.getElementById&&!document.all)) {
                          if (e.which==2||e.which==3) {
                              alert(message);
                              return false;
                          }
                      }
             }

             if (document.layers) {
                 document.captureEvents(Event.MOUSEDOWN);
                 document.onmousedown=clickNS;
             } else {
                 document.onmouseup=clickNS;
                 document.oncontextmenu=clickIE;
             }

         document.oncontextmenu=new Function("return false")
         <? } ?>
         // -->
         </SCRIPT>

         <STYLE>
         <!--
         <?=$theme_style."\n"?>
         //-->
         </STYLE>
         <?
         $background = ($background) ? "background=$background" : NULL ;
         echo "\n$head\n</HEAD>\n<BODY bgcolor=\"$body_color\" $background $body_vars $javascript>\n";
}

function start_short_html($title="ModernBill .:. Client Billing System",$head=NULL,$body_color=NULL,$body_vars="leftmargin=0 marginwidth=0 topmargin=0 marginheight=0")
{
         GLOBAL $start_timer,
                $submitted,
                $this_admin,
                $title,
                $tile,
                $body_color,
                $theme_style,
                $background,
                $logo_img,
                $standard_url,
                $login_page,
                $https,
                $secure_url,
                $page,
                $is_popup,
                $version,
                $disable_right_click;

         $start_timer=get_microtime();
         ?>
         <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2//EN">
         <HTML dir="<?=TEXTDIRECTION?>">
         <HEAD>
         <TITLE><?=$title?></TITLE>
         <META HTTP-EQUIV="Content-Type" CONTENT="text/html;" CHARSET="<?=CHARSET?>">

         <!-- ModernBill TM .:. Client Billing System .:. Version <?=$version?> -->
         <!-- Copyright © 2001,2002 .:. ModernGigabyte, LLC .:. All Rights Reserved. -->

         <SCRIPT LANGUAGE="JavaScript">
         <!--
         var submitted = false;

             function submitCheck() {
                      if (submitted) {
                          alert("<?=ALREADYCLICKED?>");
                          return false;
                      } else {
                          submitted = true;
                          return true;
                      }
             }

             function OpenWindow(theURL,winName,features) {
                      window.open(theURL,winName,features);
             }

             function goFocus() {
                      document.login.username.focus();
             }

             function printWindow(){
                      bV = parseInt(navigator.appVersion)
                      if (bV >= 4) window.print()
             }

         var myHeight = 200;
         var isResizable = true;

             function createTarget(form) {
                      _target = form.target;
                      _colon = _target.indexOf(":");
                      if(_colon != -1) {
                         form.target = _target.substring(0,_colon);
                         form.args = _target.substring(_colon+1);
                      } else if(typeof(form.args)=="undefined") {
                         form.args = "";
                      }
                      if(form.args.indexOf("{")!=-1) {
                         _args = form.args.split("{");
                         form.args = _args[0];
                         for(var i = 1; i < _args.length;i++) {
                             _args[i] = _args[i].split("}");
                             form.args += eval(_args[i][0]) + _args[i][1];
                         }
                      }
                      form.args = form.args.replace(/ /g,"");
                      _win = window.open('',form.target,form.args);
                      if(typeof(focus)=="function") {
                         _win.focus();
                      }
                      return true;
             }

         <? if ($disable_right_click) { ?>
         var message="Function Disabled!";

             function clickIE() {
                      if (document.all) {
                          alert(message);
                          return false;
                      }
             }

             function clickNS(e) {
                      if (document.layers||(document.getElementById&&!document.all)) {
                          if (e.which==2||e.which==3) {
                              alert(message);
                              return false;
                          }
                      }
             }

             if (document.layers) {
                 document.captureEvents(Event.MOUSEDOWN);
                 document.onmousedown=clickNS;
             } else {
                 document.onmouseup=clickNS;
                 document.oncontextmenu=clickIE;
             }

         document.oncontextmenu=new Function("return false")
         <? } ?>

         // -->
         </SCRIPT>

         <STYLE>
         <!--
         <?=$theme_style."\n"?>
         //-->
         </STYLE>
         <?
         $background = ($background) ? "background=$background" : NULL ;
         echo "\n$head\n</HEAD>\n<BODY bgcolor=\"$body_color\" $background $body_vars $javascript>\n";
         $logo_size = GetImageSize($logo_img);
         print str_repeat(" ", 300) . "\n";
         flush();
         echo "<span id=\"processing\">&nbsp;&nbsp;&nbsp;".SFW.PROCESSING." ...".EF."</span>";
         ?>
             <br>
             <table cellpadding=0 cellspacing=0 border=0 bgColor=#ffffff align=center COLS=2>
              <TR>
               <td>
                <table width=100% cellpadding=0 cellspacing=0 border=0 bgColor=#ffffff COLS=3>
                 <TR>
                  <td align=left valign=top><img src=images/upper_left.gif></td>
                  <td align=center>&nbsp;</td>
                  <td align=right valign=top><? if(!$is_popup) { ?><a href="<?=$page?>?op=menu&tile=dashboard"><img src=images/icons/home_round.gif border=0 align=middle alt="<?=HOME?>"></a>&nbsp;<a href="<?=$https."://".$secure_url.$login_page?>?op=logout"><img src=images/icons/x_blue.jpg border=0 align=middle alt="<?=EXIT_t?>"></a><? } ?>&nbsp;<img src=images/upper_right.gif></td>
                 </tr>
                </table>
               </td>
              </tr>
              <tr><td valign=top>
         <?
}

function stop_short_html($toggle=1)
{
         GLOBAL $start_timer,
                $submitted,
                $this_admin,
                $title,
                $body_color,
                $theme_style,
                $background,
                $logo_img,
                $standard_url,
                $login_page,
                $https,
                $secure_url,
                $page,
                $version,
                $active_bgcolor,
                $default_bgcolor,
                $start_timer,
                $is_popup,
                $HTTP_SERVER_VARS,
                $op;
         if ($op!="form"&&$op!="form_response"&&$op!="delete"&&$op!="delete_response"&&!$is_popup) {
             $uri = $HTTP_SERVER_VARS["QUERY_STRING"];
         }
         ?>
         </td>
         </tr>
         <TR>
         <td>
         <table width=100% cellpadding=0 cellspacing=0 border=0 bgColor=#ffffff COLS=3>
         <TR>
         <td align=left valign=top><img src=images/lower_left.gif></td>
         <td align=center><?=SFB?><? if(!$is_popup) { go_back(); echo " | "; } ?><b><a href="javascript:printWindow()"><?=PRINTSCREEN?></a></b><?=EF?></td>
         <td align=right valign=top><img src=images/lower_right.gif></td>
         </tr>
         </table>
         </td>
         </tr>
         </table>
         <br>
         <? echo "<script>processing.style.display='none'</script>"; ?>
         <? if ($debug) { ?><center><h1><?=$uri?></h1></center><? } ?>
         </BODY></HTML>
         <?
}

function stop_html($toggle=1)
{
         GLOBAL $start_timer,
                $submitted,
                $this_admin,
                $title,
                $body_color,
                $theme_style,
                $background,
                $logo_img,
                $standard_url,
                $login_page,
                $https,
                $secure_url,
                $page,
                $version,
                $this_user,
                $active_bgcolor,
                $default_bgcolor,
                $start_timer,
                $HTTP_SERVER_VARS,
                $op,
                $db_table,
                $uri;
         if ($op=="form"&&$db_table=="client_info") {
             $uri = NULL;
         } elseif ($op!="form"&&$op!="form_response"&&$op!="delete"&&$op!="delete_response"&&!$is_popup) {
             $uri = $HTTP_SERVER_VARS["QUERY_STRING"];
         }
         ?>
         <br clear=all>
         <center><?=SFB?><?=go_back();?> | <b><a href="javascript:printWindow()"><?=PRINTSCREEN?></a></b><?=EF?></center>
         <br clear=all>
         <?
         //
         // DO NOT REMOVE THE COPYWRITE NOTICE
         // IT ONLY DISPLAYS IN THE ADMIN INTERFACE
         //
         ?>
         <? if ($toggle&&!$this_user) { ?>
           <center>
             <form method=post action=<?=$page?>?op=view&tile=<?=$tile?>>
             <input type=hidden name=search value=1>
             <input type=hidden name=db_table value=client_info>
             <?=search_select_box();?>&nbsp;<input type=text name=query size=15 maxlength=25>&nbsp;<?=GO_IMG?>
             </form>
           <br><?=SFW."ModernBill <SUP><SMALL>TM</SMALL></SUP> .:. Client Billing System .:. Version $version<br>Copyright &copy; 2001,2002 .:. ModernGigabyte, LLC .:. All Rights Reserved.<br>T:".sprintf("%.3f",abs(get_microtime()-$start_timer)).EF?>
           <br>
           <a href=http://<?=$standard_url?>><image src=<?=$logo_img?> width=157 border=0></a>
           <br>
           </center>
         <? } ?>
         <? echo "<script>processing.style.display='none'</script>"; ?>
         <? if ($debug) { ?><center><h1><?=$uri?></h1></center><? } ?>
         </BODY>
         </HTML>
         <?
}


function start_box($title=NULL,$width="100%",$align="center")
{
         GLOBAL $bgcolor_border_box;
         $bgcolor_border_box = ($bgcolor_border_box) ? $bgcolor_border_box : "#3399CC";
         $bgcolor_cell = ($bgcolor_cell) ? $bgcolor_cell : "#FFFFFF";
         ?>
         <table width=<?=$width?> align=<?=$align?> border=0 cellpadding=0 cellspacing=0 COLS=1>
         <? if ($title) { ?><tr bgcolor=<?=$bgcolor_border_box?>><td align=center><b><?=MFHR.$title.EF?></b></td></tr> <? } ?>
         <tr bgcolor=<?=$bgcolor_cell?>>
         <td>
         <table width=100% border=0 cellpadding=0 cellspacing=0 COLS=1>
         <tr>
         <td valign=top bgcolor=<?=$bgcolor_cell?>>
         <table width=100% align=center border=0 cellspacing=0 cellpadding=0 COLS=2>
         <tr>
         <td bgcolor=CCCCCC width=12><img src=images/icons/12_space.gif border=0></td>
         <td bgcolor=FFFFFF>
         <?
}

function stop_box()
{
         ?>
         </TD></TR></TABLE>
         </TD></TR></TABLE>
         </TD></TR></TABLE>
         <?
}

function start_table($title=NULL,$width="100%",$align="center",$bgcolor="#FFFFFF")
{
         GLOBAL $cellspacing, $cellpadding;
         ?>
         <TABLE cellSpacing=0 cellPadding=0 width=<?=$width?> align=<?=$align?> bgcolor=<?=$bgcolor?> border=0 COLS=1>
         <TR>
         <? if ($title) { ?> <TD align=center><b><?=LFH.$title.EF?></b></TD></TR><TR><TD> <? } else { ?> <td> <? } ?>
         <TABLE cellSpacing=<?=$cellspacing?> cellPadding=<?=$cellpadding?> bgColor=#ffffff width=100% border=0>
         <?
}

function stop_table()
{
         ?>
         </TABLE>
         </TD></TR>
         </TABLE>
         <?
}

function admin_heading($tile)
{
         GLOBAL $page,
                $logo_img,
                $body_color,
                $a_tile_width,
                $this_admin,
                $active_bgcolor,
                $standard_url,
                $secure_url,
                $secure_url,
                $login_page,
                $https;

         $default_bgcolor   = "FFFFFF";
         $affiliate_bgcolor = ($tile=="affiliate") ? $active_bgcolor : $default_bgcolor ;
         $banned_bgcolor    = ($tile=="banned")    ? $active_bgcolor : $default_bgcolor ;
         $billing_bgcolor   = ($tile=="billing")   ? $active_bgcolor : $default_bgcolor ;
         $client_bgcolor    = ($tile=="client")    ? $active_bgcolor : $default_bgcolor ;
         $register_bgcolor  = ($tile=="client_register") ? $active_bgcolor : $default_bgcolor ;
         $config_bgcolor    = ($tile=="config")    ? $active_bgcolor : $default_bgcolor ;
         $coupon_bgcolor    = ($tile=="coupon")    ? $active_bgcolor : $default_bgcolor ;
         $dashboard_bgcolor = ($tile=="dashboard") ? $active_bgcolor : $default_bgcolor ;
         $dbexport_bgcolor  = ($tile=="dbexport")  ? $active_bgcolor : $default_bgcolor ;
         $faq_config_bgcolor= ($tile=="faq_config")? $active_bgcolor : $default_bgcolor ;
         $mail_bgcolor      = ($tile=="mail")      ? $active_bgcolor : $default_bgcolor ;
         $mbsupport_bgcolor = ($tile=="mbsupport") ? $active_bgcolor : $default_bgcolor ;
         $nettools_bgcolor  = ($tile=="nettools")  ? $active_bgcolor : $default_bgcolor ;
         $package_bgcolor   = ($tile=="package")   ? $active_bgcolor : $default_bgcolor ;
         $phpinfo_bgcolor   = ($tile=="phpinfo")   ? $active_bgcolor : $default_bgcolor ;
         $reports_bgcolor   = ($tile=="reports")   ? $active_bgcolor : $default_bgcolor ;
         $support_bgcolor   = ($tile=="support")   ? $active_bgcolor : $default_bgcolor ;
         $todo_bgcolor      = ($tile=="todo")      ? $active_bgcolor : $default_bgcolor ;
         $tld_bgcolor       = ($tile=="tld")       ? $active_bgcolor : $default_bgcolor ;
         $tld_config_bgcolor= ($tile=="tld_config")? $active_bgcolor : $default_bgcolor ;
         $server_bgcolor    = ($tile=="server")    ? $active_bgcolor : $default_bgcolor ;
         $tiles = 9;
         $width = 100/$tiles;
         $logo_size = GetImageSize($logo_img);
         ?>
         <table width=100% align=center border=0 cellspacing=0 cellpadding=0>
         <tr>
         <td bgcolor=FFFFFF>
         <?=start_box(HELLO." ".$this_admin[admin_realname]."! <i>".SFW.date("l, M. d, Y").EF."</i>")?>
         <table width=100% align=center border=0 cellspacing=1 cellpadding=3>
         <tr>
             <td bgcolor=<?=$client_bgcolor?>><?=SFB?><img src=images/icons/clients.gif border=0 align=absmiddle>&nbsp;<a href=<?=$page?>?op=menu&tile=client><b><?=CLIENTADMIN?></b></a><?=EF?></td>
             <td bgcolor=<?=$mail_bgcolor?>><?=SFB?><img src=images/icons/email.gif border=0 align=absmiddle>&nbsp;<a href=<?=$page?>?op=menu&tile=mail><b><?=EMAILADMIN?></b></a><?=EF?></td>
             <td bgcolor=<?=$billing_bgcolor?>><?=SFB?><img src=images/icons/money.gif border=0 align=absmiddle>&nbsp;<a href=<?=$page?>?op=menu&tile=billing><b><?=INVANDBILLING?></b></a><?=EF?></td>
             <td bgcolor=<?=$reports_bgcolor?>><?=SFB?><img src=images/icons/graph.gif border=0 align=absmiddle>&nbsp;<a href=<?=$page?>?op=menu&tile=reports><b><?=DOMREPORTS?></b></a><?=EF?></td>
             <td bgcolor=<?=$todo_bgcolor?>><?=SFB?><img src=images/icons/todo.gif border=0 align=absmiddle>&nbsp;<a href=<?=$page?>?op=menu&tile=todo><b><?=TODOLIST?></b></a><?=EF?></td>
             <td bgcolor=<?=$default_bgcolor?> align=right><a href="<?=$https."://".$secure_url.$login_page?>?op=logout"><img src=images/icons/x_blue.jpg width=14 height=14 border=0 alt="<?=EXIT_t?>"></a></td>
         </tr>
         <tr>
             <td bgcolor=<?=$register_bgcolor?>><?=SFB?><img src=images/icons/money.gif border=0 align=absmiddle>&nbsp;<a href=<?=$page?>?op=menu&tile=client_register><b><?=ACCOUNTREGISTER?></b></a><?=EF?></td>
             <td bgcolor=<?=$support_bgcolor?>><?=SFB?><img src=images/icons/support.jpg border=0 align=absmiddle>&nbsp;<a href=<?=$page?>?op=menu&tile=support_desk><b><?=SUPPORTDESK?></b></a><?=EF?></td>
             <td bgcolor=<?=$nettools_bgcolor?>><?=SFB?><img src=images/icons/prompt.gif border=0 align=absmiddle>&nbsp;<a href=<?=$page?>?op=menu&tile=nettools><b><?=NETWORKINGTOOLS?></b></a><?=EF?></td>
             <td bgcolor=<?=$package_bgcolor?>><?=SFB?><img src=images/icons/note.gif border=0 align=absmiddle>&nbsp;<a href=<?=$page?>?op=menu&tile=package><b><?=PACKAGEADMIN?></b></a><?=EF?></td>
             <td bgcolor=<?=$config_bgcolor?>><?=SFB?><img src=images/icons/note.gif border=0 align=absmiddle>&nbsp;<a href=<?=$page?>?op=menu&tile=config><b><?=SYSTEMCONFIG?></b></a><?=EF?></td>
             <td bgcolor=<?=$default_bgcolor?> align=right><a href="<?=$page?>?op=menu&tile=dashboard"><img src=images/icons/home_round.gif width=14 height=14 border=0 alt="<?=HOME?>"></a></td>
         </tr>
         </table>
         <?=stop_box()?>
         </td>
         </tr>
         </table>
         <br>
         <?
         print str_repeat(" ", 300) . "\n";
         flush();
         echo "<span id=\"processing\">&nbsp;&nbsp;&nbsp;".SFW.PROCESSING." ...".EF."</span>";
}

function admin_main_menu($tile)
{
         GLOBAL $page,
                $body_color,
                $a_tile_width,
                $this_admin,
                $active_bgcolor,
                $standard_url,
                $secure_url,
                $login_page,
                $https;

         $default_bgcolor   = "FFFFFF";
         $client_bgcolor    = ($tile=="client")   ? $active_bgcolor : $default_bgcolor ;
         $billing_bgcolor   = ($tile=="billing")  ? $active_bgcolor : $default_bgcolor ;
         $reports_bgcolor   = ($tile=="reports")  ? $active_bgcolor : $default_bgcolor ;
         $mail_bgcolor      = ($tile=="mail")     ? $active_bgcolor : $default_bgcolor ;
         $todo_bgcolor      = ($tile=="todo")     ? $active_bgcolor : $default_bgcolor ;
         $register_bgcolor  = ($tile=="client_register") ? $active_bgcolor : $default_bgcolor ;
         $support_bgcolor   = ($tile=="support")  ? $active_bgcolor : $default_bgcolor ;
         $dashboard_bgcolor = ($tile=="dashboard")? $active_bgcolor : $default_bgcolor ;
         ?>
         <table width=100% align=center border=0 cellspacing=1 cellpadding=3>
         <tr><td bgcolor=<?=$client_bgcolor?>><?=SFB?><img src=images/icons/clients.gif border=0 align=absmiddle>&nbsp;<a href=<?=$page?>?op=menu&tile=client><b><?=CLIENTADMIN?></b></a><?=EF?></td></tr>
         <tr><td bgcolor=<?=$mail_bgcolor?>><?=SFB?><img src=images/icons/email.gif border=0 align=absmiddle>&nbsp;<a href=<?=$page?>?op=menu&tile=mail><b><?=EMAILADMIN?></b></a><?=EF?></td></tr>
         <tr><td bgcolor=<?=$billing_bgcolor?>><?=SFB?><img src=images/icons/money.gif border=0 align=absmiddle>&nbsp;<a href=<?=$page?>?op=menu&tile=billing><b><?=INVANDBILLING?></b></a><?=EF?></td></tr>
         <tr><td bgcolor=<?=$reports_bgcolor?>><?=SFB?><img src=images/icons/graph.gif border=0 align=absmiddle>&nbsp;<a href=<?=$page?>?op=menu&tile=reports><b><?=DOMREPORTS?></b></a><?=EF?></td></tr>
         <tr><td bgcolor=<?=$todo_bgcolor?>><?=SFB?><img src=images/icons/todo.gif border=0 align=absmiddle>&nbsp;<a href=<?=$page?>?op=menu&tile=todo><b><?=TODOLIST?></b></a><?=EF?></td></tr>
         <tr><td bgcolor=<?=$register_bgcolor?>><?=SFB?><img src=images/icons/money.gif border=0 align=absmiddle>&nbsp;<a href=<?=$page?>?op=menu&tile=client_register><b><?=ACCOUNTREGISTER?></b></a><?=EF?></td></tr>
         <tr><td bgcolor=<?=$support_bgcolor?>><?=SFB?><img src=images/icons/support.jpg border=0 align=absmiddle>&nbsp;<a href=<?=$page?>?op=menu&tile=support_desk><b><?=SUPPORTDESK?></b></a><?=EF?></td></tr>
         </table>
         <?
}

function admin_settings_menu($tile)
{
         GLOBAL $page,
                $body_color,
                $a_tile_width,
                $this_admin,
                $active_bgcolor,
                $standard_url,
                $secure_url,
                $login_page,
                $https;

         $default_bgcolor    = "FFFFFF";
         $config_bgcolor     = ($tile=="config")     ? $active_bgcolor : $default_bgcolor ;
         $coupon_bgcolor     = ($tile=="coupon")     ? $active_bgcolor : $default_bgcolor ;
         $package_bgcolor    = ($tile=="package")    ? $active_bgcolor : $default_bgcolor ;
         $affiliate_bgcolor  = ($tile=="affiliate")  ? $active_bgcolor : $default_bgcolor ;
         $banned_bgcolor     = ($tile=="banned")     ? $active_bgcolor : $default_bgcolor ;
         $tld_config_bgcolor = ($tile=="tld_config") ? $active_bgcolor : $default_bgcolor ;
         $faq_config_bgcolor = ($tile=="faq_config") ? $active_bgcolor : $default_bgcolor ;
         $news_bgcolor       = ($tile=="news")     ? $active_bgcolor : $default_bgcolor ;
         ?>
         <table width=100% align=center border=0 cellspacing=1 cellpadding=3>
         <tr><td bgcolor=<?=$package_bgcolor?>><?=SFB?><img src=images/icons/note.gif border=0 align=absmiddle>&nbsp;<a href=<?=$page?>?op=menu&tile=package><b><?=PACKAGEADMIN?></b></a><?=EF?></td></tr>
         <tr><td bgcolor=<?=$coupon_bgcolor?>><?=SFB?><img src=images/icons/note.gif border=0 align=absmiddle>&nbsp;<a href=<?=$page?>?op=menu&tile=coupon><b><?=COUPONCONFIG?></b></a><?=EF?></td></tr>
         <tr><td bgcolor=<?=$affiliate_bgcolor?>><?=SFB?><img src=images/icons/note.gif border=0 align=absmiddle>&nbsp;<a href=<?=$page?>?op=menu&tile=affiliate><b><?=AFFILIATECONFIG?></b></a><?=EF?></td></tr>
         <tr><td bgcolor=<?=$banned_bgcolor?>><?=SFB?><img src=images/icons/note.gif border=0 align=absmiddle>&nbsp;<a href=<?=$page?>?op=menu&tile=banned><b><?=BANNEDCONFIG?></b></a><?=EF?></td></tr>
         <tr><td bgcolor=<?=$tld_config_bgcolor?>><?=SFB?><img src=images/icons/note.gif border=0 align=absmiddle>&nbsp;<a href=<?=$page?>?op=menu&tile=tld_config><b><?=TLDCONFIG?></b></a><?=EF?></td></tr>
         <tr><td bgcolor=<?=$faq_config_bgcolor?>><?=SFB?><img src=images/icons/note.gif border=0 align=absmiddle>&nbsp;<a href=<?=$page?>?op=menu&tile=faq_config><b><?=FAQCONFIG?></b></a><?=EF?></td></tr>
         <tr><td bgcolor=<?=$config_bgcolor?>><?=SFB?><img src=images/icons/note.gif border=0 align=absmiddle>&nbsp;<a href=<?=$page?>?op=menu&tile=config><b><?=SYSTEMCONFIG?></b></a><?=EF?></td></tr>
         <tr><td bgcolor=<?=$news_bgcolor?>><?=SFB?><img src=images/icons/news_mini.gif border=0 align=absmiddle>&nbsp;<a href=<?=$page?>?op=menu&tile=news><b><?=CLIENTNEWS?></b></a><?=EF?></td></tr>
         </table>
         <?
}

function admin_utilities_menu($tile)
{
         GLOBAL $page,
                $body_color,
                $a_tile_width,
                $this_admin,
                $active_bgcolor,
                $standard_url,
                $secure_url,
                $login_page,
                $https;

         $default_bgcolor   = "FFFFFF";
         $phpinfo_bgcolor   = ($tile=="phpinfo")   ? $active_bgcolor : $default_bgcolor ;
         $server_bgcolor    = ($tile=="server")    ? $active_bgcolor : $default_bgcolor ;
         $mbsupport_bgcolor = ($tile=="mbsupport") ? $active_bgcolor : $default_bgcolor ;
         $dbexport_bgcolor  = ($tile=="dbexport")  ? $active_bgcolor : $default_bgcolor ;
         $nettools_bgcolor  = ($tile=="nettools")  ? $active_bgcolor : $default_bgcolor ;
         ?>
         <table width=100% align=center border=0 cellspacing=1 cellpadding=3>
         <tr><td bgcolor=<?=$nettools_bgcolor?>><?=SFB?><img src=images/icons/prompt.gif border=0 align=absmiddle>&nbsp;<a href=<?=$page?>?op=menu&tile=nettools><b><?=NETWORKINGTOOLS?></b></a><?=EF?></td></tr>
         <tr><td bgcolor=<?=$dbexport_bgcolor?>><?=SFB?><img src=images/icons/download.gif border=0 align=absmiddle>&nbsp;<a href=<?=$page?>?op=menu&tile=dbexport><b><?=DBEXPORT?></b></a><?=EF?></td></tr>
         <tr><td bgcolor=<?=$phpinfo_bgcolor?>><?=SFB?><img src=images/icons/config.gif border=0 align=absmiddle>&nbsp;<a href=<?=$page?>?op=menu&tile=phpinfo><b><?=PHPINFORMATION?></b></a><?=EF?></td></tr>
         <tr><td bgcolor=<?=$server_bgcolor?>><?=SFB?><img src=images/icons/config.gif border=0 align=absmiddle>&nbsp;<a href=<?=$page?>?op=menu&tile=server><b><?=SERVERINFO?></b></a><?=EF?></td></tr>
         <tr><td bgcolor=<?=$mbsupport_bgcolor?>><?=SFB?><img src=images/icons/product.gif border=0 align=absmiddle>&nbsp;<a href=<?=$page?>?op=menu&tile=mbsupport><b><?=MBSUPPORT?></b></a><?=EF?></td></tr>
         </table>
         <?
}

function user_heading($tile)
{
         GLOBAL $page,
                $body_color,
                $u_tile_width,
                $this_user,
                $active_bgcolor,
                $standard_url,
                $secure_url,
                $login_page,
                $https,
                $display_faq_link,
                $display_support_desk_link,
                $display_news_link;

         $default_bgcolor    = "FFFFFF";
         $myinfo_bgcolor     = ($tile=="myinfo")    ? $active_bgcolor : $default_bgcolor ;
         $mypackages_bgcolor = ($tile=="mypackages")? $active_bgcolor : $default_bgcolor ;
         $mydomains_bgcolor  = ($tile=="mydomains") ? $active_bgcolor : $default_bgcolor ;
         $myinvoices_bgcolor = ($tile=="myinvoices")? $active_bgcolor : $default_bgcolor ;
         $mycontact_bgcolor  = ($tile=="contact")   ? $active_bgcolor : $default_bgcolor ;
         $myfaq_bgcolor      = ($tile=="faq")       ? $active_bgcolor : $default_bgcolor ;
         $mynews_bgcolor     = ($tile=="news")      ? $active_bgcolor : $default_bgcolor ;
         $mysupport_bgcolor  = ($tile=="support")   ? $active_bgcolor : $default_bgcolor ;
         $myhome_bgcolor     = ($tile=="dashboard") ? $active_bgcolor : $default_bgcolor ;
         $mylogout_bgcolor   = ($tile=="logout")    ? $active_bgcolor : $default_bgcolor ;
         ?>
         <table width=100% align=center border=0 cellspacing=0 cellpadding=0>
         <tr>
         <td bgcolor=FFFFFF>
         <?=start_box(HELLO." ".$this_user["client_fname"]." ".$this_user["client_lname"]."! <i>".SFW.date("l, M. d, Y").EF."</i>")?>
         <table width=100% align=center border=0 cellspacing=1 cellpadding=3>
         <tr>
             <td bgcolor=<?=$myinfo_bgcolor?>><?=SFB?><img src=images/icons/clients.gif border=0 align=absmiddle>&nbsp;<a href=<?=$page?>?op=menu&tile=myinfo><b><?=MYINFORMATION?></b></a><?=EF?></td>
             <td bgcolor=<?=$mypackages_bgcolor?>><?=SFB?><img src=images/icons/product.gif border=0 align=absmiddle>&nbsp;<a href=<?=$page?>?op=menu&tile=mypackages><b><?=MYPACKAGES?></b></a><?=EF?></td>
             <td bgcolor=<?=$mydomains_bgcolor?>><?=SFB?><img src=images/icons/prompt.gif border=0 align=absmiddle>&nbsp;<a href=<?=$page?>?op=menu&tile=mydomains><b><?=MYDOMAINS?></b></a><?=EF?></td>
             <td bgcolor=<?=$myinvoices_bgcolor?>><?=SFB?><img src=images/icons/money.gif border=0 align=absmiddle>&nbsp;<a href=<?=$page?>?op=menu&tile=myinvoices><b><?=MYINVOICES?></b></a><?=EF?></td>
             <td bgcolor=<?=$default_bgcolor?> align=right>
                 <a href="<?=$page?>?op=menu&tile=dashboard"><img src=images/icons/home_round.gif width=14 height=14 border=0 alt="<?=HOME?>"></a>&nbsp;
             </td>
         </tr>
         <tr>
         <? if ($display_faq_link)          { ?>
            <td bgcolor=<?=$myfaq_bgcolor?>><?=SFB?><img src=images/icons/download.gif border=0 align=absmiddle>&nbsp;<a href=<?=$page?>?op=faq><b><?=ONLINEFAQ?></b></a><?=EF?></td>
         <? } else { ?>
            <td bgcolor=<?=$myfaq_bgcolor?>>&nbsp;</td>
         <? } ?>
         <? if ($display_support_desk_link) { ?>
            <td bgcolor=<?=$mysupport_bgcolor?>><?=SFB?><img src=images/icons/support.jpg border=0 align=absmiddle>&nbsp;<a href=<?=$page?>?op=menu&tile=mysupport><b><?=SUPPORTDESK?></b></a><?=EF?></td>
         <? } else { ?>
            <td bgcolor=<?=$myfaq_bgcolor?>>&nbsp;</td>
         <? } ?>
         <? if ($display_news_link)         { ?>
            <td bgcolor=<?=$mynews_bgcolor?>><?=SFB?><img src=images/icons/note.gif border=0 align=absmiddle>&nbsp;<a href=<?=$page?>?op=menu&tile=mynews><b><?=NEWSUPDATES?></b></a><?=EF?></td>
         <? } else { ?>
            <td bgcolor=<?=$myfaq_bgcolor?>>&nbsp;</td>
         <? } ?>
            <td bgcolor=<?=$mycontact_bgcolor?>><?=SFB?><img src=images/icons/email.gif border=0 align=absmiddle>&nbsp;<a href=<?=$page?>?op=menu&tile=contact><b><?=MYSUPPORT?></b></a><?=EF?></td>
            <td bgcolor=<?=$default_bgcolor?> align=right>
               <a href="<?=$https."://".$secure_url.$login_page?>?op=logout"><img src=images/icons/x_blue.jpg width=14 height=14 border=0 alt="<?=EXIT_t?>"></a>
            </td>
         </tr>

         </table>
         <?=stop_box()?>
         </td>
         </tr>
         </table>
         <br>
         <?
         print str_repeat(" ", 300) . "\n";
         flush();
         echo "<span id=\"processing\">&nbsp;&nbsp;&nbsp;".SFW.PROCESSING." ...".EF."</span>";
}

function display_login()
{
         GLOBAL $background,
                $body_color,
                $default_language,
                $exit_img,
                $https,
                $HTTP_SERVER_VARS,
                $language_enabled,
                $logo_img,
                $login_color,
                $login_error,
                $login_page,
                $new_language,
                $page,
                $secure_url,
                $standard_url,
                $start_timer,
                $submitted,
                $theme_style,
                $this_admin,
                $title,
                $tile,
                $version;

         $language = ($new_language) ? $new_language : $default_language;
         $logo_size = GetImageSize($logo_img);
         $start_timer=get_microtime();
         ?>
         <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2//EN">
         <HTML>
         <HEAD>
         <TITLE><?=$title?></TITLE>
         <META HTTP-EQUIV="Content-Type" CONTENT="text/html;" CHARSET="<?=CHARSET?>">
         <SCRIPT LANGUAGE="JavaScript">
         <!--
         var submitted = false;
             function submitCheck() {
                      if (submitted) {
                          alert("<?=ALREADYCLICKED?>");
                          return false;
                      } else {
                          submitted = true;
                          return true;
                      }
             }

             function OpenWindow(theURL,winName,features) {
                      window.open(theURL,winName,features);
             }

             function goFocus() {
                      document.login.username.focus();
             }
         //-->
         </script>
         <style>
         <!--
         <?=$theme_style."\n"?>
         //-->
         </style>
         <?
         $background = ($background) ? "background=$background" : NULL ;
         echo "\n$head\n</HEAD>\n<BODY bgcolor=\"$body_color\" $background onLoad=\"goFocus();\" leftmargin=0 rightmargin=0 topmargin=0 marginwidth=0 marginheight=0>\n";
         ?>
         <table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
         <tr>
         <td>

         <form method="post" action="<?=$https?>://<?=$secure_url.$login_page?>" name="login" onSubmit="return submitCheck()">
         <input type="hidden" name="op" value="login">

         <table width="250" cellpadding="0" cellspacing="0" border="0" bgColor="#ffffff" align="center">
         <tr>
         <td>

         <table width="100%" cellpadding="0" cellspacing="0" border="0" bgColor="#ffffff">
         <TR>
         <td align="left" valign="top"><img src="images/upper_left.gif"></td>
         <td align="center">&nbsp;</td>
         <td align="right" valign="top"><img src="images/upper_right.gif"></td>
         </tr>
         </table>

         <table width="100%" height="100%" border="0" cellpadding="2" cellspacing="2">

         <tr><td colspan="2" align="center"><a href="<?=$https?>://<?=$secure_url?>"><img src="images/small_logo.gif" border="0"></a></td></tr>

         <? if ($login_error) { ?>
         <tr><td valign="middle" align="center" colspan="2"><font color="RED"><?=SFB.LOGINERROR.EF?></a></td></tr>
         <? } ?>

         <tr><td valign="middle" align="right"><?=SFB.EMAIL?>:<?=EF?></td>
             <td><input type="text" name="username" size="15" maxlength="50">&nbsp;</td></tr>

         <tr><td valign="middle" align="right"><?=SFB.PASSWORD_t?>:<?=EF?></td>
              <td><input type="password" name="password" size="15" maxlength="50">&nbsp;</td></tr>

         <? if ($language_enabled) { ?><tr><td valign="middle" align="right"><?=SFB.LANGUAGE?>:<?=EF?></td><td><?=language_select_box($language);?>&nbsp;</td></tr><?}?>

         <tr><td valign="middle" align="right"><?=SFB?>&nbsp;<?=EF?></td>
             <td><input type="submit" name="submit" value="<?=LOGIN?>">&nbsp;<? if ($https == "https") { ?><?=SFB?><br><a href="<?=$https?>://<?=$secure_url.$login_page?>"><?=SECURELOGIN?></a><?=EF?><? } ?></td></tr>

         </table>

         <table width="100%" border="0" cellpadding="2" cellspacing="2">
         <tr><td align="center"><?=SFB?><?=WELCOMEUSERFROM?> <? echo $HTTP_SERVER_VARS["REMOTE_ADDR"];?>!<br><?=PLEASELOGINTOBEGIN?><?=EF?></td></tr>
         </table>

         <table width="100%" cellpadding="0" cellspacing="0" border="0" bgColor="#ffffff">
         <TR>
         <td align="left" valign="top"><img src="images/lower_left.gif"></td>
         <td align="center"><?=SFB?><a href="#" target="reminder" onClick='window.open("<?=$login_page?>?op=reminder","reminder","width=250,height=150,scrollbars=0,resizable=0"); return false;'><?=FORGOTYOURPASSWORD?></a><?=EF?></td>
         <td align="right" valign="top"><img src="images/lower_right.gif"></td>
         </tr>
         </table>

         </td>
         </tr>
         </table>
         <center><font color="<?=$body_color?>"><?=TFB."MB:".$version.EF?></font></center>

         </form>

         </td>
         </tr>
         </table>

         </body></html>
         <?
}

function display_reminder($response=NULL)
{
         GLOBAL $background,
                $body_color,
                $exit_img,
                $https,
                $HTTP_SERVER_VARS,
                $language,
                $logo_img,
                $login_color,
                $login_page,
                $language_enabled,
                $page,
                $standard_url,
                $secure_url,
                $start_timer,
                $submitted,
                $this_admin,
                $title,
                $tile,
                $theme_style,
                $version;

         $logo_size = GetImageSize($logo_img);
         $start_timer=get_microtime();
         ?>
         <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2//EN">
         <HTML>
         <HEAD>
         <TITLE><?=$title?></TITLE>
         <META HTTP-EQUIV="Content-Type" CONTENT="text/html;" CHARSET="<?=CHARSET?>">
         <SCRIPT LANGUAGE="JavaScript">
         <!--
         var submitted = false;
             function submitCheck() {
                      if (submitted) {
                          alert("<?=ALREADYCLICKED?>");
                          return false;
                      } else {
                          submitted = true;
                          return true;
                      }
             }

             function OpenWindow(theURL,winName,features) {
                      window.open(theURL,winName,features);
             }

             function goFocus() {
                      document.login.email.focus();
             }
         //-->
         </script>
         <style>
         <!--
         <?=$theme_style."\n"?>
         //-->
         </style>
         <?
         $background = ($background) ? "background=$background" : NULL ;
         $javascript = ($response) ? NULL : "onLoad=\"goFocus();\"";
         echo "\n$head\n</HEAD>\n<BODY bgcolor=\"$body_color\" $background  $javascript leftmargin=0 rightmargin=0 topmargin=0 marginwidth=0 marginheight=0>\n";
         ?>
         <table width=100% height=100% border=0 cellpadding=5 cellspacing=0>
         <tr>
         <td>
         <form method="post" action="<?=$https?>://<?=$secure_url.$login_page?>" name="login" onSubmit="return submitCheck();">
         <input type=hidden name=op value=reminder>
         <table width=200 cellpadding=0 cellspacing=0 border=0 bgColor=#ffffff align=center>
         <tr>
         <td>
         <table width=100% cellpadding=0 cellspacing=0 border=0 bgColor=#ffffff>
         <TR>
         <td align=left valign=top><img src=images/upper_left.gif></td>
         <td align=center><b><?=MFB.PASSWORDREMINDER.EF?></b></td>
         <td align=right valign=top><img src=images/upper_right.gif></td>
         </tr>
         </table>
         <table width=100% height=100% border=0 cellpadding=2 cellspacing=2>
         <tr><td valign=middle align=center>
             <? if ($response == 1) { ?>
                  <?=SFB.ERRORPLEASETRYAGAIN.EF?>
             <? } elseif ($response == 2) { ?>
                  <?=SFB.YOURLOGININFOEMAILED.EF?>
             <? } else { ?>
                  <?=SFB.EMAIL?>:<?=EF?>&nbsp;<input type=text name=email size=20 maxlength=50>
             <? } ?>
             </td></tr>
         </table>
         <table width=100% cellpadding=0 cellspacing=0 border=0 bgColor=#ffffff>
         <TR>
         <td align=left valign=top><img src=images/lower_left.gif></td>
         <td align=center>
             <? if ($response) { ?>
                  <A onclick="window.close(); return false;" href="#"><b><?=CLOSETHISWIN?></b></A>
             <? } else { ?>
                  <input type=submit name=submit value="<?=REMINDME?>">
             <? } ?>
         </td>
         <td align=right valign=top><img src=images/lower_right.gif></td>
         </tr>
         </table>
         </td>
         </tr>
         </table>
         </form>
         </td>
         </tr>
         </table>
         </body>
         </html>
         <?
}
?>