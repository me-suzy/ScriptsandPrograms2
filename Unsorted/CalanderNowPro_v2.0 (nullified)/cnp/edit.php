<html>

<head>

<title>Untitled Document</title>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

</head>



<body bgcolor="#FFFFFF" text="#000000">
<div align="left"><font size="4" face="Arial, Helvetica, sans-serif"><strong>Modify</strong></font> 
</div>
<form name="" method="post" action="main.php"  ONSUBMIT="copyValue(this);">
  <p align="left"> <font size="2" face="Arial, Helvetica, sans-serif"> </font><font size="3"><b><font size="3"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif"><font size="4" face="Arial, Helvetica, sans-serif" color="#336699"> 
    <?php
//////////////////////////////////////////////////////////////////////////////                      
//                                                                          //
//  Program Name         : Calander Now Pro                                 //
//  Program version      : 2.0                                              //
//  Program Author       : Jason VandeBoom                                  //
//  Supplied by          : drew010                                          //
//  Nullified by         : CyKuH [WTN]                                      //
//  Distribution         : via WebForum, ForumRU and associated file dumps  //
//                                                                          //
//////////////////////////////////////////////////////////////////////////////                      
		  $result = mysql_query ("SELECT * FROM cnpCalendar

                         WHERE id LIKE '$id'

						 limit 1

                       ");

$row = mysql_fetch_array($result)

?>
    </font></font></b></font></b></font></b></font></b></font><font size="2" face="Arial, Helvetica, sans-serif"> 
    <input type="text" name="date" value="<?php	print $row["date"];	?>">
    Date [ EX: 2002-01-22 ]<br>
    <input name="time" type="text" id="time" value="<?php	print $row["time"];	?>">
    Time<br>
    <input type="text" name="header" value="<?php	print $row["header"];	?>">
    Header </font></p>
  <p align="left"><font size="2" face="Arial, Helvetica, sans-serif"> Information<br>
    <? 
	$srcloc = $id;
	include("editor.php"); 
	?>
    </font></p>
  <p align="left"><font size="2" face="Arial, Helvetica, sans-serif"> 
    <input type="submit" name="Submit" value="Save Changes">
    <input type="hidden" name="id" value="<? print $id; ?>">
    <input name="nl" type="hidden" id="nl" value="<? print $nl; ?>">
    <input name="page" type="hidden" id="page" value="edit2">
    </font></p>
</form>
</body>

</html>

