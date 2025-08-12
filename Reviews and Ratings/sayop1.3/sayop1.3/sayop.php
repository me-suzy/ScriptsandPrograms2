<?php
/*


	Copyright (C) 2004-2005 Alex B

	E-Mail: dirmass@devplant.com
	URL: http://www.devplant.com
	
    This file is part of SayOp.

    SayOp is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2.1 of the License, or
    (at your option) any later version.

    SayOp is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with SayOp; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA


*/
/////////////////////////////////////////////////////////////////
//Do not edit below except if you know what you are doing.
/////////////////////////////////////////////////////////////////

function showComms($id,$path) {




include("com/db.php");


$catid = $id;
$sql = mysql_query("SELECT * FROM ".$so_prefix."_main WHERE catid='".$catid."' ORDER BY id "); 
while ($irow = mysql_fetch_row($sql)) {
echo "
<div class='sayopcontainer'>
<div class='sayopspacer'>&nbsp;</div>
<div class='sayopufloat'>By $irow[3] &nbsp;&nbsp;&nbsp;&nbsp; $irow[4]<br /></div>
<div class='sayopfloat'>$irow[5]<br /></div>
<div class='sayopdfloat'>posted at $irow[6]<br /></div>
<div class='sayopspacer'>&nbsp;</div>
</div>
";
} 


$s = mysql_query("SELECT * FROM ".$so_prefix."_obj WHERE catid='".$catid."' ");
$o_name = mysql_fetch_row($s);
$obj_name = $o_name[1];

$p = $path;

echo "
<div style='sayopfloat: left;width: 35%; background-color: ; border-top: 0px; border-bottom: 1px groove #333; padding: 5px;
margin: 0px auto; text-align: left;'>
  <form name='f1' action='$p/com/add_entry.php' method='post'>
    <input type='hidden' name='obj_name' value='$obj_name' />
    <input type='hidden' name='catid' value='$catid' />
    <div class='sayoprow'>
      Name: <input type='text' size='25' name='author' />
    </div>
";
echo "
    <div class='sayoprow'>
      Email: <input type='text' size='25' name='email' /><span class='sayopsmall'>(optional)</span>
    </div>

";
echo "
    <div class='sayoprow'>
      <span class='sayoplabel'>Comments:</span><span
class='sayopformw'>
        <textarea name='comment' cols='32' rows='8'></textarea>
      </span>
    </div>
   <div class='sayoprow'>
<input type='submit' name='Add' value='Sayit' />
   </div>
  <div class='sayopspacer' style='text-align: right'>
 <a href='http://www.devplant.com/sayop' class='sl'>Powered by SayOp</a>
  </div>
 </form>
</div>
";


}





?>