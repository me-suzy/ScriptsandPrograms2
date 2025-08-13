<?php

switch ($_REQUEST['do']) {
  case DO_UPGRADE:
    $type = UPGRADE_STR;
    $btn = DO_UPGRADE_GO;
    break;
  default:
    $type = INSTALL_STR;
    $btn = DO_INSTALL_GO;
}

########################################

function db_type_menu($name, $selected_val="", $onchange="") {

  $html = "<select name=\"$name\" onchange=\"$onchange\" title=\"Click on the question mark to the left to get more information on this item.\">";
  
  $values = array( "mysql"=>"MySQL", "postgres"=>"Postgres 6.x", "postgres7"=>"Postgres 7.x" );
  
  foreach ($values as $key => $value) {
  
    if ($key == $selected_val) {
      $selected = "selected";
    } else {
      $selected = "";
    }
    
    $html .= "<option value=\"$key\" $selected>$value</option>";
  
  }

  $html .= "</select>";
  
  return $html;

}

########################################

function lang_pack_menu($name, $selected_val="en", $onchange="") {

  $html = "<select name=\"$name\" onchange=\"$onchange\" class=input_content title=\"Click on the question mark to the left to get more information on this item.\">";
  
  $values = array( "en"=>"English", "fr"=>"French", "ca"=>"Catalan", "de"=>"German", "es"=>"Spanish" );
  
  asort($values);
  
  foreach ($values as $key => $value) {
  
    if ($key == $selected_val) {
      $selected = "selected";
    } else {
      $selected = "";
    }
    
    $html .= "<option value=\"$key\" $selected>$value</option>";
  
  }

  $html .= "</select>";
  
  return $html;

}

########################################

?>

<HTML>
<HEAD>
	<TITLE>NETJUKE INSTALLER <?php echo  NETJUKE_VERSION ?>: Step 3: Information Gathering</TITLE>
	<link rel="Stylesheet" href="./lib/styles.css" type="text/css">
</HEAD>
<BODY BGCOLOR='#FFFFFF' TEXT='#000000' LINK='#0000FF' ALINK='#333333' VLINK='#9900CC'>
<a name="PageTop"></a>

<script language='Javascript'>

  function checkForm () {

    var msg = '';
    
    var type = document.prefsForm.typeField.value;

    if (type != 'upgrade') {
      if (document.prefsForm.db_hostField.value.length == 0) {
        msg = msg + '- Please enter a DB Host.\n';  
      }
      if (document.prefsForm.db_userField.value.length == 0) {
        msg = msg + '- Please enter a DB User.\n';  
      }
      if (document.prefsForm.db_nameField.value.length == 0) {
        msg = msg + '- Please enter a DB Name.\n';  
      }
    }
    var emailPat=/^(.+)@(.+)\.(.+)$/;
    var matchArray=document.prefsForm.sys_admin_userField.value.match(emailPat);
    if (document.prefsForm.sys_admin_userField.value == '') {
      msg = msg + '- Please enter a default Sys. Admin email address.\n';  
    } else if (matchArray == null) {
      msg = msg + '- Please enter a valid default Sys. Admin email address.\n'; 
    } else {
      if (document.prefsForm.sys_admin_passField.value == '') {
        msg = msg + '- Please enter a Sys. Admin password.\n';  
      } else {
        if (type != 'upgrade') {
          if (document.prefsForm.sys_admin_pass2Field.value == '') {
            msg = msg + '- Please confirm your new default Sys. Admin password.\n';  
          } else if (document.prefsForm.sys_admin_passField.value.length < 6) {
            msg = msg + '- Your new default Sys. Admin password is too short.\n   Please use a minimum of 6 characters.\n';  
          } else if (document.prefsForm.sys_admin_passField.value.length > 20) {
            msg = msg + '- Your new default Sys. Admin password is too long.\n   Please use a maximum of 20 characters.\n';  
          } else if (document.prefsForm.sys_admin_pass2Field.value != document.prefsForm.sys_admin_passField.value) {
            msg = msg + '-  Default Sys. Admin passwords do not match.\n   Please retype your new password.\n';  
          }
        } else {
          if (document.prefsForm.sys_admin_passField.value.length < 6) {
            msg = msg + '- Your new default Sys. Admin password is too short.\n   Please use a minimum of 6 characters.\n';  
          } else if (document.prefsForm.sys_admin_passField.value.length > 20) {
            msg = msg + '- Your new default Sys. Admin password is too long.\n   Please use a maximum of 20 characters.\n';  
          }
        }
      }
    }

    if (msg != '') {

      alert(msg);

      return (false);

    } else {
      
      return confirm("The installer is now going to generate the database and preference file.\n\nYour browser will:\n- prompt you to save the inc-prefs.php file,\n- or save it to your default download directory (Desktop?),\n- or just display a blank page.\n\nIf the file is saved, open it and verify it (see notes above installer form).\n\nIf you only see a blank page, try the \"view source\" feature of your browser,\nand copy-paste the content in a new file at netjuke/etc/inc-prefs.php.\n\nIf the installer keeps returning you to the form, it is possible that an error\nis occuring, but that you are not being warned appropriately  because\nyour browser is set to ignore new windows not instigated by a\nphysical click of the mouse. (mozilla, etc.).");

    }

  }

</script>

<div align=center>

<table width='550' border=0 cellspacing=1 cellpadding=3 class='border'>
<form action='<?php echo  $_SERVER['PHP_SELF'] ?>' method=post name='prefsForm' target="_self" onSubmit='return checkForm ();'>
<input type="hidden" name="typeField" value="<?php echo  $type ?>">
<tr>
  <td class='header' align=left nowrap>
    <B>NETJUKE INSTALLER <?php echo  NETJUKE_VERSION ?>: Step 3: Information Gathering</B>
  </td>
</tr>
<tr>
  <td class='content' nowrap align=center>
    <table border=0 cellspacing=0 cellpadding=2>
    <tr>
      <td align=left nowrap colspan=2>&nbsp;</td>
    </tr>
    <tr>
      <td align=center colspan=2>
        <div align=justify style="width: 450px;">
          <b style="color: #FF0000;">IMPORTANT</b>: After saving the resulting
          file, we strongly advise to verify it in a text editor to make sure
          there are no empty characters (spaces, blank lines, tabs, etc.) before
          the top "&lt;?php" and after the bottom "?&gt;" as they may generate
          errors when the file is included by the application.
          <br><br>
          Some browser will prompt you to save the file (as inc-prefs.php),
          while some will save it to your default download folder (Desktop?), or
          even display the content of the file. Just make sure to verify
          the file afterward. If unsure or unsucessful, you can download
          the netjuke-toolkit package, and generate the database and/or
          preference file manually by referring to PHP/master-inc-prefs.php
          (to be edited, not run), obfuscate-db-passwd.php, and SQL/install/.   
          <br><br>
          <font style="color: #FF0000;">
            PLEASE make sure to read the documentation in the previous step.
            <br>
            For some help with the form below, please click on the associated
            question marks [?].
          </font>
          <br><br>
          Here is some PHP-related <a href="./lib/phpinfo.php" target="_blank">information
          about your computer</a>. Keep in mind that this information is about
          the computer serving the netjuke more than the one that accesses the
          installer (could be different).
        </div>
      </td>
    </tr>
    <tr>
      <td align=left nowrap colspan=2>&nbsp;</td>
    </tr>
    <tr>
      <td align=right nowrap>DB Type [<a href="javascript:alert('- Select the type of database you wish to connect to.');" title="Click on this question mark to get more information on this item.">?</a>]</td>
      <td align=left nowrap><?php echo db_type_menu("db_typeField")?></td>
    </tr>
<?php if ($type != UPGRADE_STR) {?>
    <tr>
      <td align=right nowrap>DB Host [<a href="javascript:alert('- Enter the database hostname you wish to connect to.\n- Tip: Use \'127.0.0.1\' if the netjuke and database are on the same server.');" title="Click on this question mark to get more information on this item.">?</a>]</td>
      <td align=left nowrap><input tabindex=1 type=text name='db_hostField' size='30' maxlength='256' value='' title="Click on the question mark to the left to get more information on this item."></td>
    </tr>
    <tr>
      <td align=right nowrap>DB User [<a href="javascript:alert('- Enter a valid database user with create, select, insert, update and delete permissions.\n- We advise to also use host-based authentication at the db level.');" title="Click on this question mark to get more information on this item.">?</a>]</td>
      <td align=left nowrap><input tabindex=2 type=text name='db_userField' size='30' maxlength='256' value=''></td>
    </tr>
    <tr>
      <td align=right nowrap>DB Password [<a href="javascript:alert('- Enter a valid database password.\n- The password can be left blank if none is set in the database.\n- The value will be scrambled before being stored.');" title="Click on this question mark to get more information on this item.">?</a>]</td>
      <td align=left nowrap><input tabindex=3 type=password name='db_passField' size='30' maxlength='256' value='' title="Click on the question mark to the left to get more information on this item."></td>
    </tr>
    <tr>
      <td align=right nowrap>DB Name [<a href="javascript:alert('- Enter the name of the database you wish to connect to.\n- The database must already exist and be accessible.');" title="Click on this question mark to get more information on this item.">?</a>]</td>
      <td align=left nowrap><input tabindex=4 type=text name='db_nameField' size='30' maxlength='256' value='' title="Click on the question mark to the left to get more information on this item."></td>
    </tr>
<?php } ?>
    <tr>
      <td align=left nowrap colspan=2>&nbsp;</td>
    </tr>
    <tr>
      <td align=right nowrap>Sys. Admin. Email
<?php if ($type != UPGRADE_STR) {?>
        [<a href="javascript:alert('- Enter a valid email address to be set as the netjuke\'s default administrator.\n- You will need this account to start using the application.\n- We advise to also use host-based authentication at the http server level.');" title="Click on this question mark to get more information on this item.">?</a>]
<?php } else { ?>
        [<a href="javascript:alert('- Enter the email address of a current netjuke administrator account.\n- Make sure that this installer\'s host has access to the netjuke.');" title="Click on this question mark to get more information on this item.">?</a>]
<?php } ?>
      </td>
      <td align=left nowrap><input tabindex=5 type=text name='sys_admin_userField' size='30' maxlength='256' value='' title="Click on the question mark to the left to get more information on this item."></td>
    </tr>
    <tr>
      <td align=right nowrap>Admin. Password
<?php if ($type != UPGRADE_STR) {?>
        [<a href="javascript:alert('- Choose a password for the default account.\n- Must be at least 6 characters long.\n- Password is scrambled before being stored.');" title="Click on this question mark to get more information on this item.">?</a>]
<?php } else { ?>
        [<a href="javascript:alert('- Enter the password of the administrator account you chose.\n- Must be at least 6 characters long.\n- Password is scrambled before being stored.');" title="Click on this question mark to get more information on this item.">?</a>]
<?php } ?>
      </td>
      <td align=left nowrap><input tabindex=6 type=password name='sys_admin_passField' size='30' maxlength='256' value='' title="Click on the question mark to the left to get more information on this item."></td>
    </tr>
<?php if ($type != UPGRADE_STR) {?>
    <tr>
      <td align=right nowrap>Confirm Password [<a href="javascript:alert('- Confirm the password you just entered above.');" title="Click on this question mark to get more information on this item.">?</a>]</td>
      <td align=left nowrap><input tabindex=7 type=password name='sys_admin_pass2Field' size='30' maxlength='256' value='' title="Click on the question mark to the left to get more information on this item."></td>
    </tr>
<?php } ?>
    <tr>
      <td align=left nowrap colspan=2>&nbsp;</td>
    </tr>
<?php if ($type != UPGRADE_STR) {?>
    <tr>
      <td align=right valign=top nowrap>Language [<a href="javascript:alert('Choose the language you wish to use.');" title="Click on this question mark to get more information on this item.">?</a>]</td>
      <td align=left valign=top>
      	<?php echo  lang_pack_menu("lang_packField") ?>
      </td>
    </tr>
    <tr>
      <td align=left nowrap colspan=2>&nbsp;</td>
    </tr>
    <tr>
      <td align=right valign=top nowrap>User Themes [<a href="javascript:alert('- Define if you want to let every user choose their own color scheme.\n- If you are planning on embedding the application into an elaborate\n&nbsp; &nbsp;surrounding interface, using custom html header and footer, we do\n&nbsp; &nbsp;advise to disable the feature.');" title="Click on this question mark to get more information on this item.">?</a>]</td>
      <td align=left valign=top>
      	<input type=checkbox tabindex=8 name='user_themesField' value='t' title="Click on the question mark to the left to get more information on this item."> Enabled
      </td>
    </tr>
    <tr>
      <td align=left nowrap colspan=2>&nbsp;</td>
    </tr>
<?php } ?>
    <tr>
      <td align=center nowrap colspan=2>
        <input type='submit' name='do' value='<?php echo  $btn ?>' class='btn_off' tabindex='9'>
        &nbsp;
        <input type=button tabindex=10 value='Start Over' class='btn_off' onclick="top.location.href='<?php echo $_SERVER['PHP_SELF']?>';">
        &nbsp;
        <input type=button tabindex=11 value='Cancel' class='btn_off' onclick="if ( confirm('Are you sure you want to exit this installer?') ) window.close();">
      </td>
    </tr>
    <tr>
      <td align=left nowrap colspan=2>&nbsp;</td>
    </tr>
    </table>
  </td>
</tr>
</form>
</table>
</div>

</body>
</html>
