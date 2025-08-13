<?PHP
///////////////////////////////////////////////////////////////////////////////
//                                                                           //
//   Program Name         : GhostDriver TGP  (Random Gallery Rotator)        //
//   Release Version      : 0.2                                              //
//   Supplied by          : CyKuH [WTN]                                      //
//   Nullified by         : CyKuH [WTN]                                      //
//   Distribution         : via WebForum, ForumRU and associated file dumps  //
//                                                                           //
//           Random Gallery Rotator  (c) Copyright  Nibbi `2002              //
//                    Copyright  WTN Team `2000 - `2002                      //
//                                                                           //
///////////////////////////////////////////////////////////////////////////////
include("settings.inc.php");
mysql_connect("$V8642fb61","$V6402673d","$Vc4cf7065"); 
 mysql_db_query("$Vd77d5e50","CREATE TABLE TgpRotate (
 id int(11) NOT NULL auto_increment,
 url varchar(150) NOT NULL default '',
 category varchar(100) NOT NULL default '',
 description varchar(100) NOT NULL default '',
 numpic varchar(5) NOT NULL default '',
 picname varchar(100) NOT NULL default '',
 type varchar(100) NOT NULL default '',
 vote int(3) NOT NULL default '5',
 numlisted int(5) NOT NULL default '0',
 stat varchar(7) NOT NULL default '',
 PRIMARY KEY (id),
 FULLTEXT KEY description (description),
 FULLTEXT KEY category (category),
 FULLTEXT KEY url (url)
 ) TYPE=MyISAM");
echo "<b>Gallery Database Created!</b><br>";
?> 