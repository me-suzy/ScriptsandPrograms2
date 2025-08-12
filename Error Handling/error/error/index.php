<?
$homepage = "mywebpage.com";

	if(isset($_GET['error'])){
		if($_GET['error'] == 400){
			$title = 'Error 400 - BAD REQUEST!';
			$message = 'The server didn\'t understand the request.';
		}elseif($_GET['error'] == 401){
			$title = 'Error 401 - UNAUTHoRIZED!';
			$message = 'You are not unauthorized to view the page requested.';
		}elseif($_GET['error'] == 403){
			$title = 'Error 403 - ACCESS FORBIDDEN!';
			$message = 'Access for this request was denied.';
		}elseif($_GET['error'] == 404){
			$title = 'Error 404 - PAGE NOT FOUND!';
			$message = 'File not found! ';
		}elseif($_GET['error'] == 406){
			$title = 'Error 404 - NOT ACCEPTABLE!';
			$message = 'The resource cannot be displayed! ';
		}elseif($_GET['error'] == 500){
			$title = 'Error 500 - INTERNAL SERVER ERROR!';
			$message = 'The server encountered an unexpected condition which prevented it from fulfilling the request.';
		}elseif($_GET['error'] == 502){
			$title = 'Error 502 - BAD GATEWAY!';
			$message = 'The server, while acting as a gateway or proxy, received an invalid response from the upstream server.';
		}elseif($_GET['error'] == 504){
			$title = 'Error 504 - GATEWAY TIMEOUT!';
			$message = 'The server, while acting as a gateway or proxy, did not receive a timely response from the upstream server.';
		}
	}
			if($error==""){
			$title = 'Error Script by bluEyez';
			$message = '';}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HEAD>
<TITLE>[ <? echo $title ?> ]</TITLE>
<META http-equiv=Content-Type content="text/html; charset=iso-8859-1">
<META HTTP-EQUIV=Refresh CONTENT="30; URL=http://<? echo $homepage ?>">
<STYLE>
BODY {
	FONT-SIZE: 10px;
	BACKGROUND: url(http://<? echo $homepage ?>/error/bg.gif);
	COLOR: #333333; LINE-HEIGHT: 180%;
	FONT-FAMILY: tahoma, arial;
}
A {
	PADDING-RIGHT: 1px;
	PADDING-LEFT: 1px;
	PADDING-BOTTOM: 1px;
	COLOR: #ff6600;
	PADDING-TOP: 1px;
	TEXT-DECORATION: none
}
A:hover {
	PADDING-RIGHT: 1px;
	PADDING-LEFT: 1px;
	PADDING-BOTTOM: 1px;
	COLOR: #ff6600;
	PADDING-TOP: 1px;
	TEXT-DECORATION: underline;
}
TD {
	FONT-SIZE: 10px;
	COLOR: #333333;
	LINE-HEIGHT: 180%;
	FONT-FAMILY: tahoma, arial;
}
.box {
	BORDER-RIGHT: #e3e3e3 1px solid;
	BORDER-TOP: #e3e3e3 1px solid;
	BACKGROUND: #ffffff;
	BORDER-LEFT: #e3e3e3 1px solid;
	WIDTH: 400px;
	BORDER-BOTTOM: #e3e3e3 1px solid;
}
</STYLE>
</HEAD>
<BODY>
<CENTER><BR><BR><BR><BR>
<DIV class=box>
<BR><BR>
<H3><? echo $title; ?><br><? echo $message; ?></H3>
<br>
<DL>
<DD>
You will be automatically redirected to  www.<? echo $homepage ?> in 30 seconds.<br>
If u don't wish to wait click <a href="http://<? echo $homepage ?>">here</a>.
<br><a href="http://www.HotScripts.com/?RID= N402513"><img src="http://images.hotscripts.com/static/2.gif" alt="" border=""></a><br>© 2005 <A href="http://xsteam.org/">bluEyez</A>
</DD>
</DL>

</DIV>
</CENTER>
</BODY>
</HTML>