<?php

/* Reminder: always indent with 4 spaces (no tabs). */
// +---------------------------------------------------------------------------+
// | Geeklog 1.3                                                               |
// +---------------------------------------------------------------------------+
// | article.php                                                               |
// | Shows articles in various formats.                                        |
// |                                                                           |
// +---------------------------------------------------------------------------+
// | Copyright (C) 2000,2001 by the following authors:                         |
// |                                                                           |
// | Authors: Tony Bibbs, tony@tonybibbs.com                                   |
// |          Jason Whitttenburg, jwhitten@securitygeeks.com		      	   |
// +---------------------------------------------------------------------------+
// |                                                                           |
// | This program is free software; you can redistribute it and/or             |
// | modify it under the terms of the GNU General Public License               |
// | as published by the Free Software Foundation; either version 2            |
// | of the License, or (at your option) any later version.                    |
// |                                                                           |
// | This program is distributed in the hope that it will be useful,           |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of            |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the             |
// | GNU General Public License for more details.                              |
// |                                                                           |
// | You should have received a copy of the GNU General Public License         |
// | along with this program; if not, write to the Free Software Foundation,   |
// | Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.           |
// |                                                                           |
// +---------------------------------------------------------------------------+

// +---------------------------------------------------------------------------+
// | CHANGE THE LOOK OF A PARTICULAR BLOCK ON THE LEFT OR RIGHT                |
// +---------------------------------------------------------------------------+
// The following are working samples.  If you find that you need 1 particular block to
// stand out from the rest of your blocks, either on the left or right side, then simply
// alter either of the following files, "blockheader-left.thtml or blockheader-right.thtml"
// to suit your needs.  Then, come back here, uncomment the block you want to change (this is
// by no means the entire listing of block names), and then reload your site.  Your altered
// look is now applied to that singular block (or blocks).

//$_BLOCK_TEMPLATE['whosonline_block'] = 'blockheader-right.thtml,blockfooter-right.thtml';
//$_BLOCK_TEMPLATE['first_block'] = 'blockheader-right.thtml,blockfooter-right.thtml';
//$_BLOCK_TEMPLATE['whats_new_block'] = 'blockheader-right.thtml,blockfooter-right.thtml';
//$_BLOCK_TEMPLATE['poll_block'] = 'blockheader-right.thtml,blockfooter-right.thtml';


// +---------------------------------------------------------------------------+
// | CHANGE THE LOOK OF ALL THE BLOCKS ON THE LEFT OR RIGHT                    |
// +---------------------------------------------------------------------------+
// As of geeklog 1.3.5 You can have different themes for either the left or right blocks.  
// The default install has both the left and right block using the same theme. If you edit 
// the contents of "blockheader-right(or left).thtml" then the following code will properly
// apply your new theme to any block which appears on that side.
// (Depends on which block you decide to edit, blockheader-left.thtml or blockheader-right.thtml).

// SINCERELY, GEEKLOG TEAM.
 
$result = DB_query("SELECT onleft,name FROM {$_TABLES['blocks']} WHERE is_enabled = 1"); 
$nrows = DB_numRows($result); 
for ($i = 1; $i <= $nrows; $i++) { 
    $A = DB_fetchArray($result); 
        if ($A['onleft'] == 1) { 
            $_BLOCK_TEMPLATE[$A['name']] = 'blockheader-left.thtml,blockfooter-left.thtml'; 
        } else { 
            $_BLOCK_TEMPLATE[$A['name']] = 'blockheader-right.thtml,blockfooter-right.thtml'; 
        } 
} 

?>