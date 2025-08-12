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
@include("engine.inc.php");
$usernow=base64_decode ($usercnp);
$sql_admin = "SELECT * FROM cnpAdmin WHERE user='$usernow' and pass='$passcnp'";
$result_admin = mysql_query($sql_admin);
$numc = mysql_numrows($result_admin);
if ($numc == 0){
header("Location: index.php?val=invalid");
exit; 
}
$row_admin = mysql_fetch_array($result_admin);
?>