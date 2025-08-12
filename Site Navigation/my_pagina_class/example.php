<?php
include($_SERVER['DOCUMENT_ROOT']."/classes/my_pagina/my_pagina_class.php");

$test = new MyPagina;

$test->sql = "SELECT * FROM links"; // the (basic) sql statement (use the SQL whatever you like)
$result = $test->get_page_result(); // result set
$num_rows = $test->get_page_num_rows(); // number of records in result set 
$nav_links = $test->navigation(" | ", "currentStyle"); // the navigation links (define a CSS class selector for the current link)
$nav_info = $test->page_info("to"); // information about the number of records on page ("to" is the text between the number)
$simple_nav_links = $test->back_forward_link(); // the navigation with only the back and forward links
$total_recs = $test->get_total_rows(); // the total number of records
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>MyPagina example page</title>
<style type="text/css">
<!--
body {
	font-family:"Courier New", Courier, mono;
}
.currentStyle {
	font-size:1.5em;
	font-weight:bold;
}
-->
</style>
</head>

<body>
<h2>Example page for MyPagina class</h2>
<p>The page navigation (pagination) of lists with records from a (MySQL) database is often used on a lot of dynamic websites. This class is extremely easy to use in your existing application: just use your own SQL statement and voila you get information like the result set (you need this for later output, page navigation links and the (current) number of records. While using this class all other query string parts are included inside the page links. An example database table (with data) is included.</p>
<p>This information shows the current range of records and the total number of records.</p>
<p><b><?php echo "rec. ".$nav_info." of ".$total_recs; ?></b></p>
<p>&nbsp;</p>
<p>Here the records (id and titel):</p>
<?php 
for ($i = 0; $i < $num_rows; $i++) {
	$titel = mysql_result($result, $i, "titel");
	$id = mysql_result($result, $i, "id");
	echo ($id < 9) ? "&nbsp;".$id : $id;
	echo " -> ".$titel."<br>\n";
}
echo "<hr>\n";
echo "<p>The navigation() method is showing this kind of links:</p>";
echo "<p>".$nav_links."</p>";
echo "<p>Notice the large number of the current link, you can modify the style with CSS.</p>";
echo "<hr>\n";

echo "<p>The back_forward_link() method, shows only for- and backward links (you can use it for small recordsets):</p>";
echo "<p>".$simple_nav_links."</p>";
?>
</body>
</html>
<?php $test->free_page_result(); // if your result set is large then free the result here ?>  

