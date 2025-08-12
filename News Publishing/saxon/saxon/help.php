<?php
/************************************************************************/
// SAXON: Simple Accessible XHTML Online News system
// Module: help.php
// Version 4.6
// Developed by Black Widow
// Copyright (c) 2004 by Black Widow
// Support: www.forum.quirm.net
// Commercial Site: www.blackwidows.co.uk
/************************************************************************/
// stop errors on multiple session_start()
if(session_id() == ""){
  session_start();
}
header("Cache-control: private"); // IE 6 Fix.
include("functions.php");
include("header.php");
Authenticate();
include 'config.php';
include("menu.php");
?>
<h2>User Help</h2>
<dl>
<dt>I can't get this to work properly!</dt>
<dd>Check the settings and paths in your config.php file. 99% of all problems are due to incorrect configurations.</dd>

<dt>How do I add a news item?</dt>
<dd>When you enter SAXON's Admin section, you are immediately presented with a form to allow you to enter a new 
item. This option can be accessed, from any other page within SAXON, using the <em>Add</em> option on the top 
menu bar.</dd>

<dt>How do I edit an existing news item?</dt>
<dd><ol>
<li>Select <em>Edit / Delete</em> from the top menu bar.</li>
<li>Locate the item you want to edit in the list of currently published news.</li>
<li>Select the <em>Edit</em> button immediately below your news item.</li>
</ol>
You can now edit any of the information with the exception of the 'Posted' date which 
remains unalterable.</dd>

<dt>But I want to change the 'Posted' date on an article!</dt>
<dd>Use the <em>Edit</em> option and select a new date.</dd>

<dt>Where does the 'Posting' date come from? I didn't enter it.</dt>
<dd>If you do not select a specific date for a given article, SAXON timestamps the article with the current date. 
It obtains this date directly from your server.</dd>

<dt>How do I delete a news item?</dt>
<dd><ol>
<li>Select <em>Delete</em> from the top menu bar.</li>
<li>Locate the item you want to edit in the list of currently published news.</li>
<li>Select <em>Yes</em> to remove the article or <em>No</em> if you've changed your mind.</li>
</ol>
</dd>

<dt>I've accidentally deleted an news item. Can it be retrieved?</dt>
<dd>No. SAXON does not keep copies of articles that it has been instructed to remove. You will have to re-submit 
that particular piece of news.</dd>

<dt>Can I post-date an article?</dt>
<dd>Yes. Simply select the date you wish an article to appear when you add it to the system. If 
you do not select a specific date, SAXON will assume that you wish to publish the article immediately. Articles 
with their date set to some time in the future will not be published until that date.</dd>

<dt>Can I preview how the News will look on my web site?</dt>
<dd>Use the <em>View News Page</em> option on the main menu at the top of the page to 
jump straight to your current news page on your site.</dd>

<dt>How do I display my news on a web page?</dt>
<dd>Place this bit of code on the appropriate web page:<br />
<code>&lt;?php include "relative_path_to/saxon_directory/news.php";?&gt;</code><br />
Ensure that this page ends with the extension '.php'.</dd>

<dt>I am only displaying the latest x items on my news page. How do I display the full news archive?</dt>
<dd>Place this bit of code on the appropriate web page:<br />
<code>&lt;?php include "relative_path_to/saxon_directory/archive-display.php";?&gt;</code><br />
Ensure that this page ends with the extension '.php'.</dd>

<dt>How do I display news posted by a particular user on a web page?</dt>
<dd>Place this bit of code on the appropriate web page:<br />
<code>&lt;?php $user="user_name"; include "relative_path_to/saxon_directory/news.php";?&gt;</code><br />
where user_name = one of your SAXON users. Please note that user_name is case-sensitive. You need to 
enter it <strong>exactly</strong> as it  appears in your SAXON users table or on the List All Users option 
of the Admin section. If you try to reference a non existent user, you will simply end up with a page 
displaying the message "<em>No news to display</em>". Ensure that this page ends with the extension '.php'.</dd>

<dt>Why does my news not show up on my web page even though I added the correct piece of code?</dt>
<dd>Your web page has to be in <abbr title="PHP Hypertext Preprocessor">PHP</abbr> format. You can 
just change the file type to .php and it should work.</dd>

<dt>How can I display my news differently?</dt>
<dd>Create a new template file in the template folder. To find out how, look at the example templates provided. 
Templates creation requires a basic knowledge of <abbr title="HyperText Markup Language">HTML</abbr>.</dd>

<dt>What's this 'Accessible' about?</dt>
<dd>SAXON uses the highest levels of web accessibility standards to try and ensure that the system can be 
used as easily as possible by everyone - irrespective of any disabilities that they might have or what technology 
they may use. SAXON's news output is also created to comply with the same standards of web accessibility wherever 
possible. For more information on web accessibility issues, please see the 
<a href="http://www.gawds.org/" title="GAWDS web site">Guild of Accessible Web Designers</a> or 
<a href="http://www.blackwidows.co.uk/resources/access/">Black Widow's Web Accessibility section</a>.</dd>

<dt>What's XHTML?</dd>
<dd>eXtensible HyperText Markup Language. XHTML is used to create web pages.</dd>

<dt>Who developed SAXON?</dt>
<dd><a href="http://www.blackwidows.co.uk/>"Black Widow Web Design</a></dd>

<dt>What is the official SAXON website?</dt>
<dd><a href="http://www.quirm.net/">www.quirm.net</a></dd>

<dt>Where can I get support?</dt>
<dd><a href="http://www.quirm.net">www.quirm.net</a></dd>

<dt>Where can I get the latest version of SAXON?</dt>
<dd><a href="http://www.quirm.net/">www.quirm.net</a></dd>

</dl>
<?php
include("footer.php");
?>