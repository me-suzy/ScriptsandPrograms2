<?php

	/*////////////////////////////////////////////////////////////
	
	iWare Professional 4.0.0
	Copyright (C) 2002,2003 David N. Simmons 
	http://www.dsiware.com

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

	A COPY OF THE GPL LICENSE FOR THIS PROGRAM CAN BE FOUND WITHIN THE
	docs/ DIRECTORY OF THE INSTALLATION PACKAGE.

	/////////////////////////////////////////////////////////////*/	
	
	// Load Configuration
	$config=$IW->Query("select * from mod_guestbook_config limit 1");
	define("MSG_PER_PAGE",$IW->Result($config,0,"msg_per_page"));		
	define("MAX_AUTHOR_LENGTH",$IW->Result($config,0,"max_author"));
	define("MAX_SUBJECT_LENGTH",$IW->Result($config,0,"max_subject"));
	define("MAX_BODY_LENGTH",$IW->Result($config,0,"max_body"));
	define("ALLOW_POSTS",$IW->Result($config,0,"allow_posts"));
	define("AUTHOR_REQUIRED",$IW->Result($config,0,"author_req"));
	define("SUBJECT_REQUIRED",$IW->Result($config,0,"subject_req"));
	define("BODY_REQUIRED",$IW->Result($config,0,"body_req"));
	$IW->FreeResult($config);

	// Handle New Posts
	if(isset($post) && $post==1)
		{
		// validate required data
		if(AUTHOR_REQUIRED==1)
			{if(empty($msg_author)){die("You must enter your name in order to post a message");}}
		if(SUBJECT_REQUIRED==1)
			{if(empty($msg_subject)){die("You must enter a title / subject in order to post a message");}}
		if(BODY_REQUIRED==1)
			{if(empty($msg_body)){die("You must enter message text in order to post a message");}}
		// format inputted data
		$msg_author=str_replace("'","",$msg_author);
		$msg_author=trim(substr($msg_author,0,MAX_AUTHOR_LENGTH));
		$msg_subject=str_replace("'","",$msg_subject);
		$msg_subject=trim(substr($msg_subject,0,MAX_SUBJECT_LENGTH));
		$msg_body=str_replace("'","",$msg_body);
		$msg_body=trim(substr($msg_body,0,MAX_BODY_LENGTH));
		// create msg identity
		$id=md5(uniqid(rand(),1)); 
		$date=strtotime(date("m/d/Y"));
		// add the message
		$IW->Query("insert into mod_guestbook (id,msg_date,msg_author,msg_subject,msg_body) values ('$id','$date','$msg_author','$msg_subject','$msg_body')");
		}

	// get total num of posts
	$posts=$IW->Query("select * from mod_guestbook");
	$numrows=$IW->CountResult($posts);
	$IW->FreeResult($posts);

	// Show Previous Posts Ordered By Date Posted
	if (empty($offset)) {$offset=0;}
	$result=$IW->Query("select * from mod_guestbook order by msg_date desc limit $offset,".MSG_PER_PAGE);
	$count=$IW->CountResult($result);
	
	// message post header
	?>
	<center>
	<table border=0 cellpadding=3 cellspacing=0 width=525>
	<tr>
	<td><?php echo $numrows . " messages have been posted."; ?></td>
	<td align=center width=150>
	<?php
	if (ALLOW_POSTS==1){echo "<a href=\"index.php?D=$D#new\">Post New Message</a>";}
	else{echo "&nbsp;";}
	?>
	</td>
	</tr>
	</table>
	</center>
	<center>
	<table border=0 cellpadding=3 cellspacing=0 width=525>
	<?php
	// show posts
	for($i=0;$i<$count;$i++)
		{
		echo "<tr>\n<td>\n<b>".$IW->Result($result,$i,"msg_subject")."</b><br />\n";
		echo "<i>posted on ".date("m/d/Y",$IW->Result($result,$i,"msg_date"))." by ".$IW->Result($result,$i,"msg_author")."</i><br />\n";
		echo "<font size=1>".$IW->Result($result,$i,"msg_body")."</font>\n</td>\n</tr>\n";
		}
	?>
	</table>
	</center>
	<center>
	<table border=0 cellpadding=3 cellspacing=0 width=525>
	<tr><td>
	<?php
	$IW->FreeResult($result);

	// Paging Links
	echo "<center>\n";

		// next we need to do the links to other results
		if ($offset!=0) { // bypass PREV link if offset is 0
			$prevoffset=$offset-3;
				echo "<a href=\"index.php?D=$D&V=offset&offset=$prevoffset\"><b>««</b></a> prev ".MSG_PER_PAGE."&nbsp;&nbsp;&nbsp; \n";
		}

		// calculate number of pages needing links
		$pages=intval($numrows/MSG_PER_PAGE);

		// $pages now contains int of pages needed unless there is a remainder from division
		if ($numrows % MSG_PER_PAGE) {
			// has remainder so add one page
			$pages++;
		}

		for ($i=1;$i<=$pages;$i++) { // loop thru
			$newoffset=MSG_PER_PAGE*($i-1);
			print "<a href=\"index.php?D=$D&V=offset&offset=$newoffset\">[<b>$i</b>]</a> &nbsp; \n";
		}

		// check to see if last page
		if (!(($offset/MSG_PER_PAGE)==$pages) && $pages!=1) {
			// not last page so give NEXT link
			$newoffset=$offset + MSG_PER_PAGE;
			print "&nbsp;&nbsp;&nbsp;next ".MSG_PER_PAGE." <a href=\"index.php?D=$D&V=offset&offset=$newoffset\"><b>»»</b></a><p>\n";
		}

	echo "</center>\n";
	?>
	</td></tr>
	</table></center>
	<?php

	// Optionally Show the Post New Message Form
	if (ALLOW_POSTS==1)
		{
		?>
		<script language=JavaScript>
			/* message post validation */
			function vPost ()
				{
				<?php
					// Write out JavaScript Validation if necessary
					if(AUTHOR_REQUIRED==1){echo "if(document.postForm.msg_author.value.length<1)\n{alert('You must enter your author name.');\nreturn false;\n}\n";}		
					if(SUBJECT_REQUIRED==1){echo "if(document.postForm.msg_subject.value.length<1)\n{alert('You must enter a title / subject.');\nreturn false;\n}\n";}
					if(BODY_REQUIRED==1){echo "if(document.postForm.msg_body.value.length<1)\n{alert('You must enter message text to post.');\nreturn false;\n}\n";}		
				?>
				return true;
				}
		</script>
		<a name="new"></a>
		<form method=post name=postForm action="index.php?D=<?php echo $D; ?>&post=1" onSubmit="return vPost ()">
		<input type=hidden name=V value="msg_author|msg_subject|msg_body|post">
		<center><table border=0 cellpadding=3 cellspacing=0>
		<tr><td><b>Post New Message</b></td></tr>
		<tr><td>
		Your Name<br  />
		<input type="text" name="msg_author" size=30 maxlength="<?php echo MAX_AUTHOR_LENGTH; ?>"><br />
		Subject or Title<br />
		<input type="text" name="msg_subject" size=30 maxlength="<?php echo MAX_SUBJECT_LENGTH; ?>"><br />
		Message  (<i><?php echo MAX_BODY_LENGTH; ?> chars max.</i>)<br />
		<textarea name="msg_body" rows="6" cols="35"></textarea><br />			
		</td></tr>
		<tr><td align=center><input type="submit" value="post message"></td></tr>
		</table></center>
		</form>
		<?php
		}
?>