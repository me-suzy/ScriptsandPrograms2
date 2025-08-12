<? ob_start();
include "../common.php";
 
 if ($_GET["flag"]) {
    db_query("alter table {$tableprefix}tblforum modify column sticky bool default 0 NOT NULL");
    db_query("alter table {$tableprefix}tblsubforum modify column sticky bool default 0 NOT NULL");
    
    echo("Update successful. Proceed to <a href=\"../index.php\">Index</a>.");
 } else {

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta name="GENERATOR" content="Microsoft FrontPage 4.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
<title>Baal Smart Form (Update)</title>
<LINK href="../incl/style2.css" rel=stylesheet>
<SCRIPT LANGUAGE="JavaScript1.2" SRC="incl/all.js" TYPE='text/javascript'></SCRIPT>

</head>

<body bgcolor="<?=$bgcolor?>">

Here you can update your forum database.
What happen when you click "Update" button:

<ul>
<li>Fixed bug with the column "sticky", which causes incorrect ordering of the posts.</li>
</ul>

<b>Please, be sure, that database user account, which will used while updating (it's taken from your current settings), have permissions for altering the tables.</b>

<form action="update1.php">
<input type="hidden" name="flag" value="1">
<input type="submit" value="Update">
</form>

</body>
</html>
<?}
ob_end_flush();?>