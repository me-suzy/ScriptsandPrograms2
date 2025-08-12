<div class="title">News Process</div>
<?php

  include("config.php");
  $action = $_REQUEST['action'];
  $id = $_REQUEST['id'];

  $title = $_POST['title'];
  $author = $_POST['author'];
  $news = $_POST['news'];
  $news = nl2br($news);
  $ip = $_POST['ip'];
  $date = date($news_date);

  if ($action == "post") {
    if ($_POST['submit_news']) {
      if ($title == "")  { echo "<strong>Error:</strong> Please enter a title for this news. <a href=\"index.php?page=postnews\">Click here</a> to continue."; }
      else if ($author == "") { echo "<strong>Error:</strong> Please enter an author name. <a href=\"index.php?page=postnews\">Click here</a> to continue."; }
      else if ($news == "")  { echo "<strong>Error:</strong> Please enter a news. <a href=\"index.php?page=postnews\">Click here</a> to continue."; }
      else { 
        $db = mysql_connect($dbhost,$dbuser,$dbpass); 
        mysql_select_db($dbname) or die("Cannot connect to database");
        mysql_query("INSERT INTO qlitenews(author,title,news,date,ip) VALUES('$author','$title','$news','$date','$ip')"); 
        echo "<strong>News has been posted successfully!</strong> <a href=\"index.php\">Click here</a> to continue.";
        mysql_close($db);
      }
    }
  }
  else if ($action == "modify") {
    if ($_POST['submit_news']) {
      $db = mysql_connect($dbhost,$dbuser,$dbpass); 
      mysql_select_db($dbname) or die("Cannot connect to database");
      mysql_query("UPDATE qlitenews SET title='$title' WHERE id='$id'");
      mysql_query("UPDATE qlitenews SET author='$author' WHERE id='$id'");
      mysql_query("UPDATE qlitenews SET news='$news' WHERE id='$id'");
      echo "<strong>News has been updated successfully!</strong> <a href=\"index.php\">Click here</a> to continue.";
      mysql_close($db);
    }
  }
  else if ($action == "del") { 
    $db = mysql_connect($dbhost,$dbuser,$dbpass); 
    mysql_select_db($dbname) or die("Cannot connect to database");
    mysql_query("DELETE FROM qlitenews WHERE id='$id'");
    echo "<strong>News has been deleted successfully!</strong> <a href=\"index.php\">Click here</a> to continue.";
    mysql_close($db);
  }
  else { echo "<a href=\"index.php\">Click here</a> to continue. $action"; }

?>