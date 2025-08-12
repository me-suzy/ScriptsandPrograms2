

CREATE TABLE IF NOT EXISTS ###scheme###.dev_###name### (
  ###name###_id    int(11)     NOT NULL auto_increment,
  mandator         int(11)     NOT NULL default 1,
  parent           int(11)     NOT NULL default 0,
  is_dir           char(1)     NOT NULL default '0',
  name             varchar(50),
  description      text,
  PRIMARY KEY  (mandator, ###name###_id)
) TYPE=MyISAM;
