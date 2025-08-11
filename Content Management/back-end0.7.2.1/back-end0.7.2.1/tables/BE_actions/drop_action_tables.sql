#**
#* drop_action_tables.sql
#*
#* Drop tables for Back-End Actions
#* 
#* @package   Back-End on phpSlash
#* @author    Peter Bojanic
#* @copyright Copyright (C) 2003 OpenConcept Consulting
#* @version   $Id: drop_action_tables.sql,v 1.2 2005/03/23 18:35:53 krabu Exp $
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
# see the script add_action_tables.sql for details on these tables

DROP TABLE if exists be_target;
DROP TABLE if exists be_targetType;
DROP TABLE if exists be_contactType;
DROP TABLE if exists be_action;
DROP TABLE if exists be_actionText;
DROP TABLE if exists be_actionType;
DROP TABLE if exists be_action2section;
DROP TABLE if exists be_contact;
DROP TABLE if exists be_action2contact;
DROP TABLE if exists be_targetFinder;
DROP TABLE if exists be_targetFinder2action;
DROP TABLE if exists be_target2participant;
