<?

define('IN_PHPBB', true); // to ensure your script works ! //
$phpbb_root_path = './';
include_once($phpbb_root_path . 'extension.inc');
include_once($phpbb_root_path . 'common.php'); 

$table_prefix = "phpbb_" ;
$encoding = "utf-8";

//$userdata = session_pagestart($user_ip, PAGE_INDEX);
//init_userprefs($userdata);


  if  (isset($_SERVER['PHP_SELF']) && isset($_SERVER['HTTP_HOST'])) {
   $me = $_SERVER['PHP_SELF'];
   $path_pieces = explode("/", $me);
   $trim1= array_pop($path_pieces);
   $trim2 = array_pop($path_pieces);
   $pathweb = implode("/", $path_pieces);
   $forum_url = "http://".$_SERVER['HTTP_HOST'].$pathweb;
   }
elseif (isset($_SERVER['PHP_SELF']) && isset($_SERVER['SERVER_NAME'])) {
   $me = $_SERVER['PHP_SELF'];
   $path_pieces = explode("/", $me);
   $trim1= array_pop($path_pieces);
   $trim2 = array_pop($path_pieces);
   $pathweb = implode("/", $path_pieces);
   $forum_url = 'http://'.$_SERVER['SERVER_NAME'].$pathweb;
   }

header("Content-type: application/xml");
  echo "<?xml version=\"1.0\" encoding=\"".$encoding."\"?".">";

?>
<!-- generator="myWebland BBrss 1.0" -->
<rss version="0.92">
  <channel>
     <title><? echo "myWebland.com - Source of PHP Scripts"  ?></title>
    <link><?   if (isset($_SERVER['HTTP_HOST'])) { echo "http://".$_SERVER['HTTP_HOST']; }
               elseif (isset($_SERVER['SERVER_NAME'])) { echo "http://".$_SERVER['SERVER_NAME']; }
    ?></link>
    <description><? echo $blog_desc ?></description>
    <docs>http://backend.userland.com/rss092</docs>
    <?

$sqltopic = "SELECT  DISTINCT topic_id
FROM  `phpbb_posts` 
ORDER  BY post_id DESC 
LIMIT 0 , 8";


$result = $db->sql_query($sqltopic) ;

//$row = $db->sql_numrows($result) ;

if ( !($result = $db->sql_query($sqltopic)) )
    {
        message_die(GENERAL_ERROR, 'Could not obtain forums information', '', __LINE__, __FILE__, $sql);
    }

while ($unique_topics = $db->sql_fetchrow($result))
{
$topic_id = $unique_topics['topic_id'] ;
if ( $n % 2 ) { $alt_clr =" class=\"whitebg\""; } else { $alt_clr = " class=\"greybg\""; }

$sqlforum = "SELECT ".$table_prefix."topics.topic_title, ".$table_prefix."forums.forum_name,  ".$table_prefix."users.username, ".$table_prefix."posts.topic_id,
".$table_prefix."posts.post_id , ".$table_prefix."topics.topic_views,  ".$table_prefix."users.user_id, ".$table_prefix."forums.forum_id
FROM ".$table_prefix."forums, ".$table_prefix."users, ".$table_prefix."topics, ".$table_prefix."posts
WHERE 
".$table_prefix."posts.topic_id =  '$topic_id'
AND
".$table_prefix."users.user_id = ".$table_prefix."posts.poster_id
AND 
".$table_prefix."topics.topic_id =  ".$table_prefix."posts.topic_id
AND ".$table_prefix."forums.forum_id = ".$table_prefix."posts.forum_id
order by ".$table_prefix."posts.post_time desc limit 0, 1";

$result1 = $db->sql_query($sqlforum) ;
$forum_topics = $db->sql_fetchrow($result1);

    ?>
    <item>
      <title><? echo $forum_topics['topic_title']  ?></title>
      <link><? echo  $forum_url."/showtopic.php?t=".$forum_topics['topic_id'] ?></link>
    </item>
    <? } ?>
  </channel>
</rss>