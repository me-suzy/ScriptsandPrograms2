<?
/*
    .: EasyNews by Pierino :.
|===============================|
| http://www.code4fun.org       |
| mail01: info@code4fun.org     |
| mail02: sanculamo@hotmail.com |
|===============================|

*/

//---------------------------< general settings >-------------------------------

/* Database Account */
$db_host = 'localhost';
$db_name = 'news';
$table_name = 'easynews16';
$db_user = 'user';
$db_pw = 'pw';

/* Control Panel (admin area) User Id & Password */
$id='admin';
$pw='admin';

/* EasyNews folder path. Insert the path to the script file's folder,
   relative to the file that will include the news
   (refer to readme.txt for further details) */
$enPath = 'easynews/';

/* Number of news to show for each page */
$newscount=4;

/* Max upload image file size ( Kbyte ) */
$maxSize=200;

/* image ratio setup (percentage '%') */
$imgRatio=30; // 30 = image will take max 30% of the news width

/* max char for each news */
$charMax=600; // 0: no cut


//---------------------------< style settings >---------------------------------

/* table */
$tableBorder=2;              // table border (pixel), 0 = no border
$borderColor='#E7E7E7';      // table border color
$tableWidth=460;             // table width
$newsSpacer=6;               // space between news (pixel)

/* date & title fields */
$textAlign='left';           // date & title alignment
$dateBgColor='#E5E4C6';      // date & title fields background color, you can also use 'transparent'.
$dateSize=10;                // date font size (pixel)
$dateColor='#000000';        // date font color
$dateBoldness='normal';      // date boldness: normal, bold, bolder or lighter
$titleSize=13;               // title font size (pixel)
$titleColor='#000000';       // title font color
$titleBoldness='bold';       // title boldness: normal, bold, bolder or lighter

/* text field */
$textSize=13;                // text font size (in pixel)
$textColor='#000000';        // text font color
$textBoldness='normal';      // text boldness: normal, bold, bolder or lighter
$textBgColor='#ffffff';      // text background color, you can use also 'transparent'.
$contentPadding=4;           // text (and image) padding


?>
