<?php
include "header.php";
session_start();
if (($_SESSION['perm'] < "3"))  {
echo "<font color='#$col_text'>"; die ('You are not authorised to access this section');
}
?>

<HTML>
<HEAD>
<TITLE>Admin</TITLE>
</HEAD>
<BODY>

<br>
<font color="#<?php echo $col_text ?>">

(Admin icons not yet added to page)
<BR><BR>
<a href="configuration.php"><font color="#<?php echo "$col_link" ?>">Configuration</font></font>
<BR><BR>
<a href="register.php"><font color="#<?php echo "$col_link" ?>">Add Member</font></font>
<BR><BR>
<a href="addnews.php"><font color="#<?php echo "$col_link" ?>">Add News</font></font>
<BR><BR>

<a href="memberrole.php"><font color="#<?php echo "$col_link" ?>">Member Role Management</font></font>
<BR><BR>
<a href="memberedit.php"><font color="#<?php echo "$col_link" ?>">Member Management</font></font>
<BR><BR>
<a href="topicmanage.php"><font color="#<?php echo "$col_link" ?>">Topic Management</font></font>
<BR><BR>
<a href="addownteam.php"><font color="#<?php echo "$col_link" ?>">Edit own team details</font></font>
<BR><BR>
<a href="addteam.php"><font color="#<?php echo "$col_link" ?>">Add New Team</font></font>
<BR><BR>
<a href="teamlist.php"><font color="#<?php echo "$col_link" ?>">List all Teams</font></font>
<BR><BR>
<a href="addfixture.php"><font color="#<?php echo "$col_link" ?>">Add Fixture</font></font>
<BR><BR>
<a href="matchtype.php"><font color="#<?php echo "$col_link" ?>">Add / Edit Match Type</font></font>
<BR><BR>
<a href="updateresults.php"><font color="#<?php echo "$col_link" ?>">Update Match Results</font></font>
<BR><BR>
<a href="otherresult.php"><font color="#<?php echo "$col_link" ?>">Update Other teams results</font></font>
<BR><BR>
<a href="seasonmanage.php"><font color="#<?php echo "$col_link" ?>">Add / Edit Season</font></font>







</BODY>
</HTML> 