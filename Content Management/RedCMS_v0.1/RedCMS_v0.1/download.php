<?php

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

    echo '<meta http-equiv="Refresh" content="2;url=' . $fileLink . '">';

    echo "<b>" . $fileName . " should start downloading in 2 seconds. If your download does not start please click <a href=''" . $fileLink . "'>here</a></b>";

    $fileDownloads++;

    $sql = "UPDATE redcms_files SET redcms_files.file_downloads = '" . $fileDownloads . "' WHERE redcms_files.file_id = '" . $fileID . "'";

    $result = mysql_query($sql);

  } else {

    echo "You should visit the <a href='files.php'>files</a> page.";

  }

  include"bottom.php";

?>