<?php
  ini_set('short_open_tag',"1");
  require_once("inc/common.php");
  require_once("inc/parser.php");
  require_once("inc/feedcreator.class.php");
  require_once("inc/auth.php");

  //set auth header for login
  if($_REQUEST['login'] && !isset($_SERVER['PHP_AUTH_USER'])){
    header('WWW-Authenticate: Basic realm="'.$conf['title'].'"');
    header('HTTP/1.0 401 Unauthorized');
    auth_logoff();
  }


  $num  = $_REQUEST['num'];
  $type = $_REQUEST['type'];
  $mode = $_REQUEST['mode'];
  $ns   = $_REQUEST['ns'];

  switch ($type){
    case 'rss':
       $type = 'RSS0.9';
       break;
    case 'rss2':
       $type = 'RSS2.0';
       break;
    case 'atom':
       $type = 'ATOM0.3';
       break;
    default:
       $type = 'RSS1.0';
  }

  //some defaults for the feed
  $CACHEGROUP = 'feed';
  $conf['typography'] = false;
  $conf['canonical']  = true;
  $parser['toc']      = false;

  $rss = new UniversalFeedCreator();
  $rss->title = $conf['title'];
  $rss->link  = wl();
  $rss->syndicationURL = getBaseURL().'/feed.php';
  $rss->cssStyleSheet = getBaseURL().'/feed.css';

  if($mode == 'list'){
    rssListNamespace($rss,$ns);
  }else{
    rssRecentChanges($rss,$num);
  }

  header("Content-Type: application/xml");
  print $rss->createFeed($type);



/* some functions */

function rssRecentChanges(&$rss,$num){

  $recents = getRecents($num);
  foreach(array_keys($recents) as $id){
    $desc = cleanDesc(parsedWiki($id));
    if(!empty($recents[$id]['sum'])){
      $desc = '['.strip_tags($recents[$id]['sum']).'] '.$desc;
    }
    $item = new FeedItem();
    $item->title       = $id;
    $item->link        = wl($id);
    $item->description = $desc;
    $item->date        = date('r',$recents[$id]['date']);
    if(strpos($id,':')!==false){
      $item->category    = substr($id,0,strrpos($id,':'));
    }
    if($recents[$id]['user']){
      $item->author = $recents[$id]['user'].'@';
    }else{
      $item->author = 'anonymous@';
    }
    $item->author  .= $recents[$id]['ip'];
    
    $rss->addItem($item);
  }
}
  
function rssListNamespace(&$rss,$ns){
  require_once("inc/search.php");
  global $conf;

  $ns=':'.cleanID($ns);
  $ns=str_replace(':','/',$ns);

  $data = array();
  sort($data);
  search($data,$conf['datadir'],'search_list','',$ns);
  foreach($data as $row){
    $id = $row['id'];
    $desc = cleanDesc(parsedWiki($id));
    $item = new FeedItem();
    $item->title       = $id;
    $item->link        = wl($id);
    $item->description = $desc;
    $item->date        = date('r',filemtime(wikiFN($id)));
    $rss->addItem($item);
  }  
}

function cleanDesc($desc){
  //remove TOC
  $desc = strip_tags($desc);
  $desc = preg_replace('/[\n\r\t]/',' ',$desc);
  $desc = preg_replace('/  /',' ',$desc);
  $desc = substr($desc,0,250);
  $desc = $desc.'...';
  return $desc;
}
?>
