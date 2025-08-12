<?php
require_once("config.php");

if ( !file_exists($config["signguestbook"]) )
{
   die( "The file ".$config["signguestbookl"]." does not exists." );
}

if ( !file_exists($config["guestbook"]) )
{
   die( "The file ".$config["guestbook"]." does not exists." );
}

if ( !file_exists($config["guestbooksignerror"]) )
{
   die( "The file ".$config["guestbooksignerror"]." does not exists." );
}

if ( !file_exists($config["guestbookthankyou"]) )
{
   die( "The file ".$config["guestbookthanksyou"]." does not exists." );
}

if ( !file_exists($config["guestbookpost"]) )
{
   die( "The file ".$config["guestbookpost"]." does not exists." );
}

$signlink = $_SERVER['PHP_SELF']."?action=sign";

if ( (!isset( $_GET['action'] )) || (empty( $_GET['action'] )) || ($_GET['action'] === "veiw" ))
{
   $posttoreplace = array(
   "/{emailorurl}/",
   "/{name}/",
   "/{comments}/",
   "/{date}/",
   );
   $fp = fopen( $config["guestbookpost"], "r" );
   $posttpl = fread( $fp, filesize( $config["guestbook"] ) );
   fclose( $fp );
   if( !isset($_GET['num']) || !is_numeric($_GET['num']) )
   {
       $start = 0;
       $end = $config["numpostsperpage"];
   }
   else
   {
      $start = $_GET['num'] * $config["numpostsperpage"];
      $end = $start + $config["numpostsperpage"];
   }
   $title = "Guestbook";
   if( $config["newestfirst"] === "yes" )
   {
      $order = " ORDER BY `ID` DESC ";
   }
   else
   {
      $order = " ORDER BY `ID` ASC ";
   }
   $query = mysql_query( "SELECT * FROM ".$config["mysqlguestbooktable"]."".$order."LIMIT ".$start.",".$end."" );
   $isresults = FALSE;
   $i = 0;
   $posts = "";
   while( $post = mysql_fetch_array( $query, MYSQL_ASSOC ) )
   {
      if( strstr ( $post["EMAILORURL"], "@" ) )
      {
          $emailorurl = "mailto:".stripslashes( $post["EMAILORURL"] );
      }
      else
      {
         if( preg_match( "/http:\/\//i", $post["EMAILORURL"] ) )
         {
             $emailorurl = stripslashes( $post["EMAILORURL"] );
         }
         else
         {
             $emailorurl = "http://".stripslashes( $post["EMAILORURL"] );
         }
      }
      $comments = preg_replace($smiles, $rsmiles, stripslashes( $post["COMMENTS"] ));
      $name = stripslashes( $post["NAME"] );
      $postreplace = array(
      $emailorurl,
      $name,
      $comments,
      $post["DATE"]
      );
      $posts .= preg_replace($posttoreplace, $postreplace, $posttpl);
      $i++;
   }
   $text .= "<br /><br />";
   if( $i >= $config["numpostsperpage"] )
   {
      $isresults = TRUE;
   }
   if( $start >= $config["numpostsperpage"] )
   {
      $prev = ($start / $config["numpostsperpage"]) - 1;
      $posts .= "<a href=\"".$_SERVER['PHP_SELF']."?num=".$prev."\">Prev.</a> ";
   }
   if( $isresults )
   {
      $next = ($start / $config["numpostsperpage"]) + 1;
      $posts .= " <a href=\"".$_SERVER['PHP_SELF']."?num=".$next."\">Next</a>\n";
   }
   $fp = fopen( $config["guestbook"], "r" );
   $tpl = fread( $fp, filesize( $config["guestbook"] ) );
   fclose( $fp );
   $toreplace = array(
   "/{title}/",
   "/{signlink}/",
   "/{posts}/",
   );
   $replace = array(
   $title,
   $signlink,
   $posts,
   );
   $tpl = preg_replace($toreplace, $replace, $tpl);
   echo "".$tpl."";
}
elseif( $_GET['action'] === "sign" )
{
   $fp = fopen( $config["signguestbook"], "r" );
   $tpl = fread( $fp, filesize( $config["signguestbook"] ) );
   fclose( $fp );
   $toreplace = array(
   "/{wheresend}/",
   "/{title}/"
   );
   $replace = array(
   $_SERVER['PHP_SELF']."?action=dosign",
   "Sign our guestbook"
   );
   $tpl = preg_replace($toreplace, $replace, $tpl);
   echo "".$tpl."";
}
elseif( $_GET['action'] === "dosign" )
{
   if( (!isset($_POST['name'])) || (rtrim($_POST['name']) == "") )
   {
      $error = " Sorry you need to enter a name.";
      $fp = fopen( $config["guestbooksignerror"], "r" );
      $tpl = fread( $fp, filesize( $config["guestbooksignerror"] ) );
      fclose( $fp );
   }
   elseif( (!isset($_POST['comments'])) || (rtrim($_POST['comments']) == "") )
   {
      $error = " Sorry you need to enter some comments.";
      $fp = fopen( $config["guestbooksignerror"], "r" );
      $tpl = fread( $fp, filesize( $config["guestbooksignerror"] ) );
      fclose( $fp );
   }
   else
   {
      $checkforban = mysql_query( "SELECT * FROM `".$config["mysqlbantable"]."` WHERE `ip` = '".make_query_safe( getenv('REMOTE_ADDR') )."'" ) or die ( "ERROR: Cannot query database" );
      if ( mysql_num_rows ( $checkforban ) > 0 )
      {
         $error = " Sorry your IP has been baned from posting in this guestbook.";
         $fp = fopen( $config["guestbooksignerror"], "r" );
         $tpl = fread( $fp, filesize( $config["guestbooksignerror"] ) );
         fclose( $fp );
      }
      else
      {
         $word = array();
         $wordreplacement = array();
         $banedwords = mysql_query( "SELECT `WORD`, `REPLACEMENT` FROM `".$config["mysqlbantable"]."` WHERE `WORD` != ''" ) or die ( "ERROR: Cannot query database" );
         while ( $banned = mysql_fetch_assoc( $banedwords ) )
         {
            $word[] = "/".preg_quote($banned['WORD'], '/')."/i";
            $wordreplacement[] = $banned['REPLACEMENT'];
         }
         $comments = nl2br(htmlspecialchars(preg_replace($word, $wordreplacement, $_POST['comments'])));
         $emailorurl = htmlspecialchars(preg_replace($word, $wordreplacement, $_POST['emailorurl']));
         $name = htmlspecialchars(preg_replace($word, $wordreplacement, $_POST['name']));
         $error = "";
         mysql_query( "INSERT INTO `".$config["mysqlguestbooktable"]."` ( `ID` , `NAME` , `EMAILORURL` , `IP` , `COMMENTS` , `DATE` ) VALUES ( '', '".make_query_safe( $name )."', '".make_query_safe( $emailorurl )."', '".make_query_safe( getenv('REMOTE_ADDR') )."', '".make_query_safe( $comments )."', NOW( ) )" ) or die ( "ERROR: cannot insert into database." );
         $fp = fopen( $config["guestbookthankyou"], "r" );
         $tpl = fread( $fp, filesize( $config["guestbookthankyou"] ) );
         fclose( $fp );
      }
   }
   $toreplace = array(
   "/{backlink}/",
   "/{message}/"
   );
   $replace = array(
   $_SERVER['PHP_SELF']."?action=veiw",
   $error
   );
   $tpl = preg_replace($toreplace, $replace, $tpl);
   echo "".$tpl."";
}
?>