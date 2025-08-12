<form action="<?php echo $PHP_SELF; ?>" method="post">
<input type="hidden" name="makedoorway" value="yes">
<input type="hidden" name="doorwaymaker" value="yes">
<input type="hidden" name="file" value="<?php echo $file;?>">
<font face="Arial" size="2">Here is the code to the doorway page you created. You can add/edit/delete 
anything from it you'd like to, then when you're finished click "Create Doorway Page". If you would 
like to create the HTML file manually, copy the code below and paste it into an HTML file, save it, then 
upload it to your server, and submit it to search engines. Otherwise, click the "Create Doorway Page" button below:</font><br>
<textarea cols="55" rows="20" name="page"><?php include("$doorway");?></textarea><br><br>
<input type="submit" name="submit" value="Create Doorway Page">
</form>
