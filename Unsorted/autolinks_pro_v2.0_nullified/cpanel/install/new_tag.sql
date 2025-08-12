CREATE TABLE al_tag (
  id smallint(5) unsigned NOT NULL auto_increment,
  name varchar(50) NOT NULL default '',
  type varchar(10) NOT NULL default '',
  orderby varchar(10) NOT NULL default '',
  category tinyint(3) unsigned NOT NULL default '0',
  numlinks smallint(5) unsigned NOT NULL default '0',
  position smallint(5) unsigned NOT NULL default '0',
  minhits tinyint(3) unsigned NOT NULL default '0',
  numcolumns tinyint(3) unsigned NOT NULL default '0',
  padding tinyint(3) unsigned NOT NULL default '0',
  align varchar(10) NOT NULL default '',
  cssclass varchar(20) NOT NULL default '',
  fontsize varchar(5) NOT NULL default '',
  fonttype varchar(50) NOT NULL default '',
  showdesc tinyint(4) NOT NULL default '0',
  mouseover tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

INSERT INTO al_tag VALUES (1, 'Newest Links', 'text', 'added', '0', 15, 0, '0', '1', '0', 'center', '', '', '', '0', '1');
INSERT INTO al_tag VALUES (2, 'Top Referrers', 'text', 'hitsin', '0', 15, 0, '1', '1', '0', 'center', '', '', '', '0', '1');
INSERT INTO al_tag VALUES (3, 'Top Referrers with Desc.', 'text', 'hitsin', '0', 15, 0, '1', '1', '0', 'left', '', '', '', '1', '1');
INSERT INTO al_tag VALUES (4, 'Random Banner', 'banner', 'random', '0', 1, 0, '0', '1', '5', 'center', '', '1', '', '1', '1');
INSERT INTO al_tag VALUES (5, 'Top Quality', 'text', 'clicks', '0', 15, 0, '1', '1', '0', 'center', '', '', '', '0', '1');
INSERT INTO al_tag VALUES (6, 'All Links in 4 Columns', 'text', 'name', '0', 999, 0, '0', '4', '0', 'center', '', '1', '', '0', '1');
INSERT INTO al_tag VALUES (7, '8 Thumbs in 4 Columns', 'thumb', 'random', '0', 8, 0, '0', '4', '5', 'center', '', '1', '', '1', '1');
INSERT INTO al_tag VALUES (8, 'Link of the Moment', 'text', 'random', '0', 1, 0, '10', '1', '0', 'center', '', '', '', '0', '1');
