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
  
  if( $submitted=="updatealurl" )
  {
    mysql_query( "UPDATE al_site SET alurl='$alurl' WHERE login='$login' LIMIT 1" );
  }
  
  $res_site = mysql_query( "SELECT * FROM al_site WHERE login='$login' LIMIT 1" );
  if( !mysql_num_rows($res_site) ) header( "Location: site_select.php" );
  $site = mysql_fetch_array( $res_site );
	  
  // find the relative path to autolinks dir
  $aldir = getaldir( $site[alurl] );
  
?>

<html>
<head>
<link rel="stylesheet" href="main.css">
</head>
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><b>1. Create the AutoLinks directory and chmod it.</b></td>
  </tr>
  <tr>
    <td>
      <table width="100%" border="0" cellspacing="0" cellpadding="10">
        <tr>
          <td>
            <p>First of all, you need to create an AutoLinks directory within 
              <? echo( $site[name] . "'s" ); ?>
               structure. This directory will contain all the code to display, generate and store tags/images, as well as the referrers area.   According to your settings, it must be located at the URL below. </p>
            <table width="100%" border="0" cellspacing="0" cellpadding="2">
			<form method="get" action="<?=$PHP_SELF?>">
			<input type="hidden" name="login" value="<?=$login?>">
			<input type="hidden" name="submitted" value="updatealurl">
              <tr>
                <td width="30">&nbsp;</td>
                <td>
                    <input type="text" name="alurl" size="50" value="<?=$site[alurl]?>">
                  <input type="submit" name="Submit" value="Update">
                </td>
              </tr>
			</form>
            </table>
		<!--CyKuH-->
            </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td height="30"></td>
  </tr>
  <tr>
    <td><b>2. Setup the variables and upload the files.</b></td>
  </tr>
  <tr>
    <td>
      <table width="100%" border="0" cellspacing="0" cellpadding="10">
        <tr>
          <td>
            <p>The files to upload are located in the /autolinks/ directory in the .zip file provided when you purchased the script. Before uploading them,  create a file called 'variables.php' inside the directory  using the information below. Please note that the $sitelogin variable depends on the site where you install it so if you copy the directory to another site,  make sure you change the $sitelogin variable accordingly.</p>
            <table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="30">&nbsp;</td>
                <td align="right">
                  <textarea name="textfield" cols="49" rows="7">&lt;?php
  $mysql_host = &quot;<?=$mysql_host?>&quot;;
  $mysql_user = &quot;<?=$mysql_user?>&quot;;
  $mysql_pass = &quot;<?=$mysql_pass?>&quot;;
  $mysql_db = &quot;<?=$mysql_db?>&quot;;
  $sitelogin = &quot;<?=$login?>&quot;;
?&gt;</textarea>
                </td>
              </tr>
              <tr>
                <td width="30">&nbsp;</td>
                <td align="right"><a href="dl_variables.php?login=<?=$login?>"><font size="1">download variables.php file</font></a></td>
              </tr>
            </table>
            
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td height="30"></td>
  </tr>
  <tr>
    <td><b>3. Replace the autolinks.php (optional)</b></td>
  </tr>
  <tr>
    <td>
      <table width="100%" border="0" cellspacing="0" cellpadding="10">
        <tr>
          <td>
            <p>If you previously installed version 1.x, you have to know that the autolinks.php file is no more used, the referrers must send hits to /autolinks/?i=login. To avoid to force them to change their link, edit the autolinks.php file and replace the code with the code below. Visitors will be redirected to the correct URL and all hits will be counted.</p>
            <table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="30">&nbsp;</td>
                <td align="right">
                  <textarea name="textfield" cols="49" rows="6" wrap="OFF">&lt;?php
  if( isset($i) )
    header( &quot;Location: <?=$aldir?>?i=$i&quot; );
  elseif( isset($o) )
    header( &quot;Location: <?=$aldir?>?o=$o&quot; );
?&gt;</textarea>
                  </td>
              </tr>
              <tr>
                <td width="30">&nbsp;</td>
                <td align="right"><a href="dl_autolinks.php?login=<?=$login?>"><font size="1">download autolinks.php file</font></a></td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td height="30"></td>
  </tr>
  
  
  
  
  <tr>
    <td><b>4. Include AutoLinks on  your pages</b></td>
  </tr>
  <tr>
    <td>
      <table width="100%" border="0" cellspacing="0" cellpadding="10">
        <tr>
          <td>
            <p>The AutoLinks directory is  fully setup. Now you have to integrate it within 
              <?=$site[name]?>
              . This goes in 2 steps, the first step is to include the code below at the top of  all pages where you want to display links/images generated by AutoLinks. <b>All  those pages must have a .php extension</b>. If you have installed a previous version of AutoLinks, replace the old code at the top of the pages with this one.</p>
            <table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="30">&nbsp;</td>
                <td align="right">
                    <textarea name="textarea" rows="3" cols="49">&lt;?php $aldir=&quot;<?=$aldir?>&quot;; include($DOCUMENT_ROOT.$aldir.&quot;display.php&quot;); ?&gt;</textarea>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td height="30"></td>
  </tr>
  <tr>
    <td><b>5. Add the tags on your pages</b></td>
  </tr>
  <tr>
    <td>
      <table width="100%" border="0" cellspacing="0" cellpadding="10">
        <tr>
          <td>
            <p>Tags in AutoLinks define the way the text and images links to referrers are displayed. There are many ways to customize them. You can create a new tag <a href="tag_add.php">here</a> or you can use those already created below. Just insert the code where you want the links to appear and   a 100%-width table with the text link(s) or image(s) will be created in real time.</p>
            <table border="0" cellspacing="0" cellpadding="2">
              <?
  $res_tag = mysql_query( "SELECT * FROM al_tag" );
  while( $tag = mysql_fetch_array($res_tag) ):
?>
              			
              <tr>
                <td width="30">&nbsp;</td>
                <td><b>
                  <?=$tag[name]?>
                  </b> (<a href="tag_edit.php?id=<?=$tag[id]?>">edit</a>)</td>
                <td>
                  <input type="text" name="textfield2" size="25" value="&lt;?php showtag(<?=$tag[id]?>); ?&gt;">
                </td>
              </tr>
              <? endwhile; ?>
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td height="30"></td>
  </tr>
  <tr>
    <td><b>6. Get the link exchanges started!</b></td>
  </tr>
  <tr>
    <td>
      <table width="100%" border="0" cellspacing="0" cellpadding="10">
        <tr>
          <td>
            <p>Now that AutoLinks is installed and integrated within 
              <?=$site[name]?>, you must get referrers to send hits to it. To do this, add a link to the register page so that referrers can easily signup. You may also add a link to the signin page  so that referrers can check their statistics, edit their information, get the code to link you, etc.</p>
            <table width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td width="30">&nbsp;</td>
                <td width="70"><b>Register</b></td>
                <td>
                  <input type="text" name="textfield22" size="50" value="<? echo($site[alurl]. "register.php"); ?>">
                </td>
              </tr>
              <tr>
                <td width="30">&nbsp;</td>
                <td width="70"><b>Sign in</b></td>
                <td>
                  <input type="text" name="textfield222" size="50" value="<? echo($site[alurl]. "signin.php"); ?>">
                </td>
              </tr>
            </table>
            <p>Another way  to get new referrers is to <a href="ref_invite.php">invite</a> them. You just enter their information yourself and the script sends an email to the referrer with the URL to use to link you.</p>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</body>
</html>
