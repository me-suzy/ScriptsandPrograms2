sNews v1.3
Copyright(c) 2005, Solucija - All rights reserved
http://www.solucija.com/
---------------------------------------------------------------------------

Welcome to sNews, a single-file template-independent PHP and MySQL, CSS
and XHTML valid CMS that powers your site. sNews was created for all those 
who want to change their design easily and everytime they want without 
adapting their template to any CMS.

---------------------------------------------------------------------------

Install your sNews v1.3 through these 3 easy steps:


1) Edit 'snews.php' and enter your settings at the top of the file,
   the default username and password are "test".

2) Copy files to your server and CHMOD 777 your folder where you'll be
   uploading your images. (default: 'img')

3) Create the MySQL database with this code:

CREATE TABLE articles (
  id INT PRIMARY KEY AUTO_INCREMENT,
  title VARCHAR(100) DEFAULT NULL,
  text TEXT,
  textlimit INT(5) NOT NULL DEFAULT '0',
  date DATETIME DEFAULT NULL,
  category INT(8) NOT NULL DEFAULT '0',
  position CHAR(3),
  displaytitle CHAR(3) NOT NULL DEFAULT 'YES',
  displayinfo CHAR(3) NOT NULL DEFAULT 'YES',
  commentable VARCHAR(5) NOT NULL,
  image varchar(30) DEFAULT NULL
);


CREATE TABLE categories (
  id int(8) PRIMARY KEY AUTO_INCREMENT,
  name varchar(20) NOT NULL,
  description varchar(50) NOT NULL,
  published varchar(4) NOT NULL DEFAULT 'YES'
);


CREATE TABLE comments (
  id INT(11) PRIMARY KEY AUTO_INCREMENT,
  articleid INT(11) DEFAULT '0',
  name varchar(50) DEFAULT '',
  comment TEXT,
  time DATETIME NOT NULL DEFAULT ''
);   



4) You are ready to go!
Go to administration area and start writing your articles.

Please send bug reports, suggestions, comments, questions:
info@solucija.com

Support and additional info: 
forum.solucija.com

Your Solucija.com team!

DISCLAIMER:

sNews is distributed "as is", no warranty of any kind is
expressed or implied. You use this software at your own risk.
Only commercial licence holders are allowed to remove the 
'Powered by sNews' line.