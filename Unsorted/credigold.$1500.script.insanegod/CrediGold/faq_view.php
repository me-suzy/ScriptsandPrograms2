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



if (empty($id) && empty($category))

	{

		$dc->query("SELECT * FROM faq_categories;");

?>

<div align=center><img src=images/logos/views.jpg width="221" height="45" border=0 alt="View Questions">

<p align=justify style=width:500px;padding:10px>

&nbsp;&nbsp; Welcome to our FAQ pages. Here you will have the ultimate ability to ask us questions on site issues you do not understand. We will answer the questions and they will appear here in the site. Before you post us a question please check if it has not been asked here before.

<br><br>&nbsp;&nbsp; All questions are devided by categories.

<br><br>&nbsp;&nbsp; <b>Categories:</b><br><br>

<?

for ($i=0;$i<$dc->num_rows();$i++)

	{

		$dc->next_record();

		$cat = $dc->get("id");

		$rc->query("SELECT * FROM faq_questions WHERE category='$cat' AND for_view='Y';");

		$rate = ($rc->num_rows() == 0)?"":"<font class=little> (".$rc->num_rows().")</font>";

		print "<img src=images/dot.gif width=40 height=9><img src=images/point.gif width=9 height=9> <a href=faq_view.php?category=".$dc->get("id")."&cat_name=".urlencode($dc->get("name")).">".$dc->get("name")."$rate</a><br>";

	}		

?>

<br>&nbsp;&nbsp; You question does not fit any of our categories??? <a href=contactUs.php>Contact us</a> and we will create a new one...

</p>

<?

	}

else if (isset($category))

	{

		$dc->query("SELECT * FROM faq_questions WHERE category='$category' AND for_view='Y';");

		if ($dc->num_rows() == 0)

			{

				$rc->query("SELECT * FROM faq_categories WHERE id='$category';");

				$rc->next_record();

				if ($rc->num_rows() == 0)

					{			

?>

<div align=center><img src=images/logos/category.jpg width="204" height="49" border=0 alt="View Category"></div>

<br>

<div align=center class=head style=color:red>No Such Category!</div>

<br>

<div align=center>

<p align=justify style=width:500px;padding:10px>

&nbsp;&nbsp; The category that you selected does not exist on our site. Please, contact us if you have posted a question in this category. Follow the links below to continue your visit in our site.

<br><br>

<div align=center><a href=faq_view.php>View Questions</a><font color=DDDDDD> | </font><a href=index.php><?=$_Config["masterRef"]?> Home</a><font color=DDDDDD> | </font><a href=help.php>Help Pages</a><font color=DDDDDD> | </font><a href=javascript:history.back(-1)>Go Back</a></div>

</p>

<?	

					}

				else

					{

?>

<div align=center><img src=images/logos/category.jpg width="204" height="49" border=0 alt="View Category"></div>

<br>

<div align=center class=head style=color:darkred>No Questions In This Category!</div>

<br>

<div align=center>

<p align=justify style=width:500px;padding:10px>

&nbsp;&nbsp; The category you just accessed has no questions in it. If you have posted a question in it, a possible reason that it is not availble here is that you are disabled it for public view or we have not answered it yet. Follow the links below to continue your visit in our site.

<br><br>

<div align=center><a href=faq_post.php?cat_point=<?=$category?>>Post First Question Here</a><font color=DDDDDD> | </font><a href=index.php><?=$_Config["masterRef"]?> Home</a><font color=DDDDDD> | </font><a href=help.php>Help Pages</a><font color=DDDDDD> | </font><a href=javascript:history.back(-1)>Go Back</a></div>

</p>

<?					

					}

			}

		else

			{

?>

<div align=center><img src=images/logos/category.jpg width="204" height="49" border=0 alt="View Category"></div>

<div align=center>

<p align=justify style=width:500px;padding:10px>

&nbsp;&nbsp; Welcome to Category <b><?=$cat_name?></b>. Below are all questions posted in the category that we have answered and are availble for your referrence and help.

<br><br>

<?

for ($i=0;$i<$dc->num_rows();$i++)

	{

		$dc->next_record();

		print "<img src=images/dot.gif width=40 height=9><img src=images/point.gif width=9 height=9> <a href=faq_view.php?id=".$dc->get("id").">".$dc->get("question")." <font class=little>(By ".$dc->get("author").")</font></a><br>";

	}	

?>

<div align=center><a href=faq_post.php?cat_point=<?=$category?>>Post Question Here</a><font color=DDDDDD> | </font><a href=faq_view.php>View Questions</a><font color=DDDDDD> | </font><a href=help.php>Help Pages</a><font color=DDDDDD> | </font><a href=contactUs.php>Contact Us</a></div>

</p>

<?		

			}

	}

else

	{

		$dc->query("SELECT faq_questions.*, faq_categories.* FROM faq_questions, faq_categories WHERE faq_questions.id='$id' AND faq_categories.id=faq_questions.category;");

		$dc->next_record();

		if ($dc->get("for_view") == "N")

			{

?>

<div align=center><img src=images/logos/view.jpg width="221" height="45" border=0 alt="View Question"></div>

<br>

<div align=center class=head style=color:red>Access Denied!</div>

<br>

<div align=center>

<p align=justify style=width:500px;padding:10px>

&nbsp;&nbsp; The author of this question has disabled the public view of the question. Please, go back to where you came from and select another question.

<br><br>

<div align=center><a href=index.php>c<?=$_Config["masterRef"]?> Home</a><font color=DDDDDD> | </font><a href=help.php>Help Pages</a><font color=DDDDDD> | </font><a href=javascript:history.back(-1)>Go Back</a></div>

</p>

<?	

			}

		else if ($dc->get("answered") == "N")

			{

?>

<div align=center><img src=images/logos/view.jpg width="221" height="45" border=0 alt="View Question"></div>

<br>

<div align=center class=head style=color:darkred>Question Not Answered Yet!</div>

<br>

<div align=center>

<p align=justify style=width:500px;padding:10px>

&nbsp;&nbsp; The question you selected is not answered by us yet. Please check regularly this URL to see if the status of the question has changed. Now you could go back from where you came or follow one of the other links.

<br><br>

<div align=center><a href=index.php><?=$_Config["masterRef"]?> Home</a><font color=DDDDDD> | </font><a href=help.php>Help Pages</a><font color=DDDDDD> | </font><a href=javascript:history.back(-1)>Go Back</a></div>

</p>

<?	

			}

		else

			{

?>

<div align=center><img src=images/logos/view.jpg width="221" height="45" border=0 alt="View Question">

<p align=justify style=width:500px>

&nbsp;&nbsp; Below is the question and the answer to it.

</p>

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

<tr>

<td width=5% bgColor=F9F9F9><img src=images/dot.gif width=1 height=1></td>

<td width=90% class=text bgColor=white align=center><p align=justify>&nbsp;&nbsp;<b style=color:darkorange>Answer: </b><? print nl2br($dc->get("answer")); ?></p>

</td>

<td width=5% bgColor=F9F9F9><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td colspan=3 bgcolor=F3F3F3><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td colspan=3 bgcolor=D0D0D0><img src=images/dot.gif width=1 height=1></td>

</tr>

</table>

<br>

<div align=center><a href=faq_post.php>Post New Question</a><font color=DDDDDD> | </font><a href=faq_view.php>View Questions</a><font color=DDDDDD> | </font><a href=help.php>Help Pages</a><font color=DDDDDD> | </font><a href=contactUs.php>Contact Us</a></div>

<?		

			}

	}

endPage();

?>

