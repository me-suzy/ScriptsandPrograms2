<?

include("prepend.php3");

page_open(array("sess" => "User_Session"));

initPage();

?>

<div align=center class=head>Welcome to Help Pages!

<br>

<p align=justify class=text style=width:500px>

&nbsp;&nbsp;Welcome to our online help section. If you experience problems while using <?=$_Config["masterRef"]?> this is the place to turn to for an answer. Our team will do its best to answer all your questions in time and provide you with the best support should you come up with problems with our services.

<br><br>&nbsp;&nbsp;Before you turn to us for help please look at our <a href=faq_view.php>Frequently Asked Questions</a> section when we have posted the answers to some very common issues and questions already asked by other users of the site. If you still could not find a clue to your problem feel free to contact us using the Contact Us link from below.

<br><br>

<div align=center class=text><a href=faq_view.php>FAQ</a> <font color=gray>|</font> <a href=contactUs.php>Contact Us</a></div>

</p>

<?

endPage();

page_close();

exit;

?>