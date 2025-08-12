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

page_open(array("sess" => "User_Session"));

initPage();

if (empty($id))

	{

		$dc->query("SELECT * FROM faq_questions ORDER BY id DESC;");

?>

<div align=center class=head>Welcome to FAQ Administrator!</div>

<p align=justify style=width:520px;padding:10px>

&nbsp;&nbsp; Welcome to the FAQ Administrator. Dear Administrator, this is the place to answer questions posted on the site's Help section. All the questions answered or now are available here so you could edit your answers at any time.

</p>

<?

		for ($i=0;$i<$dc->num_rows();$i++)

			{

				$dc->next_record();

				$EditAnswer = ($dc->get("answered") == "Y")?"<a href=faq_admin.php?id=".$dc->get("id").">Edit</a>  [<font color=gray>Posted on ".strftime("%m/%d/%Y at %H:%M",$dc->get("posted"))."</font>]":"<a href=faq_admin.php?id=".$dc->get("id").">Answer</a> [<font color=gray>Posted on ".strftime("%m/%d/%Y at %H:%M",$dc->get("posted"))."</font>]";

				print "<img src=images/dot.gif width=40 height=9><img src=images/point.gif width=9 height=9> <a href=faq_view.php?id=".$dc->get("id").">".$dc->get("question")."</a> <font class=little>($EditAnswer)</font><br>";

			}	

	}

else

	{

		if ($done == "yes")

			{

				$dc->query("UPDATE faq_questions SET answer='$answer', answered='Y' WHERE id='$id';");

				$note = "<div align=center class=head style=color:green>".(($type == "answer")?"Question answered successfully!":"Answer edited successfully!")."</div>";

				if ($set == "yes")

					{

						$headers   = "From: ".$_Config["masterRef"]." <".$_Config["masterEmail"].">\n";

						$headers  .= "X-Sender: <".$_Config["masterEmail"].">\n"; 

						$headers  .= "X-Mailer: PHP\n"; // mailer

						$headers  .= "Return-Path: <".$_Config["masterEmail"].">\n";  // Return path for errors

						$headers  .= "Content-Type: text/html; charset=iso-8859-1\n";



						@mail($email, $_Config["masterRef"]." FAQ : You question was answered", 

						"Question Answered!", $headers);



					}

			}

		$dc->query("SELECT faq_questions.*, faq_categories.* FROM faq_questions, faq_categories WHERE faq_questions.id='$id' AND faq_categories.id=faq_questions.category;");

		$dc->next_record();

?>

<div align=center class=head>FAQ Administrator</div>

<p align=justify style=width:520px;padding:10px>

&nbsp;&nbsp; Here is the place to answer and edit your answers. The question you selected is extracted below. View it and answer it in the form below after which just click on send.

</p>

<?

	print $note;

?>

<form method=POST action=faq_admin.php name=postForm>

<table border=0 width=495 cellspacing=3 cellpadding=0 align=center>

<tr>

<td colspan=3 bgcolor=D0D0D0><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td width=5% bgColor=F9F9F9 align=center><img src=images/down.gif></td>

<td width=90% class=text bgColor=F9F9F9 align=center><b><? $dc->write("question"); ?></b> <font class=little>(By <? $dc->write("author"); ?>)</font></td>

<td width=5% bgColor=F9F9F9 align=center><img src=images/down.gif></td>

</tr>

<tr>

<td colspan=3 bgcolor=D0D0D0><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td width=5% bgColor=F9F9F9></td>

<td width=90% class=text bgColor=FAFAFA align=left>&nbsp;&nbsp;<b>Category: </b> <a href="faq_view.php?category=<?$dc->write("id");?>&cat_name=<?=urlencode($dc->get("name"))?>"><? $dc->write("name");?></a></font></td>

<td width=5% bgColor=F9F9F9></td>

<tr>

<td colspan=3 bgcolor=F3F3F3><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td width=5% bgColor=F9F9F9></td>

<td width=90% class=text bgColor=FAFAFA align=left>&nbsp;&nbsp;<b>Posted: </b><? print strftime("%m/%d/%Y at %H:%M",$dc->get("posted"));?></td>

<td width=5% bgColor=F9F9F9></td>

<tr>

<td colspan=3 bgcolor=F3F3F3><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td width=5% bgColor=F9F9F9></td>

<td width=90% class=text bgColor=FAFAFA align=left>&nbsp;&nbsp;<b>Author: </b><?$dc->write("author");?></td>

<td width=5% bgColor=F9F9F9></td>

<tr>

<td colspan=3 bgcolor=F3F3F3><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td width=5% bgColor=F9F9F9></td>

<td width=90% class=text bgColor=FAFAFA align=left>&nbsp;&nbsp;<b>Email: </b><a href=mailto:<?$dc->write("email");?>><?$dc->write("email");?></a></td>

<td width=5% bgColor=F9F9F9></td>

<tr>

<td colspan=3 bgcolor=F3F3F3><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td width=5% bgColor=F9F9F9></td>

<td width=90% class=text bgColor=FAFAFA align=left>&nbsp;&nbsp;<b>Type: </b><?=($dc->get("for_view") == "Y")?"Public <font class=little color=gray>(All visitors of the site can see it)</font>":"Private <font class=little color=gray>(The answer is only for the one who posted the question)</font>";?></a></td>

<td width=5% bgColor=F9F9F9></td>

<tr>

<td colspan=3 bgcolor=F3F3F3><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td width=5% bgColor=F9F9F9></td>

<td width=90% class=text bgColor=FAFAFA align=left>&nbsp;&nbsp;<b>Notification: </b><?=($dc->get("notify") == "Y")?"<font color=green>Enabled</font> <font class=little color=gray>(The person will receive an email)</font>":"<font color=red>Diabled</font> <font class=little color=gray>(No email with the answer will be sent)</font>";?></a></td>

<td width=5% bgColor=F9F9F9></td>

<tr>

<td colspan=3 bgcolor=F3F3F3><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td colspan=3 bgcolor=D0D0D0><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td width=5% bgColor=F9F9F9></td>

<td width=90% class=text bgColor=white align=center><p align=justify>&nbsp;&nbsp;<b style=color:878787>Question: </b><? print nl2br($dc->get("body")); ?></p></td>

<td width=5% bgColor=F9F9F9></td>

</tr>

<tr>

<td colspan=3 bgcolor=F3F3F3><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td colspan=3 bgcolor=D0D0D0><img src=images/dot.gif width=1 height=1></td>

</tr>

<?

$extract = ($dc->get("answer") == "")?"Answer should be written here.":$dc->get("answer");

$action  = ($dc->get("answer") == "")?"onFocus=\"this.value=''\"":"";

?>

<tr>

<td width=5% bgColor=F9F9F9><img src=images/dot.gif width=1 height=1></td>

<td width=90% class=text bgColor=white align=center><p align=justify>&nbsp;&nbsp;<textarea rows=5 <?=$action?> name=answer maxlength=500 cols=<? print (isGecko())?"36":"39"; ?> <? if (!isNS()) print "class=boxG"; ?>><?=$extract?></textarea></p>

</td>

<td width=5% bgColor=F9F9F9><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td colspan=3 bgcolor=F3F3F3><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td colspan=3 bgcolor=D0D0D0><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td align=center colspan=3 class=text height=27><input type=submit value="Edit/Answer Question" name=post class=box></td>

</tr>

</table>

<br>

<div align=center class=text><a href=faq_admin.php>FAQ Administrator Home</a></div>

<br>

<input type=hidden name=id value="<?=$id?>">

<input type=hidden name=done value="yes">

<input type=hidden name=email value="<?=$dc->get("email")?>">

<input type=hidden name=set value="<?=($dc->get("notify") == "Y")?"yes":"no";?>">

<input type=hidden name=type value="<?=($dc->get("answered") == "Y")?"edit":"answer";?>">

</form>

<?	

	}

endPage();

?>

