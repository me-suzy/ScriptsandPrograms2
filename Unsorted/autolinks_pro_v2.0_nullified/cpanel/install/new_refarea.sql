CREATE TABLE al_refarea (
  id tinyint(3) unsigned NOT NULL auto_increment,
  header text NOT NULL,
  footer text NOT NULL,
  reginfo text NOT NULL,
  mainfontface varchar(100) NOT NULL default '',
  mainfontsize varchar(7) NOT NULL default '',
  mainfontcol varchar(7) NOT NULL default '',
  headerfontcol varchar(7) NOT NULL default '',
  highlightfontcol varchar(7) NOT NULL default '',
  formfrontcol varchar(7) NOT NULL default '',
  formbackcol varchar(7) NOT NULL default '',
  bodybackcol varchar(7) NOT NULL default '',
  areabackcol varchar(7) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

INSERT INTO al_refarea VALUES ('10', '<table width="520" border="0" cellspacing="0" cellpadding="0" align="center">\r\n  <tr>\r\n    <td height="34" valign="top"><font size="5">REFERRERS AREA</font></td>\r\n  </tr>\r\n</table>\r\n<table width="520" cellpadding="10" cellspacing="0" class="area" align="center">\r\n  <tr>\r\n    <td>', '    </td>\r\n  </tr>\r\n</table>', 'All links exchange on this site are automated. That means that if you want to get a link from this site, you need to sign up for an account and link us using the given code. It\'s very easy, the signup takes 2 seconds. Another advantage is that you can link all our sites with a single account. Just fill the form below!', 'Verdana, Arial, Helvetica, sans-serif', '12px', 'black', 'white', 'red', '#9999CC', '#F5F5F5', '#9999CC', '#D7D7D7');
