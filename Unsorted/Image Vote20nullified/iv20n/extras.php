<?
//////////////////////////////////////////////////////////////////////////////
// Program Name         : Image Vote - Photo Rating System                  //
// Release Version      : 2.0.0                                             //
// Program Author       : Ronald James                                      //
// Supplied by          : Scoons [WTN]                                      //
// Nullified by         : CyKuH [WTN]                                       //
//////////////////////////////////////////////////////////////////////////////
// COPYRIGHT NOTICE                                                         //
// (c) 2002 Ronald James    All Rights Reserved.                            //
// Distributed under the licencing agreement located in wtn_release.nfo     //
//////////////////////////////////////////////////////////////////////////////
// -- field definitions --

$extra=array();   // leave this line alone

/*
[Start Instructions]

The following is the setup for the extra profile fields
There are two settings that need to be set for each profile field
you wish to use.  

First, the [name] will contain the name of the field, as you wish to
have displayed throughout your site.  This can be anything you wish.  
Leave it set as "" to disable a field from being used.

Second, you will need to specify the type of information to accept
for the profile field.  This is set by the [type]

For information where you want the user to enter his own data, leave
the [type] set as "text"  

For items where you want the user to select from a pre-defined list of
options, use the array type, in this format:
$extra[info1][type]=array("Choice 1","Choice 2","Choice 3");


[End Instructions]
*/

$extra[info1][name]="Marital Status";
$extra[info1][type]=array("Married","Single","Undecided","Engaged","Dating");

$extra[info2][name]="Something About You";
$extra[info2][type]="text";

$extra[info3][name]="Favorite Movie";
$extra[info3][type]="text";

$extra[info4][name]="";
$extra[info4][type]="text";

$extra[info5][name]="";
$extra[info5][type]="text";

$extra[info6][name]="";
$extra[info6][type]="text";

$extra[info7][name]="";
$extra[info7][type]="text";

$extra[info8][name]="";
$extra[info8][type]="text";

$extra[info9][name]="";
$extra[info9][type]="text";

$extra[info10][name]="";
$extra[info10][type]="text";

$extra[info11][name]="";
$extra[info11][type]="text";

$extra[info12][name]="";
$extra[info12][type]="text";

$extra[info13][name]="";
$extra[info13][type]="text";

$extra[info14][name]="";
$extra[info14][type]="text";

$extra[info15][name]="";
$extra[info15][type]="text";

$extra[info16][name]="";
$extra[info16][type]="text";

$extra[info17][name]="";
$extra[info17][type]="text";

$extra[info18][name]="";
$extra[info18][type]="text";

$extra[info19][name]="";
$extra[info19][type]="text";

$extra[info20][name]="";
$extra[info20][type]="text";

// do not edit past this line

$extras = array();
for ($i=0; $i < 21; $i++)
 { $marker = "info".$i; // print $marker."<br>"; // debug
 if (strlen($extra[$marker][name]) > 1)

  $extras[]=$marker;
 }


/* this is the code to call the extra fields from other php scripts.

if (sizeof($extras) > 0) {

$xquery = "select ";
do {
      $xquery .= ($extras[key($extras)]) . ", ";
    } while (next($extras));
$xquery = substr ($xquery, 0, -2);
$xquery .= " from $usertable where name = '$name' limit 1";

$xresult = mysql_query($xquery);
foreach ( $extras as $marker ) {  // set values
   $extra[$marker][value]=mysql_result($xresult,0,$marker);
   }

foreach ( $extras as $marker ) {  // display values
print $extra[$marker][name].": ".$extra[$marker][value]."<br>";
   }

}
*/

// in general, to display extra variable values, use $extra[infox][value]


?>
