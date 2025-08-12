<?

/*----------------[      Instant Access Code Generator (GD/PHP)      ]---------------*/

/*                                                                                   */

/*   This PHP4 script program is written by Infinity Interactive. It could not be,   */

/*  copied, modified and/or reproduced in any form let it be private or public       */

/*  without the appropriate permission of its authors.                               */

/*                                                                                   */

/*  Date    : 05/08/2002                                                             */

/*  Version : 1.0                                                                    */

/*  Authors : Svetlin Staev (svetlin@developer.bg), Kiril Angov (kirokomara@yahoo.com) */

/*                                                                                   */

/*              Copyright(c)2002 Infinity Interactive. All rights reserved.          */

/*-----------------------------------------------------------------------------------*/

include("prepend.php3");



// Question Post - Security Redirection

if (isset($question) && isset($author))

	{

		$getDate    = time();

		$publicView = ($public)?"Y":"N";

		$notify     = ($notify)?"Y":"N";

		$dc->query("INSERT INTO faq_questions SET id='', category='$category', question='$subject', body='$question', author='$author', email='$email', posted='$getDate', for_view='$publicView', notify='$notify';");

		Header("Location: faq_post.php?cmd=thanks");

	}

// End of Question Post



initPage();

if (empty($cmd))

	{

?>

<div align=center><img src=images/logos/post.jpg width="249" height="42" border=0 alt="Post A Question">

<form method=POST action=faq_post.php name=postForm onSubmit="return postIt();">

<p align=justify style=width:500px;padding:10px>

&nbsp;&nbsp;By using the form below you will be able to post a question in your site. We will try our best to answer as soon as possible. If you check the option "Notify me when question is answered" you will receive an email when we answer you question.

</p>

<table border=0 width=430 cellspacing=3 cellpadding=0 align=center>

<tr>

<td colspan=2 bgcolor=D0D0D0><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td align=center class=text colspan=2 bgColor=F9F9F9><b>Question Form</td>

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

<td>&nbsp;</td>

<td align=left class=little><input type=checkbox name=notify value=Y>&nbsp;Notify me when question is answered</td>

</tr>

<tr>

<td>&nbsp;</td>

<td align=left class=little><input type=checkbox name=public value=true>&nbsp;Allow others to view your question</td>

</tr>

<tr>

<td colspan=2 bgcolor=F3F3F3><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td align=right class=little>Category:&nbsp;</td>

<td align=left class=little>&nbsp;<select name=category class=box>

<option value="1">Select a category for your question</option>

<?

$dc->query("SELECT * FROM faq_categories;");

for ($i=0;$i<$dc->num_rows();$i++)

	{

		$dc->next_record();

		print "<option value=\"".$dc->get("id")."\" ".((((int)$cat_point-1) == $i)?"selected":"").">".$dc->get("name")."</option>\n";

	}

?>

</select>

</td>

</tr>

<tr>

<td colspan=2 bgcolor=D0D0D0><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td align=center colspan=2 class=text height=27><input type=submit value="Post New Question" name=post class=box></td>

</tr>

</table>

</form>

<script language="JavaScript">

<!--

f = document.postForm;

	dot = new Image();

	dot.src = "images/dot.gif";

function postIt()

	{

		m = /^[a-z0-9]([a-z0-9_\-\.]*)@([a-z0-9_\-\.]*)(\.[a-z]{2,3}(\.[a-z]{2}){0,2})$/i;

		check = new Image();

		check.src = "images/clickArrow.gif";

		if (f.author.value.length < 2)

			{

				alert("Post Question Error!\n\nYou forgot to enter your name in the supplied box. Please, go back and do so now.");

				document.author.src = check.src;

				f.author.focus();

				return false;

			}

		else if (m.test(f.email.value) == false)

			{

				alert("Post Question Error!\n\nThe email you entered is invalid. Please, go back and enter a valid one.");

				document.email.src = check.src;

				f.email.focus();

				return false;

			}

		else if (f.subject.value.length < 5)

			{

				alert("Post Question Error!\n\nPlease, write a simple sentance describing your question.");

				document.subject.src = check.src;

				f.subject.focus();

				return false;

			}

		else if (f.question.value.length < 10)

			{

				alert("Post Question Error!\n\You forgot the most important thing - the question. Please, enter it now.");

				document.question.src = check.src;

				f.question.focus();

				return false;

			}

		else 

			{

				f.post.disabled = true;

				window.status="Posting Question...Please wait!";

				return true;

			}

	}

function unclear(which)

	{

		eval("document."+which+".src = dot.src");

	}

//-->

</script>

<?

	}

else

	{

?>

<div align=center><img src=images/logos/post.jpg width="249" height="42" border=0 alt="Post A Question"></div>

<br>

<div align=center class=head style=color:green>Question Posted Successfully!</div>

<br>

<div align=center>

<p align=justify style=width:500px;padding:10px>

&nbsp;&nbsp; You question was just posted in our system. If you have checked the email notification option, do check regularly your email for an answer by us. We will review your enquiry in the next 1-2 business days.

<br><br>&nbsp;&nbsp; Now you could follow the link below to the home page of <?=$_Config["masterRef"]?> or the other one which leads to the Help Pages where you could review questions posted by others.

<br><br>

<div align=center><a href=index.php><?=$_Config["masterRef"]?> Home</a><font color=DDDDDD> | </font><a href=help.php>Help Pages</a></div>

</p>



<?	

	}

endPage();

?>

