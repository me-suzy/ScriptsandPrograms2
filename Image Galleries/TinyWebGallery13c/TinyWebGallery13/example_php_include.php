
<html >
<head>
<style>

.top {
	background : #eeeeee;
	padding: 3px;
}
.left {
	background : #cccccc;
	padding: 3px;
}


</style>
<title>TinyWebGallery integration example</title>
</head>

<body leftmargin="0" rightmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table cellpadding='0' cellspacing='0' border='1' height=100%>
<tr>
<td colspan=2 height="70" class="top">
Top row - the borders of the table is set to 1. if you use include - the only problem could be the stylesheet where some parts could overwritten. If you have weired displays - this is the place to search ;)<br />
</a>
</td>
</tr>
<tr>
<td class="left">Here comes maybe&nbsp;the&nbsp;navigation of your web page- use a spacerimage for exact width of this cell<br />&nbsp;<br /><br/> Or you can display a random image here:<br/>&nbsp;<br />
<a href ='example_php_include.php?twg_random=1'>
<img src="image.php?twg_album=Domai|Mexico+%282004%29&twg_random=1&twg_type=random&twg_random_size=100" />
</a>
</td>
<td width="100%" height="100%"><?php include "index.php"  ?>
</td>
</tr>
</table>
</body>
</html>