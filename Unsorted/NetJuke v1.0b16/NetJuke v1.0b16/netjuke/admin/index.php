<?php

// defines if this script requires to be logged in
define( "PRIVATE", true );

require_once('../lib/inc-admin.php');
require_once(FS_PATH."/etc/locale/".LANG_PACK."/inc-admin_index.php");

$section = "sysadmin";
include (INTERFACE_HEADER);

?>

  <table width='100%' border=0 cellspacing=0 cellpadding=0>
  <tr>
    <td width="50%" align=left valign=top>


  <table width='97%' border=0 cellspacing=1 cellpadding=3 class='border'>
  <tr>
    <td class='header' nowrap><B><?php echo  ADMNDX_SYS_HEADER ?></B></td>
  </tr>
  <tr>
    <td width="35%" align=left wrap="virtual" class="content">
      <b>&raquo;</b> 
      <A HREF="<?php echo WEB_PATH?>/admin/prefs-edit.php" title="<?php echo  ADMNDX_EDIT_HELP ?>"><?php echo  ADMNDX_PREFEDIT ?></A>
      [<b><A HREF="<?php echo $_SERVER['PHP_SELF']?>?help=prefs-edit" title="<?php echo  ADMNDX_INFO_HELP ?>">?</A></b>]
    </td>
  </tr>
<?php if ($_REQUEST['help'] == 'prefs-edit') { ?>
  </tr>
    <td align=left wrap="virtual" class="content">
      <?php echo  ADMNDX_PREFEDIT_HELP ?>
    </td>
  </tr>
<?php } ?>
  <tr>
    <td width="35%" align=left wrap="virtual" class="content">
      <b>&raquo;</b> 
      <A HREF="<?php echo WEB_PATH?>/admin/user-list.php" title="<?php echo  ADMNDX_EDIT_HELP ?>"><?php echo  ADMNDX_USERMAINT ?></A>
      [<b><A HREF="<?php echo $_SERVER['PHP_SELF']?>?help=user_maint" title="<?php echo  ADMNDX_INFO_HELP ?>">?</A></b>]
    </td>
  </tr>
<?php if ($_REQUEST['help'] == 'user_maint') { ?>
  </tr>
    <td align=left wrap="virtual" class="content">
      <?php echo  ADMNDX_USERMAINT_HELP ?>
    </td>
  </tr>
<?php } ?>
  <tr>
    <td align=left wrap="virtual" class="content">
      <b>&raquo;</b>
      <A HREF="<?php echo WEB_PATH?>/admin/hidden.php" title="<?php echo  ADMNDX_EDIT_HELP ?>"><?php echo  ADMNDX_HIDFILE ?></A>
      [<b><A HREF="<?php echo $_SERVER['PHP_SELF']?>?help=hidden_files" title="<?php echo  ADMNDX_INFO_HELP ?>">?</A></b>]
    </td>
  </tr>
<?php if ($_REQUEST['help'] == 'hidden_files') { ?>
  </tr>
    <td align=left wrap="virtual" class="content">
      <?php echo  ADMNDX_HIDFILE_HELP ?>
    </td>
  </tr>
<?php } ?>
  <tr>
    <td align=left wrap="virtual" class="content">
      <b>&raquo;</b>
      <A HREF="<?php echo WEB_PATH?>/admin/phpinfo.php" title="<?php echo  ADMNDX_EDIT_HELP ?>" TARGET="_phpinfo"><?php echo  ADMNDX_PHPINFO ?></A>
      [<b><A HREF="<?php echo $_SERVER['PHP_SELF']?>?help=phpinfo" title="<?php echo  ADMNDX_INFO_HELP ?>">?</A></b>]
    </td>
  </tr>
<?php if ($_REQUEST['help'] == 'phpinfo') { ?>
  </tr>
    <td align=left wrap="virtual" class="content">
      <?php echo  ADMNDX_PHPINFO_HELP ?>
    </td>
  </tr>
<?php } ?>
  </table>

  
 </td>
 <td width="50%" align=right valign=top>

  
  <table width='97%' border=0 cellspacing=1 cellpadding=3 class='border'>
  <tr>
    <td class='header' nowrap><B><?php echo  ADMNDX_CONT_HEADER ?></B></td>
  </tr>
  <tr>
    <td align=left wrap="virtual" class="content">
      <b>&raquo;</b> 
      <A href="<?php echo  WEB_PATH ?>/admin/tr-edit.php" target="NetJukeGetInfo" onClick="window.open('','NetJukeGetInfo','width=500,height=575,top=25,left=25,menubar=no,scrollbars=yes,resizable=yes');" title="<?php echo  ADMNDX_EDIT_HELP ?>"><?php echo  ADMNDX_TRADD ?></A>
      [<b><A HREF="<?php echo $_SERVER['PHP_SELF']?>?help=tr-add" title="<?php echo  ADMNDX_INFO_HELP ?>">?</A></b>]
    </td>
  </tr>
<?php if ($_REQUEST['help'] == 'tr-add') { ?>
  </tr>
    <td align=left wrap="virtual" class="content">
      <?php echo  ADMNDX_TRADD_HELP ?>
    </td>
  </tr>
<?php } ?>
  <tr>
    <td align=left wrap="virtual" class="content">
      <b>&raquo;</b> 
      <A HREF="<?php echo WEB_PATH?>/admin/tabfile-recursive.php" title="<?php echo  ADMNDX_EDIT_HELP ?>"><?php echo  ADMNDX_MP3FIND ?></A>
      [<b><A HREF="<?php echo $_SERVER['PHP_SELF']?>?help=tabfile-recursive" title="<?php echo  ADMNDX_INFO_HELP ?>">?</A></b>]
    </td>
  </tr>
<?php if ($_REQUEST['help'] == 'tabfile-recursive') { ?>
  </tr>
    <td align=left wrap="virtual" class="content">
      <?php echo  ADMNDX_MP3FIND_HELP ?>
    </td>
  </tr>
<?php } ?>
  <tr>
    <td align=left wrap="virtual" class="content">
      <b>&raquo;</b> 
      <A HREF="<?php echo WEB_PATH?>/admin/tabfile-import.php" title="<?php echo  ADMNDX_EDIT_HELP ?>"><?php echo  ADMNDX_TABFILEIMP ?></A>
      [<b><A HREF="<?php echo $_SERVER['PHP_SELF']?>?help=tabfile-import" title="<?php echo  ADMNDX_INFO_HELP ?>">?</A></b>]
    </td>
  </tr>
<?php if ($_REQUEST['help'] == 'tabfile-import') { ?>
  </tr>
    <td align=left wrap="virtual" class="content">
      <?php echo  ADMNDX_TABFILEIMP_HELP ?> 
    </td>
  </tr>
<?php } ?>
  <tr>
    <td width="35%" align=left wrap="virtual" class="content">
      <b>&raquo;</b> 
      <A HREF="<?php echo WEB_PATH?>/admin/tabfile-upload.php" title="<?php echo  ADMNDX_EDIT_HELP ?>"><?php echo  ADMNDX_TABFILEUPL ?></A>
      [<b><A HREF="<?php echo $_SERVER['PHP_SELF']?>?help=tabfile-upload" title="<?php echo  ADMNDX_INFO_HELP ?>">?</A></b>]
    </td>
  </tr>
<?php if ($_REQUEST['help'] == 'tabfile-upload') { ?>
  </tr>
    <td align=left wrap="virtual" class="content">
      <?php echo  ADMNDX_TABFILEUPL_HELP ?>
    </td>
  </tr>
<?php } ?>
  <tr>
    <td align=left wrap="virtual" class="content">
      <b>&raquo;</b>
      <A HREF="<?php echo WEB_PATH?>/admin/db-maintain.php" title="<?php echo  ADMNDX_EDIT_HELP ?>"><?php echo  ADMNDX_DBMAINTAIN ?></A>
      [<b><A HREF="<?php echo $_SERVER['PHP_SELF']?>?help=db-maintain" title="<?php echo  ADMNDX_INFO_HELP ?>">?</A></b>]
    </td>
  </tr>
<?php if ($_REQUEST['help'] == 'db-maintain') { ?>
  </tr>
    <td align=left wrap="virtual" class="content">
      <?php echo  ADMNDX_DBMAINTAIN_HELP ?>
    </td>
  </tr>
<?php } ?>
  </table>


 </td>
</tr>
</table>

<?php

  include (INTERFACE_FOOTER);

  exit;

?>
