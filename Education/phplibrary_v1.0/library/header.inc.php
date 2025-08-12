<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo $library_name; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="styles.css" rel="stylesheet" type="text/css">
<! Script to remove text from a box on click e.g. boxes with DD MM YYYY in them !>
<script>
function m(el) {
  if (el.defaultValue==el.value) el.value = ""
}
</script>
</head>

<body>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
<tbody>
<tr valign="top">
<td>

 
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr class=color1>
    <td><h1><?php echo $library_name; ?></h1></td>
    <td><div align="right"><?php echo $date ?><?php if ($PHPLibrary[username]==true) { echo "<br><b>Logged in as: $user_full_name (Username: $PHPLibrary[username])</b>"; }?></div></td>
  </tr>
</table>

<?php 
if ($PHPLibrary[username]==true) 
	{ ?>
<table width="100%" border="0" cellpadding="2" cellspacing="1" bgcolor="#000000">
   <tbody>
    <tr align=middle>
	
     <?php 
	// This checks what page they are viewing and highlights the page on the menu
	// home
	if ($module=="home")
		{
		$homecolor = "color3"; 
		} 
	else 
		{ 
		$homecolor = "color2";
		}
	// Items
	if ($module=="items")
		{
		$itemcolor = "color3"; 
		} 
	else 
		{ 
		$itemcolor = "color2";
		} 
	// Students
	if ($module=="students")
		{	
		$studentcolor = "color3"; 
		} 
	else 
		{ 
		$studentcolor = "color2";
		} 
	// Loans
	if ($module=="loans")
		{	
		$loanscolor = "color3"; 
		} 
	else 
		{ 
		$loanscolor = "color2";
		} 
	 // Overdue
	 	if ($module=="overdue")
		{	
		$overduecolor = "color3"; 
		} 
	else 
		{ 
		$overduecolor = "color2";
		} 
	// Account
	 	if ($module=="accounts")
		{	
		$accountcolor = "color3"; 
		} 
	else 
		{ 
		$accountcolor = "color2";
		} 
	// You do not need a logout as its not a page

	 
	 ?>
 	 <td class="<?php echo $homecolor; ?>" width="14%" onMouseOver=this.style.backgroundColor="#DFE6EF" onMouseOut=this.style.backgroundColor="" onclick="window.location.href='?module=home'"><A class="<?php echo $homecolor; ?>" href="?module=home">Home</A></td>
	 <td class="<?php echo $itemcolor; ?>" onMouseOver=this.style.backgroundColor="#DFE6EF" onMouseOut=this.style.backgroundColor="" onclick="window.location.href='?module=items'" width="14%"><A class="<?php echo $itemcolor; ?>" href="?module=items">Items</A></td>
     <td class="<?php echo $studentcolor; ?>" onMouseOver=this.style.backgroundColor="#DFE6EF" onMouseOut=this.style.backgroundColor="" onclick="window.location.href='?module=students'" width="14%"><A class="<?php echo $studentcolor; ?>" href="?module=students">Students</A></td>
     <td class="<?php echo $loanscolor; ?>" onMouseOver=this.style.backgroundColor="#DFE6EF" onMouseOut=this.style.backgroundColor="" onclick="window.location.href='?module=loans'" width="14%"><A class="<?php echo $loanscolor; ?>" href="?module=loans">Loans</A></td>
     <td class="<?php echo $overduecolor; ?>" onMouseOver=this.style.backgroundColor="#DFE6EF" onMouseOut=this.style.backgroundColor="" onclick="window.location.href='?module=overdue'" width="14%"><A class="<?php echo $overduecolor; ?>" href="?module=overdue">Overdue Items</A></td>
     <td class="<?php echo $accountcolor; ?>" onMouseOver=this.style.backgroundColor="#DFE6EF" onMouseOut=this.style.backgroundColor="" onclick="window.location.href='?module=accounts'" width="14%"><A class="<?php echo $accountcolor; ?>" href="?module=accounts">Admin Accounts</A></td>
     <td class="color2" onMouseOver=this.style.backgroundColor="#DFE6EF" onMouseOut=this.style.backgroundColor="" onclick="window.location.href='?module=logout'" width="14%"><A class="color2" href="?module=logout">Logout</A></td>
    </tr>
   </tbody>
</table>
<?php
	}

// For debugging 
echo "\n<!-Debug: PHP Item Library $library_ver | Build Date: $library_ver_date | http://www.sebflipper.com-!>\n";
?>

<p>&nbsp;</p>