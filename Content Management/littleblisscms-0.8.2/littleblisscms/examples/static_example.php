<?php
require_once("../cms_class.inc") ;
$content = new cms_class("../xml/content.xml") ;	
$content->test_editing() ;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>LittleBlissCMS</title>
	<link rel="stylesheet" type="text/css" href="/main.css">
	<?php echo $content->tinymce_init() ;?>
</head>
<body>
<table>
	<tr>
		<td width="10%">
			<?php echo $content->display_static_section("example_navigation") ;?>
		</td>
		<td>
			<?php echo $content->display_static_section("static2") ;?>
		</td>
	</tr>
	<tr>
		<td>
			&nbsp;
		</td>
		<td class="content">
			This section shows a new section, login then hit edit...
			<br />
			<?php echo $content->display_static_section("static4") ;?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo $content->display_auth_link();?>
		</td>
	</tr>
</table>
</body>
</html>