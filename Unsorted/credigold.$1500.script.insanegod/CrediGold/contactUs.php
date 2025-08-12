<?

include("prepend.php3");

page_open(array("sess" => "User_Session"));



// Question Post - Security Redirection

if (isset($question) && isset($author))

	{

		$headers   = "From: $author <$email>\n";

		$headers  .= "X-Sender: <".$_Config["masterEmail"].">\n"; 

		$headers  .= "X-Mailer: PHP\n"; // mailer

		$headers  .= "Return-Path: <".$_Config["masterEmail"].">\n";  // Return path for errors

		$headers  .= "Content-Type: text/html; charset=iso-8859-1\n";



		@mail($_Config["masterEmail"], $subject, $question, $headers);

		Header("Location: contactUs.php?cmd=thanks");

	}

// End of Question Post



initPage();

if (empty($cmd))

	{

?>

<div align=center><img src="images/logos/contact.jpg" width="221" height="45" border=0 alt="Contact Us"></div>

<br>

<p align=justify class=text style=width:500px>

&nbsp;&nbsp;Fill all the fields below and try to describe your problem as visually as possible so we could give you the best solution to it.

<br><br>

<form name=contact action=contactUs.php method=POST onSubmit="return postIt();">

<table border=0 width=430 cellspacing=3 cellpadding=0 align=center>

<tr>

<td colspan=2 bgcolor=D0D0D0><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td align=center class=text colspan=2 bgColor=F9F9F9><b>Contact Us Form</td>

</tr>

<tr>

<td colspan=2 bgcolor=D0D0D0><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td align=right class=little width=27%>Your name:&nbsp;</td>

<td align=left class=little width=73%>&nbsp;<input type=text name=author onBlur="unclear('author')" size=<? print (isGecko())?"35":"40"; ?> <? if (!isNS()) print "class=note"; ?>>&nbsp;<img src=images/dot.gif name=author width=7 height=10></td>

</tr>

<tr>

<td colspan=2 bgcolor=F3F3F3><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td align=right class=little>Your email:&nbsp;</td>

<td align=left class=little>&nbsp;<input type=text name=email onBlur="unclear('email')" size=<? print (isGecko())?"35":"40"; ?> <? if (!isNS()) print "class=note"; ?>>&nbsp;<img src=images/dot.gif name=email width=7 height=10></td>

</tr>

<tr>

<td colspan=2 bgcolor=F3F3F3><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td align=right class=little>Subject:&nbsp;</td>

<td align=left class=little>&nbsp;<input type=text name=subject onBlur="unclear('subject')" size=<? print (isGecko())?"35":"40"; ?> <? if (!isNS()) print "class=note"; ?>>&nbsp;<img src=images/dot.gif name=subject width=7 height=10></td>

</tr>

<tr>

<td colspan=2 bgcolor=F3F3F3><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td align=right class=little valign=top>Question:&nbsp;</td>

<td align=left class=little>&nbsp;<textarea rows=5 name=question onBlur="unclear('question')" maxlength=500 cols=<? print (isGecko())?"26":"29"; ?> <? if (!isNS()) print "class=boxG"; ?>></textarea>&nbsp;<img src=images/dot.gif name=question width=7 height=10></td>

</tr>

<tr>

<td colspan=2 bgcolor=F3F3F3><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td colspan=2 bgcolor=D0D0D0><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td align=center colspan=2 class=text height=27><input type=submit value="Send Enquiry" name=post class=box></td>

</tr>

<tr>

<td colspan=2 class=little align=left><br><img src="images/lock.gif" align=left> All the information that you input here is securely stored and will not be abused with in any way. </td>

</tr>



</table>

</form>

<script language="JavaScript">

<!--

f = document.contact;

	dot = new Image();

	dot.src = "images/dot.gif";

function postIt()

	{

		m = /^[a-z0-9]([a-z0-9_\-\.]*)@([a-z0-9_\-\.]*)(\.[a-z]{2,3}(\.[a-z]{2}){0,2})$/i;

		check = new Image();

		check.src = "images/clickArrow.gif";

		if (f.author.value.length < 2)

			{

				alert("Enquiry Error!\n\nYou forgot to enter your name in the supplied box. Please, go back and do so now.");

				document.author.src = check.src;

				f.author.focus();

				return false;

			}

		else if (m.test(f.email.value) == false)

			{

				alert("Enquiry Error!\n\nThe email you entered is invalid. Please, go back and enter a valid one.");

				document.email.src = check.src;

				f.email.focus();

				return false;

			}

		else if (f.subject.value.length < 5)

			{

				alert("Enquiry Error!\n\nPlease, write a simple sentance describing your question.");

				document.subject.src = check.src;

				f.subject.focus();

				return false;

			}

		else if (f.question.value.length < 10)

			{

				alert("Enquiry Error!\n\You forgot the most important thing - the question. Please, enter it now.");

				document.question.src = check.src;

				f.question.focus();

				return false;

			}

		else 

			{

				f.post.disabled = true;

				window.status="Sending Enquiry...Please wait!";

				return true;

			}

	}

function unclear(which)

	{

		eval("document."+which+".src = dot.src");

	}

//-->

</script>



</p>

<?

	}

else

	{

?>

<div align=center><img src="images/logos/contact.jpg" width="221" height="45" border=0 alt="Contact Us"></div>

<br>

<div align=center class=head style=color:green>Enquiry Posted Successfully!</div>

<br>

<p align=justify style=width:520px;padding:10px>

&nbsp;&nbsp; Your question was successfully submitted to our team. We will review it at the soonest posibility and will answer it.

<br><br>

</p>



<?	

	}

endPage();

page_close();

exit;

?>