<?php
// EDIT THESE VARIABLES
$file = "bookmark_counter.dat";

// DON'T EDIT BELOW
if ($action) {

  if ($action=="bookmark") {
  $open = fopen($file, "a+");
  $date = date("d-m-Y");

    if ($url && $title) {
      if (fwrite($open, "$date,$url\n")) {
      echo("<script language=\"JavaScript\"><!--\n");
      echo("window.external.addFavorite('$url', '$title');\n");
      echo("location.href='$url';\n");
      echo("// --></script>\n");
      echo("<noscript>Sorry, you don't have JavaScript enabled. Please click back and hit CTRL+D on your keyboard to bookmark.</noscript>\n");
      }
      else {
      echo("Error! Could not write to file $file.");
      }
    }
    else {
    echo("Error! Title and URL not specified.");
    }

  fclose($open);
  }
  elseif ($action=="showcount") {
  $num = count($file);
  $line = file($file);
  echo("Page has been bookmarked <b>$num</b> times.<br>\n");
  echo("<table>\n<tr><td><b>Date</b></td><td><b>URL Bookmarked</b></td></tr>\n");

    for ($i=0;$i<$num;$i++) {
    list($date, $url) = split(",", $line[$i]);
    echo("<tr><td>$date</td><td><a href='$url'>$url</a></tr>\n");
    }

  echo("</table>\n");
  echo("<form action='$PHP_SELF' method=post>\n");
  echo("<input type=hidden name='action' value='reset'>\n");
  echo("<input type=submit value='Reset Counter'>\n");
  echo("</form>\n");
  }
  elseif ($action=="reset") {
  $open = fopen($file, "w");

    if (fwrite($open, " ")) {
    echo("Counter reset.");
    }
    else {
    echo("Error! Counter could not be reset.");
    }

  fclose($open);
  }
  else {
  echo("Error! Action not recognized.");
  }

}
else {
echo("Error! No action specified.");
}
?>