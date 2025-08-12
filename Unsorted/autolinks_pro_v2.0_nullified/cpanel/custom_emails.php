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

  function showtempl( $login )
  {
    $res_templ = mysql_query( "SELECT * FROM al_email WHERE login='$login' LIMIT 1" );
	$templ = mysql_fetch_array( $res_templ );

    echo( "<input type='text' name='title[{$templ[login]}]' size='60' maxlength='100' value='{$templ[title]}'><BR>" );
	echo( "<textarea name='content[{$templ[login]}]' rows='10' cols='60'>{$templ[content]}</textarea>" );
  }
  
  if( $submitted=="customemails" )
  {
    while( list($k, $v) = each($title) )
	{
	  $title[$k] = addslashes( $title[$k] );
	  $content[$k] = addslashes( $content[$k] );
	  mysql_query( "UPDATE al_email SET title='{$title[$k]}', content='{$content[$k]}' WHERE login='$k' LIMIT 1" );
	}
  
	$notice = "Email templates successfully updated.<BR>";
  }

  $info = "On this page you can customize the emails that are automatically sent by AutoLinks. On the templates, you may use any of the following variables which will be converted according to the referrer's data:";
  
?>

<html>
<head>
<link rel="stylesheet" href="main.css">
</head>
<body>
<? showmessage(); ?>
<table width='100%' border='0' cellspacing='0' cellpadding='0'><tr><td width='50%'><ul><li><b>[name] </b>Referrer name</li><li><b>[login]</b> Referrer login</li><li><b>[pass]</b> Referrer password</li><li><b>[url]</b> Referrer URL</li><li><b>[email]</b> Webmaster email</li><li><b>[code]</b> Activation code</li></ul></td><td width='50%'><ul><li><b>[refarea]</b> Referrer's area</li><li><b>[category]</b> Referrer category</li><li><b>[sites]</b> Sites accepting  category</li><li><b>[links]</b> URL  to link these sites</li><li><b>[admin_name]</b> Your name</li><li><b>[admin_email]</b> Your email</li></ul></td></tr></table>
<form method="post" action="<? echo($PHP_SELF); ?>">
<input type="hidden" name="submitted" value="customemails">
  <table cellpadding='0' cellspacing='0' border='0' width='100%' bgcolor="#9999CC">
  <tr>
    <td>
        <table cellpadding='4' cellspacing='1' border='0' width='100%'>
          <tr class='tblhead'>
            <td colspan='2'><font color="#FFFFFF" size="1">CUSTOMIZE EMAILS</font></td>
          </tr>
          <tr bgcolor="#F5F5F5">
            <td width="45%" valign="top">
              <p><b>Invitation</b><br>
              <font size="1">Email sent when you use the auto-signup invitation feature.</font></p>
            </td>
            <td width="55%">
              <? showtempl("invite"); ?>
            </td>
          </tr>
          <tr bgcolor="#F5F5F5">
            <td width="45%" valign="top">
              <p><b>Confirmation</b><br>
              <font size="1">Email sent to a referrer just after he signed up. Only if you turned off the moderation feature and choose to send a confirmation email.</font></p>
            </td>
            <td width="55%">
              <? showtempl("confirm"); ?>
            </td>
          </tr>
          <tr bgcolor="#F5F5F5">
            <td width="45%" valign="top">
              <p><b>Accept (Moderation)</b><br>
              <font size="1">Email sent when a pending referrer has been accepted. Only if you turned on the moderation feature.</font></p>
            </td>
            <td width="55%">
              <? showtempl("mod_accept"); ?>
            </td>
          </tr>
          <tr bgcolor="#F5F5F5">
            <td width="45%" valign="top">
              <p><b>Refuse (Moderation)</b><br>
              <font size="1">Email sent when a pending referrer has been refused. Only if you turned on the moderation feature.</font></p>
            </td>
            <td width="55%">
              <? showtempl("mod_refuse"); ?>
            </td>
          </tr>
          <tr bgcolor="#F5F5F5">
            <td width="45%" valign="top">
              <p><b>Ban Notification</b><br>
              <font size="1">Email sent when a referrer has been banned. You can choose not to send this notification in the settings.</font></p>
            </td>
            <td width="55%">
              <? showtempl("ban"); ?>
            </td>
          </tr>
          <tr bgcolor="#F5F5F5">
            <td width="45%" valign="top">
              <p><b>New Referrer</b><br>
              <font size="1">Email sent to you when when a new referrer signed up. You can choose not to send this notification in the settings.</font></p>
            </td>
            <td width="55%">
              <? showtempl("new_ref"); ?>
            </td>
          </tr>
          <tr bgcolor="#F5F5F5">
            <td width="45%" valign="top">
              <p><b>Email Verification</b><br>
              <font size="1">If you chose to turn on the email verification, this message will be sent to new referrers after they signed up.</font></p>
            </td>
            <td width="55%">
              <? showtempl("verify"); ?>
            </td>
          </tr>
          
          
          <tr bgcolor="#F5F5F5">
            <td width="45%" valign="top">
              <p><b>Password Retrieval</b><br>
              <font size="1">Email sent when a referrer lost his password.</font></p>
            </td>
            <td width="55%">
              <? showtempl("pass_send"); ?>
            </td>
          </tr>
        </table>
    </td>
  </tr>
</table>
  <table cellpadding='4' cellspacing='0' border='0' width='100%' bgcolor="#9999CC">
  <tr>
    <td align="center">
        <input type="submit" value="  Update Templates  " name="submit">
      </td>
  </tr>
</table>
</form>
</body>
</html>
