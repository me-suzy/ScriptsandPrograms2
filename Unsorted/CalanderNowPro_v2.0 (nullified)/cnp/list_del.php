<p><font size="4" face="Arial, Helvetica, sans-serif"><strong>Delete Calendar</strong></font></p>
<p><font face="Arial, Helvetica, sans-serif" size="2"> 
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
  if ($val == yes){
mysql_query ("DELETE FROM cnpLists
                                WHERE id = '$nl'
								");
								?>
  <font size="2" face="Arial, Helvetica, sans-serif" color="#990000"> The calendar 
  settings, events, and data has been removed.</font> </font></p>
<p><font face="Arial, Helvetica, sans-serif" size="2"><b><font size="2" face="Arial, Helvetica, sans-serif"><a href="main.php"><font color="#FF0000">&lt;&lt; 
  Click here to continue! &gt;&gt;</font></a></font></b> 
  <? } else { ?>
  <b>Are you sure you want to remove the calendar?</b></font></p>
<p><font size="2" face="Arial, Helvetica, sans-serif">By clicking on the &quot;Remove 
  Now&quot; button, all settings, events, and data for this calendar will be deleted.</font></p>
<p><font size="2" face="Arial, Helvetica, sans-serif"><b><i>This can not be undone.</i></b></font></p>
<p><font size="2" face="Arial, Helvetica, sans-serif">Would you still like to 
  remove this list?</font></p>
<font size="2" face="Arial, Helvetica, sans-serif"><b><a href="main.php?page=list_del&nl=<? print $nl; ?>&val=yes">Yes 
- Remove Now</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="main.php?nl=<? print $nl; ?>">No 
- Cancel</a></b></font> 
<? } ?>
