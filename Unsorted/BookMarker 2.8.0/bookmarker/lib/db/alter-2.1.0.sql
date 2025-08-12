#
# this script will alter a bookmarker 2.0.0 database
# for bookmarker 2.1.0
#
# run this file against a current bookmarker installation
# using something like:
#   mysql bookmarks <alter-2.1.0.sql
# where bookmarks is the name of the local bookmarker db.
#
# rename the public field since it is a reserved word in
# the postgres database.
alter table bookmark change public public_f char(1);
