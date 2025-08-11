<?php
/**************************************************************************
    FILENAME        :   functions.php
    PURPOSE OF FILE :   General functions used through out CMScout
    LAST UPDATED    :   22 November 2005
    COPYRIGHT       :   © 2005 CMScout Group
    WWW             :   www.cmscout.za.org
    LICENSE         :   GPL vs2.0
    
    

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
**************************************************************************/
?>
<?php
function error_message ($description)
{
    $description = addslashes($description);
    echo "<script> alert('Error: $description'); history.go(-1);</script>";
}//error_message

function show_message ($message)
{
    echo "<script> alert('$message'); </script>";
    return;
}

function show_message_back ($message)
{
    echo "<script> alert('$message'); history.go(-1);</script>";
    return;
}

function die_horrible_death($error)
{
    die("A critical error has occured with CMScout.<br />The error is a follows: $error<br />Please contact the administrator as soon as possible.");
    return;
}

function read_config() {
	global $data;
	$query = $data->select_query("config");
	$configdata = array();
	$config = $data->fetch_array($query);
	do{
		$configdata[$config['name']] = $config['value'];
	}while ($config = $data->fetch_array($query));
	return $configdata;
}//read_config

function location($location, $uid) {
 global $data, $check, $timestamp;
 if ($check['uname']) 
 {
	$query = $data->update_query("onlineusers", "location = '$location', locchange=$timestamp", "uid='$uid'", "", "", false);
 }
 return false;
}//location

function change_theme_dir($themeid = false) 
{
	global $config, $tpl, $data, $check;
	
    if (!$themeid) 
    {
        $themeid = isset($check['theme_id']) ? $check['theme_id'] : 0;
    }
    
	if (!isset($themeid) || ($themeid == 0) || (empty($themeid))) 
    {
        $themeid = $config['defaulttheme'];
	} 
    
    $theme_select = $data->select_query("themes", "WHERE id='$themeid'");
    $theme = $data->fetch_array($theme_select);
    if (!$theme) 
    {
        $themeid = $config['defaulttheme'];
        $theme_select = $data->select_query("themes", "WHERE id='$themeid'");
        $theme = $data->fetch_array($theme_select);
        if (!$theme)
        {
            $theme_select = $data->select_query("themes");
            $theme = $data->fetch_array($theme_select);
            if(!$theme)
            {
                die_horrible_death("There are no templates installed");
            }            
        }
    } 
    
    $configfile = $theme['configfile'];
    $themedir = $theme['dir'];
    if (file_exists("templates/$themedir/index.tpl"))
    {
	    $tpl->template_dir = "templates/$themedir/";
        $templatedir = $tpl->template_dir;
        include("templates/$themedir/$configfile");
        return $templateinfo;
    }
    else
    {
        $themeid = $config['defaulttheme'];
        
        $theme_select = $data->select_query("themes", "WHERE id='$themeid'");
        $theme = $data->fetch_array($theme_select);
        if (!$theme) 
        {
            $themeid = $config['defaulttheme'];
            $theme_select = $data->select_query("themes", "WHERE id='$themeid'");
            $theme = $data->fetch_array($theme_select);
            if (!$theme)
            {
                $theme_select = $data->select_query("themes");
                $theme = $data->fetch_array($theme_select);
                if(!$theme)
                {
                    die_horrible_death("There are no templates installed");
                }            
            }
        } 
        
        $configfile = $theme['configfile'];
        $themedir = $theme['dir'];
        $tpl->template_dir = "templates/$themedir/";
        $templatedir = $tpl->template_dir;
        include("templates/$themedir/$configfile");
        return $templateinfo;
    }
    
} //change_theme_dir

function get_theme_css() {
	global $config, $tpl, $data, $currtheme;
	$theme_select = $data->select_query("themes", "WHERE id='$currtheme'");
	$theme = $data->fetch_array($theme_select);
	return $tpl->template_dir . $theme['cssfile'];
} //get_theme_css

function get_spec($page) {
    global $data;
    $pages = $data->select_query("static_content", "WHERE name='$page'");
    $page =  $data->fetch_array($pages);
    return $page['content'];
} //get_spec

function get_patrol_page($page, $patrol) {
    global $data, $check;
    $pages = $data->select_query("patrolcontent", "WHERE name='$page' AND patrol='$patrol'");
    $page =  $data->fetch_array($pages);
    if ($page['public'] == 0 && $data->num_rows($pages) > 0)
    {
        if($check['team'] == $page['patrol'] || $check['level'] == 0 || $check['level'] == 1 && $check['level'] == 2)
        {
            return $page['content'];
        }
        else
        {
            return "$%$#PageOFF%$^$%";
        }
    }
    elseif ($page['public'] == 1)
    {
        return $page['content'];
    }
    else
    {
        return false;
    }
} //get_patrol_page

function get_sub_page($page, $site) {
    global $data, $check;
    $pages = $data->select_query("subcontent", "WHERE name='$page' AND site='$site'");
    $page =  $data->fetch_array($pages);
    
    return $page['content'];
} //get_sub_page

function get_temp($pagename, $pagenum) {
	global $data;
	if ((!isset($pagename) || !$pagename)) {error_message("Getting Template", "No pagename set");}
	if ($pagenum == 0) {$pagenum = 1;}
	$get = $data->select_query("pagetracking", "WHERE pagename='$pagename'");
	$temp = $data->fetch_array($get);
	if (!isset($temp['id']) || !$temp['id']) {error_message("Getting Template", "No page by the name $pagename found");}
	if ($pagenum > $temp['numpages']) {$pagenum = $temp['numpages'];}
	$pageid = $temp['id'];
	$pages = $data->select_query("pagecontent", "WHERE pageid='$pageid' AND pagenum='$pagenum'");
	$page =  $data->fetch_array($pages);
	if (!isset($page['content']) || !$page['content']) {error_message("Getting Template", "There are no pages for $pagename");}
	return $page['content'];
} //get_temp


function uploadpic($file, $width, $height, $savejpeg = false, $path=false)
{
    global $config;

    list($width_orig, $height_orig) = getimagesize($file['tmp_name']);

    switch ($file['type'])
    {
        case 'image/gif':
            $image = imagecreatefromgif($file['tmp_name']);
            $type = "gif";
            break;
        case 'image/jpeg': case 'image/pjpeg':
            $image = imagecreatefromjpeg($file['tmp_name']);
            $type = "jpeg";
            break;
        case 'image/png':
            $image = imagecreatefrompng($file['tmp_name']);
            $type = "png";
            break;
    }  
    
    if($width_orig > $width || $height_orig > $height)
    {    
        if ($width && ($width_orig < $height_orig)) 
        {
            $width = ($height / $height_orig) * $width_orig;
        }
        else 
        {
            $height = ($width / $width_orig) * $height_orig;
        }
    }
    else
    {
        $height = $height_orig;
        $width = $width_orig;
    }
    
    if ($type != "gif")
    {
        $image_p = imagecreatetruecolor($width, $height);
    }
    else
    {
        $image_p = imagecreate($width, $height);
    }
    
    imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
    
    if ($width_orig > $width || $height_orig > $height)
    {
        show_message("Image resized. Old size: $width_orig x $height_orig New Size: $width x $height");
    }
    
    if (!$savejpeg)
    {
        switch ($type)
        {
            case 'gif':
                $output['filename'] = md5(rand()%time() . $file['name']).'.gif'; 
                if ($path == false)
                {
                    imagegif($image_p, $config["photopath"]."/".$output['filename']);
                }
                else
                {
                    imagegif($image_p, $path."/".$output['filename']);
                }
                break;
            case 'jpeg':
                $output['filename'] = md5(rand()%time() . $file['name']).'.jpeg'; 
                if ($path == false)
                {
                    imagejpeg($image_p, $config["photopath"]."/".$output['filename']);
                }
                else
                {
                    imagejpeg($image_p, $path."/".$output['filename']);
                }
                break;
            case 'png':
                imagealphablending($image_p, FALSE);
                imagesavealpha($image_p, true);
                $output['filename'] = md5(rand()%time() . $file['name']).'.png'; 
                if ($path == false)
                {
                    imagepng($image_p, $config["photopath"]."/".$output['filename']);
                }
                else
                {
                    imagepng($image_p, $path."/".$output['filename']);
                }
                break;
        }
    }
    else
    {
        $output['filename'] = md5(rand()%time() . $file['name']).'.jpeg'; 
        if ($path == false)
        {
            imagejpeg($image_p, $config["photopath"]."/".$output['filename']);
        }
        else
        {
            imagejpeg($image_p, $path."/".$output['filename']);
        }
    }
    imagedestroy($image);
    imagedestroy($image_p);
    
    return $output;
}

function safesql($theValue, $theType, $striptags = true, $theDefinedValue = "", $theNotDefinedValue = "") 
{
    $theValue = $striptags ? strip_tags($theValue) : $theValue;
    $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}

function search_highlight($text, $terms_rx)
{

    $start = '(^|<(?:.*?)>)';
    $end   = '($|<(?:.*?)>)';
    return preg_replace(
        "/$start(.*?)$end/se",
        "StripSlashes('\\1').".
            "search_highlight_inner(StripSlashes('\\2'), \$terms_rx).".
            "StripSlashes('\\3')",
        $text
    );
}

function search_highlight_inner($text, $terms_rx)
{

    $colors = search_get_highlight_colors();
    foreach($terms_rx as $term_rx)
    {
        $color = array_shift($colors);

        $text = preg_replace(
                "/($term_rx)/ise",
                "search_highlight_do(StripSlashes('\\1'), \$color)", 
                $text
            );
    }

    return $text;
}

function search_get_highlight_colors()
{

    return array(
        array('#ffff66','#000000'),
        array('#A0FFFF','#000000'),
        array('#99ff99','#000000'),
        array('#ff9999','#000000'),
        array('#ff66ff','#000000'),
        array('#880000','#ffffff'),
        array('#00aa00','#ffffff'),
        array('#886800','#ffffff'),
        array('#004699','#ffffff'),
        array('#990099','#ffffff'),
    );
}

function search_highlight_do($fragment, $color)
{

    return "<span style=\"background-color: $color[0]; ".
        "color: $color[1]; font-weight: bold;\">".
        "$fragment</span>";
}

function search_pretty_terms_highlighted($terms_html)
{

    $colors = search_get_highlight_colors();
    $temp = array();

    foreach($terms_html as $term_html){
        $color = array_shift($colors);
        $temp[] = search_highlight_do($term_html, $color);
    }

    return search_pretty_terms($temp);
}

function search_split_terms($terms)
{

    $terms = preg_replace("/\"(.*?)\"/e", "search_transform_term('\$1')", $terms);
    $terms = preg_split("/\s+|,/", $terms);

    $out = array();

    foreach($terms as $term){

        $term = preg_replace("/\{WHITESPACE-([0-9]+)\}/e", "chr(\$1)", $term);
        $term = preg_replace("/\{COMMA\}/", ",", $term);

        $out[] = $term;
    }

    return $out;
}

function search_transform_term($term)
{
    $term = preg_replace("/(\s)/e", "'{WHITESPACE-'.ord('\$1').'}'", $term);
    $term = preg_replace("/,/", "{COMMA}", $term);
    return $term;
}

function search_escape_rlike($string)
{
    return preg_replace("/([.\[\]*^\$])/", '\\\$1', $string);
}

function search_db_escape_terms($terms)
{
    $out = array();
    foreach($terms as $term){
        $out[] = '[[:<:]]'.AddSlashes(search_escape_rlike($term)).'[[:>:]]';
    }
    return $out;
}

function search_perform($terms, $table, $field, $type, $extra=false)
{
    global $data;
    $terms = search_split_terms($terms);
    $terms_db = search_db_escape_terms($terms);
    $terms_rx = search_rx_escape_terms($terms);

    $parts = array();
    foreach($terms_db as $term_db){
        $parts[] = "$field RLIKE '$term_db'";
    }
    $parts = implode(" $type ", $parts);
    
    if ($extra)
    {
        $parts .= " AND $extra";
    }
    $rows = array();

    $result = $data->select_query($table, "WHERE $parts");
    while($row = $data->fetch_array($result)){

        $row[score] = 0;

        foreach($terms_rx as $term_rx){
            $row[score] += preg_match_all("/$term_rx/i", $row[$field], $null);
        }

        $rows[] = $row;
    }

    uasort($rows, 'search_sort_results');

    return $rows;
}

function search_rx_escape_terms($terms)
{
    $out = array();
    foreach($terms as $term){
        $out[] = '\b'.preg_quote($term, '/').'\b';
    }
    return $out;
}

function search_sort_results($a, $b)
{

    $ax = $a[score];
    $bx = $b[score];

    if ($ax == $bx){ return 0; }
    return ($ax > $bx) ? -1 : 1;
}

function search_html_escape_terms($terms)
{
    $out = array();

    foreach($terms as $term){
        if (preg_match("/\s|,/", $term)){
            $out[] = '"'.HtmlSpecialChars($term).'"';
        }else{
            $out[] = HtmlSpecialChars($term);
        }
    }

    return $out;	
}

function search_pretty_terms($terms_html)
{

    if (count($terms_html) == 1){
        return array_pop($terms_html);
    }

    $last = array_pop($terms_html);

    return implode(', ', $terms_html)." and $last";
}


function getoffset($id)
{
    global $data;
    if (isset($id))
    {
        $sql = $data->select_query("timezones", "WHERE id=$id");
        
        $d = $data->fetch_array($sql);
        
        return ($d['offset'] * 3600);
    }
    else
    {
        return 0;
    }
}

function getuseroffset($uname)
{
    global $data, $config;
    $uname = safesql($uname, "text");
    $sql = $data->select_query("authuser", "WHERE uname=$uname");
    $d = $data->fetch_array($sql);
    return getoffset($d['timezone']) - getoffset($config['zone']);
}

function is_valid_email_address($email){

    $qtext = '[^\\x0d\\x22\\x5c\\x80-\\xff]';

    $dtext = '[^\\x0d\\x5b-\\x5d\\x80-\\xff]';

    $atom = '[^\\x00-\\x20\\x22\\x2e\\x2c\\x3a\\x3b\\x40\\x50'.
        '\\x51\\x5b-\\x5d\\x74\\x76\\x7f-\\xff]+';

    $quoted_pair = '\\x5c\\x00-\\x7f';

    $domain_literal = "\\x5b($dtext|$quoted_pair)*\\x5d";

    $quoted_string = "\\x22($qtext|$quoted_pair)*\\x22";

    $domain_ref = $atom;

    $sub_domain = "($domain_ref|$domain_literal)";

    $word = "($atom|$quoted_string)";

    $domain = "$sub_domain(\\x2e$sub_domain)*";

    $local_part = "$word(\\x2e$word)*";

    $addr_spec = "$local_part\\x40$domain";

    return preg_match("!^$addr_spec$!", $email) ? 1 : 0;
}

function validdate($date)
{
    list($y, $m, $d) = explode('-', $date);
    list($yy, $mm, $dd) = explode('-', date('Y-m-d',strtotime($date)));
    
    if (($y == $yy) and ($m == $mm) and ($d == $dd))
    {
        return true;
    }
    else 
    {
        return false;
    }
}

function publish_mail($user, $itemname, $name)
{
    global $config;
    
    $email = $config['sitemail'];

    $subject = "[{$config['troopname']}] New item posted that requires publishing";
    
    $emess = "To {$config['troopname']} webmaster,
    
You are recieving this email because you indicated that any $itemname posted requires your approval. 
$user has posted a new (Or modified an existing) $itemname (Under the title $name) and you are required to review it and the publish or delete it.

Please visit the site as soon as possible to review the item.

From {$config['troopname']} website.";

    $headers .= "From: {$config['troopname']} Website <{$config['sitemail']}>\r\n";
    echo $emess;
    $mailsuc = @mail($email, $subject, $emess, $headers);

}

?>