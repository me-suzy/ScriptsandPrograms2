<?php
#############################################################################
# myAgenda v1.1																#
# =============																#
# Copyright (C) 2002  Mesut Tunga - mesut@tunga.com							#
# http://php.tunga.com														#
#																			#
# This program is free software. You can redistribute it and/or modify		#
# it under the terms of the GNU General Public License as published by 		#
# the Free Software Foundation; either version 2 of the License.       		#
#############################################################################

# MySql authentication variables
$sql_host = "localhost";
$sql_db = "agenda";
$sql_user = "agenda";
$sql_pass = "agenda";

# Authentication user/pass for sr.php. Use same values on your cron entry
$auth_us = "agenda";
$auth_ps = "agenda";

# The name of this application
$MyAgenda_name = "myAgenda";

# The URL to your myAgenda installation
$myAgenda_url = "http://yourdomain.com";

# Local mode myAgenda directory
$myAgenda_server_path = "/system/path/to/myagenda/";

# Admin's email address (used to set the FROM-address when sending email)
$myAgenda_admin_email = "reminder@yourdomain.com";

# Your language file
$myAgenda_language = "english";

# Week starts with monday. else set it to 0
$monday = 1;

# MyAgenda database tables
# If you change these names, don't forget to change table names in myAgenda.sql
$myAgenda_tbl_reminders = "myagenda_reminders";
$myAgenda_tbl_users = "myagenda_users";

# Mail Headers for sending email
$myAgenda_email_from = "From: $myAgenda_name <$admin_email> \n";

# If your server time is different then your country 
# use this variable like this : time() + (3600*2)
# 2 is the hour
$TimeOffSet = time();

# Users time out 
$TimeOut = 1800; // As seconds

# Pages bgcolor
$bg_color = "#DADADA";

# Pages Character Set
$CharSet = "ISO-8859-9";
?>