<?php
/*************************
  Copyright (c) 2004-2005 TinyWebGallery
  written by Michael Dempfle

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.
  ********************************************
  TWG version: 1.3c
  $Date: 2005/11/15 09:02 $
**********************************************/
loadXMLFiles(urldecode($twg_album));

function loadXMLFiles($album_url)
{
    global $werte;
    global $kwerte;
    global $index;
    global $kindex;
    global $xmldir;

    $album_url = str_replace("/", "_", urldecode($album_url)); 
    // we replace he encoded version here as well - otherwise it has to be done twiche
    $album_dec = str_replace("/", "_", urldecode($album_url)); 
    // echo ("loadxml : " . $album_url);
    if ($album_url != false) {
        if (($_SESSION["actalbum"] != $album_url) || $werte == false || $werte == null) {
            // echo ("lade xml : " . urldecode($album_url));
            $xml_filename = $xmldir . "/" . $album_dec . "_image_text.xml";
            $xml_parser_handle = xml_parser_create();
            if (!file_exists($xml_filename)) {
                // we create an empty one
                $xml_file = fopen($xml_filename, 'w');
                $xml_dummy_string = "<?xml version='1.0'?>\n<beschreibung>\n<bild><name> </name><wert> </wert></bild>\n</beschreibung>";
                fputs($xml_file, $xml_dummy_string);
                fclose($xml_file);
            } 

            $xml_parser_handle = xml_parser_create();

            if (!($parse_handle = fopen($xml_filename, 'r'))) {
                die("FEHLER: Datei $xml_filename kann nicht erzeugt werden.");
            } 
            $xml_data = fread($parse_handle, filesize($xml_filename)); 
            // translation nneded für german umlaute &uuml; ... !!
            $e = array_flip (get_html_translation_table (HTML_ENTITIES));
            unset($e["&amp;"]);
            unset($e["&lt;"]);
            unset($e["&gt;"]);
            $xml_data = strtr($xml_data, $e);

            if (!xml_parse_into_struct($xml_parser_handle, $xml_data, $werte, $index)) {
                die(sprintf('XML error: %s at line %d (%s)',
                        xml_error_string(xml_get_error_code($xml_parser_handle)),
                        xml_get_current_line_number($xml_parser_handle), $xml_filename));
            } 

            xml_parser_free($xml_parser_handle); 
            // check if the right albumtexts are loaded !!
            $xml_filename = "./" . $xmldir . "/" . $album_dec . "_kommentar_text.xml";
            $xml_parser_handle = xml_parser_create();

            if (!file_exists($xml_filename)) {
                // we create an empty one
                $xml_file = fopen($xml_filename, 'w');
                $xml_dummy_string = "<?xml version='1.0'?>\n<beschreibung>\n<bild><name> </name><wert> </wert></bild>\n</beschreibung>";
                fputs($xml_file, $xml_dummy_string);
                fclose($xml_file);
            } 

            $xml_parser_handle = xml_parser_create();

            if (!($parse_handle = fopen($xml_filename, 'r'))) {
                die("FEHLER: Datei $xml_filename kann nicht erzeugt werden.");
            } 
            $xml_data = fread($parse_handle, filesize($xml_filename)); 
            // translation nneded für german umlaute &uuml; ... !!
            $e = array_flip (get_html_translation_table (HTML_ENTITIES));
            unset($e["&amp;"]);
            unset($e["&lt;"]);
            unset($e["&gt;"]);
            $xml_data = strtr($xml_data, $e);
            if (!xml_parse_into_struct($xml_parser_handle, $xml_data, $kwerte, $kindex)) {
                 die(sprintf('XML error: %s at line %d (%s)',
								        xml_error_string(xml_get_error_code($xml_parser_handle)),
                        xml_get_current_line_number($xml_parser_handle), $xml_filename));
            } 

            xml_parser_free($xml_parser_handle); 
            // echo ("loaded : " . $album_url);
            $_SESSION["actalbum"] = $album_url;
            $_SESSION["werte"] = $werte;
            $_SESSION["index"] = $index;
            $_SESSION["kwerte"] = $kwerte;
            $_SESSION["kindex"] = $kindex;
        } else {
            // load session variables
            $werte = $_SESSION["werte"];
            $index = $_SESSION["index"];
            $kwerte = $_SESSION["kwerte"];
            $kindex = $_SESSION["kindex"];
        } 
    } 
} 
// get beschreibung for images
function getBeschreibung($image, $werte, $index)
{
    global $autodetect_filenames_as_captions;
    global $autodetect_filenames_as_captions_number;

    $i = 0;
    foreach (((array)($index["NAME"])) as $band) {
        if (urldecode($werte[$band]["value"]) == urldecode($image)) { // encoding has to be done twice to get a match ! - some
            return urldecode($werte[$index["WERT"][$i]]["value"]);
        } else {
            $i = $i + 1;
        } 
    } 

    if (!$autodetect_filenames_as_captions) {
        return "";
    } 
    // we haven't found a valid name - therefore we return the filename without extension if we
    // have less than 4 numbers in it (no camera name)
    $image = urldecode($image);

    $name = substr($image, 0, strlen($image)-4);
    $countNum = 0;

    $result = count_chars($name, 0);

    for ($i = 0; $i < count($result); $i++) {
        if ($result[$i] != 0) {
            if (is_numeric(chr($i))) {
                $countNum += $result[$i]; 
                // echo "There were $result[$i] instance(s) of \"" , chr($i) , "\" in the string.\n";
            } 
        } 
    } 

    if ($countNum > $autodetect_filenames_as_captions_number) {
        return "";
    } else {
        // no html conversion - is done later !
        return $name;
    } 
} 
// get kommentare for images
function getKommentar($image, $twg_album, $kwerte, $kindex, $isiframe)
{
    global $login;
    global $install_dir;
    global $show_enter_comment_at_bottom;
    global $default_is_fullscreen;
    global $lang_comments;
    global $lang_height_comment;
    global $twg_standalone;
    global $show_comments_in_layer;
    global $show_number_of_comments;

    $kommentar = "";
    $i = 0;
    $hits = 0;

    $install_save = $install_dir;
    if ($isiframe) {
        $install_dir_com = "../" ;
        $install_dir = "../";
    } else {
        $install_dir_com = $install_dir;
    } 

    foreach ($kindex["NAME"] as $band) {
        if (isset($kwerte[$band]["value"])) {
            if (urldecode($kwerte[$band]["value"]) == $image) {
                $hits++;
                $line = urldecode($kwerte[$kindex["WERT"][$i]]["value"]);
                list ($datum, $name, $komment) = explode("=||=", $line);
                // fix for 1.2 !!
                $pos = strpos ($datum, ".");
								if ($pos === false) {
								   $datum = date("j.n.Y G:i", $datum);  
								}             
                $name = php_to_html_chars_all(restore_plus(urldecode(replace_plus($name)))); // fix for some server
                $komment = php_to_html_chars_all(restore_plus(urldecode(replace_plus($komment)))); // fix for some server
                $kommentar .= " <span class='twg_bold'>" . $name . "</span>  <span class='twg_kommentar_date'>(" . $datum . ")</span>";
                if ($login != 'FALSE') {
                    $kommentar .= " <a href='" . $_SERVER['PHP_SELF'] . "?twg_album=" . urlencode($twg_album) . "&amp;twg_show=" . urlencode($image) . "&amp;twg_delcomment=" . urlencode($line) . "'><img align='top' src='" . $install_dir_com . "buttons/close.gif' width='7' height='7'></a>";
                } 
                $kommentar .= "<br />" . $komment . "<br />&nbsp;<br />\n";
            } 
        } 
        $i++;
    } 

    $install_dir = $install_save; // not nice - change if time ! 
    if ($show_number_of_comments) {
        $com_counter = " (" . $hits . ")";
    } else {
        $com_counter = "";
    } 
    $headerkommentar = "";

    if (!$show_comments_in_layer) {
        if ($show_enter_comment_at_bottom && !$default_is_fullscreen) {
            $headerkommentar = "<div class='twg_underlineb'>" . $lang_comments . $com_counter . "<a onclick='twg_showSec(" . $lang_height_comment . ")' target='details' href='" . $install_dir_com . "i_frames/i_kommentar.php?twg_album=" . urlencode($twg_album) . "&amp;twg_show=" . urlencode($image) . $twg_standalone . "'>";
            $headerkommentar .= '<img alt="" width=5   src="' . $install_dir_com . 'buttons/1x1.gif" /><img alt=""   src="' . $install_dir_com . 'buttons/add.gif" />';
            $headerkommentar .= "</a></div>";
        } else {
            if ($kommentar <> "") {
                $headerkommentar = "<div class='twg_underlineb'>" . $lang_comments . $com_counter . "</div>";
            } 
        } 
    } 
    return sprintf("%10s", $hits) . $headerkommentar . $kommentar;
} 
// get kommentare for images
function getKommentarCount($image, $twg_album, $kwerte, $kindex)
{
    global $install_dir;
    $i = 0;
    $counter = 0;
    foreach ($kindex["NAME"] as $band) {
        if (isset($kwerte[$band]["value"])) {
            if (urldecode($kwerte[$band]["value"]) == urldecode($image)) {
                $counter++;
            } 
        } 
        $i++;
    } 
    return $counter;
} 
// every entry of the $werte is stored. Each time it is checked
// if we already have this value - if yes we replace - if no we
// add at the end
function saveBeschreibung($titel, $twg_album, $image, $werte, $index)
{
    global $xmldir;

    $isnew = true;
    $twg_album = str_replace("/", "_", urldecode($twg_album));
    $xml_filename = "./" . $xmldir . "/" . urldecode($twg_album) . "_image_text.xml";
    $xml_file = fopen($xml_filename, 'w');

    fputs($xml_file, "<?xml version='1.0'?><beschreibung>\n");

    $i = 0;
    foreach ($index["NAME"] as $band) {
        // $imageName = urldecode(urldecode($werte[$band]["value"]));
        $imageName = urldecode($werte[$band]["value"]);
        if (urlencode($imageName) == urlencode(urldecode($image))) {
            $oldtitel = urlencode($titel);
            $isnew = false;
        } else {
            $oldtitel = $werte[$index["WERT"][$i]]["value"];
        } 
        $i = $i + 1; 
        // $xml_string = "<bild><name>" . urlencode($imageName) . "</name><wert>" . $oldtitel . "</wert></bild>\n";
        $xml_string = "<bild><name>" . urlencode($imageName) . "</name><wert>" . $oldtitel . "</wert></bild>\n";
        fputs($xml_file, $xml_string);
    } 

    if ($isnew) {
        // $xml_string = "<bild><name>" . urlencode(urldecode($image)) . "</name><wert>" . urlencode($titel) . "</wert></bild>\n";
        $xml_string = "<bild><name>" . urlencode($image) . "</name><wert>" . urlencode($titel) . "</wert></bild>\n";
        fputs($xml_file, $xml_string);
    } 

    fputs($xml_file, "</beschreibung>");
    fclose($xml_file);
    clearstatcache(); 
    // we reload
    $_SESSION["actalbum"] = "_RELOAD_";
    loadXMLFiles(urldecode($twg_album));
} 
// every entry of the $kwerte is stored.
function saveKommentar($titel, $name, $twg_album, $image, $kwerte, $kindex, $image_orig)
{
    $twg_album = str_replace("/", "_", $twg_album); 
    // $e= array_flip (get_html_translation_table (HTML_ENTITIES));
    $e = get_html_translation_table (HTML_ENTITIES);
    $titel = strtr($titel, $e);
    $name = strtr($name, $e);

    global $xmldir;

    $now = time(); //  = date("j.n.Y G:i");
    $titel = $now . "=||=" . $name . "=||=" . $titel;
    $xml_filename = "./" . $xmldir . "/" . urldecode($twg_album) . "_kommentar_text.xml";
    $xml_file = fopen($xml_filename, 'w');

    fputs($xml_file, "<?xml version='1.0'?><beschreibung>\n"); 
    // new comments on top !!
    $xml_string = "<bild><name>" . urlencode($image) . "</name><wert>" . urlencode($titel) . "</wert></bild>\n";
    fputs($xml_file, $xml_string);

    $i = 0;
    foreach ($kindex["NAME"] as $band) {
        $imageName = $kwerte[$band]["value"];
        $oldtitel = $kwerte[$kindex["WERT"][$i]]["value"];
        $i = $i + 1;
        $xml_string = "<bild><name>" . $imageName . "</name><wert>" . $oldtitel . "</wert></bild>\n";
        fputs($xml_file, $xml_string);
    } 
    fputs($xml_file, "</beschreibung>");
    fclose($xml_file);
    clearstatcache();
    deleteThumb($twg_album, $image_orig); 
    // we reload
    $_SESSION["actalbum"] = "_RELOAD_";
    loadXMLFiles(urldecode($twg_album));
} 
// every entry of the $kwerte is stored.
function deleteKommentar($komment, $twg_album, $image, $kwerte, $kindex)
{
    global $xmldir;

    $album_rep = str_replace("/", "_", urldecode($twg_album));
    $xml_filename = "./" . $xmldir . "/" . $album_rep . "_kommentar_text.xml";
    $xml_file = fopen($xml_filename, 'w');

    fputs($xml_file, "<?xml version='1.0'?><beschreibung>\n");

    $i = 0;
    foreach ($kindex["NAME"] as $band) {
        $imageName = $kwerte[$band]["value"];
        $oldtitel = $kwerte[$kindex["WERT"][$i]]["value"];
        $i = $i + 1; 
        // echo urldecode($oldtitel) . ' : ' . urldecode($komment);
        if (strcmp(trim(urldecode(urldecode($oldtitel))), trim(urldecode(urldecode($komment)))) != 0) { // wir schreiben alle ausser dem zu löschenden !!
            $xml_string = "<bild><name>" . $imageName . "</name><wert>" . $oldtitel . "</wert></bild>\n";
            fputs($xml_file, $xml_string);
        } 
    } 
    fputs($xml_file, "</beschreibung>");
    fclose($xml_file);
    clearstatcache();
    deleteThumb($twg_album, $image);
    $_SESSION["actalbum"] = "_RELOAD_";
    loadXMLFiles(urldecode($twg_album)); 
    // we reload
} 

function deleteThumb($twg_album, $image)
{
    global $cachedir;
    global $extension_thumb;
    $thumb = dirname(__FILE__) . "/../" . $cachedir . "/" . urlencode(str_replace("/", "_", $twg_album) . "_" . $image) . "." . $extension_thumb;
    if (file_exists($thumb)) {
        unlink($thumb);
    } 
} 

function getDownloadCount($album_url, $image)
{
    return getCount($album_url, $image, "DOWNLOAD");
} 

function getVotesCount($album_url, $image)
{
    return getCount($album_url, $image, "VOTES");
} 

function getCount($album_url, $image, $type)
{
    global $xmldir;
    global $lang_rating_vote;

    if ((!isset($_SESSION["twg_count_actalbum"])) || ($_SESSION["twg_count_actalbum"] != $album_url)) {
        $returncount = 1;
        $album_url = str_replace("/", "_", $album_url);
        $xml_filename = "./" . $xmldir . "/" . urldecode($album_url) . "_image_counter.xml";

        if (!file_exists($xml_filename)) {
            return 0;
        } else {
            $xml_parser_handle = xml_parser_create();

            if (!($parse_handle = fopen($xml_filename, 'r'))) {
                die("FEHLER: Datei $xml_filename kann nicht erzeugt werden.");
            } 
            $xml_data = fread($parse_handle, filesize($xml_filename)); 
            // translation needed für german umlaute &uuml; ... !!
            // $e = array_flip (get_html_translation_table (HTML_ENTITIES));
            // unset($e["&amp;"]);
            // unset($e["&lt;"]);
            // unset($e["&gt;"]);
            // $xml_data = strtr($xml_data, $e);
            if (!xml_parse_into_struct($xml_parser_handle, $xml_data, $werte, $index)) {
                 die(sprintf('XML error: %s at line %d (%s)',
								                        xml_error_string(xml_get_error_code($xml_parser_handle)),
                        xml_get_current_line_number($xml_parser_handle), $xml_filename));
            } 
            xml_parser_free($xml_parser_handle);
        } 
        $_SESSION["twg_count_actalbum"] = $album_url;
        $_SESSION["twg_count_werte"] = $werte;
        $_SESSION["twg_count_index"] = $index;
    } else {
        // load session variables
        $werte = $_SESSION["twg_count_werte"];
        $index = $_SESSION["twg_count_index"];
    } 
    $i = 0;
    foreach ($index["NAME"] as $band) {
        $imageName = $werte[$band]["value"];
        if (urldecode($imageName) == $image) { // we have to decode because in the xml we encoded already
            if ($type == "DOWNLOAD") {
                $count = $werte[$index["DOWNLOAD"][$i]]["value"];
                return $count;
            } else {
                $votes = $werte[$index["VOTES"][$i]]["value"];
                $average = $werte[$index["AVERAGE"][$i]]["value"];
                return trim(sprintf("%1.2f", $average)) . " (" . $votes . " " . $lang_rating_vote . ")";
            } 
        } 
        $i = $i + 1;
    } 
    // if not we return 0
    return 0;
} 

function increaseImageCount($album_url, $image)
{
    return increaseCount($album_url, $image, "WERT", 1);
} 

function increaseDownloadCount($album_url, $image)
{
    increaseCount($album_url, $image, "DOWNLOAD", 1);
} 

function increaseVotesCount($album_url, $image, $rating)
{
    return increaseCount($album_url, $image, "VOTES", $rating);
} 

function increaseCount($album_url, $image, $type, $count)
{
    global $xmldir;
    $returncount = 1;

    $album_url = str_replace("/", "_", $album_url);
    $xml_filename = "./" . $xmldir . "/" . urldecode($album_url) . "_image_counter.xml";
    $xml_parser_handle = xml_parser_create();

    if (!file_exists($xml_filename)) {
        // we create an empty one
        $xml_file = fopen($xml_filename, 'w');
        $xml_1st_string = "<?xml version='1.0'?>\n<BESCHREIBUNG><BILD><NAME>" . urlencode($image) . "</NAME><WERT>0</WERT><DOWNLOAD>0</DOWNLOAD><VOTES>0</VOTES><AVERAGE>0</AVERAGE></BILD>\n</BESCHREIBUNG>";
        fputs($xml_file, $xml_1st_string);
        fclose($xml_file);
        return 1;
    } else {
        $xml_parser_handle = xml_parser_create();

        if (!($parse_handle = fopen($xml_filename, 'r'))) {
            die("FEHLER: Datei $xml_filename kann nicht erzeugt werden.");
        } 
        $xml_data = fread($parse_handle, filesize($xml_filename)); 
        // translation nneded für german umlaute &uuml; ... !!
        $e = array_flip (get_html_translation_table (HTML_ENTITIES));
        unset($e["&amp;"]);
        unset($e["&lt;"]);
        unset($e["&gt;"]);
        $xml_data = strtr($xml_data, $e);

        if (!xml_parse_into_struct($xml_parser_handle, $xml_data, $werte, $index)) {
             die(sprintf('XML error: %s at line %d (%s)',
						                        xml_error_string(xml_get_error_code($xml_parser_handle)),
                        xml_get_current_line_number($xml_parser_handle), $xml_filename));
        } 
        xml_parser_free($xml_parser_handle);
    } 

    $reload = false;
    if (isset($_SESSION['lastimage' . $type])) {
        if ($_SESSION['lastimage' . $type] == ($album_url . $image)) { // we check if we have a reload ;).
            if ($type == "VOTES" || $type == "DOWNLOAD") {
                return false;
            } 
            $reload = true;
        } 
    } 

    $isnew = true;
    $xml_file = fopen($xml_filename, 'w');

    fputs($xml_file, "<?xml version='1.0'?><BESCHREIBUNG>\n");

    $i = 0;

    $_SESSION['lastimage' . $type] = ($album_url . $image);

    foreach ($index["NAME"] as $band) {
        if (isset($werte[$band]["value"])) {
						$imageName = $werte[$band]["value"];
						$oldcount = $werte[$index["WERT"][$i]]["value"];
						$olddownload = $werte[$index["DOWNLOAD"][$i]]["value"];
						$oldvotes = $werte[$index["VOTES"][$i]]["value"];
						$oldaverage = $werte[$index["AVERAGE"][$i]]["value"];
						if (urldecode($imageName) == $image) { // we have to decode because in the xml we encoded already
								if ($reload) {
										$newcount = $oldcount;
										$newdownload = $olddownload;
										$newvotes = $oldvotes;
										$newaverage = $oldaverage;
								} else {
										if ($type == "WERT") {
												$newcount = $oldcount + 1;
												$newdownload = $olddownload;
												$newvotes = $oldvotes;
												$newaverage = $oldaverage;
										} else if ($type == "DOWNLOAD") {
												$newcount = $oldcount;
												$newdownload = $olddownload + 1;
												$newvotes = $oldvotes;
												$newaverage = $oldaverage;
										} else { // votes
												$newcount = $oldcount;
												$newdownload = $olddownload;
												$newaverage = (($oldaverage * $oldvotes) + $count) / ($oldvotes + 1) ;
												$newvotes = $oldvotes + 1;
										} 
								} 
								$returncount = $newcount; // we only return the viewcounter - the rest is called seperately !
								$isnew = false;
						} else {
								$newcount = $oldcount;
								$newdownload = $olddownload;
								$newvotes = $oldvotes;
								$newaverage = $oldaverage;
						} 
						$i = $i + 1;
						$xml_string = "<BILD><NAME>" . $imageName . "</NAME><WERT>" . $newcount . "</WERT><DOWNLOAD>" . $newdownload . "</DOWNLOAD><VOTES>" . $newvotes . "</VOTES><AVERAGE>" . $newaverage . "</AVERAGE></BILD>\n"; 
						// echo $xml_string;
						fputs($xml_file, $xml_string);
        }
    }
    if ($isnew) {
        $newcount = 0;
        $newdownload = 0;
        $newvotes = 0;
        $newaverage = 0;
        if ($type == "WERT") {
            $newcount = 1;
        } 
        if ($type == "DOWNLOAD") {
            $newdownload = 1;
        } 
        if ($type == "RATE") {
            $newvotes = 1;
            $newaverage = $count;
        } 
        $xml_string = "<BILD><NAME>" . urlencode($image) . "</NAME><WERT>" . $newcount . "</WERT><DOWNLOAD>" . $newdownload . "</DOWNLOAD><VOTES>" . $newvotes . "</VOTES><AVERAGE>" . $newaverage . "</AVERAGE></BILD>\n";
        fputs($xml_file, $xml_string);
    } 

    fputs($xml_file, "</BESCHREIBUNG>");
    fclose($xml_file);
    clearstatcache();

    session_unregister("twg_count_actalbum");
    session_unregister("twg_count_werte");
    session_unregister("twg_count_index");

    return $returncount;
} 

function getTopXViews($dirs)
{
    return getTopX($dirs, 'WERT');
} 

function getTopXDownloads($dirs)
{
    return getTopX($dirs, 'DOWNLOAD');
} 

function getTopXAverage($dirs)
{
    return getTopX($dirs, 'AVERAGE');
} 

function getTopXVotes($dirs)
{
    return getTopX($dirs, 'VOTES');
} 

function getTopX($dirs, $type)
{
    global $xmldir;
    global $install_dir;
    global $twg_standalone;
    global $basedir;

    $topx = array();

    for($ii = 0; $ii < count($dirs); $ii++) {
        $returncount = 1;
        $album_url = str_replace("/", "_", $dirs[$ii]);
        $xml_filename = "./" . $xmldir . "/" . urldecode($album_url) . "_image_counter.xml";

        $xml_parser_handle = xml_parser_create();

        if (file_exists($xml_filename)) {
            $xml_parser_handle = xml_parser_create();

            if (!($parse_handle = fopen($xml_filename, 'r'))) {
                die("FEHLER: File $xml_filename cannot be read.");
            } 
            $xml_data = fread($parse_handle, filesize($xml_filename)); 
            // translation nneded für german umlaute &uuml; ... !!
            $e = array_flip (get_html_translation_table (HTML_ENTITIES));
            unset($e["&amp;"]);
            unset($e["&lt;"]);
            unset($e["&gt;"]);
            $xml_data = strtr($xml_data, $e);

            if (!xml_parse_into_struct($xml_parser_handle, $xml_data, $werte, $index)) {
                 die(sprintf('XML error: %s at line %d (%s)',
								                        xml_error_string(xml_get_error_code($xml_parser_handle)),
                        xml_get_current_line_number($xml_parser_handle), $xml_filename));
            } 
            xml_parser_free($xml_parser_handle);
            $i = 0;
            foreach ($index["NAME"] as $band) {
                $imageName = $werte[$band]["value"]; 
                // echo  "<br><br>" . $imageName;
                $counter = $werte[$index[$type][$i]]["value"]; 
                // echo "<br />" . urldecode($imageName) . ":" . $counter . "<br />";
                $i++;

                if ($counter != 0) {
                    if ($type == "AVERAGE") {
                        $counter = trim(sprintf("%1.2f", $counter));
                    } 
                    // test if the image still exists!
                    $remote_image = checkurl($basedir . "/" . $dirs[$ii]);
                    $remote_image_exists = in_array(encodespace(urldecode($imageName)), get_image_list($dirs[$ii]));
                    if (file_exists($basedir . "/" . $dirs[$ii] . "/" . urldecode($imageName)) || $remote_image_exists) {
                        // the decode at the end is important - if you remove them images with hard filenames are not displayed :).
                        $compare = sprintf("%010s", $counter) . "_" . $install_dir . "image.php?twg_album=" . urlencode($dirs[$ii]) . "&amp;twg_type=thumb&amp;twg_show=" . $imageName . $twg_standalone; 
                        // echo $compare;
                        $topx[] = $compare;
                    } 
                } 
            } 
        } 
    } 
    rsort ($topx);
    reset ($topx);
    return $topx;
} 

function getLatestKomments($dirs)
{
    global $xmldir;
    global $install_dir;
    global $twg_standalone;
    global $basedir;

    $type = "WERT";

    $topx = array();

    for($ii = 0; $ii < count($dirs); $ii++) {
        $returncount = 1;
        $album_url = str_replace("/", "_", $dirs[$ii]);
        $xml_filename = "./" . $xmldir . "/" . urldecode($album_url) . "_kommentar_text.xml";

        $xml_parser_handle = xml_parser_create();

        if (file_exists($xml_filename)) {
            $xml_parser_handle = xml_parser_create();

            if (!($parse_handle = fopen($xml_filename, 'r'))) {
                die("FEHLER: File $xml_filename cannot be read.");
            } 
            $xml_data = fread($parse_handle, filesize($xml_filename)); 
            // translation nneded für german umlaute &uuml; ... !!
            $e = array_flip (get_html_translation_table (HTML_ENTITIES));
            unset($e["&amp;"]);
            unset($e["&lt;"]);
            unset($e["&gt;"]);
            $xml_data = strtr($xml_data, $e);

            if (!xml_parse_into_struct($xml_parser_handle, $xml_data, $werte, $index)) {
                 die(sprintf('XML error: %s at line %d (%s)',
								                        xml_error_string(xml_get_error_code($xml_parser_handle)),
                        xml_get_current_line_number($xml_parser_handle), $xml_filename));
            } 
            xml_parser_free($xml_parser_handle);
            $i = 0;
            $map = array();
            foreach ($index["NAME"] as $band) {
                $imageName = $werte[$band]["value"]; 
                // echo  "<br><br>" . $imageName;
                // echo "<br />" . urldecode($imageName) . ":" . $counter . "<br />";
                $line = urldecode($werte[$index["WERT"][$i]]["value"]);
                $i++;
                if (!(trim($line) == "")) {
                    list ($datum, $name, $komment) = explode("=||=", $line);

                    if (!isset($map[$imageName])) {
                        $map[$imageName] = "set"; 
                        // test if the image still exists!
                        $name = php_to_html_chars_all(restore_plus(urldecode(replace_plus($name)))); // fix for some server
                        $komment = php_to_html_chars_all(restore_plus(urldecode(replace_plus($komment)))); // fix for some server	
                        $pos = strpos ($datum, ".");
												if ($pos) {
												   $ttime = split("[.: ]" , $datum);
												   $datum = strtotime($ttime[1] . "/" . $ttime[0] . "/" . $ttime[2] . " " . $ttime[3] .  ":" . $ttime[4] ); // default for old formats!
												}    
                        $line = $datum . "=||=" . $name . "=||=" . $komment ;

                        $remote_image = checkurl($basedir . "/" . $dirs[$ii]);
                        $remote_image_exists = in_array(encodespace(urldecode($imageName)), get_image_list($dirs[$ii]));

                        if (file_exists($basedir . "/" . $dirs[$ii] . "/" . urldecode($imageName)) || $remote_image_exists) {
                            // the decode at the end is important - if you remove them images with hard filenames are not displayed :).
                            $compare = $line . "=||=" . $install_dir . "image.php?twg_album=" . urlencode($dirs[$ii]) . "&amp;twg_type=thumb&amp;twg_show=" . $imageName . $twg_standalone; 
                            // echo $compare;
                            $topx[] = $compare;
                        } 
                    } 
                } 
            } 
        } 
    } 
    rsort ($topx);
    reset ($topx);
    return $topx;
} 

function searchComments($dirs, $searchstring)
{
    global $xmldir;
    global $install_dir;
    global $twg_standalone;
    global $basedir;

    $type = "WERT";

    $topx = array();

    for($ii = 0; $ii < count($dirs); $ii++) {
        $returncount = 1;
        $album_url = str_replace("/", "_", $dirs[$ii]);
        $xml_filename = "./" . $xmldir . "/" . urldecode($album_url) . "_kommentar_text.xml";

        $xml_parser_handle = xml_parser_create();

        if (file_exists($xml_filename)) {
            $xml_parser_handle = xml_parser_create();

            if (!($parse_handle = fopen($xml_filename, 'r'))) {
                die("FEHLER: File $xml_filename cannot be read.");
            } 
            $xml_data = fread($parse_handle, filesize($xml_filename)); 
            // translation nneded für german umlaute &uuml; ... !!
            $e = array_flip (get_html_translation_table (HTML_ENTITIES));
            unset($e["&amp;"]);
            unset($e["&lt;"]);
            unset($e["&gt;"]);
            $xml_data = strtr($xml_data, $e);

            if (!xml_parse_into_struct($xml_parser_handle, $xml_data, $werte, $index)) {
                 die(sprintf('XML error: %s at line %d (%s)',
								                        xml_error_string(xml_get_error_code($xml_parser_handle)),
                        xml_get_current_line_number($xml_parser_handle), $xml_filename));
            } 
            xml_parser_free($xml_parser_handle);
            $i = 0;
            $map = array();
            foreach ($index["NAME"] as $band) {
                $imageName = $werte[$band]["value"]; 
                // echo  "<br><br>" . $imageName;
                // echo "<br />" . urldecode($imageName) . ":" . $counter . "<br />";
                $line = urldecode($werte[$index["WERT"][$i]]["value"]);
                $i++;
                if (!(trim($line) == "")) {
                    list ($datum, $name, $komment) = explode("=||=", $line);
                    if (!isset($map[$imageName])) {
                        $map[$imageName] = "set";
                        if (stristr(html_entity_decode ($name), $searchstring) || stristr(html_entity_decode ($komment), $searchstring)) {
                            $name = php_to_html_chars_all(restore_plus(urldecode(replace_plus($name)))); // fix for some server
                            $komment = php_to_html_chars_all(restore_plus(urldecode(replace_plus($komment)))); // fix for some server	
                            $pos = strpos ($datum, ".");
														if ($pos) {
															 $ttime = split("[.: ]" , $datum);
															 $datum = strtotime($ttime[1] . "/" . $ttime[0] . "/" . $ttime[2] . " " . $ttime[3] .  ":" . $ttime[4] ); // default for old formats!
											    	}    
                            $line = $datum . "=||=" . $name . "=||=" . $komment ;

                            $remote_image = checkurl($basedir . "/" . $dirs[$ii]);
                            $remote_image_exists = in_array(encodespace(urldecode($imageName)), get_image_list($dirs[$ii]));

                            if (file_exists($basedir . "/" . $dirs[$ii] . "/" . urldecode($imageName)) || $remote_image_exists) {
                                // the decode at the end is important - if you remove them images with hard filenames are not displayed :).
                                $compare = $line . "=||=" . $install_dir . "image.php?twg_album=" . urlencode($dirs[$ii]) . "&amp;twg_type=thumb&amp;twg_show=" . $imageName . $twg_standalone; 
                                // echo $compare;
                                $topx[] = $compare;
                            } 
                        } 
                    } 
                } 
            } 
        } 
    } 
    rsort ($topx);
    reset ($topx);
    return $topx;
} 

function searchCaption($dirs, $searchstring)
{
    global $xmldir;
    global $install_dir;
    global $twg_standalone;
    global $basedir;

    $type = "WERT";

    $topx = array();

    for($ii = 0; $ii < count($dirs); $ii++) {
        $returncount = 1;
        $album_url = str_replace("/", "_", $dirs[$ii]);
        $xml_filename = "./" . $xmldir . "/" . urldecode($album_url) . "_image_text.xml";

        $xml_parser_handle = xml_parser_create();

        if (file_exists($xml_filename)) {
            $xml_parser_handle = xml_parser_create();

            if (!($parse_handle = fopen($xml_filename, 'r'))) {
                die("FEHLER: File $xml_filename cannot be read.");
            } 
            $xml_data = fread($parse_handle, filesize($xml_filename)); 
            // translation nneded für german umlaute &uuml; ... !!
            $e = array_flip (get_html_translation_table (HTML_ENTITIES));
            unset($e["&amp;"]);
            unset($e["&lt;"]);
            unset($e["&gt;"]);
            $xml_data = strtr($xml_data, $e);

            if (!xml_parse_into_struct($xml_parser_handle, $xml_data, $werte, $index)) {
                 die(sprintf('XML error: %s at line %d (%s)',
								                        xml_error_string(xml_get_error_code($xml_parser_handle)),
                        xml_get_current_line_number($xml_parser_handle), $xml_filename));
            } 
            xml_parser_free($xml_parser_handle);
            $i = 0;
            $map = array();
            foreach ($index["NAME"] as $band) {
                $imageName = $werte[$band]["value"]; 
                // echo  "<br><br>" . $imageName;
                // echo "<br />" . urldecode($imageName) . ":" . $counter . "<br />";
                $line = urldecode($werte[$index["WERT"][$i]]["value"]);
                $i++;
                if (!(trim($line) == "")) {
                    $datum = ""; // we want to have the same display for all results - therefore some dummys here!
                    $name = $line;
                    $comment = "";

                    if (!isset($map[$imageName])) {
                        $map[$imageName] = "set";
                        if (stristr(html_entity_decode ($name), $searchstring)) {
                            $name = php_to_html_chars_all(restore_plus(urldecode(replace_plus($name)))); // fix for some server
                            $komment = htmlentities ($dirs[$ii]);

                            $line = $datum . "=||=" . $name . "=||=" . $komment ;

                            $remote_image = checkurl($basedir . "/" . $dirs[$ii]);
                            $remote_image_exists = in_array(encodespace(urldecode($imageName)), get_image_list($dirs[$ii]));

                            if (file_exists($basedir . "/" . $dirs[$ii] . "/" . urldecode($imageName)) || $remote_image_exists) {
                                // the decode at the end is important - if you remove them images with hard filenames are not displayed :).
                                $compare = $line . "=||=" . $install_dir . "image.php?twg_album=" . urlencode($dirs[$ii]) . "&amp;twg_type=thumb&amp;twg_show=" . $imageName . $twg_standalone; 
                                // echo $compare;
                                $topx[] = $compare;
                            } 
                        } 
                    } 
                } 
            } 
        } 
    } 
    return $topx;
} 

?>
