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

  if( $submitted=="refnotice" )
  {
    if( $title=="" || $content=="" )
	{
	  $notice = "Some required fields are missing or incorrect";
	}
	else
	{
	  switch( $refselect )
	  {
	    case "status": $where = "status=$status"; break;
	    case "category": $where = "category='$category' AND status=1"; break;
	    case "reflogin": $where = "login='$reflogin'"; break;
	  }
	
	  // it's more complicated for sitelogin...
	  if( $refselect=="sitelogin" )
	  {
	    // get the categories accepted by this site
	    $res_site = mysql_query( "SELECT * FROM al_site WHERE login='$sitelogin' LIMIT 1" );
	    $site = mysql_fetch_array( $res_site );
	    $catarray = explode( ",", $site['categories'] );
	  
	    while( list($k, $v) = each($catarray) )
	    {
	      if( $k>0 ) $where .= "OR ";
		  $where .= "category='$v' ";
	    }
	  }

	  $res_ref = mysql_query( "SELECT * FROM al_ref WHERE $where" );
	  $numrefs = mysql_num_rows( $res_ref );
	
	  $content = stripslashes( $content );
	  $title = stripslashes( $title ); 

	  while( $ref = mysql_fetch_array($res_ref) )
	  {
	    $varcontent = fill_ref_vars( $content, $ref );
	    $vartitle = fill_ref_vars( $title, $ref );
	
	    mail( $ref[email], $vartitle, $varcontent, "From: {$CONF[admin_name]} <{$CONF[admin_email]}>\nReply-To: {$CONF[admin_email]}" );
	  }

	  $notice = "Notifications successfully sent to $numrefs referrer(s).";
    }
  }
  else
  {
    // enter default values
    $status = 1;
  }

  $info = "On this page you can email to one or several referrers. On the title and message, you may use any of the following variables which will be converted according to each referrer's data:";

?>

<html>
<head>
<link rel="stylesheet" href="main.css">
</head>
<body>
<? showmessage(); ?>
<table width='100%' border='0' cellspacing='0' cellpadding='0'><tr><td width='50%'><ul><li><b>[name] </b>Referrer name</li><li><b>[login]</b> Referrer login</li><li><b>[pass]</b> Referrer password</li><li><b>[url]</b> Referrer URL</li><li><b>[email]</b> Webmaster email</li><li><b>[code]</b> Activation code</li></ul></td><td width='50%'><ul><li><b>[refarea]</b> Referrer's area</li><li><b>[category]</b> Referrer category</li><li><b>[sites]</b> Sites accepting  category</li><li><b>[links]</b> URL  to link these sites</li><li><b>[admin_name]</b> Your name</li><li><b>[admin_email]</b> Your email</li></ul></td></tr></table>
<form method="post" action="<? echo($PHP_SELF); ?>">
<input type="hidden" name="submitted" value="refnotice">
  <table cellpadding='0' cellspacing='0' border='0' width='100%' bgcolor="#9999CC">
  <tr>
    <td>
        <table cellpadding='4' cellspacing='1' border='0' width='100%'>
          <tr class='tblhead'>
            <td colspan='2'><font color="#FFFFFF" size="1">SEND NOTIFICATION</font></td>
          </tr>
          <tr bgcolor="#F5F5F5">
            <td width="50%" valign="top">
                
              <p><b>Referrers Selection</b><br>
              <font size="1">Choose here who you'll send the notification to.</font></p>
            </td>
            <td width="50%">
              <p>
                <input type="radio" name="refselect" value="status" <? if($refselect=="status") echo("checked"); ?>>
              Referrers with status: 
                <select name="status">
                  <option value="1" <? if($status==1) echo("selected"); ?>>Active</option>
                  <option value="0" <? if($status==0) echo("selected"); ?>>Banned</option>
                  <option value="2" <? if($status==2) echo("selected"); ?>>Pending</option>
				  <option value="3" <? if($status==3) echo("selected"); ?>>Unverified</option>
                </select>
                <br>
				
<? if( multicats() ): ?>
				
                <input type="radio" name="refselect" value="category" <? if($refselect=="category") echo("checked"); ?>>
              Referrers in category:
<select name="category">

<? 		  
  $res_cat = mysql_query( "SELECT * FROM al_cat WHERE name!='' ORDER BY name" );

  for( $i=0; $i<mysql_num_rows($res_cat); $i++ )
  {
    $cat = mysql_fetch_array( $res_cat );
	
	// check if category is accepted in 1+ site
	$res_site = mysql_query( "SELECT * FROM al_site WHERE status=1 AND FIND_IN_SET('{$cat['id']}', categories)>0" );
	
	if( mysql_num_rows($res_site)>0 )
	{
      echo( "<option value='{$cat['id']}'" );
	  if( $cat['id']==$category ) echo( " selected" );
	  echo( ">{$cat['name']}</option>" );
	}
  }
?>

              </select>
                <br>
				
<?
  endif; 

  $res_site = mysql_query( "SELECT * FROM al_site WHERE status=1" );
  
  if( mysql_num_rows($res_site)>1 ):
  
?>
				
                <input type="radio" name="refselect" value="sitelogin" <? if($refselect=="sitelogin") echo("checked"); ?>>
                Referrers linking to:
                <select name="sitelogin">

<?
  $res_site = mysql_query( "SELECT * FROM al_site WHERE status=1" );

  while( $site = mysql_fetch_array($res_site) )
  {
    echo( "<option value='{$site['login']}'" );
	if( $site['login']==$sitelogin ) echo( " selected" );
	echo( ">{$site['name']}</option>" );
  }
?>

              </select>
                <br>
				
<? endif; ?>
				
                <input type="radio" name="refselect" value="reflogin" <? if($refselect=="reflogin") echo("checked"); ?>>
              Only this referrer:
                <select name="reflogin">

<?
  $res_ref = mysql_query( "SELECT * FROM al_ref" );

  while( $ref = mysql_fetch_array($res_ref) )
  {
    echo( "<option value='{$ref['login']}'" );
	if( $ref['login']==$reflogin ) echo( " selected" );
	echo( ">{$ref['name']}</option>" );
  }
?>

              </select>
              </p>
            </td>
          </tr>
          <tr bgcolor="#F5F5F5">
            <td width="50%" valign="top">
              <p><b>Email Title</b><br>
              <font size="1">The title of the email you'll send. You may use any of the variables listed above.</font></p>
            </td>
            <td width="50%">
              <input type="text" name="title" size="61" value="<? echo($title); ?>">
            </td>
          </tr>
          <tr bgcolor="#F5F5F5">
            <td width="50%" valign="top">
              <p><b>Email Content</b><br>
              <font size="1">The body of the email. You may use any of the variables listed above</font></p>
            </td>
            <td width="50%">
              <textarea name="content" rows="15" cols="60"><? echo($content); ?></textarea>
            </td>
          </tr>
        </table>
    </td>
  </tr>
</table>
  <table cellpadding='4' cellspacing='0' border='0' width='100%' bgcolor="#9999CC">
  <tr>
    <td align="center">
        <input type="submit" value="  Send Notification (Click Once)" name="submit">
      </td>
  </tr>
</table>
</form>
</body>
</html>
