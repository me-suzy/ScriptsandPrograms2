<?php
//This is a basic SAXON template created by Black Widow

function Template($date_posted, $title, $news, $poster)
{
	return("
<div class=\"news-display\">
<h2>$title</h2>
<div class=\"post-date\">Posted by $poster on $date_posted</div>
<div>$news</div>
</div>
");
}
?>