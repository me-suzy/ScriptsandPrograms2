#
# Table structure for table Category
#

DROP TABLE IF EXISTS Category;
CREATE TABLE Category (
   ID tinyint(4) DEFAULT '0' NOT NULL auto_increment,
   Name varchar(100) NOT NULL,
   Description varchar(255) NOT NULL,
   PRIMARY KEY (ID)
);

#
# Dumping data for table Category
#

INSERT INTO Category (ID, Name, Description) VALUES (21,'Computers & Internet','Computers & Internet description. \r\n<br>\r\n<a href=\\"<replace:URL>\\">Enter</a>');
INSERT INTO Category (ID, Name, Description) VALUES (22,'Education','Education description. \r\n<br>\r\n<a href=\\"<replace:URL>\\">Enter</a>');
INSERT INTO Category (ID, Name, Description) VALUES (23,'Entertainment','Entertainment description. \r\n<br>\r\n<a href=\\"<replace:URL>\\">Enter</a>');
# --------------------------------------------------------

#
# Table structure for table Code
#

DROP TABLE IF EXISTS Code;
CREATE TABLE Code (
   ID tinyint(4) DEFAULT '0' NOT NULL auto_increment,
   ProgramID tinyint(4) DEFAULT '0' NOT NULL,
   Name varchar(100) NOT NULL,
   Type varchar(50) DEFAULT 'TOP_BANNER' NOT NULL,
   Description text NOT NULL,
   Code text NOT NULL,
   PRIMARY KEY (ID)
);

#
# Dumping data for table Code
#

INSERT INTO Code (ID, ProgramID, Name, Type, Description, Code) VALUES (1,1,'Textlink','TextLink','&nbsp;<b>Welcome to Test1</b>','<!-- JS code for test 1-->\r\n<A HREF=\\"http://www.test1.com/cgi-bin/partner.pl?UserID=<replace:UserID>&ProgramID=<replace:ProgramID>&SiteID=<jsreplace:SiteID>\\" <jsreplace:TYPE>>Textlink</A>\r\n<!-- end of JS code-->\r\n');
INSERT INTO Code (ID, ProgramID, Name, Type, Description, Code) VALUES (2,1,'Banner Link','Top banner','<img src=\\"http://www.test1.com/banner/banner1.gif\\" width=\\"468\\" height=\\"60\\" border=\\"1\\">\r\n','<!-- JS code for test 1 banner-->\r\n<A HREF=\\"http://www.test1.com/cgi-bin/partner.pl?<jsreplace:ID>\\" <jsreplace:TYPE> ><img src=\\"http://www.handypix.de/banner/banner1.gif\\" border=0></A>\r\n<!-- end of JS code-->\r\n');
# --------------------------------------------------------

#
# Table structure for table CodeType
#

DROP TABLE IF EXISTS CodeType;
CREATE TABLE CodeType (
   ID tinyint(4) DEFAULT '0' NOT NULL auto_increment,
   Name varchar(100) NOT NULL,
   Description varchar(255) NOT NULL,
   DefaultCode text NOT NULL,
   PRIMARY KEY (ID)
);

#
# Dumping data for table CodeType
#

INSERT INTO CodeType (ID, Name, Description, DefaultCode) VALUES (1,'Bottom banner','Bottom banner','dfgdfg');
INSERT INTO CodeType (ID, Name, Description, DefaultCode) VALUES (2,'Top banner','Top banner','');
INSERT INTO CodeType (ID, Name, Description, DefaultCode) VALUES (3,'Left panel banner','Left panel banner','<a href=lalal?REF_ID=<replace:id>></a>');
INSERT INTO CodeType (ID, Name, Description, DefaultCode) VALUES (4,'Right panel banner','Right panel banner','');
INSERT INTO CodeType (ID, Name, Description, DefaultCode) VALUES (5,'TextLink','Text link ad.','');
# --------------------------------------------------------

#
# Table structure for table Program
#

DROP TABLE IF EXISTS Program;
CREATE TABLE Program (
   ID tinyint(4) DEFAULT '0' NOT NULL auto_increment,
   CatID tinyint(4) DEFAULT '0' NOT NULL,
   Name varchar(100) NOT NULL,
   ShortInfo varchar(255) NOT NULL,
   Info text NOT NULL,
   PRIMARY KEY (ID)
);

#
# Dumping data for table Program
#

INSERT INTO Program (ID, CatID, Name, ShortInfo, Info) VALUES (1,21,'Business to Business@','Business to Business@ short info','Business to Business@ detailed info.');
INSERT INTO Program (ID, CatID, Name, ShortInfo, Info) VALUES (2,21,'Consumer Products and Services@','Consumer Products and Services@ short info','Consumer Products and Services@ detailed info');
# --------------------------------------------------------

#
# Table structure for table Template
#

DROP TABLE IF EXISTS Template;
CREATE TABLE Template (
   ID int(10) unsigned DEFAULT '0' NOT NULL auto_increment,
   GroupID tinyint(4) DEFAULT '0' NOT NULL,
   Name varchar(100) NOT NULL,
   Description varchar(255) NOT NULL,
   Body text NOT NULL,
   PRIMARY KEY (ID)
);

#
# Dumping data for table Template
#

INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (1,1,'Index','This is the link to main page action: \r\nIndex.inc','<action:sys/session/Session.inc:Session><action:sys/actions/Index.inc:Index>');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (4,1,'Model','This is a common model of the current page structure.','<html>\r\n<head>\r\n<title>[ example of user page ]</title>\r\n<script language=\\"JavaScript\\">\r\n<!-- hide\r\nfunction openWin ()\r\n{\r\n	codeBuff = select ();\r\n\r\n	win = open(\\"\\", \\"displayWindow\\",\r\n			   \\"width=800,height=600,status=yes,toolbar=yes,menubar=yes\\");\r\n\r\n	win.document.open();\r\n	win.document.write(\\"<html><head><title>Code example\\");\r\n	win.document.write(\\"</title></head><body>\\");\r\n	win.document.write(\\"<center>\\");\r\n	win.document.write(codeBuff);\r\n	win.document.write(\\\'<br><input type=button value=\\"Close it\\" onClick=\\"window.close();\\"></center>\\\');\r\n	win.document.write(\\"</body></html>\\");\r\n	win.document.close();\r\n}\r\nfunction select ()\r\n{\r\n	id       = eval (\\\'document.CodeForm.BannerType [document.CodeForm.BannerType.selectedIndex].value\\\');\r\n	bannerType = eval (\\\'document.CodeForm.BannerType.value\\\');\r\n\r\n	if (bannerType == \\\'this\\\')\r\n		bannerType = \\\'\\\';\r\n	else\r\n		bannerType = \\\' target=_blank \\\';\r\n\r\n	id       = eval (\\\'document.CodeForm.SelectSiteID [document.CodeForm.SelectSiteID.selectedIndex].value\\\');\r\n	codeBuff = eval (\\\'document.CodeForm.Code.value\\\');\r\n\r\n	codeBuff = codeBuff.replace (/<jsreplace:SiteID>/i,   id);\r\n	codeBuff = codeBuff.replace (/<jsreplace:TYPE>/i, bannerType);\r\n\r\n	document.CodeForm.CodeField.value = codeBuff;\r\n\r\n	return codeBuff;\r\n}\r\n// -->\r\n</script>\r\n</head>\r\n<body bgcolor=#FFFFFF text=#000000 leftmargin=0 topmargin=0 marginwidth=0 marginheight=0>\r\n<table width=100% height=100% cellpadding=0 cellspacing=0 border=0>\r\n<td bgcolor=#FFFFFF width=100% height=100%>\r\n<table width=100% height=100% cellpadding=0 cellspacing=1 border=0>\r\n<td width=160 height=70 bgcolor=#E6E6E6 valign=middle align=center><font color=#FF6633><b><a href=./index.php>site logo</a></b></font></td>\r\n<td height=50 bgcolor=#E6E6E6 valign=middle align=center><font color=#006699><b>site sections</b></font></td><tr>\r\n<td width=160 bgcolor=#E6E6E6 valign=top align=left>\r\n<!-- left control panel will be here -->\r\n<replace:LeftPanel>\r\n</td>\r\n<td bgcolor=#E6E6E6 valign=top align=left>\r\n<!-- main panel will be here -->\r\n<replace:MainPanel>\r\n</td>\r\n</table>\r\n</td>\r\n</table>\r\n</body>\r\n</html>');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (5,1,'LeftPanelLogged','Left control panel user will see after logging into the system.','<table width=100% cellpadding=0 cellspacing=0 border=0>\r\n<td bgcolor=#E6E6E6 width=100% >\r\n<table width=100% cellpadding=2 cellspacing=1 border=0>\r\n<td height=20 bgcolor=#E6E6E6 valign=top align=left> </td><tr>\r\n<td height=20 bgcolor=#006699 valign=top align=left><a href=index.php?ActionGroup=UPI><font color=white>Personal info</font></a></td><tr>\r\n<td height=20 bgcolor=#006699 valign=top align=left><a href=index.php?ActionGroup=UBAI><font color=white>Bank account info</font></a></td><tr>\r\n<td height=20 bgcolor=#006699 valign=top align=left><a href=index.php?ActionGroup=UMTI><font color=white>Payment info</font></a></td><tr>\r\n<td height=20 bgcolor=#006699 valign=top align=left><a href=index.php?ActionGroup=USO><font color=white>Your sites</font></a></td><tr>\r\n<td height=20 bgcolor=#006699 valign=top align=left><a href=index.php?ActionGroup=UPO><font color=white>Choose</font></a></td><tr>\r\n<td height=20 bgcolor=#E6E6E6 valign=top align=left> </td><tr>\r\n<td height=20 bgcolor=#006699 valign=top align=left><a href=index.php?ActionGroup=Logout><font color=white>Logout</font></a></td>\r\n</table>\r\n</td>\r\n</table>');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (6,1,'MainPanel','Common structure of MainPanel. \r\nIt is used by all actions (UserPrograms, UserDetails, Registration, Login and so on)','<table width=100% height=100% cellpadding=3 cellspacing=0 border=0>\r\n<td height=20 bgcolor=#E6E6E6 valign=top align=left>&nbsp;<font color=#006699><replace:UserWelcome></font>\r\n</td>\r\n<td height=20 bgcolor=#E6E6E6 valign=top align=right>\r\n<replace:TopControls>&nbsp;\r\n</td><tr>\r\n<td height=100% colspan=2 bgcolor=#FFFFFF valign=top align=center>\r\n<replace:Main>\r\n</td>\r\n</table>');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (19,1,'LeftPanelLogouted','If user is not logged yet he sees this left panel','<br>\r\n<a href=./index.php?ActionGroup=Login>Login</a><br>\r\n<a href=./index.php?ActionGroup=URP>Register</a>');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (20,5,'Login','A form to login','<br><b>Please put your user name and password:</b><br><replace:Error><table>\r\n<form method=POST>\r\n<td>User name:</td><td><input type=text size=30 maxlength=30 name=UserName></td><tr>\r\n<td>User password:</td><td><input type=password size=10 maxlength=10 name=UserPswd></td><tr>\r\n<td><a href=index.php?ActionGroup=URP>Register</a><br><a href=index.php?ActionGroup=Recovery>Did you forget your password?</a></td>\r\n<td><input type=submit value=\\"Enter\\">\r\n<replace:Hidden></td>\r\n</form>\r\n</table>');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (21,5,'LoginError','Put error login description here','<b><font color=red>Not correct values</font></b>');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (126,18,'WML_USO_SAD','Addition of new site.','<table border=\\"0\\" width=\\"500\\" cellspacing=\\"0\\" cellpadding=\\"5\\">\r\n  <tr> \r\n    \r\n    <td width=\\"470\\" valign=\\"top\\"> \r\n      <table border=0 cellspacing=0 cellpadding=0 width=100%>\r\n        <tr> \r\n          <td> \r\n            <div class=\\"headline\\"> New site addition</div>\r\n          </td>\r\n          <td align=\\"right\\"> \r\n            <div class=\\"headline\\">&nbsp; </div>\r\n          </td>\r\n        </tr>\r\n      </table><hr noshade size=\\"1\\" color=\\"#000000\\">\r\n      <div class=\\"inhalt\\"> <b class=\\"inhalt\\">Add info about your site</b> <br>\r\n        <form action=\\"index.php\\" method=get name=\\"FORM_0\\">\r\n          <table border=0 cellpadding=3 cellspacing=0>\r\n            <tr> \r\n              <td valign=\\"top\\"> <img src=\\"../images/leer.gif\\" width=\\"10\\" height=\\"1\\" border=\\"0\\"> \r\n              </td>\r\n              <td> \r\n                <table border=0 cellpadding=0 cellspacing=3 width=300 bgcolor=\\"#eaeaea\\">\r\n                  <tr> \r\n                    <td class=\\"block1\\" valign=center>&nbsp;Site title<br>\r\n                    </td>\r\n                    <td class=\\"block2\\"> \r\n                      <input maxlength=40 name=Title class=\\"inputbox\\"  size=33 value=\\"<replace:Title>\\">\r\n                    </td>\r\n                  <tr> \r\n                    <td class=\\"block1\\">&nbsp;Site URL</td>\r\n                    <td class=\\"block2\\"> \r\n                      <input id=wsURL maxlength=90 name=URL  class=\\"inputbox\\"  size=33 value=\\"<replace:URL>\\" >\r\n                    </td>\r\n                  </tr>\r\n                  <tr> \r\n                    <td class=\\"block1\\">&nbsp;Site description</td>\r\n                    <td class=\\"block2\\"> \r\n                      <input maxlength=60 name=Description class=\\"inputbox\\"  size=33 value=\\"<replace:Description>\\">\r\n                    </td>\r\n                  </tr>\r\n                  <tr> \r\n                    <td colspan=1>&nbsp;</td>\r\n                    <td class=\\"blockwhite\\" align=right> \r\n                      <div class=\\"headline\\"> \r\n                        <input type=\\"submit\\" name=\\"WML_USO_SAD\\" value=\\"Add\\">\r\n                        </div>\r\n                    </td>\r\n                  </tr>\r\n                </table>\r\n              </td>\r\n            </tr>\r\n          </table>\r\n<replace:Hidden>\r\n        </form>\r\n      </div>\r\n    </td>\r\n    <td width=\\"1\\" background=\\"../images/vline.gif\\">&nbsp;</td>\r\n    <td width=\\"150\\" valign=top> \r\n      <p><a href=\\"./index.php?ActionGroup=USO&aid=0\\">Add new site</a></p>\r\n      <p><a href=\\"./index.php?ActionGroup=USO&aid=3\\">Show all sites</a><br>\r\n      </p>\r\n    </td>\r\n  </tr>\r\n</table>');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (38,10,'Recovery','Form to gather email from user to send his password and name to him','<br><b>Put your e-mail:</b><br><replace:Error><table>\r\n<form emthod=GET>\r\n<td>E-mail:</td><td><input type=text size=30 maxlength=30 name=Email></td><tr>\r\n<td><a href=index.php?ActionGroup=Login>Login</a></td>\r\n<td><input type=submit value=\\"ok\\">\r\n<replace:Hidden></td>\r\n</form>\r\n</table>');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (39,10,'RecoveryError','If this email does not exists in db user can register.','<b><font color=red>There is no users with such E-mail.</font></b><br>\r\nRegister yourself, please <a href=./index.php?ActionGroup=Register>Registration</a>');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (40,10,'WaitRecoveryMessage','Wait a message with your details','<b>Wait a letter!</b>');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (41,10,'RecoveryLetter','Recovery Letter','Hello, <replace:FirstName>. \r\nYour details as following:\r\nUserName: <replace:UserName> \\\\n\r\nPassword: <replace:Password>\r\n\r\nBest regards.');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (55,12,'Index','index','<action:sys/session/Session.inc:Session><action:sys/actions/AdminIndex.inc:AdminIndex>');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (56,12,'Model','model','<html>\r\n<head>\r\n<title>[ example of user page ]</title>\r\n<script language=\\"JavaScript\\">\r\n<!-- hide\r\nfunction openWin (clearCode)\r\n{\r\n	win = open(\\"\\", \\"displayWindow\\",\r\n			   \\"width=800,height=400,status=yes,toolbar=yes,menubar=yes\\");\r\n	\r\n	win.document.open();\r\n	win.document.write(\\"<html><head><title>Test\\");\r\n	win.document.write(\\"</title></head><body>\\");\r\n	win.document.write(\\"<center>\\");\r\n	win.document.write(clearCode);\r\n	win.document.write(\\\'<br><input type=button value=\\"Close it\\" onClick=\\"window.close();\\"></center>\\\');\r\n	win.document.write(\\"</body></html>\\");\r\n	win.document.close();\r\n}\r\n// -->\r\n</script>\r\n</head>\r\n<body bgcolor=#FFFFFF text=#000000 leftmargin=0 topmargin=0 marginwidth=0 marginheight=0>\r\n<table width=100% height=100% cellpadding=0 cellspacing=0 border=0>\r\n<td bgcolor=#FFFFFF width=100% height=100%>\r\n<table width=100% height=100% cellpadding=0 cellspacing=1 border=0>\r\n<td width=160 bgcolor=#E6E6E6 valign=top align=left>\r\n<!-- left control panel will be here -->\r\n<replace:LeftPanel>\r\n</td>\r\n<td bgcolor=#E6E6E6 valign=top align=left>\r\n<!-- main panel will be here -->\r\n<replace:MainPanel>\r\n</td>\r\n</table>\r\n</td>\r\n</table>\r\n</body>\r\n</html>');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (57,12,'LeftPanelLogged','Left control panel user will see after logging into the system.','<table width=100% cellpadding=0 cellspacing=0 border=0>\r\n<td bgcolor=#E6E6E6 width=100% >\r\n<table width=100% cellpadding=2 cellspacing=1 border=0>\r\n<td height=20 bgcolor=#E6E6E6 valign=top align=left> </td><tr>\r\n<td height=20 bgcolor=#006699 valign=top align=left><a href=index.php?ActionGroup=UPI><font color=white>Personal info</font></a></td><tr>\r\n<td height=20 bgcolor=#006699 valign=top align=left><a href=index.php?ActionGroup=UBAI><font color=white>Bank account info</font></a></td><tr>\r\n<td height=20 bgcolor=#006699 valign=top align=left><a href=index.php?ActionGroup=UMTI><font color=white>Payment info</font></a></td><tr>\r\n<td height=20 bgcolor=#006699 valign=top align=left><a href=index.php?ActionGroup=USO><font color=white>Your sites</font></a></td><tr>\r\n<td height=20 bgcolor=#006699 valign=top align=left><a href=index.php?ActionGroup=UPO><font color=white>Choose</font></a></td><tr>\r\n<td height=20 bgcolor=#E6E6E6 valign=top align=left> </td><tr>\r\n<td height=20 bgcolor=#006699 valign=top align=left><a href=../index.php><font color=white>Logout</font></a></td>\r\n</table>\r\n</td>\r\n</table>');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (58,12,'MainPanel','Common structure of MainPanel. \r\nIt is used by all actions (UserPrograms, UserDetails, Registration, Login and so on)','<table width=100% height=100% cellpadding=3 cellspacing=0 border=0>\r\n<td height=20 bgcolor=#E6E6E6 valign=top align=left>&nbsp;<font color=#006699><replace:UserWelcome></font>\r\n</td>\r\n<td height=20 bgcolor=#E6E6E6 valign=top align=right>\r\n<replace:TopControls>&nbsp;\r\n</td><tr>\r\n<td height=100% colspan=2 bgcolor=#FFFFFF valign=top align=center>\r\n<replace:Main>\r\n</td>\r\n</table>');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (59,12,'LeftPanelLogouted','If user is not logged yet he sees this left panel','');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (63,13,'Error','Error group','<br>There are following errors: <br>\r\n<table width=200>\r\n<replace:Element>\r\n</table>\r\n');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (64,13,'ErrorElement','Error itself','<td width=1><font color=red><b>-</b></font></td><td><font color=red><replace:Element></font></td><tr>');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (65,13,'Warning','Warning','<h3>\r\n<replace:Element>\r\n</h3>');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (89,15,'Error_BankCountry','Bank country error.','Bank country error.');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (97,15,'FORM_0','User bank account information form.','<link rel=\\"Stylesheet\\" href=\\"../includes/stylesheet.css\\" type=\\"text/css\\">\r\n    <div class=\\"headline\\">\r\n    Your bank account\r\n    </div>\r\n\r\n    <hr noshade size=\\"1\\" color=\\"#000000\\">\r\n    <div class=\\"inhalt\\">\r\n    <b class=\\"inhalt\\">\r\n\r\n\r\n	<br>&nbsp;<br>\r\n<br>&nbsp;<br>\r\n\r\n\r\n\r\n	<form name=\\"FORM_0\\" action=\\"index.php\\" method=GET>\r\n	<table border=0>\r\n		<tr><td class=inhalt>Account holder name:</td>\r\n			<td class=inhalt><input name=AccountHolder type=\\"text\\" size=25 value=\\"<replace:AccountHolder>\\"></td>\r\n		</tr>\r\n		<tr><td class=inhalt>Account number:</td>\r\n			<td class=inhalt><input name=AccountNumber type=\\"text\\" size=25 value=\\"<replace:AccountNumber>\\"></td>\r\n		</tr>\r\n		<tr><td class=inhalt>Bank code</td>\r\n			<td class=inhalt><input name=BankCode type=\\"text\\" size=11 value=\\"<replace:BankCode>\\"></td>\r\n		</tr>\r\n		<tr><td class=inhalt>Bank name</td>\r\n			<td class=inhalt><input name=BankName type=\\"text\\" size=25 value=\\"<replace:BankName>\\"></td>\r\n		</tr>\r\n\r\n		<tr><td class=inhalt colspan=2>\r\n		<br>&nbsp;<br>\r\n				</td></tr>\r\n\r\n		<tr><td class=inhalt>Additional Code </td>\r\n			<td class=inhalt><input name=SWIFT type=\\"text\\" size=11 value=\\"<replace:SWIFT>\\"></td>\r\n		</tr>\r\n		<tr><td class=inhalt>Bank street:</td>\r\n			<td class=inhalt><input name=BankStreet type=\\"text\\" size=25 value=\\"<replace:BankStreet>\\"></td>\r\n		</tr>\r\n		<tr><td class=inhalt>Bank city</td>\r\n			<td class=inhalt><input name=BankCity type=\\"text\\" size=25 value=\\"<replace:BankCity>\\" ></td>\r\n		</tr>\r\n		<tr><td class=inhalt>Bank country</td>\r\n			<td class=inhalt><input name=BankCountry type=\\"text\\" size=25 value=\\"<replace:BankCountry>\\" ></td>\r\n		</tr>\r\n\r\n		<tr><td class=inhalt colspan=2>\r\n		<br>&nbsp;<input type=submit name=FORM_0 value=\\"Update\\">\r\n		</td></table>\r\n<replace:Hidden>\r\n</form>');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (90,15,'Error_AccountHolder','Account holder name error.','Account holder name error.');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (91,15,'Error_AccountNumber','Account number error.','Account number error.');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (92,15,'Error_BankCode','Bank code error.','Bank code error.');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (93,15,'Error_BankName','Bank name error','Bank name error');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (94,15,'Error_SWIFT','code error','code error');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (95,15,'Error_BankStreet','Bank street error.','Bank street error.');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (96,15,'Error_BankCity','Bank city error.','Bank city error.');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (98,15,'Success_0','Succesfull information updating.','Your information has been updated succesfully.');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (99,17,'Form_0','<b>User details form</b>\r\n<br><b>Fields:</b><br>\r\n<ul>\r\n<li>Company,\r\n<li>FirstName,\r\n<li>Name,\r\n<li>Street,\r\n<li>Country,\r\n<li>ZipCode,\r\n<li>Location,\r\n<li>WorkPhone,\r\n<li>HomePhone,\r\n<li>MobilePhone,\r\n<li>Fax,\r\n<li>Email,\r\n<li>Dob (Date of birt','<link rel=\\"Stylesheet\\" href=\\"includes/stylesheet.css\\" type=\\"text/css\\">\r\n\r\n	<div class=\\"headline\\">\r\n		Welcome to registration\r\n	</div>\r\n	<hr noshade size=\\"1\\" color=\\"#000000\\">\r\n\r\n	<div class=\\"infotext\\">\r\n		1. Step - Personal info\r\n		<br>&nbsp;<br>\r\n	</div>\r\n	\r\n	<div class=\\"inhalt\\">\r\n\r\n		<br> <br>\r\n		\r\n		<FORM action=\\"index.php\\" method=POST name=\\"FORM_0\\">\r\n	\r\n		\r\n		<TABLE border=0 cellpadding=0 cellspacing=3 width=300 bgcolor=\\"#eaeaea\\">\r\n		\r\n		\r\n			\r\n	        <TR>\r\n	                <TD class=\\"block1\\" valign=center>&nbsp;Company <br></TD>\r\n	                <TD class=\\"block2\\">\r\n						<INPUT id=wmFirma maxLength=40 name=Company CLASS=\\"inputbox\\"  SIZE=35 value=\\"<replace:Company>\\"></td>\r\n					</tr>\r\n	        \r\n	        <TR>\r\n	                <TD class=\\"block1\\">&nbsp;First name</TD>\r\n	                <TD class=\\"block2\\"><INPUT id=wmVorname maxLength=30 name=FirstName  CLASS=\\"inputbox\\"  SIZE=35 value=\\"<replace:FirstName>\\" ></TD>\r\n	        </TR>\r\n	        \r\n	        <TR>\r\n	                <TD class=\\"block1\\">&nbsp;Name</TD>\r\n	                <TD class=\\"block2\\"><INPUT id=wmName maxLength=40 name=Name  CLASS=\\"inputbox\\"  SIZE=35  value=\\"<replace:Name>\\"></TD>\r\n\r\n	        </TR>\r\n	        \r\n	        <TR>\r\n	                <TD class=\\"block1\\">&nbsp;Street</td>\r\n	                <TD class=\\"block2\\"><INPUT id=wmStrasse maxLength=40 name=Street CLASS=\\"inputbox\\"  SIZE=35  value=\\"<replace:Street>\\"></td>\r\n	        </tr>\r\n	        \r\n	        <TR>\r\n	                <TD class=\\"block1\\">&nbsp;Country</td>\r\n	                <TD class=\\"block2\\">\r\n<INPUT id=wmOrt maxLength=50 name=Country size=26 CLASS=\\"inputbox\\"  value=\\"<replace:Country>\\">\r\n</td>\r\n	        </tr>\r\n\r\n	        <TR>\r\n	                <TD class=\\"block1\\">&nbsp;Code</td>\r\n	                <TD class=\\"block2\\"><INPUT id=wmPLZ maxLength=5 name=ZipCode  size=5 CLASS=\\"inputbox\\"  value=\\"<replace:ZipCode>\\">&nbsp;<INPUT id=wmOrt maxLength=50 name=Location size=26 CLASS=\\"inputbox\\"  value=\\"<replace:Location>\\"></td>\r\n	        </tr>\r\n\r\n	        <TR>\r\n	                <TD class=\\"block1\\">&nbsp;Tel. work</td>\r\n	                <TD class=\\"block2\\"><INPUT id=wmTelefon_tags maxLength=18 name=WorkPhone  CLASS=\\"inputbox\\"  SIZE=35  value=\\"<replace:WorkPhone>\\">\r\n	                </td>\r\n	                <tr>\r\n	                <TD class=\\"block1\\">&nbsp;Tel. home</td>\r\n	                <td class=\\"block2\\"><INPUT id=wm0 maxLength=20 name=HomePhone size=\\"35\\" CLASS=\\"inputbox\\"   value=\\"<replace:HomePhone>\\"></td>\r\n	        </tr>\r\n\r\n	        <TR>\r\n	                <TD class=\\"block1\\">&nbsp;Tel. mobile</td>\r\n	                <TD class=\\"block2\\"><INPUT id=wm1 maxLength=20 name=MobilePhone  CLASS=\\"inputbox\\"  SIZE=35  value=\\"<replace:MobilePhone>\\">\r\n	                </td><tr>\r\n	                <TD class=\\"block1\\">&nbsp;Fax</td>\r\n	                <td class=\\"block2\\"><INPUT id=wm2 maxLength=20 name=Fax size=\\"35\\"  CLASS=\\"inputbox\\"  SIZE=35  value=\\"<replace:Fax>\\"></td>\r\n\r\n	        </tr>\r\n	        <TR>\r\n	                <TD class=\\"block1\\">&nbsp;Email</td>\r\n	                <TD class=\\"block2\\"><INPUT id=wm4 maxLength=50 name=Email CLASS=\\"inputbox\\" SIZE=35  value=\\"<replace:Email>\\"></td>\r\n	        </tr>\r\n\r\n	        <TR>\r\n	                <TD class=\\"block1\\">&nbsp;Birth date&nbsp;&nbsp;&nbsp;</td>\r\n	                <TD class=\\"block2\\"><INPUT id=wmGebdatum_tag maxLength=2 name=BirthDay size=2 CLASS=\\"inputbox\\"  value=\\"<replace:BirthDay>\\">.<INPUT id=wmGebdatum_monat maxLength=2 name=BirthMonth size=2  CLASS=\\"inputbox\\"  value=\\"<replace:BirthMonth>\\">.<INPUT id=wmGebdatum_jahr maxLength=4 name=BirthYear size=4 CLASS=\\"inputbox\\"  value=\\"<replace:BirthYear>\\"> \r\n	                </td>\r\n	        </tr>\r\n	        \r\n\r\n	        <TR>\r\n					<td colspan=1>&nbsp;</td>\r\n	                <td class=\\"blockwhite\\" align=right><div class=\\"headline\\">\r\n	                &nbsp;<br><input type=submit name=\\"FORM_0\\" value=\\"Next\\"></td>\r\n	        </tr>\r\n	</TABLE>\r\n	<replace:Hidden>\r\n	</FORM>\r\n	</div>\r\n\r\n</td>\r\n</tr>\r\n</table>');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (100,17,'Error_0_Company','Company error.','\\"Firma\\"');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (101,17,'Error_0_FirstName','Firstname error.','\\"Vorname\\"');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (102,17,'Error_0_Name','Name error.','\\"Name\\"');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (103,17,'Error_0_Street','Street error.','\\"Stra?e\\"');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (104,17,'Error_0_Country','Country error.','\\"Land\\"');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (105,17,'Error_0_ZipCode','Zip code error.','\\"PLZ\\"');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (106,17,'Error_0_Location','Location error.','\\"Ort\\"');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (107,17,'Error_0_HomePhone','Home phone error.','\\"Tel. abends\\"');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (108,17,'Error_0_WorkPhone','Work phone error.','\\"Tel. tags\\"');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (109,17,'Error_0_Fax','Fax error.','\\"Fax\\"');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (110,17,'Error_0_Email','Email error.','\\"Email\\"');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (111,17,'Error_0_BirthDay','Birth date error.','\\"Geburtsdatum tag\\"');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (112,17,'Message_0','User details have been filled.','0 - yes');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (113,17,'Error_0_BirthMonth','Birth month error.','\\" monat\\"');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (114,17,'Error_0_BirthYear','Birth year error.','\\"jhar\\"');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (115,17,'Error_0_MobilePhone','Mobile phone error.','\\"Tel. mobil\\"');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (116,17,'Form_1','Agreement.','<link rel=\\"Stylesheet\\" href=\\"includes/stylesheet.css\\" type=\\"text/css\\">\r\n\r\n\r\n\r\n\r\n\r\n<table border=0 width=600>\r\n<tr>\r\n<td>\r\n	<div class=\\"headline\\">\r\n		Welcome to registration\r\n	</div>\r\n	<hr noshade size=\\"1\\" color=\\"#000000\\">\r\n\r\n	<div class=\\"infotext\\">\r\n		2. Step - Agreement\r\n		<br>&nbsp;<br>\r\n	</div>\r\n	\r\n	<div class=\\"inhalt\\">\r\n		<br> <br>\r\n				\r\n		\r\n		<FORM action=\\"index.php\\" method=POST name=\\"FORM_1\\">\r\n		\r\n		\r\n		<TABLE border=0 cellpadding=0 cellspacing=3 width=400 bgcolor=\\"#eaeaea\\">\r\n		\r\n		\r\n			\r\n	        <TR>\r\n	        <!-- Gewerbetreibender -->\r\n	                <TD CLASS=\\"block1\\" Colspan=2>\r\n	                First agreement\r\n	                </Td>\r\n			</tr>\r\n			<tr>\r\n	                <TD class=\\"block2\\" colspan=2><div class=\\"inhaltsmall\\">\r\n						<input type=\\"radio\\" checked value=\\"true\\" name=\\"Agree1\\" >Yes						<br>\r\n						<input type=\\"radio\\" value=\\"false\\" name=\\"Agree1\\">No						</div>\r\n					</td>\r\n			</tr>\r\n			\r\n			\r\n	        <TR>\r\n	        <!-- AGBS -->\r\n	                <TD CLASS=\\"block1\\" Colspan=2><br>&nbsp;<br>\r\n	                Second agreement\r\n	                <textarea class=\\"inputbox\\" name=\\"agbstext\\" rows=7 cols=50 >\r\nAgreement items here.\r\n</textarea>\r\n	                </Td>\r\n			</tr>\r\n			<tr>\r\n	                <TD class=\\"block2\\" colspan=2><div class=\\"inhaltsmall\\">\r\n						<input type=\\"checkbox\\" value=\\"true\\" name=\\"Agree2\\" >Yes, I\\\'ve read.						</div>\r\n								\r\n					\r\n\r\n	        <TR>\r\n					<td colspan=1>&nbsp;</td>\r\n	                <td class=\\"blockwhite\\" align=right><div class=\\"headline\\">\r\n	                &nbsp;<input type=button onClick=\\"javascript:history()\\" value=\\"Back\\">\r\n					<input type=submit name=\\"FORM_1\\" value=\\"Next\\">\r\n	               </td>\r\n	        </tr>\r\n	</TABLE>\r\n<replace:Hidden>\r\n	</FORM>\r\n	</div>\r\n\r\n</td>\r\n</tr>\r\n</table>');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (117,17,'Message_1','Agreement accepted.','1 - yes');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (118,17,'Error_1_Agree1','Agreement 1 error.','Agreement 1 error.');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (119,17,'Error_1_Agree2','Agreement 2 error.','Agreement 2 error.');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (120,17,'Form_2','User name and user password.','<link rel=\\"Stylesheet\\" href=\\"includes/stylesheet.css\\" type=\\"text/css\\">\r\n\r\n\r\n\r\n\r\n\r\n<table border=0 width=600>\r\n<tr>\r\n<td>\r\n	<div class=\\"headline\\">\r\n		Welcome to registration\r\n	</div>\r\n	<hr noshade size=\\"1\\" color=\\"#000000\\">\r\n\r\n	<div class=\\"infotext\\">\r\n		3. Step - User name and password\r\n		<br>&nbsp;<br>\r\n	</div>\r\n\r\n	<div class=\\"inhalt\\">\r\n		<br> <br>\r\n\r\n\r\n		<FORM action=\\"index.php\\" method=POST name=\\"FORM_2\\">\r\n		<TABLE border=0 cellpadding=0 cellspacing=3 width=300 bgcolor=\\"#eaeaea\\">\r\n\r\n\r\n\r\n	        <TR>\r\n	                <TD class=\\"block1\\" valign=center>&nbsp;Username<br></TD>\r\n	                <TD class=\\"block2\\">\r\n						<INPUT id=wmFirma maxLength=15 name=UserName CLASS=\\"inputbox\\"  SIZE=20 value=\\"<replace:UserName>\\"></td>\r\n			</tr>\r\n			<tr>\r\n					<TD class=\\"block1\\" valign=center>&nbsp;Passwort<br></TD>\r\n					<td class=\\"block2\\">\r\n	                    <INPUT id=wmFirma1 type=password maxLength=10 name=UserPswdA   CLASS=\\"inputbox\\"  SIZE=20  >\r\n	                </TD>\r\n	        </TR>\r\n	        <tr>\r\n					<TD class=\\"block1\\" valign=center>&nbsp;Password<br></TD>\r\n					<td class=\\"block2\\">\r\n	                    <INPUT id=wmFirma1 type=password maxLength=10 name=UserPswdB   CLASS=\\"inputbox\\"  SIZE=20  >\r\n	                </TD>\r\n	        </TR>\r\n\r\n	        <TR>\r\n					<td colspan=1>&nbsp;</td>\r\n	                <td class=\\"blockwhite\\" align=right><div class=\\"headline\\">\r\n	                &nbsp;<input type=submit name=\\"FORM_2\\" value=\\"Finish\\"></td>\r\n	        </tr>\r\n	</TABLE>\r\n	<replace:Hidden>\r\n	</FORM>\r\n	</div>\r\n\r\n</td>\r\n</tr>\r\n</table>');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (121,17,'Message_2','User has been registered.','<link rel=\\"Stylesheet\\" href=\\"includes/stylesheet.css\\" type=\\"text/css\\">\r\n\r\n\r\n\r\n\r\n\r\n<table border=0 width=600>\r\n<tr>\r\n<td>\r\n	<div class=\\"headline\\">\r\n		Registration is finished!\r\n	</div>\r\n	<hr noshade size=\\"1\\" color=\\"#000000\\">\r\n\r\n	<div class=\\"infotext\\">\r\n		You are welcome!\r\n		<br>&nbsp;<br>\r\n	</div>\r\n	\r\n	<div class=\\"inhalt\\">\r\n		Your user name is <b class=\\"inhalt\\"><replace:UserName></b> and your password is stored in the system. \r\n		<br>&nbsp;<br>\r\n\r\n		<br>&nbsp;<br>\r\n		\r\n		<a href=./index.php?ActionGroup=Login>Login</a>\r\n\r\n	</div>\r\n\r\n</td>\r\n</tr>\r\n</table>');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (122,17,'Error_2_UserName','User name error.','User name error.');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (123,17,'Error_2_UserPswd','User password error.','User password error.');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (124,17,'Error_2_UserExists','User name exists.','User name exists.');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (125,17,'Error_2_PswdDifference','Passwords are different.','Passwords are different.');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (127,18,'WML_USO_SAR','New site has been added.','New site has been added.');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (128,18,'WML_USO_SAD_TNC','Site title error.','Site title error.');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (129,18,'WML_USO_SAD_UNC','Site URL error.','Site URL error.');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (130,18,'WML_USO_SAD_DNC','Site description error.','Site description error.');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (139,18,'WML_USO_SUD_SUE','Such URL exists in db.','Such URL exists in db.');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (140,19,'WML_UPO_CCD','Category choise displaying.','<table>\r\n<replace:WML_UPO_CCD_CDB>\r\n</table>');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (141,19,'WML_UPO_CCD_CDB','Category displaying block.','<tr><td><replace:Name></td>\r\n<td><replace:Description></td>\r\n</tr>');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (142,19,'WML_UPO_PCD','Program choise displaying.','<table border=\\"0\\" width=\\"500\\" cellspacing=\\"0\\" cellpadding=\\"5\\">\r\n  <tr> \r\n    <td width=\\"470\\" valign=\\"top\\"> \r\n      <table width=\\"100%\\" border=\\"0\\" cellspacing=\\"0\\" cellpadding=\\"0\\">\r\n        <replace:WML_UPO_PCD_PDB>\r\n      </table>\r\n      <p>&nbsp;</p>\r\n    </td>\r\n    <td width=\\"1\\" background=\\"../images/vline.gif\\">&nbsp;</td>\r\n    <td width=\\"150\\" valign=top> \r\n      <p><a href=\\"<replace:CategoryURL>\\">All categories</a><br>\r\n      </p>\r\n      </td>\r\n  </tr>\r\n</table>');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (131,18,'WML_USO_SID','Site information displaying.','<table border=\\"0\\" width=\\"500\\" cellspacing=\\"0\\" cellpadding=\\"5\\">\r\n  <tr> <form action=\\"index.php\\" method=GET>\r\n    <td width=\\"470\\" valign=\\"top\\"> \r\n      <table border=0 cellspacing=0 cellpadding=0 width=100%>\r\n        <tr> \r\n          <td> \r\n            <div class=\\"headline\\"> Site list</div>\r\n          </td>\r\n          <td align=\\"right\\"> \r\n            <div class=\\"headline\\">&nbsp; </div>\r\n          </td>\r\n        </tr>\r\n      </table>\r\n      <table border=0 cellpadding=0 cellspacing=3 width=300 bgcolor=\\"#eaeaea\\">\r\n        <tr> \r\n          <td class=\\"block1\\" valign=center bgcolor=\\"#999999\\" width=\\"17\\">&nbsp;<font color=\\"#FFFFFF\\">#</font><br>\r\n          </td>\r\n          <td class=\\"block2\\" bgcolor=\\"#999999\\" width=\\"49\\"><font color=\\"#FFFFFF\\">Title \r\n            </font></td>\r\n          <td class=\\"block2\\" colspan=\\"3\\" bgcolor=\\"#999999\\"><font color=\\"#FFFFFF\\">Description</font></td></tr>\r\n        <replace:WML_USO_SID_SIB>\r\n      </table>\r\n      <p>&nbsp;</p>\r\n<input type=submit value=\\"Delete checked sites\\">\r\n    </td>\r\n    <td width=\\"1\\" background=\\"../images/vline.gif\\">&nbsp;</td>\r\n    <td width=\\"150\\" valign=top> \r\n      <p><a href=\\"./index.php?ActionGroup=USO&aid=0\\">Add new site</a></p>\r\n      <p><a href=\\"./index.php?ActionGroup=USO&aid=3\\">Show all sites</a><br>\r\n      </p>\r\n    </td>\r\n<replace:Hidden>\r\n</form>\r\n  </tr>\r\n</table>');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (132,18,'WML_USO_SID_SIB','Site information block.','<tr> \r\n          <td class=\\"block1\\" valign=center width=\\"17\\"><replace:Number></td>\r\n          <td class=\\"block2\\" nowrap width=\\"49\\"><replace:Title></td>\r\n          <td class=\\"block2\\" nowrap width=\\"155\\"><replace:Description></td>\r\n          <td class=\\"block2\\" width=\\"56\\">\r\n<input type=checkbox name=DeleteID<replace:Number> value=delete>\r\n</td>\r\n          <td class=\\"block2\\" width=\\"34\\"><a href=\\"<replace:editURL>\\">edit</a></td>\r\n\r\n</tr>');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (133,18,'WML_USO_SDC','Site delete conformation.','<table border=\\"0\\" width=\\"500\\" cellspacing=\\"0\\" cellpadding=\\"5\\">\r\n  <tr> <form action=\\"index.php\\" method=GET>\r\n    <td width=\\"470\\" valign=\\"top\\"> \r\n      <table border=0 cellspacing=0 cellpadding=0 width=100%>\r\n        <tr> \r\n          <td> \r\n            <div class=\\"headline\\"> Site list for delete</div>\r\n          </td>\r\n          <td align=\\"right\\"> \r\n            <div class=\\"headline\\">&nbsp; </div>\r\n          </td>\r\n        </tr>\r\n      </table>\r\n      <table border=0 cellpadding=0 cellspacing=3 width=300 bgcolor=\\"#eaeaea\\">\r\n        <tr> \r\n          <td class=\\"block1\\" valign=center bgcolor=\\"#999999\\" width=\\"17\\">&nbsp;<font color=\\"#FFFFFF\\">#</font><br>\r\n          </td>\r\n          <td class=\\"block2\\" bgcolor=\\"#999999\\" width=\\"49\\"><font color=\\"#FFFFFF\\">Title \r\n            </font></td></tr>\r\n        <replace:WML_USO_SDC_DCB>\r\n      </table>\r\n      <p>&nbsp;</p>\r\n<input type=button onClick=\\"javascript:history.back ();\\" value=\\"Back\\">\r\n<input type=submit value=\\"Delete\\">\r\n    </td>\r\n    <td width=\\"1\\" background=\\"../images/vline.gif\\">&nbsp;</td>\r\n    <td width=\\"150\\" valign=top> \r\n      <p><a href=\\"./index.php?ActionGroup=USO&aid=0\\">Add new site</a></p>\r\n      <p><a href=\\"./index.php?ActionGroup=USO&aid=3\\">Show all sites</a><br>\r\n      </p>\r\n    </td>\r\n<replace:Hidden>\r\n</form>\r\n  </tr>\r\n</table>');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (134,18,'WML_USO_SDC_DCB','Delete conformation block.','<tr> \r\n          <td class=\\"block1\\" valign=center width=\\"17\\"><replace:Number></td>\r\n          <td class=\\"block2\\" nowrap width=\\"49\\"><replace:Title></td>\r\n</tr>');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (135,18,'WML_USO_SDD','Site delete displaying.','Selected sites have been removed.');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (136,18,'WML_USO_SID_ESL','Empty site list.','<table border=\\"0\\" width=\\"500\\" cellspacing=\\"0\\" cellpadding=\\"5\\">\r\n  <tr> \r\n    <td width=\\"470\\" valign=\\"top\\"> \r\n      <table border=0 cellspacing=0 cellpadding=0 width=100%>\r\n        <tr> \r\n          <td> \r\n            <div class=\\"headline\\"> &nbsp;</div>\r\n          </td>\r\n          <td align=\\"right\\"> \r\n            <div class=\\"headline\\">&nbsp; </div>\r\n          </td>\r\n        </tr>\r\n      </table>\r\n      There are no sites. \r\n      <p>&nbsp;</p>\r\n    </td>\r\n    \r\n    <td width=\\"1\\" background=\\"../images/vline.gif\\">&nbsp;</td>\r\n    <td width=\\"150\\" valign=top> \r\n      <p><a href=\\"./index.php?ActionGroup=USO&aid=0\\">Add new site</a></p>\r\n      <p><a href=\\"./index.php?ActionGroup=USO&aid=3\\">Show all sites</a><br>\r\n      </p>\r\n    </td>\r\n  </tr>\r\n</table>');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (137,18,'WML_USO_SUD','Site updating displaying.','<table border=\\"0\\" width=\\"500\\" cellspacing=\\"0\\" cellpadding=\\"5\\">\r\n  <tr> \r\n    <td width=\\"470\\" valign=\\"top\\"> \r\n      <table border=0 cellspacing=0 cellpadding=0 width=100%>\r\n        <tr> \r\n          <td> \r\n            <div class=\\"headline\\"> Site information updating</div>\r\n          </td>\r\n          <td align=\\"right\\"> \r\n            <div class=\\"headline\\">&nbsp; </div>\r\n          </td>\r\n        </tr>\r\n      </table>\r\n      <hr noshade size=\\"1\\" color=\\"#000000\\">\r\n      <div class=\\"inhalt\\"> <b class=\\"inhalt\\">New site info</b> <br>\r\n        <form action=\\"index.php\\" method=get name=\\"FORM_0\\">\r\n          <table border=0 cellpadding=3 cellspacing=0>\r\n            <tr> \r\n              <td valign=\\"top\\"> <img src=\\"../images/leer.gif\\" width=\\"10\\" height=\\"1\\" border=\\"0\\"> \r\n              </td>\r\n              <td> \r\n                <table border=0 cellpadding=0 cellspacing=3 width=300 bgcolor=\\"#eaeaea\\">\r\n                  <tr> \r\n                    <td class=\\"block1\\" valign=center>&nbsp;Site title<br>\r\n                    </td>\r\n                    <td class=\\"block2\\"> \r\n                      <input maxlength=40 name=Title class=\\"inputbox\\"  size=33 value=\\"<replace:Title>\\">\r\n                    </td>\r\n                  <tr> \r\n                    <td class=\\"block1\\">&nbsp;Site URL</td>\r\n                    <td class=\\"block2\\"> \r\n                      <input id=wsURL maxlength=90 name=URL  class=\\"inputbox\\"  size=33 value=\\"<replace:URL>\\" >\r\n                    </td>\r\n                  </tr>\r\n                  <tr> \r\n                    <td class=\\"block1\\">&nbsp;Site description</td>\r\n                    <td class=\\"block2\\"> \r\n                      <input maxlength=60 name=Description class=\\"inputbox\\"  size=33 value=\\"<replace:Description>\\">\r\n                    </td>\r\n                  </tr>\r\n                  <tr> \r\n                    <td colspan=1>&nbsp;</td>\r\n                    <td class=\\"blockwhite\\" align=right> \r\n                      <div class=\\"headline\\"> \r\n                        <input type=\\"submit\\" name=\\"WML_USO_SUD\\" value=\\"Update\\">\r\n                        </div>\r\n                    </td>\r\n                  </tr>\r\n                </table>\r\n              </td>\r\n            </tr>\r\n          </table>\r\n<replace:Hidden>\r\n        </form>\r\n      </div>\r\n    </td>\r\n    <td width=\\"1\\" background=\\"../images/vline.gif\\">&nbsp;</td>\r\n    <td width=\\"150\\" valign=top> \r\n      <p><a href=\\"./index.php?ActionGroup=USO&aid=0\\">Add new site</a></p>\r\n      <p><a href=\\"./index.php?ActionGroup=USO&aid=3\\">Show all sites</a><br>\r\n      </p>\r\n    </td>\r\n  </tr>\r\n</table>');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (138,18,'WML_USO_SUI','Site updating result.','Selected site has been updating.');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (143,19,'WML_UPO_PCD_PDB','Program displaying block.','<tr>\r\n<td><a href=\\"<replace:URL>\\"><replace:Name></a></td>\r\n<td><replace:ShortInfo></td>\r\n</tr>');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (144,19,'WML_UPO_PID','Program Info Displaying.','<table border=\\"0\\" width=\\"500\\" cellspacing=\\"0\\" cellpadding=\\"5\\">\r\n  <tr> \r\n    <td width=\\"470\\" valign=\\"top\\"> \r\n      <replace:Name><br>\r\n<replace:ShortInfo><br>\r\n<replace:Info><br>\r\n<a href=\\"<replace:URL>\\">I want to add this!</a>\r\n      <p>&nbsp;</p>\r\n    </td>\r\n    <td width=\\"1\\" background=\\"../images/vline.gif\\">&nbsp;</td>\r\n    <td width=\\"150\\" valign=top> \r\n      <p><a href=\\"<replace:CategoryURL>\\">All categories</a><br>\r\n      </p>\r\n\r\n      <p><a href=\\"<replace:ProgramURL>\\">All programs</a><br>\r\n      </p>\r\n      </td>\r\n  </tr>\r\n</table>');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (145,19,'WML_UPO_SCD','Site choise displaying.','<table border=\\"0\\" width=\\"500\\" cellspacing=\\"0\\" cellpadding=\\"5\\">\r\n  <tr> \r\n    <td width=\\"470\\" valign=\\"top\\"> \r\n      <table width=\\"100%\\" border=\\"0\\" cellspacing=\\"0\\" cellpadding=\\"0\\">\r\n        <replace:WML_UPO_SCD_SDB>\r\n      </table>\r\n      <p>&nbsp;</p>\r\n    </td>\r\n    <td width=\\"1\\" background=\\"../images/vline.gif\\">&nbsp;</td>\r\n    <td width=\\"150\\" valign=top> \r\n      <p><a href=\\"<replace:CategoryURL>\\">All categories</a><br>\r\n      </p>\r\n\r\n      <p><a href=\\"<replace:ProgramURL>\\">All programs</a><br>\r\n      </p>\r\n\r\n      <p><a href=\\"<replace:ProgramInfoURL>\\">Chosen program</a><br>\r\n      </p>\r\n      </td>\r\n  </tr>\r\n</table>');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (146,19,'WML_UPO_SCD_SDB','Site displaying block.','<option value=<replace:SiteID>><replace:SiteInfo>');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (147,19,'WML_UPO_CTD','Code type displaying.','<table border=\\"0\\" width=\\"500\\" cellspacing=\\"0\\" cellpadding=\\"5\\">\r\n  <tr> \r\n    <td width=\\"470\\" valign=\\"top\\"> \r\n      <table width=\\"100%\\" border=\\"0\\" cellspacing=\\"0\\" cellpadding=\\"0\\">\r\n        <replace:WML_UPO_CTD_TDB>\r\n      </table>\r\n      <p>&nbsp;</p>\r\n    </td>\r\n    <td width=\\"1\\" background=\\"../images/vline.gif\\">&nbsp;</td>\r\n    <td width=\\"150\\" valign=top> \r\n      <p><a href=\\"<replace:CategoryURL>\\">All categories</a><br>\r\n      </p>\r\n\r\n      <p><a href=\\"<replace:ProgramURL>\\">All programs</a><br>\r\n      </p>\r\n\r\n      <p><a href=\\"<replace:ProgramInfoURL>\\">Chosen program</a><br>\r\n      </p>\r\n      </td>\r\n  </tr>\r\n</table>');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (148,19,'WML_UPO_CTD_TDB','Code type displaying block.','<tr>\r\n<td><a href=\\"<replace:URL>\\"><replace:Name></a></td>\r\n<td><replace:Description></td>\r\n</tr>');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (149,19,'WML_UPO_GCD','Getting code displaying.','<table border=\\"0\\" width=\\"500\\" cellspacing=\\"0\\" cellpadding=\\"5\\">\r\n  <tr> <form name=CodeForm>\r\n    <td width=\\"470\\" valign=\\"top\\">\r\nOpen <br>\r\n						<select name=BannerType onchange=\\"select ();\\">\r\n						<option selected value=new>in new window&nbsp;<font size=\\"1\\" face=\\"Tahoma, Arial\\">\r\n						<option value=this>in current window&nbsp;<font size=\\"1\\" face=\\"Tahoma, Arial\\"></font>\r\n						</select>\r\n					<br><br>\r\nSite IDs:<br>\r\n<select name=SelectSiteID onChange=\\"select ();\\">\r\n<replace:WML_UPO_SCD_SDB>\r\n</select> \r\n      <br><b>Copy your chosed code:</b><br>\r\n<textarea rows=10 cols=40 name=CodeField><replace:Code></textarea><br>\r\n\r\n<BR><input type=button value=\\"Example\\" onClick=\\"openWin ()\\">\r\n      <p>&nbsp;</p>\r\n    </td>\r\n    <td width=\\"1\\" background=\\"../images/vline.gif\\">&nbsp;</td>\r\n    <td width=\\"150\\" valign=top> \r\n      <p><a href=\\"<replace:CategoryURL>\\">All categories</a><br>\r\n      </p>\r\n\r\n      <p><a href=\\"<replace:ProgramURL>\\">All programs</a><br>\r\n      </p>\r\n\r\n      <p><a href=\\"<replace:ProgramInfoURL>\\">Chosen program</a><br>\r\n      </p>\r\n\r\n      <p><a href=\\"<replace:CodeURL>\\">Codes</a><br>\r\n      </p>\r\n      </td>\r\n<replace:Hidden>\r\n</form>\r\n  </tr>\r\n</table>');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (150,20,'WML_UPP_PID','Program info displaying.','<table border=\\"0\\" width=\\"500\\" cellspacing=\\"0\\" cellpadding=\\"5\\">\r\n  <tr> \r\n    <td width=\\"470\\" valign=\\"top\\"> \r\n      <replace:Name><br>\r\n<replace:ShortInfo><br>\r\n<replace:Info><br>\r\n      <p>&nbsp;</p>\r\n    </td>\r\n    <td width=\\"1\\" background=\\"../images/vline.gif\\">&nbsp;</td>\r\n    <td width=\\"150\\" valign=top> \r\n      <p><a href=\\"<replace:CategoryURL>\\">All categories</a><br>\r\n      </p>\r\n\r\n      <p><a href=\\"<replace:ProgramURL>\\">All programs</a><br>\r\n      </p>\r\n      </td>\r\n  </tr>\r\n</table>');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (151,21,'WML_UPI_USS_NCV','Non correct values.','Non correct values.');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (152,21,'WML_UPI_IUR','Information updating result.','Your details have been updated succesfully.');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (153,21,'WML_UPI_USS','User super secure.','<table border=0 width=300>\r\n<tr>\r\n<td>\r\n<div class=\\"headline\\"><br>\r\n      </div>\r\n      <div class=\\"inhalt\\">\r\n<FORM action=\\"index.php\\" method=GET name=\\"FORM_2\\">\r\n		  <TABLE border=0 cellpadding=0 cellspacing=3 width=300 bgcolor=\\"#eaeaea\\">\r\n            <TR> \r\n              <TD class=\\"block1\\" valign=center>&nbsp;Username<br>\r\n              </TD>\r\n              <TD class=\\"block2\\"> \r\n                <INPUT id=wmFirma maxLength=15 name=UserName CLASS=\\"inputbox\\"  SIZE=20 value=\\"<replace:UserName>\\">\r\n              </td>\r\n            </tr>\r\n            <tr> \r\n              <TD class=\\"block1\\" valign=center>&nbsp;Password<br>\r\n              </TD>\r\n              <td class=\\"block2\\"> \r\n                <INPUT type=password maxLength=10 name=UserPswd   CLASS=\\"inputbox\\"  SIZE=20  >\r\n              </TD>\r\n            </TR>\r\n           \r\n            <TR> \r\n              <td colspan=1>&nbsp;</td>\r\n              <td class=\\"blockwhite\\" align=right> &nbsp;\r\n                <input type=submit name=\\"WML_UPI_USS\\" value=\\"Next\\">\r\n              </td>\r\n            </tr>\r\n          </TABLE>\r\n	<replace:Hidden>\r\n	</FORM>\r\n	</div>\r\n\r\n</td>\r\n</tr>\r\n</table>');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (154,21,'WML_UPI_UIU','User information updating.','<div class=\\"headline\\"> Personal info</div>\r\n	<div class=\\"inhalt\\">\r\n<FORM action=\\"index.php\\" method=GET>\r\n				<TABLE border=0 cellpadding=0 cellspacing=3 width=300 bgcolor=\\"#eaeaea\\">\r\n		\r\n		\r\n			\r\n	        <TR>\r\n	                <TD class=\\"block1\\" valign=center>&nbsp;Company<br></TD>\r\n	                <TD class=\\"block2\\">\r\n						<INPUT id=wmFirma maxLength=40 name=Company CLASS=\\"inputbox\\"  SIZE=35 value=\\"<replace:Company>\\"></td>\r\n					</tr>\r\n	        \r\n	        <TR>\r\n	                <TD class=\\"block1\\">&nbsp;First name</TD>\r\n	                <TD class=\\"block2\\"><INPUT id=wmVorname maxLength=30 name=FirstName  CLASS=\\"inputbox\\"  SIZE=35 value=\\"<replace:FirstName>\\" ></TD>\r\n	        </TR>\r\n	        \r\n	        <TR>\r\n	                <TD class=\\"block1\\">&nbsp;Name</TD>\r\n	                <TD class=\\"block2\\"><INPUT id=wmName maxLength=40 name=Name  CLASS=\\"inputbox\\"  SIZE=35  value=\\"<replace:Name>\\"></TD>\r\n\r\n	        </TR>\r\n	        \r\n	        <TR>\r\n	                <TD class=\\"block1\\">&nbsp;Street</td>\r\n	                <TD class=\\"block2\\"><INPUT id=wmStrasse maxLength=40 name=Street CLASS=\\"inputbox\\"  SIZE=35  value=\\"<replace:Street>\\"></td>\r\n	        </tr>\r\n	        \r\n	        <TR>\r\n	                <TD class=\\"block1\\">&nbsp;Country</td>\r\n	                <TD class=\\"block2\\"><INPUT id=wmStrasse maxLength=40 name=Country CLASS=\\"inputbox\\"  SIZE=35  value=\\"<replace:Country>\\"></td>\r\n	        </td>\r\n	        </tr>\r\n\r\n	        <TR>\r\n	                <TD class=\\"block1\\">&nbsp;Code</td>\r\n	                <TD class=\\"block2\\"><INPUT id=wmPLZ maxLength=5 name=ZipCode  size=5 CLASS=\\"inputbox\\"  value=\\"<replace:ZipCode>\\">&nbsp;<INPUT id=wmOrt maxLength=50 name=Location size=26 CLASS=\\"inputbox\\"  value=\\"<replace:Location>\\"></td>\r\n	        </tr>\r\n\r\n	        <TR>\r\n	                <TD class=\\"block1\\">&nbsp;Work Tel.</td>\r\n	                <TD class=\\"block2\\"><INPUT id=wmTelefon_tags maxLength=18 name=WorkPhone  CLASS=\\"inputbox\\"  SIZE=35  value=\\"<replace:WorkPhone>\\">\r\n	                </td>\r\n	                <tr>\r\n	                <TD class=\\"block1\\">&nbsp;Home Tel. </td>\r\n	                <td class=\\"block2\\"><INPUT id=wm0 maxLength=20 name=HomePhone size=\\"35\\" CLASS=\\"inputbox\\"   value=\\"<replace:HomePhone>\\"></td>\r\n	        </tr>\r\n\r\n	        <TR>\r\n	                <TD class=\\"block1\\">&nbsp;Mobile Tel.</td>\r\n	                <TD class=\\"block2\\"><INPUT id=wm1 maxLength=20 name=MobilePhone  CLASS=\\"inputbox\\"  SIZE=35  value=\\"<replace:MobilePhone>\\">\r\n	                </td><tr>\r\n	                <TD class=\\"block1\\">&nbsp;Fax</td>\r\n	                <td class=\\"block2\\">\r\n          <input type=\\"text\\" name=\\"Fax\\" value=\\"<replace:Fax>\\">\r\n        </td>\r\n\r\n	        </tr>\r\n	        <TR>\r\n	                <TD class=\\"block1\\">&nbsp;Email</td>\r\n	                <TD class=\\"block2\\"><INPUT id=wm4 maxLength=50 name=Email CLASS=\\"inputbox\\" SIZE=35  value=\\"<replace:Email>\\"></td>\r\n	        </tr>\r\n\r\n	        <TR>\r\n	                <TD class=\\"block1\\">&nbsp;Birth date&nbsp;&nbsp;&nbsp;</td>\r\n	                <TD class=\\"block2\\"><INPUT id=wmGebdatum_tag maxLength=2 name=BirthDay size=2 CLASS=\\"inputbox\\"  value=\\"<replace:BirthDay>\\">.<INPUT id=wmGebdatum_monat maxLength=2 name=BirthMonth size=2  CLASS=\\"inputbox\\"  value=\\"<replace:BirthMonth>\\">.<INPUT id=wmGebdatum_jahr maxLength=4 name=BirthYear size=4 CLASS=\\"inputbox\\"  value=\\"<replace:BirthYear>\\"> \r\n	                </td>\r\n	        </tr>\r\n	        <TR>\r\n	                <TD class=\\"block1\\"></td>\r\n	                <TD class=\\"block2\\"></td>\r\n	        </tr>	        \r\n	        <TR>\r\n	                <TD class=\\"block1\\">New user name</td>\r\n	                <TD class=\\"block2\\"><INPUT id=wm4 maxLength=50 name=UserName CLASS=\\"inputbox\\" SIZE=35  value=\\"<replace:UserName>\\"></td>\r\n	        </tr>	\r\n	        <TR>\r\n	                <TD class=\\"block1\\">New password</td>\r\n	                <TD class=\\"block2\\"><INPUT id=wm4 type=password maxLength=50 name=UserPswdA CLASS=\\"inputbox\\" SIZE=35  value=\\"\\"></td>\r\n	        </tr>\r\n	        <TR>\r\n	                <TD class=\\"block1\\">Retype password</td>\r\n	                <TD class=\\"block2\\"><INPUT id=wm4 type=password maxLength=50 name=UserPswdB CLASS=\\"inputbox\\" SIZE=35  value=\\"\\"></td>\r\n	        </tr>		\r\n	        <TR>\r\n					<td colspan=1>&nbsp;</td>\r\n	                \r\n        <td class=\\"blockwhite\\" align=right>&nbsp;<br>\r\n          <input type=submit name=\\"WML_UPI_UIU\\" value=\\"Update\\">\r\n        </td>\r\n	        </tr>\r\n	</TABLE>\r\n	<replace:Hidden>\r\n	</FORM>\r\n	</div>\r\n');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (155,21,'WML_UPI_UIUR','User Information Updating Result.','Your information has been updated succesfully.');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (156,16,'WML_UMTI','User money transfer information.','Last transfer date - <replace:LastTransferDate><br>\r\nLast transfer - <replace:LastTransferAmount><br>\r\nCurrently - <replace:CurrentAmount><br>');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (157,16,'WML_UMTI_UTE','User trasfer empty.','You have not got any transfer yet!<br>\r\nCurrently: <replace:CurrentAmount>');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (158,22,'LeftPanelLogged','Left paenl when admin is logged as user.','<table width=100% cellpadding=0 cellspacing=0 border=0>\r\n<td bgcolor=#E6E6E6 width=100% >\r\n<table width=100% cellpadding=2 cellspacing=1 border=0>\r\n<td height=20 bgcolor=#E6E6E6 valign=top align=left> </td><tr>\r\n<td height=20 bgcolor=#006699 valign=top align=left><a href=index.php?ActionGroup=UPI><font color=white>Personal info</font></a></td><tr>\r\n<td height=20 bgcolor=#006699 valign=top align=left><a href=index.php?ActionGroup=UBAI><font color=white>Bank account info</font></a></td><tr>\r\n<td height=20 bgcolor=#006699 valign=top align=left><a href=index.php?ActionGroup=UMTI><font color=white>Payment info</font></a></td><tr>\r\n<td height=20 bgcolor=#006699 valign=top align=left><a href=index.php?ActionGroup=USO><font color=white>Your sites</font></a></td><tr>\r\n<td height=20 bgcolor=#006699 valign=top align=left><a href=index.php?ActionGroup=UPO><font color=white>Angebote</font></a></td><tr>\r\n<td height=20 bgcolor=#E6E6E6 valign=top align=left> </td><tr>\r\n<td height=20 bgcolor=#006699 valign=top align=left><a href=../index.php><font color=white>Logout</font></a></td>\r\n</table>\r\n</td>\r\n</table>');
INSERT INTO Template (ID, GroupID, Name, Description, Body) VALUES (159,19,'WML_UPO_SCD_SLE','Site list empty.','<table border=\\"0\\" width=\\"500\\" cellspacing=\\"0\\" cellpadding=\\"5\\">\r\n  <tr> \r\n    <td width=\\"470\\" valign=\\"top\\"> \r\n      At first add some sites!\r\n      <p>&nbsp;</p>\r\n    </td>\r\n    <td width=\\"1\\" background=\\"../images/vline.gif\\">&nbsp;</td>\r\n    <td width=\\"150\\" valign=top> \r\n      <p><a href=\\"<replace:CategoryURL>\\">All categories</a><br>\r\n      </p>\r\n\r\n      </td>\r\n  </tr>\r\n</table>');
# --------------------------------------------------------

#
# Table structure for table User
#

DROP TABLE IF EXISTS User;
CREATE TABLE User (
   ID int(4) DEFAULT '0' NOT NULL auto_increment,
   UserName varchar(100) NOT NULL,
   Password varchar(10) NOT NULL,
   FirstName varchar(100) NOT NULL,
   Name varchar(100) NOT NULL,
   Email varchar(100) NOT NULL,
   Company varchar(100) NOT NULL,
   Country varchar(100) NOT NULL,
   Location varchar(100) NOT NULL,
   WorkPhone varchar(50) NOT NULL,
   HomePhone varchar(50) NOT NULL,
   MobilePhone varchar(50) NOT NULL,
   Fax varchar(50) NOT NULL,
   BirthDate date DEFAULT '0000-00-00' NOT NULL,
   City varchar(100) NOT NULL,
   Street varchar(100) NOT NULL,
   ZipCode varchar(15) NOT NULL,
   Status enum('0','1') DEFAULT '1' NOT NULL,
   RegDate date DEFAULT '0000-00-00' NOT NULL,
   MwStSelf enum('1','0') DEFAULT '1' NOT NULL,
   PRIMARY KEY (ID),
   UNIQUE UserName (UserName),
   UNIQUE Email (Email)
);

#
# Dumping data for table User
#

# --------------------------------------------------------

#
# Table structure for table UserSite
#

DROP TABLE IF EXISTS UserSite;
CREATE TABLE UserSite (
   ID int(11) DEFAULT '0' NOT NULL auto_increment,
   UserID int(10) unsigned DEFAULT '0' NOT NULL,
   Title varchar(255) NOT NULL,
   Description varchar(255) NOT NULL,
   URL varchar(255) NOT NULL,
   RefID varchar(255) NOT NULL,
   PRIMARY KEY (ID)
);

#
# Dumping data for table UserSite
#

# --------------------------------------------------------

#
# Table structure for table bankaccount
#

DROP TABLE IF EXISTS bankaccount;
CREATE TABLE bankaccount (
   ID int(10) unsigned DEFAULT '0' NOT NULL auto_increment,
   UserID int(10) unsigned DEFAULT '0' NOT NULL,
   AccountHolder varchar(100) NOT NULL,
   AccountNumber varchar(100) NOT NULL,
   BankCode varchar(100) NOT NULL,
   BankName varchar(100) NOT NULL,
   SWIFT varchar(100) NOT NULL,
   BankStreet varchar(100) NOT NULL,
   BankCity varchar(100) NOT NULL,
   BankCountry varchar(100) NOT NULL,
   PRIMARY KEY (ID)
);

#
# Dumping data for table bankaccount
#

# --------------------------------------------------------

#
# Table structure for table moneytransfer
#

DROP TABLE IF EXISTS moneytransfer;
CREATE TABLE moneytransfer (
   ID int(10) unsigned DEFAULT '0' NOT NULL auto_increment,
   UserID int(10) unsigned DEFAULT '0' NOT NULL,
   LastTransfer float(10,2) DEFAULT '0.00' NOT NULL,
   CurrentAmount float(10,2) DEFAULT '0.00' NOT NULL,
   LastTransferDate date DEFAULT '0000-00-00' NOT NULL,
   PRIMARY KEY (ID)
);

#
# Dumping data for table moneytransfer
#

# --------------------------------------------------------

#
# Table structure for table templategroup
#

DROP TABLE IF EXISTS templategroup;
CREATE TABLE templategroup (
   ID tinyint(4) DEFAULT '0' NOT NULL auto_increment,
   Name varchar(100) NOT NULL,
   Description varchar(255) NOT NULL,
   PRIMARY KEY (ID)
);

#
# Dumping data for table templategroup
#

INSERT INTO templategroup (ID, Name, Description) VALUES (1,'Base','Group of templates for base user page structure including corresponding actions');
INSERT INTO templategroup (ID, Name, Description) VALUES (5,'Login','A template for logging process');
INSERT INTO templategroup (ID, Name, Description) VALUES (18,'USO','User site operations.');
INSERT INTO templategroup (ID, Name, Description) VALUES (10,'Recovery','Password recovery group');
INSERT INTO templategroup (ID, Name, Description) VALUES (12,'AdminUsers','Templates corresponding to adminitering of user accounts');
INSERT INTO templategroup (ID, Name, Description) VALUES (13,'Message','Describes warning and error messages');
INSERT INTO templategroup (ID, Name, Description) VALUES (15,'UBAI','User bank account information.');
INSERT INTO templategroup (ID, Name, Description) VALUES (16,'UMTI','User money transfer information.');
INSERT INTO templategroup (ID, Name, Description) VALUES (17,'URP','User registration process.');
INSERT INTO templategroup (ID, Name, Description) VALUES (19,'UPO','User program operations.');
INSERT INTO templategroup (ID, Name, Description) VALUES (20,'UPP','User program preview.');
INSERT INTO templategroup (ID, Name, Description) VALUES (21,'UPI','User personal info.');
INSERT INTO templategroup (ID, Name, Description) VALUES (22,'AdminBase','Admin base.');
# --------------------------------------------------------

#
# Table structure for table useraccount
#

DROP TABLE IF EXISTS useraccount;
CREATE TABLE useraccount (
   ID int(10) unsigned DEFAULT '0' NOT NULL auto_increment,
   UserID int(10) unsigned DEFAULT '0' NOT NULL,
   AccountHolder varchar(100) NOT NULL,
   AccountNumber varchar(100) NOT NULL,
   BankCode varchar(100) NOT NULL,
   BankName varchar(100) NOT NULL,
   SWIFT varchar(100) NOT NULL,
   BankStreet varchar(100) NOT NULL,
   BankCity varchar(100) NOT NULL,
   BankCountry varchar(100) NOT NULL,
   PRIMARY KEY (ID)
);

#
# Dumping data for table useraccount
#

