<html>
<head><title>Admin Control Panel</title>
<style type="text/css">
<!--
     A:link {text-decoration: none;}
     A:visited {text-decoration: none;}
-->
</style>
</head>
<body bgcolor="#008080" link="#FF0000" vlink="#FF0000" alink="#FF0000">
<center>
<table border="3" cellpadding="2" cellspacing="6" width=600 bgcolor="#FFFFFF">
    <tr>
        <td bgcolor="#004080" align="left"><font
        color="#FFFFFF" size="2" face="Verdana"><strong>Admin Panel</strong></font>
        </td>
    </tr>
    <tr>
        <td align="center"><table border="0" width=550>
            <tr>
                <td valign="top" width=50%><br>
<!-- #### HTML #### -->

<?
   include("config.php");

   //--- check if the admin password is correct
   if($admin_pass != $pass){
?>

<p><br>
<blockquote>
<form action=admin.php method=post>
<Font face=Verdana size=2><b>Admin Password: </b>
<input name=pass size=15 type=password></li>
<input type=submit value=Enter>
<p><br>

<?
  } else {


   if($action=="list"){
	include("lib/template.inc");
	include("lib/quiz_lib.php");
	include("lib/adminlist.php");
	adminlist();
   }elseif($action=="add"){
	include("lib/adminadd.php");
	adminadd();
   }elseif($action=="added"){
	include("lib/template.inc");
	include("lib/quiz_lib.php");
	include("lib/adminadd.php");
	adminadded();
   }else {

	print "<b>Admin Panel of Quiz Manager</b>";
	print "<ul>";
    	print "<li><a href=admin.php?pass=$pass&action=list>List All Quizes</a>";
    	print "<li><a href=admin.php?pass=$pass&action=add>Add New Quiz</a>";
	print "</ul>";

   }












  }


?>

<!-- #### HTML #### -->
<p><br><a href=admin.php?pass=<? print $pass; ?>>Admin Panel</a>
                </td>
            </tr>
        </table>
        </td>
    </tr>
</table>
</center>
</body>
</html>

