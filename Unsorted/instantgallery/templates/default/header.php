<html>
<head>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<STYLE TYPE="text/css">
<!--
.scrolltxt 			{ font-size:10px; font-family:verdana, arial, helvetica; font-weight:bold }
.scrolltxt A 		{ color:#000; text-decoration:none }
.scrolltxt A:hover 	{ color:#000; text-decoration:underline }
// end hiding -->
</STYLE>

<!-- begin necessary javascript: do not remove or modify -->
	<script language="JavaScript">
		function imgSubmit(s) {
			document.img.method = "post";
			document.img.action = s;
			document.img.submit();
		}

		function imgList() {
			var imglist = new Array('<? echo implode("', '", $imglist); ?>');
	
			for (var i=0; i < imglist.length; i++) {
				document.writeln ("<input type=\"hidden\" name=\"imglist[]\" value=\"" + imglist[i] + "\">\n");	
			}
		}
	</script>
<!-- end necessary javascript: do not remove or modify -->
</head>

<body>
<!-- begin necessary javascript: do not remove or modify -->
<form name="img"><script language="JavaScript">imgList();</script></form>
<!-- end necessary javascript: do not remove or modify -->