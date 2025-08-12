<?
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
$today = date("Ymd");
mysql_query ("INSERT INTO cnpLists (name, date) VALUES ('$name' ,'$today')");  
$result = mysql_query ("SELECT * FROM cnpAdmin
                         WHERE user != ''
                       	ORDER BY user
						");
if ($row = mysql_fetch_array($result)) {
do {
$lists = $row["lists"];
$id = $row["id"];
$last = mysql_query ("SELECT * FROM cnpLists
                         WHERE name LIKE '$name'
                       	ORDER BY id DESC
						LIMIT 1
						");
$lastfind = mysql_fetch_array($last);
$add = $lastfind["id"];
$lists = "$lists , $add";
mysql_query("UPDATE cnpAdmin SET lists='$lists' WHERE (id='$id')");

} while($row = mysql_fetch_array($result));
}
?>
<p><font size="4" face="Arial, Helvetica, sans-serif"><strong>Add A Calendar</strong></font></p>
<p><font size="2" face="Arial, Helvetica, sans-serif">Your calendar has been added.</font></p>
<hr width="100%" size="1" noshade>
<p><font size="2" face="Arial, Helvetica, sans-serif"><em>NOTE: By default, all 
  new calendars are able to be accessed and modified in whole by any admin user. 
  To change permissions, the administrator must goto the user permissions page.</em></font></p>
<hr width="100%" size="1" noshade>
<p><a href="main.php"><font size="2" face="Arial, Helvetica, sans-serif">Please click here to continue.</font></a></p>
