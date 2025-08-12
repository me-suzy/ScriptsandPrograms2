<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php  
	while(list($key, $value) = each($HTTP_GET_VARS))
	{
		if ($key != "show")
		{
			if ($key == "anSize")
			{
				//--- php obscurity: an urlencoded qoute (") is decoded as /"
				$strURL = $strURL."&$key=".urlencode(stripslashes($value));
			}
			else
			{
				$strURL = $strURL."&$key=".urlencode($value);
			}
		}	
	}  
?>
<html>
<head>
	<title></title>
</head>
<frameset rows="40,*" framespacing="0" border="0" frameborder="0">
	<frame name="Toolbar" src="printbar.php?<?php echo $strURL;?>" marginwidth="0" marginheight="0" scrolling="no" frameborder="0" noresize>
	<frame name="Main" src="../circulation_detail.php?<?php echo $strURL;?>&view=print">" marginwidth="0" marginheight="0" frameborder="0" scrolling="auto">
</frameset>

</html>
