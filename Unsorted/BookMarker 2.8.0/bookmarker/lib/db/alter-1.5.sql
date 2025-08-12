# alter-1.5.sql
#
# updates 1.4 bookmarker database for use in bookmarker 1.5
#
# run this file against a current bookmarker installation
# using something like:
#   mysql bookmarks <alter-1.5.sql
# where bookmarks is the name of the local bookmarker db.
#

#
# add keyword field to bookmark records
#
# note: some older versions of MySQL may not support the 'after'
#       syntax. feel free to remove the 'after ldesc' if you have
#       problems applying the alter.
alter table bookmark add column keywords varchar(255) after ldesc;

#
# Table structure for table 'search'
#
CREATE TABLE search (
  id tinyint(3) unsigned DEFAULT '0' NOT NULL auto_increment,
  name varchar(30) DEFAULT '' NOT NULL,
  query varchar(255) DEFAULT '' NOT NULL,
  username varchar(32) DEFAULT '' NOT NULL,
  PRIMARY KEY (id,username)
);
