<?php

  include"redcms_news_config.php";
  include"top.php";

  access(10); // Restrict access to admins only

  // Connect to database

  connect();

  if($delete) {

    $sql = "DELETE FROM redcms_news WHERE redcms_news.news_id = '" . $id . "' LIMIT 1";

    $result = mysql_query($sql) or die("ERROR: Unable to delete news article");

    echo "News article has been deleted";

  }

  if($del) {

?>

Are you sure you want to delete this news article?

<br><br>

<form method="post" action="<?php echo $PHP_SELF;?>">

    <table>

    <tr><td><input type="hidden" value="<?php echo $id; ?>" name="id"></td><td></td></tr>

    <tr><td><input type="submit" value="Yes" name="delete"></td><td></td></tr>

    </table>

</form>

<br><br>

<?php

  }


  // Fetch from database

  if($id) {

    $sql = "SELECT * FROM redcms_news LEFT JOIN redcms_users ON redcms_news.user_id = redcms_users.user_id WHERE redcms_news.news_id ='" . $id . "'";

  } else {

    $sql="SELECT * FROM redcms_news LEFT JOIN redcms_users ON redcms_news.user_id = redcms_users.user_id ORDER BY redcms_news.news_id DESC";

  }

  $result = mysql_query($sql);
  $num = mysql_num_rows($result);

  if($num == 0) {

    echo "ERROR: No News Articles Found.";

    include"bottom.php";

    exit();

  }

  echo '<div class="redNews"><table width="100%">';

  echo'<tr><td><b>Title</b></td><td><b>Date</b></td><td><b>Author</b></td><td></td></tr>';

  for($i=0; $i<$num; $i++) {

    $newsID = mysql_result($result,$i,"redcms_news.news_id");
    $newsDate = mysql_result($result,$i,"redcms_news.news_date");
    $newsTime = mysql_result($result,$i,"redcms_news.news_time");
    $newsFormatDate = mysql_result($result,$i,"redcms_news.news_rdate");
    $newsTitle = mysql_result($result,$i,"redcms_news.news_title");
    $newsText = mysql_result($result,$i,"redcms_news.news_text");

    $authorID = mysql_result($result,$i,"redcms_users.user_id");
    $authorUName = mysql_result($result,$i,"redcms_users.user_uname");

    $options = "";

    if($e) { $options .= "[ <a href='news_add.php?id=" . $newsID . "&e=1'>Edit</a> ]"; }
    if($d) { $options .= "[ <a href='news_view.php?id=" . $newsID . "&del=1'>Delete</a> ]"; }

    echo'<tr><td>' . $newsTitle . '</td><td>' . $newsDate . '</td><td><a href="profile.php?id=' . $authorID . '">' . $authorUName . '</td><td>' . $options . '</td></tr>';


  }

  echo '</table></div>';



?>

<br>

<div class="redNews">
  This news script is powered by <a href='http://www.redcms.co.uk' target='_blank'>RedCMS : RedNews Script</a>.
</div>

<?php

  include"bottom.php";

?>
