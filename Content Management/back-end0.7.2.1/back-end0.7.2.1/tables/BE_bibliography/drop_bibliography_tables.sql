#**
#* drop_bibliography_tables.sql
#*
#* Drop tables for Back-End Bibliographies
#* 
#* @package   Back-End on phpSlash
#* @author    Peter Bojanic
#* @copyright Copyright (C) 2003 OpenConcept Consulting
#* @version   $Id: drop_bibliography_tables.sql,v 1.1.1.1 2003/11/06 02:21:36 mgifford Exp $
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
# see the script add_bibliography_tables.sql for details on these tables

DROP TABLE if exists be_bib;
DELETE FROM db_sequence WHERE seq_name='bibID_seq';

DROP TABLE if exists be_bib2category;

DROP TABLE if exists be_bib2country;

DROP TABLE if exists be_bib2keywords;

DROP TABLE if exists be_bib2profile2role;

DROP TABLE if exists be_bib2region;

DROP TABLE if exists be_bibMLA;

DROP TABLE if exists be_bib_category;
DELETE FROM db_sequence WHERE seq_name='categoryID_seq';

DROP TABLE if exists be_bib_country;

DROP TABLE if exists be_country2region;

DROP TABLE if exists be_profile_keywords;

DROP TABLE if exists be_bib_language;

DROP TABLE if exists be_profile_photo;

DROP TABLE if exists be_profession;
DELETE FROM db_sequence WHERE seq_name='professionID_seq';

DROP TABLE if exists be_profile;
DELETE FROM db_sequence WHERE seq_name='profileID_seq';

DROP TABLE if exists be_profile2category;

DROP TABLE if exists be_profile2country;

DROP TABLE if exists be_profile2keywords;

DROP TABLE if exists be_profile2nationality;

DROP TABLE if exists be_profile2profession;

DROP TABLE if exists be_profile2region;

DROP TABLE if exists be_profile2spokenLanguages;

DROP TABLE if exists be_publisher;
DELETE FROM db_sequence WHERE seq_name='publisherID_seq';

DROP TABLE if exists be_bib_region;
DELETE FROM db_sequence WHERE seq_name='regionID_seq';

DROP TABLE if exists be_profile_role;

DROP TABLE if exists be_bib_types;

DROP TABLE if exists be_profile2upload;

