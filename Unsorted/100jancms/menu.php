<?php
/***************************************\
|					|
|	    100janCMS v1.01		|
|    ------------------------------	|
|    Supplied & Nulled by GTT '2004	|
|					|
\***************************************/
// do not cache
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    	        // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header ("Cache-Control: no-cache, must-revalidate");  	        // HTTP/1.1
header ("Pragma: no-cache");                          	        // HTTP/1.0
 
// Restrict acces to this page

//this page clearance
$arr = array (  
  '0' => 'ADMIN',
  '1' => 'ARTICLES_MASTER',
  '2' => 'COMMENTS_MASTER',  
  );
define('CONSTANT_ARRAY',serialize($arr));

// check
include 'restrict_access.php';

//configuration file
include 'config_inc.php'; 


//load user specific data
$query="SELECT * FROM ".$db_table_prefix."users WHERE username='".$_SESSION["current_user_username"]."'";
$result=mysql_query($query);
$row = mysql_fetch_array($result); //wich row

	//assign variables
	$user_privileges=$row["user_privileges"];
	$menu_state=$row["menu_state"];

?>
<html>
<head>
<title>100janCMS Articles Control</title>
<?php echo "$text_encoding";?>
<link href="cms_style.css" rel="stylesheet" type="text/css">

<style type="text/css">
body
{
background-image: 
url("images/app/menu_bg.png");
background-repeat: 
repeat-y;
background-attachment: 
fixed
}
</style>

<script language="JavaScript1.2">
var dayarray=new Array("Sun","Mon","Tue","Wed","Thu","Fri","Sat")
var montharray=new Array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec")

function getthedate(){
var mydate=new Date()
var year=mydate.getYear()
//var year = (mydate).getFullYear().toString().substring(2);

if (year < 1000)
year+=1900
var day=mydate.getDay()
var month=mydate.getMonth()
var daym=mydate.getDate()
if (daym<10)
daym="0"+daym
/*
if (daym==1)
daym=daym+"<SUP>st</SUP>"
if (daym==2)
daym=daym+"<SUP>nd</SUP>"
if (daym==3)
daym=daym+"<SUP>rd</SUP>"
if (daym>3)
daym=daym+"<SUP>th</SUP>"
*/
var hours=mydate.getHours()
var minutes=mydate.getMinutes()
var seconds=mydate.getSeconds()
var dn=""
if (hours>=12)
dn=""
if (hours>24){
hours=hours-12
}
if (hours==0)
hours=0
if (minutes<=9)
minutes="0"+minutes
if (seconds<=9)
seconds="0"+seconds
//change font size here
var cdate="<b>Date: </b>"+dayarray[day]+", "+montharray[month]+" "+daym+", "+year+"<br><b>Time:</b> "+hours+":"+minutes+":"+seconds+" "+dn+""
if (document.all)
document.all.clock.innerHTML=cdate
else
document.write(cdate)
}
if (!document.all)
getthedate()
function goforit(){
if (document.all)
setInterval("getthedate()",1000)
}


//------------------------------------------------------
var bV=parseInt(navigator.appVersion);
NS4=(document.layers) ? true : false;
IE4=((document.all)&&(bV>=4))?true:false;
ver4 = (NS4 || IE4) ? true : false;


function expandDiv(){return;}
function expandDivGrp(){return;}


//------------------------------------------------------
function initialize()
//------------------------------------------------------
{ 
  if (NS4) {
    ns_init();
  }
  else {
    ie_init();
  } 
}
//------------------------------------------------------
function expandDiv(el) 
//------------------------------------------------------
{
if (!ver4) return;
if (IE4) {expandIE(el)} 
}
//------------------------------------------------------
function expandDivGrp(isBot) 
//------------------------------------------------------
{ 
  newSrc = (isExpanded) ? "images/plus.gif" : "images/minus.gif";

  if (NS4) {

    ns_expandDivGrp(newSrc);
  }
  else {
    ie_expandDivGrp(newSrc);
  }

  isExpanded = !isExpanded;
}

//######################################################
//  IE functions
//######################################################

//------------------------------------------------------
function ie_init()
//------------------------------------------------------
{
    tempColl = document.all.tags("DIV");
    for (i=0; i<tempColl.length; i++) {

       if (tempColl(i).className == "child") tempColl(i).style.display = "none";
    }
}

//*********************************** 
//expand all
//------------------------------------------------------
function expandAll()
{
    divColl = document.all.tags("DIV");
    for (i=0; i<divColl.length; i++) {
      if (divColl(i).className == "child") {
//divColl(i).style.display = (isExpanded) ? "none" : "block";
divColl(i).style.display = "block";
      }
    }
// change images
document.images["imArticles"].src="images/app/minus.gif";
document.images["imComments"].src="images/app/minus.gif";
document.images["imVisitors"].src="images/app/minus.gif";
document.images["imUsers"].src="images/app/minus.gif";
document.images["imHelp"].src="images/app/minus.gif";
document.images["imAdmin"].src="images/app/minus.gif";

// set all cookies
document.cookie="ac_menu_articles=expand; path=/";
document.cookie="ac_menu_comments=expand; path=/";
document.cookie="ac_menu_visitors=expand; path=/";
document.cookie="ac_menu_users=expand; path=/";
document.cookie="ac_menu_help=expand; path=/";
document.cookie="ac_menu_admin=expand; path=/";
}

//collapse all
//------------------------------------------------------
function collapseAll()
{
    divColl = document.all.tags("DIV");
    for (i=0; i<divColl.length; i++) {
      if (divColl(i).className == "child") {
divColl(i).style.display = "none" ;
      }
    }
// change images
document.images["imArticles"].src="images/app/plus.gif";
document.images["imComments"].src="images/app/plus.gif";
document.images["imVisitors"].src="images/app/plus.gif";
document.images["imUsers"].src="images/app/plus.gif";
document.images["imHelp"].src="images/app/plus.gif";
document.images["imAdmin"].src="images/app/plus.gif";

// set all cookies
document.cookie="ac_menu_articles=collapse; path=/";
document.cookie="ac_menu_comments=collapse; path=/";
document.cookie="ac_menu_visitors=collapse; path=/";
document.cookie="ac_menu_users=collapse; path=/";
document.cookie="ac_menu_help=collapse; path=/";
document.cookie="ac_menu_admin=collapse; path=/";
}


//------------------------------------------------------
// functions to expand / collapse

function expandArticles() { 
whichEl = eval("elArticlesChild");

if (whichEl.style.display == "none") {
whichEl.style.display = "block";
document.images["imArticles"].src="images/app/minus.gif"; 
document.cookie="ac_menu_articles=expand; path=/";
}
else {
whichEl.style.display = "none";
document.images["imArticles"].src="images/app/plus.gif";
document.cookie="ac_menu_articles=collapse; path=/";
     }
}


function expandComments() { 
whichEl = eval("elCommentsChild");

if (whichEl.style.display == "none") {
whichEl.style.display = "block";
document.images["imComments"].src="images/app/minus.gif"; 
document.cookie="ac_menu_comments=expand; path=/";
}
else {
whichEl.style.display = "none";
document.images["imComments"].src="images/app/plus.gif";
document.cookie="ac_menu_comments=collapse; path=/";
     }
}


function expandVisitors() { 
whichEl = eval("elVisitorsChild");

if (whichEl.style.display == "none") {
whichEl.style.display = "block";
document.images["imVisitors"].src="images/app/minus.gif"; 
document.cookie="ac_menu_visitors=expand; path=/";
}
else {
whichEl.style.display = "none";
document.images["imVisitors"].src="images/app/plus.gif";
document.cookie="ac_menu_visitors=collapse; path=/";
     }
}


function expandUsers() { 
whichEl = eval("elUsersChild");

if (whichEl.style.display == "none") {
whichEl.style.display = "block";
document.images["imUsers"].src="images/app/minus.gif"; 
document.cookie="ac_menu_users=expand; path=/";
}
else {
whichEl.style.display = "none";
document.images["imUsers"].src="images/app/plus.gif";
document.cookie="ac_menu_users=collapse; path=/";
     }
}


function expandHelp() { 
whichEl = eval("elHelpChild");

if (whichEl.style.display == "none") {
whichEl.style.display = "block";
document.images["imHelp"].src="images/app/minus.gif"; 
document.cookie="ac_menu_help=expand; path=/";
}
else {
whichEl.style.display = "none";
document.images["imHelp"].src="images/app/plus.gif";
document.cookie="ac_menu_help=collapse; path=/";
     }
}


function expandAdmin() { 
whichEl = eval("elAdminChild");

if (whichEl.style.display == "none") {
whichEl.style.display = "block";
document.images["imAdmin"].src="images/app/minus.gif"; 
document.cookie="ac_menu_admin=expand; path=/";
}
else {
whichEl.style.display = "none";
document.images["imAdmin"].src="images/app/plus.gif";
document.cookie="ac_menu_admin=collapse; path=/";
     }
}

//------------------------------------------------------
//functions to expand only

function expandArticles_ex() { 
whichEl = eval("elArticlesChild");
whichEl.style.display = "block";
document.images["imArticles"].src="images/app/minus.gif"; 
document.cookie="ac_menu_articles=expand; path=/";
}

function expandComments_ex() { 
whichEl = eval("elCommentsChild");
whichEl.style.display = "block";
document.images["imComments"].src="images/app/minus.gif"; 
document.cookie="ac_menu_comments=expand; path=/";
}

function expandVisitors_ex() { 
whichEl = eval("elVisitorsChild");
whichEl.style.display = "block";
document.images["imVisitors"].src="images/app/minus.gif"; 
document.cookie="ac_menu_visitors=expand; path=/";
}

function expandUsers_ex() { 
whichEl = eval("elUsersChild");
whichEl.style.display = "block";
document.images["imUsers"].src="images/app/minus.gif"; 
document.cookie="ac_menu_users=expand; path=/";
}

function expandHelp_ex() { 
whichEl = eval("elHelpChild");
whichEl.style.display = "block";
document.images["imHelp"].src="images/app/minus.gif"; 
document.cookie="ac_menu_help=expand; path=/";
}

function expandAdmin_ex() { 
whichEl = eval("elAdminChild");
whichEl.style.display = "block";
document.images["imAdmin"].src="images/app/minus.gif"; 
document.cookie="ac_menu_admin=expand; path=/";
}

//------------------------------------------------------
//functions to collapse only

function collapseArticles_ex() { 
whichEl = eval("elArticlesChild");
whichEl.style.display = "none";
document.images["imArticles"].src="images/app/plus.gif";
document.cookie="ac_menu_articles=collapse; path=/";
}

function collapseComments_ex() { 
whichEl = eval("elCommentsChild");
whichEl.style.display = "none";
document.images["imComments"].src="images/app/plus.gif";
document.cookie="ac_menu_comments=collapse; path=/";
}

function collapseVisitors_ex() { 
whichEl = eval("elVisitorsChild");
whichEl.style.display = "none";
document.images["imVisitors"].src="images/app/plus.gif";
document.cookie="ac_menu_visitors=collapse; path=/";
}

function collapseUsers_ex() { 
whichEl = eval("elUsersChild");
whichEl.style.display = "none";
document.images["imUsers"].src="images/app/plus.gif";
document.cookie="ac_menu_users=collapse; path=/";
}

function collapseHelp_ex() { 
whichEl = eval("elHelpChild");
whichEl.style.display = "none";
document.images["imHelp"].src="images/app/plus.gif";
document.cookie="ac_menu_help=collapse; path=/";
}

function collapseAdmin_ex() { 
whichEl = eval("elAdminChild");
whichEl.style.display = "none";
document.images["imAdmin"].src="images/app/plus.gif";
document.cookie="ac_menu_admin=collapse; path=/";
}


//-->
</script>

</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" scroll="auto" class="maintext" 
onLoad="getthedate();goforit();initialize();
<?php 
//set menu state
// expanding
if (substr_count($menu_state, "articles=expand")<>"0") {echo "expandArticles_ex();";}
if (substr_count($menu_state, "comments=expand")<>"0") {echo "expandComments_ex();";}
if (substr_count($menu_state, "visitors=expand")<>"0") {echo "expandVisitors_ex();";}
if (substr_count($menu_state, "users=expand")<>"0") {echo "expandUsers_ex();";}
if (substr_count($menu_state, "help=expand")<>"0") {echo "expandHelp_ex();";}
if (substr_count($menu_state, "admin=expand")<>"0") {echo "expandAdmin_ex();";}
//collapsing
if (substr_count($menu_state, "articles=collapse")<>"0") {echo "collapseArticles_ex();";}
if (substr_count($menu_state, "comments=collapse")<>"0") {echo "collapseComments_ex();";}
if (substr_count($menu_state, "visitors=collapse")<>"0") {echo "collapseVisitors_ex();";}
if (substr_count($menu_state, "users=collapse")<>"0") {echo "collapseUsers_ex();";}
if (substr_count($menu_state, "help=collapse")<>"0") {echo "collapseHelp_ex();";}
if (substr_count($menu_state, "admin=collapse")<>"0") {echo "collapseAdmin_ex();";}
?>
">

<table width="181" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr align="left" valign="top"> 
    <td><a href="home.php" target="mainFrame" onFocus="if(this.blur)this.blur()"><img src="images/app/logo_cms.jpg" width="181" height="90" border="0" ></a><br>
      
	  <table width="180" height="100%" border="0" cellpadding="0" cellspacing="5" class="maintext">
        <tr>
          <td align="left" valign="top">
		    <img src="images/app/separate.jpg" width="163" height="1"><br>
            	<div class="maintext" id="clock"></div>
            <img src="images/app/separate.jpg" width="163" height="1"><br>

            <br>
            <!-- Articles start -->
            <?php 
$articles_open='
<!-- parent start -->
<div  class="parent" id="elArticlesParent">
			<table style="cursor:hand" onclick="expandArticles(); return false" width="163" border="0" cellpadding="0" cellspacing="0" background="images/app/n.jpg" bgcolor="#3C89D1" class="maintext2beli">
              <tr>
                <td>&nbsp;<img src="images/app/plus.gif" name="imArticles" width="9" height="9" border="0" align="absmiddle" id="imArticles">&nbsp;&nbsp;Articles</td>
              </tr>
            </table>
</div>
<!-- parent end -->

<!-- child start -->
<div class="child" id="elArticlesChild">									
            <img src="images/app/arrow.gif" width="5" height="9">&nbsp;&nbsp;<a href="articles_items_add.php" target="mainFrame" class="maintext2" onFocus="if(this.blur)this.blur()">Add new Article</a><br>
            <img src="images/app/arrow.gif" width="5" height="9">&nbsp;&nbsp;<a href="articles_items_search.php" target="mainFrame" class="maintext2" onFocus="if(this.blur)this.blur()">View/Edit Articles</a><br>

';
$articles_admin='<span class="spacer"><br></span>

            <img src="images/app/arrow.gif" width="5" height="9">&nbsp;&nbsp;<a href="articles_marker_add.php" target="mainFrame" class="maintext2" onFocus="if(this.blur)this.blur()">Add new Marker</a><br>
            <img src="images/app/arrow.gif" width="5" height="9">&nbsp;&nbsp;<a href="articles_marker_search.php" target="mainFrame" class="maintext2" onFocus="if(this.blur)this.blur()">View/Edit Markers</a><br>			

<span class="spacer"><br></span>

            <img src="images/app/arrow.gif" width="5" height="9">&nbsp;&nbsp;<a href="articles_cat_add.php" target="mainFrame" class="maintext2" onFocus="if(this.blur)this.blur()">Add new Category</a><br>
            <img src="images/app/arrow.gif" width="5" height="9">&nbsp;&nbsp;<a href="articles_cat_search.php" target="mainFrame" class="maintext2" onFocus="if(this.blur)this.blur()">View/Edit Categories</a><br>			
';

$articles_close='</div>
<!-- child end -->
<br>
';

if (substr_count($user_privileges, "ADMIN")=="0") {$articles_admin="";}

//check user_privileges
if ((substr_count($user_privileges, "ARTICLES_MASTER")<>"0") or (substr_count($user_privileges, "ADMIN")<>"0")) 
{ 
echo $articles_open.$articles_admin.$articles_close;
} 
else {$articles_dummy=1;}



?>
            <!-- Comments start -->
            <?php 
$comments='
<!-- parent start -->
<div class="parent" id="elCommentsParent">
			  <table style="cursor:hand" onclick="expandComments(); return false" width="163" border="0" cellpadding="0" cellspacing="0" background="images/app/n.jpg" bgcolor="#3C89D1" class="maintext2beli">
              <tr>
                <td>&nbsp;<img src="images/app/plus.gif" name="imComments" width="9" height="9" border="0" align="absmiddle" id="imComments">&nbsp;&nbsp;Comments</td>
              </tr>
            </table>
</div>
<!-- parent end -->

<!-- child start -->
<div class="child" id="elCommentsChild">						
            <img src="images/app/arrow.gif" width="5" height="9">&nbsp;&nbsp;<a href="comments_search.php" target="mainFrame" class="maintext2" onFocus="if(this.blur)this.blur()">View/Edit 
            Comments</a><br>
</div>
<!-- child end -->
            <br>
';

//check user_privileges
if ((substr_count($user_privileges, "COMMENTS")<>"0") || (substr_count($user_privileges, "ADMIN")<>"0")) //ove dve || su OR operator
{ 
echo "$comments";
} 
else {$comments_dummy=1;}

?>
            <!-- Visitors start -->
            <?php
$visitors='
<!-- parent start -->
<div class="parent" id="elVisitorsParent">
		    <table style="cursor:hand" onclick="expandVisitors(); return false" width="163" border="0" cellpadding="0" cellspacing="0" background="images/app/n.jpg" bgcolor="#3C89D1" class="maintext2beli">
              <tr> 
                <td>&nbsp;<img src="images/app/plus.gif" name="imVisitors" width="9" height="9" border="0" align="absmiddle" id="imVisitors">&nbsp;&nbsp;Visitors</td>
              </tr>
            </table>
</div>
<!-- parent end -->

<!-- child start -->
<div class="child" id="elVisitorsChild">						
            <img src="images/app/arrow.gif" width="5" height="9">&nbsp;&nbsp;<a href="visitors_search.php" target="mainFrame" class="maintext2" onFocus="if(this.blur)this.blur()">View/Edit 
            Visitors</a><br>
	
</div>
<!-- child end -->
			<br>
';

//check user_privileges
if (substr_count($user_privileges, "ADMIN")<>"0") 
{ 
echo "$visitors";
} 
else {$visitors_dummy=1;}

?>
            <!-- Users start -->
            <?php
$users='     
<!-- parent start -->
<div class="parent" id="elUsersParent">
			<table style="cursor:hand" onclick="expandUsers(); return false"  width="163" border="0" cellpadding="0" cellspacing="0" background="images/app/n.jpg" bgcolor="#3C89D1" class="maintext2beli">
              <tr> 
                <td>&nbsp;<img src="images/app/plus.gif" name="imUsers" width="9" height="9" border="0" align="absmiddle" id="imUsers">&nbsp;&nbsp;Users</td>				
              </tr>
            </table>
</div>
<!-- parent end -->

<!-- child start -->
<div class="child" id="elUsersChild">			
            <img src="images/app/arrow.gif" width="5" height="9">&nbsp;&nbsp;<a href="users_add.php" target="mainFrame" class="maintext2" onFocus="if(this.blur)this.blur()">Add 
            new User</a><br>
            <img src="images/app/arrow.gif" width="5" height="9">&nbsp;&nbsp;<a href="users_search.php" target="mainFrame" class="maintext2" onFocus="if(this.blur)this.blur()">View/Edit 
            Users</a><br>
</div>
<!-- child end -->
            <br>
';

//check user_privileges
if (substr_count($user_privileges, "ADMIN")<>"0") 
{ 
echo "$users";
} 
else {$users_dummy=1;}

?>
            <!-- Help start -->
<!-- parent start -->
<div class="parent" id="elHelpParent">
            <table style="cursor:hand" onclick="expandHelp(); return false" width="163" border="0" cellpadding="0" cellspacing="0" background="images/app/n.jpg" bgcolor="#3C89D1" class="maintext2beli">
              <tr> 
                <td>&nbsp;<img src="images/app/plus.gif" name="imHelp" width="9" height="9" border="0" align="absmiddle" id="imHelp">&nbsp;&nbsp;Help</td>
              </tr>
            </table>
</div>
<!-- parent end -->

<!-- child start -->
            <div class="child" id="elHelpChild"> <img src="images/app/arrow.gif" width="5" height="9">&nbsp;&nbsp;<a href="help_user_manual.php" target="mainFrame" class="maintext2" onFocus="if(this.blur)this.blur()">User 
              manual</a><br>
            
            <img src="images/app/arrow.gif" width="5" height="9">&nbsp; <a href="help_eula.php" target="mainFrame" class="maintext2" onFocus="if(this.blur)this.blur()">License Agreement</a><br>
            <img src="images/app/arrow.gif" width="5" height="9">&nbsp;&nbsp;<a href="help_about.php" target="mainFrame" class="maintext2" onFocus="if(this.blur)this.blur()">About</a><br>
</div>
<!-- child end -->

            <br>

            <!-- Admin start -->
<!-- parent start -->
<div class="parent" id="elAdminParent">
            <table style="cursor:hand" onclick="expandAdmin(); return false" width="163" border="0" cellpadding="0" cellspacing="0" background="images/app/n.jpg" bgcolor="#3C89D1" class="maintext2beli" >
              <tr> 
                  <td>&nbsp;<img src="images/app/plus.gif" name="imAdmin" width="9" height="9" border="0" align="absmiddle" id="imAdmin">&nbsp;&nbsp;Admin</td>
              </tr>
            </table>
</div>
<!-- parent end -->

<!-- child start -->
<div class="child" id="elAdminChild">
			<?php 
			//proveri
if (substr_count($user_privileges, "ADMIN")<>"0") 
{ 
echo '<img src="images/app/arrow.gif" width="5" height="9">&nbsp;&nbsp;<a href="config_edit.php" target="mainFrame" class="maintext2" onFocus="if(this.blur)this.blur()">View/Edit Configuration</a><br>';
} 	
			?>
            <img src="images/app/arrow.gif" width="5" height="9">&nbsp;&nbsp;<a href="logout.php" target="_top" class="maintext2" onFocus="if(this.blur)this.blur()">Logout</a> 

            <br>
</div>
<!-- child end -->
			
			<br>
            <br>
<!--            <a href="javascript:;" onclick="expandDivGrp(true); return false">expand / collapse all</a> -->
            <a href="javascript:;" onclick="expandAll()" onFocus="if(this.blur)this.blur()">expand all</a> / <a href="javascript:;" onclick="collapseAll()" onFocus="if(this.blur)this.blur()">collapse all</a>
<br>
 <!-- Dummies start -->
<?php 
if (isset($articles_dummy) and $articles_dummy==1) {echo '<div style="visibility: hidden;" class="parent" id="elArticlesParent"><img src="images/app/menu_dummy.gif" name="imArticles" width="0" height="0" align="absmiddle" id="imArticles"></div><div class="child" id="elArticlesChild"></div>';}
if (isset($comments_dummy) and $comments_dummy==1) {echo '<div style="visibility: hidden;" class="parent" id="elCommentsParent"><img src="images/app/menu_dummy.gif" name="imComments" width="0" height="0" align="absmiddle" id="imComments"></div><div class="child" id="elCommentsChild"></div>';}
if (isset($visitors_dummy) and $visitors_dummy==1) {echo '<div style="visibility: hidden;" class="parent" id="elVisitorsParent"><img src="images/app/menu_dummy.gif" name="imVisitors" width="0" height="0" align="absmiddle" id="imVisitors"></div><div class="child" id="elVisitorsChild"></div>';}
if (isset($users_dummy) and $users_dummy==1) 		{echo '<div style="visibility: hidden;" class="parent" id="elUsersParent"><img src="images/app/menu_dummy.gif" name="imUsers" width="0" height="0" align="absmiddle" id="imUsers"></div><div class="child" id="elUsersChild"></div>';}

?>
<!-- Dummies end -->

          </td>
        </tr>
      </table>
	  
</td>
  </tr>
</table>

</body>
</html>
