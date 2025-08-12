<?php

  $l = "stats";

  include"top.php";

  access(10);

  connect();

  if($add) {

    $sql = "INSERT INTO redcms_hit_counters VALUES ('', '" . $counterName . "', '" . $counterSite . "')";

    mysql_query($sql) or die("ERROR: Failed to execute SQL query");

    echo "Counter has been added to the database.<br><br>";

    $sql = "SELECT * FROM redcms_hit_counters ORDER BY redcms_hit_counters.counter_id DESC";

    $result = mysql_query($sql);

    $id = mysql_result($result, "0", "redcms_hit_counters.counter_id");

    echo "Add:<br>";

    echo '&lt?php hit(' . $id . '); ?&gt';

    echo "<br>To the page you want to keep track of.";

  }

  if($edit) {

    $sql = "UPDATE redcms_hit_counters SET redcms_hit_counters.counter_name = '" . $counterName . "', redcms_hit_counters.counter_site = '" . $counterSite . "' WHERE redcms_hit_counters.counter_id = '" . $id . "' LIMIT 1";

    mysql_query($sql) or die("ERROR: Failed to execute SQL query");

    echo "Counter has been updated.";

  }

  if($delete) {

    $sql = "DELETE FROM redcms_hit_counters WHERE redcms_hit_counters.counter_id = '" . $id . "' LIMIT 1";

    mysql_query($sql) or die("ERROR: Failed to execute SQL query");

    echo "Counter has been removed from the database.";

  }

  if($a || $ed) {


    if($ed) {

      $sql = "SELECT * FROM redcms_hit_counters WHERE redcms_hit_counters.counter_id = '" . $ed . "'";

      $result = mysql_query($sql);

      $num = mysql_num_rows($result);

      if($num == 0) { echo "ERROR: Counter not found in database."; include"bottom.php"; exit(); }

      $counterID = mysql_result($result, "0", "redcms_hit_counters.counter_id");
      $counterName = mysql_result($result, "0", "redcms_hit_counters.counter_name");
      $counterSite = mysql_result($result, "0", "redcms_hit_counters.counter_site");

    }

?>

<form method="post" enctype="multipart/form-data" action="<?php echo $PHP_SELF?>">

  <table>

    <tr><td>Counter Name:</td><td><input type="text" name="counterName" value="<?php echo $counterName; ?>"></td></tr>
    <tr><td>Counter Site:</td><td><input type="text" name="counterSite" value="<?php echo $counterSite; ?>"></td></tr>

  </table>

  <input type="hidden" name="id" value="<?php echo $ed; ?>">

<?php

  if($a) {

  echo'<input type="Submit" name="add" value="Add">';

  } else if($ed) {

  echo'<input type="Submit" name="edit" value="Edit">';

  }

?>

</form>

<?php


  }

  if($del) {

    $sql = "SELECT * FROM redcms_hit_counters WHERE redcms_hit_counters.counter_id = '" . $del . "'";

    $result = mysql_query($sql);

    $num = mysql_num_rows($result);

    if($num == 0) { echo "ERROR: Counter not found in database."; include"bottom.php"; exit(); }

    $counterID = mysql_result($result, "0", "redcms_hit_counters.counter_id");
    $counterName = mysql_result($result, "0", "redcms_hit_counters.counter_name");

?>

Are you sure you want to delete <?php echo $counterName; ?>?

<form method="post" action="<?php echo $PHP_SELF?>">

  <input type="hidden" name="id" value="<?php echo $del; ?>">

  <input type="Submit" name="delete" value="Yes">

</form>


<?php

  }

  // Fetch a list of counters

  $sql = "SELECT * FROM redcms_hit_counters ORDER BY redcms_hit_counters.counter_name ASC";

  $result = mysql_query($sql);

  $num = mysql_num_rows($result);

  if($num == 0) { echo "ERROR: No counters found in database."; include"bottom.php"; exit(); }

  echo "<table>";

    echo "<tr class='tr1'><td>Counter</td><td></td><td></td></tr>";

  for($i = 0; $i < $num; $i++) {

    $counterID = mysql_result($result, $i, "redcms_hit_counters.counter_id");
    $counterName = mysql_result($result, $i, "redcms_hit_counters.counter_name");
    $counterSite = mysql_result($result, $i, "redcms_hit_counters.counter_site");

    $options = "";

    if($e || $ed) { $options .= "<a href='?ed=" . $counterID . "'>Edit</a>"; }
    if($d || $del) { $options .= "<a href='?del=" . $counterID . "'>Delete</a>"; }

    $rID = $counterID;

    if($counterID == $id) { $counterID = -1; }

    echo "<tr class='tr2'><td><a href='?v=" . $counterID . "'>" .$counterName . "</a></td><td><a href='?v=" . $counterID . "'>View stats</a></td><td>" . $options . "</td></tr>";

    if($v == $rID) {

      echo"<tr class='tr3'><td colspan='3'>" . stats($id) . "</td></tr>";

    }

  }

  echo "</table>";

  include"bottom.php";

?>