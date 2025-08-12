-- phpMyAdmin SQL Dump
-- version 2.6.3-pl1
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Erstellungszeit: 22. Oktober 2005 um 12:07
-- Server Version: 4.1.13
-- PHP-Version: 4.4.0
-- 
-- Datenbank: `faq`
-- 

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `faq_admins`
-- 

DROP TABLE IF EXISTS `faq_admins`;
CREATE TABLE `faq_admins` (
  `usernr` tinyint(3) unsigned NOT NULL auto_increment,
  `username` varchar(80) NOT NULL default '',
  `password` varchar(40) character set latin1 collate latin1_bin NOT NULL default '',
  `email` varchar(80) NOT NULL default '',
  `rights` int(2) unsigned NOT NULL default '0',
  `lastlogin` datetime NOT NULL default '0000-00-00 00:00:00',
  `lockpw` tinyint(1) unsigned NOT NULL default '0',
  `signature` text NOT NULL,
  `autopin` int(10) unsigned NOT NULL default '0',
  `language` varchar(20) NOT NULL default 'en',
  `hideemail` tinyint(1) unsigned NOT NULL default '0',
  `lockentry` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`usernr`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Daten für Tabelle `faq_admins`
-- 

INSERT INTO `faq_admins` VALUES (1, 'support', 0x3532303865643761653066393065303633373332373335313039356238326434, 'test@boesch.lan', 4, '2005-10-22 12:04:44', 0, 'Sig f?r support\r\nZeile 2', 0, 'de', 0, 0);
INSERT INTO `faq_admins` VALUES (12, 'das', 0x3261363537316461323636303261363762653134656138633561623832333439, 'test@test.info', 0, '2003-09-12 23:37:58', 0, '', 0, 'de', 0, 0);
INSERT INTO `faq_admins` VALUES (2, 'view', 0x3162646138306632626534643336353865306261613433666265376165386331, 'info@boesch-it.com', 1, '2002-08-28 18:36:47', 1, '', 0, 'en', 0, 1);
INSERT INTO `faq_admins` VALUES (3, 'edit', 0x6465393562343362636565623462393938616564346165643563656631616537, 'postmaster@boesch.lan', 2, '2003-10-27 16:54:19', 0, '', 0, 'de', 0, 1);

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `faq_bad_words`
-- 

DROP TABLE IF EXISTS `faq_bad_words`;
CREATE TABLE `faq_bad_words` (
  `indexnr` int(10) unsigned NOT NULL auto_increment,
  `word` varchar(100) NOT NULL default '',
  `replacement` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`indexnr`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Daten für Tabelle `faq_bad_words`
-- 


-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `faq_banlist`
-- 

DROP TABLE IF EXISTS `faq_banlist`;
CREATE TABLE `faq_banlist` (
  `bannr` int(10) unsigned NOT NULL auto_increment,
  `ipadr` varchar(16) NOT NULL default '0.0.0.0',
  `subnetmask` varchar(16) NOT NULL default '0.0.0.0',
  `reason` text NOT NULL,
  PRIMARY KEY  (`bannr`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Daten für Tabelle `faq_banlist`
-- 

INSERT INTO `faq_banlist` VALUES (6, '5.6.7.8', '255.255.255.0', '');
INSERT INTO `faq_banlist` VALUES (8, '1.1.1.1', '255.255.255.0', 'aa\r<BR>&amp;#22914;&amp;#26524;&amp;#24744;&amp;#30340;&amp;#39046;&amp;#22495;&amp;#23384;&amp;#22312;&amp;#20110;&amp;#25105; &lt;test&gt; &quot;&quot;');
INSERT INTO `faq_banlist` VALUES (10, '1.2.3.4', '255.255.255.0', '&amp;#22914;&amp;#26524;&amp;#24744;&amp;#30340;&amp;#39046;&amp;#22495;&amp;#23384;&amp;#22312;&amp;#20110;&amp;#25105; &amp;lt;test&amp;gt; \\&amp;quot;\\&amp;quot;');

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `faq_category`
-- 

DROP TABLE IF EXISTS `faq_category`;
CREATE TABLE `faq_category` (
  `catnr` int(10) unsigned NOT NULL auto_increment,
  `categoryname` varchar(240) NOT NULL default '',
  `numfaqs` int(10) unsigned NOT NULL default '0',
  `programm` int(10) unsigned default '0',
  `displaypos` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`catnr`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Daten für Tabelle `faq_category`
-- 

INSERT INTO `faq_category` VALUES (12, 'M', 2, 10, 0);
INSERT INTO `faq_category` VALUES (11, 'Cat 1', 6, 11, 1);
INSERT INTO `faq_category` VALUES (17, 'Cat 2', 0, 11, 2);
INSERT INTO `faq_category` VALUES (28, 'admin interface', 1, 18, 1);
INSERT INTO `faq_category` VALUES (29, 'Administrationsoberfläche', 2, 17, 1);

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `faq_category_admins`
-- 

DROP TABLE IF EXISTS `faq_category_admins`;
CREATE TABLE `faq_category_admins` (
  `catnr` int(10) unsigned NOT NULL default '0',
  `usernr` int(10) unsigned NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Daten für Tabelle `faq_category_admins`
-- 

INSERT INTO `faq_category_admins` VALUES (0, 1);
INSERT INTO `faq_category_admins` VALUES (12, 1);
INSERT INTO `faq_category_admins` VALUES (28, 5);
INSERT INTO `faq_category_admins` VALUES (28, 3);
INSERT INTO `faq_category_admins` VALUES (28, 1);
INSERT INTO `faq_category_admins` VALUES (29, 5);
INSERT INTO `faq_category_admins` VALUES (29, 3);
INSERT INTO `faq_category_admins` VALUES (29, 1);

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `faq_category_ref`
-- 

DROP TABLE IF EXISTS `faq_category_ref`;
CREATE TABLE `faq_category_ref` (
  `srccatnr` int(10) unsigned NOT NULL default '0',
  `language` varchar(5) NOT NULL default '',
  `destcatnr` int(10) unsigned NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Daten für Tabelle `faq_category_ref`
-- 

INSERT INTO `faq_category_ref` VALUES (16, 'en', 11);
INSERT INTO `faq_category_ref` VALUES (17, 'de', 18);
INSERT INTO `faq_category_ref` VALUES (16, 'en', 17);
INSERT INTO `faq_category_ref` VALUES (18, 'en', 17);
INSERT INTO `faq_category_ref` VALUES (13, 'en', 11);
INSERT INTO `faq_category_ref` VALUES (9, 'en', 11);
INSERT INTO `faq_category_ref` VALUES (11, 'de', 9);

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `faq_comments`
-- 

DROP TABLE IF EXISTS `faq_comments`;
CREATE TABLE `faq_comments` (
  `commentnr` int(10) unsigned NOT NULL auto_increment,
  `faqnr` int(10) unsigned NOT NULL default '0',
  `email` varchar(140) NOT NULL default '',
  `comment` text NOT NULL,
  `ipadr` varchar(16) NOT NULL default '0.0.0.0',
  `postdate` datetime NOT NULL default '0000-00-00 00:00:00',
  `rating` int(10) unsigned NOT NULL default '0',
  `ratingcount` int(10) unsigned NOT NULL default '0',
  `views` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`commentnr`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Daten für Tabelle `faq_comments`
-- 

INSERT INTO `faq_comments` VALUES (3, 7, 'boesch@boesch-it.com', 'Test', '127.0.0.1', '2001-12-21 10:45:29', 3, 1, 1);
INSERT INTO `faq_comments` VALUES (2, 7, 'test@test2.com', 'Test', '127.0.0.1', '2001-12-20 23:54:18', 0, 0, 1);
INSERT INTO `faq_comments` VALUES (4, 7, 'boesch@boesch-it.com', 'Test', '127.0.0.1', '2001-12-21 10:45:53', 0, 0, 1);
INSERT INTO `faq_comments` VALUES (5, 7, 'boesch@boesch-it.com', 'Test', '127.0.0.1', '2001-12-21 10:46:08', 0, 0, 3);
INSERT INTO `faq_comments` VALUES (6, 7, 'boesch@boesch-it.com', 'Test', '127.0.0.1', '2001-12-20 10:46:54', 0, 0, 2);
INSERT INTO `faq_comments` VALUES (7, 7, 'boesch@boesch-it.com', 'Test a 2 3', '127.0.0.1', '2001-12-22 18:53:42', 3, 1, 1);
INSERT INTO `faq_comments` VALUES (8, 7, 'test@test.de', 'Fett', '127.0.0.1', '2001-12-23 12:39:13', 0, 0, 1);
INSERT INTO `faq_comments` VALUES (9, 13, 'aaa@aaa.com', 'adjadjsadjlsadhdslkhdsfdsajgasjkgfdsjfgjfgdhbdcmxgcbczewrdhcs', '127.0.0.1', '2002-01-06 23:25:58', 0, 0, 14);
INSERT INTO `faq_comments` VALUES (10, 13, 'aaa@aaa.com', 'assashakhs', '127.0.0.1', '2002-01-07 00:39:40', 3, 1, 12);
INSERT INTO `faq_comments` VALUES (11, 9, 'aaaa@aaa.com', 'aaaaaaa', '127.0.0.1', '2002-01-22 15:12:08', 3, 1, 41);
INSERT INTO `faq_comments` VALUES (12, 9, 'aaa@aaa.com', '????????', '127.0.0.1', '2002-01-30 14:05:24', 3, 1, 36);
INSERT INTO `faq_comments` VALUES (13, 9, 'test@boesch.de', 'addadassdadsa', '127.0.0.1', '2002-05-21 23:53:59', 0, 0, 19);
INSERT INTO `faq_comments` VALUES (14, 9, 'test@boesch.de', 'addadassdadsa', '127.0.0.1', '2002-05-21 23:54:15', 0, 0, 19);
INSERT INTO `faq_comments` VALUES (15, 9, 'test@boesch.de', 'dsajasdljdsa', '127.0.0.1', '2002-05-21 23:56:06', 0, 0, 18);
INSERT INTO `faq_comments` VALUES (16, 9, 'test@boesch.de', 'dsajasdljdsa', '127.0.0.1', '2002-05-21 23:57:43', 0, 0, 18);
INSERT INTO `faq_comments` VALUES (17, 9, 'test@boesch.de', 'dsajasdljdsa', '127.0.0.1', '2002-05-21 23:59:05', 0, 0, 18);
INSERT INTO `faq_comments` VALUES (18, 9, 'test@boesch.de', 'dsajasdljdsa', '127.0.0.1', '2002-05-21 23:59:39', 0, 0, 18);
INSERT INTO `faq_comments` VALUES (19, 9, 'test@boesch.de', 'dsajasdljdsa', '127.0.0.1', '2002-05-22 00:00:26', 0, 0, 18);
INSERT INTO `faq_comments` VALUES (20, 9, 'test@boesch.de', 'dsajasdljdsa', '127.0.0.1', '2002-05-22 00:02:08', 0, 0, 18);
INSERT INTO `faq_comments` VALUES (21, 60, 'test@boesch.lan', 'sadjk?saj?sadj', '127.0.0.1', '2002-07-17 16:07:13', 9, 3, 13);
INSERT INTO `faq_comments` VALUES (22, 60, 'test@boesch.lan', 'asd?sdaljdsajl', '127.0.0.1', '2002-07-17 16:28:24', 0, 0, 0);
INSERT INTO `faq_comments` VALUES (23, 68, 'test@boesch.lan', '&#22914;&#26524;&#24744;&#30340;&#39046;&#22495;&#23384;&#22312;&#20110;&#25105;  ""', '127.0.0.1', '2002-07-20 19:52:07', 0, 0, 4);
INSERT INTO `faq_comments` VALUES (24, 68, 'test@boesch.lan', '&#22914;&#26524;&#24744;&#30340;&#39046;&#22495;&#23384;&#22312;&#20110;&#25105;  ""', '127.0.0.1', '2002-07-20 19:56:03', 0, 0, 1);
INSERT INTO `faq_comments` VALUES (25, 13, 'test@boesch.lan', 'aaa', '127.0.0.1', '2002-07-25 15:34:59', 0, 0, 1);
INSERT INTO `faq_comments` VALUES (26, 14, 'test@boesch.lan', 'Kommentar', '10.1.1.30', '2003-05-02 10:46:30', 0, 0, 4);
INSERT INTO `faq_comments` VALUES (27, 88, 'test@boesch.lan', 'dsadasdsasda', '127.0.0.1', '2003-05-21 13:54:31', 0, 0, 0);
INSERT INTO `faq_comments` VALUES (28, 88, 'test@boesch.lan', 'dsadasdsasda', '127.0.0.1', '2003-05-21 13:55:42', 0, 0, 0);
INSERT INTO `faq_comments` VALUES (29, 88, 'test@boesch.lan', 'dsadasdsasda', '127.0.0.1', '2003-05-21 13:56:16', 0, 0, 0);
INSERT INTO `faq_comments` VALUES (30, 88, 'test@boesch.lan', 'dsadasdsasda', '127.0.0.1', '2003-05-21 13:57:38', 0, 0, 0);
INSERT INTO `faq_comments` VALUES (31, 88, 'test@boesch.lan', 'dsadasdsasda', '127.0.0.1', '2003-05-21 13:59:43', 0, 0, 0);
INSERT INTO `faq_comments` VALUES (32, 88, 'test@boesch.lan', 'dsadasdsasda', '127.0.0.1', '2003-05-21 14:00:42', 0, 0, 0);
INSERT INTO `faq_comments` VALUES (33, 88, 'test@boesch.lan', 'dsadasdsasda', '127.0.0.1', '2003-05-21 14:02:11', 0, 0, 0);
INSERT INTO `faq_comments` VALUES (34, 88, 'test@boesch.lan', 'dsadasdsasda', '127.0.0.1', '2003-05-21 14:03:06', 0, 0, 0);
INSERT INTO `faq_comments` VALUES (35, 88, 'test@boesch.lan', 'dsadasdsasda', '127.0.0.1', '2003-05-21 14:07:05', 0, 0, 0);
INSERT INTO `faq_comments` VALUES (36, 88, 'test@boesch.lan', 'dsadasdsasda', '127.0.0.1', '2003-05-21 14:07:31', 0, 0, 0);

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `faq_data`
-- 

DROP TABLE IF EXISTS `faq_data`;
CREATE TABLE `faq_data` (
  `faqnr` int(10) unsigned NOT NULL auto_increment,
  `heading` varchar(240) NOT NULL default '',
  `category` int(10) unsigned NOT NULL default '0',
  `questiontext` text,
  `editor` varchar(80) NOT NULL default 'unknown',
  `editdate` date NOT NULL default '0000-00-00',
  `answertext` text,
  `views` int(10) unsigned NOT NULL default '0',
  `ratingcount` int(10) unsigned NOT NULL default '0',
  `rating` int(10) unsigned NOT NULL default '0',
  `displaypos` int(10) unsigned NOT NULL default '0',
  `subcategory` int(10) unsigned NOT NULL default '0',
  `linkedfaq` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`faqnr`),
  KEY `category` (`category`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Daten für Tabelle `faq_data`
-- 

INSERT INTO `faq_data` VALUES (11, 'Blah', 12, 'aaa<BR><BR>', 'support', '2002-04-28', 'aaa<BR><BR>', 11, 0, 0, 0, 0, 0);
INSERT INTO `faq_data` VALUES (8, 'Changed entry 1', 12, 'This is the question for entry 1, changed in offline editor<BR><BR><BR><BR><BR><BR><BR><BR><BR><BR>', 'support', '2004-05-18', 'This is the answer for entry 1, changed in offline editor<BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR>', 4, 0, 0, 0, 13, 0);
INSERT INTO `faq_data` VALUES (12, 'aaaa', 11, 'frage 4444<BR><BR>', 'support', '2002-04-28', 'antwort<BR><BR>', 0, 0, 0, 0, 0, 0);
INSERT INTO `faq_data` VALUES (23, 'yyyyyyyyyyyy', 11, 'yx<BR><BR>', 'support', '2002-04-28', 'yx&lt;yx<BR><BR>', 0, 0, 0, 0, 0, 0);
INSERT INTO `faq_data` VALUES (53, 'unable to login to admin interface', 28, 'After install I can not get the program to recognize my password for admin. I checked the mysql table and do find the username and password listed.', 'support', '2004-05-17', '&lt;!-- SPCode Start --&gt;&lt;I&gt;For versions &lt;3.35:&lt;/I&gt;&lt;!-- SPCode End --&gt;<BR>Please check in &lt;!-- SPCode Start --&gt;&lt;I&gt;config.php&lt;/I&gt;&lt;!-- SPCode End --&gt; you have provided the correct value set for &lt;!-- SPCode Start --&gt;&lt;I&gt;$cookiedomain&lt;/I&gt;&lt;!-- SPCode End --&gt;. If this entry does not match your servername, the sessionid never will be sent back to the server, so the programm does not recognice you as logged in. The cookiename should just be the hostname (the one also defined in e.g. apache.conf).<BR>If the URL is &lt;!-- SPCode Start --&gt;&lt;I&gt;http://www.foo.bar/faq/faq.php&lt;/I&gt;&lt;!-- SPCode End --&gt; the cookiedomain is &lt;!-- SPCode Start --&gt;&lt;I&gt;www.foo.bar&lt;/I&gt;&lt;!-- SPCode End --&gt;.<BR>If your server can be access by different domains, you have to decide which one to use to login to admin interface and use this domain for cookiedomain.<BR><BR>&lt;!-- SPCode Start --&gt;&lt;I&gt;all versions:&lt;/I&gt;&lt;!-- SPCode End --&gt;<BR>&lt;!-- SPCode Start --&gt;&lt;I&gt;Note:&lt;/I&gt;&lt;!-- SPCode End --&gt; You have to use this domain in your URL for accessing the admin interface, using an other hostalias will not let you loggin in.<BR>Please also note to set &lt;!-- SPCode Start --&gt;&lt;I&gt;$url_faqengine&lt;/I&gt;&lt;!-- SPCode End --&gt; to the right value (in the example above this would be &lt;!-- SPCode Start --&gt;&lt;I&gt;/faq&lt;/I&gt;&lt;!-- SPCode End --&gt;).<BR><BR>Other possible reasons why session is not recogniced:&lt;!-- SPCode ulist Start --&gt;&lt;UL&gt;&lt;!-- SPCode --&gt;&lt;LI&gt;Hostname for virtual host defined in configuration of HTTP server does not match the hostname used to access the site (and used in cookiedomain).<BR>Solution: use hostname defined in the configuration of your HTTP server to access the admin interface or add a virtual host for the desired hostname or insert line &lt;!-- SPCode Code Start --&gt;&lt;TABLE BORDER=0 ALIGN=CENTER WIDTH=85%&gt;&lt;TR&gt;&lt;TD class=&quot;bbc_code_title&quot;&gt;{bbc_code}:&lt;/font&gt;&lt;/TD&gt;&lt;/TR&gt;&lt;TR&gt;&lt;TD&gt;&lt;table width=&quot;100%&quot; class=&quot;bbc_code&quot; cellspacing=&quot;1&quot; cellpadding=&quot;3&quot;&gt;&lt;tr&gt;&lt;td&gt;&lt;PRE CLASS=&quot;bbc_code&quot;&gt;$cookiedomain=&amp;quot;&amp;lt;desired domain&amp;gt;&amp;quot;&lt;/PRE&gt;&lt;/TD&gt;&lt;/TR&gt;&lt;/TABLE&gt;&lt;/TD&gt;&lt;/TR&gt;&lt;/TABLE&gt;&lt;!-- SPCode End --&gt;<BR><BR><BR> in config.php. Please note, that the defined domain at least has to contain 2 dots according to specification for cookies by Netscape.&lt;!-- SPCode --&gt;&lt;LI&gt;You have disabled accepting cookies in your browser configuration&lt;!-- SPCode --&gt;&lt;LI&gt;You have installed a local proxy, which wipes cookies (e.g. Webwasher, NIS).<BR>Solution: exclude the host on which FAQEngine is running from being included in this wiping.&lt;/UL&gt;&lt;!-- SPCode ulist End --&gt;<BR><BR>If (for any reason) you can\\''t use cookies for storing the sessionid, use one of the 2 alternative sessionhandling methods (see readme.txt).', 0, 0, 0, 1, 0, 0);
INSERT INTO `faq_data` VALUES (54, 'Ich kann mich nicht anmelden', 29, 'Nach der Installation kann ich mit dem eingegebenen Benutzernamen/Passwort nicht anmelden. Ich habe in der Datenbank nachgesehen, aber dort sind Benutzername und Passwort eingetragen.', 'support', '2002-06-08', '&lt;!-- SPCode Start --&gt;&lt;I&gt;Versionen bis 3.35:&lt;/I&gt;&lt;!-- SPCode End --&gt;\r<BR>Bitte &uuml;berpr&uuml;fen Sie, da&szlig; in &lt;!-- SPCode Start --&gt;&lt;I&gt;config.php&lt;/I&gt;&lt;!-- SPCode End --&gt; der richtige Wert bei &lt;!-- SPCode Start --&gt;&lt;I&gt;$cookiedomain&lt;/I&gt;&lt;!-- SPCode End --&gt; eingetragen ist. Stimmt dieser Wert nicht mit dem Servernamen &uuml;bereinn, so wird die Session-ID niemals zum Server zur&uuml;ck geschickt, so da&szlig; das Programm die Anmeldung auch nicht erkennen kann. Der Cookiename muss der Hostname sein, wie er z.B. auch in der apache.conf eingetragen ist.\r<BR>Lautet die URL zum Programm etwas &lt;!-- SPCode Start --&gt;&lt;I&gt;http://www.foo.bar/faq/faq.php&lt;/I&gt;&lt;!-- SPCode End --&gt;, so ist die Cookiedomain &lt;!-- SPCode Start --&gt;&lt;I&gt;www.foo.bar&lt;/I&gt;&lt;!-- SPCode End --&gt;.\r<BR>Sollte Ihr Webserver &uuml;ber mehrere Domainnamen erreichbar sein, so m&uuml;ssen Sie sich f&uuml;r einen zur Benutzung der Administrationsoberfl&auml;che entscheiden.\r<BR>&lt;!-- SPCode Start --&gt;&lt;I&gt;Bitte beachten:&lt;/I&gt;&lt;!-- SPCode End --&gt; Sie k&ouml;nnen sich bei der Administrationsoberfl&auml;che nur anmelden, wenn Sie in der Zugriffs-URL auch die in cookiedomain definierte Domain benutzen.\r<BR>\r<BR>&lt;!-- SPCode Start --&gt;&lt;I&gt;Alle Versionen:&lt;/I&gt;&lt;!-- SPCode End --&gt;\r<BR>Achten Sie ebenfalls darauf, dass der Wert f&uuml;r &lt;!-- SPCode Start --&gt;&lt;I&gt;$url_faqengine&lt;/I&gt;&lt;!-- SPCode End --&gt; richtig gesetzt ist (im o.a. Beispiel w&auml;re dies &lt;!-- SPCode Start --&gt;&lt;I&gt;/faq&lt;/I&gt;&lt;!-- SPCode End --&gt;).\r<BR>\r<BR>Andere m&ouml;gliche Ursachen, warum die Session nicht erkannt wird:&lt;!-- SPCode ulist Start --&gt;&lt;UL&gt;&lt;!-- SPCode --&gt;&lt;LI&gt;Der in der Konfigurationsdatei des HTTP-Servers definierte Hostname stimmt nicht mit dem &uuml;berein, den Sie f&uuml;r den Zugang zur Administrationsoberfl&auml;che benutzen wollen.\r<BR>L&ouml;sungsm&ouml;glichkeit: Benutzen Sie den Hostnamen, der in der Kofigurationsdatei des HTTP-Servers steht, um auf die Administrationsoberfl&auml;che zuzugreifen oder erstellen Sie einen virtuellen Host f&uuml;r den Hostnamen, den Sie f&uuml;r den Zugriff verwenden m&ouml;chten.\r<BR>&lt;!-- SPCode --&gt;&lt;LI&gt;Sie haben in Ihrem Browser die Annahme von Cookies abgeschalten.\r<BR>L&ouml;sungsm&ouml;glichkeit: Schalten Sie die Annahme von Cookies f&uuml;r den Host, auf dem die FAQEngine l&auml;uft, ein.\r<BR>&lt;!-- SPCode --&gt;&lt;LI&gt;Sie haben ein Programm installiert, das Cookies beim Senden l&ouml;scht (z.B. Webwasher, NIS).\r<BR>L&ouml;sungsm&ouml;glichkeit: Konfigurieren Sie das Programm so, dass es das Senden von Cookies an den Host, auf dem die FAQEngine l&auml;uft, zul&auml;sst.&lt;/UL&gt;&lt;!-- SPCode ulist End --&gt;\r<BR>\r<BR>Sollten Sie keine Cookies f&uuml;r das Sessionhandling verwenden k&ouml;nnen/wollen, so k&ouml;nnen Sie alternativ die SessionID per post und get mit &uuml;bertragen lassen. Setzen Sie dazu &lt;!-- SPCode Start --&gt;&lt;I&gt;$sessid_url=true;&lt;/I&gt;&lt;!-- SPCode End --&gt; in der &lt;!-- SPCode Start --&gt;&lt;I&gt;config.php&lt;/I&gt;&lt;!-- SPCode End --&gt; oder benutzen Sie die HTTP-Access Methode f&uuml;r die Benutzeridentifizierung.', 3, 0, 0, 1, 0, 0);
INSERT INTO `faq_data` VALUES (55, 'dsadsa', 11, 'dasdas', 'support', '2002-06-10', 'dsdsa', 6, 0, 0, 9, 0, 0);
INSERT INTO `faq_data` VALUES (75, 'Eintrag mit Umlauten (&auml;)', 11, NULL, 'unknown', '2003-11-27', NULL, 6, 0, 0, 10, 0, 14);
INSERT INTO `faq_data` VALUES (93, 'dsadsadasdsa', 11, '&amp;#22914;&amp;#26524;&amp;#24744;&amp;#30340;&amp;#39046;&amp;#22495;&amp;#23384;&amp;#22312;&amp;#20110;&amp;#25105;  &quot;&quot;', 'support', '2003-12-15', 'dsadasdsadsa', 0, 0, 0, 0, 0, 0);
INSERT INTO `faq_data` VALUES (94, 'Blah', 29, NULL, 'unknown', '2005-10-22', NULL, 0, 0, 0, 2, 0, 11);

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `faq_dir_access`
-- 

DROP TABLE IF EXISTS `faq_dir_access`;
CREATE TABLE `faq_dir_access` (
  `entrynr` int(10) unsigned NOT NULL auto_increment,
  `dirname` varchar(240) NOT NULL default '',
  PRIMARY KEY  (`entrynr`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Daten für Tabelle `faq_dir_access`
-- 

INSERT INTO `faq_dir_access` VALUES (6, 'test');
INSERT INTO `faq_dir_access` VALUES (7, 'test/testsub');

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `faq_failed_logins`
-- 

DROP TABLE IF EXISTS `faq_failed_logins`;
CREATE TABLE `faq_failed_logins` (
  `loginnr` int(10) unsigned NOT NULL auto_increment,
  `username` varchar(250) NOT NULL default '0',
  `ipadr` varchar(16) NOT NULL default '',
  `logindate` datetime NOT NULL default '0000-00-00 00:00:00',
  `usedpw` varchar(240) NOT NULL default '',
  PRIMARY KEY  (`loginnr`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Daten für Tabelle `faq_failed_logins`
-- 

INSERT INTO `faq_failed_logins` VALUES (1, 'support', '10.1.1.30', '2002-12-17 21:54:11', '***');
INSERT INTO `faq_failed_logins` VALUES (2, 'edit', '10.1.1.30', '2003-06-21 13:23:03', '***');
INSERT INTO `faq_failed_logins` VALUES (3, 'edit', '10.1.1.30', '2003-06-21 13:23:06', '***');
INSERT INTO `faq_failed_logins` VALUES (4, 'zampano', '10.1.1.30', '2003-08-08 11:29:02', '***');
INSERT INTO `faq_failed_logins` VALUES (5, 'edit', '10.1.1.30', '2003-10-27 10:49:23', '***');
INSERT INTO `faq_failed_logins` VALUES (6, 'none', '10.1.1.20', '2004-06-03 01:13:18', '***');
INSERT INTO `faq_failed_logins` VALUES (7, 'zampano', '10.1.1.20', '2004-06-05 00:40:46', 'Dkgm4a');
INSERT INTO `faq_failed_logins` VALUES (8, 'zampano', '10.1.1.20', '2004-06-23 17:05:06', 'Dkgm4a');
INSERT INTO `faq_failed_logins` VALUES (9, 'zampano', '10.1.1.20', '2004-10-29 10:51:26', 'Dkgm4a');

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `faq_failed_notify`
-- 

DROP TABLE IF EXISTS `faq_failed_notify`;
CREATE TABLE `faq_failed_notify` (
  `usernr` int(10) unsigned default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Daten für Tabelle `faq_failed_notify`
-- 


-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `faq_faq_attachs`
-- 

DROP TABLE IF EXISTS `faq_faq_attachs`;
CREATE TABLE `faq_faq_attachs` (
  `entrynr` int(10) unsigned NOT NULL auto_increment,
  `faqnr` int(10) unsigned NOT NULL default '0',
  `attachnr` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`entrynr`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Daten für Tabelle `faq_faq_attachs`
-- 

INSERT INTO `faq_faq_attachs` VALUES (3, 88, 11);

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `faq_faq_keywords`
-- 

DROP TABLE IF EXISTS `faq_faq_keywords`;
CREATE TABLE `faq_faq_keywords` (
  `faqnr` int(10) unsigned NOT NULL default '0',
  `keywordnr` int(10) unsigned NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Daten für Tabelle `faq_faq_keywords`
-- 

INSERT INTO `faq_faq_keywords` VALUES (23, 13);
INSERT INTO `faq_faq_keywords` VALUES (9, 29);
INSERT INTO `faq_faq_keywords` VALUES (9, 15);
INSERT INTO `faq_faq_keywords` VALUES (18, 13);

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `faq_faq_os`
-- 

DROP TABLE IF EXISTS `faq_faq_os`;
CREATE TABLE `faq_faq_os` (
  `faqnr` int(10) unsigned NOT NULL default '0',
  `osnr` int(10) unsigned NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Daten für Tabelle `faq_faq_os`
-- 

INSERT INTO `faq_faq_os` VALUES (13, 1);
INSERT INTO `faq_faq_os` VALUES (56, 1);
INSERT INTO `faq_faq_os` VALUES (59, 2);

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `faq_faq_prog_version`
-- 

DROP TABLE IF EXISTS `faq_faq_prog_version`;
CREATE TABLE `faq_faq_prog_version` (
  `faqnr` int(10) unsigned NOT NULL default '0',
  `progversion` int(10) unsigned NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Daten für Tabelle `faq_faq_prog_version`
-- 

INSERT INTO `faq_faq_prog_version` VALUES (9, 2);
INSERT INTO `faq_faq_prog_version` VALUES (55, 3);
INSERT INTO `faq_faq_prog_version` VALUES (13, 2);
INSERT INTO `faq_faq_prog_version` VALUES (59, 5);

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `faq_faq_ref`
-- 

DROP TABLE IF EXISTS `faq_faq_ref`;
CREATE TABLE `faq_faq_ref` (
  `srcfaqnr` int(10) unsigned NOT NULL default '0',
  `language` varchar(5) NOT NULL default '',
  `destfaqnr` int(10) unsigned NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Daten für Tabelle `faq_faq_ref`
-- 

INSERT INTO `faq_faq_ref` VALUES (9, 'en', 8);

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `faq_fileextensions`
-- 

DROP TABLE IF EXISTS `faq_fileextensions`;
CREATE TABLE `faq_fileextensions` (
  `entrynr` int(10) unsigned NOT NULL auto_increment,
  `mimetype` int(10) unsigned NOT NULL default '0',
  `extension` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`entrynr`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Daten für Tabelle `faq_fileextensions`
-- 

INSERT INTO `faq_fileextensions` VALUES (1, 1, '.tar.gz');
INSERT INTO `faq_fileextensions` VALUES (2, 1, '.tgz');
INSERT INTO `faq_fileextensions` VALUES (3, 2, '.zip');
INSERT INTO `faq_fileextensions` VALUES (4, 3, '.tar');
INSERT INTO `faq_fileextensions` VALUES (5, 4, '.html');
INSERT INTO `faq_fileextensions` VALUES (6, 5, '.php');
INSERT INTO `faq_fileextensions` VALUES (7, 5, '.inc');
INSERT INTO `faq_fileextensions` VALUES (8, 5, '.txt');
INSERT INTO `faq_fileextensions` VALUES (9, 5, '.asc');
INSERT INTO `faq_fileextensions` VALUES (10, 6, '.bmp');
INSERT INTO `faq_fileextensions` VALUES (11, 6, '.ico');
INSERT INTO `faq_fileextensions` VALUES (12, 7, '.gif');
INSERT INTO `faq_fileextensions` VALUES (13, 8, '.jpg');
INSERT INTO `faq_fileextensions` VALUES (14, 8, '.jpeg');
INSERT INTO `faq_fileextensions` VALUES (15, 9, '.swf');
INSERT INTO `faq_fileextensions` VALUES (16, 10, '.doc');
INSERT INTO `faq_fileextensions` VALUES (17, 11, '.xls');
INSERT INTO `faq_fileextensions` VALUES (18, 12, '.pdf');
INSERT INTO `faq_fileextensions` VALUES (19, 13, '.aiff');
INSERT INTO `faq_fileextensions` VALUES (20, 13, '.aif');
INSERT INTO `faq_fileextensions` VALUES (21, 14, '.arj');
INSERT INTO `faq_fileextensions` VALUES (22, 15, '.asx');
INSERT INTO `faq_fileextensions` VALUES (23, 16, '.au');
INSERT INTO `faq_fileextensions` VALUES (25, 18, '.bz');
INSERT INTO `faq_fileextensions` VALUES (26, 19, '.bz2');
INSERT INTO `faq_fileextensions` VALUES (27, 20, '.dvi');
INSERT INTO `faq_fileextensions` VALUES (28, 21, '.hlp');
INSERT INTO `faq_fileextensions` VALUES (29, 22, '.hqx');
INSERT INTO `faq_fileextensions` VALUES (30, 23, '.mov');
INSERT INTO `faq_fileextensions` VALUES (31, 24, '.mp3');
INSERT INTO `faq_fileextensions` VALUES (32, 24, '.mpeg3');
INSERT INTO `faq_fileextensions` VALUES (33, 25, '.mpeg');
INSERT INTO `faq_fileextensions` VALUES (34, 25, '.mpg');
INSERT INTO `faq_fileextensions` VALUES (35, 26, '.mpp');
INSERT INTO `faq_fileextensions` VALUES (36, 27, '.png');
INSERT INTO `faq_fileextensions` VALUES (37, 28, '.ppt');
INSERT INTO `faq_fileextensions` VALUES (38, 29, '.ps');
INSERT INTO `faq_fileextensions` VALUES (39, 30, '.rtf');
INSERT INTO `faq_fileextensions` VALUES (40, 31, '.sea');
INSERT INTO `faq_fileextensions` VALUES (41, 32, '.tex');
INSERT INTO `faq_fileextensions` VALUES (42, 33, '.texi');
INSERT INTO `faq_fileextensions` VALUES (43, 34, '.tif');
INSERT INTO `faq_fileextensions` VALUES (44, 34, '.tiff');
INSERT INTO `faq_fileextensions` VALUES (45, 35, '.wmf');

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `faq_files`
-- 

DROP TABLE IF EXISTS `faq_files`;
CREATE TABLE `faq_files` (
  `entrynr` int(10) unsigned NOT NULL auto_increment,
  `bindata` longblob,
  `filename` varchar(240) NOT NULL default '',
  `mimetype` varchar(240) NOT NULL default '',
  `filesize` int(10) unsigned NOT NULL default '0',
  `fs_filename` varchar(240) NOT NULL default '',
  `downloads` int(10) unsigned NOT NULL default '0',
  `description` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`entrynr`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Daten für Tabelle `faq_files`
-- 

INSERT INTO `faq_files` VALUES (11, 0x3c3f786d6c2076657273696f6e3d22312e302220656e636f64696e673d2249534f2d383835392d3122203f3e0d0a3c7273732076657273696f6e3d22302e3931223e0d0a093c6368616e6e656c3e0d0a09093c7469746c653e57726974655468655765623c2f7469746c653e200d0a09093c6c696e6b3e687474703a2f2f77726974657468657765622e636f6d3c2f6c696e6b3e200d0a09093c6465736372697074696f6e3e4e65777320666f72207765622075736572732074686174207772697465206261636b3c2f6465736372697074696f6e3e200d0a09093c6c616e67756167653e656e2d75733c2f6c616e67756167653e200d0a09093c636f707972696768743e436f7079726967687420323030302c205772697465546865576562207465616d2e3c2f636f707972696768743e200d0a09093c6d616e6167696e67456469746f723e656469746f724077726974657468657765622e636f6d3c2f6d616e6167696e67456469746f723e200d0a09093c7765624d61737465723e7765626d61737465724077726974657468657765622e636f6d3c2f7765624d61737465723e200d0a09093c696d6167653e0d0a0909093c7469746c653e57726974655468655765623c2f7469746c653e200d0a0909093c75726c3e687474703a2f2f77726974657468657765622e636f6d2f696d616765732f6d796e6574736361706538382e6769663c2f75726c3e200d0a0909093c6c696e6b3e687474703a2f2f77726974657468657765622e636f6d3c2f6c696e6b3e200d0a0909093c77696474683e38383c2f77696474683e200d0a0909093c6865696768743e33313c2f6865696768743e200d0a0909093c6465736372697074696f6e3e4e65777320666f72207765622075736572732074686174207772697465206261636b3c2f6465736372697074696f6e3e200d0a0909093c2f696d6167653e0d0a09093c6974656d3e0d0a0909093c7469746c653e476976696e672074686520776f726c64206120706c75676761626c6520476e7574656c6c613c2f7469746c653e200d0a0909093c6c696e6b3e687474703a2f2f77726974657468657765622e636f6d2f726561642e7068703f6974656d3d32343c2f6c696e6b3e200d0a0909093c6465736372697074696f6e3e576f726c644f532069732061206672616d65776f726b206f6e20776869636820746f206275696c642070726f6772616d73207468617420776f726b206c696b6520467265656e6574206f7220476e7574656c6c61202d616c6c6f77696e67206469737472696275746564206170706c69636174696f6e73207573696e6720706565722d746f2d7065657220726f7574696e672e3c2f6465736372697074696f6e3e200d0a0909093c2f6974656d3e0d0a09093c6974656d3e0d0a0909093c7469746c653e53796e6469636174696f6e2064697363757373696f6e7320686f742075703c2f7469746c653e200d0a0909093c6c696e6b3e687474703a2f2f77726974657468657765622e636f6d2f726561642e7068703f6974656d3d32333c2f6c696e6b3e200d0a0909093c6465736372697074696f6e3e4166746572206120706572696f64206f6620646f726d616e63792c207468652053796e6469636174696f6e206d61696c696e67206c69737420686173206265636f6d652061637469766520616761696e2c207769746820636f6e747269627574696f6e732066726f6d206c65616465727320696e20747261646974696f6e616c206d6564696120616e64205765622073796e6469636174696f6e2e3c2f6465736372697074696f6e3e200d0a0909093c2f6974656d3e0d0a09093c6974656d3e0d0a0909093c7469746c653e506572736f6e616c207765622073657276657220696e74656772617465732066696c652073686172696e6720616e64206d6573736167696e673c2f7469746c653e200d0a0909093c6c696e6b3e687474703a2f2f77726974657468657765622e636f6d2f726561642e7068703f6974656d3d32323c2f6c696e6b3e200d0a0909093c6465736372697074696f6e3e546865204d6167692050726f6a65637420697320616e20696e6e6f7661746976652070726f6a65637420746f20637265617465206120636f6d62696e656420706572736f6e616c207765622073657276657220616e64206d6573736167696e672073797374656d207468617420656e61626c6573207468652073686172696e6720616e642073796e6368726f6e697a6174696f6e206f6620696e666f726d6174696f6e206163726f7373206465736b746f702c206c6170746f7020616e642070616c6d746f7020646576696365732e3c2f6465736372697074696f6e3e200d0a0909093c2f6974656d3e0d0a09093c6974656d3e0d0a0909093c7469746c653e53796e6469636174696f6e20616e64204d657461646174613c2f7469746c653e200d0a0909093c6c696e6b3e687474703a2f2f77726974657468657765622e636f6d2f726561642e7068703f6974656d3d32313c2f6c696e6b3e200d0a0909093c6465736372697074696f6e3e5253532069732070726f6261626c79207468652062657374206b6e6f776e206d6574616461746120666f726d61742061726f756e642e205244462069732070726f6261626c79206f6e65206f6620746865206c6561737420756e64657273746f6f642e20496e20746869732065737361792c207075626c6973686564206f6e206d79204f275265696c6c79204e6574776f726b207765626c6f672c2049206172677565207468617420746865206e6578742067656e65726174696f6e206f66205253532073686f756c64206265206261736564206f6e205244462e3c2f6465736372697074696f6e3e200d0a0909093c2f6974656d3e0d0a09093c6974656d3e0d0a0909093c7469746c653e554b20626c6f676765727320676574206f7267616e697365643c2f7469746c653e200d0a0909093c6c696e6b3e687474703a2f2f77726974657468657765622e636f6d2f726561642e7068703f6974656d3d32303c2f6c696e6b3e200d0a0909093c6465736372697074696f6e3e4c6f6f6b73206c696b6520746865207765626c6f6773207363656e6520697320676174686572696e672070616365206265796f6e64207468652073686f726573206f66207468652055532e2054686572652773206e6f77206120554b2d73706563696669632070616765206f6e207765626c6f67732e636f6d2c20616e642061206d61696c696e67206c697374206174206567726f7570732e3c2f6465736372697074696f6e3e200d0a0909093c2f6974656d3e0d0a09093c6974656d3e0d0a0909093c7469746c653e596f75726e616d65686572652e636f6d206d6f726520696d706f7274616e74207468616e20616e797468696e673c2f7469746c653e200d0a0909093c6c696e6b3e687474703a2f2f77726974657468657765622e636f6d2f726561642e7068703f6974656d3d31393c2f6c696e6b3e200d0a0909093c6465736372697074696f6e3e576861746576657220796f75277265207075626c697368696e67206f6e20746865207765622c20796f75722073697465206e616d6520697320746865206d6f73742076616c7561626c6520617373657420796f7520686176652c206163636f7264696e6720746f204361726c2053746561646d616e2e3c2f6465736372697074696f6e3e200d0a0909093c2f6974656d3e0d0a09093c2f6368616e6e656c3e0d0a093c2f7273733e0d0a, 'sampleRss.xml', 'application/octetstream', 2741, '', 0, 'Description');
INSERT INTO `faq_files` VALUES (12, '', 'beautifier-php-full-current.zip', 'application/x-zip-compressed', 763900, 'beautifier-php-full-current.zip', 0, '');

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `faq_filetypedescription`
-- 

DROP TABLE IF EXISTS `faq_filetypedescription`;
CREATE TABLE `faq_filetypedescription` (
  `mimetype` int(10) unsigned NOT NULL default '0',
  `language` varchar(10) NOT NULL default '',
  `description` varchar(80) NOT NULL default '',
  UNIQUE KEY `filetypedescription` (`mimetype`,`language`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Daten für Tabelle `faq_filetypedescription`
-- 

INSERT INTO `faq_filetypedescription` VALUES (14, 'de', 'ARJ-Archiv');
INSERT INTO `faq_filetypedescription` VALUES (14, 'en', 'arj archiv');
INSERT INTO `faq_filetypedescription` VALUES (2, 'de', 'ZIP-Archiv');
INSERT INTO `faq_filetypedescription` VALUES (2, 'en', 'zip archiv');

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `faq_freemailer`
-- 

DROP TABLE IF EXISTS `faq_freemailer`;
CREATE TABLE `faq_freemailer` (
  `entrynr` int(10) unsigned NOT NULL auto_increment,
  `address` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`entrynr`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Daten für Tabelle `faq_freemailer`
-- 

INSERT INTO `faq_freemailer` VALUES (2, 'foo.com');
INSERT INTO `faq_freemailer` VALUES (3, '@barodamail');
INSERT INTO `faq_freemailer` VALUES (4, '@flashmail');
INSERT INTO `faq_freemailer` VALUES (5, '@langoo');
INSERT INTO `faq_freemailer` VALUES (6, '@mail2');
INSERT INTO `faq_freemailer` VALUES (7, '0iq.net');
INSERT INTO `faq_freemailer` VALUES (8, '123Auditor.com');
INSERT INTO `faq_freemailer` VALUES (9, '123Banker.com');
INSERT INTO `faq_freemailer` VALUES (10, '123Caribbean.com');
INSERT INTO `faq_freemailer` VALUES (11, '123Denmark.com');
INSERT INTO `faq_freemailer` VALUES (12, '123Musician.com');
INSERT INTO `faq_freemailer` VALUES (13, '123Netherlands.com');
INSERT INTO `faq_freemailer` VALUES (14, '123Surgeon.com');
INSERT INTO `faq_freemailer` VALUES (15, '1coolplace.com');
INSERT INTO `faq_freemailer` VALUES (16, '1funplace.com');
INSERT INTO `faq_freemailer` VALUES (17, '1internetdrive.com');
INSERT INTO `faq_freemailer` VALUES (18, '1musicrow.com');
INSERT INTO `faq_freemailer` VALUES (19, '1netdrive.com');
INSERT INTO `faq_freemailer` VALUES (20, '1nsyncfan.com');
INSERT INTO `faq_freemailer` VALUES (21, '1under.com');
INSERT INTO `faq_freemailer` VALUES (22, '1webave.com');
INSERT INTO `faq_freemailer` VALUES (23, '1webhighway.com');
INSERT INTO `faq_freemailer` VALUES (24, '2die4.com');
INSERT INTO `faq_freemailer` VALUES (25, '75k.net');
INSERT INTO `faq_freemailer` VALUES (26, '93xrocks.com');
INSERT INTO `faq_freemailer` VALUES (27, 'accountant.com');
INSERT INTO `faq_freemailer` VALUES (28, 'Ace4ever.com');
INSERT INTO `faq_freemailer` VALUES (29, 'AceLook.com');
INSERT INTO `faq_freemailer` VALUES (30, 'ace-of-base.com');
INSERT INTO `faq_freemailer` VALUES (31, 'acmemail');
INSERT INTO `faq_freemailer` VALUES (32, 'adexec.com');
INSERT INTO `faq_freemailer` VALUES (33, 'admirateursecret.com');
INSERT INTO `faq_freemailer` VALUES (34, 'africamail.com');
INSERT INTO `faq_freemailer` VALUES (35, 'allergist.com');
INSERT INTO `faq_freemailer` VALUES (36, 'all-mychildren.com');
INSERT INTO `faq_freemailer` VALUES (37, 'altavista.com');
INSERT INTO `faq_freemailer` VALUES (38, 'altavista.net');
INSERT INTO `faq_freemailer` VALUES (39, 'alumnidirector.com');
INSERT INTO `faq_freemailer` VALUES (40, 'angelfire.com');
INSERT INTO `faq_freemailer` VALUES (41, 'another-world.com');
INSERT INTO `faq_freemailer` VALUES (42, 'antisocial.com');
INSERT INTO `faq_freemailer` VALUES (43, 'anywhereusa.com');
INSERT INTO `faq_freemailer` VALUES (44, 'apexmail.com');
INSERT INTO `faq_freemailer` VALUES (45, 'Aptos.net');
INSERT INTO `faq_freemailer` VALUES (46, 'archaeologist.com');
INSERT INTO `faq_freemailer` VALUES (47, 'arcticmail.com');
INSERT INTO `faq_freemailer` VALUES (48, 'artlover.com');
INSERT INTO `faq_freemailer` VALUES (49, 'asia.com');
INSERT INTO `faq_freemailer` VALUES (50, 'as-if.com');
INSERT INTO `faq_freemailer` VALUES (51, 'Aspen.to');
INSERT INTO `faq_freemailer` VALUES (52, 'australiamail.com');
INSERT INTO `faq_freemailer` VALUES (53, 'babylon5fan.com');
INSERT INTO `faq_freemailer` VALUES (54, 'backstreet-boys.com');
INSERT INTO `faq_freemailer` VALUES (55, 'baldandsexy.com');
INSERT INTO `faq_freemailer` VALUES (56, 'batcave.net');
INSERT INTO `faq_freemailer` VALUES (57, 'bay-watch.com');
INSERT INTO `faq_freemailer` VALUES (58, 'beenhad.com');
INSERT INTO `faq_freemailer` VALUES (59, 'berlin.com');
INSERT INTO `faq_freemailer` VALUES (60, 'beverlyhills-90210.com');
INSERT INTO `faq_freemailer` VALUES (61, 'bikerider.com');
INSERT INTO `faq_freemailer` VALUES (62, 'bizland.com');
INSERT INTO `faq_freemailer` VALUES (63, 'Blonde.to');
INSERT INTO `faq_freemailer` VALUES (64, 'bol.com.br');
INSERT INTO `faq_freemailer` VALUES (65, 'bonbon.net');
INSERT INTO `faq_freemailer` VALUES (66, 'bravenet.com');
INSERT INTO `faq_freemailer` VALUES (67, 'Britsworld.net');
INSERT INTO `faq_freemailer` VALUES (68, 'buffbody.com');
INSERT INTO `faq_freemailer` VALUES (69, 'bullsfan.com');
INSERT INTO `faq_freemailer` VALUES (70, 'bullsgame.com');
INSERT INTO `faq_freemailer` VALUES (71, 'buzon.as');
INSERT INTO `faq_freemailer` VALUES (72, 'buzzmail.com');
INSERT INTO `faq_freemailer` VALUES (73, 'Calera.net');
INSERT INTO `faq_freemailer` VALUES (74, 'canada.com');
INSERT INTO `faq_freemailer` VALUES (75, 'canwetalk.com');
INSERT INTO `faq_freemailer` VALUES (76, 'casa.as');
INSERT INTO `faq_freemailer` VALUES (77, 'catlover.com');
INSERT INTO `faq_freemailer` VALUES (78, 'cheerful.com');
INSERT INTO `faq_freemailer` VALUES (79, 'Cheesecube.com');
INSERT INTO `faq_freemailer` VALUES (80, 'chemist.com');
INSERT INTO `faq_freemailer` VALUES (81, 'clerk.com');
INSERT INTO `faq_freemailer` VALUES (82, 'cliffhanger.com');
INSERT INTO `faq_freemailer` VALUES (83, 'coffin-rock.com');
INSERT INTO `faq_freemailer` VALUES (84, 'columnist.com');
INSERT INTO `faq_freemailer` VALUES (85, 'comic.com');
INSERT INTO `faq_freemailer` VALUES (86, 'consultant.com');
INSERT INTO `faq_freemailer` VALUES (87, 'coolpets.net');
INSERT INTO `faq_freemailer` VALUES (88, 'Copenhagen.to');
INSERT INTO `faq_freemailer` VALUES (89, 'cornerpub.com');
INSERT INTO `faq_freemailer` VALUES (90, 'corporatedirtbag.com');
INSERT INTO `faq_freemailer` VALUES (91, 'counsellor.com');
INSERT INTO `faq_freemailer` VALUES (92, 'cptp.com');
INSERT INTO `faq_freemailer` VALUES (93, 'crazedanddazed.com');
INSERT INTO `faq_freemailer` VALUES (94, 'crazysexycool.com');
INSERT INTO `faq_freemailer` VALUES (95, 'cuteandcuddly.com');
INSERT INTO `faq_freemailer` VALUES (96, 'cutey.com');
INSERT INTO `faq_freemailer` VALUES (97, 'cyberbabies.com');
INSERT INTO `faq_freemailer` VALUES (98, 'cyberloveplace.com');
INSERT INTO `faq_freemailer` VALUES (99, 'dangerous-minds.com');
INSERT INTO `faq_freemailer` VALUES (100, 'dazedandconfused.com');
INSERT INTO `faq_freemailer` VALUES (101, 'deal-maker.com');
INSERT INTO `faq_freemailer` VALUES (102, 'death-star.com');
INSERT INTO `faq_freemailer` VALUES (103, 'deliveryman.com');
INSERT INTO `faq_freemailer` VALUES (104, 'diplomats.com');
INSERT INTO `faq_freemailer` VALUES (105, 'doctor.com');
INSERT INTO `faq_freemailer` VALUES (106, 'dog.com');
INSERT INTO `faq_freemailer` VALUES (107, 'doggiestyle.com');
INSERT INTO `faq_freemailer` VALUES (108, 'doglover.com');
INSERT INTO `faq_freemailer` VALUES (109, 'dontmesswithtexas.com');
INSERT INTO `faq_freemailer` VALUES (110, 'dr.com');
INSERT INTO `faq_freemailer` VALUES (111, 'droplets.com');
INSERT INTO `faq_freemailer` VALUES (112, 'dublin.com');
INSERT INTO `faq_freemailer` VALUES (113, 'earthalliance.com');
INSERT INTO `faq_freemailer` VALUES (114, 'earthdome.com');
INSERT INTO `faq_freemailer` VALUES (115, 'eboarding.net');
INSERT INTO `faq_freemailer` VALUES (116, 'email.com');
INSERT INTO `faq_freemailer` VALUES (117, 'emailchoice.com');
INSERT INTO `faq_freemailer` VALUES (118, 'end-war.com');
INSERT INTO `faq_freemailer` VALUES (119, 'engineer.com');
INSERT INTO `faq_freemailer` VALUES (120, 'Entrepreneur.to');
INSERT INTO `faq_freemailer` VALUES (121, 'espana.as');
INSERT INTO `faq_freemailer` VALUES (122, 'espn1000mail.com');
INSERT INTO `faq_freemailer` VALUES (123, 'esweeet.com');
INSERT INTO `faq_freemailer` VALUES (124, 'eudoramail.com');
INSERT INTO `faq_freemailer` VALUES (125, 'europe.com');
INSERT INTO `faq_freemailer` VALUES (126, 'eurosport.com');
INSERT INTO `faq_freemailer` VALUES (127, 'excite.com');
INSERT INTO `faq_freemailer` VALUES (128, 'execs.com');
INSERT INTO `faq_freemailer` VALUES (129, 'expressfind.com');
INSERT INTO `faq_freemailer` VALUES (130, 'Farmersville.net');
INSERT INTO `faq_freemailer` VALUES (131, 'financier.com');
INSERT INTO `faq_freemailer` VALUES (132, 'finebody.com');
INSERT INTO `faq_freemailer` VALUES (133, 'forpresident.com');
INSERT INTO `faq_freemailer` VALUES (134, 'for-president.com');
INSERT INTO `faq_freemailer` VALUES (135, 'frech.as');
INSERT INTO `faq_freemailer` VALUES (136, 'freeandsingle.com');
INSERT INTO `faq_freemailer` VALUES (137, 'freedns.da.ru');
INSERT INTO `faq_freemailer` VALUES (138, 'friendsfan.com');
INSERT INTO `faq_freemailer` VALUES (139, 'fuerdich.com');
INSERT INTO `faq_freemailer` VALUES (140, 'galaxy5.com');
INSERT INTO `faq_freemailer` VALUES (141, 'gamebox.net');
INSERT INTO `faq_freemailer` VALUES (142, 'gamespotmail.com');
INSERT INTO `faq_freemailer` VALUES (143, 'gardener.com');
INSERT INTO `faq_freemailer` VALUES (144, 'general-hospital.com');
INSERT INTO `faq_freemailer` VALUES (145, 'geocities.com');
INSERT INTO `faq_freemailer` VALUES (146, 'geologist.com');
INSERT INTO `faq_freemailer` VALUES (147, 'girlofyourdreams.com');
INSERT INTO `faq_freemailer` VALUES (148, 'givepeaceachance.com');
INSERT INTO `faq_freemailer` VALUES (149, 'gluecklich.net');
INSERT INTO `faq_freemailer` VALUES (150, 'gmx.net');
INSERT INTO `faq_freemailer` VALUES (151, 'gosympatico.ca');
INSERT INTO `faq_freemailer` VALUES (152, 'grabmail.com');
INSERT INTO `faq_freemailer` VALUES (153, 'graphic-designer.com');
INSERT INTO `faq_freemailer` VALUES (154, 'gurlmail.com');
INSERT INTO `faq_freemailer` VALUES (155, 'guyofyourdreams.com');
INSERT INTO `faq_freemailer` VALUES (156, 'hairdresser.net');
INSERT INTO `faq_freemailer` VALUES (157, 'hang-ten.com');
INSERT INTO `faq_freemailer` VALUES (158, 'hardrocksports.com');
INSERT INTO `faq_freemailer` VALUES (159, 'Harrisville.net');
INSERT INTO `faq_freemailer` VALUES (160, 'heartthrob.com');
INSERT INTO `faq_freemailer` VALUES (161, 'heehaw.com');
INSERT INTO `faq_freemailer` VALUES (162, 'hehe.com');
INSERT INTO `faq_freemailer` VALUES (163, 'helloworld.tc');
INSERT INTO `faq_freemailer` VALUES (164, 'hjem.as');
INSERT INTO `faq_freemailer` VALUES (165, 'hollywoodkids.com');
INSERT INTO `faq_freemailer` VALUES (166, 'homeschools.com');
INSERT INTO `faq_freemailer` VALUES (167, 'Honolulu.to');
INSERT INTO `faq_freemailer` VALUES (168, 'hooola.com');
INSERT INTO `faq_freemailer` VALUES (169, 'Hopkinton.net');
INSERT INTO `faq_freemailer` VALUES (170, 'hotmail.com');
INSERT INTO `faq_freemailer` VALUES (171, 'hotpop.com');
INSERT INTO `faq_freemailer` VALUES (172, 'hot-shot.com');
INSERT INTO `faq_freemailer` VALUES (173, 'howamazing.com');
INSERT INTO `faq_freemailer` VALUES (174, 'iamit.com');
INSERT INTO `faq_freemailer` VALUES (175, 'iamwaiting.com');
INSERT INTO `faq_freemailer` VALUES (176, 'iamwasted.com');
INSERT INTO `faq_freemailer` VALUES (177, 'iamyours.com');
INSERT INTO `faq_freemailer` VALUES (178, 'icqmail.com');
INSERT INTO `faq_freemailer` VALUES (179, 'ididitmyway.com');
INSERT INTO `faq_freemailer` VALUES (180, 'ihavepms.com');
INSERT INTO `faq_freemailer` VALUES (181, 'ijustdontcare.com');
INSERT INTO `faq_freemailer` VALUES (182, 'ilovechocolate.com');
INSERT INTO `faq_freemailer` VALUES (183, 'ilovepoems.com');
INSERT INTO `faq_freemailer` VALUES (184, 'imatrekkie.com');
INSERT INTO `faq_freemailer` VALUES (185, 'imneverwrong.com');
INSERT INTO `faq_freemailer` VALUES (186, 'imstressed.com');
INSERT INTO `faq_freemailer` VALUES (187, 'imtoosexy.com');
INSERT INTO `faq_freemailer` VALUES (188, 'inbox.as');
INSERT INTO `faq_freemailer` VALUES (189, 'inbox.net');
INSERT INTO `faq_freemailer` VALUES (190, 'indabox.com');
INSERT INTO `faq_freemailer` VALUES (191, 'innboks.com');
INSERT INTO `faq_freemailer` VALUES (192, 'inorbit.com');
INSERT INTO `faq_freemailer` VALUES (193, 'insurer.com');
INSERT INTO `faq_freemailer` VALUES (194, 'internetdrive.com');
INSERT INTO `faq_freemailer` VALUES (195, 'isellcars.com');
INSERT INTO `faq_freemailer` VALUES (196, 'itookmyprozac.com');
INSERT INTO `faq_freemailer` VALUES (197, 'ivebeenframed.com');
INSERT INTO `faq_freemailer` VALUES (198, 'ivillage.com');
INSERT INTO `faq_freemailer` VALUES (199, 'japan.com');
INSERT INTO `faq_freemailer` VALUES (200, 'Jaunty.net');
INSERT INTO `faq_freemailer` VALUES (201, 'jazzandjava.com');
INSERT INTO `faq_freemailer` VALUES (202, 'jazzgame.com');
INSERT INTO `faq_freemailer` VALUES (203, 'jetaime.as');
INSERT INTO `faq_freemailer` VALUES (204, 'journalist.com');
INSERT INTO `faq_freemailer` VALUES (205, 'keg-party.com');
INSERT INTO `faq_freemailer` VALUES (206, 'kornfreak.com');
INSERT INTO `faq_freemailer` VALUES (207, 'lachen.net');
INSERT INTO `faq_freemailer` VALUES (208, 'lawyer.com');
INSERT INTO `faq_freemailer` VALUES (209, 'legislator.com');
INSERT INTO `faq_freemailer` VALUES (210, 'linuxplanet.nu');
INSERT INTO `faq_freemailer` VALUES (211, 'LiveAmerica.net');
INSERT INTO `faq_freemailer` VALUES (212, 'LiveAustralia.net');
INSERT INTO `faq_freemailer` VALUES (213, 'LiveCanada.net');
INSERT INTO `faq_freemailer` VALUES (214, 'LiveNewzealand.net');
INSERT INTO `faq_freemailer` VALUES (215, 'lobbyist.com');
INSERT INTO `faq_freemailer` VALUES (216, 'localbar.com');
INSERT INTO `faq_freemailer` VALUES (217, 'london.com');
INSERT INTO `faq_freemailer` VALUES (218, 'lookingforme.com');
INSERT INTO `faq_freemailer` VALUES (219, 'Looneyville.com');
INSERT INTO `faq_freemailer` VALUES (220, 'loveable.com');
INSERT INTO `faq_freemailer` VALUES (221, 'lover-boy.com');
INSERT INTO `faq_freemailer` VALUES (222, 'lovergirl.com');
INSERT INTO `faq_freemailer` VALUES (223, 'lycos.com');
INSERT INTO `faq_freemailer` VALUES (224, 'mad.scientist.com');
INSERT INTO `faq_freemailer` VALUES (225, 'madrid.com');
INSERT INTO `faq_freemailer` VALUES (226, 'mail.com');
INSERT INTO `faq_freemailer` VALUES (227, 'mailbox.as');
INSERT INTO `faq_freemailer` VALUES (228, 'Manhattan.to');
INSERT INTO `faq_freemailer` VALUES (229, 'married-not.com');
INSERT INTO `faq_freemailer` VALUES (230, 'marsattack.com');
INSERT INTO `faq_freemailer` VALUES (231, 'melrose-place.com');
INSERT INTO `faq_freemailer` VALUES (232, 'Milan.to');
INSERT INTO `faq_freemailer` VALUES (233, 'millionaireintraining.com');
INSERT INTO `faq_freemailer` VALUES (234, 'minister.com');
INSERT INTO `faq_freemailer` VALUES (235, 'moonman.com');
INSERT INTO `faq_freemailer` VALUES (236, 'moonshinehollow.com');
INSERT INTO `faq_freemailer` VALUES (237, 'moscowmail.com');
INSERT INTO `faq_freemailer` VALUES (238, 'mostlysunny.com');
INSERT INTO `faq_freemailer` VALUES (239, 'most-wanted.com');
INSERT INTO `faq_freemailer` VALUES (240, 'MountShasta.net');
INSERT INTO `faq_freemailer` VALUES (241, 'mrearl.com');
INSERT INTO `faq_freemailer` VALUES (242, 'mr-potatohead.com');
INSERT INTO `faq_freemailer` VALUES (243, 'msuspartans.com');
INSERT INTO `faq_freemailer` VALUES (244, 'mtv.com');
INSERT INTO `faq_freemailer` VALUES (245, 'munich.com');
INSERT INTO `faq_freemailer` VALUES (246, 'musician.org');
INSERT INTO `faq_freemailer` VALUES (247, 'mydotcomaddress.com');
INSERT INTO `faq_freemailer` VALUES (248, 'mynetaddress.com');
INSERT INTO `faq_freemailer` VALUES (249, 'myownemail.com');
INSERT INTO `faq_freemailer` VALUES (250, 'myself.com');
INSERT INTO `faq_freemailer` VALUES (251, 'mystupidjob.com');
INSERT INTO `faq_freemailer` VALUES (252, 'mystupidschool.com');
INSERT INTO `faq_freemailer` VALUES (253, 'MySweden.net');
INSERT INTO `faq_freemailer` VALUES (254, 'n2.com');
INSERT INTO `faq_freemailer` VALUES (255, 'nachtschwaermer.com');
INSERT INTO `faq_freemailer` VALUES (256, 'nameplanet.com');
INSERT INTO `faq_freemailer` VALUES (257, 'netexecutive.com');
INSERT INTO `faq_freemailer` VALUES (258, 'netexpressway.com');
INSERT INTO `faq_freemailer` VALUES (259, 'netlane.com');
INSERT INTO `faq_freemailer` VALUES (260, 'netlimit.com');
INSERT INTO `faq_freemailer` VALUES (261, 'netscape.net');
INSERT INTO `faq_freemailer` VALUES (262, 'netspeedway.com');
INSERT INTO `faq_freemailer` VALUES (263, 'nicetomeetyou.to');
INSERT INTO `faq_freemailer` VALUES (264, 'nirvanafan.com');
INSERT INTO `faq_freemailer` VALUES (265, 'Nope.cc');
INSERT INTO `faq_freemailer` VALUES (266, 'notme.com');
INSERT INTO `faq_freemailer` VALUES (267, 'nycmail.com');
INSERT INTO `faq_freemailer` VALUES (268, 'oceanfree.net');
INSERT INTO `faq_freemailer` VALUES (269, 'ohio-state.com');
INSERT INTO `faq_freemailer` VALUES (270, 'onebox.com');
INSERT INTO `faq_freemailer` VALUES (271, 'onecooldude.com');
INSERT INTO `faq_freemailer` VALUES (272, 'optician.com');
INSERT INTO `faq_freemailer` VALUES (273, 'o-tay.com');
INSERT INTO `faq_freemailer` VALUES (274, 'over-the-rainbow.com');
INSERT INTO `faq_freemailer` VALUES (275, 'packersfan.com');
INSERT INTO `faq_freemailer` VALUES (276, 'Pancake.cc');
INSERT INTO `faq_freemailer` VALUES (277, 'paris.com');
INSERT INTO `faq_freemailer` VALUES (278, 'partlycloudy.com');
INSERT INTO `faq_freemailer` VALUES (279, 'pcpostal.com');
INSERT INTO `faq_freemailer` VALUES (280, 'pediatrician.com');
INSERT INTO `faq_freemailer` VALUES (281, 'perfectmail.com');
INSERT INTO `faq_freemailer` VALUES (282, 'pg13.cc');
INSERT INTO `faq_freemailer` VALUES (283, 'phayze.com');
INSERT INTO `faq_freemailer` VALUES (284, 'phreaker.net');
INSERT INTO `faq_freemailer` VALUES (285, 'planetout.com');
INSERT INTO `faq_freemailer` VALUES (286, 'playful.com');
INSERT INTO `faq_freemailer` VALUES (287, 'poetic.com');
INSERT INTO `faq_freemailer` VALUES (288, 'pool-sharks.com');
INSERT INTO `faq_freemailer` VALUES (289, 'popstar.com');
INSERT INTO `faq_freemailer` VALUES (290, 'positive-thinking.com');
INSERT INTO `faq_freemailer` VALUES (291, 'post.com');
INSERT INTO `faq_freemailer` VALUES (292, 'postmaster.co.uk');
INSERT INTO `faq_freemailer` VALUES (293, 'presidency.com');
INSERT INTO `faq_freemailer` VALUES (294, 'priest.com');
INSERT INTO `faq_freemailer` VALUES (295, 'private.as');
INSERT INTO `faq_freemailer` VALUES (296, 'programmer.net');
INSERT INTO `faq_freemailer` VALUES (297, 'psicorps.com');
INSERT INTO `faq_freemailer` VALUES (298, 'publicist.com');
INSERT INTO `faq_freemailer` VALUES (299, 'pulp-fiction.com');
INSERT INTO `faq_freemailer` VALUES (300, 'Pune.to');
INSERT INTO `faq_freemailer` VALUES (301, 'punkass.com');
INSERT INTO `faq_freemailer` VALUES (302, 'quackquack.com');
INSERT INTO `faq_freemailer` VALUES (303, 'realtyagent.com');
INSERT INTO `faq_freemailer` VALUES (304, 'rednecks.com');
INSERT INTO `faq_freemailer` VALUES (305, 'registerednurses.com');
INSERT INTO `faq_freemailer` VALUES (306, 'repairman.com');
INSERT INTO `faq_freemailer` VALUES (307, 'representative.com');
INSERT INTO `faq_freemailer` VALUES (308, 'rescueteam.com');
INSERT INTO `faq_freemailer` VALUES (309, 'rodrun.com');
INSERT INTO `faq_freemailer` VALUES (310, 'romanticdate.net');
INSERT INTO `faq_freemailer` VALUES (311, 'rome.com');
INSERT INTO `faq_freemailer` VALUES (312, 'rubyridge.com');
INSERT INTO `faq_freemailer` VALUES (313, 'sailormoonfan.com');
INSERT INTO `faq_freemailer` VALUES (314, 'saintly.com');
INSERT INTO `faq_freemailer` VALUES (315, 'samerica.com');
INSERT INTO `faq_freemailer` VALUES (316, 'sanfranmail.com');
INSERT INTO `faq_freemailer` VALUES (317, 'schpaaa.net');
INSERT INTO `faq_freemailer` VALUES (318, 'scientist.com');
INSERT INTO `faq_freemailer` VALUES (319, 'scifianime.com');
INSERT INTO `faq_freemailer` VALUES (320, 'scififan.com');
INSERT INTO `faq_freemailer` VALUES (321, 'seductive.com');
INSERT INTO `faq_freemailer` VALUES (322, 'selin.com');
INSERT INTO `faq_freemailer` VALUES (323, 'sexmagnet.com');
INSERT INTO `faq_freemailer` VALUES (324, 'siii.net');
INSERT INTO `faq_freemailer` VALUES (325, 'singapore.com');
INSERT INTO `faq_freemailer` VALUES (326, 'skatenc.com');
INSERT INTO `faq_freemailer` VALUES (327, 'smashing-pumpkins.com');
INSERT INTO `faq_freemailer` VALUES (328, 'smileyface.com');
INSERT INTO `faq_freemailer` VALUES (329, 'sociologist.com');
INSERT INTO `faq_freemailer` VALUES (330, 'somethingorother.com');
INSERT INTO `faq_freemailer` VALUES (331, 'soon.com');
INSERT INTO `faq_freemailer` VALUES (332, 'sourcechannel.com');
INSERT INTO `faq_freemailer` VALUES (333, 'speed-racer.com');
INSERT INTO `faq_freemailer` VALUES (334, 'sportsaddict.com');
INSERT INTO `faq_freemailer` VALUES (335, 'spyring.com');
INSERT INTO `faq_freemailer` VALUES (336, 'starmail.com');
INSERT INTO `faq_freemailer` VALUES (337, 'starplace.com');
INSERT INTO `faq_freemailer` VALUES (338, 'startrekave.com');
INSERT INTO `faq_freemailer` VALUES (339, 'startreklane.com');
INSERT INTO `faq_freemailer` VALUES (340, 'starwarsave.com');
INSERT INTO `faq_freemailer` VALUES (341, 'starwarsfan.com');
INSERT INTO `faq_freemailer` VALUES (342, 'stopdropandroll.com');
INSERT INTO `faq_freemailer` VALUES (343, 'sunrise-sunset.com');
INSERT INTO `faq_freemailer` VALUES (344, 'sunsgame.com');
INSERT INTO `faq_freemailer` VALUES (345, 'superheros.as');
INSERT INTO `faq_freemailer` VALUES (346, 'supernetpower.com');
INSERT INTO `faq_freemailer` VALUES (347, 'Surfdude.to');
INSERT INTO `faq_freemailer` VALUES (348, 'surffast.com');
INSERT INTO `faq_freemailer` VALUES (349, 'Surfout.com');
INSERT INTO `faq_freemailer` VALUES (350, 'Switz.net');
INSERT INTO `faq_freemailer` VALUES (351, 'teacher.com');
INSERT INTO `faq_freemailer` VALUES (352, 'techemail.com');
INSERT INTO `faq_freemailer` VALUES (353, 'techfreak.to');
INSERT INTO `faq_freemailer` VALUES (354, 'techie.com');
INSERT INTO `faq_freemailer` VALUES (355, 'teenagedirtbag.com');
INSERT INTO `faq_freemailer` VALUES (356, 'tellmeimcute.com');
INSERT INTO `faq_freemailer` VALUES (357, 'tfcentral.co.uk');
INSERT INTO `faq_freemailer` VALUES (358, 'tfcentral.org');
INSERT INTO `faq_freemailer` VALUES (359, 'the18th.com');
INSERT INTO `faq_freemailer` VALUES (360, 'the-any-key.com');
INSERT INTO `faq_freemailer` VALUES (361, 'the-big-apple.com');
INSERT INTO `faq_freemailer` VALUES (362, 'the-eagles.com');
INSERT INTO `faq_freemailer` VALUES (363, 'theglobe.com');
INSERT INTO `faq_freemailer` VALUES (364, 'thegolfcourse.com');
INSERT INTO `faq_freemailer` VALUES (365, 'theinbox.org');
INSERT INTO `faq_freemailer` VALUES (366, 'the-lair.com');
INSERT INTO `faq_freemailer` VALUES (367, 'thelanddownunder.com');
INSERT INTO `faq_freemailer` VALUES (368, 'themail.com');
INSERT INTO `faq_freemailer` VALUES (369, 'the-pentagon.com');
INSERT INTO `faq_freemailer` VALUES (370, 'the-police.com');
INSERT INTO `faq_freemailer` VALUES (371, 'theraces.com');
INSERT INTO `faq_freemailer` VALUES (372, 'the-stock-market.com');
INSERT INTO `faq_freemailer` VALUES (373, 'theteebox.com');
INSERT INTO `faq_freemailer` VALUES (374, 'thevortex.com');
INSERT INTO `faq_freemailer` VALUES (375, 'tigerdrive.com');
INSERT INTO `faq_freemailer` VALUES (376, 't-mail.com');
INSERT INTO `faq_freemailer` VALUES (377, 'Toast.cc');
INSERT INTO `faq_freemailer` VALUES (378, 'tokyo.com');
INSERT INTO `faq_freemailer` VALUES (379, 'toosexyforyou.com');
INSERT INTO `faq_freemailer` VALUES (380, 'topniveau.net');
INSERT INTO `faq_freemailer` VALUES (381, 'toughguy.net');
INSERT INTO `faq_freemailer` VALUES (382, 'tropicalstorm.com');
INSERT INTO `faq_freemailer` VALUES (383, 'trust-me.com');
INSERT INTO `faq_freemailer` VALUES (384, 'txas.com');
INSERT INTO `faq_freemailer` VALUES (385, 'uclabruins.com');
INSERT INTO `faq_freemailer` VALUES (386, 'umpire.com');
INSERT INTO `faq_freemailer` VALUES (387, 'underwriters.com');
INSERT INTO `faq_freemailer` VALUES (388, 'usa.com');
INSERT INTO `faq_freemailer` VALUES (389, 'USCongressman.net');
INSERT INTO `faq_freemailer` VALUES (390, 'uymail.com');
INSERT INTO `faq_freemailer` VALUES (391, 'vh1.com');
INSERT INTO `faq_freemailer` VALUES (392, 'vorlonempire.com');
INSERT INTO `faq_freemailer` VALUES (393, 'webave.com');
INSERT INTO `faq_freemailer` VALUES (394, 'webjetters.com');
INSERT INTO `faq_freemailer` VALUES (395, 'wetwetwet.com');
INSERT INTO `faq_freemailer` VALUES (396, 'white-star.com');
INSERT INTO `faq_freemailer` VALUES (397, 'whoever.com');
INSERT INTO `faq_freemailer` VALUES (398, 'winning.com');
INSERT INTO `faq_freemailer` VALUES (399, 'winningteam.com');
INSERT INTO `faq_freemailer` VALUES (400, 'Wiskonsin.com');
INSERT INTO `faq_freemailer` VALUES (401, 'witty.com');
INSERT INTO `faq_freemailer` VALUES (402, 'wolf-web.com');
INSERT INTO `faq_freemailer` VALUES (403, 'worldvillage.com');
INSERT INTO `faq_freemailer` VALUES (404, 'wouldilie.com');
INSERT INTO `faq_freemailer` VALUES (405, 'writeme.com');
INSERT INTO `faq_freemailer` VALUES (406, 'www.com');
INSERT INTO `faq_freemailer` VALUES (407, 'wzr.net');
INSERT INTO `faq_freemailer` VALUES (408, 'xfilesfan.com');
INSERT INTO `faq_freemailer` VALUES (409, 'yada-yada.com');
INSERT INTO `faq_freemailer` VALUES (410, 'yahoo.com');
INSERT INTO `faq_freemailer` VALUES (411, 'yeayea.com');
INSERT INTO `faq_freemailer` VALUES (412, 'youareadork.com');
INSERT INTO `faq_freemailer` VALUES (413, 'your-house.com');
INSERT INTO `faq_freemailer` VALUES (414, 'yours.com');
INSERT INTO `faq_freemailer` VALUES (415, 'yuppieintraining.com');
INSERT INTO `faq_freemailer` VALUES (416, 'zahadum.com');
INSERT INTO `faq_freemailer` VALUES (417, 'zdnetonebox.com');
INSERT INTO `faq_freemailer` VALUES (418, 'zzn.com');
INSERT INTO `faq_freemailer` VALUES (419, 'xxx.com');

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `faq_hostcache`
-- 

DROP TABLE IF EXISTS `faq_hostcache`;
CREATE TABLE `faq_hostcache` (
  `ipadr` varchar(16) NOT NULL default '0',
  `hostname` varchar(240) NOT NULL default '',
  UNIQUE KEY `ipadr` (`ipadr`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Daten für Tabelle `faq_hostcache`
-- 

INSERT INTO `faq_hostcache` VALUES ('127.0.0.1', 'athlon.boesch.lan');

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `faq_iplog`
-- 

DROP TABLE IF EXISTS `faq_iplog`;
CREATE TABLE `faq_iplog` (
  `lognr` int(10) unsigned NOT NULL auto_increment,
  `usernr` int(10) unsigned NOT NULL default '0',
  `logtime` datetime NOT NULL default '0000-00-00 00:00:00',
  `ipadr` varchar(16) NOT NULL default '',
  `used_lang` varchar(4) NOT NULL default '',
  PRIMARY KEY  (`lognr`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Daten für Tabelle `faq_iplog`
-- 

INSERT INTO `faq_iplog` VALUES (1128, 1, '2005-07-21 23:57:02', '10.1.1.20', 'de');
INSERT INTO `faq_iplog` VALUES (1127, 1, '2005-07-19 12:11:55', '10.1.1.20', 'de');
INSERT INTO `faq_iplog` VALUES (1126, 1, '2005-02-23 22:31:28', '10.1.1.20', 'de');
INSERT INTO `faq_iplog` VALUES (1125, 1, '2005-01-31 12:59:28', '10.1.1.20', 'de');
INSERT INTO `faq_iplog` VALUES (1124, 1, '2005-01-31 12:08:15', '10.1.1.20', 'de');
INSERT INTO `faq_iplog` VALUES (1123, 1, '2005-01-31 11:55:19', '10.1.1.20', 'de');
INSERT INTO `faq_iplog` VALUES (1122, 1, '2005-01-31 01:59:16', '10.1.1.20', 'en');
INSERT INTO `faq_iplog` VALUES (1121, 1, '2005-01-31 00:55:08', '10.1.1.20', 'de');
INSERT INTO `faq_iplog` VALUES (1120, 1, '2005-01-20 09:02:43', '10.1.1.20', 'de');
INSERT INTO `faq_iplog` VALUES (1119, 1, '2004-11-21 10:54:11', '10.1.1.20', 'de');
INSERT INTO `faq_iplog` VALUES (1116, 1, '2004-11-20 13:24:35', '10.1.1.20', 'de');
INSERT INTO `faq_iplog` VALUES (1117, 1, '2004-11-20 22:04:24', '10.1.1.20', 'de');
INSERT INTO `faq_iplog` VALUES (1118, 1, '2004-11-20 22:20:22', '10.1.1.20', 'de');
INSERT INTO `faq_iplog` VALUES (1115, 1, '2004-11-20 12:48:24', '10.1.1.20', 'de');
INSERT INTO `faq_iplog` VALUES (1114, 1, '2004-11-20 00:06:00', '10.1.1.20', 'de');
INSERT INTO `faq_iplog` VALUES (1113, 1, '2004-11-01 21:58:52', '10.1.1.20', 'de');
INSERT INTO `faq_iplog` VALUES (1112, 1, '2004-10-29 11:57:29', '10.1.1.20', 'de');
INSERT INTO `faq_iplog` VALUES (1111, 1, '2004-10-29 10:51:31', '10.1.1.20', 'de');
INSERT INTO `faq_iplog` VALUES (1110, 1, '2004-10-27 11:12:07', '10.1.1.20', 'de');
INSERT INTO `faq_iplog` VALUES (1109, 1, '2004-10-19 12:52:18', '10.1.1.20', 'de');
INSERT INTO `faq_iplog` VALUES (1108, 1, '2004-10-16 16:52:29', '10.1.1.20', 'de');
INSERT INTO `faq_iplog` VALUES (1107, 1, '2004-10-14 10:47:22', '10.1.1.20', 'de');
INSERT INTO `faq_iplog` VALUES (1106, 1, '2004-10-05 11:34:05', '10.1.1.20', 'de');
INSERT INTO `faq_iplog` VALUES (1105, 1, '2004-10-05 11:31:58', '10.1.1.20', 'de');
INSERT INTO `faq_iplog` VALUES (1104, 1, '2004-09-21 17:00:02', '10.1.1.20', 'de');
INSERT INTO `faq_iplog` VALUES (1103, 1, '2004-09-20 22:22:01', '10.1.1.20', 'de');
INSERT INTO `faq_iplog` VALUES (543, 5, '2002-06-03 21:38:22', '10.1.1.30', 'de');
INSERT INTO `faq_iplog` VALUES (1102, 1, '2004-09-20 22:10:39', '10.1.1.20', 'de');
INSERT INTO `faq_iplog` VALUES (1101, 1, '2004-08-27 14:06:44', '10.1.1.20', 'de');
INSERT INTO `faq_iplog` VALUES (1100, 1, '2004-08-27 12:51:33', '10.1.1.20', 'de');
INSERT INTO `faq_iplog` VALUES (1099, 1, '2004-08-05 21:37:44', '10.1.1.20', 'de');
INSERT INTO `faq_iplog` VALUES (1129, 1, '2005-08-04 22:31:15', '10.1.1.20', 'de');
INSERT INTO `faq_iplog` VALUES (1130, 1, '2005-08-05 00:21:35', '10.1.1.20', 'de');
INSERT INTO `faq_iplog` VALUES (1131, 1, '2005-08-06 14:21:16', '10.1.1.20', 'de');
INSERT INTO `faq_iplog` VALUES (1132, 1, '2005-08-06 14:34:11', '10.1.1.20', 'de');
INSERT INTO `faq_iplog` VALUES (1133, 1, '2005-08-06 19:14:08', '10.1.1.20', 'de');
INSERT INTO `faq_iplog` VALUES (1134, 1, '2005-08-07 22:27:42', '10.1.1.20', 'de');
INSERT INTO `faq_iplog` VALUES (1135, 1, '2005-08-07 22:29:25', '10.1.1.20', 'de');
INSERT INTO `faq_iplog` VALUES (1136, 1, '2005-08-10 12:18:18', '10.1.1.20', 'de');
INSERT INTO `faq_iplog` VALUES (1137, 1, '2005-08-12 15:23:27', '10.1.1.20', 'de');
INSERT INTO `faq_iplog` VALUES (1138, 1, '2005-10-22 12:04:44', '10.1.1.20', 'de');

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `faq_kb_articles`
-- 

DROP TABLE IF EXISTS `faq_kb_articles`;
CREATE TABLE `faq_kb_articles` (
  `articlenr` int(10) unsigned NOT NULL auto_increment,
  `programm` int(10) unsigned NOT NULL default '0',
  `heading` varchar(240) NOT NULL default '',
  `article` text NOT NULL,
  `ratingcount` int(10) unsigned NOT NULL default '0',
  `rating` int(10) unsigned NOT NULL default '0',
  `editor` varchar(80) NOT NULL default '',
  `lastedited` date NOT NULL default '0000-00-00',
  `category` int(10) unsigned NOT NULL default '0',
  `displaypos` int(10) unsigned NOT NULL default '0',
  `subcategory` int(10) unsigned NOT NULL default '0',
  `views` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`articlenr`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Daten für Tabelle `faq_kb_articles`
-- 

INSERT INTO `faq_kb_articles` VALUES (1, 17, 'sdadasdsa', '&lt;!-- SPCode faqref2 Start --&gt;&lt;A HREF=&quot;{url_faqengine}/faq.php?{lang}&amp;display=faq&amp;faqnr=75&amp;catnr=11&amp;prog=tst2&quot; TARGET=&quot;faqdisplay&quot;&gt;AA&lt;/A&gt;&lt;!-- SPCode faqref2 End --&gt;dsadadasdasdsa<BR><BR>', 0, 0, 'support', '2004-09-21', 0, 1, 0, 0);
INSERT INTO `faq_kb_articles` VALUES (2, 17, 'Entry from offline', 'This is an entry from the offline editor<BR><BR><BR>', 0, 0, 'support', '2004-09-21', 0, 2, 0, 0);
INSERT INTO `faq_kb_articles` VALUES (10, 17, 'HTML Test', '&lt;h2&gt;HTML Test&lt;/h2&gt;<BR>', 0, 0, 'support', '2004-09-21', 0, 1, 0, 0);

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `faq_kb_attachs`
-- 

DROP TABLE IF EXISTS `faq_kb_attachs`;
CREATE TABLE `faq_kb_attachs` (
  `entrynr` int(10) unsigned NOT NULL auto_increment,
  `articlenr` int(10) unsigned NOT NULL default '0',
  `attachnr` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`entrynr`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Daten für Tabelle `faq_kb_attachs`
-- 

INSERT INTO `faq_kb_attachs` VALUES (1, 48, 11);

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `faq_kb_cat`
-- 

DROP TABLE IF EXISTS `faq_kb_cat`;
CREATE TABLE `faq_kb_cat` (
  `catnr` int(10) unsigned NOT NULL auto_increment,
  `catname` varchar(240) NOT NULL default '',
  `heading` varchar(250) NOT NULL default '',
  `programm` int(10) unsigned NOT NULL default '0',
  `displaypos` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`catnr`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Daten für Tabelle `faq_kb_cat`
-- 

INSERT INTO `faq_kb_cat` VALUES (8, 'kbcat2', 'KB category 2', 12, 2);
INSERT INTO `faq_kb_cat` VALUES (5, '&#22914;&#26524;&#24744;&#30340;&#39046;&#22495;&#23384;&#22312;&#20110;&#25105; <test> ""', '&#22914;&#26524;&#24744;&#30340;&#39046;&#22495;&#23384;&#22312;&#20110;&#25105; <test> ""', 21, 1);
INSERT INTO `faq_kb_cat` VALUES (7, 'kbcat1', 'KB category', 12, 1);

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `faq_kb_keywords`
-- 

DROP TABLE IF EXISTS `faq_kb_keywords`;
CREATE TABLE `faq_kb_keywords` (
  `articlenr` int(10) unsigned NOT NULL default '0',
  `keywordnr` int(10) unsigned NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Daten für Tabelle `faq_kb_keywords`
-- 

INSERT INTO `faq_kb_keywords` VALUES (31, 15);
INSERT INTO `faq_kb_keywords` VALUES (30, 15);
INSERT INTO `faq_kb_keywords` VALUES (29, 5);
INSERT INTO `faq_kb_keywords` VALUES (28, 23);
INSERT INTO `faq_kb_keywords` VALUES (27, 15);
INSERT INTO `faq_kb_keywords` VALUES (26, 15);
INSERT INTO `faq_kb_keywords` VALUES (25, 15);
INSERT INTO `faq_kb_keywords` VALUES (24, 5);
INSERT INTO `faq_kb_keywords` VALUES (23, 5);
INSERT INTO `faq_kb_keywords` VALUES (22, 5);
INSERT INTO `faq_kb_keywords` VALUES (21, 5);
INSERT INTO `faq_kb_keywords` VALUES (20, 5);
INSERT INTO `faq_kb_keywords` VALUES (19, 5);
INSERT INTO `faq_kb_keywords` VALUES (18, 17);
INSERT INTO `faq_kb_keywords` VALUES (15, 5);
INSERT INTO `faq_kb_keywords` VALUES (32, 15);
INSERT INTO `faq_kb_keywords` VALUES (35, 24);
INSERT INTO `faq_kb_keywords` VALUES (34, 15);
INSERT INTO `faq_kb_keywords` VALUES (36, 15);
INSERT INTO `faq_kb_keywords` VALUES (37, 15);
INSERT INTO `faq_kb_keywords` VALUES (38, 5);
INSERT INTO `faq_kb_keywords` VALUES (39, 15);
INSERT INTO `faq_kb_keywords` VALUES (40, 15);
INSERT INTO `faq_kb_keywords` VALUES (41, 5);
INSERT INTO `faq_kb_keywords` VALUES (42, 15);
INSERT INTO `faq_kb_keywords` VALUES (43, 27);
INSERT INTO `faq_kb_keywords` VALUES (44, 28);
INSERT INTO `faq_kb_keywords` VALUES (45, 30);
INSERT INTO `faq_kb_keywords` VALUES (46, 30);
INSERT INTO `faq_kb_keywords` VALUES (47, 30);
INSERT INTO `faq_kb_keywords` VALUES (48, 5);
INSERT INTO `faq_kb_keywords` VALUES (49, 5);
INSERT INTO `faq_kb_keywords` VALUES (1, 5);
INSERT INTO `faq_kb_keywords` VALUES (10, 32);
INSERT INTO `faq_kb_keywords` VALUES (2, 33);

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `faq_kb_os`
-- 

DROP TABLE IF EXISTS `faq_kb_os`;
CREATE TABLE `faq_kb_os` (
  `articlenr` int(10) unsigned NOT NULL default '0',
  `osnr` int(10) unsigned NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Daten für Tabelle `faq_kb_os`
-- 

INSERT INTO `faq_kb_os` VALUES (1, 2);
INSERT INTO `faq_kb_os` VALUES (1, 1);
INSERT INTO `faq_kb_os` VALUES (2, 2);
INSERT INTO `faq_kb_os` VALUES (2, 1);

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `faq_kb_prog_version`
-- 

DROP TABLE IF EXISTS `faq_kb_prog_version`;
CREATE TABLE `faq_kb_prog_version` (
  `articlenr` int(10) unsigned NOT NULL default '0',
  `progversion` int(10) unsigned NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Daten für Tabelle `faq_kb_prog_version`
-- 

INSERT INTO `faq_kb_prog_version` VALUES (1, 1);
INSERT INTO `faq_kb_prog_version` VALUES (1, 2);

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `faq_kb_ratings`
-- 

DROP TABLE IF EXISTS `faq_kb_ratings`;
CREATE TABLE `faq_kb_ratings` (
  `entrynr` int(10) unsigned NOT NULL auto_increment,
  `rating` int(10) unsigned NOT NULL default '0',
  `articlenr` int(10) unsigned NOT NULL default '0',
  `comment` tinytext NOT NULL,
  PRIMARY KEY  (`entrynr`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Daten für Tabelle `faq_kb_ratings`
-- 

INSERT INTO `faq_kb_ratings` VALUES (1, 3, 17, 'aaa');
INSERT INTO `faq_kb_ratings` VALUES (2, 3, 17, 'aaa');
INSERT INTO `faq_kb_ratings` VALUES (6, 3, 44, '&#22914;&#26524;&#24744;&#30340;&#39046;&#22495;&#23384;&#22312;&#20110;&#25105; <test> ""');
INSERT INTO `faq_kb_ratings` VALUES (4, 3, 17, 'aaa');
INSERT INTO `faq_kb_ratings` VALUES (5, 3, 17, 'aaa');
INSERT INTO `faq_kb_ratings` VALUES (7, 3, 44, '&#22914;&#26524;&#24744;&#30340;&#39046;&#22495;&#23384;&#22312;&#20110;&#25105; <test> ""');

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `faq_kb_subcat`
-- 

DROP TABLE IF EXISTS `faq_kb_subcat`;
CREATE TABLE `faq_kb_subcat` (
  `catnr` int(10) unsigned NOT NULL auto_increment,
  `catname` varchar(240) NOT NULL default '',
  `heading` varchar(250) NOT NULL default '',
  `category` int(10) unsigned NOT NULL default '0',
  `displaypos` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`catnr`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Daten für Tabelle `faq_kb_subcat`
-- 

INSERT INTO `faq_kb_subcat` VALUES (4, 'kbsubcat1', 'KB subcategory 1', 7, 1);
INSERT INTO `faq_kb_subcat` VALUES (3, '&#22914;&#26524;&#24744;&#30340;&#39046;&#22495;&#23384;&#22312;&#20110;&#25105; <test> ""', '&#22914;&#26524;&#24744;&#30340;&#39046;&#22495;&#23384;&#22312;&#20110;&#25105; <test> \\"\\"', 5, 1);
INSERT INTO `faq_kb_subcat` VALUES (5, 'kbsubcat2', 'KB subcategory 2', 7, 2);

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `faq_keywords`
-- 

DROP TABLE IF EXISTS `faq_keywords`;
CREATE TABLE `faq_keywords` (
  `keywordnr` int(10) unsigned NOT NULL auto_increment,
  `keyword` varchar(250) NOT NULL default '',
  PRIMARY KEY  (`keywordnr`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Daten für Tabelle `faq_keywords`
-- 

INSERT INTO `faq_keywords` VALUES (29, 'cc');
INSERT INTO `faq_keywords` VALUES (15, 'aaa');
INSERT INTO `faq_keywords` VALUES (5, 'aa');
INSERT INTO `faq_keywords` VALUES (13, '?');
INSERT INTO `faq_keywords` VALUES (17, 'sadsdadas');
INSERT INTO `faq_keywords` VALUES (23, 'cx<xc<x<');
INSERT INTO `faq_keywords` VALUES (24, 'dsad');
INSERT INTO `faq_keywords` VALUES (27, 'dsasdadsa');
INSERT INTO `faq_keywords` VALUES (28, 'aad');
INSERT INTO `faq_keywords` VALUES (30, 'd');
INSERT INTO `faq_keywords` VALUES (32, 'sdds');
INSERT INTO `faq_keywords` VALUES (33, 'qaa');

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `faq_layout`
-- 

DROP TABLE IF EXISTS `faq_layout`;
CREATE TABLE `faq_layout` (
  `layoutnr` tinyint(3) unsigned NOT NULL auto_increment,
  `headingbg` varchar(8) NOT NULL default '',
  `bgcolor1` varchar(8) NOT NULL default '',
  `bgcolor2` varchar(8) NOT NULL default '',
  `pagebg` varchar(8) NOT NULL default '',
  `tablewidth` varchar(10) NOT NULL default '',
  `fontface` varchar(80) NOT NULL default '',
  `fontsize1` varchar(10) NOT NULL default '',
  `fontsize2` varchar(10) NOT NULL default '',
  `fontsize3` varchar(10) NOT NULL default '',
  `fontcolor` varchar(8) NOT NULL default '',
  `fontsize4` varchar(10) NOT NULL default '',
  `bgcolor3` varchar(8) NOT NULL default '',
  `stylesheet` varchar(80) NOT NULL default '',
  `headingfontcolor` varchar(8) NOT NULL default '',
  `subheadingfontcolor` varchar(8) NOT NULL default '',
  `linkcolor` varchar(8) NOT NULL default '',
  `vlinkcolor` varchar(8) NOT NULL default '',
  `alinkcolor` varchar(8) NOT NULL default '',
  `groupfontcolor` varchar(8) NOT NULL default '',
  `tabledescfontcolor` varchar(8) NOT NULL default '',
  `fontsize5` varchar(10) NOT NULL default '',
  `dateformat` varchar(10) NOT NULL default '',
  `newtime` int(4) unsigned NOT NULL default '0',
  `newpic` varchar(80) NOT NULL default '',
  `searchpic` varchar(80) NOT NULL default '',
  `printpic` varchar(80) NOT NULL default '',
  `backpic` varchar(80) NOT NULL default '',
  `listpic` varchar(80) NOT NULL default '',
  `pageheader` text NOT NULL,
  `pagefooter` text NOT NULL,
  `usecustomheader` tinyint(1) unsigned NOT NULL default '1',
  `usecustomfooter` tinyint(1) unsigned NOT NULL default '1',
  `emailpic` varchar(80) NOT NULL default '',
  `questionpic` varchar(80) NOT NULL default '',
  `usercommentpic` varchar(80) NOT NULL default '',
  `allowlists` tinyint(1) unsigned NOT NULL default '1',
  `allowsearch` tinyint(1) unsigned NOT NULL default '1',
  `searchcomments` tinyint(1) unsigned NOT NULL default '1',
  `searchquestions` tinyint(1) unsigned NOT NULL default '1',
  `showsummary` tinyint(1) unsigned NOT NULL default '1',
  `summarylength` tinyint(2) unsigned NOT NULL default '40',
  `progrestrict` tinyint(1) unsigned NOT NULL default '1',
  `footerfile` varchar(240) NOT NULL default '',
  `headerfile` varchar(240) NOT NULL default '',
  `printheader` tinyint(1) unsigned NOT NULL default '0',
  `printfooter` tinyint(1) unsigned NOT NULL default '0',
  `mincommentlength` int(3) unsigned NOT NULL default '0',
  `minquestionlength` int(3) unsigned NOT NULL default '0',
  `proginfopic` varchar(80) NOT NULL default '',
  `proginfowidth` int(4) unsigned NOT NULL default '0',
  `proginfoheight` int(4) unsigned NOT NULL default '0',
  `textareawidth` int(4) unsigned NOT NULL default '0',
  `textareaheight` int(4) unsigned NOT NULL default '0',
  `proginfoleft` int(4) unsigned NOT NULL default '0',
  `proginfotop` int(4) unsigned NOT NULL default '0',
  `helpwindowwidth` int(4) unsigned NOT NULL default '0',
  `helpwindowheight` int(4) unsigned NOT NULL default '0',
  `helpwindowleft` int(4) unsigned NOT NULL default '0',
  `helpwindowtop` int(4) unsigned NOT NULL default '0',
  `helppic` varchar(80) NOT NULL default '',
  `closepic` varchar(80) NOT NULL default '',
  `kbmode` varchar(20) NOT NULL default 'wizard',
  `defsearchmethod` tinyint(1) unsigned NOT NULL default '0',
  `enablekeywordsearch` tinyint(1) unsigned NOT NULL default '1',
  `enablelanguageselector` tinyint(1) unsigned NOT NULL default '0',
  `faqsortmethod` tinyint(1) unsigned NOT NULL default '0',
  `kbsortmethod` tinyint(1) unsigned NOT NULL default '0',
  `copyrightpos` tinyint(1) unsigned NOT NULL default '0',
  `copyrightbgcolor` varchar(8) NOT NULL default '',
  `ascheader` text NOT NULL,
  `subheadingbgcolor` varchar(8) NOT NULL default '',
  `actionbgcolor` varchar(8) NOT NULL default '',
  `headerfilepos` tinyint(1) unsigned NOT NULL default '0',
  `footerfilepos` tinyint(1) unsigned NOT NULL default '0',
  `newinfobgcolor` varchar(8) NOT NULL default '',
  `useascheader` tinyint(1) unsigned NOT NULL default '0',
  `asclinelength` int(4) unsigned NOT NULL default '0',
  `ascforcewrap` tinyint(1) unsigned NOT NULL default '0',
  `addbodytags` varchar(240) NOT NULL default '',
  `asclistmimetype` tinyint(1) unsigned NOT NULL default '0',
  `asclistcharset` varchar(80) NOT NULL default 'iso-8859-1',
  `keywordsearchmode` tinyint(1) unsigned NOT NULL default '0',
  `questionrequireos` tinyint(1) unsigned NOT NULL default '1',
  `questionrequireversion` tinyint(1) unsigned NOT NULL default '0',
  `newfaqdisplaymethod` tinyint(1) unsigned NOT NULL default '0',
  `enablefaqnewdisplay` tinyint(1) unsigned NOT NULL default '0',
  `faqnewdisplaybgcolor` varchar(8) NOT NULL default '',
  `faqnewdisplayfontcolor` varchar(8) NOT NULL default '',
  `listallfaqmethod` tinyint(1) unsigned NOT NULL default '0',
  `enableshortcutbar` tinyint(1) unsigned NOT NULL default '0',
  `enablejumpboxes` tinyint(1) unsigned NOT NULL default '0',
  `subcatbgcolor` varchar(8) NOT NULL default '',
  `subcatfontcolor` varchar(8) NOT NULL default '',
  `displayrelated` tinyint(1) unsigned NOT NULL default '0',
  `htmllisttype` int(2) unsigned NOT NULL default '0',
  `pagetoppic` varchar(80) NOT NULL default 'gfx/top.gif',
  `attachpic` varchar(80) NOT NULL default 'gfx/attach.gif',
  `summaryintotallist` tinyint(1) unsigned NOT NULL default '0',
  `summarychars` tinyint(2) unsigned NOT NULL default '40',
  `maxentries` int(10) unsigned NOT NULL default '0',
  `activcellcolor` varchar(8) NOT NULL default '#ffff72',
  `ratingspublic` tinyint(1) unsigned NOT NULL default '0',
  `ratingcommentpublic` tinyint(1) unsigned NOT NULL default '0',
  `hovercells` tinyint(1) unsigned NOT NULL default '0',
  `qesautopub` tinyint(1) unsigned NOT NULL default '0',
  `ns4style` varchar(240) NOT NULL default 'faq_ns4.css',
  `ns6style` varchar(240) NOT NULL default 'faq_ns6.css',
  `operastyle` varchar(240) NOT NULL default 'faq_opera.css',
  `geckostyle` varchar(240) NOT NULL default 'faq_gecko.css',
  `konquerorstyle` varchar(240) NOT NULL default 'faq_konqueror.css',
  `ascheaderfile` varchar(240) NOT NULL default '',
  `ascheaderfilepos` tinyint(1) unsigned NOT NULL default '0',
  `numlatest` tinyint(4) unsigned NOT NULL default '10',
  `colorscrollbars` tinyint(1) unsigned NOT NULL default '1',
  `sbfacecolor` varchar(7) NOT NULL default '#94AAD6',
  `sbhighlightcolor` varchar(7) NOT NULL default '#AFEEEE',
  `sbshadowcolor` varchar(7) NOT NULL default '#ADD8E6',
  `sb3dlightcolor` varchar(7) NOT NULL default '#1E90FF',
  `sbarrowcolor` varchar(7) NOT NULL default '#0000ff',
  `sbtrackcolor` varchar(7) NOT NULL default '#E0FFFF',
  `sbdarkshadowcolor` varchar(7) NOT NULL default '#4682B4',
  `pagebgpic` varchar(240) NOT NULL default '',
  `tabledescfontsize` varchar(20) NOT NULL default '10pt',
  `langselectfontsize` varchar(20) NOT NULL default '10pt',
  `faqnewfontsize` varchar(20) NOT NULL default '10pt',
  `jumpboxfontsize` varchar(20) NOT NULL default '10pt',
  `actionlinefontsize` varchar(20) NOT NULL default '9pt',
  `newinfofontsize` varchar(20) NOT NULL default '9pt',
  `newinfofontcolor` varchar(7) NOT NULL default '#000000',
  `shortbarfontsize` varchar(20) NOT NULL default '9pt',
  `jumpboxsorting` tinyint(4) unsigned NOT NULL default '0',
  `disableasclist` tinyint(1) unsigned NOT NULL default '0',
  `disablehtmlemail` tinyint(1) unsigned NOT NULL default '1',
  `contentcopy` varchar(250) NOT NULL default '',
  `pagebgattach` varchar(80) NOT NULL default 'scroll',
  `pagebgrepeat` varchar(80) NOT NULL default 'repeat',
  `pagebgposition` varchar(80) NOT NULL default 'top',
  `subcatfontsize` varchar(10) NOT NULL default '12pt',
  `questionshorting` int(10) unsigned NOT NULL default '20',
  `defmailsig` text NOT NULL,
  `cc_font` varchar(80) NOT NULL default '"Times New Roman", Times, serif',
  `cc_fontsize` varchar(20) NOT NULL default '12pt',
  `cc_fontcolor` varchar(7) NOT NULL default '',
  `subscriptionpic` varchar(240) NOT NULL default 'gfx/subscribe.gif',
  `irow_bgcolor` varchar(7) NOT NULL default '#ADD8E6',
  `irow_fontcolor` varchar(7) NOT NULL default '#808100',
  `irow_fontsize` varchar(20) NOT NULL default '12pt',
  `pagenavfontcolor` varchar(7) NOT NULL default '#000000',
  `pagenavfontsize` varchar(20) NOT NULL default '9pt',
  `subcatfontstyle` tinyint(2) unsigned NOT NULL default '1',
  `linkoptions` tinyint(4) unsigned NOT NULL default '0',
  `listoptions` tinyint(4) unsigned NOT NULL default '0',
  `clist_linkcolor` varchar(7) NOT NULL default '#000000',
  `clist_vlinkcolor` varchar(7) NOT NULL default '#000000',
  `clist_alinkcolor` varchar(7) NOT NULL default '#000000',
  `shownextprev` tinyint(1) unsigned NOT NULL default '0',
  `nextprevmode` tinyint(1) unsigned NOT NULL default '0',
  `kbsearchoptions` int(10) unsigned NOT NULL default '0',
  `searchoptions` int(4) unsigned NOT NULL default '0',
  `displayoptions` int(4) unsigned NOT NULL default '0',
  `nextpagepic` varchar(80) NOT NULL default 'gfx/fwd.gif',
  `prevpagepic` varchar(80) NOT NULL default 'gfx/prev.gif',
  `firstpagepic` varchar(80) NOT NULL default 'gfx/first.gif',
  `lastpagepic` varchar(80) NOT NULL default 'gfx/last.gif',
  `usepagenavicons` tinyint(1) unsigned NOT NULL default '1',
  `displayvotesinline` tinyint(1) unsigned NOT NULL default '0',
  `tablealign` int(4) unsigned NOT NULL default '2',
  `votesinlinedisplaymode` tinyint(1) unsigned NOT NULL default '0',
  `navbarwidth` int(10) unsigned NOT NULL default '250',
  `navsync` tinyint(1) unsigned NOT NULL default '1',
  `navpic_progclosed` varchar(80) NOT NULL default 'gfx/book_closed.gif',
  `navpic_progopen` varchar(80) NOT NULL default 'gfx/book_open.gif',
  `navpic_proglocked` varchar(80) NOT NULL default 'gfx/book_locked.gif',
  `navpic_faq` varchar(80) NOT NULL default 'gfx/document.gif',
  `navpic_question` varchar(80) NOT NULL default 'gfx/question2.gif',
  `navpic_catclosed` varchar(80) NOT NULL default 'gfx/cat_closed.gif',
  `navpic_catopen` varchar(80) NOT NULL default 'gfx/cat_open.gif',
  `navpic_catlocked` varchar(80) NOT NULL default 'gfx/cat_locked.gif',
  `navpic_subcatopen` varchar(80) NOT NULL default 'gfx/subcat_open.gif',
  `navpic_subcatclosed` varchar(80) NOT NULL default 'gfx/subcat_closed.gif',
  `navpic_subcatlocked` varchar(80) NOT NULL default 'gfx/subcat_locked.gif',
  `searchhighlightcolor` varchar(7) NOT NULL default '#ff0000',
  `searchhighlight` tinyint(1) unsigned NOT NULL default '0',
  `navpic_kbarticle` varchar(80) NOT NULL default 'gfx/document.gif',
  `navpic_kbwizard` varchar(80) NOT NULL default 'gfx/wizard.gif',
  `kbnavoptions` int(10) unsigned NOT NULL default '0',
  `navpic_kbsearch` varchar(80) NOT NULL default 'gfx/search.gif',
  `navtreepos` tinyint(1) unsigned NOT NULL default '0',
  `search_inputfieldwidth` int(4) unsigned NOT NULL default '60',
  `faqnavoptions` int(10) unsigned NOT NULL default '0',
  `id` varchar(10) NOT NULL default '',
  `deflayout` tinyint(1) unsigned NOT NULL default '0',
  `tablespacing` int(4) unsigned NOT NULL default '1',
  `tablepadding` int(4) unsigned NOT NULL default '1',
  `extdateformat` varchar(20) NOT NULL default 'Y-m-d H:i:s',
  `srchtoolpic` varchar(80) NOT NULL default 'gfx/srchtool.gif',
  `donltrans` tinyint(4) unsigned NOT NULL default '0',
  `displayattachinfo` tinyint(1) unsigned NOT NULL default '1',
  PRIMARY KEY  (`layoutnr`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Daten für Tabelle `faq_layout`
-- 

INSERT INTO `faq_layout` VALUES (1, '#94AAD6', '#000000', '#CCCCCC', '#C0C0C0', '50%', 'Verdana, Geneva, Arial, Helvetica, sans-serif', '10pt', '12pt', '14pt', '#000000', '8pt', '#C0C0C0', 'faq.css', '#FFF0C0', '#F0F0F0', '#CC0000', '#CC0000', '#0000CC', '#2C2C2C', '#2C2C2C', '12pt', 'd.m.Y', 90, 'gfx/new.gif', 'gfx/search.gif', 'gfx/print.gif', 'gfx/back.gif', 'gfx/list.gif', '', '', 0, 0, 'gfx/email.gif', 'gfx/question.gif', 'gfx/comment.gif', 1, 1, 1, 1, 1, 40, 1, '', '', 0, 0, 0, 0, 'gfx/info.gif', 380, 420, 50, 20, 20, 20, 380, 420, 20, 20, 'gfx/help.gif', 'gfx/close.gif', 'proglist', 0, 1, 1, 1, 0, 0, '#C0C0C0', '', '#94AAD6', '#94AAD6', 0, 0, '#94AAD6', 0, 0, 0, '', 0, 'iso-8859-1', 0, 1, 1, 1, 0, '#94AAD6', '#000000', 0, 1, 1, '#e0e0e0', '#000000', 1, 0, 'gfx/top.gif', 'gfx/attach.gif', 0, 40, 10, '#ffff72', 1, 1, 1, 0, 'faq_ns4.css', 'faq_ns6.css', 'faq_opera.css', 'faq_gecko.css', 'faq_konqueror.css', '', 0, 20, 1, '#94AAD6', '#AFEEEE', '#ADD8E6', '#1E90FF', '#0000ff', '#E0FFFF', '#4682B4', '', '10pt', '10pt', '10pt', '10pt', '9pt', '9pt', '#000000', '9pt', 0, 0, 0, '&copy;2002-2005 B&ouml;sch EDV-Consulting', 'scroll', 'repeat', 'top', '12pt', 20, 'Sig Zeile 1\r\nZeile2\r\nZeil3', '"Times New Roman", Times, serif', '12pt', '', 'gfx/subscribe.gif', '#ADD8E6', '#808100', '12pt', '#000000', '9pt', 3, 3, 7, '#000000', '#000000', '#000000', 1, 1, 3, 7, 1, 'gfx/fwd.gif', 'gfx/prev.gif', 'gfx/first.gif', 'gfx/last.gif', 1, 1, 0, 1, 250, 1, 'gfx/book_closed.gif', 'gfx/book_open.gif', 'gfx/book_locked.gif', 'gfx/document.gif', 'gfx/question2.gif', 'gfx/cat_closed.gif', 'gfx/cat_open.gif', 'gfx/cat_locked.gif', 'gfx/subcat_open.gif', 'gfx/subcat_closed.gif', 'gfx/subcat_locked.gif', '#ff0000', 1, 'gfx/document.gif', 'gfx/wizard.gif', 7, 'gfx/search.gif', 0, 40, 1, 'def', 1, 1, 5, 'd.m.Y H:i:s', 'gfx/srchtool.gif', 3, 1);

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `faq_leachers`
-- 

DROP TABLE IF EXISTS `faq_leachers`;
CREATE TABLE `faq_leachers` (
  `entrynr` int(10) unsigned NOT NULL auto_increment,
  `useragent` varchar(80) NOT NULL default '',
  `description` text,
  PRIMARY KEY  (`entrynr`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Daten für Tabelle `faq_leachers`
-- 

INSERT INTO `faq_leachers` VALUES (2, 'saassa', 'sasaas');

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `faq_mimetypes`
-- 

DROP TABLE IF EXISTS `faq_mimetypes`;
CREATE TABLE `faq_mimetypes` (
  `entrynr` int(10) unsigned NOT NULL auto_increment,
  `mimetype` varchar(240) NOT NULL default '',
  `icon` varchar(240) NOT NULL default '',
  PRIMARY KEY  (`entrynr`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Daten für Tabelle `faq_mimetypes`
-- 

INSERT INTO `faq_mimetypes` VALUES (1, 'application/x-gzip-compressed', '');
INSERT INTO `faq_mimetypes` VALUES (2, 'application/x-zip-compressed', 'fileicons/zip.gif');
INSERT INTO `faq_mimetypes` VALUES (3, 'application/x-tar', '');
INSERT INTO `faq_mimetypes` VALUES (4, 'text/html', '');
INSERT INTO `faq_mimetypes` VALUES (5, 'text/plain', '');
INSERT INTO `faq_mimetypes` VALUES (6, 'image/bmp', '');
INSERT INTO `faq_mimetypes` VALUES (7, 'image/gif', '');
INSERT INTO `faq_mimetypes` VALUES (8, 'image/jpeg', '');
INSERT INTO `faq_mimetypes` VALUES (9, 'application/x-shockwave-flash', '');
INSERT INTO `faq_mimetypes` VALUES (10, 'application/msword', '');
INSERT INTO `faq_mimetypes` VALUES (11, 'application/vnd.ms-excel', '');
INSERT INTO `faq_mimetypes` VALUES (12, 'application/pdf', '');
INSERT INTO `faq_mimetypes` VALUES (13, 'audio/aiff', '');
INSERT INTO `faq_mimetypes` VALUES (14, 'application/arj', 'back.gif');
INSERT INTO `faq_mimetypes` VALUES (15, 'video/x-ms-asf', '');
INSERT INTO `faq_mimetypes` VALUES (16, 'audio/basic', '');
INSERT INTO `faq_mimetypes` VALUES (18, 'application/x-bzip', '');
INSERT INTO `faq_mimetypes` VALUES (19, 'application/x-bzip2', '');
INSERT INTO `faq_mimetypes` VALUES (20, 'application/x-dvi', '');
INSERT INTO `faq_mimetypes` VALUES (21, 'application/x-helpfile', '');
INSERT INTO `faq_mimetypes` VALUES (22, 'application/mac-binhex', '');
INSERT INTO `faq_mimetypes` VALUES (23, 'video/quicktime', '');
INSERT INTO `faq_mimetypes` VALUES (24, 'audio/mpeg3', '');
INSERT INTO `faq_mimetypes` VALUES (25, 'video/mpeg', '');
INSERT INTO `faq_mimetypes` VALUES (26, 'application/vnd.ms-project', '');
INSERT INTO `faq_mimetypes` VALUES (27, 'image/png', '');
INSERT INTO `faq_mimetypes` VALUES (28, 'application/vnd.ms-powerpoint', '');
INSERT INTO `faq_mimetypes` VALUES (29, 'application/postscript', '');
INSERT INTO `faq_mimetypes` VALUES (30, 'application/rtf', '');
INSERT INTO `faq_mimetypes` VALUES (31, 'application/sea', '');
INSERT INTO `faq_mimetypes` VALUES (32, 'application/x-tex', '');
INSERT INTO `faq_mimetypes` VALUES (33, 'application/x-texinfo', '');
INSERT INTO `faq_mimetypes` VALUES (34, 'image/tiff', '');
INSERT INTO `faq_mimetypes` VALUES (35, 'windows/metafile', '');

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `faq_misc`
-- 

DROP TABLE IF EXISTS `faq_misc`;
CREATE TABLE `faq_misc` (
  `shutdown` tinyint(3) unsigned NOT NULL default '0',
  `shutdowntext` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Daten für Tabelle `faq_misc`
-- 

INSERT INTO `faq_misc` VALUES (0, 'Aktuell nicht verf&uuml;gbar.\r<BR>\r<BR>&lt;!-- SPCode ulist Start --&gt;&lt;UL&gt;&lt;!-- SPCode --&gt;&lt;LI&gt;Temporary offline&lt;/UL&gt;&lt;!-- SPCode ulist End --&gt;\r<BR>');

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `faq_os`
-- 

DROP TABLE IF EXISTS `faq_os`;
CREATE TABLE `faq_os` (
  `osnr` int(10) unsigned NOT NULL auto_increment,
  `osname` varchar(180) NOT NULL default '',
  PRIMARY KEY  (`osnr`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Daten für Tabelle `faq_os`
-- 

INSERT INTO `faq_os` VALUES (1, 'Win95');
INSERT INTO `faq_os` VALUES (2, 'Win2k');

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `faq_prog_dirs`
-- 

DROP TABLE IF EXISTS `faq_prog_dirs`;
CREATE TABLE `faq_prog_dirs` (
  `prognr` int(10) unsigned NOT NULL default '0',
  `dirnr` int(10) unsigned NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Daten für Tabelle `faq_prog_dirs`
-- 


-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `faq_prog_os`
-- 

DROP TABLE IF EXISTS `faq_prog_os`;
CREATE TABLE `faq_prog_os` (
  `osnr` int(10) unsigned NOT NULL default '0',
  `prognr` int(10) unsigned NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Daten für Tabelle `faq_prog_os`
-- 

INSERT INTO `faq_prog_os` VALUES (0, 10);
INSERT INTO `faq_prog_os` VALUES (1, 18);
INSERT INTO `faq_prog_os` VALUES (2, 18);
INSERT INTO `faq_prog_os` VALUES (1, 17);
INSERT INTO `faq_prog_os` VALUES (2, 17);

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `faq_programm`
-- 

DROP TABLE IF EXISTS `faq_programm`;
CREATE TABLE `faq_programm` (
  `prognr` int(10) unsigned NOT NULL auto_increment,
  `programmname` varchar(240) NOT NULL default '',
  `numcats` int(10) unsigned default '0',
  `progid` varchar(10) NOT NULL default '',
  `language` varchar(5) NOT NULL default 'de',
  `newsgroup` varchar(250) NOT NULL default '',
  `newssubject` varchar(80) default NULL,
  `nntpserver` varchar(80) default NULL,
  `newsdomain` varchar(80) default NULL,
  `description` text NOT NULL,
  `displaypos` int(10) unsigned NOT NULL default '0',
  `lastmailed` date NOT NULL default '0000-00-00',
  `htmlmailtype` tinyint(1) unsigned NOT NULL default '0',
  `subscriptionavail` tinyint(1) unsigned NOT NULL default '1',
  PRIMARY KEY  (`prognr`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Daten für Tabelle `faq_programm`
-- 

INSERT INTO `faq_programm` VALUES (10, 'Bl', 1, 'blaeh', 'de', '', '', '', '', '', 5, '0000-00-00', 0, 1);
INSERT INTO `faq_programm` VALUES (11, 'Test 2', 3, 'tst2', 'en', '', '', '', '', 'This is the program description', 1, '2004-01-19', 1, 1);
INSERT INTO `faq_programm` VALUES (12, 'neu', 0, 'neu', 'de', '', '', '', '', 'aaaa', 4, '0000-00-00', 0, 1);
INSERT INTO `faq_programm` VALUES (13, 'Test', 0, 'tst', 'en', '', '', '', '', '', 2, '0000-00-00', 0, 1);
INSERT INTO `faq_programm` VALUES (14, 'aaaa', 0, 'aaa', 'de', '', '', '', '', 'aaaaaaaaaaaa', 6, '0000-00-00', 0, 1);
INSERT INTO `faq_programm` VALUES (16, 'Neu', 0, 'new', 'de', '', '', '', '', '', 8, '0000-00-00', 0, 1);
INSERT INTO `faq_programm` VALUES (17, 'FAQEngine', 1, 'faqe', 'de', '', '', '', '', '', 9, '0000-00-00', 0, 1);
INSERT INTO `faq_programm` VALUES (18, 'FAQEngine', 1, 'faqe', 'en', '', '', '', '', '', 3, '0000-00-00', 0, 1);

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `faq_programm_admins`
-- 

DROP TABLE IF EXISTS `faq_programm_admins`;
CREATE TABLE `faq_programm_admins` (
  `prognr` int(10) unsigned NOT NULL default '0',
  `usernr` int(10) unsigned NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Daten für Tabelle `faq_programm_admins`
-- 

INSERT INTO `faq_programm_admins` VALUES (11, 3);

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `faq_programm_version`
-- 

DROP TABLE IF EXISTS `faq_programm_version`;
CREATE TABLE `faq_programm_version` (
  `entrynr` int(10) unsigned NOT NULL auto_increment,
  `programm` int(10) unsigned NOT NULL default '0',
  `version` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`entrynr`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Daten für Tabelle `faq_programm_version`
-- 

INSERT INTO `faq_programm_version` VALUES (1, 10, '1.1');
INSERT INTO `faq_programm_version` VALUES (2, 10, '2.1');
INSERT INTO `faq_programm_version` VALUES (3, 17, '1.11');
INSERT INTO `faq_programm_version` VALUES (4, 17, '1.12');
INSERT INTO `faq_programm_version` VALUES (5, 17, '1.13');
INSERT INTO `faq_programm_version` VALUES (6, 18, '1.11');
INSERT INTO `faq_programm_version` VALUES (7, 18, '1.12');
INSERT INTO `faq_programm_version` VALUES (8, 18, '1.13');

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `faq_questions`
-- 

DROP TABLE IF EXISTS `faq_questions`;
CREATE TABLE `faq_questions` (
  `questionnr` int(10) unsigned NOT NULL auto_increment,
  `prognr` int(10) unsigned NOT NULL default '0',
  `osname` varchar(180) default NULL,
  `versionnr` varchar(10) default NULL,
  `email` varchar(140) NOT NULL default '',
  `question` text NOT NULL,
  `enterdate` datetime NOT NULL default '0000-00-00 00:00:00',
  `faqref` int(10) unsigned default '0',
  `posterip` varchar(20) NOT NULL default '',
  `answerdate` datetime NOT NULL default '0000-00-00 00:00:00',
  `answerauthor` int(10) unsigned NOT NULL default '0',
  `answer` text NOT NULL,
  `language` varchar(5) NOT NULL default 'de',
  `questionref` int(10) unsigned NOT NULL default '0',
  `publish` tinyint(1) unsigned NOT NULL default '0',
  `state` int(2) unsigned NOT NULL default '0',
  PRIMARY KEY  (`questionnr`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Daten für Tabelle `faq_questions`
-- 

INSERT INTO `faq_questions` VALUES (67, 8, 'dsasaddsa', '22', 'test@boesch.lan', 'dsjksdajkladsjkdasjlksadjldas', '2003-12-15 12:47:32', 0, '10.1.1.30', '0000-00-00 00:00:00', 0, '', 'de', 0, 0, 0);
INSERT INTO `faq_questions` VALUES (24, 8, '', '', 'aaa@aaa.com', 'sajlsajlsdaljdas', '2002-02-01 22:22:36', 0, '127.0.0.1', '0000-00-00 00:00:00', 0, '', 'de', 0, 1, 0);
INSERT INTO `faq_questions` VALUES (25, 8, '', '', 'aaa@aaa.com', 'asjasjlads', '2002-02-04 17:32:24', 0, '127.0.0.1', '0000-00-00 00:00:00', 0, '', 'de', 0, 1, 0);
INSERT INTO `faq_questions` VALUES (26, 8, '', '', 'aaa@aaa.com', 'aaa', '2002-02-04 17:33:01', 10, '127.0.0.1', '0000-00-00 00:00:00', 0, '', 'de', 0, 1, 0);
INSERT INTO `faq_questions` VALUES (27, 7, 'Win95', '1.1', 'postmaster@boesch.de', 'Frage', '2002-03-18 22:17:39', 0, '127.0.0.1', '0000-00-00 00:00:00', 0, '', 'de', 0, 0, 0);
INSERT INTO `faq_questions` VALUES (20, 7, 'Win95', '1.01', 'aaa@aaaa.com', 'Fragetext', '2002-01-22 14:17:31', 9, '127.0.0.1', '0000-00-00 00:00:00', 0, '', 'de', 0, 1, 2);
INSERT INTO `faq_questions` VALUES (21, 7, 'Win95', '1.02', 'aaa@aaa.com', 'noch ne frage', '2002-01-22 14:20:35', 9, '127.0.0.1', '2002-01-27 00:04:24', 1, '> noch ne frage\r\naaa', 'de', 0, 1, 0);
INSERT INTO `faq_questions` VALUES (39, 13, 'aaa', '1.1', 'a@a.com', 'aaa', '2002-06-25 16:30:26', 55, '127.0.0.1', '0000-00-00 00:00:00', 0, '', 'en', 0, 1, 0);
INSERT INTO `faq_questions` VALUES (28, 7, 'Win95', '1.1', 'postmaster@boesch.de', 'dsjlsadljdsaljdsajlsda', '2002-03-18 22:23:27', 0, '127.0.0.1', '0000-00-00 00:00:00', 0, '', 'de', 0, 0, 0);
INSERT INTO `faq_questions` VALUES (29, 7, 'Win95', '1.1', 'postmaster@boesch.de', 'dsjlsadljdsaljdsajlsda', '2002-03-18 22:24:16', 0, '127.0.0.1', '0000-00-00 00:00:00', 0, '', 'de', 0, 0, 0);
INSERT INTO `faq_questions` VALUES (34, 7, 'Win95', '1.1', 'test@boesch.de', 'dsjdasjlkads', '2002-05-22 00:05:54', 9, '127.0.0.1', '0000-00-00 00:00:00', 0, '', 'de', 0, 0, 0);
INSERT INTO `faq_questions` VALUES (35, 7, 'Win95', '1.1', 'test@boesch.de', 'dsjdasjlkads', '2002-05-22 00:07:26', 9, '127.0.0.1', '0000-00-00 00:00:00', 0, '', 'de', 0, 0, 0);
INSERT INTO `faq_questions` VALUES (36, 7, 'aaa', '1.1', 'test@boesch.de', '?dsjdsajlksdajldasjl', '2002-06-01 22:22:28', 0, '10.1.1.30', '0000-00-00 00:00:00', 0, '', 'de', 0, 0, 0);
INSERT INTO `faq_questions` VALUES (37, 7, 'aaa', '1.1', 'test@boesch.de', '?dsjdsajlksdajldasjl', '2002-06-01 22:24:09', 0, '10.1.1.30', '2002-06-03 21:49:19', 5, '> ?dsjdsajlksdajldasjl\r\naaaaaaaa', 'de', 0, 0, 0);
INSERT INTO `faq_questions` VALUES (38, 7, 'aaa', '1.1', 'info@boesch.de', 'dadsadsadsa', '2002-06-01 22:26:00', 0, '10.1.1.30', '0000-00-00 00:00:00', 0, '', 'de', 0, 0, 0);
INSERT INTO `faq_questions` VALUES (71, 7, 'Win95', '1.1', 'test@boesch.lan', 'dsadasdsadasdasdasdsa', '2003-12-15 13:00:29', 0, '10.1.1.30', '0000-00-00 00:00:00', 0, '', 'de', 0, 0, 0);
INSERT INTO `faq_questions` VALUES (70, 8, 'dsasaddsa', '22', 'test@boesch.lan', 'dsjksdajkladsjkdasjlksadjldas', '2003-12-15 12:52:43', 0, '10.1.1.30', '0000-00-00 00:00:00', 0, '', 'de', 0, 0, 0);
INSERT INTO `faq_questions` VALUES (69, 8, 'dsasaddsa', '22', 'test@boesch.lan', 'dsjksdajkladsjkdasjlksadjldas', '2003-12-15 12:50:48', 0, '10.1.1.30', '0000-00-00 00:00:00', 0, '', 'de', 0, 0, 0);
INSERT INTO `faq_questions` VALUES (68, 8, 'dsasaddsa', '22', 'test@boesch.lan', 'dsjksdajkladsjkdasjlksadjldas', '2003-12-15 12:50:23', 0, '10.1.1.30', '0000-00-00 00:00:00', 0, '', 'de', 0, 0, 0);
INSERT INTO `faq_questions` VALUES (48, 21, 'dsadsa', '11', 'test@boesch.lan', '&#22914;&#26524;&#24744;&#30340;&#39046;&#22495;&#23384;&#22312;&#20110;&#25105;  \\"\\"', '2002-07-20 19:16:32', 0, '127.0.0.1', '2002-07-23 11:10:53', 1, '> &#22914;&#26524;&#24744;&#30340;&#39046;&#22495;&#23384;&#22312;&#20110;&#25105;  ""\r\n&#22914;&#26524;&#24744;&#30340;&#39046;&#22495;&#23384;&#22312;&#20110;&#25105;  ""', 'de', 0, 1, 0);
INSERT INTO `faq_questions` VALUES (49, 21, '&#22914;&#26524;&#24744;&#30340;&#39046;&#22495;&#23384;&#22312;&#20110;&#25105; <test this> ""', '111', 'test@boesch.lan', '&#22914;&#26524;&#24744;&#30340;&#39046;&#22495;&#23384;&#22312;&#20110;&#25105;  \\"\\"', '2002-07-20 19:33:32', 0, '127.0.0.1', '2002-07-23 11:01:04', 1, '> &#22914;&#26524;&#24744;&#30340;&#39046;&#22495;&#23384;&#22312;&#20110;&#25105;  ""\r\n&#22914;&#26524;&#24744;&#30340;&#39046;&#22495;&#23384;&#22312;&#20110;&#25105;  ""\r\n', 'de', 0, 1, 0);
INSERT INTO `faq_questions` VALUES (50, 21, '&#22914;&#26524;&#24744;&#30340;&#39046;&#22495;&#23384;&#22312;&#20110;&#25105; <test this> ""', 'aa', 'test@boesch.lan', '&#22914;&#26524;&#24744;&#30340;&#39046;&#22495;&#23384;&#22312;&#20110;&#25105;  \\"\\"', '2002-07-20 19:34:47', 0, '127.0.0.1', '2002-07-23 10:44:08', 1, '> &#22914;&#26524;&#24744;&#30340;&#39046;&#22495;&#23384;&#22312;&#20110;&#25105;  ""\r\n&#22914;&#26524;&#24744;&#30340;&#39046;&#22495;&#23384;&#22312;&#20110;&#25105;  ""', 'de', 0, 0, 0);
INSERT INTO `faq_questions` VALUES (51, 21, '&#22914;&#26524;&#24744;&#30340;&#39046;&#22495;&#23384;&#22312;&#20110;&#25105; <test this> ""', '11', 'test@boesch.lan', '&#22914;&#26524;&#24744;&#30340;&#39046;&#22495;&#23384;&#22312;&#20110;&#25105;  \\"\\"', '2002-07-20 19:46:01', 68, '127.0.0.1', '2002-07-23 11:14:17', 1, '> &#22914;&#26524;&#24744;&#30340;&#39046;&#22495;&#23384;&#22312;&#20110;&#25105;  ""\r\n&#22914;&#26524;&#24744;&#30340;&#39046;&#22495;&#23384;&#22312;&#20110;&#25105;  ""', 'de', 0, 1, 0);
INSERT INTO `faq_questions` VALUES (52, 21, '&#22914;&#26524;&#24744;&#30340;&#39046;&#22495;&#23384;&#22312;&#20110;&#25105; <test this> ""', '11', 'test@boesch.land', '&#22914;&#26524;&#24744;&#30340;&#39046;&#22495;&#23384;&#22312;&#20110;&#25105;  \\"\\"', '2002-07-20 19:49:18', 68, '127.0.0.1', '2002-07-23 11:12:59', 1, '> &#22914;&#26524;&#24744;&#30340;&#39046;&#22495;&#23384;&#22312;&#20110;&#25105;  ""\r\n&#22914;&#26524;&#24744;&#30340;&#39046;&#22495;&#23384;&#22312;&#20110;&#25105;  ""', 'de', 51, 1, 0);
INSERT INTO `faq_questions` VALUES (66, 8, 'dsasaddsa', '22', 'test@boesch.lan', 'dsjksdajkladsjkdasjlksadjldas', '2003-12-15 12:47:04', 0, '10.1.1.30', '0000-00-00 00:00:00', 0, '', 'de', 0, 0, 0);
INSERT INTO `faq_questions` VALUES (54, 7, 'Win95', '1.1', 'test@boesch.lan', '&#22914;&#26524;&#24744;&#30340;&#39046;&#22495;&#23384;&#22312;&#20110;&#25105;  \\"\\"', '2002-07-22 22:03:16', 0, '10.1.1.30', '0000-00-00 00:00:00', 0, '', 'de', 0, 0, 3);
INSERT INTO `faq_questions` VALUES (55, 7, 'Win95', '1.1', 'test@boesch.lan', '&#22914;&#26524;&#24744;&#30340;&#39046;&#22495;&#23384;&#22312;&#20110;&#25105;  \\"\\"', '2002-07-22 22:03:43', 0, '10.1.1.30', '0000-00-00 00:00:00', 0, '', 'de', 0, 0, 0);
INSERT INTO `faq_questions` VALUES (56, 7, 'Win95', '1.1', 'test@boesch.lan', '&#22914;&#26524;&#24744;&#30340;&#39046;&#22495;&#23384;&#22312;&#20110;&#25105;  \\"\\"', '2002-07-22 22:04:03', 0, '10.1.1.30', '0000-00-00 00:00:00', 0, '', 'de', 0, 0, 0);
INSERT INTO `faq_questions` VALUES (57, 7, 'Win95', '1.1', 'test@boesch.lan', '&#22914;&#26524;&#24744;&#30340;&#39046;&#22495;&#23384;&#22312;&#20110;&#25105;  \\"\\"', '2002-07-22 22:04:20', 0, '10.1.1.30', '0000-00-00 00:00:00', 0, '', 'de', 0, 0, 0);
INSERT INTO `faq_questions` VALUES (58, 7, 'Win95', '1.1', 'test@boesch.lan', '&#22914;&#26524;&#24744;&#30340;&#39046;&#22495;&#23384;&#22312;&#20110;&#25105;  \\"\\"', '2002-07-22 22:04:57', 0, '10.1.1.30', '0000-00-00 00:00:00', 0, '', 'de', 0, 0, 0);
INSERT INTO `faq_questions` VALUES (59, 7, 'Win95', '1.1', 'test@boesch.lan', '&#22914;&#26524;&#24744;&#30340;&#39046;&#22495;&#23384;&#22312;&#20110;&#25105;  \\"\\"', '2002-07-22 22:05:17', 0, '10.1.1.30', '0000-00-00 00:00:00', 0, '', 'de', 0, 0, 0);
INSERT INTO `faq_questions` VALUES (60, 7, 'Win95', '1.1', 'test@boesch.lan', 'a', '2002-07-25 13:14:21', 0, '127.0.0.1', '0000-00-00 00:00:00', 0, '', 'de', 0, 0, 0);
INSERT INTO `faq_questions` VALUES (61, 8, 'Win2k', '22', 'aaa@aaa.com', 'aaa', '2002-08-24 10:37:24', 0, '10.1.1.30', '0000-00-00 00:00:00', 0, '', 'de', 25, 0, 0);
INSERT INTO `faq_questions` VALUES (62, 8, 'Win2k', '22', 'sda@aaa.com', 'daljdasjl', '2002-08-24 10:40:04', 0, '10.1.1.30', '0000-00-00 00:00:00', 0, '', 'de', 25, 0, 0);
INSERT INTO `faq_questions` VALUES (63, 8, 'Win2k', '22', 'test@boesch.lan', 'aaaa', '2002-09-22 19:23:18', 14, '10.1.1.30', '0000-00-00 00:00:00', 0, '', 'de', 0, 1, 0);
INSERT INTO `faq_questions` VALUES (64, 7, 'Win95', '1.1', '', 'aaa', '2002-11-16 11:46:26', 0, '10.1.1.30', '0000-00-00 00:00:00', 0, '', 'de', 0, 0, 0);
INSERT INTO `faq_questions` VALUES (65, 7, 'Win95', '1.1', 'test@boesch.lan', 'dsdasdasdas', '2003-05-21 14:19:02', 88, '127.0.0.1', '0000-00-00 00:00:00', 0, '', 'de', 0, 0, 0);
INSERT INTO `faq_questions` VALUES (72, 10, 'Win95', '11', 'test@boesch.lan', 'dsdsad', '2004-05-18 18:05:43', 0, '127.0.0.1', '0000-00-00 00:00:00', 0, '', 'de', 0, 1, 0);

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `faq_ratings`
-- 

DROP TABLE IF EXISTS `faq_ratings`;
CREATE TABLE `faq_ratings` (
  `entrynr` int(10) unsigned NOT NULL auto_increment,
  `rating` int(10) unsigned NOT NULL default '0',
  `faqnr` int(10) unsigned NOT NULL default '0',
  `comment` tinytext NOT NULL,
  PRIMARY KEY  (`entrynr`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Daten für Tabelle `faq_ratings`
-- 

INSERT INTO `faq_ratings` VALUES (2, 1, 20, 'aaaa');
INSERT INTO `faq_ratings` VALUES (3, 0, 43, 'aaaa');
INSERT INTO `faq_ratings` VALUES (4, 0, 43, 'aaaa');
INSERT INTO `faq_ratings` VALUES (5, 0, 43, 'aaaa');
INSERT INTO `faq_ratings` VALUES (6, 3, 59, 'Grund');
INSERT INTO `faq_ratings` VALUES (7, 3, 68, '&#22914;&#26524;&#24744;&#30340;&#39046;&#22495;&#23384;&#22312;&#20110;&#25105; <test> ""');
INSERT INTO `faq_ratings` VALUES (8, 3, 14, 'reason for rating');

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `faq_related_categories`
-- 

DROP TABLE IF EXISTS `faq_related_categories`;
CREATE TABLE `faq_related_categories` (
  `srccat` int(10) unsigned NOT NULL default '0',
  `destcat` int(10) unsigned NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Daten für Tabelle `faq_related_categories`
-- 

INSERT INTO `faq_related_categories` VALUES (9, 18);
INSERT INTO `faq_related_categories` VALUES (18, 9);

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `faq_related_faq`
-- 

DROP TABLE IF EXISTS `faq_related_faq`;
CREATE TABLE `faq_related_faq` (
  `srcfaq` int(10) unsigned NOT NULL default '0',
  `destfaq` int(10) unsigned NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Daten für Tabelle `faq_related_faq`
-- 

INSERT INTO `faq_related_faq` VALUES (35, 59);
INSERT INTO `faq_related_faq` VALUES (59, 35);
INSERT INTO `faq_related_faq` VALUES (20, 13);
INSERT INTO `faq_related_faq` VALUES (13, 20);
INSERT INTO `faq_related_faq` VALUES (69, 68);
INSERT INTO `faq_related_faq` VALUES (68, 69);
INSERT INTO `faq_related_faq` VALUES (9, 56);
INSERT INTO `faq_related_faq` VALUES (56, 9);

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `faq_related_subcat`
-- 

DROP TABLE IF EXISTS `faq_related_subcat`;
CREATE TABLE `faq_related_subcat` (
  `srccat` int(10) unsigned NOT NULL default '0',
  `destcat` int(10) unsigned NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Daten für Tabelle `faq_related_subcat`
-- 

INSERT INTO `faq_related_subcat` VALUES (8, 7);

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `faq_session`
-- 

DROP TABLE IF EXISTS `faq_session`;
CREATE TABLE `faq_session` (
  `sessid` int(10) unsigned NOT NULL default '0',
  `usernr` int(10) NOT NULL default '0',
  `starttime` int(10) unsigned NOT NULL default '0',
  `remoteip` varchar(15) NOT NULL default '',
  `lastlogin` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`sessid`),
  KEY `sess_id` (`sessid`),
  KEY `start_time` (`starttime`),
  KEY `remote_ip` (`remoteip`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Daten für Tabelle `faq_session`
-- 

INSERT INTO `faq_session` VALUES (73061791, 1, 1129975531, '10.1.1.20', '2005-08-12 15:23:27');

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `faq_settings`
-- 

DROP TABLE IF EXISTS `faq_settings`;
CREATE TABLE `faq_settings` (
  `settingnr` int(10) unsigned NOT NULL default '0',
  `showproglist` tinyint(1) unsigned NOT NULL default '0',
  `watchlogins` tinyint(1) unsigned NOT NULL default '1',
  `allowemail` tinyint(1) unsigned NOT NULL default '1',
  `urlautoencode` tinyint(1) unsigned NOT NULL default '1',
  `enablespcode` tinyint(1) unsigned NOT NULL default '1',
  `nofreemailer` tinyint(1) unsigned NOT NULL default '0',
  `allowquestions` tinyint(1) unsigned NOT NULL default '1',
  `faqemail` varchar(140) NOT NULL default '',
  `allowusercomments` tinyint(1) unsigned NOT NULL default '1',
  `newcommentnotify` tinyint(1) unsigned NOT NULL default '0',
  `enablefailednotify` tinyint(1) unsigned NOT NULL default '0',
  `loginlimit` int(5) unsigned NOT NULL default '0',
  `timezone` int(10) unsigned NOT NULL default '0',
  `enablehostresolve` tinyint(1) unsigned NOT NULL default '1',
  `ratecomments` tinyint(1) unsigned NOT NULL default '1',
  `usemenubar` tinyint(1) unsigned NOT NULL default '1',
  `admtextareasrows` int(4) unsigned NOT NULL default '30',
  `admtextareascols` int(4) unsigned NOT NULL default '10',
  `enablekbrating` tinyint(1) unsigned NOT NULL default '0',
  `userquestionanswermode` tinyint(1) unsigned NOT NULL default '0',
  `userquestionanswermail` tinyint(1) unsigned NOT NULL default '0',
  `userquestionautopublish` tinyint(1) unsigned NOT NULL default '0',
  `faqengine_hostname` varchar(140) NOT NULL default 'localhost',
  `ratingcomment` tinyint(1) unsigned NOT NULL default '0',
  `nosunotify` tinyint(1) unsigned NOT NULL default '0',
  `blockoldbrowser` tinyint(1) unsigned NOT NULL default '1',
  `bbccolorbar` tinyint(1) unsigned NOT NULL default '1',
  `disablehtmlemail` tinyint(1) unsigned NOT NULL default '0',
  `admstorefaqfilters` tinyint(1) unsigned NOT NULL default '1',
  `admhideunassigned` tinyint(1) unsigned NOT NULL default '0',
  `admdelconfirm` tinyint(1) unsigned NOT NULL default '0',
  `zlibavail` tinyint(1) unsigned NOT NULL default '0',
  `msendlimit` int(10) unsigned NOT NULL default '30',
  `subscriptionavail` tinyint(1) unsigned NOT NULL default '1',
  `admedoptions` int(10) unsigned NOT NULL default '0',
  `allsendcompressed` tinyint(1) unsigned NOT NULL default '0',
  `uq_allownoemail` tinyint(1) unsigned NOT NULL default '0',
  `blockleacher` tinyint(1) unsigned NOT NULL default '1',
  `defmailsig` text NOT NULL,
  `faqlistshortcuts` tinyint(1) unsigned NOT NULL default '0',
  `faqlimitrelated` tinyint(1) unsigned NOT NULL default '0',
  `displayrating` tinyint(1) unsigned NOT NULL default '0',
  `admdateformat` varchar(20) NOT NULL default 'Y-m-d H:i:s',
  `showtimezone` tinyint(1) unsigned NOT NULL default '1',
  `showcurrtime` tinyint(1) unsigned NOT NULL default '1',
  `maxconfirmtime` int(4) unsigned NOT NULL default '2',
  `dosearchlog` tinyint(1) unsigned NOT NULL default '0',
  `logdateformat` varchar(20) NOT NULL default 'Y-m-d H:i:s',
  `uqscmail` tinyint(1) NOT NULL default '0',
  `extfailedlog` tinyint(1) unsigned NOT NULL default '0',
  `usebwlist` tinyint(1) unsigned NOT NULL default '0',
  `lhide` tinyint(1) unsigned NOT NULL default '0',
  UNIQUE KEY `settingnr` (`settingnr`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Daten für Tabelle `faq_settings`
-- 

INSERT INTO `faq_settings` VALUES (1, 1, 1, 0, 1, 1, 0, 1, 'faqengine@localhost', 1, 1, 0, 0, 261, 1, 1, 1, 10, 50, 1, 1, 1, 0, 'localhost', 0, 0, 1, 1, 0, 1, 0, 0, 0, 30, 1, 0, 0, 0, 0, '', 0, 0, 1, 'Y-m-d H:i:s', 1, 1, 2, 1, 'd.m.Y H:i:s', 1, 1, 1, 0);

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `faq_subcategory`
-- 

DROP TABLE IF EXISTS `faq_subcategory`;
CREATE TABLE `faq_subcategory` (
  `catnr` int(10) unsigned NOT NULL auto_increment,
  `categoryname` varchar(240) NOT NULL default '',
  `category` int(10) unsigned default '0',
  `displaypos` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`catnr`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Daten für Tabelle `faq_subcategory`
-- 

INSERT INTO `faq_subcategory` VALUES (13, 'Subcat', 12, 1);
INSERT INTO `faq_subcategory` VALUES (12, 'subcategory', 17, 1);

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `faq_subscriptions`
-- 

DROP TABLE IF EXISTS `faq_subscriptions`;
CREATE TABLE `faq_subscriptions` (
  `subscriptionnr` int(10) unsigned NOT NULL auto_increment,
  `email` varchar(240) NOT NULL default '',
  `confirmed` int(1) unsigned NOT NULL default '0',
  `language` varchar(4) NOT NULL default '',
  `subscribeid` int(10) unsigned NOT NULL default '0',
  `unsubscribeid` int(10) unsigned NOT NULL default '0',
  `enterdate` datetime NOT NULL default '0000-00-00 00:00:00',
  `emailtype` tinyint(1) unsigned NOT NULL default '1',
  `progid` varchar(10) NOT NULL default '',
  `compression` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`subscriptionnr`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Daten für Tabelle `faq_subscriptions`
-- 

INSERT INTO `faq_subscriptions` VALUES (1, 'test@boesch.lan', 1, 'en', 0, 793256673, '2002-09-09 22:24:10', 0, 'tst2', 0);
INSERT INTO `faq_subscriptions` VALUES (16, 'test@test.de', 0, 'de', 1383093782, 0, '2005-08-06 14:20:19', 0, 'faqe', 0);
INSERT INTO `faq_subscriptions` VALUES (18, 'test@boesch.lan', 0, 'de', 2090533690, 0, '2005-08-10 11:29:27', 0, 'blaeh', 0);
INSERT INTO `faq_subscriptions` VALUES (19, 'test2@boesch.lan', 0, 'de', 1328776014, 0, '2005-08-10 12:23:13', 0, 'blaeh', 0);
INSERT INTO `faq_subscriptions` VALUES (20, 'blah@boesch.lan', 0, 'de', 1954821443, 0, '2005-08-11 09:49:54', 0, 'faqe', 0);
INSERT INTO `faq_subscriptions` VALUES (21, '1@boesch.lan', 0, 'de', 1943346586, 0, '2005-08-11 22:23:06', 0, 'blaeh', 0);
INSERT INTO `faq_subscriptions` VALUES (22, '2@boesch.lan', 0, 'de', 30133398, 0, '2005-08-11 22:27:13', 0, 'blaeh', 0);
INSERT INTO `faq_subscriptions` VALUES (23, '3@boesch.lan', 0, 'de', 1429652632, 0, '2005-08-11 22:28:37', 0, 'blaeh', 0);
INSERT INTO `faq_subscriptions` VALUES (24, '4@boesch.lan', 0, 'de', 2076183742, 0, '2005-08-11 22:29:29', 0, 'blaeh', 0);
INSERT INTO `faq_subscriptions` VALUES (25, '5@boesch.lan', 0, 'de', 1113289129, 0, '2005-08-11 22:30:02', 0, 'blaeh', 0);
INSERT INTO `faq_subscriptions` VALUES (26, '6@boesch.lan', 0, 'de', 781122005, 0, '2005-08-11 22:31:08', 0, 'blaeh', 0);
INSERT INTO `faq_subscriptions` VALUES (27, '7@boesch.lan', 0, 'de', 383242051, 0, '2005-08-11 22:31:44', 0, 'blaeh', 0);
INSERT INTO `faq_subscriptions` VALUES (28, '99@boesch.lan', 0, 'de', 1001949613, 0, '2005-08-11 22:33:13', 0, 'blaeh', 0);

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `faq_texts`
-- 

DROP TABLE IF EXISTS `faq_texts`;
CREATE TABLE `faq_texts` (
  `textnr` int(10) unsigned NOT NULL auto_increment,
  `textid` varchar(20) NOT NULL default '',
  `lang` varchar(4) NOT NULL default '',
  `text` text NOT NULL,
  PRIMARY KEY  (`textnr`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Daten für Tabelle `faq_texts`
-- 

INSERT INTO `faq_texts` VALUES (2, 'commpre', 'de', '&lt;!-- SPCode Start --&gt;&lt;B&gt;Vorspann Benutzerkommentar&lt;/B&gt;&lt;!-- SPCode End --&gt;\r<BR>&amp;#22914;&amp;#26524;&amp;#24744;&amp;#30340;&amp;#39046;&amp;#22495;&amp;#23384;&amp;#22312;&amp;#20110;&amp;#25105; &amp;lt;test&amp;gt; \\&amp;quot;\\&amp;quot;');
INSERT INTO `faq_texts` VALUES (3, 'questpre', 'de', '&lt;!-- SPCode Start --&gt;&lt;I&gt;Vorspann Benutzerfrage&lt;/I&gt;&lt;!-- SPCode End --&gt;');
INSERT INTO `faq_texts` VALUES (4, 'searchpre', 'de', 'Vorspann Suche');
INSERT INTO `faq_texts` VALUES (5, 'searchpre', 'en', 'aaa bb');
INSERT INTO `faq_texts` VALUES (6, 'uqsubj', 'en', 'New user question');
INSERT INTO `faq_texts` VALUES (7, 'uqbody', 'en', 'A new user question (# {qnr}) was posted.\r<BR>Click on this link to display question:\r<BR>{qlink}');
