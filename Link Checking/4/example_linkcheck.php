<div style="font-family: verdana; font-size: 12px; border: 1px solid #EAEAEA; background-color: #F6F7F8; width: 270px; padding: 5px">

<div style="background-color: #F1F2F3; font-weight: bold; border: 1px dashed #A8A8A8; width: 260; padding: 5px">
<?php
	echo 'Status: ';
	if(isset($_POST['check'])){
		include('linkcheck.php');
	}
	else{
		echo 'Waiting for link';
	}
?>
</div>

<br />
<form method="post" action="example_linkcheck.php">
<p>Website? (http://)<br /><input type="text" name="check" size="40" maxlength="150" /></p>
<p>Contains Link? (http://)<br /><input type="text" name="for" size="40" maxlength="150" /></p>
<p><input type="submit" value="Check for link" /></p>
</form>
</div>