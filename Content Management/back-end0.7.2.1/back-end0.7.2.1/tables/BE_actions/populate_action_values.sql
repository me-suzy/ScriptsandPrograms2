#
# populate_action_values.sql
#
# Population tables with initial values for Back-End Actions
#
# @package   Back-End on phpSlash
# @author    Peter Bojanic
# @copyright Copyright (C) 2003 OpenConcept Consulting
# @version   $Id: populate_action_values.sql,v 1.4 2005/03/02 17:50:01 mgifford Exp $
#
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


#
# Table descriptions
#
# see the script add_action_tables.sql for details on these tables


#
# Populate values for tables `be_actionType`
#
INSERT INTO be_actionType
  (actionTypeID, description)
  VALUES
  (1, 'Email'),
  (2, 'Fax');

#
# Populate values for table `be_contactType`
#
INSERT INTO be_contactType
  (contactTypeID, description)
  VALUES
  (1, 'Private citizen'),
  (2, 'MP/MLA');


#
# Populate values for table `be_targetType`
#

INSERT INTO be_targetFinder
  (targetFinderID, countryID,  targetTypeName, targetFinderClassName, active, targetFinderClassVersion)
  VALUES
  (1, 'CAN', 'MP', '', 0, 1),
  (2, 'CAN', 'MP', 'BE_TargetFinderMP_CA', 1, 2),
  (3, 'CAN', 'Walmart', 'BE_TargetFinderWalmart_CA', 1, 1);


