CREATE TABLE be_trackback2article (
   trackbackID smallint(5) unsigned not null auto_increment,
   articleID smallint(5) unsigned not null,
   languageID char(3) not null default '',
   dateSubmitted int(10) unsigned not null default 0,
   title varchar(255) not null default '',
   excerpt varchar(255) not null default '',
   url varchar(255) not null default '',
   blog_name varchar(255) not null default '',
   primary key (trackbackID),
   key articleID(articleID),
   KEY languageID(languageID)
) type=MyISAM auto_increment=1;
   
