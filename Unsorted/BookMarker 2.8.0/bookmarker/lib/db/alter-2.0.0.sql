#
# this script will alter a bookmarker 1.6 database
# with new fields needed in bookmarker 2.0.0
#
# run this file against a current bookmarker installation
# using something like:
#   mysql bookmarks <alter-2.0.0.sql
# where bookmarks is the name of the local bookmarker db.
#
alter table auth_user add default_public char(1) DEFAULT 'N' NOT NULL;
alter table auth_user add include_public char(1) DEFAULT 'Y' NOT NULL;
alter table auth_user add total_public_bookmarks int(6) DEFAULT 0 NOT NULL;
alter table auth_user add perm_auth_cookie varchar(32) DEFAULT '0' NOT NULL;
alter table bookmark add added date DEFAULT '1999-01-01' NOT NULL;
alter table bookmark add public char(1) DEFAULT 'N' NOT NULL;

# add default perm auth cookies to all users
update auth_user set perm_auth_cookie = concat(reverse(left(uid, 12)), reverse(right(password, 10)));

# make the perm auth cookie field a unique key
alter table auth_user add unique perm_auth_idx (perm_auth_cookie);

# set the added date to all existing bookmarks to 01/01/1999
update bookmark set added = '1999-01-01';

# set all users to see public bookmarks
update auth_user set include_public = 'Y';

#### YOU MAY WANT TO CHANGE THE BELOW UPDATES.
#### I HAVE SET THEM TO BE AS CONSERVATIVE AS 
#### POSSIBLE (I.E., EVERYTHING IS PRIVATE)
update auth_user set default_public = 'N';
update bookmark set public = 'N';
