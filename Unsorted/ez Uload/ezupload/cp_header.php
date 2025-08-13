<html>
<head>
<title>ezUpload Pro Control Panel</title>
<link rel="stylesheet" href="cpanel.css" type="text/css">
</head>
<body>

<table width="680" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td>
	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
	    <tr height="35"> 
	      <td align="left" valign="middle">
		    <img src="images/logo.gif" width="280" height="20">
	      </td>
	      <td align="right" valign="top">
		    <a href="index.php" target="_blank" class="topmenu">Upload Form</a> | <? if($CONF->getval("adminpass")!=""): ?> <a href="sign.php?action=signout&<?=$SID?>" class="topmenu">Sign Out</a><? endif; ?>
	      </td>
		</tr>	
	  </table>
	</td>
  </tr>
  <tr>
    <td valign="top">
      <table width="100%" border="0" cellspacing="1" cellpadding="5" class="area">
	    <tr>
		
<?
  showmenu( ($section=="browser"), "Browser", "browser.php?$SID", "View, download and delete the files users have uploaded" );
  showmenu( ($section=="settings"), "Settings", "settings.php?$SID", "Define general settings and options" );
  showmenu( ($section=="fields"), "Form Fields", "fields.php?$SID", "Define the type of information you want to take from the user" );
  showmenu( ($section=="results"), "Upload Results", "results.php?$SID", "What you want the script to do after the form has been submitted" );
  showmenu( ($section=="access"), "Access Control", "access.php?$SID", "Defines access to the upload form" );
  showmenu( ($section=="customize"), "Customize", "customize.php?$SID", "Customize the apperance of the upload form and include it on your site" );
  showmenu( ($section=="filter"), "Filter", "filter.php?$SID", "Define the files you accept based on their extension, size and dimension" );
  showmenu( ($section=="nullified"), "Info", "nullified.php?$SID", "Information" );
?>

            
		</tr>
		<tr bgcolor="#FFFFFF">
		  <td colspan="8">
            <table width="100%" border="0" cellspacing="1" cellpadding="7">
              <tr bgcolor="#FFFFFF">
                <td>

<? global $demomode; if( $demomode ) showmessage( "<b>Demo Mode</b> - Due to abuses, no changes can be made on the control panel. However you can surf through the control panel, <a href=\"index.php\" target=\"_blank\">upload files</a> and see them on the <a href=\"browser.php\">file browser</a>. Sorry for any inconvenience this may cause." ); ?>