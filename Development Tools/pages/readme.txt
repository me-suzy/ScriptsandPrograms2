http://fi.ddfr.ru/free/
last modify 26.03.02(Russia)
class pages version 0.5 stable

It's just a class I need for some my projects and I think it will usefull for someone else as this trouble 
is common. It easy to use, just include pages.php, and initalyze class 'pages', set a properties,
after that use method 'check()' and array's element 0 will be your mysql result.
You can use it in your way futher.
Use 'make_list()' to make a page navigation.

**NOTE**
Because of slow PHP mysql_num_rows() function it will be inefficiently to use this->class
for a large data sets, but for record number <2000(without limit) it will be ok. Best of all is to count
number of row using built in mysql aggregate function 'count(*)', it will be MUCH faster,
but for this library such method hasn't found its application. If you need one, try to write
something of your own.

PROPERTIES:
1. [start] - from which offset to start (default value is current and if empty use 0)
2. [lim] - limitation per page (default 10)
3. [type] Set to 'normal' or 'extend'. default 'normal'
4. [script] - name of script to link to (normaly it should be current document) default current document
5. [vars] - array of variables to pass with (array("user"=>Bobby, $something=>$else))
	***variable 'start' will be added to query string automaticaly
6. query - SQL query without limit
7. [titlenext] - title text of > 
8. [titleprev] - title text of <
9. [center] Set to true or false. Align navigation bar by center or not. Default true.

METHODS:
1. array check()
	element 0 - mysql result
	element 1 - to list or not to list(true,false). You don't need it.
	element 2 - number of records total without limit

2. void make_list() - print a listing (Make a page navigation - that's your aim). Call it anywhere you need.

Example of use:
	$vr=array("tid"=>$tid, "catid"=>$catid, "view"=>"yes");//(http query will be: script.php?tid=something&catid=6&view=yes)
	$zapros="select * from $tbl order by $order";//(any valid mysql query)
	$tty=new pages();
	$tty->lim=50; //records per page
	$tty->zapros=$zapros;
	$tty->type="extend";
	$tty->script=self.php; //Call only if you need to link to other document
	$tty->vars=$vr;
	$tty->center=false;
	$oop=$tty->check();
	$res=$oop[0];
	...............
	$tty->make_list();


SOME PROJECTS THAT USE CLASS 'PAGES':
1. FI.DDFR.RU //WWW catalog
2. STAT.DDFR.RU //counter & stats
3. LENTAI.RU //Inet shop
4. VECTOR-VENT.RU //Just a corporate site
5. GAMEZ.EIT.RU //Games portal

Copyright 2001-2002 NetScripter 
Please send any suggestions, questions, bug reports to sasha@ddfr.ru


