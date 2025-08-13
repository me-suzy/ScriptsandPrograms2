
<?php

require_once(FS_PATH."/etc/locale/".LANG_PACK."/inc-lib_inc-header.php");

if (isset($onload)) $onload_conf = $onload;

?>

<!-- Start Primary Header -->

<HTML>
<HEAD>
	<meta HTTP-EQUIV="Pragma" CONTENT="no-cache">
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo  LANG_CHARSET ?>">
	<TITLE>Netjuke @ <?php echo $_SERVER['HTTP_HOST']?> : <?php echo $_SERVER['SCRIPT_NAME']?></TITLE>
	<link REL="shortcut icon" HREF="<?php echo WEB_PATH?>/var/artwork/favicon.ico" TYPE="image/x-icon">
	<script type="text/javascript" language="Javascript">
	  <?php require_once(FS_PATH."/lib/inc-jscript.php"); ?>
	</script>
    <style type="text/css">
      <?php require_once(FS_PATH."/lib/inc-css.php"); ?>
    </style>
</HEAD>
<BODY BGCOLOR='#<?php echo $NETJUKE_SESSION_VARS["bgcolor"] ?>' TEXT='#<?php echo $NETJUKE_SESSION_VARS["text"] ?>' LINK='#<?php echo $NETJUKE_SESSION_VARS["link"] ?>' ALINK='#<?php echo $NETJUKE_SESSION_VARS["alink"] ?>' VLINK='#<?php echo $NETJUKE_SESSION_VARS["vlink"] ?>' ONLOAD="<?php echo $onload_conf ?>">
<a name="PageTop"></a>

<?php

      
  if (strlen(CUSTOM_HEADER) > 0) {
        
    include (CUSTOM_HEADER);
         
  }

  $css_browse = "header";
  $css_search = "header";
  $css_random = "header";
  $css_community = "header";
  $css_radio = "header";
  $css_playlists = "header";
  $css_account = "header";
  $css_prefs = "header";
  $css_sysadmin = "header";
  $css_login = "header";
  $css_register = "header";
  
  // Let's be over-careful with the vars
  // passed to the following eval() call.
  // It can be a security nightmare...

  if (    ($section != 'browse')
       && ($section != 'search')
       && ($section != 'random')
       && ($section != 'community')
       && ($section != 'playlists')
       && ($section != 'account')
       && ($section != 'prefs') 
       && ($section != 'sysadmin')
       && ($section != 'login')
       && ($section != 'register') ) {
  
    $section = "browse";
  
  }
    
  eval("  \$css_$section = 'content';  ");
  
  $colspan = 0;

  $browse_html = '';
  $search_html = '';
  $random_html = '';
  $community_html = '';
  $radio_html = '';
  $playlists_html = '';
  $account_html = '';
  $prefs_html = '';
  $sysadmin_html = '';
  $login_html = '';
  $register_html = '';
  
  if (    (abs(substr(SECURITY_MODE,0,1)) == 0) 
       || ( (abs(substr(SECURITY_MODE,0,1)) == 1) && ($NETJUKE_SESSION_VARS["email"] != "") ) ) {
  
    $browse_html = "<td class=\"$css_browse\" align=center nowrap><B><A CLASS=\"$css_browse\" HREF=\"".WEB_PATH."/index.php\" title=\"".TB1_BROWSE_HELP."\">".strtoupper(TB1_BROWSE)."</A></B></td>";
    $colspan++;
  
    $search_html = "<td class=\"$css_search\" align=center nowrap><B><a CLASS=\"$css_random\" href=\"".WEB_PATH."/search-adv.php\" target=\"NetJukeSearchAdv\" onClick=\"window.open('','NetJukeSearchAdv','width=500,height=575,top=0,left=0,menubar=no,scrollbars=yes,resizable=yes');\" title=\"".TB1_SEARCH_HELP."\">".strtoupper(TB1_SEARCH)."</A></B></td>";
    $colspan++;
    
    $random_html = "<td class=\"$css_random\" align=center nowrap><B><a CLASS=\"$css_random\" href=\"".WEB_PATH."/random.php\" target=\"NetJukeRandom\" onClick=\"window.open('','NetJukeRandom','width=500,height=575,top=0,left=0,menubar=no,scrollbars=yes,resizable=yes');\" title=\"".TB1_RANDOM_HELP."\">".strtoupper(TB1_RANDOM)."</A></B></td>";
    $colspan++;

    # make sure the community feature is enabled
    if (ENABLE_COMMUNITY == "t") {
      $community_html = "<td class=\"$css_community\" align=center nowrap><B><A CLASS=\"$css_community\" HREF=\"".WEB_PATH."/community.php\" title=\"".TB1_COMMUNITY_HELP."\">".strtoupper(TB1_COMMUNITY)."</A></B></td>";
      $colspan++;
    }

    # make sure the radio feature is enabled and we have a url
    if (stristr(RADIO_URL,"://")) {
      $radio_html = "<td class=\"$css_radio\" align=center nowrap><B><A CLASS=\"$css_radio\" HREF=\"".WEB_PATH."/play.php?do=radio\" title=\"".TB1_RADIO_HELP."\">".strtoupper(TB1_RADIO)."</A></B></td>";
      $colspan++;
    }
  
  } else {
  
    $browse_html = "<td class=\"$css_browse\" align=center nowrap><B><A CLASS=\"$css_browse\" HREF=\"".WEB_PATH."/login.php\" title=\"".TB1_BROWSE_HELP."\">".strtoupper(TB1_BROWSE)."</A></B></td>";
    $colspan++;
  
    $search_html = "<td class=\"$css_search\" align=center nowrap><B><a CLASS=\"$css_random\" href=\"".WEB_PATH."/login.php\" title=\"".TB1_SEARCH_HELP."\">".strtoupper(TB1_SEARCH)."</A></B></td>";
    $colspan++;
    
    $random_html = "<td class=\"$css_random\" align=center nowrap><B><a CLASS=\"$css_random\" href=\"".WEB_PATH."/login.php\" title=\"".TB1_RANDOM_HELP."\">".strtoupper(TB1_RANDOM)."</A></B></td>";
    $colspan++;
  
  }

  if ($NETJUKE_SESSION_VARS["email"] != "") {
    $playlists_html = "<td class=\"$css_playlists\" align=center nowrap><B><A CLASS=\"$css_playlists\" HREF=\"".WEB_PATH."/pl-list.php?do=list\" title=\"".TB1_PLISTS_HELP."\">".strtoupper(TB1_PLISTS)."</A></B></td>";
    $colspan++;
    $account_html = "<td class=\"$css_account\" align=center nowrap><B><A CLASS=\"$css_account\" HREF=\"".WEB_PATH."/account.php?do=edit\" title=\"".TB1_ACCOUNT_HELP."\">".strtoupper(TB1_ACCOUNT)."</A></B></td>";
    $colspan++;
    if (USER_THEMES != "f") {
      $prefs_html = "<td class=\"$css_prefs\" align=center nowrap><B><A CLASS=\"$css_prefs\" HREF=\"".WEB_PATH."/prefs.php?do=edit\" title=\"".TB1_PREFS_HELP."\">".strtoupper(TB1_PREFS)."</A></B></td>";
      $colspan++;
    }
    $login_html = "<td class=\"$css_login\" align=center nowrap><B><A CLASS=\"$css_login\" HREF=\"".WEB_PATH."/login.php?netjuke_logout=1\" title=\"".TB1_LOGOUT_HELP."\">".strtoupper(TB1_LOGOUT)."</A></B></td>";
    $colspan++;
  }

  if ($NETJUKE_SESSION_VARS["gr_id"] == 1) {
    $sysadmin_html = "<td class=\"$css_sysadmin\" align=center nowrap><B><A CLASS=\"$css_sysadmin\" HREF=\"".WEB_PATH."/admin/index.php\" title=\"".TB1_SYSADMIN_HELP."\">".strtoupper(TB1_SYSADMIN)."</A></B></td>";
    $colspan++;
  }

  if ( $login_html == '' ) {
    $login_html = "<td class=\"$css_login\" align=center nowrap><B><A CLASS=\"$css_login\" HREF=\"".WEB_PATH."/login.php\" title=\"".TB1_LOGIN_HELP."\">".strtoupper(TB1_LOGIN)."</A></B></td>";
    $colspan++;
    if (abs(substr(SECURITY_MODE,2,1)) == 0) {
      $register_html = "<td class=\"$css_register\" align=center nowrap><B><A CLASS=\"$css_register\" HREF=\"".WEB_PATH."/account.php?do=new\" title=\"".TB1_REGISTER_HELP."\">".strtoupper(TB1_REGISTER)."</A></B></td>";
      $colspan++;
    }
  }

?>
        
<table width='100%' border=0 cellspacing=1 cellpadding=3 class="border">
<tr>
	<?php echo $browse_html?>
	<?php echo $search_html?>
	<?php echo $random_html?>
	<?php echo $community_html?>
	<?php echo $radio_html?>
	<?php echo $playlists_html?>
	<?php echo $account_html?>
	<?php echo $prefs_html?>
	<?php echo $sysadmin_html?>
	<?php echo $login_html?>
	<?php echo $register_html?>
</tr>
</table>
      
<BR>

<?php flush(); ?>
        
<!--  End Primary Header  -->