<html><body> 
 <?php 
 include 'conection.php';
   import_request_variables('gP', 'r_') ;
 if (isset($r_submit)){ 
 $newcomment=mysql_query("insert into gs_comments set description='$r_comment', username='$r_username', gallery='$r_gallery' ");} 
 $result = mysql_query("SELECT * FROM gs_galleries where id='$r_gallery'"); 
$row = mysql_fetch_array($result) ; 
$comments=mysql_query("select description, username from gs_comments where gallery='$r_gallery'"); ?> 
<?php echo $row['title']; ?>
<?php echo "<img src='$row[picture]' border='0'><br>"; ?>
<?php 
 while ( $comrow = mysql_fetch_array($comments) ) { 
 ?> 
<?php echo $comrow['username']; ?><br>
<?php echo $comrow['description']; ?><br>
 <?php 
 } 
 ?> 

<?php
echo "<br><br>\n";
 echo "<form action=gallery.php method='post' \n>  ";
 echo "Add your comment<p> \n";
 echo "<textarea cols=50 rows=6 name='comment'></textarea><br> \n" ;
 echo "<input type='hidden' name='gallery' value='$r_gallery'> \n";
 echo "<input type='text' name='username' value='Enter your nickname'> \n ";
 echo "<input type='hidden' name='email' value='Enter your nickname email'> \n";
 echo "<input type='submit' name='submit' value='Submit'> \n";
 echo "</form>";

?>
