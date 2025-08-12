<?php

include("plog-functions.php");
include("plog-globals.php");

// this is our comment script, it simply writes the comment information
// to our flat-file database and links it to the picture using the 
// pictures id.

// Loosly validate url string format without actually checking the link (cause that takes time)
function check_url($url) {
    if (preg_match('#^http\\:\\/\\/[a-z0-9\-]+\.([a-z0-9\-]+\.)?[a-z]+#i', $url)) {
        return "http";
    } 
    else if (preg_match('#^[a-z0-9\-]+\.([a-z0-9\-]+\.)?[a-z]+#i', $url)) {
        return "nohttp";
    }
    else {
        return "badurl";
    }
} 

// first get all the neccessary variables

if (check_url($_POST["url"]) == "http"){
    $url = $_POST["url"];
}
else if (check_url($_POST["url"]) == "nohttp"){
    $url = "http://".$_POST["url"];
}
else{
    $url = "";
}

global $config;
$parent_id = intval($_POST["parent"]);
require_once('admin/plog-admin-functions.php');
$redirect = generate_url("picture",$parent_id);
	
$rv = add_comment($parent_id,$_POST["author"],$_POST["email"],$url,$_POST["comment"]);

// redirect back to picture page
if ($rv["errors"]) {
	// will this work?
	$_SESSION["comment_post_error"] = $rv["errors"];
};
header("Location: $redirect");
?>
