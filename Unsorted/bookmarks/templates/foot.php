<?

//####################################################################
// Active PHP Bookmarks - lbstone.com/apb/
//
// Filename:    foot.php
// Authors:     L. Brandon Stone (lbstone.com)
//
// 2003-03-11   Added security check. [LBS]
//
//####################################################################

//////////////////////////////////////////////////////////////////////
// Security check.
//////////////////////////////////////////////////////////////////////

if ($HTTP_COOKIE_VARS["APB_SETTINGS"]["template_path"] ||
    $HTTP_POST_VARS["APB_SETTINGS"]["template_path"] ||
    $HTTP_GET_VARS["APB_SETTINGS"]["template_path"])
{ exit(); }

//////////////////////////////////////////////////////////////////////
// There should be no need to alter this file.  If you want to change
// the look and feel of APB, you should change the foot_design.php
// file.
//////////////////////////////////////////////////////////////////////

if ($edit_mode) {  echo "<h2 class='warning'>Edit Mode</h2>"; }

?>

<? if ($APB_SETTINGS['allow_search_box']) { ?>

    <p>
    <form method='get' action='search.php'>
    <input name='keywords' value='' size='25'>
    <input type='submit' name='submit' value='Search'>
    </form>
    </p>

<? } ?>

<?
debug("auth_user_id: ".$APB_SETTINGS['auth_user_id']);
debug("auth_type: ".$APB_SETTINGS['auth_type']);
?>

<p>
<table cellpadding="5" align="center" cellspacing="0" border="0">
<tr>

  <!-- HOME -->
  <td align="center">
    <a href="<? echo $APB_SETTINGS['apb_url'] ?>"><img src="images/tb_home.gif" border="0"><br><font size='1'>Bookmarks Home</font></a>
  </td>

  <!-- EDIT MODE -->
  <? if ($APB_SETTINGS['auth_user_id'] && $APB_SETTINGS['allow_edit_mode']) { ?>
  <td align="center">
    <?
        if ($APB_SETTINGS['edit_mode']) {
            print "<a href='".$SCRIPT_NAME."?";
            if ($date) { echo "date=".$date."&"; }
            if ($id) { echo "id=".$id."&"; }
            if ($action) { echo "action=".$action."&"; }
            if ($keywords) { echo "keywords=".$keywords; }
            print "'><img src='images/tb_edit.gif' border='0'><br><font size='1'>Exit Edit Mode</font></a>";

        } else {
            ?>
            <a href="<?= $SCRIPT_NAME ?>?edit_mode=1<? if ($QUERY_STRING) { echo "&".$QUERY_STRING; } ?>"><img src="images/tb_edit.gif" border="0"><br><font size='1'>Enter Edit Mode</font></a>
            <?
        }
    ?>
  </td>
  <? } ?>

  <!-- ADD BOOKMARK -->
  <? if ($APB_SETTINGS['auth_user_id']) { ?>
  <td align="center">
    <a href="<? echo $APB_SETTINGS['apb_url'] ?>add_bookmark.php"><img src="images/tb_new.gif" border="0"><br><font size='1'>Add Bookmark</font></font></a>
  </td>
  <? } ?>

  <!-- ADD GROUP -->
<!--
  <? if ($APB_SETTINGS['auth_user_id']) { ?>
  <td align="center">
    <a href="<? echo $APB_SETTINGS['apb_url'] ?>add_group.php"><img src="images/tb_open.gif" border="0"><br><font size='1'>Add Group</font></font></a>
  </td>
  <? } ?>
-->

  <? if ($APB_SETTINGS['allow_login']) { ?>

      <!-- COOKIE LOGIN -->
      <? if (!$APB_SETTINGS['auth_user_id'] && $APB_SETTINGS['auth_type'] == 'cookie') { ?>
      <td align="center">
        <a href="<? echo $APB_SETTINGS['apb_url'] ?>cookie_auth.php?action=cookie_login"><img src="images/tb_enter.gif" border="0"><br><font size='1'>User Login</font></a>
      </td>
      <? } ?>

      <!-- COOKIE LOGOUT -->
      <? if ($APB_SETTINGS['auth_user_id'] && $APB_SETTINGS['auth_type'] == 'cookie') { ?>
      <td align="center">
        <a href="<? echo $APB_SETTINGS['apb_url'] ?>cookie_auth.php?action=cookie_logout"><img src="images/tb_exit.gif" border="0"><br><font size='1'>Logout <b><? echo $cookie_username ?></b></font></a>
      </td>
      <? } ?>

  <? } ?>

  <!-- SETUP -->
  <? if ($APB_SETTINGS['auth_user_id']) { ?>
  <td align="center">
    <a href="setup.php"><img src="images/tb_preferences.gif" border="0"><br><font size='1'>Tools</font></a>
  </td>
  <? } ?>

  <!-- HELP -->
  <td align="center">
    <p><a href="<? echo $APB_SETTINGS['program_home_url'] ?>help/?version=<? echo $APB_SETTINGS['version'] ?>"><img src="images/tb_help.gif" border="0"><br><font size='1'>Help</font></a>
  </td>

</tr>
</table>

<p><font size="1">Powered by <a href="<?= $APB_SETTINGS['program_home_url'] ?>?version=<?= $APB_SETTINGS['version'] ?>"><? echo $APB_SETTINGS['program_name'] ?></a> v<? echo $APB_SETTINGS['version'] ?></font>

</center>

<?

// If you want to create your own design for APB, change the foot_design.php file.
include($APB_SETTINGS['template_path'] . "foot_design.php");

?>