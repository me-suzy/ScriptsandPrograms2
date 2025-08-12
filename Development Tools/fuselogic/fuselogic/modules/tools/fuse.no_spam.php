<?php
$spamer = array();

//insert your spamer list here
$spamer[] = 'sex';
$spamer[] = 'incested';
$spamer[] = '4t.com';
$spamer[] = 'rubli.biz';
$spamer[] = 'nude';
$spamer[] = 'gang-bang';
$spamer[] = 'girls';
$spamer[] = 'rape';
$spamer[] = 'lesbiansmania';
$spamer[] = 'pervertedtaboo.com';
$spamer[] = 'bad-movies.net';
$spamer[] = 'nudity';
$spamer[] = 'gay';
$spamer[] = 'pussy';
$spamer[] = 'wmwars.com';
$spamer[] = 'blogspot.com';
$spamer[] = 'bad-movies.net';
$spamer[] = 'movies.com';
$spamer[] = 'x-stories.org';
$spamer[] = 'porn';
$spamer[] = 'linuxwaves.com';
$spamer[] = 'incest';
$spamer[] = '3333.ws';
$spamer[] = 'cool-extreme.com';
$spamer[] = 'pics';
$spamer[] = 'pictures';
$spamer[] = 'hardcore';



//this line is for testing only
//$spamer[] = 'LocalHost';

require_once('class.no_spamer.php');
$test = &new no_spam($spamer);
$test->hitBack();
//$test->redirectTo('http://www.yahoo.com');

?>