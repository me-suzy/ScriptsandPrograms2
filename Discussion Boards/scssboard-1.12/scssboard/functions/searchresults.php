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
<?php

$sortby = $_POST[sortby];

$search_term = trim($_POST[search_term]);
$search_term = stripslashes($search_term);

if (strlen($search_term) < 3) {
	echo "<p align='center'>Please enter a search term longer than 2 characters.</p><br />";
} else {

$query_text = "SELECT *, MATCH(posts_body) AGAINST ('$search_term' IN BOOLEAN MODE) AS score FROM $_CON[prefix]posts WHERE MATCH(posts_body) AGAINST('$search_term' IN BOOLEAN MODE)";

if ($sortby == "post_date")
	$query_text .= " order by posts_posted desc";
elseif ($sortby == "relevancy")
	$query_text .= " order by score desc, posts_posted desc";

$search_query = @mysql_query($query_text);
$numresults = @mysql_num_rows(@mysql_query($query_text));

        echo "<div class='catheader' style='padding:10px;'><strong>Search Results</strong></div>
				<div class='poster_info'><strong>Your search for <em>$search_term</em> returned $numresults results.</strong></div>";
			if ($numresults != 0) echo "<br />";

			while($result = @mysql_fetch_array($search_query)) {

				$stripped_body = strip_tags($result[posts_body]);

				$excerpt = substr($stripped_body, 0, 1000);

				if (strlen($stripped_body) >= 1000) {
					$excerpt .= "...";
				}

				$topic = @mysql_fetch_array(@mysql_query("select * from $_CON[prefix]posts where posts_topic = '$result[posts_topic]' and posts_main = 'yes'"));

				$result[posts_posted] = get_date($result[posts_posted],$_MAIN[date_format],$_MAIN[use_relative_dates]);

				echo "<div class='catheader' style='padding:5px;'>Posted in <strong><a href='?&amp;act=showforum&amp;f=$topic[posts_forum]&amp;t=$topic[posts_topic]#$result[posts_id]'>$topic[posts_name]</a></strong> - <strong>$result[posts_posted]</strong></div>";
				echo "<div class='msg_content'>$excerpt</div>";
				echo "<br />";
			}
            echo "
        <br />";
}
    ?>