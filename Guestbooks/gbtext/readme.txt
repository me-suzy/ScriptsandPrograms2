
Guesbook-Text V1.0
------------------
This is very simple script, 
Need only one PHP file program and one TEXT file for data, 
No need for MySQL database. 
Featured with: 
- Record Paging 
- Duplicate Entry Check 
- HTML Code Parsing 
- Long Words spliter 
- and much more. 
Just try it, and you'll know what I mean :) 


This program is free,
and you could modify it as much as you like
And, ... If you appreciated with my works.
Please do not remove my identity or my mark, 
I will thank you a lots for your appreciation.

Best regards;
Ridwank (mail@ridwank.com)


INSTALLATION:
=============
1. Unzip the file gbtext.zip.
   But if you are reading this file,
   I believe you're already done this step.

2. Upload the files :
   - guestbook.php (Guestbook Page)
   - adm_guestbook.php (Admin Page)
   - data.txt (Data File)
   in any folder you like, 
   and you could specified your own data directory in 
   or even rename the file with your own chosen name
   Just type your own specified data file behind the line:
   // *** LOADING CONFIG FILE 
   e.g.:
   $data="files/private/data.txt"; 

3. Change File MODE of "data.txt" to "666" (rw-rw-rw-)
   

ADMIN:
======
I have already made the DATABASE ADMIN PAGE,
the file, named "adm_guestbook.php"
You could EDIT or DELETE the guestbook record,

But if you like to change or edit contents directly on data file,
you could use any TEXT EDITOR,
Just open your "data.txt" then change what you like.
This way is more complicated 
rather using the administration program which simple and easy, 

I made the structure of file "data.txt" in one line of record each ,
and I marked with "|line|"
which in every line record divided with "|#|" to specified the field
the order format are :

|line|POSTDATE|#|NAME|#|EMAIL|#|SHOW_EMAIL|#|HOMEPAGE|#|COMMENTS

example, the content would be like this :
|line|25 Jun 2004 - 16:14|#|Ridwan Karsadarma|#|mail@ridwank.com|#|y|#|http://www.ridwank.com|#|This is very simple script, need only one PHP file program and one TEXT file for data, No need for MySQL database. Featured with: - Record Paging - Duplicate Entry Check - HTML Code Parsing - Long Words spliter - and much more. Just try it, and you'll know what I mean :)

So if you like to edit the record just don remove the "|line|" and "|#|" marks.

regards;
RIDWANK


