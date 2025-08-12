<?php session_start();
?>

<HTML>
<HEAD>
<TITLE>Content Management System</TITLE>
</HEAD>

<FRAMESET cols="20%, *">
      <FRAME name="Menu" src="menulist.php">
 <?php  
 if ($_SESSION['Admin'] == "Y") 
  {
 	print   ' <FRAME name="Pages" src="Admin/Menus/List.php">';
  }
  else
  {
 	 print   ' <FRAME name="Pages" src="firstpage.php">';  
  }

  ?>
  <NOFRAMES>
      <P>You must have frames enabled browser to view this page
  </NOFRAMES>
</FRAMESET>
</HTML>