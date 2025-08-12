CREATE TABLE mod_newspost ( id varchar(50) default NULL, news_date int(20) unsigned default NULL, news_headline varchar(50) default NULL, news_author varchar(50) default NULL, news_body text
) TYPE=MyISAM;
CREATE TABLE mod_newspost_config ( news_per_page int(3) default NULL, preview_length int(3) default NULL
) TYPE=MyISAM;
INSERT INTO mod_newspost_config VALUES("4", "200");