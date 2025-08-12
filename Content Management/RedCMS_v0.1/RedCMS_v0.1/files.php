<?php

  $l = "downloads";

  include"top.php";

  connect();

  if($file) {

    $sql = "SELECT * FROM redcms_files LEFT JOIN redcms_file_categories ON redcms_file_categories.cat_id = redcms_files.cat_id WHERE redcms_files.file_id = '" . $file . "'";

    $result = mysql_query($sql);

    $num = mysql_num_rows($result);

    if($num == 0) { echo "ERROR: File not found."; include"bottom.php"; exit(); }

    $fileID = mysql_result($result, $i, "redcms_files.file_id");
    $fileName = mysql_result($result, $i, "redcms_files.file_name");
    $fileDesc = mysql_result($result, $i, "redcms_files.file_desc");
    $fileLink = mysql_result($result, $i, "redcms_files.file_link");
    $fileSize = mysql_result($result, $i, "redcms_files.file_size");
    $fileDownloads = mysql_result($result, $i, "redcms_files.file_downloads");
    $fileDate = mysql_result($result, $i, "redcms_files.file_date");
    $fileTime = mysql_result($result, $i, "redcms_files.file_time");

    $catID = mysql_result($result, $i, "redcms_file_categories.cat_id");
    $catName = mysql_result($result, $i, "redcms_file_categories.cat_name");

    echo "<a href='?'>back</a><br><br>";

    echo "<b>Name: </b>" . $fileName . "<br>";
    echo "<b>Size: </b>" . $fileSize . "<br>";
    echo "<b>Downloads: </b>" . $fileDownloads . "<br>";
    echo "<b>Date: </b>" . $fileDate . "(" . $fileTime . ")<br>";
    echo "<b>Description: </b><br>" . $fileDesc . "<br><br>";
    echo "<a href='download.php?file=" . $fileID . "'><b>Download</b></a>";

  } else {

    $sql = "SELECT * FROM redcms_files LEFT JOIN redcms_file_categories ON redcms_file_categories.cat_id = redcms_files.cat_id ORDER BY redcms_file_categories.cat_name ASC";

    $result = mysql_query($sql);

    $num = mysql_num_rows($result);

    if($num == 0) { echo "ERROR: No files found."; include"bottom.php"; exit(); }

    echo'<table width="100%">';

    echo'<tr class="tr1"><td>Filename</td><td>Size</td><td>No of Downloads</td><td>Date</td></tr>';

    for($i=0; $i < $num; $i++) {

      $fileID = mysql_result($result, $i, "redcms_files.file_id");
      $fileName = mysql_result($result, $i, "redcms_files.file_name");
      $fileDesc = mysql_result($result, $i, "redcms_files.file_desc");
      $fileLink = mysql_result($result, $i, "redcms_files.file_link");
      $fileSize = mysql_result($result, $i, "redcms_files.file_size");
      $fileDownloads = mysql_result($result, $i, "redcms_files.file_downloads");
      $fileDate = mysql_result($result, $i, "redcms_files.file_date");
      $fileTime = mysql_result($result, $i, "redcms_files.file_time");

      $catID = mysql_result($result, $i, "redcms_file_categories.cat_id");
      $catName = mysql_result($result, $i, "redcms_file_categories.cat_name");

      if($catID != $lastCat) {

        if($e) { $option = "&e=1"; }
        if($d) { $option = "&d=1"; }

        if($cat == "all" || $cat == $catID) {
          $temp = '<a href="?' . $option . '">' . $catName . '</a>';
        } else {
          $temp = '<a href="?cat=' . $catID . $option . '">' . $catName . '</a>';
        }

        echo'<tr class="tr2"><td colspan="4">' . $temp . '</td></tr>';

      }

  if($e || $d) {   $option = "<td>"; }

   if($e && $_SESSION['redUserLevel'] == '10') {

    $option .= "[ <a href='file_add.php?edit=1&id=" . $fileID . "'>Edit</a> ] ";

  }

   if($d && $_SESSION['redUserLevel'] == '10') {

    $option .= "[ <a href='file_add.php?delete=1&id=" . $fileID . "'>Delete</a> ] ";

  }

  if($e || $d) {   $option .= "</td>"; }

      if($cat == $catID || $cat == "all") {

        echo'<tr class="tr3"><td><a href="?file=' . $fileID . '">' . $fileName . '</a></td><td>' . $fileSize . ' mb</td><td>' . $fileDownloads . '</td><td>' . $fileDate . ' (' . $fileTime . ')</td>' . $option . '</tr>';

      }

      $lastCat = $catID;

    }

    echo'<tr class="tr1"><td colspan="4" align="right">Powered by <a href="http://redcms.co.uk" target="_blank">RedCMS</a> copyright &copy; 2004+</td></tr>';

    echo'</table>';

  }

  include"bottom.php";

?>
