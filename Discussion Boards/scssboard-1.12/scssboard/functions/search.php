<?php
/*
** sCssBoard, an extremely fast and flexible CSS-based message board system
** Copyright (CC) 2005 Elton Muuga
**
** This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike License. 
** To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/2.0/ or send 
** a letter to Creative Commons, 559 Nathan Abbott Way, Stanford, California 94305, USA.
*/
?>

<div class='catheader' style='width:400px; margin-left:auto; margin-right:auto; padding:5px;'>
	&nbsp; <b>Search</b>
</div>
<div class='msg_content' style='width:400px; margin-left:auto; margin-right:auto; padding:5px; text-align:center;'>
	<form action='?act=search-results' method='post'>
	<strong>Search Query:</strong><br /><br />
	<input type='text' name='search_term' size='50' /><br /><br />
	Sort by: <input type="radio" name="sortby" value="post_date" style="position:relative; top:3px;" /> Post Date &nbsp; <input type="radio" name="sortby" value="relevancy" checked="true" style="position:relative; top:3px;" /> Relevancy<br /><br />
	<input type='submit' name='submit' value='Search' />
	</form>
</div>
<br />
<div class='catheader' style='width:400px; margin-left:auto; margin-right:auto; padding:5px;'>
	&nbsp; <b>Advanced Search Functions</b>
</div>
<div id='adv_settings' class='msg_content' style='width:400px; margin-left:auto; margin-right:auto; padding:5px; text-align:left;'>
	<strong>aggressive badger</strong> - Find posts that contain at least one of these words<br />
	<strong>+aggressive +badger</strong> - Find posts with BOTH of these words<br />
	<strong>+aggressive -badger</strong> - Find posts with <em>aggressive</em> but not <em>badger</em><br />
	<strong>aggressive*</strong> - Find posts with <em>aggressive</em> and/or <em>aggressively</em><br />
	<strong>"aggressive badger"</strong> - Find posts with the phrase <em>aggressive badger</em> but not <em>aggressive tomato badger</em>
</div>

<br />