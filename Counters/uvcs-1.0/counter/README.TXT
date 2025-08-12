  +-----------------------------------------------------------------------------+
  | [README.TXT]                                                                |
  +-----------------------------------------------------------------------------+
  |                                                                             |
  |               U V C S   1 . 0                                               |
  |               ===============                                               |
  |                                                                             |
  |               Urkburk Visitor Counting System                               |
  |                                                                             |
  |                                                                             |
  |               By:       Simon Lundmark                                      |
  |               Website:  http://www.urkburk.com/                             |
  |               Email:    simon@urkburk.com                                   |
  |               MSN:      simon@urkburk.com                                   |
  |               ICQ:      29537272                                            |
  |                                                                             |
  +-----------------------------------------------------------------------------+
  | LICENSE                                                                     |
  +-----------------------------------------------------------------------------+
  |                                                                             |
  | Urkburk Visitor Counting System 1.0                                         |
  | Copyright (C) 2004  Simon Lundmark                                          |
  |                                                                             |
  | This program is free software; you can redistribute it and/or               |
  | modify it under the terms of the GNU General Public License                 |
  | as published by the Free Software Foundation; either version 2              |
  | of the License, or (at your option) any later version.                      |
  |                                                                             |
  | This program is distributed in the hope that it will be useful,             |
  | but WITHOUT ANY WARRANTY; without even the implied warranty of              |
  | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the               |
  | GNU General Public License for more details.                                |
  |                                                                             |
  | You should have received a copy of the GNU General Public License           |
  | along with this program; if not, write to the Free Software                 |
  | Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA. |
  |                                                                             |
  +-----------------------------------------------------------------------------+
  | TOC                                                                         |
  +-----------------------------------------------------------------------------+
  |                                                                             |
  |   1.     BENEFITS OF USING UVCS                                             |
  |   2.     REQUIREMENTS                                                       |
  |   3.     HOW TO INSTALL                                                     |
  |   4.     FAQ                                                                |
  |                                                                             |
  +-----------------------------------------------------------------------------+
  | 1. BENEFITS OF USING UVCS                                                   |
  +-----------------------------------------------------------------------------+
  |                                                                             |
  |   + NO database needed                                                      |
  |   + EASY to understand the well-commented code                              |
  |   + XHTML valid =)                                                          |
  |                                                                             |
  +-----------------------------------------------------------------------------+
  | 2. REQUIREMENTS                                                             |
  +-----------------------------------------------------------------------------+
  |                                                                             |
  |   Developed on Apache/1.3.29 (Win32) PHP/4.3.6.                             |
  |   Should work with any server, any OS and PHP/4.3.x.                        |
  |                                                                             |
  +-----------------------------------------------------------------------------+
  | 3. HOW TO INSTALL                                                           |
  +-----------------------------------------------------------------------------+
  |                                                                             |
  |   1.     Unpack                                                             |
  |   2.     Add the following lines to the top of                              |
  |          every document that is to be counted:                              |
  +- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -+

<?php
include('./counter/counter.inc.php');
echo "<!-- using Urkburk Visitor Counting System from www.urkburk.com -->\n";
?>

  +- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -+
  |   3. Add the following line where the counter should be displayed:          |
  +- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -+

You are visitor number <?= $UVCS['count'] ?>
 
  +- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -+
  |   2. FAQ                                                                    |
  +-----------------------------------------------------------------------------+
  |                                                                             |
  |   Q: I don't understand the install-guide.                                  |
  |   A: See the example.php file.                                              |
  |                                                                             |
  +-----------------------------------------------------------------------------+
