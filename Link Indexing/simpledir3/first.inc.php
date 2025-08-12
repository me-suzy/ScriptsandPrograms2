<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
<title>SimpleDir <?=$sdversion?> - Simple WebDirectory Management</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="style3.css" rel="stylesheet" type="text/css">
</head>
<body>

<div class="admin">

<p class="head">SimpleDir <?=$sdversion?></p>
<div class="menu">
  <div class="leftbox">
    <div class="maintext">
      <?php if($logged == 'Y') { ?>
      <p><u>Categories</u><br>
      <a href="admin.php?do=addcat">Add</a><br>
      <a href="admin.php?do=editcat">Edit</a><br>
      <a href="admin.php?do=delcat">Delete</a></p>
      
      <p><u>Links</u><br>
      <a href="admin.php?do=addlink">Add</a><br>
      <a href="admin.php?do=apprlink">Approve</a><br>
      <a href="admin.php?do=searchform">Search</a><br>
      <a href="admin.php?do=listall">List all</a></p>
      
      <p><u>Misc.</u><br>
      <a href="admin.php?do=editcfg">Edit Options</a><br>
      <a href="admin.php?do=editlogin">Change Login Info</a><br>
      <a href="admin.php?do=files">File Manager</a><br>
      <a href="admin.php?action=email&id=all">Send E-Mail</a><br>
      <a href="admin.php?do=uninstall">Uninstall</a></p>
      
      <p><u>Templates</u><br>
      <a href="admin.php?do=tplslist">List All Templates</a><br>
      <a href="admin.php?action=edittpls&id=headfoot">Header/Footer</a><br>
      <a href="admin.php?action=edittpls&id=add">Add</a>/<a href="admin.php?action=edittpls&id=modify">Modify</a><br>
      <a href="admin.php?action=edittpls&id=cats">Categories</a><br>
      <a href="admin.php?action=edittpls&id=links">Links</a></p>
      
      <p><u>Navigation</u><br>
      <a href="<?=$siteurl?>" target="_blank">View Site</a><br>
      <a href="readme.html" target="_blank">Readme</a><br>
      <a href="admin.php?do=main">Main</a></p>
      <?php } ?>
    </div>
  </div>
</div>
<div class="mainbox">
  <div class="maintext">