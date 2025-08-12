<?php
    require_once('class.text_on_image.php');
		$text_on_image = &new text_on_image();
?>
<div align="center">
<h2>Contact Us</h2>
<form action="<?php echo $XFA['action']; ?>" method="post">
<p>Email: <br><input type="text" name="email" value="
<?php
    echo $_POST['email'];
?>
" size="40"></p>
<p>Subject: <br><input type="text" name="subject" size="40" value="
<?php
    echo $_POST['subject'];
?>
"></p>
<p>Message: <br><textarea rows="8" cols="40" name="body">
<?php
    echo $_POST['body'];
?>
</textarea></p>
<p>
<?php    
    echo 'Submit Code is <img src="'.index().module().'/image.php"><br><br>';
		echo 'Submit Code:';
		echo $text_on_image->showCodeBox();    
?>
</p>
<p><input type="submit" name="submit" value=" Send "></p>
</form>
</div>
