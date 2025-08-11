<?php 
 while ( $comrow = mysql_fetch_array($comments) ) { 
 ?> 
<?php echo $comrow['username']; ?><br>
<?php echo $comrow['description']; ?><br>
 <?php 
 } 
 ?> 
