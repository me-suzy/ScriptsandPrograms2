<?php
// TODO: Localisation!!!!
echo"
<p class='style1'><span class='stylelarger'>RW::Download {$version_short}</span><br>
       Admin Control Panel</p>

<p class='navhead'>RW::Download</p>
<p class='navbox'>
<img src='{$rwdInfo->skinurl}/images/tri.gif' width='7' height='7'>  <a href='admin.php?sid=$sid&area=main&act=home' target='body'>Admin Home</a><br>
<img src='{$rwdInfo->skinurl}/images/tri.gif' width='7' height='7'>  <a href='admin.php?sid=$sid&area=main&act=news' target='body'>RW::Scripts News </a><br>
<img src='{$rwdInfo->skinurl}/images/tri.gif' width='7' height='7'>  <a href='index.php' target='_top'>Your Downloads Page</a><br>
<img src='{$rwdInfo->skinurl}/images/tri.gif' width='7' height='7'>  <a href='http://www.rwscripts.com/' target='_top'>RW::Scripts Home</a><br>
<img src='{$rwdInfo->skinurl}/images/tri.gif' width='7' height='7'>  <a href='http://www.rwscripts.com/forum/' target='_top'>RW::Download support</a><br>
</p>

<p class='navhead'>Configuration</p>
<p class='navbox'>
<img src='{$rwdInfo->skinurl}/images/tri.gif' width='7' height='7'> <a href='admin.php?sid=$sid&area=config&act=general' target='body'>General Configuration</a><br>
<img src='{$rwdInfo->skinurl}/images/tri.gif' width='7' height='7'> <a href='admin.php?sid=$sid&area=config&act=security' target='body'>Security Settings</a><br>
<img src='{$rwdInfo->skinurl}/images/tri.gif' width='7' height='7'> <a href='admin.php?sid=$sid&area=config&act=gallery' target='body'>Gallery Settings</a><br>
<img src='{$rwdInfo->skinurl}/images/tri.gif' width='7' height='7'> <a href='admin.php?sid=$sid&area=config&act=date' target='body'>Date and Time Settings</a><br>
<img src='{$rwdInfo->skinurl}/images/tri.gif' width='7' height='7'> <a href='admin.php?sid=$sid&area=config&act=phpinfo' target='body'>Server Environment</a><br>
<img src='{$rwdInfo->skinurl}/images/tri.gif' width='7' height='7'> <a href='admin.php?sid=$sid&area=config&act=resync' target='body'>Recount All Categories</a><br>
<img src='{$rwdInfo->skinurl}/images/tri.gif' width='7' height='7'> <a href='admin.php?sid=$sid&area=config&act=offline' target='body'>Script on/off</a>
</p>
		  
<p class='navhead'>File Management </p>
<p class='navbox'>
<img src='{$rwdInfo->skinurl}/images/tri.gif' width='7' height='7'> <a href='admin.php?sid=$sid&area=files&act=edit' target='body'>Edit/Delete Categories and files</a><br>
<img src='{$rwdInfo->skinurl}/images/tri.gif' width='7' height='7'> <a href='admin.php?sid=$sid&area=files&act=unapproved' target='body'>Unapproved files ($unapp)</a><br>
<img src='{$rwdInfo->skinurl}/images/tri.gif' width='7' height='7'> <a href='admin.php?sid=$sid&area=files&act=addcat' target='body'>Add Category</a><br>
<img src='{$rwdInfo->skinurl}/images/tri.gif' width='7' height='7'> <a href='admin.php?sid=$sid&area=files&act=ordercat' target='body'>Order Categorys</a><br>
<img src='{$rwdInfo->skinurl}/images/tri.gif' width='7' height='7'> <a href='admin.php?sid=$sid&area=files&act=adddl' target='body'>Add Download</a><br>
<img src='{$rwdInfo->skinurl}/images/tri.gif' width='7' height='7'> <a href='admin.php?sid=$sid&area=files&act=filetype' target='body'>Edit File Types</a><br>
</p>

<p class='navhead'>Admin Control</p>
<p class='navbox'>
<img src='{$rwdInfo->skinurl}/images/tri.gif' width='7' height='7'> <a href='admin.php?sid=$sid&area=users&act=addmember' target='body'>Create New Admin User</a><br>
<img src='{$rwdInfo->skinurl}/images/tri.gif' width='7' height='7'> <a href='admin.php?sid=$sid&area=users&act=editusers' target='body'>Edit Admins</a><br>
</p>

<p class='navhead'>Skin Control</p>
<p class='navbox'>
<img src='{$rwdInfo->skinurl}/images/tri.gif' width='7' height='7'> <a href='admin.php?sid=$sid&area=skins&act=new' target='body'>Create New Skin Set</a><br>
<img src='{$rwdInfo->skinurl}/images/tri.gif' width='7' height='7'> <a href='admin.php?sid=$sid&area=skins&act=manage' target='body'>Edit/Delete Skins</a><br>
<img src='{$rwdInfo->skinurl}/images/tri.gif' width='7' height='7'> <a href='admin.php?sid=$sid&area=skins&act=test' target='body'>Update Skin from PHP</a><br>
<img src='{$rwdInfo->skinurl}/images/tri.gif' width='7' height='7'> <a href='admin.php?sid=$sid&area=skins&act=import' target='body'>Import Skin</a><br>
<img src='{$rwdInfo->skinurl}/images/tri.gif' width='7' height='7'> <a href='admin.php?sid=$sid&area=skins&act=export' target='body'>Export Skin</a><br>
</p>

<p class='navhead'>Language Control </p>
<p class='navbox'>
<img src='{$rwdInfo->skinurl}/images/tri.gif' width='7' height='7'> <a href='admin.php?sid=$sid&area=lang&act=new' target='body'>Create New Language</a><br>
<img src='{$rwdInfo->skinurl}/images/tri.gif' width='7' height='7'> <a href='admin.php?sid=$sid&area=lang&act=manage' target='body'>Edit Languages</a><br>
<img src='{$rwdInfo->skinurl}/images/tri.gif' width='7' height='7'> <a href='admin.php?sid=$sid&area=lang&act=import' target='body'>Import Language</a><br>
<img src='{$rwdInfo->skinurl}/images/tri.gif' width='7' height='7'> <a href='admin.php?sid=$sid&area=lang&act=export' target='body'>Export Language</a><br>
</p>

<form action='http://www.hotscripts.com/rate/21660.html?RID=764' method='post' target='body'>
<p class='navhead'>Rate Our Script</p>
<p class='navbox'>
<select name='rating' size='1'>
	<option selected='selected'>Select Your Rating</option>
	<option value='5'>Excellent!</option>
	<option value='4'>Very Good</option>
	<option value='3'>Good</option>
	<option value='2'>Fair</option>
	<option value='1'>Poor</option>
</select>
<div align='center'><input name='submit' type='submit' value='Rate @ \nHotscripts.com'></div>
</p></form>
";
?>
