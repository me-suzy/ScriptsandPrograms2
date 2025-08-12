<?php
ini_set('short_open_tag',"1");
require_once("conf/dokuwiki.php");
require_once("inc/common.php");
require_once("lang/en/lang.php");
require_once("lang/".$conf['lang']."/lang.php");
setCorrectLocale();
require_once("inc/html.php");
require_once("inc/search.php");
require_once("inc/format.php");
require_once("inc/auth.php");

header('Content-Type: text/html; charset='.$lang['encoding']);

$NS = $_REQUEST['ns'];
$NS = cleanID($NS);

if(auth_quickaclcheck("$NS:*") >= AUTH_UPLOAD){
  $uploadok = true;
  //create the given namespace (just for beautification)
  $mdir = $conf['mediadir'].'/'.str_replace(':','/',$NS);
  io_mkdir_p($mdir);
}else{
  $uploadok = false;
}

if($_FILES['upload']['tmp_name'] && $uploadok){
  media_upload($NS);
}

//start output
html_head();
?>
<body>
  <?html_msgarea()?>
  <h1><?=$lang['mediaselect']?></h1>

  <div class="mediaselect">
    <div class="mediaselect-left">
      <?=media_html_namespaces()?>
    </div>
    <div class="mediaselect-right">
      <?
        print media_html_media($NS);
        if($uploadok){
          print media_html_uploadform($NS);
        }
      ?>
    </div>
  </div>

</body>
</html>
<?
/**********************************************/

function media_upload($NS){
  global $conf;
  global $lang;

  // get file
  $id   = $_POST['id'];
  $file = $_FILES['upload'];
  // get id
  if(empty($id)) $id = $file['name'];
  $id   = cleanID($NS.':'.$id);
  // get filename
  $fn   = str_replace(':','/',$id);
  $fn   = $conf['mediadir'].'/'.$fn;
  // prepare directory
  io_makeFileDir($fn);

  if(preg_match('/\.'.$conf['uploadtypes'].'$/i',$fn)){
    if (move_uploaded_file($file['tmp_name'], $fn)) {
      msg($lang['uploadsucc'],1);
      return true;
    }else{
      msg($lang['uploadfail'],-1);
    }
  }else{
    msg($lang['uploadwrong'],-1);
  }
  return false;
}

function media_html_uploadform($ns){
  global $lang;
?>
  <div class="uploadform">
  <form action="<?=$_SERVER['PHP_SELF']?>" name="upload" method="post" enctype="multipart/form-data">
  <?=$lang['txt_upload']?>:<br />
  <input type="file" name="upload" class="edit" onchange="suggestWikiname();" />
  <input type="hidden" name="ns" value="<?=htmlspecialchars($ns)?>" /><br />
  <?=$lang['txt_filename']?>:<br />
  <input type="text" name="id" class="edit" />
  <input type="submit" class="button" value="<?=$lang['btn_upload']?>" accesskey="s" />
  </form>
  </div>
<?
}

function media_html_media($ns){
  global $conf;
  global $lang;
  $dir = str_replace(':','/',$ns);

  print '<b>'.$lang['mediafiles'].'</b>';
  print ' <code>'.$ns.':</code>';

  $data = array();
  search($data,$conf['mediadir'],'search_media',array(),$dir);

  if(!count($data)){
    print '<div style="text-align:center; margin:2em;">';
    print $lang['nothingfound'];
    print '</div>';
    return;
  }

  print '<ul>';
  foreach($data as $item){
    print '<li>';
    print '<a href="javascript:mediaSelect(\''.$item['id'].'\')">';
    print $item['file'];
    print '</a>';
    if($item['isimg']){
      print ' ('.$item['info'][0].'&#215;'.$item['info'][1];
      print ' '.filesize_h($item['size']).')<br />';

      # build thumbnail
      $link=array();
      $link['name']=$item['id'];
      if($item['info'][0]>120) $link['name'] .= '?120';
      $link = format_link_media($link);
      print $link['name'];

    }else{
      print ' ('.filesize_h($item['size']).')';
    }
    print '</li>';
  }
  print '</ul>';
}

function media_html_namespaces(){
  global $conf;
  global $lang;

  $data = array();
  #add default namespace
  print '<b><a href="'.getBaseURL().'media.php?ns=">'.$lang['namespaces'].'</a></b>';
  search($data,$conf['mediadir'],'search_namespaces',array());
  print html_buildlist($data,'idx',media_html_list_namespaces);
}

function media_html_list_namespaces($item){
  $ret  = '';
  $ret .= '<a href="'.getBaseURL().'media.php?ns='.$item['id'].'" class="idx_dir">';
  $ret .= $item['id'];
  $ret .= '</a>';
  return $ret;
}

?>
