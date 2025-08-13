<html>
<head>
<title>EzUpload Pro Control Panel</title>
<link rel="stylesheet" href="main.css" type="text/css">
</head>
<body>

<table width="700" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td colspan="3">
	  <font size="4">EZUPLOAD PRO CONTROL PANEL</font>
	</td>
  </tr>
  <tr>
    <td colspan="3" height="7"><img src="dot.gif" width="1" height="1"></td>
  </tr>
  <tr>
    <td valign="top" width="110">
      <table width="100%" border="0" cellspacing="1" cellpadding="5" class="area">
        <tr <?=getmenuclass("home", $section)?>>
          <td><b><a href="cpanel.php" class="menu" title="The ezUpload control panel home">Home</a></b></td>
        </tr>
      </table>
	  <? showspace(10); ?>
      <table width="100%" border="0" cellspacing="1" cellpadding="5" class="area">
        <tr <?=getmenuclass("nullified", $section)?>>
          <td><b><a href="nullified.php" class="menu" title="Nullified Info">Information</a></b></td>
        </tr>
        <tr <?=getmenuclass("fields", $section)?>>
          <td><b><a href="fields.php" class="menu" title="Define the type of information you want to take from the user.">Form Fields</a></b></td>
        </tr>
        <tr <?=getmenuclass("results", $section)?>>
          <td><b><a href="results.php" class="menu" title="What you want the script to do after the form has been submitted.">Upload Results</a></b></td>
        </tr>
	    <tr <?=getmenuclass("customize", $section)?>>
          <td><b><a href="customize.php" class="menu" title="Customize the upload form and easily include it on your site.">Customize Form</a></b></td>
        </tr>
        <tr <?=getmenuclass("filter", $section)?>>
          <td><b><a href="filter.php" class="menu" title="Define the files you accept based on their extension, size and dimension.">Files Filter</a></b></td>
        </tr>
      </table>
	  <? showspace(10); ?>
      <table width="100%" border="0" cellspacing="1" cellpadding="5" class="area">
        <tr <?=getmenuclass("browser", $section)?>>
          <td><b><a href="browser.php" class="menu" title="View, download and delete the files users have uploaded.">Files Browser</a></b></td>
        </tr>
      </table>
	  <? showspace(10); ?>
      <table width="100%" border="0" cellspacing="1" cellpadding="5" class="area">
        <tr <?=getmenuclass("default", $section)?>>
          <td><b><a href="index.php" class="menu" target="_blank" title="The default upload form, to see the changes you made in real time.">Default Form</a></b></td>
        </tr>
      </table>
    </td>
    <td valign="top" width="10"><img src="dot.gif" width="1" height="1"></td>
    <td valign="top">
      <table width="100%" border="0" cellspacing="1" cellpadding="10" class="area">
        <tr bgcolor="#FFFFFF">
          <td>