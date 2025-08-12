//////////////////////////////////////////////////////////////////////////
/////////////////                 aWebNews              //////////////////
//////////////////////////////////////////////////////////////////////////
============================================
INSTALL

1. Edit the values in config.php to suit your MySQL DB
2. Upload all files to you server
3. Point your browser to 
	www.yourdomain.com/aWebNews/install.php
4. To display news on your pages, change the page extension to .php and add the code: 
<?php
// Number of Stories to display
$number_of_stories = "8";
// Width of news section
$news_width1 = "550px";
// Path to news directory in relation to page displaying the news
$path_to_news = "aWebNews/";
// Location of news page in relation to page displaying the news (visview.php)
include "aWebNews/visview.php";
?> 

============================================
VERSION 1.0

This is the first release of the aWebNews a php and mysql
news and comment management script.  It's simple Admin GUI 
and ease of use make it ideal for small to medium sites 
which need a news and comment system.
Expect future versions, check out www.labs.aweb.com.au

============================================
LICENSE

aWebNews is licensed under the GNU General Public
License available at: 
	http://www.gnu.org/copyleft/gpl.html
In using aWebNews you agree to the terms and conditions
set forward in the General Public License.  

============================================
LAST MODIFIED ON Mar. 12, 2005
