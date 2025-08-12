CREATE TABLE am_chapter (
   fldAuto int(11) NOT NULL auto_increment,
   faq_fldAuto int(11) DEFAULT '0' NOT NULL,
   name varchar(50) NOT NULL,
   orderingfield varchar(10) NOT NULL,
   PRIMARY KEY (fldAuto),
   UNIQUE fldAuto (fldAuto),
   KEY fldAuto_2 (fldAuto, faq_fldAuto)
);

#
# Dumping data for table 'am_chapter'
#

INSERT INTO am_chapter VALUES ( '1', '1', 'General', 'ZZZZZZ');
INSERT INTO am_chapter VALUES ( '2', '1', 'Download', 'ZZZZZZ');
INSERT INTO am_chapter VALUES ( '3', '1', 'Help', 'ZZZZZZ');

# --------------------------------------------------------
#
# Table structure for table 'am_faq'
#

CREATE TABLE am_faq (
   fldAuto int(11) NOT NULL auto_increment,
   name varchar(50) NOT NULL,
   descr text NOT NULL,
   PRIMARY KEY (fldAuto),
   UNIQUE fldAuto (fldAuto),
   KEY fldAuto_2 (fldAuto)
);

#
# Dumping data for table 'am_faq'
#

INSERT INTO am_faq VALUES ( '1', 'ArticleMentor', 'A year ago or so I developed quite a few free scripts written in ASP ( <a href=\\\"http://www.aspcode.net/articlementor/index.php\\\">Listing here</a> ). Amongst other AdMentor ( a very popular banner rotation script ), a Postcard script, Poll script etc. I also developed ArticleMentor - which was a simple/lightweight content management system. 

Now I have started porting ( and enhancing ) the scripts to PHP, and the first I release is ArticleMentor.

');

# --------------------------------------------------------
#
# Table structure for table 'am_question'
#

CREATE TABLE am_question (
   fldAuto int(11) NOT NULL auto_increment,
   chapter_fldAuto int(11) DEFAULT '0' NOT NULL,
   question varchar(255) NOT NULL,
   answer longtext NOT NULL,
   orderingfield varchar(10) NOT NULL,
   PRIMARY KEY (fldAuto),
   UNIQUE fldAuto (fldAuto),
   KEY fldAuto_2 (fldAuto, chapter_fldAuto)
);

#
# Dumping data for table 'am_question'
#

INSERT INTO am_question VALUES ( '1', '1', 'Presentation and features', 'ArticleMentor is a content management system with these features. 

1. Lightweight. Few tables and few scripts.

2. MySQL database access

3. Three layered content structure - Categories, subcategories and the actual articles. For example I have a category \\\'ArticleMentor\\\', with subcategories \\\'Features\\\', \\\'Download\\\' etc and all subcategories contains articles 

4. Show multipage articles ( with automatic next/prev buttons ). See example by looking at the Admin GUI screenshots

5. Articles can also be co called \\\'external links\\\' - when you just specify an url as articletext then when user clicks on the article he/she will be redirected to that url


6. Article should be able to contain HTML tags





', 'ZZZZZZ');
INSERT INTO am_question VALUES ( '2', '2', 'Version log', '<b>1.0 2000-09-04</b>
First ASP version released.

<b>2.0 2001-08-23</b>
First PHP version released. Enhancements include:
- Better CSS support



', 'ZZZZZZ');
INSERT INTO am_question VALUES ( '3', '1', 'Online DEMO - well you are looking at ArticleMentor right now...', 'http://www.aspcode.net/php/article/index.php', 'ZZZZZZ');
INSERT INTO am_question VALUES ( '4', '1', 'Admin GUI screenshots', '<b>The system is administered with a webbased GUI, here are some screenshots:</b>


<img src=\\\"screenshots/image1.gif\\\">

<NEWPAGE>

<img src=\\\"screenshots/image2.gif\\\">

<NEWPAGE>

<img src=\\\"screenshots/image3.gif\\\">


', 'ZZZZZZ');
INSERT INTO am_question VALUES ( '5', '3', 'Config issues', 'Create a database in MySQL ( lets say you call it artment ) and then run the script artmentor.sql.

Open up incconfig.php.

Change the rows
<EM>
$incdbhost = \\\"localhost\\\";
$incdbuser = \\\"youruser\\\";
$incdbpwd = \\\"yourpwd\\\";
$databasename = \\\"artment\\\";
</EM>

to reflect your installation.

Thats it. Now you should be able to run it.

Read on for more advanced configuration
<NEWPAGE>
<b>GUI configuration</b>

- style.css can be changed to get some other look
- also the file inctemplate.php. Change that file to reflect your own GUI - however be sure to look for the PHP variables so you don\\\'t delete them.

', 'ZZZZZZ');
