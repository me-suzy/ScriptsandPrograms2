#**
#* add_bibliography_tables.sql
#*
#* Add tables for Back-End Bibliographies
#*
#* @package   Back-End on phpSlash
#* @author    Peter Bojanic
#* @copyright Copyright (C) 2003 OpenConcept Consulting
#* @version   $Id: add_bibliography_tables.sql,v 1.2 2005/04/19 15:44:29 mgifford Exp $
#*
#
# This file is part of Back-End.
#
# Back-End is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# Back-End is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with Back-End; if not, write to the Free Software
# Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA


#**
#* Table descriptions
#*
# be_bib - Bibliography entries
# be_bib2category - defines associations between a be_bib and multiple be_bib_category rows
# be_bib2country - defines an association between a be_bib and multiple be_bib_country rows
# be_bib2keywords - UNNUSED
# be_bib2profile2role - defines an association between a be_bib, and a be_profile for a specific be_profile_role
# be_bib2region - defines associations between a be_bib and multiple be_bib_region rows
# be_bibMLA -
# be_bib_category - arbitrary hierarchical named categories for organizing be_bib rows
# be_bib_country - country names
# be_country2region - defines an association between a be_bib_country a be_bib_region
# be_profile_keywords - UNUSED
# be_bib_language - language names
# be_profile_photo - stores images in binary format, associated with a given be_profile
# be_profession - profession names
# be_profile - Profiles of people
# be_profile2category - defines an association between a be_profile and multiple be_bib_category rows
# be_profile2country - defines an association betwee a be_profile and multiple be_bib_country rows
# be_profile2keywords - UNUSED
# be_profile2nationality - defines an association between a be_profile and multiple be_bib_country rows
# be_profile2profession - defines an association between a be_profile and multiple be_profession rows
# be_profile2region - defines an association between a be_profile and multiple be_bib_region rows
# be_profile2spokenLanguages - defines an association between a be_profile and multiple be_bib_language rows
# be_publisher - Publishers of books, magazines, Internet siets, etc.
# be_bib_region - Geographic regions
# be_profile_role - Roles that Profiles can play in relation to Bibliogrphies
# be_bib_types - Types of Bibliogrphy sources
# be_profile2upload - defines an association between a be_profile and multiple be_upload rows



#**
#* Schema notes
#*



#
# Table structure for table `be_bib`
#
DROP TABLE IF EXISTS be_bib;
CREATE TABLE be_bib (
  bibID mediumint(9) NOT NULL default '0',
  languageID varchar(5) default NULL,
  publisherID mediumint(9) default NULL,
  type varchar(55) default NULL,
  title text,
  articleTitle varchar(255) default NULL,
  volumeSeries varchar(255) default NULL,
  pageNumber varchar(55) default NULL,
  source_bibID varchar(55) default NULL,
  publicationDate varchar(25) default NULL,
  dateAdded date default NULL,
  publishedLanguage varchar(5) default NULL,
  URL varchar(100) default NULL,
  abstract text,
  status varchar(5) default NULL,
  story_id int(11) default NULL,
  PRIMARY KEY  (bibID)
) TYPE=MyISAM;

# Create the sequence entry
INSERT INTO db_sequence (seq_name, nextid) VALUES ('bibID_seq', 1);


#
# Table structure for table `be_bib2category`
#
DROP TABLE IF EXISTS be_bib2category;
CREATE TABLE be_bib2category (
  categoryID varchar(5) default NULL,
  bibID varchar(255) default NULL
) TYPE=MyISAM;


#
# Table structure for table `be_bib2country`
#
DROP TABLE IF EXISTS be_bib2country;
CREATE TABLE be_bib2country (
  countryID varchar(5) default NULL,
  bibID varchar(255) default NULL
) TYPE=MyISAM;

#
# Table structure for table `be_bib2keywords`
#
DROP TABLE IF EXISTS be_bib2keywords;
CREATE TABLE be_bib2keywords (
  keywordsID varchar(5) default NULL,
  bibID varchar(255) default NULL
) TYPE=MyISAM;


#
# Table structure for table `be_bib2profile2role`
#
DROP TABLE IF EXISTS be_bib2profile2role;
CREATE TABLE be_bib2profile2role (
  roleID smallint(9) default NULL,
  profileID smallint(9) default NULL,
  bibID smallint(9) default NULL,
  profileOrder smallint(6) default NULL
) TYPE=MyISAM;


#
# Table structure for table `be_bib2region`
#
DROP TABLE IF EXISTS be_bib2region;
CREATE TABLE be_bib2region (
  regionID smallint(6) NOT NULL default '0',
  bibID mediumint(9) NOT NULL default '0'
) TYPE=MyISAM;


#
# Table structure for table `be_bibMLA`
#
DROP TABLE IF EXISTS be_bibMLA;
CREATE TABLE be_bibMLA (
  bibID mediumint(9) NOT NULL default '0',
  authors varchar(255) default NULL,
  listing text,
  publicationDate varchar(55) default NULL,
  status varchar(5) default NULL,
  abstract text,
  PRIMARY KEY  (bibID)
) TYPE=MyISAM;


#
# Table structure for table `be_bib_category`
#
DROP TABLE IF EXISTS be_bib_category;
CREATE TABLE be_bib_category (
  categoryID mediumint(9) NOT NULL default '0',
  languageID varchar(5) default NULL,
  name varchar(255) default NULL,
  parentID smallint(6) NOT NULL default '0',
  description varchar(255) default NULL,
  status varchar(5) default NULL,
  PRIMARY KEY  (categoryID),
  KEY name (name),
  KEY parentID (parentID)
) TYPE=MyISAM;

# Create the sequence entry
INSERT INTO db_sequence (seq_name, nextid) VALUES ('categoryID_seq', 1);


#
# Table structure for table `be_bib_country`
#
DROP TABLE IF EXISTS be_bib_country;
CREATE TABLE be_bib_country (
  countryID char(3) NOT NULL default '',
  languageID varchar(5) default NULL,
  name varchar(55) default NULL,
  PRIMARY KEY  (countryID)
) TYPE=MyISAM;


#
# Table structure for table `be_country2region`
#
DROP TABLE IF EXISTS be_country2region;
CREATE TABLE be_country2region (
  countryID varchar(5) default NULL,
  regionID varchar(255) default NULL
) TYPE=MyISAM;


#
# Table structure for table `be_profile_keywords`
#
DROP TABLE IF EXISTS be_profile_keywords;
CREATE TABLE be_profile_keywords (
  keywordID mediumint(9) NOT NULL default '0',
  languageID varchar(5) default NULL,
  name varchar(255) default NULL,
  PRIMARY KEY  (keywordID)
) TYPE=MyISAM;


#
# Table structure for table `be_bib_language`
#
DROP TABLE IF EXISTS be_bib_language;
CREATE TABLE be_bib_language (
  languageID varchar(5) NOT NULL default '',
  name varchar(255) default NULL,
  PRIMARY KEY  (languageID)
) TYPE=MyISAM;


#
# Table structure for table `be_profile_photo`
#
DROP TABLE IF EXISTS be_profile_photo;
CREATE TABLE be_profile_photo (
  profileID mediumint(9) NOT NULL default '0',
  photo blob,
  height smallint(3) default NULL,
  width smallint(3) default NULL,
  altText varchar(255) default NULL,
  PRIMARY KEY  (profileID)
) TYPE=MyISAM;


#
# Table structure for table `be_profession`
#
DROP TABLE IF EXISTS be_profession;
CREATE TABLE be_profession (
  professionID mediumint(9) NOT NULL default '0',
  languageID varchar(5) default NULL,
  name varchar(255) default NULL,
  PRIMARY KEY  (professionID)
) TYPE=MyISAM;

# Create the sequence entry
INSERT INTO db_sequence (seq_name, nextid) VALUES ('professionID_seq', 1);


#
# Table structure for table `be_profile`
#
DROP TABLE IF EXISTS be_profile;
CREATE TABLE be_profile (
  profileID mediumint(9) NOT NULL default '0',
  languageID varchar(5) default NULL,
  firstName varchar(255) default NULL,
  middleName varchar(255) default NULL,
  lastName varchar(255) default NULL,
  title varchar(255) default NULL,
  organization varchar(255) default NULL,
  nationality varchar(255) default NULL,
  bio text,
  status varchar(5) default NULL,
  countryID char(3) default NULL,
  email varchar(255) default NULL,
  emailprivate char(3) default NULL,
  URL varchar(255) default NULL,
  URLprivate char(3) default NULL,
  phone varchar(50) default NULL,
  phoneprivate char(3) default NULL,
  fax varchar(50) default NULL,
  faxprivate char(3) default NULL,
  addressprivate char(3) default NULL,
  address1 varchar(255) default NULL,
  address2 varchar(255) default NULL,
  city varchar(55) default NULL,
  state varchar(55) default NULL,
  postalCode varchar(15) default NULL,
  nomination tinyint(2) NOT NULL default '0',
  PRIMARY KEY  (profileID)
) TYPE=MyISAM;

# Create the sequence entry
INSERT INTO db_sequence (seq_name, nextid) VALUES ('profileID_seq', 1);


#
# Table structure for table `be_profile2category`
#
DROP TABLE IF EXISTS be_profile2category;
CREATE TABLE be_profile2category (
  categoryID varchar(5) default NULL,
  profileID varchar(255) default NULL
) TYPE=MyISAM;


#
# Table structure for table `be_profile2country`
#
DROP TABLE IF EXISTS be_profile2country;
CREATE TABLE be_profile2country (
  countryID varchar(5) default NULL,
  profileID mediumint(9) default NULL
) TYPE=MyISAM;


#
# Table structure for table `be_profile2keywords`
#
DROP TABLE IF EXISTS be_profile2keywords;
CREATE TABLE be_profile2keywords (
  keywordID varchar(5) default NULL,
  profileID varchar(255) default NULL
) TYPE=MyISAM;


#
# Table structure for table `be_profile2nationality`
#
DROP TABLE IF EXISTS be_profile2nationality;
CREATE TABLE be_profile2nationality (
  countryID varchar(5) default NULL,
  profileID mediumint(9) default NULL
) TYPE=MyISAM;


#
# Table structure for table `be_profile2profession`
#
DROP TABLE IF EXISTS be_profile2profession;
CREATE TABLE be_profile2profession (
  professionID mediumint(9) default NULL,
  profileID mediumint(9) default NULL
) TYPE=MyISAM;


#
# Table structure for table `be_profile2region`
#
DROP TABLE IF EXISTS be_profile2region;
CREATE TABLE be_profile2region (
  regionID varchar(5) default NULL,
  profileID mediumint(9) default NULL
) TYPE=MyISAM;


#
# Table structure for table `be_profile2spokenLanguages`
#
DROP TABLE IF EXISTS be_profile2spokenLanguages;
CREATE TABLE be_profile2spokenLanguages (
  languageID varchar(5) default NULL,
  profileID varchar(255) default NULL
) TYPE=MyISAM;


#
# Table structure for table `be_publisher`
#
DROP TABLE IF EXISTS be_publisher;
CREATE TABLE be_publisher (
  publisherID mediumint(9) NOT NULL default '0',
  languageID varchar(5) default NULL,
  name varchar(255) default NULL,
  alias4publisherID mediumint(9) default NULL,
  countryID char(3) default NULL,
  email varchar(255) default NULL,
  URL varchar(255) default NULL,
  address1 varchar(255) default NULL,
  address2 varchar(255) default NULL,
  city varchar(55) default NULL,
  state varchar(55) default NULL,
  postalCode varchar(15) default NULL,
  PRIMARY KEY  (publisherID)
) TYPE=MyISAM;

# Create the sequence entry
INSERT INTO db_sequence (seq_name, nextid) VALUES ('publisherID_seq', 1);


#
# Table structure for table `be_bib_region`
#
DROP TABLE IF EXISTS be_bib_region;
CREATE TABLE be_bib_region (
  regionID smallint(9) NOT NULL default '0',
  languageID varchar(5) default NULL,
  name varchar(55) default NULL,
  PRIMARY KEY  (regionID)
) TYPE=MyISAM;

# Create the sequence entry
INSERT INTO db_sequence (seq_name, nextid) VALUES ('regionID_seq', 1);


#
# Table structure for table `be_profile_role`
#
DROP TABLE IF EXISTS be_profile_role;
CREATE TABLE be_profile_role (
  roleID smallint(9) NOT NULL default '0',
  languageID varchar(5) default NULL,
  name varchar(55) default NULL,
  bibName varchar(55) default NULL,
  PRIMARY KEY  (roleID)
) TYPE=MyISAM;


#
# Table structure for table `be_bib_types`
#
DROP TABLE IF EXISTS be_bib_types;
CREATE TABLE be_bib_types (
  typeID smallint(9) NOT NULL default '0',
  languageID char(3) default 'eng',
  name varchar(55) default NULL,
  PRIMARY KEY  (typeID)
) TYPE=MyISAM;


#
# Table structure for table `be_profile2upload`
#
DROP TABLE IF EXISTS be_profile2upload;
CREATE TABLE be_profile2upload (
  uploadID smallint(6) NOT NULL,
  profileID smallint(6) NOT NULL,
  PRIMARY KEY  (uploadID, profileID)
) TYPE=MyISAM;
