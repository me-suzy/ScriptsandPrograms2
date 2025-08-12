<?php

/******************************************************
 * CjOverkill version 2.0.1
 * © Kaloyan Olegov Georgiev
 * http://www.icefire.org/
 * spam@icefire.org
 * 
 * Please read the lisence before you start editing this script.
 * 
********************************************************/
	

    require("cj-conf.inc.php");
    require("cj-functions.inc.php");
    
    cjoverkill_connect();
    
$filter_url_default="http://payload.icefire.org/cjoverkill.php";

    mysql_query("DROP TABLE IF EXISTS cjoverkill_filter_base") OR
      print_error(mysql_error());
    mysql_query("CREATE TABLE cjoverkill_filter_base (
						        ip_from int(10) unsigned NOT NULL default '0',
						        ip_to int(10) unsigned NOT NULL default '0',
						        c2code char(2) NOT NULL default '',
						        c3code char(3) NOT NULL default '',
						        country varchar(42) NOT NULL default '',
						        KEY code (ip_from,ip_to,c2code)
						      ) TYPE=MyISAM") OR
      print_error(mysql_error());
    mysql_query("DROP TABLE IF EXISTS cjoverkill_filter_client") OR
      print_error(mysql_error());

    mysql_query("CREATE TABLE cjoverkill_filter_client (
						      cid int(11) NOT NULL auto_increment,
						      client varchar(250) NOT NULL default '',
						      reason varchar(250) NOT NULL default '',
						      PRIMARY KEY  (cid)
						    ) TYPE=MyISAM") OR
      print_error(mysql_error()); 

    $cjoverkill_client_data=array("INSERT INTO cjoverkill_filter_client VALUES (1,'Eversion Avenger','Leech Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (2,'Teleport Pro','Leech Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (3,'PersonaPilot','Leech Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (4,'Lachesis','Leech Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (5,'Download Ninja','Leech Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (6,'Internet Ninja','Leech Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (7,'WebFetch','Leech Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (8,'Natbyte','Leech Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (9,'NetApp','Leech Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (10,'ASPseek','Leech Bot / Hitbot Spider')",
"INSERT INTO cjoverkill_filter_client VALUES (11,'AbachoBOT','Leech Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (12,'Atrax_Robustus','Leech Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (13,'BunnySlippers','Leech Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (14,'mailto:craftbot@yahoo.com','Leech Bot / Hitbot Spider')",
"INSERT INTO cjoverkill_filter_client VALUES (15,'BaiDuSpider','Aggressive Spider Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (16,'Chameleon','Leech Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (17,'Confuzzledbot','Leech Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (18,'CoolBot','Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (19,'DIIbot','Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (20,'DSurf15a','Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (21,'DWII-QServer','Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (22,'DigOut4U','Aggressive Spider Bot / Hitbot')",
"INSERT INTO cjoverkill_filter_client VALUES (23,'ESurf15a','Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (24,'EasyDL','Leech Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (25,'EasyWebPromotion','Spam Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (26,'Email Dm','Email harvester')",
"INSERT INTO cjoverkill_filter_client VALUES (27,'Email Extractor','Email harvester')",
"INSERT INTO cjoverkill_filter_client VALUES (28,'EmailSiphon','Email harvester')",
"INSERT INTO cjoverkill_filter_client VALUES (29,'ExtractorPro','Email harvester')",
"INSERT INTO cjoverkill_filter_client VALUES (30,'Firefly','Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (31,'FlashGet','Leech Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (32,'FlickBot','Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (33,'GetURL','Leech Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (34,'GhostURLOpener','Leech Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (35,'HyperBee','Leech bot')",
"INSERT INTO cjoverkill_filter_client VALUES (36,'HyperRobot','Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (37,'www.dev-soft.com','HTTP Component providers. Hitbots and Leech bots use these.')",
"INSERT INTO cjoverkill_filter_client VALUES (38,'IPiumBot','Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (39,'InfoNaviRobot','Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (40,'Kototoi','Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (41,'Leech','Leech Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (42,'LexiBot','Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (43,'Linbot','Aggressive Spider Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (44,'MarkWatch','Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (45,'Marvin','Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (46,'Mata Hari','Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (47,'MindSpider','Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (48,'Mister Pix','Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (49,'Nagara','Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (50,'Nocilla','Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (51,'Offline Explorer','Leech Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (52,'OpenSource Retriver','Leech Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (53,'OrangeBot','Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (54,'PowerBuilder','Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (55,'ProxyHunter','Bot / Hitbot')",
"INSERT INTO cjoverkill_filter_client VALUES (56,'Program Shareware','Bot / Leech Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (57,'RaBot','Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (58,'Robozilla','Leech Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (59,'Rumours-Agent','Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (60,'ScoreBot','Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (61,'ScoutAbout','Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (62,'SecretBrowser','Leech Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (63,'ShadowWebAnalyzer','Agressive Spider / Hitbot')",
"INSERT INTO cjoverkill_filter_client VALUES (64,'Slarp','Leech Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (65,'SpaceBison','Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (66,'Spida','Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (67,'Spinne','Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (68,'Sqworm','Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (69,'StressTest','Hitbot')",
"INSERT INTO cjoverkill_filter_client VALUES (70,'SurfControl','Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (71,'Surfnomore','Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (72,'SyncBot','Leech Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (73,'TAGENT','Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (74,'TITAN','Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (75,'Tkensaku','Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (76,'True_Robot','Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (77,'URL Spider','aggressive Spider / Hitbot')",
"INSERT INTO cjoverkill_filter_client VALUES (78,'VillSpider','Aggressive Spider / Hitbot')",
"INSERT INTO cjoverkill_filter_client VALUES (79,'Vertigo','Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (80,'Web Downloader','Leech Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (81,'Web Sucker','Leech Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (82,'WebCPO','Leech Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (83,'WebCopier','Leech Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (84,'WebSauger','Leech Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (85,'WebStripper','Leech Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (86,'WebTrends','Leech Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (87,'WebZIP','Leech Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (88,'WebcraftBoot','Leech Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (89,'Webdup','Leech Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (90,'WebsiteIlluminator','Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (91,'WebLight','Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (92,'Webster','Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (93,'WorQmada','Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (94,'WyoDEX','Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (95,'Link Sleuth','Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (96,'Zelig','Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (97,'Zeus','Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (98,'Ziggy','Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (99,'dloader','Leech Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (100,'dumbBot','Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (101,'gigabaz','Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (102,'htdig','Aggressive Spider / Hitbot')",
"INSERT INTO cjoverkill_filter_client VALUES (103,'httpGlooton','Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (104,'iSiloX','Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (105,'larbin','Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (106,'libwww-perl','Perl Internet HTTP library. Used by bots and automated software. Some custom hitbots use this.')",
"INSERT INTO cjoverkill_filter_client VALUES (107,'moget','Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (108,'polybot','Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (109,'potbot','Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (110,'psbot','Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (111,'pslinky','Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (112,'rabaz','Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (113,'suzuran','Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (114,'swbot','Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (115,'web-browser','Bot. Faked Web Browser bot')",
"INSERT INTO cjoverkill_filter_client VALUES (116,'webbandit','Leech Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (117,'webber','Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (118,'webcollage','Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (119,'Lite Bot','Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (120,'Pockey-GetHTML','Leech Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (121,'wget','Leech Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (122,'Liberate DTV','Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (123,'vobsub','Bot')",
"INSERT INTO cjoverkill_filter_client VALUES (124,'Indy Library','Delphi and C Internet libraries used by hitbots, leech bots and some small spiders for Windows.')"
				     );

    for ($n=0; $n < count($cjoverkill_client_data); $n++){
	$sql_aux=$cjoverkill_client_data[$n];
	@mysql_query($sql_aux) OR
	  print_error(mysql_error());
    }	


    mysql_query("DROP TABLE IF EXISTS cjoverkill_filter_country") OR
      print_error(mysql_error());
    mysql_query("CREATE TABLE cjoverkill_filter_country (
						       c2code char(2) NOT NULL default '',
						       country varchar(42) NOT NULL default '',
						       filter tinyint(1) NOT NULL default '0',
						       url varchar(250) NOT NULL default '',
						       reason varchar(250) NOT NULL default '',
						       KEY code (c2code)
						     ) TYPE=MyISAM") OR
      print_error(mysql_error());

    $cjoverkill_country_data=array("INSERT INTO cjoverkill_filter_country VALUES ('AD','ANDORRA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('AE','UNITED ARAB EMIRATES',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('AF','AFGHANISTAN',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('AG','ANTIGUA AND BARBUDA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('AL','ALBANIA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('AM','ARMENIA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('AN','NETHERLANDS ANTILLES',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('AO','ANGOLA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('AR','ARGENTINA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('AT','AUSTRIA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('AU','AUSTRALIA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('AZ','AZERBAIJAN',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('BA','BOSNIA AND HERZEGOVINA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('BB','BARBADOS',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('BD','BANGLADESH',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('BE','BELGIUM',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('BF','BURKINA FASO',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('BG','BULGARIA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('BH','BAHRAIN',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('BI','BURUNDI',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('BJ','BENIN',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('BM','BERMUDA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('BN','BRUNEI DARUSSALAM',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('BO','BOLIVIA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('BR','BRAZIL',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('BS','BAHAMAS',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('BT','BHUTAN',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('BW','BOTSWANA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('BY','BELARUS',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('BZ','BELIZE',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('CA','CANADA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('CD','CONGO, THE DEMOCRATIC REPUBLIC OF THE',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('CF','CENTRAL AFRICAN REPUBLIC',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('CG','CONGO',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('CH','SWITZERLAND',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('CK','COOK ISLANDS',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('CL','CHILE',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('CM','CAMEROON',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('CN','CHINA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('CO','COLOMBIA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('CR','COSTA RICA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('CS','CZECHOSLOVAKIA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('CU','CUBA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('CV','CAPE VERDE',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('CY','CYPRUS',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('DE','GERMANY',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('DJ','DJIBOUTI',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('DK','DENMARK',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('DM','DOMINICA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('DO','DOMINICAN REPUBLIC',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('DZ','ALGERIA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('EC','ECUADOR',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('EE','ESTONIA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('EG','EGYPT',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('ER','ERITREA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('ES','SPAIN',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('ET','ETHIOPIA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('FI','FINLAND',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('FJ','FIJI',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('FK','FALKLAND ISLANDS (MALVINAS)',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('FO','FAROE ISLANDS',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('FR','FRANCE',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('GA','GABON',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('GB','UNITED KINGDOM',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('GD','GRENADA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('GE','GEORGIA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('GH','GHANA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('GI','GIBRALTAR',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('GL','GREENLAND',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('GM','GAMBIA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('GN','GUINEA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('GP','GUADELOUPE',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('GQ','EQUATORIAL GUINEA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('GR','GREECE',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('GT','GUATEMALA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('GU','GUAM',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('GW','GUINEA-BISSAU',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('HK','HONG KONG',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('HN','HONDURAS',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('HR','CROATIA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('HT','HAITI',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('HU','HUNGARY',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('ID','INDONESIA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('IE','IRELAND',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('IL','ISRAEL',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('IN','INDIA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('IO','BRITISH INDIAN OCEAN TERRITORY',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('IR','IRAN, ISLAMIC REPUBLIC OF',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('IS','ICELAND',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('IT','ITALY',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('JM','JAMAICA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('JO','JORDAN',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('JP','JAPAN',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('KE','KENYA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('KG','KYRGYZSTAN',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('KH','CAMBODIA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('KI','KIRIBATI',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('KM','COMOROS',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('KR','KOREA, REPUBLIC OF',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('KW','KUWAIT',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('KY','CAYMAN ISLANDS',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('KZ','KAZAKHSTAN',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('LB','LEBANON',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('LI','LIECHTENSTEIN',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('LK','SRI LANKA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('LR','LIBERIA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('LS','LESOTHO',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('LT','LITHUANIA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('LU','LUXEMBOURG',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('LV','LATVIA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('LY','LIBYAN ARAB JAMAHIRIYA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('MA','MOROCCO',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('MC','MONACO',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('MD','MOLDOVA, REPUBLIC OF',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('MG','MADAGASCAR',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('MK','MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('ML','MALI',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('MM','MYANMAR',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('MN','MONGOLIA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('MO','MACAO',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('MP','NORTHERN MARIANA ISLANDS',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('MQ','MARTINIQUE',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('MR','MAURITANIA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('MT','MALTA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('MU','MAURITIUS',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('MV','MALDIVES',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('MW','MALAWI',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('MX','MEXICO',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('MY','MALAYSIA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('MZ','MOZAMBIQUE',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('NA','NAMIBIA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('NC','NEW CALEDONIA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('NE','NIGER',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('NG','NIGERIA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('NI','NICARAGUA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('NL','NETHERLANDS',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('NO','NORWAY',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('NP','NEPAL',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('NR','NAURU',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('NZ','NEW ZEALAND',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('OM','OMAN',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('PA','PANAMA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('PE','PERU',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('PF','FRENCH POLYNESIA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('PG','PAPUA NEW GUINEA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('PH','PHILIPPINES',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('PK','PAKISTAN',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('PL','POLAND',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('PR','PUERTO RICO',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('PS','PALESTINIAN TERRITORY, OCCUPIED',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('PT','PORTUGAL',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('PW','PALAU',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('PY','PARAGUAY',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('QA','QATAR',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('RE','REUNION',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('RO','ROMANIA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('RU','RUSSIAN FEDERATION',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('RW','RWANDA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('SA','SAUDI ARABIA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('SB','SOLOMON ISLANDS',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('SD','SUDAN',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('SE','SWEDEN',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('SG','SINGAPORE',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('SI','SLOVENIA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('SK','SLOVAKIA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('SL','SIERRA LEONE',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('SM','SAN MARINO',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('SN','SENEGAL',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('SO','SOMALIA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('SR','SURINAME',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('ST','SAO TOME AND PRINCIPE',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('SV','EL SALVADOR',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('SY','SYRIAN ARAB REPUBLIC',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('SZ','SWAZILAND',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('TD','CHAD',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('TG','TOGO',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('TH','THAILAND',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('TJ','TAJIKISTAN',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('TM','TURKMENISTAN',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('TN','TUNISIA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('TO','TONGA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('TP','EAST TIMOR',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('TR','TURKEY',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('TT','TRINIDAD AND TOBAGO',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('TV','TUVALU',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('TW','TAIWAN',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('TZ','TANZANIA, UNITED REPUBLIC OF',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('UA','UKRAINE',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('UG','UGANDA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('US','UNITED STATES',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('UY','URUGUAY',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('UZ','UZBEKISTAN',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('VA','HOLY SEE (VATICAN CITY STATE)',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('VE','VENEZUELA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('VN','VIET NAM',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('VU','VANUATU',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('WS','SAMOA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('YE','YEMEN',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('YU','YUGOSLAVIA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('ZA','SOUTH AFRICA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('ZM','ZAMBIA',0,'','')",
"INSERT INTO cjoverkill_filter_country VALUES ('ZW','ZIMBABWE',0,'','')"
				      );

    for ($n=0; $n < count($cjoverkill_country_data); $n++){
	$sql_aux=$cjoverkill_country_data[$n];
	@mysql_query($sql_aux) OR
	  print_error(mysql_error());
    }	


    mysql_query("DROP TABLE IF EXISTS cjoverkill_filter_ip") OR
      print_error(mysql_error());

    mysql_query("CREATE TABLE cjoverkill_filter_ip (
						  ip_from int(10) unsigned NOT NULL default '0',
						  ip_to int(10) unsigned NOT NULL default '0',
						  reason varchar(250) NOT NULL default 'No reason suplied',
						  hour tinyint(2) NOT NULL default '0',
						  auto tinyint(1) NOT NULL default '0',
						  fid int(11) NOT NULL auto_increment,
						  PRIMARY KEY  (fid)
						) TYPE=MyISAM") OR
      print_error(mysql_error());

    mysql_query("DROP TABLE IF EXISTS cjoverkill_filter_method") OR
      print_error(mysql_error());

    mysql_query("CREATE TABLE cjoverkill_filter_method (
						      method varchar(250) NOT NULL default '',
						      allow tinyint(1) NOT NULL default '0',
						      reason varchar(250) NOT NULL default 'No reason suplied',
						      mid int(11) NOT NULL auto_increment,
						      PRIMARY KEY  (mid)
						    ) TYPE=MyISAM") OR
      print_error(mysql_error());

    $cjoverkill_method_data=array("INSERT INTO cjoverkill_filter_method VALUES ('GET',1,'GET method MUST be allowed if you want your site to work',1)",
"INSERT INTO cjoverkill_filter_method VALUES ('HEAD',0,'Known to be used by hitbot attackers',2)",
"INSERT INTO cjoverkill_filter_method VALUES ('PUT',0,'Notmal surfers do not need to use this method. Used by hackers.',3)",
"INSERT INTO cjoverkill_filter_method VALUES ('SEARCH',0,'Disallow unless you are running mod_se and have your own search engine hosted at your server.',4)",
"INSERT INTO cjoverkill_filter_method VALUES ('OPTIONS',0,'Method used by hackers to gather information for further attacks.',5)",
"INSERT INTO cjoverkill_filter_method VALUES ('POST',0,'Surfers usually make only GET. POST could be used for attacks similar to the HEAD attacks.',6)",
"INSERT INTO cjoverkill_filter_method VALUES ('CONNECT',0,'Disallow unless you are running mod_proxy and your web server is working as a proxy too.',7)",
"INSERT INTO cjoverkill_filter_method VALUES ('DELETE',0,'Method used by hackers to delete web pages on badly configured servers.',8)",
"INSERT INTO cjoverkill_filter_method VALUES ('DEL',0,'Same as DELETE',9)",
"INSERT INTO cjoverkill_filter_method VALUES ('DELE',0,'Same as DELETE',10)"
				     );
    for ($n=0; $n < count($cjoverkill_method_data); $n++){
	$sql_aux=$cjoverkill_method_data[$n];
	@mysql_query($sql_aux) OR
	  print_error(mysql_error());
    }	


	

    mysql_query("ALTER TABLE cjoverkill_iplog_in ADD hour int(2) NOT NULL default '0'") OR
      print_error(mysql_error());
    mysql_query("ALTER TABLE cjoverkill_iplog_out ADD hour int(2) NOT NULL default '0'") OR
      print_error(mysql_error());
    mysql_query("ALTER TABLE cjoverkill_ref ADD hour int(2) NOT NULL default '0'") OR
      print_error(mysql_error());
    mysql_query("ALTER TABLE cjoverkill_security ADD hour int(2) NOT NULL default '0'") OR
      print_error(mysql_error());
    mysql_query("ALTER TABLE cjoverkill_settings CHANGE max_noref max_ip int(2) NOT NULL default '25'") OR
      print_error(mysql_error());
    mysql_query("ALTER TABLE cjoverkill_settings CHANGE noref_enable ip_enable tinyint(1) NOT NULL default '1'") OR
      print_error(mysql_error());
    mysql_query("UPDATE cjoverkill_settings SET ip_enable=1") OR
      print_error(mysql_error());
    mysql_query("ALTER TABLE cjoverkill_settings ADD filter_url_default varchar(250) NOT NULL default ''") OR
      print_error(mysql_error());
    mysql_query("ALTER TABLE cjoverkill_settings ADD max_ret int(11) NOT NULL default '200'") OR
      print_error(mysql_error());
    mysql_query("ALTER TABLE cjoverkill_settings ADD trade_method int(2) NOT NULL default '1'") OR
      print_error(mysql_error());
    mysql_query("ALTER TABLE cjoverkill_trades CHANGE max_noref max_ip int(2) NOT NULL default '25'") OR
     print_error(mysql_error());
    mysql_query("ALTER TABLE cjoverkill_trades ADD max_ret int(11) NOT NULL default '200'") OR
     print_error(mysql_error());
    mysql_query("UPDATE cjoverkill_trades SET domain='unknown', url='unknown', 
		  site_name='Not Tracked', site_desc='Not Tracked by the script. Possible hitbot action' WHERE
		  trade_id=3") OR
      print_error(mysql_error());        
    mysql_query("UPDATE cjoverkill_settings SET filter_url_default='$filter_url_default'") OR
      print_error(mysql_error());
    cjoverkill_disconnect();

echo ("<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">
	<html>
	<head>
	<title>CjOverkill Installation</title>
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
	<link href=\"cj-style.css\" rel=\"stylesheet\" type=\"text/css\">
	</head>
	<body bgcolor=\"#FFFFFF\" text=\"#000000\" link=\"#000000\" vlink=\"#000000\" alink=\"#000000\">
	");
    echo ("<div align=\"center\"><b><font size=\"4\">CjOverkill was succesfully upgraded<br>
	    Make sure you delete cjoverkill-install.php and cjoverkill-update.php!!!<br>
	    <br>
	    <a href=\"cjoverkill_filter_base/filter_base-1.php\">CLICK HERE TO CONTINUE WITH THE FILTER INSTALL</a></font></b></div>
	    ");
echo ("</body>
	</html>
	");


?>

