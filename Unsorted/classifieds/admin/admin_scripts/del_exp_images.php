<?php

// written by Nick Rembis
// 07-11-2002.
// You may use this script for your needs,alter as you need,just please dont remove
// my name or date from lines 3 and 4.give me some credit for helping you out here..LOL!
// Thanks,Nick Rembis


//This file must be readable by the webserver,so set your file permissions accordingly!!!!!


//be very,very careful while editing anything below this line.You will screw the whole works up!!!!!!


$current_images = mysql_query("SELECT * FROM std_blob_images");
$current_image_count = mysql_num_rows($current_images);
$expired_images = time()-2678400;
$delete_images = mysql_query("DELETE FROM std_blob_images WHERE timestamp < $expired_images");
?>
<center>    <!-- you may alter the line below between the html tags -->
       <h1><b><u><?=cnfg('siteName')?> Database Administration</u></b></h1>
<br>
    <br>
</center>
<p><b>Here are the results for deletion of expired images 
<?=strval(cnfg('expireAdsDays'))?> days or older.</b></p>
<br>
    <br>
<center>
<table  width=60% border=2 cellpadding=3 cellspacing=3>
       <tr>
           <td bgcolor=c0c0c0><? printf ("Number of expired images just deleted from the database: %d\n", mysql_affected_rows()); ?></td>
       </tr>
       <tr>
           <td bgcolor=c0c0c0><? echo "Current number of images in the database: $current_image_count \n"; ?></td>
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
