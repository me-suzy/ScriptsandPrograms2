<?


//template switch functions


function templatetype($type,$id){

switch ($type) {

case 'home':
inc_home($type,$id);
break;


case 'content':

 inc_cont($type,$id);
  break;

case 'news':
inc_news($type,$id);
 break;
 
 
 case 'competition':
 inc_comp($type,$id);
 break;
 
 
 case 'interview':
 inc_interview($type,$id);
 break;
 

 case 'gallery':
inc_gallery($type,$id);
 break;

 case 'contact':
 inc_contact($type,$id);
 break;
 
 case 'registration':
 inc_registration($type,$id);
 break;
 
 
 case 'venue':
 inc_venue($type,$id);
 break;
 
 case 'events':
 inc_djp($type,$id);
 break;

 }// end case

}//end function



//---------------------------------------------include templates
// include home template
function inc_home($type,$id) {
require("home.php");
}//end function


// include content template
function inc_cont($type,$id) {
require("content.php");
}//end function


// include news template
function inc_news($type,$id) {
$sql = "SELECT * FROM menu WHERE id ='$id'";
$query = mysql_query($sql) or die("Cannot query the database.<br>" . mysql_error());

while($result = mysql_fetch_array($query)) {
$name = stripslashes($result["name"]);
}

global $n_name;
$n_name = $name;

require("news.php");
}//end function


// include gallery template
function inc_gallery($type,$id) {
$sql = "SELECT * FROM menu WHERE id ='$id'";
$query = mysql_query($sql) or die("Cannot query the database.<br>" . mysql_error());

while($result = mysql_fetch_array($query)) {
$name = stripslashes($result["name"]);
}

global $n_name;
$n_name = $name;
require("gallery.php");
}//end function


// include contact template
function inc_contact($type,$id) {
require("contact.php");
}//end function


// include registration template
function inc_registration($type,$id) {
require("registration.php");
}//end function




//-----------------include FRONT PAGE LEVEL...................................//
//function to get from page news 
function getnews($type,$id) {

global $id,$type;

$sql = "SELECT * FROM menu WHERE id ='$id'";
$query = mysql_query($sql) or die("Cannot query the database.<br>" . mysql_error());

while($result = mysql_fetch_array($query)) {
$news = stripslashes($result["news"]);
$snews = stripslashes($result["show_n_page"]);
}

if ($news == "yes") {

// do this to get all news

switch ($snews) {

case 'all_news_pages':

$snid = "all";

require("home_news.php");

break;

default :
$sql2 = "SELECT * FROM menu WHERE name ='$snews'";
$query2 = mysql_query($sql2) or die("Cannot query the database.<br>" . mysql_error());

while($result = mysql_fetch_array($query2)) {
$snid = stripslashes($result["id"]);

}

require("home_news.php");
break;

}//end case
}// end if
}//end function


//function to get local news
function get_lnews($type,$id) {
$sql2 = "SELECT * FROM menu WHERE name ='Local' and deleted = '0'";
$query2 = mysql_query($sql2) or die("Cannot query the database.<br>" . mysql_error());

while($result = mysql_fetch_array($query2)) {
$snid = stripslashes($result["id"]);
}
require("local_news.php");
}//end function







?>
