<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>GateQuest php Site Recommender - Popup Method</title>
	<link rel="stylesheet" href="recommend.css" type="text/css">
	<script language="javascript" type="text/javascript">
	<!--
	var win=null;
	
	function NewWindow(mypage,myname,w,h,scroll,pos){
	
		if(pos=="random")
			{LeftPosition=(screen.width)?Math.floor(Math.random()*(screen.width-w)):100;
			TopPosition=(screen.height)?Math.floor(Math.random()*((screen.height-h)-75)):100;}

		if(pos=="center")
			{LeftPosition=(screen.width)?(screen.width-w)/2:100;
			TopPosition=(screen.height)?(screen.height-h)/2:100;}
	
		else if((pos!="center" && pos!="random") || pos==null){LeftPosition=0;TopPosition=20}
	
	settings='width='+w+',height='+h+',top='+TopPosition+',left='+LeftPosition+',scrollbars='+scroll+',location=no,directories=no,status=no,menubar=no,toolbar=no,resizable=yes';

	win=window.open(mypage,myname,settings);}
	// -->
	</script>
</head>

<body>

<table width="100%" height="100%">
	<tr>
		<td height="65" style="padding: 10px 0px 0px 10px"><a href="javascript:history.go(-1);"><img alt="" border="0" width="100" height="65" src="/art/gq_logo_back.gif"></a></td>
	</tr>
	<tr>
		<td align="center" valign="middle"><a href="recommend.popup.php" onclick="NewWindow(this.href,'GateQuest','450','450','no','center');return false" onfocus="this.blur()">Recommend Us</a></td>
	</tr>
</table>

</body>
</html>
