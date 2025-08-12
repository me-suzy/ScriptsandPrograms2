<?php error_reporting(1);if(function_exists('import_request_variables')){import_request_variables("gpc");$REMOTE_ADDR=$HTTP_SERVER_VARS['REMOTE_ADDR'];$PHP_SELF=$HTTP_SERVER_VARS['PHP_SELF'];$SERVER_NAME=$HTTP_SERVER_VARS['SERVER_NAME'];$SCRIPT_NAME=$HTTP_SERVER_VARS['SCRIPT_NAME'];}

error_reporting(8);

$time_format='d M Y H:i';                  // time format [ allowed attributes: http://www.php.net/date ]
$members_only=0;                           // guests are allowed to post (yes=0 or no=1)
$members_edit=1;                           // all members are allowed to edit their posts (yes=1 or no=0)
$pass_field='text';                        // password fields ('text'=visible, 'password'=*****)
$allow_proxies=1;                          // proxy users are allowed (yes=1 or no=0) (some proxy servers cannot be detected)
$post_interval=30;                         // interval between posts (seconds)
$topics_max=150;                           // max number of topics per forum
$posts_max=20;                             // max number of posts in a topic
$topics_per_page=10;                       // topics per page

$admin_mail="not_set_yet";                 // admin e-mail address (if you want to receive all posts via e-mail)
include "incl/lang-en.inc";                // language file
$members_file="-members.php";              // members file

$flood[0]=7;                               // how many posts out of the latest 10 an IP can have (a number greater than 10 would switch this off)
$flood[1]=15;                              // allowed length of the username
$flood[2]=20;                              // allowed length of the subject
$flood[3]=3000;                            // allowed length of the post
$flood[4]=80;                              // allowed length of a word
$flood[5]=100;                             // allowed length of the members info
$flood[6]=60;                              // allowed lines in a post
                                           // !!! set greater values (at least current*8) for flood[1-5] if you use Chinese, Japanese etc

$bad_words=array('fuck','bitch');          // words which are not acceptable

$auto_repair=1;                            // repair if necessary (yes=1 or no=0)
$gzip=1;                                   // use gzip compression (1=yes, 0=no)
$color_changing=1;                         // color changing (yes=1 or no=0)
$default_css=2;                            // default CSS file (a number between 0 and 9)
$rss_entries=5;                            // number of RSS entries

$cellpadding=8;                            // table cellpadding
$size_img[0]='width="15" height="15"';     // size of the smilie pictures
$size_img[1]='width="11" height="14"';     // size of the avatar pictures
$size_img[2]='width="19" height="24"';     // size of the topic pictures
$size_img[3]='width="14" height="14"';     // size of the post pictures

$forum_name=array();$forum_data=array();$forum_back=array();$forum_desc=array();

$forum_name[0]='1st forum';                // the name of forum #1 (the default one)
$forum_data[0]='data';                     // a CHMODed to 777 directory
$forum_back[0]='backup';                   // a CHMODed to 777 directory (backup directory for this forum)
$forum_desc[0]='description...';           // a few words to describe this forum

// $forum_name[1]='2nd forum';             // the name of forum #2
// $forum_data[1]='';                      // a CHMODed to 777 directory
// $forum_back[1]='';                      // a CHMODed to 777 directory (backup directory for this forum)
// $forum_desc[1]='description...';        // a few words to describe this forum

// $forum_name[2]='3rd Forum';             // the name of forum #3
// $forum_data[2]='';                      // a CHMODed to 777 directory
// $forum_back[2]='';                      // a CHMODed to 777 directory (backup directory for this forum)
// $forum_desc[2]='description...';        // a few words to describe this forum

// $forum_name[3]='4th Forum';             // the name of forum #4
// $forum_data[3]='';                      // a CHMODed to 777 directory
// $forum_back[3]='';                      // a CHMODed to 777 directory (backup directory for this forum)
// $forum_desc[3]='description...';        // a few words to describe this forum

if(isset($f)&&strlen($f)<3&&strlen($f)>0){$f=(int)$f;
if(!isset($forum_data[$f])||!is_dir($forum_data[$f])){$f=0;}}
else{$f=0;}

$data=$forum_data[$f];$log="$data/gshow";
$nav1temp=0;$nav2temp=0;$nav3temp=0;$row_bg='a';
set_magic_quotes_runtime(0);$current_time=time();

if($color_changing==1){$css_file=(int)$default_css;if(!isset($ccss)&&!isset($gcss)){setcookie('ccss',$css_file,time()+86400*100,'/');$gcss=$css_file;}elseif(isset($gcss)){$gcss=(int)$gcss;setcookie('ccss',$gcss,time()+86400*100,'/');}else{$gcss=$ccss;}}
if(!isset($user_time)){$user_time=0;}else{$user_time=(int)$user_time;}
$show_time=gmdate('H:i',$current_time+$user_time*3600);

function time_to_run(){$time=microtime();$time=explode(" ",$time);return $time[1]+$time[0];}$start_time=time_to_run();
function disk_space(){$s=true;if(function_exists('disk_free_space')){$a=disk_free_space("/");if(is_int($a)&&$a<204800){$s=false;}}return $s;}
function redirect($n){die("<script type=\"text/javascript\">window.location='$n';</script><title>...</title></head><body></body></html>");}
function file_allowed($n){global $f,$data;$ok=0;$handle=opendir($data);while($entry=readdir($handle)){if($entry==$n&&substr($entry,0,1)=='2'){$ok=1;break;}}closedir($handle);if($ok==0){redirect("index.php");}}
function switch_row_bg(){global $row_bg;if($row_bg=='a'){$row_bg='b';}else{$row_bg='a';}}
function open_file($n){$fd=fopen($n,"r") or die('...');$fs=fread($fd,filesize($n));fclose($fd);return $fs;}
function save_file($m,$n,$o){if(disk_space()){$n=trim($n);if($n==''){$n=' ';}$n=str_replace("\n\n","\n",$n);$p=0;do{$fd=fopen($m,"w+") or die($o);$fout=fwrite($fd,$n);fclose($fd);$p++;}while(filesize($m)<5&&$p<5);}}
function clean_entry($w){$w=stripslashes($w);$w=trim($w);$w=str_replace("<","&lt;",$w);$w=str_replace(">","&gt;",$w);$w=str_replace(":|:","",$w);$w=str_replace("\r\n","[br]",$w);$w=str_replace("\r","",$w);$w=str_replace("\n","",$w);return $w;}
function set_navbar($b,$o){global $nav1temp,$nav2temp,$nav3temp,$topics_per_page;$nav1temp=$topics_per_page*$b;$nav2temp=$nav1temp-$topics_per_page+$o;$nav3temp=$nav1temp-($topics_per_page-1);}
function remove_bad_words($w){global $bad_words;for($i=0;$i<count($bad_words);$i++){$w=eregi_replace($bad_words[$i],'***',$w);}return $w;}
function time_offset($s){global $time_format,$user_time;return gmdate($time_format,$s+$user_time*3600);}

if(!is_writeable($members_file)){save_file($members_file,'<?php die();?>',"<b>$members_file</b> is not writeable!<br /><small>CHMOD <b>$members_file</b> to 777 by an FTP program.</small>");}
if(!is_writeable($log)){save_file($log,'',"<b>/$data</b> or a file in this directory is <b>not</b> writeable!<br /><small>CHMOD <b>/$data</b> and all files inside to 777 by an FTP program.</small>");}
if(filesize($log)<9&&$auto_repair==1){include "incl/repair.inc";}

if(function_exists('ob_gzhandler')&&$gzip==1){ob_start("ob_gzhandler");}

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
?>