<?PHP
include'../config.php';
echo "
<HTML>
<HEAD>
<TITLE>EKINboard</TITLE>
<link rel=\"stylesheet\" type=text/css href=style.css>
</HEAD>
<BODY LEFTMARGIN=0 TOPMARGIN=5 MARGINWIDTH=0 MARGINHEIGHT=0>";
if($_SESSION[user_level]>1){

} else {
	echo "<center><span class=red>You need to be an admin to access this page!</span></center>";
}
?>