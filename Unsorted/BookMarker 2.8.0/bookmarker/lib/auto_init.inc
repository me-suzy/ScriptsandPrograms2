<?php
# ---------------------------------------------------------------
# bookmarker
# A WWW based bookmark management, retrieval, and search tool.
# Copyright (C) 1998  Padraic Renaghan
# Licensed under terms of GNU General Public License
# (see http://www.renaghan.com/bookmarker/source/LICENSE)
# ---------------------------------------------------------------
# $Id: auto_init.inc,v 1.4 2000/10/08 22:47:05 prenagha Exp $
# ---------------------------------------------------------------
# make all sess and user variables global so they
# are available in other scripts immediately.
global $sess_msg, $sess_warn_msg, $sess_error_msg;

# this file is executed ONCE at the startup of each session.
# we use this file to register session variables once, so
# that they are easily available to all pages.

# these session variables allow us to pass error, warning
# and information messages between pages
$sess->register("sess_msg");
$sess->register("sess_warn_msg");
$sess->register("sess_error_msg");
?>
