<? ob_start();
include "../common.php";
 
 if ($_GET["flag"]) {
    $query="alter table {$tableprefix}tblforum add column position integer NULL";
    $result=mysql_query($query);
    if (!$result) {
        die("DataBase error while updating: " . mysql_error() . ". Used query string: <b>" . $query . "</b>");
    }
    
    $query="alter table {$tableprefix}tblforum add column sticky bool DEFAULT 0 NOT NULL";
    $result=mysql_query($query);
    if (!$result) {
        die("DataBase error while updating: " . mysql_error() . ". Used query string: <b>" . $query . "</b>");
    }
    
    $query="alter table {$tableprefix}tblsubforum add column sticky bool DEFAULT 0 NOT NULL";
    $result=mysql_query($query);
    if (!$result) {
        die("DataBase error while updating: " . mysql_error() . ". Used query string: <b>" . $query . "</b>");
    }
    
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
<li>Column "position" will be added into forum table.</li>
<li>Column "sticky" will be added into forum table.</li>
<li>Column "sticky" will be added into subforum table.</li>
</ul>

<b>Please, be sure, that database user account, which will used while updating (it's taken from your current settings), have permissions for altering the tables.</b>

<form action="update.php">
<input type="hidden" name="flag" value="1">
<input type="submit" value="Update">
</form>

</body>
</html>
<?}
ob_end_flush();?>