#
# Table structure for table 'Question'
#

CREATE TABLE Question (
  intState tinyint(4) default 3,
  varQuestion varchar(255) default NULL,
  dteDate datetime default NULL,
  varComment varchar(255) default NULL,
  dtePublish date default NULL,
  ID int(11) NOT NULL auto_increment,
  PRIMARY KEY  (ID)
);

#
# Table structure for table 'Answer'
#

CREATE TABLE Answer (
  question_ID int(11) default NULL,
  varChoice varchar(255) default NULL,
  intAnswers int(11) default 0,
  ID int(11) NOT NULL auto_increment,
  PRIMARY KEY  (ID)
);

#
# Table structure for table 'Voted'
#

CREATE TABLE Voted (
  question_ID int(11) default NULL,
  varIP varchar(255) default NULL,
  intAnswer int(11) default NULL,
  ID int(11) NOT NULL auto_increment,
  PRIMARY KEY  (ID)
);

