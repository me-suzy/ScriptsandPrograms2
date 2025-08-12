#
# this script will alter a bookmarker 2.0.0 database
# with a security fix for bookmarker 2.0.2
#
# run this file against a current bookmarker installation
# using something like:
#   mysql bookmarks <alter-2.0.2.sql
# where bookmarks is the name of the local bookmarker db.
#
# update default perm auth cookies to all users
# so that no user has the same cookie value!
update auth_user set perm_auth_cookie = concat(reverse(left(uid, 12)), reverse(right(password, 10)));
