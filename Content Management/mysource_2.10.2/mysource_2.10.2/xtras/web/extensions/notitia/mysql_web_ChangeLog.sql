    ##############################################
   ### MySource ------------------------------###
  ##- Notitia   Module -------- MySQL --------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/xtras/web/extensions/notitia/mysql_web_ChangeLog.sql,v $
## $Revision: 1.6 $
## $Author: achadszinow $
## $Date: 2004/03/22 04:21:57 $
#######################################################################

#---------------------------------------------------------------------#

v 1.8.26 - 1.8.27
CREATE TABLE xtra_web_extension_notitia_attribute_role_auto_increment (
        attributeid INT UNSIGNED NOT NULL,
        value       DOUBLE       NOT NULL,
        PRIMARY KEY(attributeid)
);

v 1.7.7 - 1.7.9
UPGRADE SCRIPT NEEDS TO BE RUN

 ##############################################################
# This is basically the value of a number attribute only as it applies to
# a particular record
CREATE TABLE xtra_web_extension_notitia_attribute_value_number (
        attributeid INT UNSIGNED NOT NULL,
        recordid   INT UNSIGNED NOT NULL,
        value       DOUBLE PRECISION NOT NULL,
        PRIMARY KEY(attributeid,recordid),
        KEY(recordid)
);

 ##############################################################
# This stores the default value for number attributes only at a certain 
# category context. This saves us remembering a value for each
# record if it doesn't need to change.
CREATE TABLE xtra_web_extension_notitia_attribute_default_number (
        attributeid  INT UNSIGNED NOT NULL,
        categoryid   INT UNSIGNED NOT NULL,
        inherit_type VARCHAR(255) NOT NULL, # The variation type, for connecting this value with the defaults of its parents
        sibling_type VARCHAR(255) NOT NULL, # For connection this value with the values of its siblings.
        value        DOUBLE PRECISION NOT NULL,
        PRIMARY KEY(attributeid,categoryid),
        KEY(categoryid)
);


v 1.5.5 - 1.6.0

 ##############################################################
# Why another? So values aren't all stored in one table. There's a role value_table to assign this
CREATE TABLE xtra_web_extension_notitia_attribute_value2 (
        attributeid INT UNSIGNED NOT NULL,
        recordid   INT UNSIGNED NOT NULL,
        value       TEXT         NOT NULL,
        PRIMARY KEY(attributeid,recordid),
        KEY(recordid)
);

 ##############################################################
# Why another? Same as above
CREATE TABLE xtra_web_extension_notitia_attribute_value3 (
        attributeid INT UNSIGNED NOT NULL,
        recordid   INT UNSIGNED NOT NULL,
        value       TEXT         NOT NULL,
        PRIMARY KEY(attributeid,recordid),
        KEY(recordid)
);

v 1.4.17 - 1.5.0

 ###############################################################
# Each attribute type can have xtras. In the case of equation
# attribute type they have xtras which are operators
CREATE TABLE xtra_web_extension_notitia_attribute_xtra (
        attributeid INT UNSIGNED NOT NULL,
        xtraid  INT UNSIGNED NOT NULL AUTO_INCREMENT,
        type        VARCHAR(128), # There are different types of sub classes
        parameters  LONGTEXT,     # Definition of the subtype
        order_no    INT UNSIGNED NOT NULL DEFAULT 0, # Order this subtype can have (only needed if the attribute wants to use it for it's subtypes)
        PRIMARY KEY(xtraid),
        KEY(attributeid),
        KEY(order_no)
);