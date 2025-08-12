#
# Tabellenstruktur für Tabelle `demo_a_klicksp`
#

CREATE TABLE `demo_a_klicksp` (
  `user` varchar(255) default '0',
  `timefeld` varchar(255) NOT NULL default ''
) TYPE=MyISAM;



#
# Tabellenstruktur für Tabelle `demo_a_accounts`
#

CREATE TABLE `demo_a_accounts` (
  `name` varchar(255) NOT NULL default '',
  `prename` varchar(255) NOT NULL default '',
  `id` int(10) NOT NULL auto_increment,
  `password` varchar(255) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
  `showup` tinyint(1) NOT NULL default '0',
  `points` float NOT NULL default '0',
  `views` int(10) NOT NULL default '0',
  `hits` int(10) NOT NULL default '0',
  `recently` text,
  `sessionid` varchar(255) default NULL,
  `lastview` int(10) NOT NULL default '0',
  `refererid` int(10) default NULL,
  `savepoints` tinyint(1) NOT NULL default '0',
  `reportedby` int(10) NOT NULL default '0',
  `refpoints` float NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`)
) TYPE=MyISAM;


#
# Tabellenstruktur für Tabelle `demo_a_admin`
#

CREATE TABLE `demo_a_admin` (
  `url` varchar(255) NOT NULL default '',
  `seitenname` varchar(255) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  `loginpoints` varchar(255) NOT NULL default '',
  `reportpoints` varchar(255) NOT NULL default '',
  `bannerklick` varchar(255) NOT NULL default '',
  `startcredits` varchar(255) NOT NULL default '',
  `jackport` varchar(255) NOT NULL default '',
  `refjackport` varchar(255) NOT NULL default '',
  `ratio` varchar(255) NOT NULL default '',
  `time` varchar(255) NOT NULL default '',
  `defaultbanner` varchar(255) NOT NULL default '',
  `defaultbannerurl` varchar(255) NOT NULL default '',
  `defaulturl` varchar(255) NOT NULL default '',
  `emailmodi` varchar(255) NOT NULL default '',
  `logeout` varchar(255) NOT NULL default '',
  `registriert` varchar(255) NOT NULL default '',
  `referview` varchar(255) NOT NULL default '',
  `frequency` varchar(255) NOT NULL default '',
  `starten` varchar(255) NOT NULL default '',
  `tausch` varchar(255) NOT NULL default ''
) TYPE=MyISAM;

#
# Daten für Tabelle `demo_a_admin`
#

INSERT INTO `demo_a_admin` (`url`, `seitenname`, `email`, `loginpoints`, `reportpoints`, `bannerklick`, `startcredits`, `jackport`, `refjackport`, `ratio`, `time`, `defaultbanner`, `defaultbannerurl`, `defaulturl`, `emailmodi`, `logeout`, `registriert`, `referview`, `frequency`, `starten`, `tausch`) VALUES ('http://localhost/auto', 'Auto Traffic-Exchange', 'mail@yourdomain.com', '5', '25', '5', '500', '10000', '500', '0.8', '20', 'http://www.yourdomain.com/banner/banner0.gif', 'http://www.yourdomain.com', 'http://localhost', 'http://localhost', 'http://localhost', 'http://localhost', '0.2', '2', '18.07.2002', '10');
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `demo_a_babuchen`
#

CREATE TABLE `demo_a_babuchen` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  `views` varchar(255) NOT NULL default '',
  `source` varchar(255) NOT NULL default '',
  `target` varchar(255) NOT NULL default '',
  `alt` varchar(255) NOT NULL default '',
  `rechnung` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`)
) TYPE=MyISAM;

#
# Daten für Tabelle `demo_a_babuchen`
#

# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `demo_a_bank`
#

CREATE TABLE `demo_a_bank` (
  `name` varchar(255) NOT NULL default '',
  `konummer` varchar(255) NOT NULL default '',
  `banklz` varchar(255) NOT NULL default '',
  `bname` varchar(255) NOT NULL default ''
) TYPE=MyISAM;

#
# Daten für Tabelle `demo_a_bank`
#

INSERT INTO `demo_a_bank` (`name`, `konummer`, `banklz`, `bname`) VALUES ('Account owner', 'Account no', 'Bank no', 'Bank');
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `demo_a_banners`
#

CREATE TABLE `demo_a_banners` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  `source` varchar(255) NOT NULL default '',
  `views` int(10) NOT NULL default '0',
  `clicks` int(10) NOT NULL default '0',
  `lastview` int(10) NOT NULL default '0',
  `target` varchar(255) NOT NULL default '',
  `alt` varchar(255) NOT NULL default '',
  `anzahl` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`)
) TYPE=MyISAM;

#
# Daten für Tabelle `demo_a_banners`
#

# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `demo_a_bebuchen`
#

CREATE TABLE `demo_a_bebuchen` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `password` varchar(255) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
  `points` varchar(255) NOT NULL default '',
  `rechnung` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`)
) TYPE=MyISAM;

#
# Daten für Tabelle `demo_a_bebuchen`
#

# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `demo_a_faq`
#

CREATE TABLE `demo_a_faq` (
  `faq` mediumtext NOT NULL
) TYPE=MyISAM;

#
# Daten für Tabelle `demo_a_faq`
#

INSERT INTO `demo_a_faq` (`faq`) VALUES ('<b>I dont\'t have an own website. Why should I register for your service?</b><br>\r\nYou may either save your points until you have got your own website, or you may use your reflinks instead.<br><br>\r\n<b>Is there any limit?</b><br>\r\nNo, you may earn as much points as you want to!<br><br>\r\n<b>Du I have to install any software?</b><br>\r\nNo, you can use this software on our server und do not have to download it!<br><br>\r\n<b>Isn\'t 20 seconds a visit too short?</b><br>\r\nNo, according to scientific researches a user needs about 7 seconds to decide, wether this site is interesting or not<br><br>\r\n<b>Why do I get myself less visits than the number of websites I visited?</b><br>\r\nThese visits are kept in our system for letting work the referral system and our whole service.<br><br>\r\n<b>Which websites are forbidden to take part?</b><br>\r\nRacist websites, websites with popups,websites with framebreakers and websites with content, which is not allowed for people under 18 or even 21.<br><br>\r\n<b>What to do, if I notice a website that is not according to the rules?</b><br>\r\nPlease let us know so we can controll this.<br><br>\r\n<b>Why should I refer users?</b><br>\r\nYou will get 20% of your referral\'s points.<br><br>\r\n<b>I didn\'t find the answer I was searching for...</b><br>\r\nPlease use the contact-form to send us an e-mail. We will answer asap!');
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `demo_a_gamble`
#

CREATE TABLE `demo_a_gamble` (
  `id` int(10) NOT NULL auto_increment,
  `sessionid` varchar(255) NOT NULL default '',
  `time` int(10) NOT NULL default '0',
  `points` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`)
) TYPE=MyISAM;

#
# Daten für Tabelle `demo_a_gamble`
#

# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `demo_a_gambleadmin`
#

CREATE TABLE `demo_a_gambleadmin` (
  `gampoints` int(10) NOT NULL default '0',
  `gamchance` int(10) NOT NULL default '0',
  `gampointszu` int(10) NOT NULL default '0'
) TYPE=MyISAM;

#
# Daten für Tabelle `demo_a_gambleadmin`
#

INSERT INTO `demo_a_gambleadmin` (`gampoints`, `gamchance`, `gampointszu`) VALUES (1000, 2500, 500);
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `demo_a_grosse`
#

CREATE TABLE `demo_a_grosse` (
  `aa` varchar(255) NOT NULL default '',
  `ab` varchar(255) NOT NULL default '',
  `ba` varchar(255) NOT NULL default '',
  `bb` varchar(255) NOT NULL default '',
  `ca` varchar(255) NOT NULL default '',
  `cb` varchar(255) NOT NULL default '',
  `da` varchar(255) NOT NULL default '',
  `db` varchar(255) NOT NULL default ''
) TYPE=MyISAM;

#
# Daten für Tabelle `demo_a_grosse`
#

INSERT INTO `demo_a_grosse` (`aa`, `ab`, `ba`, `bb`, `ca`, `cb`, `da`, `db`) VALUES ('468', '60', '468', '60', '468', '60', '468', '60');
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `demo_a_iptest`
#

CREATE TABLE `demo_a_iptest` (
  `ip` varchar(255) default '0',
  `timefeld` timestamp(14) NOT NULL
) TYPE=MyISAM;

#
# Daten für Tabelle `demo_a_iptest`
#

INSERT INTO `demo_a_iptest` (`ip`, `timefeld`) VALUES ('1@usertausch.com', 20001103000149);
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `demo_a_iptestb`
#

CREATE TABLE `demo_a_iptestb` (
  `ip` varchar(255) default '0',
  `timefeld` timestamp(14) NOT NULL
) TYPE=MyISAM;

#
# Daten für Tabelle `demo_a_iptestb`
#

# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `demo_a_logo`
#

CREATE TABLE `demo_a_logo` (
  `logoa` mediumtext NOT NULL
) TYPE=MyISAM;

#
# Daten für Tabelle `demo_a_logo`
#

INSERT INTO `demo_a_logo` (`logoa`) VALUES ('Your logo');
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `demo_a_regeln`
#

CREATE TABLE `demo_a_regeln` (
  `regeln` mediumtext NOT NULL
) TYPE=MyISAM;

#
# Daten für Tabelle `demo_a_regeln`
#

INSERT INTO `demo_a_regeln` (`regeln`) VALUES ('<b>§1)</b>By registering for our service you accept these rules. Breaking these rules means your account + points will be deleted. We do have the right to change these rules at anytime.<br><br>\r\n<b>§2)</b> Websites with following content must not use our service: Porn, racism, claiming to violence, everything else which is not according to laws.<br><br>\r\n<b>§3)</b> We have got the right to delete accounts without having to explain the reason.<br><br>\r\n<b>§4)</b> We are neither responsible for websites added by users nor for any link on our website and the website it is leading to.<br><br>\r\n<b>§5)</b> "Spamming" is strictly forbidden. Do not send advertising to someone who haven\'t asked for it! Accounts of spammers will be deleted and they will have to pay for caused damage.<br><br>\r\n<b>§6)</b> We are not responsible if there are any technical difficulties. We do not pay for damage caused by difficulties in our software, on the server etc.<br><br>\r\n<b>§7)</b> We do have the right to change any condition of this service at any time we want and we do not have to notify users about changes. Furthermore we are allowed to quit this service at any time we want. In that case all accounts + points will be deleted.<br><br>\r\n<b>§8)</b> Surfbar is forbidden to be called in hidden frames, iframes or IMG-Tags.<br><br>\r\n<b>§9)</b> More forbidden websites:<br>\r\n-Homepages must not open any further sites (popup etc.)! <br>\r\n-Homepages killing frames of other websites. <br><br>\r\n<b>§10)</b> If any part of these rules is no longer actual, all other parts of these rules are still valid.<br><br>\r\n<br><center><b>Disclaimer</b></center><br><br>\r\n1. Content of this website\r\nWe are not responsible for topicality, correctness, completeness or quality of the information on our website. n, We are not responsible for any disadvantage a user, sponsor or visitor may get by using our service. All offers on this site are without obligation. We do have the right to change conditions or even stop this service at any time we want it. In that case neither users nor sponsors may require payment of damages.<br><br>\r\n2. We are not responsible for any direct or indirect links ("Links"), to other websites and for websites, words or images provided on our website, in e-mails and in the surfbar by sponsors or user. <br><br>\r\n4. As a part of our website you accept these rules as a visitor, user or sponsor of our site.<br><br>\r\n5. Breaking the rules<br><br>\r\n\r\Websites inserted in our system must not be changed after being validated through the admin.\r\nOn inserted websites must not be any program, which is installed automatically on users\' computer (e.g. dialer)\r\n\r\n\r\n');
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `demo_a_seitenbanner`
#

CREATE TABLE `demo_a_seitenbanner` (
  `code` mediumtext NOT NULL
) TYPE=MyISAM;

#
# Daten für Tabelle `demo_a_seitenbanner`
#

INSERT INTO `demo_a_seitenbanner` (`code`) VALUES ('Bannercode');
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `demo_a_texte`
#

CREATE TABLE `demo_a_texte` (
  `startseite` mediumtext NOT NULL
) TYPE=MyISAM;

#
# Daten für Tabelle `demo_a_texte`
#

INSERT INTO `demo_a_texte` (`startseite`) VALUES ('<font size="3"><b>Welcome to the the traffic exchange on *your website title*</b><br><br>\r\n  Promote your website now for getting more traffic on your website! <br>\r\n  Start now and we will give you <font color="red"><b>500</b></font>\r\n  visits only for signing up!<br><br><center><b>REFERRAL SYSTEM</b></center><center>You will get 20% of your referrals points.<br></center>');
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `demo_a_werbebanner`
#

CREATE TABLE `demo_a_werbebanner` (
  `meinebannera` varchar(255) NOT NULL default '',
  `meinebannerurla` varchar(255) NOT NULL default '',
  `meinebannerb` varchar(255) NOT NULL default '',
  `meinebannerurlb` varchar(255) NOT NULL default '',
  `meinebannerc` varchar(255) NOT NULL default '',
  `meinebannerurlc` varchar(255) NOT NULL default '',
  `meinebannerd` varchar(255) NOT NULL default '',
  `meinebannerurld` varchar(255) NOT NULL default ''
) TYPE=MyISAM;

#
# Daten für Tabelle `demo_a_werbebanner`
#

INSERT INTO `demo_a_werbebanner` (`meinebannera`, `meinebannerurla`, `meinebannerb`, `meinebannerurlb`, `meinebannerc`, `meinebannerurlc`, `meinebannerd`, `meinebannerurld`) VALUES ('http://www.usertausch.com/banner/banner1.gif', 'http://www.usertausch.com', 'http://www.usertausch.com/banner/banner2.gif', 'http://www.usertausch.com', 'http://www.usertausch.com/banner/banner3.gif', 'http://www.usertausch.com', 'http://www.usertausch.com/banner/banner4.gif', 'http://www.usertausch.com');
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `demo_a_werbpreis`
#

CREATE TABLE `demo_a_werbpreis` (
  `besuchera` varchar(255) NOT NULL default '',
  `besucherb` varchar(255) NOT NULL default '',
  `besucherc` varchar(255) NOT NULL default '',
  `besucherd` varchar(255) NOT NULL default '',
  `besuchere` varchar(255) NOT NULL default '',
  `besucherf` varchar(255) NOT NULL default '',
  `bannera` varchar(255) NOT NULL default '',
  `bannerb` varchar(255) NOT NULL default '',
  `bannerc` varchar(255) NOT NULL default ''
) TYPE=MyISAM;

#
# Daten für Tabelle `demo_a_werbpreis`
#

INSERT INTO `demo_a_werbpreis` (`besuchera`, `besucherb`, `besucherc`, `besucherd`, `besuchere`, `besucherf`, `bannera`, `bannerb`, `bannerc`) VALUES ('3', '6', '30', '55', '160', '300', '20', '80', '140');
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `demo_a_zahl`
#

CREATE TABLE `demo_a_zahl` (
  `za` varchar(255) NOT NULL default '',
  `zb` varchar(255) NOT NULL default '',
  `zc` varchar(255) NOT NULL default '',
  `zd` varchar(255) NOT NULL default ''
) TYPE=MyISAM;

#
# Daten für Tabelle `demo_a_zahl`
#

INSERT INTO `demo_a_zahl` (`za`, `zb`, `zc`, `zd`) VALUES ('1', '10', '1', '10');