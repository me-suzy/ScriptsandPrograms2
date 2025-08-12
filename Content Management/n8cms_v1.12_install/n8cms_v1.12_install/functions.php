<?

$username = $_SESSION['username'];
$usr_lvl = $_SESSION['user_level'];
$page_id=$_GET[page_id];
$pg_title=$_GET[pg_title];
$userid=$_SESSION[userid];
$lang['months'] = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
$month = date("m");
$y = date("Y");
$day = date("j");
$date=date("l dS of F  Y h:i A");
$datetime = date("Y-m-d h:i");
$lt_date = date ("Ymd");

$dir=$_GET['dir'];
	if (!$dir){$dir="home";}
	else{$dir="$dir";}

define("DB_TABLE","$dir");			//db_table

function page_id(){
if (!$page_id)
	{$page_id=1;
		}
		else {$page_id=$_GET[page_id];
	 }

}
function dirs()
{echo "\nAdd a page to: <select name=dirs onChange='document.location=options[selectedIndex].value'>\n";
$tables= mysql_list_tables(DB_NAME);
$i=-1;
while ($i < mysql_num_rows($tables)){
	$tbl_name=mysql_tablename($tables,$i);
	//lists all tables but 'users'
		if ( ($tbl_name=="users")||($tbl_name=="comment")||($tbl_name=="banned_ip") ){echo '';}
		else {echo "<option value=addpage.php?dir=".$tbl_name.">".$tbl_name."</option>\n";}
	$i++;
	}
echo "</select>";}

function monthPullDown($month, $montharray)
{
	echo "\n<select name=\"month\">\n";
	for($i=0;$i < 12; $i++) {
		if ($i != ($month - 1)) {
			echo "	<option value=\"" . ($i + 1) . "\">$montharray[$i]</option>\n";
		} else {
			echo "	<option value=\"" . ($i + 1) . "\" selected>$montharray[$i]</option>\n";
		}
	}
	echo "</select>\n\n";
}
function yearPullDown($year)
{
	echo "<select name=\"year\">\n";
	$z = 1;
	for($i=0;$i < 3; $i++) {
		if ($z == 0) {
			echo "	<option value=\"" . ($year - $z) . "\" selected>" . ($year - $z) . "</option>\n";
		} else {
			echo "	<option value=\"" . ($year - $z) . "\">" . ($year - $z) . "</option>\n";
		}
		$z--;
	}
	echo "</select>\n\n";
}

function dayPullDown($day)
{
	echo "<select name=\"day\">\n";
	for($i=1;$i <= 31; $i++) {
		if ($i == $day) {
			echo "	<option value=\"$i\" selected>$i</option>\n";
		} else {
			echo "	<option value=\"$i\">$i</option>\n";
		}
	}
	echo "</select>\n\n";
}

function pageheader ()
		 {
		  include('incl/header.php');
		}
function pagefooter () 
	{
	  include('incl/footer.php');
	}
function disclaimer () 
	{
	  include('incl/disclaimer.php');
	}

function login()
	{
	include ('html/login_form.html');
	}
function logout()
{
	if(!isset($_REQUEST['logmeout'])){
		echo "Are you sure you want to logout?<br />";
		echo "<a class=nav_links href=logout.php?logmeout>Yes</a> | <a class=nav_links href=javascript:history.back()>No</a><br>";
	} else {
		session_destroy();
		if(!session_is_registered('first_name')){
			echo "<font color=red><strong>You are now logged out!</strong></font><br />";
			echo "<a href=index.php>Home</a><br>";
			include ('html/login_form.html');		
	}
}
}
function css(){$config[css];}
function editorpageheader()
{
		 {
		  css();
		  echo "<h3>".$_SESSION['username']." is an Editor</h3>";
		  echo dirs()."<br>";
		  echo "| <a class=nav_links href='admin_funtions.php'>Admin Home</a> | 
		  <a class=nav_links href='edituser.php'>edit user info</a> |
		  <a class=nav_links href=changepw.php>Change Password</a> |
		   <a class=nav_links href=logout.php?logmeout accesskey=l>Logout</a> | 
		  ";
		}
}


function Adminpageheader ()
		 {
		  css();
		  echo "<h3>".$_SESSION['username']." is an Admin</h3>";
		  echo " ".dirs()."<br>";
		  echo " <a class=nav_links href='add_dir.php'>Add Directory</a>  | 
				<a class=nav_links href='admin_funtions.php'>Admin Home</a> |
			<a class=nav_links href='aprv.php'>pages not published</a> |
				<a class=nav_links href='edituser.php'>edit user info</a> |
				<a class=nav_links href=changepw.php>Change Password</a> |
				<a class=nav_links href=logout.php?logmeout accesskey=l>Logout</a> |";
  }
function Mastpageheader(){
		  css();
		  echo "<h3>".$_SESSION['username']." is a Master Admin</h3>";
		  echo " ".dirs()."<br>";
		  echo"<a class=nav_links href='add_dir.php'>Add Dir</a> | 
			<a class=nav_links href='admin_dir.php'>Drop Dir</a> | 
			<a class=nav_links href='admin_funtions.php'>Admin Home</a> | 		    
			<a class=nav_links href='aprv.php'>pages not published</a> |
			<a class=nav_links href='edituser.php'>Edit Users</a> |
			<a class=nav_links href='add_user.php'>Add a User </a> |
			<a class=nav_links href=changepw.php>Change Password</a> | 
			<a class=nav_links href=logout.php?logmeout accesskey=l>Logout</a>| ";
		   
}
function dietypageheader(){
			  css();
		  echo "<h3>".$_SESSION['username']." is a Deity</h3>";
		  echo dirs()."<br>";
		  echo "<a class=nav_links href='add_dir.php'>Add Dir</a> | 
		  <a class=nav_links href='admin_dir.php'>Drop Dir</a> | 
		  <a class=nav_links href='admin_funtions.php'>Admin Home</a> | 
		  <a class=nav_links href='aprv.php' class=nav_links>pages not published</a>  |
		  <a class=nav_links href='edituser.php'>Edit Users</a> |
		  <a class=nav_links href='add_user.php'>Add a User </a> |
		  <a class=nav_links href=logout.php?logmeout accesskey=l>Logout</a> | 
		  <a class=nav_links href=changepw.php>Change Password</a> |  ";


}
?>
