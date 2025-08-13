<?php

//-------------------------------------
//	Stylesheet template
//-------------------------------------

function deft_stylesheet()
{
ob_start();

?>
body {
margin: 0;
padding: 0;
background: #900;
color: #000;
width: 100%;
}

h1, h2, h3 {
font-family: lucida grande, verdana, arial, helvetica, sans-serif;}

h4 {
font-family: lucida grande, verdana, arial, helvetica, sans-serif;
margin-bottom: 4px;
}

p {
font-family: lucida grande, verdana, arial, helvetica, sans-serif;
}

blockquote {
font-family: georgia, times new roman, times, serif;
font-size: 12px;
}

.center {
text-align: center;
}


ul {
list-style: square;
margin-top: 3px;
margin-bottom: 3px;
margin-left: 1em;
padding-left: 1em;
}

li {
background: transparent;
font-family: lucida grande, verdana, arial, sans-serif;
color: #333;
}

img {
margin: 0;
padding: 0;
border: 0;
}

a:link { color: #f60; border-bottom: 1px dashed #ccc; background-color: transparent; text-decoration: none; }
a:hover { color: #ccc; border: 0; background-color: #f60; text-decoration: none; }
a:visited { color: #f60; border: 0; background-color: transparent; text-decoration: none; }

#leftbar {
position: absolute;
top: 0;
left: 0px;
border-top: 5px solid #fc0;
color: #666;
margin: 0;
padding: 0;
width: 210px;
background: transparent;
}

#leftbar p {
font-size: 11px;
margin: 10px;
background: transparent;
color: #ffc;
}

#blogtitle {
margin: 0;
padding: 10px;
text-align: center;
background: #900;
color: #eef;
border: 4px solid #fc0;
}

#blogtitle h1 {
font: bold 40px tahoma, georgia, times new roman, times, serif;
margin: 0;
padding: 0;
}

#content {
position: relative;
margin: 0 210px 0 210px;
padding: 10px;
border-right: 5px solid #fc0;
border-left: 5px solid #fc0;
background: #FFF;
color: #000;
}

#content p, ul, li {
font-size: 12px;
background: transparent;
color: #000;
text-align: left;
}


#stats {
position: relative;
margin: 15px 210px 0 210px;
padding: 10px;
border-right: 5px solid #fc0;
border-left: 5px solid #fc0;
background: #FFF;
color: #000;
}

#stats p {
font-size: 11px;
background: transparent;
color: #000;
text-align: left;
}


.date {
font-size: 13px;
background: transparent;
color: #f90;
text-align: left;
margin-top: 20px;
margin-bottom: 0;
padding: 0;
}

.title {
margin: 0;
padding: 0;
font-size: 17px;
background: transparent;
color: #000;
text-align: left;
}

.posted {
font: 10px lucida grande, verdana, arial, helvetica, sans-serif;
background: transparent;
color: #666;
text-align: right;
margin: 0;
padding-top: 0;
padding-bottom: 20px;
} 

#rightbar {
position: absolute;
top: 0;
right: 0;
border-top: 5px solid #fc0;
color: #666;
margin: 0;
padding: 0;
width: 210px;
background: transparent;
}

#rightbar p {
font-size: 11px;
margin: 10px;
background: transparent;
color: #ffc;
}

#leftbar li, #rightbar li {
font-size: 11px;
background: transparent;
color: #ffc;
}

#leftbar ul, #leftbar ul {
margin-left:  4px;
margin-right: 6px;
}

.sidetitle {
margin: 15px 10px 10px 10px;
font-size: 12px;
background: transparent;
color: #fff;
} 

.spacer {
margin: 0;
padding: 0;
clear: both;
}

.paginate {
 font-family:		Verdana, Geneva, Tahoma, Trebuchet MS, Arial, Sans-serif;
 font-size:			12px;
 font-weight: 		normal;
 letter-spacing:	.1em;
 padding:			10px 6px 10px 4px;
 margin:			0;
 background-color:	transparent;  
}

.pagecount {
 font-family:		Verdana, Geneva, Tahoma, Trebuchet MS, Arial, Sans-serif;
 font-size:			10px;
 color:				#666;
 font-weight:		normal;
 background-color: transparent;  
}

.input {
border-top:        1px solid #999999;
border-left:       1px solid #999999;
background-color:  #fff;
color:             #000;
font-family:       Verdana, Geneva, Tahoma, Trebuchet MS, Arial, Sans-serif;
font-size:         11px;
height:            1.6em;
padding:           .3em 0 0 2px;
margin-top:        6px;
margin-bottom:     3px;
} 

.textarea {
border-top:        1px solid #999999;
border-left:       1px solid #999999;
background-color:  #fff;
color:             #000;
font-family:       Verdana, Geneva, Tahoma, Trebuchet MS, Arial, Sans-serif;
font-size:         11px;
margin-top:        3px;
margin-bottom:     3px;
}

.checkbox {
background-color:  transparent;
margin:            3px;
padding:           0;
border:            0;
}

.submit {
background-color:  #fff;
font-family:       Arial, Verdana, Sans-serif;
font-size:         10px;
font-weight:       normal;
letter-spacing:    .1em;
padding:           1px 3px 1px 3px;
margin-top:        6px;
margin-bottom:     4px;
text-transform:    uppercase;
color:             #000;
}  
<?php

$buffer = ob_get_contents();
ob_end_clean(); 
return $buffer;
}
// END


//-------------------------------------
//	Weblog template
//-------------------------------------

function deft_weblog()
{
ob_start();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{lang}" lang="{lang}"> 

<head>
<meta http-equiv="content-type" content="text/html; charset={charset}" />
<title>{exp:weblog:weblog_name weblog="weblog1"}</title>

<link rel='stylesheet' type='text/css' media='all' href='{stylesheet=weblog/weblog_css}' /> 
<style type='text/css' media='screen'>@import "{stylesheet=weblog/weblog_css}";</style>
</head>

<body>

<div id="leftbar">

<p><b>Members:</b>
<br />

{if logged_in}
 <a href="{path=member/profile}">Your Account</a>  |  <a href="{path=LOGOUT}">Logout</a> 
{/if}

{if logged_out}
 <a href="{path=member/login}">Login</a> | <a href="{path=member/register}">Register</a>
{/if}

 | <a href="{path=member/memberlist}">Member List</a>

</p>

<h2 class="sidetitle">About</h2>
<p>Quote meon an estimate et non interruptus stadium. Sic tempus fugit esperanto hiccup estrogen. Glorious baklava ex librus hup hey ad infinitum. Non sequitur condominium facile et geranium incognito.</p>


{exp:search:simple_form search_in="everywhere"}
<h2 class="sidetitle">Search</h2>
<p>
<input type="text" name="keywords" value="" class="input" size="18" maxlength="100" />
<br />
<a href="{path=search/index}">Advanced Search</a>
</p>

<p><input type="submit" value="submit"  class="submit" /></p>

{/exp:search:simple_form}


<h2 class="sidetitle">Categories</h2>
<p>
{exp:weblog:categories weblog="weblog1" style="nested"}
<a href="{path=weblog/index}">{category_name}</a>
{/exp:weblog:categories}
</p>

<h2 class="sidetitle">Monthly Archives</h2>
<ul>
{exp:weblog:month_links weblog="weblog1"}
<li><a href="{path=weblog/index}">{month} {year}</a></li>
{/exp:weblog:month_links}
<li><a href="{path=weblog/archives}">Complete Archives</a></li>
<li><a href="{path=weblog/categories}">Category Archives</a></li>
</ul>

<h2 class="sidetitle">Most recent entries</h2>
<ul>
{exp:weblog:entries orderby="date" sort="desc" limit="15" weblog="weblog1" dynamic="off"}
<li><a href="{title_permalink=weblog/index}">{title}</a></li>
{/exp:weblog:entries}
</ul>

<h2 class="sidetitle">Syndicate</h2>
<ul>
<li><a href="{path=weblog/rss_1.0}">RSS 1.0</a></li>
<li><a href="{path=weblog/rss_2.0}">RSS 2.0</a></li>
<li><a href="{path=weblog/rss_atom}">Atom</a></li>
</ul>

<h2 class="sidetitle">Join our Mailing List</h2>

{exp:mailinglist:form}

<p><input type="text" name="email" value="" class="input" size="18" /></p>

<p><input type="submit" value="submit"  class="submit" /></p>

{/exp:mailinglist:form}

</div>



<div id="content">

<div id="blogtitle">
<h1>{exp:weblog:weblog_name weblog="weblog1"}</h1>
</div>

<div class="spacer">&nbsp;</div>


{exp:weblog:category_heading}
<h1>{category_name}</h1>
{/exp:weblog:category_heading}


{exp:weblog:entries weblog="weblog1" orderby="date" sort="desc" limit="15"}

{date_heading}
<h3 class="date">{entry_date format=' %l, %F %d, %Y '}</h3>
{/date_heading}

<h2 class="title">{title}</h2>

{summary}

{body}

<div class="posted">Posted by <a href="{profile_path=member/index}">{author}</a> on {entry_date format='%m/%d'} at {entry_date format='%h:%i %A'}
<br />

{categories}
<a href="{path=SITE_INDEX}">{category_name}</a> &#8226; 
{/categories}

{if allow_comments}
({comment_total}) <a href="{comment_path="weblog/comments"}">Comments</a> &#8226;
{/if}

{if allow_trackbacks}
({trackback_total}) <a href="{trackback_path="weblog/trackbacks"}">Trackbacks</a> &#8226; 
{/if}

<a href="{title_permalink=weblog/index}">Permalink</a>
</div>

{paginate}

<div class="paginate">

<span class="pagecount">Page {current_page} of {total_pages} pages</span>  {pagination_links}

</div>

{/paginate}

{/exp:weblog:entries}
</div>



<div id="stats">

<h2>Statistics</h2>
<p>
This page has been viewed {hits} times<br />
Page rendered in {elapsed_time} seconds<br />
{total_queries} querie(s) executed<br />
Debug mode is {debug_mode}<br />

{exp:stats}
Total Entries: {total_entries}<br />
Total Comments: {total_comments}<br />
Total Trackbacks: {total_trackbacks}<br />
Most Recent Entry: {last_entry_date format="%m/%d/%Y %h:%i %a"}<br />
Most Recent Comment on:  {last_comment_date format="%m/%d/%Y %h:%i %a"}<br />
Total Members: {total_members}<br />
Total Logged in members: {total_logged_in}<br />
Total guests: {total_guests}<br />
Total anonymous users: {total_anon}<br />
Most Recent Visitor on:  {last_visitor_date format="%m/%d/%Y %h:%i %a"}<br />
The most visitors ever was {most_visitors} on  {most_visitor_date format="%m/%d/%Y %h:%i %a"}

{if member_names}
<p>Current Logged-in Members:&nbsp;
{member_names}
{name backspace='6'}&nbsp;
{/member_names}
</p>
{/if}
{/exp:stats}

</p>

<p><a href="{path=weblog/referrers}">Referrers</a></p>

<p>Powered by ExpressionEngine, Nullified by GTT</p>

</div>


<div id="rightbar">

<h2 class="sidetitle">Reading</h2>
<p>Quote meon an estimate et non interruptus stadium.</p>
<ul>
<li>Book #1</li>
<li>Book #2</li>
<li>Book #3</li>
</ul>


<h2 class="sidetitle">Listening</h2>
<p>Sic tempus fugit esperanto hiccup estrogen.</p>
<ul>
<li>Song #1</li>
<li>Song #2</li>
<li>Song #3</li>
</ul>


<h2 class="sidetitle">Viewing</h2>
<p>Glorious baklava ex librus hup hey ad infinitum.</p>
<ul>
<li>Movie #1</li>
<li>Movie #2</li>
<li>Movie #3</li>
</ul>
</div>
</body>
</html><?php

$buffer = ob_get_contents();
ob_end_clean(); 
return $buffer;
}
// END


//-------------------------------------
//	Archives template
//-------------------------------------

function deft_archives()
{
ob_start();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{lang}" lang="{lang}"> 

<head>
<meta http-equiv="content-type" content="text/html; charset={charset}" />
<title>{exp:weblog:weblog_name weblog="weblog1"}</title>

<link rel='stylesheet' type='text/css' media='all' href='{stylesheet=weblog/weblog_css}' /> 
<style type='text/css' media='screen'>@import "{stylesheet=weblog/weblog_css}";</style>
</head>

<body>

<div id="leftbar">

<p><b>Members:</b>
<br />

{if logged_in}
 <a href="{path=member/profile}">Your Account</a>  |  <a href="{path=LOGOUT}">Logout</a> 
{/if}

{if logged_out}
 <a href="{path=member/login}">Login</a> | <a href="{path=member/register}">Register</a>
{/if}

 | <a href="{path=member/memberlist}">Member List</a>

</p>


<h2 class="sidetitle">About</h2>
<p>Quote meon an estimate et non interruptus stadium. Sic tempus fugit esperanto hiccup estrogen. Glorious baklava ex librus hup hey ad infinitum. Non sequitur condominium facile et geranium incognito.</p>


<h2 class="sidetitle">Monthly Archives</h2>
<ul>
{exp:weblog:month_links weblog="weblog1"}
<li><a href="{path=weblog/index}">{month} {year}</a></li>
{/exp:weblog:month_links}

<li><a href="{path=weblog/archives}">Complete Archives</a></li>
<li><a href="{path=weblog/categories}">Category Archives</a></li>
</ul>


<h2 class="sidetitle">Most recent entries</h2>
<ul>
{exp:weblog:entries orderby="date" sort="desc" limit="15" weblog="weblog1" dynamic="off"}
<li><a href="{title_permalink=weblog/index}">{title}</a></li>
{/exp:weblog:entries}
</ul>

<h2 class="sidetitle">Syndicate</h2>
<ul>
<li><a href="{path=weblog/rss_1.0}">RSS 1.0</a></li>
<li><a href="{path=weblog/rss_2.0}">RSS 2.0</a></li>
<li><a href="{path=weblog/rss_atom}">Atom</a></li>
</ul>

<h2 class="sidetitle">Statistics</h2>
<ul>
<li>This page has been viewed {hits} times</li>
<li>Page rendered in {elapsed_time} seconds</li> 
<li>{total_queries} querie(s) executed</li>
<li><a href="{path=weblog/referrers}">Referrers</a></li>
</ul>

<p>Powered by ExpressionEngine, Nullified by GTT</p>

</div>


<div id="content">


<div id="blogtitle">


<h1>{exp:weblog:weblog_name weblog="weblog1"}</h1>
</div>


<div class="spacer">&nbsp;</div>

{exp:weblog:entries orderby="date" sort="desc" limit="100"}

{date_heading display="yearly"}
<h2 class="title">{entry_date format="%Y"}</h2>
{/date_heading}

{date_heading display="monthly"}
<h3 class="date">{entry_date format="%F"}</h3>
{/date_heading}

<ul>
<li><a href="{title_permalink="weblog/index"}">{title}</a></li>
</ul>

{/exp:weblog:entries}


<p><a href="{homepage}">&lt;&lt; Back to main</a></p>

</div>
<div id="rightbar">

<h2 class="sidetitle">Reading</h2>
<p>Quote meon an estimate et non interruptus stadium.</p>
<ul>
<li>Book #1</li>
<li>Book #2</li>
<li>Book #3</li>
</ul>

<h2 class="sidetitle">Listening</h2>
<p>Sic tempus fugit esperanto hiccup estrogen.</p>
<ul>
<li>Song #1</li>
<li>Song #2</li>
<li>Song #3</li>
</ul>

<h2 class="sidetitle">Viewing</h2>
<p>Glorious baklava ex librus hup hey ad infinitum.</p>
<ul>
<li>Movie #1</li>
<li>Movie #2</li>
<li>Movie #3</li>
</ul>
</div>
</body>
</html><?php

$buffer = ob_get_contents();
ob_end_clean(); 
return $buffer;
}
// END



//-------------------------------------
//	Category archives template
//-------------------------------------

function deft_cetegory_archives()
{
ob_start();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{lang}" lang="{lang}"> 

<head>
<meta http-equiv="content-type" content="text/html; charset={charset}" />
<title>{exp:weblog:weblog_name weblog="weblog1"}</title>

<link rel='stylesheet' type='text/css' media='all' href='{stylesheet=weblog/weblog_css}' /> 
<style type='text/css' media='screen'>@import "{stylesheet=weblog/weblog_css}";</style>
</head>

<body>

<div id="leftbar">

<p><b>Members:</b>
<br />

{if logged_in}
 <a href="{path=member/profile}">Your Account</a>  |  <a href="{path=LOGOUT}">Logout</a> 
{/if}

{if logged_out}
 <a href="{path=member/login}">Login</a> | <a href="{path=member/register}">Register</a>
{/if}

 | <a href="{path=member/memberlist}">Member List</a>

</p>


<h2 class="sidetitle">About</h2>
<p>Quote meon an estimate et non interruptus stadium. Sic tempus fugit esperanto hiccup estrogen. Glorious baklava ex librus hup hey ad infinitum. Non sequitur condominium facile et geranium incognito.</p>


<h2 class="sidetitle">Monthly Archives</h2>
<ul>
{exp:weblog:month_links weblog="weblog1"}
<li><a href="{path=weblog/index}">{month} {year}</a></li>
{/exp:weblog:month_links}

<li><a href="{path=weblog/archives}">Complete Archives</a></li>
<li><a href="{path=weblog/categories}">Category Archives</a></li>
</ul>


<h2 class="sidetitle">Most recent entries</h2>
<ul>
{exp:weblog:entries orderby="date" sort="desc" limit="15" weblog="weblog1" dynamic="off"}
<li><a href="{title_permalink=weblog/index}">{title}</a></li>
{/exp:weblog:entries}
</ul>

<h2 class="sidetitle">Syndicate</h2>
<ul>
<li><a href="{path=weblog/rss_1.0}">RSS 1.0</a></li>
<li><a href="{path=weblog/rss_2.0}">RSS 2.0</a></li>
<li><a href="{path=weblog/rss_atom}">Atom</a></li>
</ul>

<h2 class="sidetitle">Statistics</h2>
<ul>
<li>This page has been viewed {hits} times</li>
<li>Page rendered in {elapsed_time} seconds</li> 
<li>{total_queries} querie(s) executed</li>
<li><a href="{path=weblog/referrers}">Referrers</a></li>
</ul>

<p>Powered by ExpressionEngine, Nullified by GTT</p>

</div>


<div id="content">


<div id="blogtitle">


<h1>{exp:weblog:weblog_name weblog="weblog1"}</h1>
</div>


<div class="spacer">&nbsp;</div>


<h2 class="sidetitle">Categories</h2>

{exp:weblog:category_archive weblog="weblog1"}

{categories}
<h4>{category_name}</h4>
{/categories}

{entry_titles}
<div><a href="{path=SITE_INDEX}">{title}</a></div>
{/entry_titles}

{/exp:weblog:category_archive}


<p><a href="{homepage}">&lt;&lt; Back to main</a></p>

</div>
<div id="rightbar">

<h2 class="sidetitle">Reading</h2>
<p>Quote meon an estimate et non interruptus stadium.</p>
<ul>
<li>Book #1</li>
<li>Book #2</li>
<li>Book #3</li>
</ul>

<h2 class="sidetitle">Listening</h2>
<p>Sic tempus fugit esperanto hiccup estrogen.</p>
<ul>
<li>Song #1</li>
<li>Song #2</li>
<li>Song #3</li>
</ul>

<h2 class="sidetitle">Viewing</h2>
<p>Glorious baklava ex librus hup hey ad infinitum.</p>
<ul>
<li>Movie #1</li>
<li>Movie #2</li>
<li>Movie #3</li>
</ul>
</div>
</body>
</html><?php

$buffer = ob_get_contents();
ob_end_clean(); 
return $buffer;
}
// END


//-------------------------------------
//	Comments
//-------------------------------------

function deft_comments()
{
ob_start();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{lang}" lang="{lang}"> 

<head>
<meta http-equiv="content-type" content="text/html; charset={charset}" />
<title>{exp:weblog:weblog_name weblog="weblog1"}</title>

<link rel='stylesheet' type='text/css' media='all' href='{stylesheet=weblog/weblog_css}' /> 
<style type='text/css' media='screen'>@import "{stylesheet=weblog/weblog_css}";</style>
</head>

<body>

<div id="leftbar">

<p><b>Members:</b>
<br />

{if logged_in}
 <a href="{path=member/profile}">Your Account</a>  |  <a href="{path=LOGOUT}">Logout</a> 
{/if}

{if logged_out}
 <a href="{path=member/login}">Login</a> | <a href="{path=member/register}">Register</a>
{/if}

 | <a href="{path=member/memberlist}">Member List</a>

</p>


<h2 class="sidetitle">About</h2>
<p>Quote meon an estimate et non interruptus stadium. Sic tempus fugit esperanto hiccup estrogen. Glorious baklava ex librus hup hey ad infinitum. Non sequitur condominium facile et geranium incognito.</p>


<h2 class="sidetitle">Monthly Archives</h2>
<ul>
{exp:weblog:month_links weblog="weblog1"}
<li><a href="{path=weblog/index}">{month} {year}</a></li>
{/exp:weblog:month_links}

<li><a href="{path=weblog/archives}">Complete Archives</a></li>
<li><a href="{path=weblog/categories}">Category Archives</a></li>
</ul>


<h2 class="sidetitle">Most recent entries</h2>
<ul>
{exp:weblog:entries orderby="date" sort="desc" limit="15" weblog="weblog1" dynamic="off"}
<li><a href="{title_permalink=weblog/index}">{title}</a></li>
{/exp:weblog:entries}
</ul>

<h2 class="sidetitle">Syndicate</h2>
<ul>
<li><a href="{path=weblog/rss_1.0}">RSS 1.0</a></li>
<li><a href="{path=weblog/rss_2.0}">RSS 2.0</a></li>
<li><a href="{path=weblog/rss_atom}">Atom</a></li>
</ul>

<h2 class="sidetitle">Statistics</h2>
<ul>
<li>This page has been viewed {hits} times</li>
<li>Page rendered in {elapsed_time} seconds</li> 
<li>{total_queries} querie(s) executed</li>
<li><a href="{path=weblog/referrers}">Referrers</a></li>
</ul>

<p>Powered by ExpressionEngine, Nullified by GTT</p>

</div>

<div id="content">
<div id="blogtitle">
<h1>{exp:weblog:weblog_name weblog="weblog1"}</h1>
</div>

<div class="spacer">&nbsp;</div>


{exp:weblog:entries}

<h2 class="title">{title}</h2>

{summary}

{body}

<div class="posted">Posted by <a href="{profile_path=member/index}">{author}</a> on {entry_date format='%m/%d'} at {entry_date format='%h:%i %A'}

</div>
{/exp:weblog:entries}



<div class="spacer">&nbsp;</div>

<ol>
{exp:comment:entries}

<li>{comment}

<div class="posted">Posted by {url_or_email_as_author}  &nbsp;on&nbsp; {comment_date format='%m/%d'} &nbsp;at&nbsp; {comment_date format='%h:%i %A'}</div></li>

{/exp:comment:entries}
</ol>


{exp:comment:form preview="weblog/preview"}

{if not_logged_in}
<p>
Name:<br />
<input type="text" name="name" value="{name}" size="45" />
</p>
<p>
Email:<br />
<input type="text" name="email" value="{email}" size="45" />
</p>
<p>
Location:<br />
<input type="text" name="location" value="{location}" size="45" />
</p>
<p>
URL:<br />
<input type="text" name="url" value="{url}" size="45" />
</p>

{/if}

<p>
<a href="#" onclick="window.open('{path=weblog/smileys}', '_blank', 'width=400,height=440')">Smileys</a>
</p>

<p>
<textarea name="comment" cols="45" rows="10">{comment}</textarea>
</p>

{if not_logged_in}
<p><input type="checkbox" name="save_info" value="yes" {save_info} /> Remember my personal information</p>
{/if}

<p><input type="checkbox" name="notify_me" value="yes" {notify_me} /> Notify me of follow-up comments?</p>

<input type="submit" name="submit" value="Submit" />
<input type="submit" name="preview" value="Preview" />

{/exp:comment:form}



<div class="center">

{exp:weblog:next_entry weblog="weblog1"}
<p>Next entry: <a href="{path=weblog/comments}">{title}</a></p>
{/exp:weblog:next_entry}

{exp:weblog:prev_entry weblog="weblog1"}
<p>Previous entry: <a href="{path=weblog/comments}">{title}</a></p>
{/exp:weblog:prev_entry}

</div>


<p><a href="{homepage}">&lt;&lt; Back to main</a></p>
</div>


<div id="rightbar">

<h2 class="sidetitle">Reading</h2>
<p>Quote meon an estimate et non interruptus stadium.</p>
<ul>
<li>Book #1</li>
<li>Book #2</li>
<li>Book #3</li>
</ul>

<h2 class="sidetitle">Listening</h2>
<p>Sic tempus fugit esperanto hiccup estrogen.</p>
<ul>
<li>Song #1</li>
<li>Song #2</li>
<li>Song #3</li>
</ul>


<h2 class="sidetitle">Viewing</h2>
<p>Glorious baklava ex librus hup hey ad infinitum.</p>
<ul>
<li>Movie #1</li>
<li>Movie #2</li>
<li>Movie #3</li>
</ul>
</div>
</body>
</html><?php

$buffer = ob_get_contents();
ob_end_clean(); 
return $buffer;
}
// END


//-------------------------------------
//	Comment preview
//-------------------------------------

function deft_comment_preview()
{
ob_start();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{lang}" lang="{lang}"> 

<head>
<meta http-equiv="content-type" content="text/html; charset={charset}" />
<title>{exp:weblog:weblog_name weblog="weblog1"}</title>

<link rel='stylesheet' type='text/css' media='all' href='{stylesheet=weblog/weblog_css}' /> 
<style type='text/css' media='screen'>@import "{stylesheet=weblog/weblog_css}";</style>
</head>

<body>

<div id="leftbar">

<p><b>Members:</b>
<br />

{if logged_in}
 <a href="{path=member/profile}">Your Account</a>  |  <a href="{path=LOGOUT}">Logout</a> 
{/if}

{if logged_out}
 <a href="{path=member/login}">Login</a> | <a href="{path=member/register}">Register</a>
{/if}

 | <a href="{path=member/memberlist}">Member List</a>

</p>

<h2 class="sidetitle">About</h2>
<p>Quote meon an estimate et non interruptus stadium. Sic tempus fugit esperanto hiccup estrogen. Glorious baklava ex librus hup hey ad infinitum. Non sequitur condominium facile et geranium incognito.</p>


<h2 class="sidetitle">Monthly Archives</h2>
<ul>
{exp:weblog:month_links weblog="weblog1"}
<li><a href="{path=weblog/index}">{month} {year}</a></li>
{/exp:weblog:month_links}

<li><a href="{path=weblog/archives}">Complete Archives</a></li>
<li><a href="{path=weblog/categories}">Category Archives</a></li>
</ul>


<h2 class="sidetitle">Most recent entries</h2>
<ul>
{exp:weblog:entries orderby="date" sort="desc" limit="15" weblog="weblog1" dynamic="off"}
<li><a href="{title_permalink=weblog/index}">{title}</a></li>
{/exp:weblog:entries}
</ul>


<h2 class="sidetitle">Syndicate</h2>
<ul>
<li><a href="{path=weblog/rss_1.0}">RSS 1.0</a></li>
<li><a href="{path=weblog/rss_2.0}">RSS 2.0</a></li>
<li><a href="{path=weblog/rss_atom}">Atom</a></li>
</ul>

<h2 class="sidetitle">Statistics</h2>
<ul>
<li>This page has been viewed {hits} times</li>
<li>Page rendered in {elapsed_time} seconds</li> 
<li>{total_queries} querie(s) executed</li>
<li><a href="{path=weblog/referrers}">Referrers</a></li>
</ul>

<p>Powered by ExpressionEngine, Nullified by GTT</p>

</ul>


</div>
<div id="content">
<div id="blogtitle">
<h1>{exp:weblog:weblog_name weblog="weblog1"}</h1>
</div>
<div class="spacer">&nbsp;</div>
{exp:comment:preview}
{comment}
{/exp:comment:preview}

{exp:comment:form}

{if not_logged_in}
<p>
Name:<br />
<input type="text" name="name" value="{name}" size="45" />
</p>
<p>
Email:<br />
<input type="text" name="email" value="{email}" size="45" />
</p>
<p>
Location:<br />
<input type="text" name="location" value="{location}" size="45" />
</p>
<p>
URL:<br />
<input type="text" name="url" value="{url}" size="45" />
</p>

{/if}

<p>
<a href="#" onclick="window.open('{path=weblog/smileys}', '_blank', 'width=400,height=440')">Smileys</a>
</p>

<p>
<textarea name="comment" cols="45" rows="10">{comment}</textarea>
</p>

{if not_logged_in}
<p><input type="checkbox" name="save_info" value="yes" {save_info} /> Remember my personal information</p>
{/if}

<p><input type="checkbox" name="notify_me" value="yes" {notify_me} /> Notify me of follow-up comments?</p>


<input type="submit" name="submit" value="Submit" />
<input type="submit" name="preview" value="Preview" />

{/exp:comment:form}


<p><a href="{homepage}">&lt;&lt; Back to main</a></p>
</div>


<div id="rightbar">
<h2 class="sidetitle">Reading</h2>
<p>Quote meon an estimate et non interruptus stadium.</p>
<ul>
<li>Book #1</li>
<li>Book #2</li>
<li>Book #3</li>
</ul>

<h2 class="sidetitle">Listening</h2>
<p>Sic tempus fugit esperanto hiccup estrogen.</p>
<ul>
<li>Song #1</li>
<li>Song #2</li>
<li>Song #3</li>
</ul>

<h2 class="sidetitle">Viewing</h2>
<p>Glorious baklava ex librus hup hey ad infinitum.</p>
<ul>
<li>Movie #1</li>
<li>Movie #2</li>
<li>Movie #3</li>
</ul>
</div>
</body>
</html><?php

$buffer = ob_get_contents();
ob_end_clean(); 
return $buffer;
}
// END


//-------------------------------------
// Trackbacks
//-------------------------------------

function deft_trackbacks()
{
ob_start();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{lang}" lang="{lang}"> 

<head>
<meta http-equiv="content-type" content="text/html; charset={charset}" />
<title>{exp:weblog:weblog_name weblog="weblog1"}</title>

<link rel='stylesheet' type='text/css' media='all' href='{stylesheet=weblog/weblog_css}' /> 
<style type='text/css' media='screen'>@import "{stylesheet=weblog/weblog_css}";</style>
</head>

<body>

<div id="leftbar">

<p><b>Members:</b>
<br />

{if logged_in}
 <a href="{path=member/profile}">Your Account</a>  |  <a href="{path=LOGOUT}">Logout</a> 
{/if}

{if logged_out}
 <a href="{path=member/login}">Login</a> | <a href="{path=member/register}">Register</a>
{/if}

 | <a href="{path=member/memberlist}">Member List</a>

</p>

<h2 class="sidetitle">About</h2>
<p>Quote meon an estimate et non interruptus stadium. Sic tempus fugit esperanto hiccup estrogen. Glorious baklava ex librus hup hey ad infinitum. Non sequitur condominium facile et geranium incognito.</p>
<h2 class="sidetitle">Monthly Archives</h2>
<ul>

{exp:weblog:month_links weblog="weblog1"}
<li><a href="{path=weblog/index}">{month} {year}</a></li>
{/exp:weblog:month_links}

<li><a href="{path=weblog/archives}">Complete Archives</a></li>
<li><a href="{path=weblog/categories}">Category Archives</a></li>
</ul>

<h2 class="sidetitle">Most recent entries</h2>
<ul>
{exp:weblog:entries orderby="date" sort="desc" limit="15" weblog="weblog1" dynamic="off"}
<li><a href="{title_permalink=weblog/index}">{title}</a></li>
{/exp:weblog:entries}
</ul>


<h2 class="sidetitle">Syndicate</h2>
<ul>
<li><a href="{path=weblog/rss_1.0}">RSS 1.0</a></li>
<li><a href="{path=weblog/rss_2.0}">RSS 2.0</a></li>
<li><a href="{path=weblog/rss_atom}">Atom</a></li>
</ul>

<h2 class="sidetitle">Statistics</h2>
<ul>
<li>This page has been viewed {hits} times</li>
<li>Page rendered in {elapsed_time} seconds</li> 
<li>{total_queries} querie(s) executed</li>
<li><a href="{path=weblog/referrers}">Referrers</a></li>
</ul>

<p>Powered by ExpressionEngine, Nullified by GTT</p>

</div>


<div id="content">

<div id="blogtitle">

<h1>{exp:weblog:weblog_name weblog="weblog1"}</h1>
</div>

<div class="spacer">&nbsp;</div>


<h2 class="title">The trackback URL for this entry is:</h2>
<form>
<input type="text" value="{exp:trackback:url}" size="45" class="input" />
</form>


<h2 class="title">Trackbacks:</h2>

<ol>
{exp:trackback:entries}

<li><strong>{title}</strong><br /><br />

{content}

<div class="posted">Tracked on: <a href="{trackback_url}">{weblog_name}</a> ({trackback_ip}) at {trackback_date format="%Y %m %d %H:%i:%s"}</div></li>

{/exp:trackback:entries}
</ol>


<p><a href="{homepage}">&lt;&lt; Back to main</a></p>
</div>


<div id="rightbar">
<h2 class="sidetitle">Reading</h2>
<p>Quote meon an estimate et non interruptus stadium.</p>
<ul>
<li>Book #1</li>
<li>Book #2</li>
<li>Book #3</li>
</ul>

<h2 class="sidetitle">Listening</h2>
<p>Sic tempus fugit esperanto hiccup estrogen.</p>
<ul>
<li>Song #1</li>
<li>Song #2</li>
<li>Song #3</li>
</ul>

<h2 class="sidetitle">Viewing</h2>
<p>Glorious baklava ex librus hup hey ad infinitum.</p>
<ul>
<li>Movie #1</li>
<li>Movie #2</li>
<li>Movie #3</li>
</ul>
</div>
</body>
</html><?php

$buffer = ob_get_contents();
ob_end_clean(); 
return $buffer;
}
// END



//-------------------------------------
//	Default referrers
//-------------------------------------

function deft_referrers()
{
ob_start();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{lang}" lang="{lang}"> 

<head>
<meta http-equiv="content-type" content="text/html; charset={charset}" />
<title>Referrers</title>

<style type="text/css">

body { 
 background-color: #ffffff; 
 margin-left: 40px; 
 margin-right: 40px; 
 margin-top: 30px; 
 font-size: 11px; 
 font-family: verdana,trebuchet,sans-serif; 
}
h4 { 
 font-size: 14px; 
 font-family: verdana,trebuchet,sans-serif; 
}
a:link { 
 color: #990000; 
 font-size: 11px; 
 font-weight: normal; 
 text-decoration: underline; 
}
a:visited { 
 color: #990000; 
 font-size: 11px; 
 font-weight: normal; 
 text-decoration: underline; 
}
a:active { 
 color: #990000; 
 font-size: 11px; 
 font-weight: normal; 
 text-decoration: underline; 
}
a:hover { 
 color: #990000; 
 font-size: 11px; 
 font-weight: normal; 
 text-decoration: none; 
}

</style>
</head>
<body>

<h4>Referrers</h4>

<table border="0" width="100%" cellpadding="6" cellspacing="1">
<tr>
<td>From</td>
<td>To</td>
</tr>

{exp:referrer limit="50" popup="yes"}
<tr class="row">
<td><div>{ref_from}</div></td>
<td><div>{ref_to}</div></td>
<td><div>{ref_ip}</div></td>
<td><div>{ref_date format="%m/%d/%Y"}</div></td>
</tr>
{/exp:referrer}

</table>

</body>
</html>
<?php

$buffer = ob_get_contents();
ob_end_clean(); 
return $buffer;

}
// END


//-------------------------------------
//	Smileys
//-------------------------------------

function deft_smileys()
{

ob_start();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{lang}" lang="{lang}"> 
<head>
<meta http-equiv="content-type" content="text/html; charset={charset}" />
<title>Smileys</title>

<style type="text/css">

body { 
 background-color: #ffffff; 
 margin-left: 40px; 
 margin-right: 40px; 
 margin-top: 30px; 
 font-size: 11px; 
 font-family: verdana,trebuchet,sans-serif; 
}
a:link { 
 color: #990000; 
 font-size: 11px; 
 font-weight: normal; 
 text-decoration: underline; 
}
a:visited { 
 color: #990000; 
 font-size: 11px; 
 font-weight: normal; 
 text-decoration: underline; 
}
a:active { 
 color: #990000; 
 font-size: 11px; 
 font-weight: normal; 
 text-decoration: underline; 
}
a:hover { 
 color: #990000; 
 font-size: 11px; 
 font-weight: normal; 
 text-decoration: none; 
}

</style>

<script language="javascript">
<!--

function add_smiley(smiley)
{
    opener.document.comment_form.comment.value += " " + smiley + " ";
    opener.window.document.comment_form.comment.focus();
    window.close();
}
//-->
</script>

</head>
<body>

<p>Click on an image to add it to your comment</p>

<table border="0" width="100%" cellpadding="6" cellspacing="1">

{exp:emoticon columns="4"}
<tr>
<td><div>{smiley}</div></td>
</tr>
{/exp:emoticon}

</table>

</body>
</html>
<?php

$buffer = ob_get_contents();
ob_end_clean(); 
return $buffer;

}
// END



//-------------------------------------
//	RSS 1.0
//-------------------------------------

function deft_rss_1()
{

ob_start();

echo "{exp:rss:feed weblog=\"weblog1\"}\n\n";

echo '<?xml version="1.0" encoding="{encoding}"?>'."\n";

?>
<rdf:RDF
    xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
    xmlns:dc="http://purl.org/dc/elements/1.1/"
    xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
    xmlns:admin="http://webns.net/mvcb/"
    xmlns:cc="http://web.resource.org/cc/"
    xmlns="http://purl.org/rss/1.0/">

<channel rdf:about="{weblog_url}">

    <title>{weblog_name}</title>
    <link>{weblog_url}</link>
    <description>{weblog_description}</description>
    <dc:language>{weblog_language}</dc:language>
    <dc:creator>{email}</dc:creator>
    <dc:date>{date format="%Y-%m-%dT%H:%i:%s%Q"}</dc:date>
    <admin:generatorAgent rdf:resource="" />
    
    <items>
      <rdf:Seq>{exp:weblog:entries weblog="weblog1" limit="10"}
      <rdf:li rdf:resource="{title_permalink=weblog/index}" />
      {/exp:weblog:entries}</rdf:Seq>
    </items>

</channel>

{exp:weblog:entries weblog="weblog1" limit="10" rdf="off"}
    <item rdf:about="{title_permalink=weblog/index}">
      <title>{exp:xml_encode}{title}{/exp:xml_encode}</title>
      <link>{title_permalink=weblog/index}</link>
      <description>{exp:xml_encode}{summary}{/exp:xml_encode}</description>
      <dc:subject>{categories backspace="1"}{category_name}, {/categories}</dc:subject>
      <dc:creator>{exp:xml_encode}{author}{/exp:xml_encode}</dc:creator>
      <dc:date>{entry_date format="%Y-%m-%dT%H:%i:%s%Q"}</dc:date>
    </item>
{/exp:weblog:entries}

</rdf:RDF>

{/exp:rss:feed}
<?php

$buffer = ob_get_contents();
ob_end_clean(); 
return $buffer;

}
// END



//-------------------------------------
//	RSS 2.0
//-------------------------------------

function deft_rss_2()
{

ob_start();

echo "{exp:rss:feed weblog=\"weblog1\"}\n\n";

echo '<?xml version="1.0" encoding="{encoding}"?>'."\n";

?>
<rss version="2.0"
    xmlns:dc="http://purl.org/dc/elements/1.1/"
    xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
    xmlns:admin="http://webns.net/mvcb/"
    xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
    xmlns:content="http://purl.org/rss/1.0/modules/content/">

    <channel>
    
    <title>{weblog_name}</title>
    <link>{weblog_url}</link>
    <description>{weblog_description}</description>
    <dc:language>{weblog_language}</dc:language>
    <dc:creator>{email}</dc:creator>
    <dc:rights>Copyright {date format="%Y"}</dc:rights>
    <dc:date>{date format="%Y-%m-%dT%H:%i:%s%Q"}</dc:date>
    <admin:generatorAgent rdf:resource="" />
    
{exp:weblog:entries weblog="weblog1" limit="10" rdf="off"}
    <item>
      <title>{exp:xml_encode}{title}{/exp:xml_encode}</title>
      <link>{exp:xml_encode}{title_permalink=weblog/index}{/exp:xml_encode}</link>
      <description>{exp:xml_encode}{summary}{/exp:xml_encode}</description>
      <dc:subject>{categories backspace="1"}{category_name}, {/categories}</dc:subject>
      <content:encoded><![CDATA[{body}]]></content:encoded>
      <dc:date>{entry_date format="%Y-%m-%dT%H:%i:%s%Q"}</dc:date>
    </item>
{/exp:weblog:entries}
    
    </channel>
</rss>

{/exp:rss:feed}
<?php

$buffer = ob_get_contents();
ob_end_clean(); 
return $buffer;

}
// END



//-------------------------------------
//	Atom
//-------------------------------------

function deft_rss_atom()
{

ob_start();

echo "{exp:rss:feed weblog=\"weblog1\"}\n\n";

echo '<?xml version="1.0" encoding="{encoding}"?>'."\n";

?>
<feed version="0.3"
    xmlns="http://purl.org/atom/ns#"
    xmlns:dc="http://purl.org/dc/elements/1.1/"
    xml:lang="{weblog_language}">

    <title>{weblog_name}</title>
    <link rel="alternate" type="text/html" href="{weblog_url}" />
    <tagline>{weblog_description}</tagline>
    <modified>{edit_date format='%Y-%m-%dT%H:%i:%s%Q'}</modified>
    <generator url="" version="{version}">ExpressionEngine</generator>
    <copyright>Copyright (c) {date format="%Y"}, {author}</copyright>

{exp:weblog:entries weblog="weblog1" limit="10" rdf="off"}
    <entry>
      <title>{exp:xml_encode}{title}{/exp:xml_encode}</title>
      <link rel="alternate" type="text/html" href="{title_permalink=weblog/index}" /> 
      <id>tag:{trimmed_url},{date format="%Y"}:{relative_url}/{weblog_id}.{entry_id}</id>
      <issued>{entry_date format="%Y-%m-%dT%H:%i:%s%Q"}</issued>
      <modified>{edit_date format='%Y-%m-%dT%H:%i:%s%Q'}</modified>
      <summary>{exp:xml_encode}{summary}{/exp:xml_encode}</summary>
      <created>{entry_date format='%Y-%m-%dT%H:%m:%s%Q'}</created>
		<author>
		  <name>{author}</name>
		  <email>{email}</email>
		  {if url}<url>{url}</url>{/if}
		</author>
      <dc:subject>{categories backspace="1"}{category_name}, {/categories}</dc:subject>
      <content type="text/html" mode="escaped" xml:lang="en-US"><![CDATA[{body}]]></content>
    </entry>
{/exp:weblog:entries}

</feed>

{/exp:rss:feed}
<?php

$buffer = ob_get_contents();
ob_end_clean(); 
return $buffer;

}
// END




function member_index()
{
return <<<EOF
{exp:member:manager}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{lang}">
<head>
<title>{page_title}</title>

<meta http-equiv='content-type' content='text/html; charset={charset}' />

{stylesheet}

</head>
<body>

<div id="content">
<div class='header'><h1>{heading}</h1></div>

{breadcrumb}
{content}
{copyright}

</div>

</body>
</html>
{/exp:member:manager}
EOF;
}
// END



function search_index()
{
return <<<EOF
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{lang}" lang="{lang}"> 

<head>
<title>{lang:search}</title>

<meta http-equiv="content-type" content="text/html; charset={charset}" />

<link rel='stylesheet' type='text/css' media='all' href='{stylesheet=search/search_css}' /> 
<style type='text/css' media='screen'>@import "{stylesheet=search/search_css}";</style>

</head>
<body>

<div id="content">
<div class='header'><h1>{lang:search_engine}</h1></div>

<table class='breadcrumb' border='0' cellpadding='0' cellspacing='0' width='99%'>
<tr>
<td><span class="defaultBold">&nbsp; <a href="{homepage}">{site_name}</a>&nbsp;&#8250;&nbsp;&nbsp;{lang:search}</span></td>
</tr>
</table>

<div class='outerBorder'>
<div class='tablePad'>


{exp:search:advanced_form result_page="search/results" }


<table cellpadding='4' cellspacing='6' border='0' width='100%'>
<tr>
<td>

<fieldset class="fieldset">
<legend>{lang:search_by_keyword}</legend>

<input type="text" class="input" maxlength="100" size="40" name="keywords" style="width:100%;" />

<div class="default">
<select name="search_in">
<option value="titles" selected="selected">{lang:search_in_titles}</option>
<option value="entries" selected="selected">{lang:search_in_entries}</option>
<option value="everywhere" >{lang:search_everywhere}</option>
</select>

</div>

</fieldset>


</td><td>

<fieldset class="fieldset">
<legend>{lang:search_by_member_name}</legend>

<input type="text" class="input" maxlength="100" size="40" name="member_name" style="width:100%;" />
<div class="default"><input type="checkbox" class="checkbox" name="exact_match" value="y"  /> {lang:exact_name_match}</div>

</fieldset>

</td>
</tr>
</table>


<table cellpadding='4' cellspacing='6' border='0' width='100%'>         
<tr>
<td valign="top" width="50%">


<table cellpadding='0' cellspacing='0' border='0'>         
<tr>
<td valign="top">

<div class="defaultBold">{lang:weblogs}</div>

<select id="weblog_id[]" name='weblog_id[]' class='multiselect' size='12' multiple='multiple' onchange='changemenu(this.selectedIndex);'>
{weblog_names}
</select>

</td>
<td valign="top" width="16">&nbsp;</td>
<td valign="top">

<div class="defaultBold">{lang:categories}</div>

<select name='cat_id[]' size='12'  class='multiselect' multiple='multiple'>
<option value='all' selected="selected">{lang:any_category}</option>
</select>

</td>
</tr>
</table>


</td>
<td valign="top" width="50%">


<fieldset class="fieldset">
<legend>{lang:search_entries_from}</legend>
				
<select name="date" style="width:150px">
<option value="0" selected="selected">{lang:any_date}</option>
<option value="1" >{lang:today_and}</option>
<option value="7" >{lang:this_week_and}</option>
<option value="30" >{lang:one_month_ago_and}</option>
<option value="90" >{lang:three_months_ago_and}</option>
<option value="180" >{lang:six_months_ago_and}</option>
<option value="365" >{lang:one_year_ago_and}</option>
</select>

<div class="default">
<input type='radio' name='date_order' value='newer' class='radio' checked="checked" />&nbsp;{lang:newer}
<input type='radio' name='date_order' value='older' class='radio' />&nbsp;{lang:older}
</div>

</fieldset>

<div class="default"><br /></div>

<fieldset class="fieldset">						
<legend>{lang:sort_results_by}</legend>

<select name="order_by">
<option value="date" >{lang:date}</option>
<option value="title" >{lang:title}</option>
<option value="most_comments" >{lang:most_comments}</option>
<option value="recent_comment" >{lang:recent_comment}</option>
</select>

<div class="default">
<input type='radio' name='sort_order' class="radio" value='desc' checked="checked" /> {lang:descending}
<input type='radio' name='sort_order' class="radio" value='asc' /> {lang:ascending}
</div>

</td>
</tr>
</table>


</td>
</tr>
</table>


<div class='searchSubmit'>

<input type='submit' value='Search' class='submit' />

</div>

{/exp:search:advanced_form}

<div class='copyright'>Powered by ExpressionEngine, Nullified by GTT</div>


</div>
</div>
</div>

</body> 
</html>
EOF;
}
// END



function search_results()
{
return <<<EOF
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{lang}" lang="{lang}"> 

<head>
<title>{lang:search}</title>

<meta http-equiv="content-type" content="text/html; charset={charset}" />

<link rel='stylesheet' type='text/css' media='all' href='{stylesheet=search/search_css}' /> 
<style type='text/css' media='screen'>@import "{stylesheet=search/search_css}";</style>

</head>
<body>

<div id="content">
<div class='header'><h1>{lang:search_results}</h1></div>

<table class='breadcrumb' border='0' cellpadding='0' cellspacing='0' width='99%'>
<tr>
<td><span class="defaultBold">&nbsp; <a href="{homepage}">{site_name}</a>&nbsp;&#8250;&nbsp;&nbsp;<a href="{path=search/index}">{lang:search}</a>&nbsp;&#8250;&nbsp;&nbsp;{lang:search_results}</span></td>
<td align="right"><span class="defaultBold">{lang:total_search_results} {exp:search:total_results}</span></td>
</tr>
</table>

<div class='outerBorder'>
<div class='tablePad'>

<table border="0" cellpadding="6" cellspacing="1" width="100%">
<tr>
<td class="resultHead">{lang:title}</td>
<td class="resultHead">{lang:excerpt}</td>
<td class="resultHead">{lang:author}</td>
<td class="resultHead">{lang:date}</td>
<td class="resultHead">{lang:total_comments}</td>
<td class="resultHead">{lang:recent_comments}</td>
</tr>

{exp:search:search_results switch="resultRowOne|resultRowTwo"}

<tr>
<td class="{switch}" width="30%" valign="top"><b><a href="{path}">{title}</a></b></td>
<td class="{switch}" width="30%" valign="top">{excerpt}</td>
<td class="{switch}" width="10%" valign="top"><a href="{member_path=member/index}">{author}</a></td>
<td class="{switch}" width="10%" valign="top">{entry_date format="%m/%d/%y"}</td>
<td class="{switch}" width="10%" valign="top">{comment_total}</td>
<td class="{switch}" width="10%" valign="top">{recent_comment_date format="%m/%d/%y"}</td>
</tr>

{/exp:search:search_results}

</table>


{if paginate}

<div class='paginate'>

<span class='pagecount'>{page_count}</span>&nbsp; {paginate}

</div>

{/if}


</td>
</tr>
</table>

<div class='copyright'>Powered by ExpressionEngine, Nullified by GTT</div>

</div>
</div>
</div>

</body> 
</html>
EOF;
}
// END


function search_css()
{
return <<<EOF
body {
 margin:0;
 padding:0;
 font-family:Verdana, Geneva, Tahoma, Trebuchet MS, Arial, Sans-serif;
 font-size:11px;
 color:#000;
 background-color:#fff;
}

a {
 text-decoration:none; color:#330099; background-color:transparent;
}
a:visited {
 color:#330099; background-color:transparent;
}
a:hover {
 color:#000; text-decoration:underline; background-color:transparent;
}

#content {
 left:				0px;
 right:				10px;
 margin:			10px 25px 10px 25px;
 padding:			8px 0 0 0;
}

.outerBorder {
 border:		1px solid #4B5388;
}

.header {
 margin:			0 0 14px 0;
 padding:			2px 0 2px 0;
 border:			1px solid #000770;
 background-color:	#797EB8;
 text-align:		center;
}

h1 {  
 font-family:		Georgia, Times New Roman, Times, Serif, Arial;
 font-size: 		20px;
 font-weight:		bold;
 letter-spacing:	.05em;
 color:				#fff;
 margin: 			3px 0 3px 0;
 padding:			0 0 0 10px;
}


.copyright {
 text-align:        center;
 font-family:       Verdana, Geneva, Tahoma, Trebuchet MS, Arial, Sans-serif;
 font-size:         9px;
 color:             #999;
 margin-top:        15px;
 margin-bottom:     15px;
}

p {  
 font-family:	Verdana, Geneva, Tahoma, Trebuchet MS, Arial, Sans-serif;
 font-size:		11px;
 font-weight:	normal;
 color:			#000;
 background:	transparent;
 margin: 		6px 0 6px 0;
}

.searchSubmit {
 font-family:       Verdana, Geneva, Tahoma, Trebuchet MS, Arial, Sans-serif;
 font-size:         11px;
 color:             #000;
 text-align: center;
 padding:           6px 10px 6px 6px;
 border-top:        1px solid #4B5388;
 border-bottom:     1px solid #4B5388;
 background-color:  #C6C9CF;  
}

.fieldset {
 border:        1px solid #999;
 padding: 10px;
}

.breadcrumb {
 margin:			0 0 10px 0;
 background-color:	transparent;   
 font-family:		Verdana, Geneva, Tahoma, Trebuchet MS, Arial, Sans-serif;
 font-size:			10px; 
}

.default, .defaultBold {
 font-family:		Verdana, Geneva, Tahoma, Trebuchet MS, Arial, Sans-serif;
 font-size:			11px;
 color:				#000;
 padding:			3px 0 3px 0;
 background-color:	transparent;  
}

.defaultBold {
 font-weight:		bold;
}

.paginate {
 font-family:		Verdana, Geneva, Tahoma, Trebuchet MS, Arial, Sans-serif;
 font-size:			12px;
 font-weight: 		normal;
 letter-spacing:	.1em;
 padding:			10px 6px 10px 4px;
 margin:			0;
 background-color:	transparent;  
}

.pagecount {
 font-family:		Verdana, Geneva, Tahoma, Trebuchet MS, Arial, Sans-serif;
 font-size:			10px;
 color:				#666;
 font-weight:		normal;
 background-color: transparent;  
}

.tablePad {
 padding:			3px 3px 5px 3px;
 background-color:	#fff;
}

.resultRowOne {
 font-family:		Verdana, Geneva, Tahoma, Trebuchet MS, Arial, Sans-serif;
 font-size:			11px;
 color:				#000;
 padding:           6px 6px 6px 8px;
 background-color:	#DADADD;  
}

.resultRowTwo {
 font-family:       Verdana, Geneva, Tahoma, Trebuchet MS, Arial, Sans-serif;
 font-size:         11px;
 color:             #000;
 padding:           6px 6px 6px 8px;
 background-color:  #eee;  
}

.resultHead {
 font-family:		Verdana, Geneva, Tahoma, Trebuchet MS, Arial, Sans-serif;
 font-size: 		11px;
 font-weight: 		bold;
 color:				#000;
 padding: 			8px 0 8px 8px;
 border-bottom:		1px solid #999;
 background-color:	transparent;  
}

form {
 margin:            0;
}
.hidden {
 margin:            0;
 padding:           0;
 border:            0;
}
.input {
 border-top:        1px solid #999999;
 border-left:       1px solid #999999;
 background-color:  #fff;
 color:             #000;
 font-family:       Verdana, Geneva, Tahoma, Trebuchet MS, Arial, Sans-serif;
 font-size:         11px;
 height:            1.6em;
 padding:           .3em 0 0 2px;
 margin-top:        6px;
 margin-bottom:     3px;
} 
.textarea {
 border-top:        1px solid #999999;
 border-left:       1px solid #999999;
 background-color:  #fff;
 color:             #000;
 font-family:       Verdana, Geneva, Tahoma, Trebuchet MS, Arial, Sans-serif;
 font-size:         11px;
 margin-top:        3px;
 margin-bottom:     3px;
}
.select {
 background-color:  #fff;
 font-family:       Arial, Verdana, Sans-serif;
 font-size:         10px;
 font-weight:       normal;
 letter-spacing:    .1em;
 color:             #000;
 margin-top:        6px;
 margin-bottom:     3px;
} 
.multiselect {
 border-top:        1px solid #999999;
 border-left:       1px solid #999999;
 background-color:  #fff;
 color:             #000;
 font-family:       Verdana, Geneva, Tahoma, Trebuchet MS, Arial, Sans-serif;
 font-size:         11px;
 margin-top:        3px;
 margin-bottom:     3px;
} 
.radio {
 color:             #000;
 margin-top:        7px;
 margin-bottom:     4px;
 padding:           0;
 border:            0;
 background-color:  transparent;
}
.checkbox {
 background-color:  transparent;
 margin:            3px;
 padding:           0;
 border:            0;
}
.submit {
 background-color:  #fff;
 font-family:       Arial, Verdana, Sans-serif;
 font-size:         10px;
 font-weight:       normal;
 letter-spacing:    .1em;
 padding:           1px 3px 1px 3px;
 margin-top:        6px;
 margin-bottom:     4px;
 text-transform:    uppercase;
 color:             #000;
}
EOF;
}
// END


?>