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
if(!$_GET[act]) {
	if($_GET[viewcat]) {
	$category = @mysql_fetch_array(@mysql_query("select * from $_CON[prefix]categories where category_id = '$_GET[viewcat]'"));
	$_PAGE[sub] = " $category[category_name]";
	$_PAGE[url] = " &#187; $category[category_name]";
	} else {
    $_PAGE[sub] = " Forum Home";
    $_PAGE[url] = " &#187; Home";
	}
} elseif($_GET[act] == "register") {
    $_PAGE[sub] = " Register";
    $_PAGE[url] = " &#187; Register";
} elseif($_GET[act] == "login") {
    $_PAGE[sub] = " Login";
    $_PAGE[url] = " &#187; Login";
} elseif($_GET[act] == "logout") {
    $_PAGE[sub] = " Logout";
    $_PAGE[url] = " &#187; Logout";
} elseif($_GET[act] == "showforum") {
    $_PAGE[sub] = " Forum Home";
    if($_GET[f] == "") {} else {
        $forum = @mysql_fetch_array(@mysql_query("select * from $_CON[prefix]forums where forums_id = $_GET[f]"));
		$category = @mysql_fetch_array(@mysql_query("select * from $_CON[prefix]categories where category_id = $forum[forums_category]"));
        $topic = @mysql_fetch_array(@mysql_query("select * from $_CON[prefix]posts where posts_topic = $_GET[t] and posts_forum = $_GET[f] and posts_main = 'yes'"));
        if($_GET[t] == "") {
            $_PAGE[sub] = $forum[forums_name];
            $_PAGE[url] = " &#187; <a href='?viewcat=$category[category_id]'>$category[category_name]</a> &#187; $forum[forums_name]";
        } else {
            $_PAGE[sub] = $forum[forums_name];
            $_PAGE[sub] .= " - " . $topic[posts_name];
            $_PAGE[url] = " &#187; <a href='?viewcat=$category[category_id]'>$category[category_name]</a> &#187; <a href='?act=showforum&amp;f=$_GET[f]'>$forum[forums_name]</a>";
            $_PAGE[url] .= " &#187; $topic[posts_name]";
        }
    }
} elseif($_GET[act] == "post") {
    $_PAGE[sub] = " Posting Message";
    $_PAGE[url] = " &#187; Posting Message";
} elseif($_GET[act] == "users") {
    $_PAGE[sub] = " Members";
    $_PAGE[url] = " &#187; Members";
} elseif($_GET[act] == "profile") {
    $_PAGE[sub] = " Viewing Member Profile";
    $_PAGE[url] = " &#187; <a href='?act=users'>Members</a> >> Profile";
} elseif($_GET[act] == "search") {
    $_PAGE[sub] = " Search";
    $_PAGE[url] = " &#187; Search";
} elseif($_GET[act] == "search-results") {
    $_PAGE[sub] = " Viewing Search Results";
    $_PAGE[url] = " &#187; <a href='?act=search'>Search</a> >> Results";
} elseif($_GET[act] == "logout") {
    $_PAGE[sub] = " Logout";
    $_PAGE[url] = " &#187; Logout";
} elseif($_GET[act] == "admin-panel") {
    $_PAGE[sub] = " Admin-Panel";
    $_PAGE[url] = " &#187; Admin-Panel";
}
?>