EZ Query by Skorch

This was designed mainly to display data. I also assumed that only webmasters would be using the alter db
commands. I use it with dynamic URL's and a apache mod-rewriter. I store each page in a db row and one 
page displays the information from a specific Database row, defined by the dynamic URL.


You must open EZQuery with a text editor and change the function db to reflect your 
personal MySql settings. I indicated by Capitalizing the required info. 

Then for each page that requires a MySql connection.

<?php 
 require_once('path/to/EZQuery.php');//needs this
 $mysql=new db;                     //create a object that connects to db
 $mysql->set('My_table','*');      //I want all info from My_table
 $mysql->ezq('page_id=13',0);     //return mysql fetch assoc WHERE page_id=13
 FOREACH($mysql){                //loop through results
 echo $mysql;                   //echo each result one by one
 }
?>


function list

 db->constructor
 set->choose db table and/or field
 stash->string prep
 dash->oppo of stash
 ezq->fetch query
 upq->update table
 inq->add row to table

I made this class to interface with a MySql database. The core function is Ezq. Before this class can be used 
you must use the function  set(It has two modes, specific and all). Most queries use the vars that this function 
sets I did this so a programmer had ultimate control over where information came from or where it goes. Once you 
are familiar with how this class works you will be able to make any mysql SELECT clause. The Stash and Dash 
functions can(and really should) be expanded to meet your specific needs.

Detailed use of function inputs

  db-
This function establishes a db connection. It is also the constructor
and has no input. output is db connection The first line of any extended
class' constructor should be make a new db object. it has no input and should
be hardcoded with your mysql db

  set(table,*/field)-
This function allows you to select which table to get data from,It uses 
class var $from. Input is table name Presets class Var $from. $col is another class var
If text is entered as the second parameter you can set field name too. set to 0 otherwise 
(uses "SELECT * FROM table" rather than "SELECT field FROM table")

  stash(string)-
This function adds html entities and slashes. Input is one string(user subbed)
output is string

  dash(string)-
This function gets rid of added slashes. Input is one string(from db)
output is string

  ezq("field=value",0)-
This function returns an associative array from the db or performs a query. 
Inputs are Complete WHERE clause "field=value" and an integer. If integer is equal to 12 a 
basic query will be done(for use with mysql functions other than fetch assoc. 
If it's not equal to 12 a assoc array will be fetched at the end of the first parameter you can add extra
mysql functions like order by or limit

  inq(table,array of values)-
This is the insert command. It has no string val at moment. It returns status of attempted
query. Inputs are Table-Name AND array-of-values 

  upq(field=value,array of combined fieldnames and values)-
This is the update row. This is the most difficult function to 
use being that the SET statement must be built. First is the where statement(field=value)
and an array. Each array line must contain "fieldname=desiredvalue"  field1=value1,etc I made this process automated for myself by
naming fields that can be updated(you are planning and outlining your project?) 

text1,text2,text3 

then I just 

FOR loop linking the relevant result to the relevant field. 
i.e. FOR($i=1;$i > 4;$i++){$array[$i]='text'.$i.'='.$value[$i];}
$this->upq('field=value',$array);

