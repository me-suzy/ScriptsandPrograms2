<?php


$style = mysql_fetch_array(mysql_query("select * from styles where owner='$stat[id]'"));

if(!$style[owner]){
$style = mysql_fetch_array(mysql_query("select * from styles where owner='2'"));
}

if(!$w||!$h){
$w=$stat[width];
$h=$stat[height];
}
$useie = strpos("$_SERVER[HTTP_USER_AGENT]", 'MSIE');

if($w<1024||$h<=768){
$ressmall=1;
}

if($useie>0||$ressmall==1){
$msie=1;
}

if($style[id]>0){

$sized=$style[size]/10;
$sized=round($sized,1);

print"<style type=\"text/css\">

body {
font: $style[size]px/$sized Arial, Helvetica, Sans-Serif;
background:$style[back];
padding:0px;
margin:0px;

scrollbar-arrow-color: $style[back];
scrollbar-base-color: $style[links];
scrollbar-dark-shadow-color: $style[stats];
scrollbar-track-color: $style[border];
scrollbar-face-color: $style[text];
scrollbar-shadow-color: $style[admin];
scrollbar-highlight-color: $style[admin];
scrollbar-3d-light-color: $style[admin];

}

a {
text-decoration:underline;
font-weight:bold;
color:$style[links];
}

.stats {
color:$style[stats];
}

.admin {
color:$style[admin];
}

admin {
color:$style[admin];
}

.statsbar {
background-color: $style[sempty];
border:2px inset $style[border];
}

.sempty {
border:2px inset $style[border];
background-color: $style[sempty];
}

.sfull {
border:2px inset $style[border];
background-color: $style[sfull];
}

.event {
text-align: center;
color:$style[eventtext];
border:2px inset $style[border];
background-color: $style[eventback];
}


.eventit {
text-align: center;
color:$style[eventittext];
border:2px inset $style[border];
background-color: $style[eventitback];
}

ol {
margin-right:40px;
}
li {
margin-bottom:10px;
}

        body {
                margin:10px 10px 0px 10px;
                padding:0px;
                color:$style[text];
                }

        #leftcontent {
";

if($msie>0){
print"position: absolute;";
}else{
print"position: fixed;";
}

print"
                left:10px;
                top:50px;
                width:150px;
                background:$style[back];
                border:2px inset $style[border];
                }

        #centercontent {

                background:$style[back];
                   margin-left: 160px;
                   margin-right:190px;
                border:1px dashed $style[border];


                voice-family: \"\\\"}\\\"\";
                voice-family: inherit;
                   margin-left: 160px;
                   margin-right:190px;
                }
        html>body #centercontent {
                   margin-left: 160px;
                   margin-right:190px;
                }



                #popcentercontent {

                background:$style[back];
                   margin-left: 10px;
                   margin-right:10px;
                border:1px dashed $style[border];


                voice-family: \"\\\"}\\\"\";
                voice-family: inherit;
                   margin-left: 10px;
                   margin-right:10px;
                }
        html>body #popcentercontent {
                   margin-left: 10px;
                   margin-right:10px;
                }


        #rightcontent {
";

if($msie>0){
print"position: absolute;";
}else{
print"position: fixed;";
}

print"

                right:9px;
                top:50px;
                width:180px;
                background:$style[back];
                border:2px inset $style[border];
                }

        #top {";

if($msie>0){
//print"position: absolute;";
}else{
print"position: fixed;";
}

print"top:10px;
                right:10px;
                left:10px;
                background:$style[back];

                border-top:2px inset $style[border];
                border-bottom:2px inset $style[border];
                border-right:2px inset $style[border];
                border-left:2px inset $style[border];
                voice-family: \"\\\"}\\\"\";
                voice-family: inherit;

                }
        html>body #top {

                }


        #lowcontent {

";

if($msie>0){
//print"position: absolute;";
}else{
print"position: fixed;";
}

print"
                bottom:10px;
                right:10px;
                left:10px;
                background:$style[back];
                color:$style[text];
                border-top:2px inset $style[border];
                border-bottom:2px inset $style[border];
                border-right:2px inset $style[border];
                border-left:2px inset $style[border];
                voice-family: \"\\\"}\\\"\";
                voice-family: inherit;
                }
        html>body #top {

                }



</style>";









}else{

print"<style type=\"text/css\">

body {
font:12px/1.2 Verdana, Arial, Helvetica, sans-serif;
background:#FFF;
padding:0px;
margin:0px;
}

a {
text-decoration: underline;
font-weight:bold;
color:#c00;
}

pre {
font-size:11px;
color:blue;
}

.stats {
color:#999;
}

.admin {
color:#800;
}

.statsbar {
background-color: #FFF;
}

.sempty {
background-color: #FFF;
}

.sfull {
background-color: #666;
}

.event {
color:#000;
background-color: #DDD;
}


.eventit {
color:#CCC;
background-color: #666;
}

ol {
margin-right:40px;
}
li {
margin-bottom:10px;
}

        body {
                margin:10px 10px 0px 10px;
                padding:0px;
                color:#000;
                }

        #leftcontent {
";

if($msie>0){
print"position: absolute;";
}else{
print"position: fixed;";
}

print"

                left:10px;
                top:50px;
                width:150px;
                background:#fff;
                border:2px inset #000;
                }

        #centercontent {

                background:#fff;
                   margin-left: 155px;
                   margin-right:190px;
                border:2px inset #000;

                voice-family: \"\\\"}\\\"\";
                voice-family: inherit;
                   margin-left: 155px;
                   margin-right:190px;
                }
        html>body #centercontent {
                   margin-left: 155px;
                   margin-right:190px;
                }

                                #popcentercontent {

                background:$style[back];
                   margin-left: 10px;
                   margin-right:10px;
                border:1px dashed $style[border];


                voice-family: \"\\\"}\\\"\";
                voice-family: inherit;
                   margin-left: 10px;
                   margin-right:10px;
                }
        html>body #popcentercontent {
                   margin-left: 10px;
                   margin-right:10px;
                }

        #rightcontent {
";

if($msie>0){
print"position: absolute;";
}else{
print"position: fixed;";
}

print"

                right:9px;
                top:50px;
                width:180px;
                background:#fff;
                border:2px inset #000;
                }

        #top {
";

if($msie>0){
//print"position: absolute;";
}else{
print"position: fixed;";
}

print"
                top:10px;
                right:10px;
                left:10px;

                background:#fff;

                border-top:2px inset #000;
                border-bottom:2px inset #000;
                border-right:2px inset #000;
                border-left:2px inset #000;
                voice-family: \"\\\"}\\\"\";
                voice-family: inherit;

                }
        html>body #top {

                }


        #lowcontent {

";

if($msie>0){
//print"position: absolute;";
}else{
print"position: fixed;";
}

print"
                bottom:10px;
                right:10px;
                left:10px;

                background:#fff;
                color:#000;
                border-bottom:2px inset #000;
                border-top:2px inset #000;
                border-right:2px inset #000;
                border-left:2px inset #000;
                voice-family: \"\\\"}\\\"\";
                voice-family: inherit;
                }
        html>body #top {

                }



</style>";
}