# alter-1.1.sql
#
# updates 1.0 bookmarker database for use in bookmarker 1.1
#
# run this file against a current bookmarker installation
# using something like:
#   mysql bookmarks <alter-1.1.sql
# where bookmarks is the name of the local bookmarker db.
#

#
# add fields to the user table for use by the
# mail-this-link page
#
alter table auth_user add name  varchar(50) not null;
alter table auth_user add email varchar(50) not null;
