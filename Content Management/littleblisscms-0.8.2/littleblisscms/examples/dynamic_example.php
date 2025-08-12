<?php
require_once("../cms_class.inc") ;
$content = new cms_class("../xml/content.xml") ;	
$content->test_editing() ;
$section = $content->set_dynamic_section('example_default') ; //Only needed if dynamic sections are used.
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>LittleBlissCMS</title>
	<link rel="stylesheet" type="text/css" href="/main.css">
	<?php echo $content->tinymce_init() ;?>
</head>

<div class="navigation">
	<?php echo $content->display_static_section("example_navigation") ;?>
</div>

<!-- Pretty basic layout...-->
<div class="content">
	<br />
	<?php echo $content->display_static_section('static1') ; ?>
	<br />
	<?php echo $content->display_dynamic_section($section) ;?>
	<br />
	<?php echo $content->display_static_section('static2') ; ?>
	<br />	
</div>

<!-- Display the login/logout box in the top right corner -->
<div style="position:absolute ; right : 5px ; top : 5px ;">
	<?php echo $content->display_auth_link();?>
</div>

</body>
</html>

