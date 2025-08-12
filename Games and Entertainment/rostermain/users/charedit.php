<? 
if (isset($_SESSION['valid_user'])) 
{ 
   echo '<p>Please change the relevant fields below and submit to update your information.</p>'; 
   $name = $_GET['select']; 
   echo ''.$name.'';
   if (isset($_GET['lvledit'])) 
   { 
      echo '<form method="post" action="index.php?page=editresults&&select='.$name.'">'; 
      echo 'Level:<input type="text" size="3" name="lvlform" value="'.$_GET['lvledit'].'" style="font-size:10px;border:solid 1px;"><br />'; 
      echo '<input type="submit" value="Update" style="font-size:10px;color:#FFFFFF;background-color:#9A0602;border: 0px;">'; 
      echo '</form>';
   }
	if (isset($_GET['nameedit'])) 
   { 
      echo '<form method="post" action="index.php?page=editresults&&select='.$name.'">'; 
      echo 'Name:<input type="text" size="50" name="nameform" value="'.$_GET['nameedit'].'" style="font-size:10px;border:solid 1px;"><br />'; 
      echo '<input type="submit" value="Update" style="font-size:10px;color:#FFFFFF;background-color:#9A0602;border: 0px;">'; 
      echo '</form>';
   }
   if (isset($_GET['classedit'])) 
   { 
      echo '<form method="post" action="index.php?page=editresults&&select='.$name.'">'; 
      echo 'Class:<input type="text" size="30" name="classform" value="'.$_GET['classedit'].'" style="font-size:10px;border:solid 1px;"><br />'; 
      echo '<input type="submit" value="Update" style="font-size:10px;color:#FFFFFF;background-color:#9A0602;border: 0px;">'; 
      echo '</form>';
   }
   if (isset($_GET['raceedit'])) 
   { 
      echo '<form method="post" action="index.php?page=editresults&&select='.$name.'">'; 
      echo 'Race:<input type="text" size="30" name="raceform" value="'.$_GET['raceedit'].'" style="font-size:10px;border:solid 1px;"><br />'; 
      echo '<input type="submit" value="Update" style="font-size:10px;color:#FFFFFF;background-color:#9A0602;border: 0px;">'; 
      echo '</form>';
   }
} 
?> 
