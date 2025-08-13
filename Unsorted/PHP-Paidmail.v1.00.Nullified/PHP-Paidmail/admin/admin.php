<?
include ('../includes/global.php');
include ('../gold_membership/gold_vars.php');
//print $admin_header;
?>

<html>
<head>
<title>Site Administration</title>
</head>
<body>
<center>
<table><tr><td bgcolor="white">
<h2 align="center"><font face="arial">Site Administration</font></h2>
<hr>
<ol type="A">
<font face="arial" size="2">
<li><a href="<?=$admin_url?>/transactions.html">Transaction Manager</a>
<ol type="1">
<li>Credit/Debit User Accounts
</ol>

<li><a href="massemailer.php">Mass E-Mailer</a>
<ol type="1">
<li>Add/Edit/Delete E-Mail Messages
</ol>

<li><a href="infoadmin.php?t=first">User Manager</a>
<ol type="1">
<li>View/Edit/Delete Users and their Information
</ol>

<li><a href="user_count.php">Current Member Base</a>
<ol type="1">
<li>The current User Count.
</ol>

<li><a href="<?=$admin_url?>/pe.php">Paid-Email Administration</a>
<ol type="1">
<li>Add/Edit/Delete Paid-Emails
</ol>

<li><a href="admin_email.php">Email Messages Administration</a>
<ol type="1">
<li>Setting up of customized email messages.
</ol>

<li><a href="user_status.php?t=show_users_not_logged">Member Status</a>
<ol type="1">
<li>Viewing the status of the members who was not logged in to the site.
</ol>

<li><a href="setup_tiers.php?Task=Tier_Settings&Task2=Edit">Member Tier and Commission Settings</a>
<ol type="1">
<li>Member Tier's and Commission Settings for each tier levels.
</ol>

<li><a href="setup_misc.php">Miscellaneous Administration</a>
<ol type="1">
<li>Miscellaneous settings.
</ol>

<li><a href="<?=$gold_url?>/admin/index.php">Gold Membership Admin</a>
<ol type="1">
<li>Gold Membership Admin.
</ol>

<li><a href="redempt.php">Redemption Manager</a>
<ol type="1">
<li>Add/Edit/Delete ways for users to redeem their points/cash. 
</ol>

<li><a href="rotate.php">Rotation Manager</a>
<ol type="1">
<li>Add/Edit/Delete the Rotation Banner. 
</ol>

<li><a href="paytoclick.php">Paid To Click Manager</a>
<ol type="1">
<li>Add/Edit/Delete the Banners that are paid for each clicks. 
</ol>

<li><a href="dump.php">Database BackUp</a>
<ol type="1">
</ol>
</font>
<br>
<hr>
</td></tr></table>
</center>
</body>
</html>