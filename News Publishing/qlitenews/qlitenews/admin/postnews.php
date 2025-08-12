<div class="title">Post News</div>
  <form name="post-news" method="post" action="index.php?page=newsprocess&amp;action=post"> 
    <p>
      Title: <input type="text" name="title" size="25"/><br/><br/>
      Author: <input type="text" name="author" size="25"/><br/><br/>
      News:<br/>
      <textarea name="news" cols="50" rows="10"></textarea>
    </p>
    <p align="right"><input type="reset" name="reset_news" value="Reset Form" style="cursor:pointer"/>&nbsp;<input type="submit" name="submit_news" value="Post News" style="cursor:pointer"/></p>
      <input type="hidden" name="ip" value="<?php echo $_SERVER["REMOTE_ADDR"]; ?>"/>
  </form> 
  <p>* HTML tags is ENABLE, for links, bold text etc. you can use HTML tags.<br/>
    * Date is generated automatically, you can edit the date format in config.php.
  </p>