<html>

<head>

<title>CalendarNow</title>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

</head>



<body bgcolor="#FFFFFF" text="#000000">
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
if ($delval == "yes"){

mysql_query ("DELETE FROM cnpCalendar

                                WHERE id = '$id'

								limit 1

								");

								?>
<font size="2" face="Arial, Helvetica, sans-serif" color="#990000">Your changes 
have been saved.</font> 
<p><font color="#FF0000" size="2" face="Arial, Helvetica, sans-serif"><a href="main.php?nl=<? print $nl; ?>">Continue</a></font></p>
<p> </p>
<?php



}

else {

?>
<form name="" method="post" action="main.php">
  <p> <font face="Arial, Helvetica, sans-serif" size="2" color="#990000">Are you 
    sure you want to delete this entry from your Calendar?</font></p>
  <p><font face="Arial, Helvetica, sans-serif" size="2" color="#990000">This action 
    can <b>not</b> be undone.</font></p>
  <p><font size="2" face="Arial, Helvetica, sans-serif"> 
    <input name="submit" type="submit" value="Yes - Delete Now">
    <input name="button" type=button onClick="history.back();" value="No - Back">
    <input type="hidden" name="id" value="<? print $id; ?>">
    <input type="hidden" name="delval" value="yes">
    <input name="page" type="hidden" id="page" value="del">
    <input name="nl" type="hidden" id="nl" value="<? print $nl; ?>">
    </font></p>
</form>
<?php } ?>
<p>&nbsp;</p>
</body>

</html>

