<?php

if(!$charjoblookup){
$charjoblookup=$stat[id];
}

$charjob = mysql_fetch_array(mysql_query("select * from `charjob` where `character`='$charjoblookup' limit 1"));

if($charjob[id]){

$jobses = mysql_fetch_array(mysql_query("select * from `jobs` where `job`='$charjob[job]' limit 1"));
$jobdes = str_replace("'" , "" , $jobses[jobdes]);
$ucname=ucfirst($jobses[job]);

        print"<a href=\"javascript:;\" onmouseover=\"return escape('$jobdes')\">$ucname</a>";

}else{

        mysql_query("INSERT INTO `charjob` (`character`)
                                        VALUES
                                        ('$charjoblookup')") or die("<br>Could not register charjob.");

        print"Unemployed";

}