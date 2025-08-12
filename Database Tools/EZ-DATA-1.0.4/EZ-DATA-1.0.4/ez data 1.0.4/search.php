<?php
/*
#############################################
#
#
# Programmer: Mike Koenig
# Contact: techwizz78@yahoo.com
# Program: EZ-Data 1.0.2 beta
#
# Changes: 1.0.2 beta adds more fields to the data entry form,
# more search options, cleaner code, data entry verification for $name 
# and $email to prevent from blank entries.
#
# Date Last Modified: 12-27-04
#
#
# License: Free Under The GNU
# We are not responsible for any damage caused by this program,
# it is still in its testing phases.
# 
##################################################
*/
include ('includes/menu.inc');

?>
<html>
<head>
  <title>Search Database</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"></head>

<body background="images/bg.jpg" text="#000000" link="#000000" vlink="#000000" alink="#000000">
<p align="center">Search </p>
<form action="results.php" method="post">
  <div align="center">Choose Search Type:<br>
    <select name="searchtype">
      <option value="name" selected>Name</option>
      <option value="city">City</option>
      <option value="state">State</option>
      <option value="zip">Zip</option>
      <option value="email">Email</option>
    </select>
    <br>
    Enter Search Term:<br>
    <input name="searchterm" type=text size="30" maxlength="30">
    <br>
    <input name="search" type=submit id="search" value="Find">
  </div>
</form>

</body>
</html>

<?php
include ('includes/footer.inc');
?>
