<?php

  $l = "stats";

  include"top.php";

  connect();

  $sql = "SELECT * FROM redcms_hit_counters ORDER BY redcms_hit_counters.counter_name ASC";

  $result = mysql_query($sql);

  $num = mysql_num_rows($result);

  if($num == 0) { echo "ERROR: No Stats Found."; include "bottom.php"; exit(); }

  echo "<table width='100%'>";

  echo "<tr class='tr1'><td>Name</td><td>Hits</td><td></td><td></td></tr>";

  for($i = 0; $i < $num; $i++) {

    $counterID = mysql_result($result, $i, "redcms_hit_counters.counter_id");
    $counterName = mysql_result($result, $i, "redcms_hit_counters.counter_name");
    $counterSite = mysql_result($result, $i, "redcms_hit_counters.counter_site");

    echo "<tr class='tr2'><td><a href='?id=" . $counterID . "'>" . $counterName ."</a></td><td>" . getHits($counterID) . "</td><td><a href='?id=" . $counterID . "'>View Stats</a></td><td><a href='" . $counterSite . "' target='_blank'>Link</a></td></tr>";

  }

  echo "</table>";

  if($id) {

    echo "<br><br>";

    stats($id);

  }

  include"bottom.php";

?>
