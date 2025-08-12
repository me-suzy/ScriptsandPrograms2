<div class="title">Modify News</div>
<?php

  include("config.php");
  $db = mysql_connect($dbhost,$dbuser,$dbpass); 
  mysql_select_db($dbname) or die("Cannot connect to database");

  $id = $_REQUEST['id'];
  $query = mysql_query("SELECT * FROM qlitenews WHERE id='$id'");
  $r = mysql_fetch_array($query);

?>
  <form name="post-news" method="post" action="index.php?page=newsprocess&amp;action=modify&amp;id=<?php echo $id; ?>"> 
    <p>
      Title: <input type="text" name="title" value="<?php echo $r[title]; ?>" size="25"/><br/><br/>
      Author: <input type="text" name="author" value="<?php echo $r[author]; ?>" size="25"/><br/><br/>
      News:<br/>
      <textarea name="news" cols="50" rows="10"><?php echo $r[news]; ?></textarea>
    </p>
    <p align="right"><a href="index.php?page=newsprocess&amp;action=del&amp;id=<?php echo $id; ?>"><span style="color: #ff0000; font-weight: bold;">Delete this news!</span></a> <input type="submit" name="submit_news" value="Modify News" style="cursor:pointer"/></p>
  </form> 
  <p>* HTML tags is ENABLE, if you want to break to a new line you must use the &lt;br&gt; tag. For links, bold text etc. you can use HTML tags.<br/>
     * Date is generated automatically, you can edit the date format in config.php.
  </p>