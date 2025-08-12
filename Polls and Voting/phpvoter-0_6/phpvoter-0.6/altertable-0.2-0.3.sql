ALTER TABLE Answer CHANGE COLUMN intAnswers intAnswers int(11) default '0';
ALTER TABLE Question CHANGE COLUMN blnActive intState tinyint(4) default '3';
ALTER TABLE Question ADD varComment varchar(255) default NULL;
ALTER TABLE Question ADD dtePublish date default NULL;
ALTER TABLE Voted ADD intAnswer int(11) default NULL;
