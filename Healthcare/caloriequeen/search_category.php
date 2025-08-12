<html>
<head>

</head>
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" background="images/thinbarbkg.gif">
<table cellspacing="0" cellpadding="0" border="0" height="66" width="100%" background="images/topbarbkg.gif">
  <tr valign="top"> 
    <td width="796" height="58"><img src="images/topbar.gif" width="758" height="133"></td>
  </tr>
</table>
<table cellspacing="0" cellpadding="0" border="0" height="208" width="745">
  <tr> 
    <td width="149" height="2" valign="top"> 
      <div align="left"> 
        <table width="115" border="0" bgcolor="#009999" bordercolor="#009999" align="center" cellpadding="1" cellspacing="1">
          <tr>
            <td height="19"><b><font face="Arial" size="2" color="#FFFFFF">Search Foods</font></b></td>
          </tr>
			<tr>
            <td height="2" bordercolor="#000000" style="border: 1px solid #000000">
			<b><font face="Arial" size="2">
			<a href="searchbyfood.php">
			<font color="#FFFFFF">By Name</font></a></font></font></b></td>
          </tr>
			<tr>
            <td height="2" bordercolor="#000000" style="border: 1px solid #000000">
			<b><font face="Arial" size="2">
			<a href="searchbycategory.php"><font color="#FFFFFF">By Category</font></a></font></font></b></td>
          </tr>
			<tr>
            <td bordercolor="#000000" style="border: 1px solid #000000">
			<b><font face="Arial" size="2"><a href="food_menu.php">
			<font color="#FFFFFF">Admin</font></a></font></b></td>
          </tr>
          <tr bgcolor="#009999" valign="top"> 
            <td>&nbsp;</td>
          </tr>
        </table>
      </div>
    </td>
    <td width="596" height="2" valign="top"> 
      <div align="left">

        <form name="form" action="search_category.php" method="get">
			<p align="right"><b><font size="2" face="Arial">Search Foods By 
			Category:</font></b> 
<input type="text" name="id" size="40" /> <input type="submit" name="Submit" value="Search" /></p>
		</form>
		<table border="0" width="100%" id="table1">
			<tr>
				<td><?php

  // Get the search variable from URL
  $var = @$_GET['id'] ;
  $trimmed = trim($var); //trim whitespace from the stored variable

// rows to return
$limit=10; 

// check for a search parameter
if (!isset($var))
  {
  echo "<p>We dont seem to have a search parameter!</p>";
  exit;
  }

$db_name = "";
$table_name = "foodcomp";
$connection = @mysql_connect("localhost", "", "") 
	or die(mysql_error());
$db = @mysql_select_db($db_name, $connection) or die(mysql_error());

// Build SQL Query  
$query = "select * from $table_name where food_category like \"%$trimmed%\"  
  order by food_category";  // EDIT HERE and specify your table and field names for the SQL query

 $numresults=mysql_query($query, $connection);
 $numrows=mysql_num_rows($numresults);

// If we have no results, offer a google search as an alternative

if ($numrows == 0)
  {
  echo "<h4>Results</h4>";
  echo "<p>Sorry, your search: &quot;" . $trimmed . "&quot; returned zero results</p>";

// google
 echo "<p><a href=\"http://www.google.com/search?q=" 
  . $trimmed . "\" target=\"_blank\" title=\"Look up 
  " . $trimmed . " on Google\">Click here</a> to try the 
  search on google</p>";
  }

// next determine if s has been passed to script, if not use 0
  if (empty($s)) {
  $s=0;
  }

// get results
  $query .= " limit $s,$limit";
  $result = @mysql_query($query, $connection) or die (mysql_error());

// display what the person searched for

echo "<p>You searched for: &quot;" . $var . "&quot;</p>";

// begin to show results set
echo "Results<br>";
$count = 1 + $s ;

// now you can display the results returned
 $food_list .= "<ul>";
	while ($row = mysql_fetch_array ($result)) {
$id = $row['id'];
$food_name = $row['food_name'];
$food_category = $row['food_category'];
echo "$count.)&nbsp;<a href=\"nutritionfacts.php?id=$id\">$food_category, $food_name</a><br>";
  $count++ ; 

$food_list .= "</ul>";
  
  }

$currPage = (($s/$limit) + 1);

//break before paging
  echo "<br />";

  // next we need to do the links to other results
  if ($s>=1) { // bypass PREV link if s is 0
  $prevs=($s-$limit);
  print "&nbsp;<a href=\"$PHP_SELF?s=$prevs&q=$var\">&lt;&lt; 
  Prev 10</a>&nbsp&nbsp;";
  }

// calculate number of pages needing links
  $pages=intval($numrows/$limit);

// $pages now contains int of pages needed unless there is a remainder from division

  if ($numrows%$limit) {
  // has remainder so add one page
  $pages++;
  }

// check to see if last page
  if (!((($s+$limit)/$limit)==$pages) && $pages!=1) {

  // not last page so give NEXT link
  $news=$s+$limit;

  echo "&nbsp;<a href=\"$PHP_SELF?s=$news&q=$var\">Next 10 &gt;&gt;</a>";
  }

$a = $s + ($limit) ;
  if ($a > $numrows) { $a = $numrows ; }
  $b = $s + 1 ;
  echo "<p>Showing results $b to $a of $numrows</p>";
  
?>
