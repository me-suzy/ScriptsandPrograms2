<html>

<head>

<title>CalendarNow</title>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

</head>



<body bgcolor="#FFFFFF" text="#000000">
<div align="center">
  <p align="left"><font size="2" face="Arial, Helvetica, sans-serif" color="#990000">Your 
    changes have been saved.</font> 
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
mysql_query("UPDATE cnpCalendar SET date='$date',header='$header',info='$Content',time='$time' WHERE (id='$id')");

?>
  </p>
  <p align="left"><font color="#FF0000" size="2" face="Arial, Helvetica, sans-serif"><a href="main.php?nl=<? print $nl; ?>">Continue</a></font> 
  </p>
</div>
</body>

</html>

