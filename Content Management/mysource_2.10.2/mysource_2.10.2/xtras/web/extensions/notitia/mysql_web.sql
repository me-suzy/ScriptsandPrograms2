    ##############################################
   ### MySource ------------------------------###
  ##- Notitia   Module -------- MySQL --------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/xtras/web/extensions/notitia/mysql_web.sql,v $
## $Revision: 1.7 $
## $Author: achadszinow $
## $Date: 2004/03/22 04:21:57 $
#######################################################################

#---------------------------------------------------------------------#

 ####################
#      RECORDS       #
 ####################


 #########################################
# A record is something what gets solded
CREATE TABLE xtra_web_extension_notitia_record (
        recordid  INT          UNSIGNED NOT NULL AUTO_INCREMENT,
        #short_name VARCHAR(128) NOT NULL, # Generated from the value of an attribute (as defined by the user)
        #name       VARCHAR(255) NOT NULL, # As above, but more descriptive.
        PRIMARY KEY(recordid)
);


 #########################################
# A category is simply a way of sorting 
# records into groups
CREATE TABLE xtra_web_extension_notitia_category (
        categoryid  INT          UNSIGNED NOT NULL AUTO_INCREMENT,
        parentid    INT          UNSIGNED NOT NULL DEFAULT 0, # Is this the subcategory of some other category?
        siteid      MEDIUMINT    UNSIGNED NOT NULL DEFAULT 0, # Is this category restricted to a particulr site?
        name        VARCHAR(255) NOT NULL DEFAULT '',
        description TEXT         NOT NULL DEFAULT '',
        order_no    INT          UNSIGNED NOT NULL DEFAULT 0,
        parameters  TEXT         NOT NULL,
        PRIMARY KEY(categoryid),
        KEY(siteid),
        UNIQUE(name,categoryid,siteid),
        KEY(order_no)
);


 ##########################################
# Linking the record and category tables
# together. At least one record should exist
# for each record. You can't have a record without
# it being in at least one category.
CREATE TABLE xtra_web_extension_notitia_record_to_category (
        recordid  INT UNSIGNED NOT NULL,
        categoryid INT UNSIGNED NOT NULL,
        PRIMARY KEY(recordid,categoryid),
        KEY(categoryid)
);


 ############################################################
# If categoryid = 0 AND recordid = 0, the attribute applies to all records in the system
# If recordid = 0 AND categoryid > 0, the attribute applies to all records in the category/sys
# If recordid > 0 AND categoryid > 0 the attribute applies to the record 
# only in the context of that particular category.
# If recordid > 0 AND categoryid = 0 the attribute applies to the record in all contexts
CREATE TABLE xtra_web_extension_notitia_attribute (
        attributeid INT UNSIGNED NOT NULL AUTO_INCREMENT,
        categoryid  INT UNSIGNED NOT NULL,
        recordid   INT UNSIGNED NOT NULL,
        type        VARCHAR(128), # There are different types of attributes.. classname
        name        VARCHAR(128), 
        parameters  LONGTEXT,     # Definition of the attribute
        order_no    INT UNSIGNED NOT NULL DEFAULT 0, # Order this atribute appears in relation to others in its context
        PRIMARY KEY(attributeid),
        UNIQUE(recordid,categoryid,name),
        KEY(categoryid),
        KEY(order_no)
);


 ###########################################################
# Role get assigned to attributes. A role is identified by
# a unique old [A-Za-z0-9]+ string. This is used for things
# like "name". Then that attribute can be treated as the name
# of the record. Its up to the code to prevent duplicates
# of role allocations that should be unique and things like
# that.
CREATE TABLE xtra_web_extension_notitia_attribute_role (
        attributeid INT UNSIGNED NOT NULL,
        role        VARCHAR(119) NOT NULL,
        parameters  TEXT NOT NULL,
        PRIMARY KEY(role,attributeid),
        KEY(attributeid)
);

 ##############################################################
# This is basically the value of an attribute as it applies to
# a particular record
CREATE TABLE xtra_web_extension_notitia_attribute_value (
        attributeid INT UNSIGNED NOT NULL,
        recordid   INT UNSIGNED NOT NULL,
        value       TEXT         NOT NULL,
        PRIMARY KEY(attributeid,recordid),
        KEY(recordid)
);

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
# This stores the default value for an attribute at a certain 
# category context. This saves us remembering a value for each
# record if it doesn't need to change.
CREATE TABLE xtra_web_extension_notitia_attribute_default (
        attributeid  INT UNSIGNED NOT NULL,
        categoryid   INT UNSIGNED NOT NULL,
        inherit_type VARCHAR(255) NOT NULL, # The variation type, for connecting this value with the defaults of its parents
        sibling_type VARCHAR(255) NOT NULL, # For connection this value with the values of its siblings.
        value        TEXT         NOT NULL,
        PRIMARY KEY(attributeid,categoryid),
        KEY(categoryid)
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

 ##############################################################
# Okay, this is another attempt at representing varieties.
# A variety set says something like. "You may make variations
# to Attribute A and Attribute B for each combination of options
# defined in Attribute C and attribute D." Or something, you know? :)
# Attributes C and D have to be of a special type which can define 
# varieties.
CREATE TABLE xtra_web_extension_notitia_variety_set (
        variety_setid INT UNSIGNED NOT NULL AUTO_INCREMENT,
        categoryid    INT UNSIGNED NOT NULL, # Once again the categoryid and the recordid define
        recordid     INT UNSIGNED NOT NULL, # the context in which this variety set applies
        PRIMARY KEY(variety_setid),
        KEY(categoryid,recordid)
);
# Attributes that contribute variety options to a variety set
CREATE TABLE xtra_web_extension_notitia_variety_set_option_attribute (
        variety_setid INT UNSIGNED NOT NULL,
        attributeid   INT UNSIGNED NOT NULL,
        PRIMARY KEY(variety_setid,attributeid)
);
# Attributes that can be varied based on a variety set
CREATE TABLE xtra_web_extension_notitia_variety_set_varied_attribute (
        variety_setid INT UNSIGNED NOT NULL,
        attributeid   INT UNSIGNED NOT NULL,
        PRIMARY KEY(variety_setid,attributeid)
);


 #######################################################
# This allows varieties to make *gasp* variations on the
# attributes of their records.
CREATE TABLE xtra_web_extension_notitia_variation (
        recordid     INT UNSIGNED NOT NULL, # Which record is having its atribute varied?
        attributeid   INT UNSIGNED NOT NULL, # Which attribute of the record is being varied?
        variety_setid INT UNSIGNED NOT NULL, # Which variety set is this a variation for?
        coord         VARCHAR(127) NOT NULL, # ",3:5,34:64,8:23" - Means this is the value for
                                                                                 # the 3d matrix coord, the 5th option of the option attribute
                                                                                 # 3, the 64th option of the option attribute 34 etc...
        type          VARCHAR(255) NOT NULL, # How is this variation being applied? (e.g. adding, appending, percentage etc)
        value         TEXT NOT NULL,
        PRIMARY KEY(recordid,attributeid,variety_setid,coord),
        KEY(attributeid,variety_setid,coord),
        KEY(variety_setid,coord)
);


 ###############################################################
# This allows varieties to make *gasp* default variations on the
# attributes of the records within the category
CREATE TABLE xtra_web_extension_notitia_variation_default (
        categoryid     INT UNSIGNED NOT NULL, # Which category is having its attributes varied
        attributeid   INT UNSIGNED NOT NULL, # Which attribute of the record is being varied?
        variety_setid INT UNSIGNED NOT NULL, # Which variety set is this a variation for?
        coord         VARCHAR(127) NOT NULL, # see above table
        type          VARCHAR(255) NOT NULL, # How is this variation being applied? (e.g. adding, appending, percentage etc)
        value         TEXT NOT NULL,
        PRIMARY KEY(categoryid,attributeid,variety_setid,coord),
        KEY(attributeid,variety_setid,coord),
        KEY(variety_setid,coord)
);


 ####################
#      SECURITY      #
 ####################

 #######################################################################
# Grants access to a category.. there are a number of types of grants:
#
# These are for backend management only.
# V - View Contents (Records, Attributes, Subcategories)
#
# P - Edit records
# A - Add records
# R - Remove records
#
# E - Edit Attributes
# C - Create Attributes
# D - Delete Attributes
#
# S - Add Subcategories
# Z - Delete
#
# Different types of entities can have access granted:
# U - Unique User
# G - Access Group
CREATE TABLE xtra_web_extension_notitia_category_grant (
        categoryid   INT         UNSIGNED NOT NULL,
        entityid     INT         UNSIGNED NOT NULL,
        entity_type  CHAR(1)     NOT NULL,
        access_types CHAR(16)    NOT NULL,
        PRIMARY KEY(categoryid,entityid,entity_type),
        KEY(entityid,entity_type)
);

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

CREATE TABLE xtra_web_extension_notitia_attribute_role_auto_increment (
        attributeid INT UNSIGNED NOT NULL,
        value      DOUBLE       NOT NULL,
        PRIMARY KEY(attributeid)
);