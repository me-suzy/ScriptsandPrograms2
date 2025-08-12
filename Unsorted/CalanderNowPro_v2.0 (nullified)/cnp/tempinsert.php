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
require("engine.inc.php");
$setter = mysql_query ("SELECT * FROM cnpCalendar
                         WHERE id LIKE '$srcloc'
						LIMIT 1
                       ");

$set = mysql_fetch_array($setter);
print $set[info]; 
?>