<p><font size="4" face="Arial, Helvetica, sans-serif"><strong>Add</strong></font></p>
<p><font size="2" face="Arial, Helvetica, sans-serif" color="#990000">Your calendar 
  entry has been saved.</font> <font size="2" face="Arial, Helvetica, sans-serif" color="#FF0000"> 
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
$date = $year . $month . $movo;
mysql_query ("INSERT INTO cnpCalendar (date, header, info, nl, time) VALUES ('$date','$header','$Content','$nl','$time')");  
?>
  </font></p>
<p><font color="#FF0000" size="2" face="Arial, Helvetica, sans-serif"><a href="main.php?page=display&nl=<? print $nl; ?>">Continue</a></font></p>

