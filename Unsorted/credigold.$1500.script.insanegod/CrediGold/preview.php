<?

include("prepend.php3");

global $account;

?>

<body bgcolor="white">

<link rel=stylesheet href=modules/styles.css>

<script language=JavaScript src=modules/mod_gen.js></script>

<table border=0 width=550 cellspacing=3 cellpadding=0 align=center>

<tr>

<td colspan=2 bgcolor=D0D0D0><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td colspan=2 bgcolor=F3F3F3><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td colspan=2 align=center class=head height=40><b>

<script>

var s = parent.opener.send;

var logo = (s.logo.value.length > 0)?"&nbsp;<img src=\""+s.logo.value+"\" vspace=5 hspace=5>":"&nbsp;<img src=\"images/crediGold.gif\" vspace=5 hspace=5>";

if (s.com.value.length > 0)

	document.write(logo+"<br>"+s.com.value+"'s Shopping Cart");

else

	document.write(logo+"<br><font color=red>Error! No Company Used.</font>");

</script>

</td>

</tr>

<tr>

<td colspan=2 bgcolor=F3F3F3><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td colspan=2 bgcolor=D0D0D0><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td colspan=2 align=center class=text>

<script>

var s = parent.opener.send;

if (s.header.value.length > 0)

	document.write("<div align=center><p align=justify style=padding:5px;width:500px>"+s.header.value+"<br></p>");

</script>

</td>

</tr>

<tr>

<td align=right class=little height=20 width=30%>Shopping Cart Holder:&nbsp;</td>

<td align=left class=little width=70%>&nbsp;<b><?=$account?></b></td>

</tr>

<tr>

<td colspan=2 bgcolor=F3F3F3><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td align=right class=little height=20>Account Status:&nbsp;</td>

<td align=left class=little>&nbsp;<b style=color:green>Verified</b></td>

</tr>

<tr>

<td colspan=2 bgcolor=F3F3F3><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td align=right class=little height=20>Transfer Amount:&nbsp;</td>

<script>

var s = parent.opener.send;

if (s.fiz.value.length > 0)

	document.write("<td align=left class=little>&nbsp;<b style=color:orange>"+s.fiz.value+"</b> <img src=images/gold.gif></td></tr>");

else

	document.write("<td align=left class=little>&nbsp;<input type=text name=amount size=<? print (isGecko())?"5":"10"; ?> <? if (!isNS()) print "class=note"; ?>></td></tr>");

</script>

<tr>

<td colspan=2 bgcolor=F3F3F3><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td align=right class=little valign=top height=20>Transfer Memo:&nbsp;</td>

<script>

var s    = parent.opener.send;

var pat  = /\n/ig;

var memo =  s.memo.value.replace(pat, "<br>");

if (s.memo.value.length > 0)

	document.write("<td align=left class=little>&nbsp;"+memo+"</td>");

else

	document.write("<td align=left class=little>&nbsp;<textarea rows=5 name=memo maxlength=500 cols=<? print (isGecko())?"26":"29"; ?> <? if (!isNS()) print "class=boxG"; ?>></textarea></td>");

</script>

</tr>

<tr>

<td colspan=2 bgcolor=F3F3F3><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td colspan=2 bgcolor=white><img src=images/dot.gif width=1 height=30></td>

</tr>

<tr>

<td colspan=2 class=text align=left>

&nbsp;&nbsp;<img src=images/point.gif><font class=text>&nbsp;&nbsp;<a href=account.php?cmd=register>Not Registered? Register now FREE...</font></a><br>

&nbsp;&nbsp;<img src=images/point.gif><font class=text>&nbsp;&nbsp;Already an user? Please, login from below:<br>

</td>

</tr>

<tr>

<td colspan=2 class=text align=center>

<br>

	<table border=0 width=330 cellspacing=3 cellpadding=2>

	<tr>

	<td bgcolor=D0D0D0>

		<table border=0 width=100% cellspacing=0 cellpadding=0>

		<tr>

		<td align=right width=30% bgcolor=white class=little>User Number:</td>

		<td align=left width=70% bgcolor=white class=little height=25>&nbsp;<input type=text name=userNumber disabled  class=note size=30 maxlength="7">

		&nbsp;<img src=images/dot.gif name="userNumber" width=7 height=10></td>

		</tr>

		<tr>

		<td colspan=2 bgcolor=F3F3F3><img src=images/dot.gif width=1 height=1></td>

		</tr>

		<tr>

		<td align=right bgcolor=white width=30% class=little>Username:</td>

		<td align=left bgcolor=white width=70% class=little height=25>&nbsp;<input type=text name=username  disabled class=note size=30>

		&nbsp;<img src=images/dot.gif name="username" width=7 height=10></td>

		</tr>

		<tr>

		<td colspan=2 bgcolor=F3F3F3><img src=images/dot.gif width=1 height=1></td>

		</tr>

		<tr>

		<td align=right bgcolor=white width=30% class=little>Password:</td>

		<td align=left bgcolor=white width=70% class=little height=25>&nbsp;<input type=password disabled name=password class=note size=30>&nbsp;<img src=images/dot.gif name="password" width=7 height=10></td>

		</tr>

		<tr>

		<td colspan=2 bgcolor=F3F3F3><img src=images/dot.gif width=1 height=1></td>

		</tr>

		<tr>

		<td colspan=2 align=center width=100% class=text bgColor=white height=30>&nbsp;&nbsp;<img src="access_code.php" width="200" height="30" border=0 alt="Access Code" align=absmiddle><input type=text name=access_codea class=access_code size=7>&nbsp;<img src=images/dot.gif name="access_code" width=7 height=10></td>

		</tr>

		<tr>

		<td colspan=2 bgcolor=F3F3F3><img src=images/dot.gif width=1 height=1></td>

		</tr>

		<tr>

		<td colspan=2 align=center width=100% class=text bgColor=white height=30><input type=submit value="Login at <?=$_Config["masterRef"]?>" name=post class=note style=" cursor: hand"></td>

		</tr>

		</table>

	</td>

	</tr>

	</table>

</td>

</tr>

</table>

