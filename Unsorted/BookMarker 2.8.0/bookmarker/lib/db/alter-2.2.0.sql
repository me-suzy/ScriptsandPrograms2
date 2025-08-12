#
# this script will alter a bookmarker 2.1.0 database
# for bookmarker 2.2.0
#
# run this file against a current bookmarker installation
# using something like:
#   mysql bookmarks <alter-2.2.0.sql
# where bookmarks is the name of the local bookmarker db.
#
# PHPLIB 7.2b change: rename the uid field to user_id
alter table auth_user change uid user_id varchar(32) DEFAULT '' NOT NULL;

# PHPLIB table to support generic auto increment like
# key assignment.
CREATE TABLE db_sequence (
  seq_name varchar(30)    DEFAULT '' NOT NULL,
  nextid int(10) unsigned DEFAULT '0' NOT NULL,
  PRIMARY KEY (seq_name)
);

INSERT INTO db_sequence (seq_name, nextid) VALUES ('bookmark', 0);
INSERT INTO db_sequence (seq_name, nextid) VALUES ('search'  , 0);

# remove the auto_increment feature from the key fields
# since we now use the above generic PHPLIB db_sequence method.
alter table bookmark change id id int(10) unsigned NOT NULL;
alter table search   change id id int(10) unsigned NOT NULL;
