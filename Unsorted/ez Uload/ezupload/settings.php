<?
/////////////////////////////////////////////////////////////
// Program Name         : EzUpload Pro                       
// Program Version      : 2.20                                
// Program Author       : ScriptsCenter.com                  
// Supplied by          : CyKuH [WTN]                        
// Nullified and tested : CyKuH [WTN]                        
// Distribution         : via WebForum and Forums File Dumps 
//                    WTN Team `2004
/////////////////////////////////////////////////////////////
  $section = "settings";
  include( "initialize.php" );

  checklogged();
  
  if( $_POST['action']=="save" )
  {
    if( $demomode ) confirm( "No change can be saved on the demo mode" );
  
	if( $_POST['adminemail'] && !isemail($_POST['adminemail']) ) confirm( "Please enter a valid email address for the administrator" );
	if( $_POST['adminpass'] != $_POST['adminpass2'] ) confirm( "The two passwords entered are different!" );
	if( $_POST['email_method'] == "smtp" && ( $_POST['smtp_host']=="" || $_POST['smtp_port']=="" ) ) confirm( "Since you selected SMTP for the email method, please enter a valid host or port!" );
  
    $CONF->setval( $_POST['adminname'], "adminname" );
	$CONF->setval( $_POST['adminemail'], "adminemail" );
	
	$CONF->setval( $_POST['timezone'], "timezone" );
	$CONF->setval( $_POST['display_warning'], "display_warning" );
	$CONF->setval( $_POST['js_detection'], "js_detection" );
	$CONF->setval( $_POST['language_pack'], "language_pack" );

	$CONF->setval( $_POST['email_method'], "email_method" );
	$CONF->setval( $_POST['smtp_host'], "smtp_host" );
	$CONF->setval( $_POST['smtp_port'], "smtp_port" );
	
	$CONF->setval( $_POST['autodel_files'], "autodel_files" );
	$CONF->setval( $_POST['autodel_info'], "autodel_info" );
	if( !$safemode) $CONF->setval( $_POST['autodel_dir'], "autodel_dir" );
	
	if( isset($_POST['reset_adminpass']) )
	{
	  $CONF->setval( "", "adminpass" );
	}
	elseif( !empty($_POST['adminpass']) )
	{
	  $passhash = md5( $_POST['adminpass'] );
	  $CONF->setval( $passhash, "adminpass" );
	}
	
	$CONF->savedata();
	
	confirm( "Changes successfully saved", "settings.php" );
  }
  
  showheader( $section );
  
  if( $CONF->getval("adminpass")=="" && !$demomode )
  {
    showmessage( "Please enter a password below to protect the control panel" );
  }
  
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<form method="post" action="settings.php">
<input type="hidden" name="action" value="save">
<? showsession(); ?>
<tr><td>

<table width="100%" border="0" cellspacing="1" cellpadding="4" class="formtbl">
  <tr class="header">
    <td colspan="2">Administrator Information</td>
  </tr>
  <tr <?=getaltclass()?>>
    <td width="50%" valign="top">
	  <b>Admin Name</b><br>
      The name of the aministrator as it will appear on the emails sent from ezUpload.
	</td>
    <td>
      <input type="text" name="adminname" size="50" value="<?=$CONF->getval("adminname")?>">
    </td>
  </tr>
  <tr <?=getaltclass()?>>
    <td width="50%" valign="top">
	  <b>Admin Email</b><br>
      The email address used for notifications as well as for the return address of sent email.
	</td>
    <td>
      <input type="text" name="adminemail" size="50" value="<?=$CONF->getval("adminemail")?>">
	</td>
  </tr>
  <tr <?=getaltclass()?>>
    <td width="50%" valign="top">
	  <b>Admin Password</b><br>
      The password to access the control panel, very important for your security. Enter password twice to set it or check "Reset?" to clear it.
	</td>
    <td>
      <input type="password" name="adminpass" size="16">&nbsp;<input type="password" name="adminpass2" size="16">
	  &nbsp;Reset? <input type="checkbox" name="reset_adminpass" value="1">
	</td>
  </tr>
</table>

<? showspace(); ?>

<table width="100%" border="0" cellspacing="1" cellpadding="4" class="formtbl">
  <tr class="header">
    <td colspan="2">General Options</td>
  </tr>
  <tr <?=getaltclass()?>>
    <td width="50%" valign="top">
	  <b>Time Zone</b><br>
      Which timezone do you want to use for the upload dates on the online browser?
	</td>
    <td>
	  <? $timezone = $CONF->getval("timezone"); ?>
      <select name="timezone">
	    <option value="-12" <? if($timezone==-12) echo("selected"); ?>>(GMT -12:00) Eniwetok, Kwajalein</option>
		<option value="-11" <? if($timezone==-11) echo("selected"); ?>>(GMT -11:00) Midway Island, Samoa</option>
		<option value="-10" <? if($timezone==-10) echo("selected"); ?>>(GMT -10:00) Hawaii</option>
		<option value="-9" <? if($timezone==-9) echo("selected"); ?>>(GMT -9:00) Alaska</option>
		<option value="-8" <? if($timezone==-8) echo("selected"); ?>>(GMT -8:00) Pacific Time, Tijuana</option>
		<option value="-7" <? if($timezone==-7) echo("selected"); ?>>(GMT -7:00) Mountain Time, Arizona</option>
		<option value="-6" <? if($timezone==-6) echo("selected"); ?>>(GMT -6:00) Central Time, Mexico City</option>
		<option value="-5" <? if($timezone==-5) echo("selected"); ?>>(GMT -5:00) Eastern Time, Bogota, Lima</option>
		<option value="-4" <? if($timezone==-4) echo("selected"); ?>>(GMT -4:00) Atlantic Time, Caracas</option>
		<option value="-3.5" <? if($timezone==-3.5) echo("selected"); ?>>(GMT -3:30) Newfoundland</option>
		<option value="-3" <? if($timezone==-3) echo("selected"); ?>>(GMT -3:00) Brassila, Buenos Aires</option>
		<option value="-2" <? if($timezone==-2) echo("selected"); ?>>(GMT -2:00) Mid-Atlantic, Ascension Is.</option>
		<option value="-1" <? if($timezone==-1) echo("selected"); ?>>(GMT -1:00) Azores, Cape Verde Islands</option>
		<option value="0" <? if($timezone==0) echo("selected"); ?>>(GMT) London, Casablanca, Dublin</option>
		<option value="1" <? if($timezone==1) echo("selected"); ?>>(GMT +1:00) Amsterdam, Berlin, Madrid</option>
		<option value="2" <? if($timezone==2) echo("selected"); ?>>(GMT +2:00) Cairo, Helsinki, Kaliningrad</option>
		<option value="3" <? if($timezone==3) echo("selected"); ?>>(GMT +3:00) Baghdad, Riyadh, Moscow</option>
		<option value="3.5" <? if($timezone==3.5) echo("selected"); ?>>(GMT +3:30) Tehran</option>
		<option value="4" <? if($timezone==4) echo("selected"); ?>>(GMT +4:00) Abu Dhabi, Baku, Muscat</option>
		<option value="4.5" <? if($timezone==4.5) echo("selected"); ?>>(GMT +4:30) Kabul</option>
		<option value="5" <? if($timezone==5) echo("selected"); ?>>(GMT +5:00) Ekaterinburg, Islamabad</option>
		<option value="5.5" <? if($timezone==5.5) echo("selected"); ?>>(GMT +5:30) Bombay, Calcutta, Madras</option>
		<option value="6" <? if($timezone==6) echo("selected"); ?>>(GMT +6:00) Almaty, Colombo, Dhaka</option>
		<option value="6.5" <? if($timezone==6.5) echo("selected"); ?>>(GMT +6:30) Rangoon</option>
		<option value="7" <? if($timezone==7) echo("selected"); ?>>(GMT +7:00) Bangkok, Hanoi, Jakarta</option>
		<option value="8" <? if($timezone==8) echo("selected"); ?>>(GMT +8:00) Beijing, Hong Kong, Perth</option>
		<option value="9" <? if($timezone==9) echo("selected"); ?>>(GMT +9:00) Sapporo, Seoul, Tokyo</option>
        <option value="9.5" <? if($timezone==9.5) echo("selected"); ?>>(GMT +9:30) Adelaide, Darwin</option>
		<option value="10" <? if($timezone==10) echo("selected"); ?>>(GMT +10:00) Canberra, Melbourne</option>
		<option value="11" <? if($timezone==11) echo("selected"); ?>>(GMT +11:00) Magadan, New Caledonia</option>
		<option value="12" <? if($timezone==12) echo("selected"); ?>>(GMT +12:00) Auckland, Wellington, Fiji</option></select>
  
	</td>
  </tr>
  <tr <?=getaltclass()?>>
    <td width="50%" valign="top">
	  <b>Form Language</b><br>
      Here you can select the language of the upload form.
	</td>
    <td>
	  <select name="language_pack">

<?
  $handle = opendir( "lang/" );
	
  while( false !== ($file = readdir($handle)) )
  { 
	// check the file is a language pack
    if( strstr($file, "lang_") && strstr($file, ".php") && !is_dir("lang/$file") )
	{
	  $i++;
      include( "lang/$file" );
	}
  }
  
  if( !empty($LANGPACK) )
  {
	foreach( $LANGPACK AS $pack )
	{
	  echo( "<option value='{$pack['file']}'" );
	  if( $CONF->getval("language_pack")==$pack['file'] ) echo( " selected" );
	  echo( ">{$pack['name']} ({$pack['file']})</option>" );
	}
  }
  else
  {
	echo( "<option value='lang_english.php'>" );
	echo( "No language pack found!</option>" );
  }
	
  closedir( $handle );
?>

	  </select>	</td>
  </tr>
</table>

<? showspace(); ?>

<table width="100%" border="0" cellspacing="1" cellpadding="4" class="formtbl">
  <tr class="header">
    <td colspan="2">Email Settings</td>
  </tr>
  <tr <?=getaltclass()?>>
    <td width="50%" valign="top">
	  <b>Email Method</b><br>
      How do you want the script to send emails? Windows- and Mac-based servers should use SMTP.
	</td>
    <td>
	  <input type="radio" name="email_method" value="default" <? if($CONF->getval("email_method")=="default") echo("checked"); ?>> Use default PHP mailer (usually sendmail)<br>
      <input type="radio" name="email_method" value="smtp" <? if($CONF->getval("email_method")=="smtp") echo("checked"); ?>> Send all emails through SMTP
    </td>
  </tr>
  <tr <?=getaltclass()?>>
    <td width="50%" valign="top">
	  <b>SMTP Host</b><br>
      You only need to setup this if you selected SMTP above. Use localhost if SMTP is on the same server. Default port is 25.
	</td>
    <td>
      <input type="text" name="smtp_host" size="34" value="<?=$CONF->getval("smtp_host")?>">
	  <b>Port</b>
	  <input type="text" name="smtp_port" size="5" value="<?=$CONF->getval("smtp_port")?>">
	</td>
  </tr>
</table>

<? showspace(); ?>

<table width="100%" border="0" cellspacing="1" cellpadding="4" class="formtbl">
  <tr class="header">
    <td colspan="2">Javascript Use</td>
  </tr>
  <tr <?=getaltclass()?>>
    <td width="50%" valign="top">
	  <b>Use Javascript Detection?</b><br>
      Do you want the script to use javascript to check some of the form fields before they are sent? This is not necessary but recommended.
	</td>
    <td>
      <input type="radio" name="js_detection" value="1" <? if($CONF->getval("js_detection")) echo("checked"); ?>> Yes
	  <input type="radio" name="js_detection" value="0" <? if(!$CONF->getval("js_detection")) echo("checked"); ?>> No
	</td>
  </tr>
  <tr <?=getaltclass()?>>
    <td width="50%" valign="top">
	  <b>Large Upload Warning?</b><br>
      If you must accept large files, this setting will show a warning before the submission that their uploads may take time.
	</td>
    <td>
      <input type="radio" name="display_warning" value="1" <? if($CONF->getval("display_warning")) echo("checked"); ?>> Yes
	  <input type="radio" name="display_warning" value="0" <? if(!$CONF->getval("display_warning")) echo("checked"); ?>> No
	</td>
  </tr>
</table>

<? showspace(); ?>

<table width="100%" border="0" cellspacing="1" cellpadding="4" class="formtbl">
  <tr class="header">
    <td colspan="2">Automatic Deletion</td>
  </tr>
  <tr <?=getaltclass()?>>
    <td width="50%" valign="top">
	  <b>Files Deletion</b><br>
      When a file can't be found, do you want the script to automatically delete it from the online browser? (If not, it will still be displayed but not clickable)
	</td>
    <td>
      <input type="radio" name="autodel_files" value="1" <? if($CONF->getval("autodel_files")) echo("checked"); ?>> Yes
	  <input type="radio" name="autodel_files" value="0" <? if(!$CONF->getval("autodel_files")) echo("checked"); ?>> No
    </td>
  </tr>
  <tr <?=getaltclass()?>>
    <td width="50%" valign="top">
	  <b>Uploads Deletion</b><br>
      Do you want the script to automatically delete the upload informations when all related files have been deleted? (either by FTP or through online browser)
	</td>
    <td>
      <input type="radio" name="autodel_info" value="1" <? if($CONF->getval("autodel_info")) echo("checked"); ?>> Yes
	  <input type="radio" name="autodel_info" value="0" <? if(!$CONF->getval("autodel_info")) echo("checked"); ?>> No
	</td>
  </tr>
  
  <? if( !$safemode ): ?>
  
  <tr <?=getaltclass()?>>
    <td width="50%" valign="top">
	  <b>Directories Deletion</b><br>
      Do you want the script to automatically delete directories which are empty? (Not recommended if you let users select a directory) 
	</td>
    <td>
      <input type="radio" name="autodel_dir" value="1" <? if($CONF->getval("autodel_dir")) echo("checked"); ?>> Yes
	  <input type="radio" name="autodel_dir" value="0" <? if(!$CONF->getval("autodel_dir")) echo("checked"); ?>> No
	</td>
  </tr>
  
  <? endif; ?>
  
</table>

<? showspace(); ?>
		
<table width="100%" border="0" cellspacing="1" cellpadding="4" class="formtbl">
  <tr align="center" class="header">
    <td colspan="2">
      <input type="submit" name="edit" value="Save Changes">
    </td>
  </tr>
</table>

</td></tr>
</form>
</table>
			
<? showfooter($section); ?>