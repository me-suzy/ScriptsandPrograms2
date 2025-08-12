# alter-0.8.sql
#
# updates pre 0.8 bookmarker database for use in bookmarker 0.8
#
# run this file against a current bookmarker installation
# using something like:
#   mysql bookmarks <alter-0.8.sql
# where bookmarks is the name of the local bookmarker db.
#

#
# add username to code tables
#
alter table category add username varchar(32) not null;
alter table subcategory add username varchar(32) not null;
alter table rating add username varchar(32) not null;

#
# add the list_tag field to the rating table.
# for future use.
alter table rating add list_tag varchar(255);

#
# update the sql below to default the existing code
# table rows to a username.
# YOU MUST DO THIS. CHANGE bk to your username!
#
update category set username = 'bk' where username ='';
update subcategory set username = 'bk' where username ='';
update rating set username = 'bk' where username ='';

#
# change the primary keys on the code tables
#
alter table category drop primary key;
alter table category add primary key (id, username);
alter table subcategory drop primary key;
alter table subcategory add primary key (id, username);
alter table rating drop primary key;
alter table rating add primary key (id, username);

#
# add an additional index to the bookmark table
# to improve performance.
alter table bookmark add index username (username);
