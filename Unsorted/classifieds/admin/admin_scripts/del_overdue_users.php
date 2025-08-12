<?php

// written by Nick Rembis
// 07-11-2002.
// You may use this script for your needs,alter as you need,just please dont remove
// my name or date from lines 3 and 4.give me some credit for helping you out here..LOL!
// Thanks,Nick Rembis

//This file must be readable by the webserver,so set your file permissions accordingly!!!!!

//be very,very careful while editing anything below this line.You will screw the whole works up!!!!!!

$current_temp_users = mysql_query("SELECT * FROM std_temp_users");
$active_temp_users = mysql_num_rows($current_temp_users);
$over_due_temp_user = time()-(cnfg('expireTempUsersDays')*86400);
$delete_over_due_temp_users = mysql_query("DELETE FROM std_temp_users WHERE sign_up_date < $over_due_temp_user");

?>
<center>    <!-- you may alter the line below between the html tags -->
       <h1><b><u><?=cnfg('siteName')?> Database Administration</u></b></h1>
<br>
    <br>
</center>
<p><b>Here are the results for deletion of over-due temp users 
<?=strval(cnfg('expireTempUsersDays'))?> days or older.</b></p>
<br>
<center>
<table  width=60% border=2 cellpadding=3 cellspacing=3>
       <tr>
           <td bgcolor=c0c0c0><? printf ("Number of temporary users just deleted from the database: %d\n", mysql_affected_rows()); ?></td>
       </tr>
       <tr>
           <td bgcolor=c0c0c0><? echo "Number of active temp users awaitng completion: $active_temp_users \n"; ?></td>
       </tr>
</table>
</center>
<?
$active_members = mysql_query("SELECT * FROM std_users");
$active_registered_members = mysql_num_rows($active_members);
?>
<br>
    <br>
<center>
<table width=60% border=2 cellpadding=3 cellspacing=3>
       <tr>
           <td bgcolor=c0c0c0><? echo "Current number of fully registered active users: $active_registered_members \n"; ?></td>
       </tr>
</table>
</center>
<br>
    <br>
        <br>
<div align=right>  <!-- You will have to alter the url tag below to give proper path name to match your needs -->
                      <a href="index.php">Back to Main Admin Page</a>
</div>
<?

?>
