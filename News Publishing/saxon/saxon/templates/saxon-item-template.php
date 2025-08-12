<?php
//This is a basic SAXON single item display template created by Black Widow
//Just remember to escape all " with a \

function displayItem($date_posted, $title, $news, $poster)
{
	return("
<h1>$title</h1>
<div id=\"display-page\">
<div id=\"display-item\">
<div class=\"post-date\">Posted by $poster on $date_posted</div>
<div>$news</div>
</div>
<p>&#171; <a href=\"templates\index.php\">Back to Main News Page</a></p>
</div>
");
}
?>