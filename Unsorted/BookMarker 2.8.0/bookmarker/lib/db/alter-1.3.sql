# update-1.3.sql
#
# updates 1.3 bookmarker password to default password
#
# run this file against a current bookmarker installation
# using something like:
#   mysql bookmarks <update-1.3.sql
# where bookmarks is the name of the local bookmarker db.
#

#
# change the username value below ('bk') to be the username
# of your administrator user. this will set the password for
# that user to 'bk'. this will allow you to login to bookmarker
# version 1.3 under this username with password 'bk' so that
# you can change the passwords for all users. bookmarker version
# 1.3 uses md5 encryption for password storage.
#

update auth_user set password = '7e7ec59d1f4b21021577ff562dc3d48b ' where username = 'bk';
