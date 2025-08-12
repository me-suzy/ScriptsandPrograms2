<?
/////////////////////////////////////////////////////////////
// Program Name         : Autolinks Professional            
// Program Version      : 2.0                               
// Program Author       : ScriptsCenter                     
// Supplied by          : CyKuH [WTN] , Stive [WTN]         
// Nullified by         : CyKuH [WTN]                       
// Distribution         : via WebForum and Forums File Dumps
//                   (c) WTN Team `2002
/////////////////////////////////////////////////////////////

  include( "cp_initialize.php" );

  function showconf( $name, $type )
  {
	global $CONF;

    $value = htmlspecialchars( $CONF[$name], ENT_QUOTES );

    switch( $type )
    {
      case "text": echo( "<input type='text' size='35' name='newconf[$name]' value='{$value}' maxlength='255'>" ); break;
      case "password": echo( "<input type='password' size='35' name='newconf[$name]' value='{$value}' maxlength='255'>" ); break;
      case "radio": if($value=="1") { $yes="checked"; } else { $no="checked"; }
		    echo( "<input type='radio' name='newconf[$name]' value='1' $yes>Yes<input type='radio' name='newconf[$name]' value='0' $no>No" ); break;
      case "smalltext": echo( "<input type='text' size='15' name='newconf[$name]' value='{$value}' maxlength='255'>" ); break;
    }
  }

  if( $submitted=="editsettings" )
  {
    // check the min/max values
	if( $newconf[desc_max]>255 ) $newconf[desc_max] = 255;
	if( $newconf[name_max]>32 ) $newconf[name_max] = 32;
	if( $newconf[name_min]<1 ) $newconf[name_min] = 1;

    while( list($k, $v) = each($newconf) )
	{
	  $v = addslashes( $v );
	  mysql_query( "UPDATE al_conf SET value='$v' WHERE name='$k' LIMIT 1" );
	}
	
	// reload the configs
	$CONF = loadconf();
	
	$notice = "Settings successfully updated.";
  }

?>

<html>
<head>
<link rel="stylesheet" href="main.css">
<SCRIPT SRC="autofill.js" LANGUAGE="JavaScript"></script>
</head>
<body>
<? showmessage(); ?>
<form method="post" action="<? echo($PHP_SELF); ?>">
<input type="hidden" name="submitted" value="editsettings">
  <table cellpadding='0' cellspacing='0' border='0' width='100%' bgcolor="#9999CC">
  <tr>
    <td>
        <table cellpadding='4' cellspacing='1' border='0' width='100%'>
          <tr>
            <td colspan='2'><font color="#FFFFFF" size="1">ADMINISTRATION</font></td>
          </tr>
          <tr bgcolor="#F5F5F5">
            <td width="65%">
              <p><b>Admin Name</b><br>
              <font size="1">Your name will be used on all automatic emails sent through AutoLinks.</font></p>
            </td>
            <td width="35%">
              <? showconf("admin_name", "text"); ?>
          </td>
          </tr>
          <tr bgcolor="#F5F5F5">
            <td width="65%">
              <p><b>Admin Email</b><br>
              <font size="1">Your email address will be used in the header of all automatic emails sent through AutoLinks as well for notifications and emails that  referer sent using the online contact form.</font></p>
            </td>
            <td width="35%">
              <? showconf("admin_email", "text"); ?>
          </td>
          </tr>
          <tr bgcolor="#F5F5F5">
            <td width="65%">
              <p><b>Admin Password</b><br>
              <font size="1">The password  will be used to prevent others to access to  the control panel. Note that it requires cookies so make sure cookies are turned on on your browser before entering a password.</font></p>
            </td>
            <td width="35%">
              <? showconf("admin_pass", "password"); ?>
          </td>
          </tr>
        </table>
    </td>
  </tr>
</table>
  <br>
<br>
  <table cellpadding='0' cellspacing='0' border='0' width='100%' bgcolor="#9999CC">
  <tr>
    <td>
        <table cellpadding='4' cellspacing='1' border='0' width='100%'>
          <tr> 
            <td colspan='2'><font color="#FFFFFF" size="1">IMAGES LINKING</font></td>
          </tr>
          <tr bgcolor="#F5F5F5"> 
            <td width="65%"> 
              <p><b> Banners Linking</b><br>
                <font size="1">Banners are 468x60 images that you can use to send 
                more visitors to referers. Referers can upload their banners when 
                they signup. Turn this off if you don't plan to use banners.</font></p>
            </td>
            <td width="35%"> 
              <? showconf("link_banners", "radio"); ?>
            </td>
          </tr>
          <tr bgcolor="#F5F5F5"> 
            <td width="65%"> 
              <p><b>Buttons Linking</b><br>
                <font size="1">Buttons are 88x31 images that you can use to send 
                more visitors to referers. Referers can upload their buttons when 
                they signup. Turn this off if you don't plan to use buttons.</font></p>
            </td>
            <td width="35%"> 
              <? showconf("link_buttons", "radio"); ?>
            </td>
          </tr>
          <tr bgcolor="#F5F5F5"> 
            <td width="65%"> 
              <p><b>Thumbs Linking</b><br>
                <font size="1">Thumbnails are 66x100 pictures,  usually 
                a small version of a larger image.  Referers can upload their thumbs when they 
                signup. If it's on, they also have to enter a &quot;thumb name&quot; which will appear above and below the images. Turn this off if you don't plan to use thumbs.</font></p>
            </td>
            <td width="35%"> 
              <? showconf("link_thumbs", "radio"); ?>
            </td>
          </tr>
          <tr bgcolor="#F5F5F5"> 
            <td width="65%"> 
              <p><b>Allow Hotlinking?</b><br>
                <font size="1">Turn this off if you don't want referrers to directly 
                use the banners on your site. A notice will tell them not to do 
                that. The advantage of letting them do that is that you can update 
                the banner and it will automatically change on the referrers' 
                site.</font></p>
            </td>
            <td width="35%"> 
              <? showconf("hotlink", "radio"); ?>
            </td>
          </tr>
        </table>
    </td>
  </tr>
</table>
<br>
  <br>
  <table cellpadding='0' cellspacing='0' border='0' width='100%' bgcolor="#9999CC">
    <tr>
      <td>
        <table cellpadding='4' cellspacing='1' border='0' width='100%'>
          <tr>
            <td colspan='2'><font color="#FFFFFF" size="1"> NOTIFICATION OPTIONS</font></td>
          </tr>
        
          <tr bgcolor="#F5F5F5">
            <td width="65%">
              <p><b>New User Notification</b><br>
              <font size="1">Send the administrator a notification when new users signup?</font></p>
            </td>
            <td width="35%">
              <? showconf("notify_new", "radio"); ?>
          </td>
          </tr>
          <tr bgcolor="#F5F5F5">
            <td width="65%">
              <p><b>Confirmation</b><br>
                <font size="1">Send a confirmation email when new referer sign 
                up? (Recommended) A confirmation email is always sent if you are 
                using the moderation feature.</font></p>
            </td>
            <td width="35%">
            
              <? showconf("confirm_new", "radio"); ?>
          </td>
          </tr>
        
        
          <tr bgcolor="#F5F5F5">
            <td width="65%">
              <p><b>Ban Notification</b><br>
              <font size="1">Send a notification to referrers when their account has been unactivated or deleted?</font></p>
            </td>
            <td width="35%">
              <? showconf("notify_ban", "radio"); ?>
          </td>
          </tr>
        
        
        </table>
      </td>
    </tr>
  </table>
  <br>
  <br>
  <table cellpadding='0' cellspacing='0' border='0' width='100%' bgcolor="#9999CC">
  <tr>
    <td>
        <table cellpadding='4' cellspacing='1' border='0' width='100%'>
          <tr>
            <td colspan='2'><font color="#FFFFFF" size="1">SIGNUP OPTIONS</font></td>
          </tr>
          <tr bgcolor="#F5F5F5">
            <td width="65%">
              <p><b> Moderation</b><br>
              <font size="1">Approve all new referers before their link shows up? This is recommended if you want to make sure no one will be able to abuse  the automatic link exchange features.</font></p>
            </td>
            <td width="35%">
              <? showconf("moderate_new", "radio"); ?>
          </td>
          </tr>
          <tr bgcolor="#F5F5F5">
            <td width="65%">
              <p><b> Email Verification</b><br>
              <font size="1">If enabled, AutoLinks will send an email to the referrer and he will have to click a link on an email  if he wants his site to be activated. It can be an additional annoyance for referrers looking for a quick signup.</font></p>
            </td>
            <td width="35%" bgcolor="#F5F5F5">
              <? showconf("verify_new", "radio"); ?>
          </td>
          </tr>
          <tr bgcolor="#F5F5F5">
            <td width="65%">
              <p><b>Name Min/Max</b><br>
              <font size="1">Minimum and maximum number of characters that can be used for the referrer's name. The maximum can't more than 32.</font></p>
            </td>
            <td width="35%">
              <table width="230" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td>
                    <? showconf("name_min", "smalltext"); ?>
					&nbsp;
                    <? showconf("name_max", "smalltext"); ?>
                    </td>
                </tr>
              </table>
            </td>
          </tr>
          
          <tr bgcolor="#F5F5F5">
            <td width="65%">
              <p><b>Description Min/Max</b><br>
              <font size="1">Minimum and maximum number of characters that can be used for the referrer's description. The maximum can't more than 255.</font></p>
            </td>
            <td width="35%">
              <table width="230" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td>
                    <? showconf("desc_min", "smalltext"); ?>
					&nbsp;
                    <? showconf("desc_max", "smalltext"); ?>
                    </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
    </td>
  </tr>
</table>
  <br>
  <br>
  <table cellpadding='0' cellspacing='0' border='0' width='100%' bgcolor="#9999CC">
    <tr>
      <td>
        <table cellpadding='4' cellspacing='1' border='0' width='100%'>
          <tr>
            <td colspan='2'><font color="#FFFFFF" size="1"> QUALITY &amp; ANTI-CHEAT OPTIONS</font></td>
          </tr>
          <tr bgcolor="#F5F5F5">
            <td width="65%">
              <p><b>Count Referred Clicks?</b><br>
              <font size="1">This is an option to know how many links the visitors send by a referrer has clicked. You can use it on the tags (Top Quality referrers) or to effecitvely detect cheaters (those who cheat will have a very low amount of referred clicks). The downside is that it slows down a bit the visitors who don't have their cookies turned on, since the script has to compare the IPs.</font></p>
            </td>
            <td width="35%">
              <? showconf("count_clicks", "radio"); ?>
          </td>
          </tr>
          
        
          <tr bgcolor="#F5F5F5">
            <td width="65%">
              <p><b>Check Cookie?</b><br>
              <font size="1">If this is turned on, a 24-hour cookie will be setup on all new visitors. Although it's not very safe and some visitors have cookies turned off, it's recommended to let this on because it will be faster for visitors with a cookie.</font></p>
            </td>
            <td width="35%">
              <? showconf("unique_cookie", "radio"); ?>
          </td>
          </tr>
          <tr bgcolor="#F5F5F5">
            <td width="65%">
              <p><b>Check IP Address?</b><br>
                <font size="1">If this is turned on, the script will check all IPs of the past 24 hours and not count the hitsin (and  referred clicks) unless the IP is unique. This is very effective but the downside is that this can slow down the script if you have a large amount of visitors.</font></p>
            </td>
            <td width="35%">
            
              <? showconf("unique_ip", "radio"); ?>
          </td>
          </tr>
        
        
          <tr bgcolor="#F5F5F5">
            <td width="65%">
              <p><b>Find Host Domain?</b><br>
              <font size="1">Host domains are better to recognize a cheater because you'll get something like *.calif.aol.com in addition to the IP address  on the hits log. However if it slows down the script (up to 30 seconds), then your DNS server is not properly configured and you should turn this off.</font></p>
            </td>
            <td width="35%">
              <? showconf("find_host", "radio"); ?>
          </td>
          </tr>
        
        
        </table>
      </td>
    </tr>
  </table>
  <br>
<br>
  <table cellpadding='4' cellspacing='0' border='0' width='100%' bgcolor="#9999CC">
  <tr>
    <td align="center">
      <input type="submit" value="  Update Settings  " name="submit">
      </td>
  </tr>
</table>
</form>
</body>
</html>
