<?php

    /*=====================================================================
	// $Id: ping.php,v 1.5 2005/08/01 14:55:12 carsten Exp $
    // copyright evandor media Gmbh 2003
	//=====================================================================*/

	include ("inc/pre_include_standard.inc.php");
	
	$css_path = get_skin_css_path ($user_id);
	$img_path = get_skin_img_path ($user_id);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <title></title>
  

<!-- pings every two minutes to refresh online information -->
<meta http-equiv="Refresh" content="<?=$PING_TIMER?>; url=ping.php">
</head>
<body marginwidth=0 marginheight=0 topmargin=0 leftmargin=0>

<?php

	// delete old ( > 3 min) entries
	if (!isset ($PING_TIMER)) $PING_TIMER = 120;
	$old_time = time() - ($PING_TIMER + 60); 
	$res = mysql_query ("DELETE FROM ".TABLE_PREFIX."useronline 
	                     WHERE user_id='".$_SESSION['user_id']."'
						 AND timestamp<$old_time");
	logDBError (__FILE__, __LINE__, mysql_error());
    
	foreach ($_SESSION['current_views'] AS $key => $view) {
     	$query = "UPDATE ".TABLE_PREFIX."useronline 
                  SET timestamp='".time()."' 
	              WHERE user_id     = '".$_SESSION['user_id']."' AND 
    	                object_type = '".$view[0]."' AND
    	                object_id   =  ".$view[1];
        echo $query;
        mysql_query ($query);
	    logDBError (__FILE__, __LINE__, mysql_error(), $query);
	}    
 	
?>

ping...
</body>
</html>