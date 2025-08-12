<?php

/******************************************
* File      :   functions.con.php
* Project   :   Contenido
* Descr     :   Defines the 'con' related
*               functions in Contenido
*
* Author    :   Olaf Niemann,
*               Jan Lengowski
*
* Created   :   sometime ago
* Modified  :   23.07.2003
*
* © four for business AG
******************************************/

/**
 * Create a new Article
 *
 * @param mixed many
 * @author Olaf Niemann <Olaf.Niemann@4fb.de>
 * @copyright four for business AG <http://www.4fb.de>
 *
 * @return int Id of the new article
 */
function conEditFirstTime($idcat, $idcatnew, $idart, $is_start, $idtpl,
                          $idartlang, $idlang, $title, $summary, $created,
                          $lastmodified, $author, $online, $datestart, $dateend,
                          $artsort, $keyart=0)
{

        global $db;
        global $client;
        global $lang;
        global $cfg;
        global $page_title;
        //Some stuff for the redirect
        global $redirect;
        global $redirect_url;
        global $external_redirect;
        global $time_move_cat; // Used to indicate "move to cat"
        global $time_target_cat; // Used to indicate the target category
        global $time_online_move; // Used to indicate if the moved article should be online
        global $timemgmt;
        global $HTTP_POST_VARS;

		$usetimemgmt		= ($timemgmt == '1') 	? '1' : '0';
		$movetocat 			= ($time_move_cat == '1') ? '1' : '0';
		$onlineaftermove 	= ($time_online_move == '1') ? '1' : '0';
		$redirect  			= ($redirect == '1') 	? '1' : '0';
        $external_redirect 	= ($redirect == '1') 	? '1' : '0';
        $redirect_url		= ($redirect_url == 'http://' || $redirect_url == '') ? '0' : $redirect_url;
        
		if ($is_start == 1)	{
			$usetimemgmt = "0";
		}
				
        $new_idart = $db->nextid($cfg["tab"]["art"]);

        # Set self defined Keywords
        if ( $keyart != "" ) {
            $keycode[1][1] = $keyart;
            SaveKeywordsforart($keycode,$new_idart,"self",$lang);
        }

        # Table 'cat_art'
        # Check if there are articles in this category.
        # If not make it a start article
        $sql = "SELECT * FROM ".$cfg["tab"]["cat_art"]." WHERE idcat='$idcat'";
        $db->query($sql);
        
        if ( $db->next_record() ) {
                $sql = "INSERT INTO ".$cfg["tab"]["cat_art"]." (idcatart, idcat, idart, is_start) VALUES ('".$db->nextid($cfg["tab"]["cat_art"])."', '$idcat', '$new_idart', '0')";
                $db->query($sql);
        } else {
                $sql = "INSERT INTO ".$cfg["tab"]["cat_art"]." (idcatart, idcat, idart, is_start) VALUES ('".$db->nextid($cfg["tab"]["cat_art"])."', '$idcat', '$new_idart', '1')";
                $db->query($sql);
        }

        # Table 'con_art'
        $sql = "INSERT INTO ".$cfg["tab"]["art"]." (idart, idclient) VALUES ('$new_idart', '$client')";
        $db->query($sql);

        # Table 'con_stat'
        $sql = "SELECT idcatart FROM ".$cfg["tab"]["cat_art"]." WHERE idcat = '$idcat' AND idart = '$new_idart'";
        $db->query($sql);
        $db->next_record();
        $idcatart = $db->f("idcatart");

        $a_languages = getLanguagesByClient($client);
        foreach ($a_languages as $tmp_lang) {
                $sql = "INSERT INTO ".$cfg["tab"]["stat"]." (idstat, idcatart, idlang, idclient, visited) VALUES ('".$db->nextid($cfg["tab"]["stat"])."', '$idcatart', '$tmp_lang', '$client', '0')";
                $db->query($sql);
        }

        # Table 'con_art_lang'
        # One entry for every language
        foreach ($a_languages as $tmp_lang) {

            $lastmodified = ( $lang == $tmp_lang ) ? $lastmodified : 0;

                $sql = "INSERT INTO
                        ".$cfg["tab"]["art_lang"]." (
                        idartlang,
                        idart,
                        idlang,
                        title,
                        pagetitle,
                        summary,
                        created,
                        lastmodified,
                        author,
                        online,
                        redirect,
                        redirect_url,
                        external_redirect,
                        artsort,
                        timemgmt,
                        datestart,
                        dateend,
                        status,
                        time_move_cat,
                        time_target_cat,
                        time_online_move
                        ) VALUES (
                        '".$db->nextid($cfg["tab"]["art_lang"])."',
                        '$new_idart',
                        '$tmp_lang',
                        '$title',
                        '".addslashes($page_title)."',
                        '$summary',
                        '$created',
                        '$lastmodified',
                        '".$auth->auth["uname"]."',
                        '$online',
                        '$redirect',
                        '$redirect_url',
                        '$external_redirect',
                        '$artsort',
                        '$usetimemgmt',
                        '$datestart',
                        '$dateend',
                        '0',
                        '$movetocat',
                        '$time_target_cat',
                        '$onlineaftermove')";
                        
                $db->query($sql);

			$availableTags = conGetAvailableMetaTagTypes();
	
			foreach ($availableTags as $key => $value)
			{
				conSetMetaValue($nextidartlang,
								$key,
								$HTTP_POST_VARS['META'.$value["name"]]);
        	}
        }

        # Set new idart
        $idart = $new_idart;

        # Table 'cat_art'
        $sql = "SELECT idcat FROM ".$cfg["tab"]["cat_art"]." WHERE idart='$idart'";          // get all idcats that contain art
        $db->query($sql);

        while ($db->next_record()) {
            $tmp_idcat[] = $db->f("idcat");
        }

        if ( !is_array($idcatnew) )     { $idcatnew[0] = 0; }
        if ( !is_array($tmp_idcat) )    { $tmp_idcat[0] = 0; }
        
        foreach ($idcatnew as $value) {

            if ( !in_array($value, $tmp_idcat) ) {

                # INSERT -> Table 'cat_art'
                $sql = "INSERT INTO ".$cfg["tab"]["cat_art"]." (idcatart, idcat, idart) VALUES ('".$db->nextid($cfg["tab"]["cat_art"])."', '$value', '$idart')";
                $db->query($sql);

                # Entry in 'stat'-table for all languages
                $sql = "SELECT idcatart FROM ".$cfg["tab"]["cat_art"]." WHERE idcat='$value' AND idart='$idart'";
                $db->query($sql);
                
                $db->next_record();
                $tmp_idcatart = $db->f("idcatart");

                $a_languages = getLanguagesByClient($client);

                foreach ($a_languages as $tmp_lang) {

                    $sql = "INSERT INTO ".$cfg["tab"]["stat"]." (idstat, idcatart, idlang, idclient, visited) VALUES ('".$db->nextid($cfg["tab"]["stat"])."', '$tmp_idcatart', '$tmp_lang', '$client', '0')";
                    $db->query($sql);
                }
            }
        }
        
        
        foreach ($tmp_idcat as $value) {

            if ( !in_array($value, $idcatnew) ) {

                $sql = "SELECT idcatart FROM ".$cfg["tab"]["cat_art"]." WHERE idcat='$value' AND idart='$idart'";          // get all idcatarts that will no longer exist
                $db->query($sql);

                //******** delete from 'code'-table ***************        // and delete corresponding code
                $sql = "DELETE FROM ".$cfg["tab"]["code"]." WHERE idcatart='".$db->f("idcatart")."'";
                $db->query($sql);

                //******* delete from 'stat'-table ****************
                $sql = "SELECT * FROM ".$cfg["tab"]["cat_art"]." WHERE idcat='$value' AND idart='$idart' ";
                $db->query($sql);

                while ($db->next_record()) {
                    $a_idcatart[] = $db->f("idcatart");
                }
                
                if (is_array($a_idcatart)) {

                        foreach ($a_idcatart AS $value2) {
                                //****** delete from 'stat'-table ************
                                $sql = "DELETE FROM ".$cfg["tab"]["stat"]." WHERE idcatart='$value2'";
                                $db->query($sql);
                        }
                }
                
                //******** delete from 'cat_art'-table ***************
                $sql = "DELETE FROM ".$cfg["tab"]["cat_art"]." WHERE idart='$idart' AND idcat='$value'";
                $db->query($sql);

                //******** delete from 'tpl_conf'-table ***************
                $sql = "SELECT idtplcfg FROM ".$cfg["tab"]["art_lang"]." WHERE idart = '$idart' AND idlang = '$lang'";
                $db->query($sql);
                $db->next_record();
                $tmp_idtplcfg = $db->f('idtplcfg');

                $sql = "DELETE FROM ".$cfg["tab"]["tpl_conf"]." WHERE idtplcfg = '".$tmp_idtplcfg."'";
                $db->query($sql);

            }
        }


        //********* update into 'art_lang'-table for all languages ******
        if ( !$title ) $title = "--- Default Title ---";

        $a_languages = getLanguagesByClient($client);

        foreach ($a_languages as $tmp_lang) {

            $online = ( $lang == $tmp_lang ) ? $online : 0;
            $lastmodified = ( $lang == $tmp_lang ) ? $lastmodified : 0;

            $sql = "UPDATE
                    ".$cfg["tab"]["art_lang"]."
                    SET
                    title           = '".$title."',
                    pagetitle       = '".$page_title."',
                    summary         = '".$summary."',
                    created         = '".$created."',
                    lastmodified    = '".$lastmodified."',
                    modifiedby          = '".$author."',
                    online          = '".$online."',
                    datestart       = '".$datestart."',
                    dateend         = '".$dateend."',
                    redirect        = '".$redirect."',
                    external_redirect = '".$external_redirect."',
                    redirect_url    = '".$redirect_url."',
                    artsort         = '".$artsort."'
                    WHERE
                    idart           = '".$new_idart."' AND
                    idlang          = '".$tmp_lang."'";

            $db->query($sql);
        }

        return $new_idart;
}




/**
 * Edit an existing article
 *
 * @param mixed many
 * @return void
 *
 * @author Olaf Niemann <olaf.niemann@4fb.de>
 * @copyright four for business AG <www.4fb.de>
 */
function conEditArt($idcat, $idcatnew, $idart, $is_start, $idtpl, $idartlang,
                    $idlang, $title, $summary, $created, $lastmodified, $author,
                    $online, $datestart, $dateend, $artsort, $keyart = 0)
{
        $args = func_get_args();
		
        global $db, $client, $lang, $cfg, $redirect, $redirect_url, $external_redirect, $perm;
        global $time_move_cat, $time_target_cat;
        global $time_online_move; // Used to indicate if the moved article should be online
        global $timemgmt;
        global $page_title;
        global $HTTP_POST_VARS;

        /* Add slashes because single quotes
           will crash the db */
        $page_title = addslashes($page_title);
        
        $usetimemgmt = ($timemgmt == '1') ? '1': '0';        
		$onlineaftermove = ($time_online_move == '1') ? '1' : '0';
		$movetocat = ($time_move_cat == '1') ? '1' : '0';
        $redirect     = ('1' == $redirect ) ? 1 : 0;
        $redirect_url = ($redirect_url == 'http://' || $redirect_url == '') ? '0' : $redirect_url;
        $external_redirect = ($external_redirect == '1') ? 1 : 0;

		if ($is_start == 1)
		{
			$usetimemgmt = "0";
		}
		
        $sql = "SELECT idcat FROM ".$cfg["tab"]["cat_art"]." WHERE idart='$idart'";          // get all idcats that contain art
        $db->query($sql);
        
        while ($db->next_record()) {
                $tmp_idcat[] = $db->f("idcat");
        }

        if ( !is_array($idcatnew) ) {
            $idcatnew[0] = 0;
        }

        if ( !is_array($tmp_idcat) ) {
            $tmp_idcat[0] = 0;
        }

//        if (is_array($idcatnew)) {
        foreach ($idcatnew as $value) {

            if ( in_array($value, $tmp_idcat) ) {
                # UPDATE 'cat_art' table
                #$sql = "UPDATE ".$cfg["tab"]["cat_art"]." SET idcat='$value' WHERE idart='$idart' AND idcat='$idcat'";
                #$db->query($sql);
                
            } else {

                # INSERT insert 'cat_art' table
                $sql = "INSERT INTO ".$cfg["tab"]["cat_art"]." (idcatart, idcat, idart) VALUES ('".$db->nextid($cfg["tab"]["cat_art"])."', '$value', '$idart')";
                $db->query($sql);

                # entry in 'stat'-table for all languages
                $sql = "SELECT idcatart FROM ".$cfg["tab"]["cat_art"]." WHERE idcat='$value' AND idart='$idart'";
                $db->query($sql);
                $db->next_record();
                
                $tmp_idcatart = $db->f("idcatart");

                $a_languages = getLanguagesByClient($client);

                foreach ($a_languages as $tmp_lang) {
                        $sql = "INSERT INTO ".$cfg["tab"]["stat"]." (idstat, idcatart, idlang, idclient, visited) VALUES ('".$db->nextid($cfg["tab"]["stat"])."', '$tmp_idcatart', '$tmp_lang', '$client', '0')";
                        $db->query($sql);
                }
            }
        }
        
//        }
//        if (is_array($tmp_idcat)) {
                foreach ($tmp_idcat as $value) {
//                        if (is_array($idcatnew)) {
                                if (!in_array($value, $idcatnew)) {

                                        $sql = "SELECT idcatart FROM ".$cfg["tab"]["cat_art"]." WHERE idcat='$value' AND idart='$idart'";          // get all idcatarts that will no longer exist
                                        $db->query($sql);
                                        //******** delete from 'code'-table ***************        // and delete corresponding code
                                        $sql = "DELETE FROM ".$cfg["tab"]["code"]." WHERE idcatart='".$db->f("idcatart")."'";
                                        $db->query($sql);

                                        //******* delete from 'stat'-table ****************
                                        $sql = "SELECT * FROM ".$cfg["tab"]["cat_art"]." WHERE idcat='$value' AND idart='$idart' ";
                                        $db->query($sql);
                                        
                                        while ($db->next_record()) {
                                                $a_idcatart[] = $db->f("idcatart");
                                        }
                                        
                                        if (is_array($a_idcatart)) {
                                                foreach ($a_idcatart as $value2) {
                                                        //****** delete from 'stat'-table ************
                                                        $sql = "DELETE FROM ".$cfg["tab"]["stat"]." WHERE idcatart='$value2'";
                                                        $db->query($sql);
                                                }
                                        }

                                        //******** delete from 'cat_art'-table ***************
                                        $sql = "DELETE FROM ".$cfg["tab"]["cat_art"]." WHERE idart='$idart' AND idcat='$value'";
                                        $db->query($sql);

                                        # TO DO: ##############################################
                                        #
                                        //******** delete from 'tpl_conf'-table ***************
                                        #$sql = "DELETE FROM ".$cfg["tab"]["tpl_conf"]." WHERE idart='$idart' AND idcat='$value'";
                                        #$db->query($sql);


                                }
//                        }
                }
//        }


        //******** update 'art'-table ***************
#        $sql = "UPDATE ".$cfg["tab"]["art"]." SET is_start='$is_start' WHERE idart='$idart'";
#       $db->query($sql);

		// If the user has no right for makeonline, don't update it.
		if (!$perm->have_perm_area_action("con","con_makeonline") &&
		    !$perm->have_perm_area_action_item("con","con_makeonline", $idcat))
		{
		    $sqlonline = "";
		} else {
			$sqlonline = "online = '$online',";
		}
		
		if ($title == "")
		{
			$title = "-- ".i18n("Default title")." --";
		}
		
        //******** update 'art_lang'-table **********
        $sql = "UPDATE
                    ".$cfg["tab"]["art_lang"]."
                SET
                    title = '$title',
                    pagetitle = '$page_title',
                    summary = '$summary',
                    created = '$created',
                    lastmodified = '$lastmodified',
                    modifiedby = '$author',
                    $sqlonline
                    timemgmt = '$usetimemgmt',
                    redirect = '$redirect',
                    external_redirect = '$external_redirect',
                    redirect_url = '$redirect_url',
                    artsort = '$artsort'";

		if ($perm->have_perm_area_action("con", "con_makeonline") ||
        $perm->have_perm_area_action_item("con","con_makeonline", $idcat))
        {
        	          $sql .= ", datestart = '$datestart',
                    dateend = '$dateend',
                    time_move_cat = '$movetocat',
                    time_target_cat = '$time_target_cat',
                    time_online_move = '$onlineaftermove'";
        }     
               

		$sql .= "                WHERE
                    idartlang='$idartlang'";
        $db->query($sql);

        $availableTags = conGetAvailableMetaTagTypes();
	
		foreach ($availableTags as $key => $value)
		{
			conSetMetaValue($idartlang	,
							$key,
							$HTTP_POST_VARS['META'.$value["name"]]);
		}

        // set kategory key
        //$keycode[1][1]=$keyart;
        //SaveKeywordsforart($keycode,$idart,"self",$lang);


}


/**
 * Save a content element
 *
 * @param integer $idartlang idartlang of the article
 * @param integer $type Type of content element
 * @param integer $typeid Serial number of the content element
 * @param string $value Content
 *
 * @return void
 *
 * @author Olaf Niemann <olaf.niemann@4fb.de>
 *         Jan Lengowski <jan.lengowski@4fb.de>
 *
 * @copyright four for business AG <www.4fb.de>
 *
 */
function conSaveContentEntry($idartlang, $type, $typeid, $value)
{
    global $db, $auth, $cfg, $cfgClient, $client, $lang;

    $date   = date("Y-m-d H:i:s");
    $author = $auth->auth["uname"];

    $cut_path  = $cfgClient[$client]["path"]["htmlpath"];

    $value = str_replace($cut_path, "", $value);
    $value = stripslashes($value);
    $value = urlencode($value);

    $sql = "SELECT * FROM ".$cfg["tab"]["type"]." WHERE type = '".$type."'";
    $db->query($sql);
    $db->next_record();
    $idtype=$db->f("idtype");

    $sql = "SELECT * FROM ".$cfg["tab"]["content"]." WHERE idartlang='$idartlang' AND idtype='$idtype' AND typeid='$typeid'";
    $db->query($sql);

    if ($db->next_record()) {

            //echo "Updated - idartlang:$idartlang / type:$type / typeid:$typeid / value:$value<br><br>";

            $sql = "UPDATE ".$cfg["tab"]["content"]." SET value='$value', author='$author', lastmodified='$date' WHERE idartlang='$idartlang' AND idtype='$idtype' AND typeid='$typeid'";
            $db->query($sql);

            // Save the main article last modified date
            $sql = "UPDATE " . $cfg["tab"]["art_lang"]." SET lastmodified='$date' WHERE idartlang='$idartlang'";
            $db->query($sql);

    } else {

            $sql = "INSERT INTO ".$cfg["tab"]["content"]." (idcontent, idartlang, idtype, typeid, value, author, created, lastmodified) VALUES('".$db->nextid($cfg["tab"]["content"])."', '$idartlang', '$idtype', '$typeid', '$value', '$author', '$date', '$date')";
            $db->query($sql);
    }
    
    /* Touch the article to update last modified date */
    $lastmodified = date("Y-m-d H:i:s");
    
    $sql = "UPDATE
                    ".$cfg["tab"]["art_lang"]."
			SET
                    lastmodified = '$lastmodified',
                    modifiedby = '$author'
			WHERE
                    idartlang='$idartlang'";
	$db->query($sql);
                    
}


/**
 * Toggle the online status
 * of an article
 *
 * @param int $idart Article Id
 * @param ing $lang Language Id
 *
 * @author Olaf Niemann <olaf.niemann@4fb-de>
 *         Jan Lengowski <jan.lengowski@4fb.de>
 *
 * @copyright four for business AG <www.4fb.de>
 */
function conMakeOnline($idart, $lang)
{
    global $db, $cfg;

    $sql = "SELECT online FROM ".$cfg["tab"]["art_lang"]." WHERE idart = '".$idart."' AND idlang = '".$lang."'";
    $db->query($sql);

    $db->next_record();

    $set = ( $db->f("online") == 0 ) ? 1 : 0;

    $sql = "UPDATE ".$cfg["tab"]["art_lang"]." SET online = '".$set."' WHERE idart = '".$idart."' AND idlang = '".$lang."'";
    $db->query($sql);
}

/**
 * Toggle the lock status
 * of an article
 *
 * @param int $idart Article Id
 * @param ing $lang Language Id
 *
 */
function conLock($idart, $lang)
{
    global $db, $cfg;

    $sql = "SELECT locked FROM ".$cfg["tab"]["art_lang"]." WHERE idart = '".$idart."' AND idlang = '".$lang."'";
    $db->query($sql);

    $db->next_record();

    $set = ( $db->f("locked") == 0 ) ? 1 : 0;

    $sql = "UPDATE ".$cfg["tab"]["art_lang"]." SET locked = '".$set."' WHERE idart = '".$idart."' AND idlang = '".$lang."'";
    $db->query($sql);
}

/**
 * Toggle the online status of
 * a category
 *
 * @param int $idcat id of the category
 * @param int $lang id of the language
 * @param int $status status of the category
 *
 * @author Jan Lengowski <jan.lengowski@4fb.de>
 * @copyright four for business AG <www.4fb.de>
 */
function conMakeCatOnline($idcat, $lang, $status)
{
    global $cfg, $db;
    
    $tmp_cats = conDeeperCategoriesArray($idcat);
    
    foreach ($tmp_cats as $val)
    {
        $sql = "UPDATE ".$cfg["tab"]["cat_lang"]." SET visible = '".!$status."'
                WHERE idcat = '".$val."' AND idlang = '".$lang."'";
        $db->query($sql);
    }
}

/**
 * Toggle the public status
 * of an category
 *
 * @param int $idcat Article Id
 * @param int $idcat Language Id
 * @param bool $is_start Start status of the Article
 *
 * @author Olaf Niemann <olaf.niemann@4fb-de>
 *         Jan Lengowski <jan.lengowski@4fb.de>
 *
 * @copyright four for business AG <www.4fb.de>
 */
function conMakePublic($idcat, $lang, $public)
{
    global $db, $cfg;

    $a_catstring = conDeeperCategoriesArray($idcat);

    foreach ($a_catstring as $value)
    {
        $sql = "UPDATE
                    ".$cfg["tab"]["cat_lang"]."
                SET
                    public = '".!$public."'
                WHERE
                    idcat = '".$value."'
                AND
                    idlang='".$lang."'";
                
        $db->query($sql);
    }
}


/**
 * Delete an Article
 *
 * @param int $idart Article Id
 *
 * @author Olaf Niemann <olaf.niemann@4fb-de>
 *         Jan Lengowski <jan.lengowski@4fb.de>
 *
 * @copyright four for business AG <www.4fb.de>
 */
function conDeleteart($idart)
{
    global $db, $cfg, $lang;

    $sql = "SELECT * FROM ".$cfg["tab"]["cat_art"]." WHERE idart = '".$idart."'";
    $db->query($sql);

    while ( $db->next_record() ) {
        $idcatart[] = $db->f("idcatart");
    }

    ##################################################
    # set keywords
    $keycode[1][1]="";
    SaveKeywordsforart($keycode,$idart,"auto",$lang);
    SaveKeywordsforart($keycode,$idart,"self",$lang);

    if ( is_array($idcatart) ) {

        foreach ($idcatart AS $value) {

            //********* delete from code table **********
            $sql = "DELETE FROM ".$cfg["tab"]["code"]." WHERE idcatart = '".$value."'";
            $db->query($sql);

            //****** delete from 'stat'-table ************
            $sql = "DELETE FROM ".$cfg["tab"]["stat"]." WHERE idcatart = '".$value."'";
            $db->query($sql);

        }
    }

    $sql = "SELECT * FROM ".$cfg["tab"]["art_lang"]." WHERE idart = '".$idart."'";

    $db->query($sql);

    while ( $db->next_record() ) {
        $idartlang[] = $db->f("idartlang");
    }

    if ( is_array($idartlang) ) {

        foreach ($idartlang AS $value) {

            //********* delete from content table **********
            $sql = "DELETE FROM ".$cfg["tab"]["content"]." WHERE idartlang = '".$value."'";
            $db->query($sql);
        }
    }

    $sql = "DELETE FROM ".$cfg["tab"]["cat_art"]." WHERE idart = '".$idart."'";
    $db->query($sql);

    $sql = "DELETE FROM ".$cfg["tab"]["art"]." WHERE idart = '".$idart."'";
    $db->query($sql);


    $sql = "DELETE FROM ".$cfg["tab"]["art_lang"]." WHERE idart = '".$idart."'";
    $db->query($sql);

    $sql = "SELECT idtplcfg FROM ".$cfg["tab"]["art_lang"]." WHERE idart = '".$idart."' AND idlang = '".$lang."'";
    $db->query($sql);
    $db->next_record();
    $tmp_idtplcfg = $db->f('idtplcfg');

    $sql = "DELETE FROM ".$cfg["tab"]["tpl_conf"]." WHERE idtplcfg = '".$tmp_idtplcfg."'";
    $db->query($sql);
        
}

/**
 * Extract a number from a string
 *
 * @param string $string String var by reference
 *
 * @author Olaf Niemann <olaf.niemann@4fb-de>
 *         Jan Lengowski <jan.lengowski@4fb.de>
 *
 * @copyright four for business AG <www.4fb.de>
 */
function extractNumber(&$string)
{
    $string = preg_replace("/[^0-9]/","",$string);
}



/**
 * Change the template of a category
 *
 * @param int $idcat Category Id 
 * @param int $idtpl Template Id
 *
 * @return void
 * @author Jan Lengowski <jan.lengowski@4fb.de>
 * @copyright four for business AG <www.4fb.de>
 */
function conChangeTemplateForCat($idcat, $idtpl)
{
    /* Global vars */
    global $db, $db2, $cfg, $lang;
	
	/* DELETE old entries */
	$sql = "SELECT idtplcfg FROM ".$cfg["tab"]["cat_lang"]." WHERE idcat = $idcat AND idlang = $lang";	
	$db->query($sql);
	$db->next_record();	
	$old_idtplcfg = $db->f("idtplcfg");
	
	$sql = "DELETE FROM ".$cfg["tab"]["tpl_conf"]." WHERE idtplcfg = $old_idtplcfg";
	$db->query($sql);
	
	$sql = "DELETE FROM ".$cfg["tab"]["container_conf"]." WHERE idtplcfg = $old_idtplcfg";
	$db->query($sql);	
	
    /* parameter $idtpl is 0,
       reset the template */
    if ( 0 == $idtpl ) {

        /* get $idtplcfg */
        $sql = "SELECT idtplcfg FROM ".$cfg["tab"]["cat_lang"]." WHERE idcat = '".$idcat."' AND idlang = '".$lang."'";

        $db->query($sql);
        $db->next_record();
        
        $idtplcfg = $db->f("idtplcfg");
        
        /* DELETE 'template_conf' entry */
        $sql = "DELETE FROM ".$cfg["tab"]["tpl_conf"]." WHERE idtplcfg = '".$idtplcfg."'";
        $db->query($sql);
        
        /* DELETE 'container_conf entries' */
        $sql = "DELETE FROM ".$cfg["tab"]["tpl_conf"]." WHERE idtplcfg = '".$idtplcfg."'";
        $db->query($sql);
        
        /* UPDATE 'cat_lang' table */
        $sql = "UPDATE ".$cfg["tab"]["cat_lang"]." SET idtplcfg = '0' WHERE idcat = '".$idcat."' AND idlang = '".$lang."'";
        $db->query($sql);

    } else {

        if ( !is_object($db2) ) $db2 = new DB_Contenido;

        /* check if a pre-configuration
           is assigned */
        $sql = "SELECT idtplcfg FROM ".$cfg["tab"]["tpl"]." WHERE idtpl = '".$idtpl."'";

        $db->query($sql);
        $db->next_record();

        if ( 0 != $db->f("idtplcfg") ) {

            /* template is pre-configured,
               create new configuration and
               copy data from pre-cfg */

            /* get new id */
            $new_idtplcfg = $db2->nextid($cfg["tab"]["tpl_conf"]);

            /* create new configuration */
            $sql = "INSERT INTO ".$cfg["tab"]["tpl_conf"]." (idtplcfg, idtpl) VALUES ('".$new_idtplcfg."', '".$idtpl."')";
            $db->query($sql);

            /* extract pre-configuration data */
            $sql = "SELECT * FROM ".$cfg["tab"]["container_conf"]." WHERE idtplcfg = '".$db->f("idtplcfg")."'";
            $db->query($sql);

            while ( $db->next_record() ) {

                /* get data */
                $nextid     = $db2->nextid($cfg["tab"]["container_conf"]);
                $number     = $db->f("number");
                $container  = $db->f("container");

                /* write new entry */
                $sql = "INSERT INTO
                            ".$cfg["tab"]["container_conf"]."
                            (idcontainerc, idtplcfg, number, container)
                        VALUES
                            ('".$nextid."', '".$new_idtplcfg."', '".$number."', '".$container."')";

                $db2->query($sql);

            }
			
			/* extract old idtplcfg */
			$sql = "SELECT idtplcfg FROM ".$cfg["tab"]["cat_lang"]." WHERE idcat = $idcat AND idlang = $lang";
			$db->query($sql);
			$db->next_record();
			$tmp_idtplcfg = $db->f("idtplcfg");
			
			if ( $tmp_idtplcfg != 0 ) {
				
				/* DELETE 'template_conf' entry */
            	$sql = "DELETE FROM ".$cfg["tab"]["tpl_conf"]." WHERE idtplcfg = '".$tmp_idtplcfg."'";
                $db->query($sql);
                
                /* DELETE 'container_conf entries' */
                $sql = "DELETE FROM ".$cfg["tab"]["container_conf"]." WHERE idtplcfg = '".$tmp_idtplcfg."'";
                $db->query($sql);					
				
			}
			
            /* update 'cat_lang' table */
            $sql = "UPDATE ".$cfg["tab"]["cat_lang"]." SET idtplcfg = '".$new_idtplcfg."' WHERE idcat = '".$idcat."' AND idlang = '".$lang."'";
            $db->query($sql);

        } else {

            /* template is not pre-configured,
               create a new configuration.  */
            $new_idtplcfg = $db->nextid($cfg["tab"]["tpl_conf"]);

            $sql = "INSERT INTO ".$cfg["tab"]["tpl_conf"]."
                    (idtplcfg, idtpl) VALUES
                    ('".$new_idtplcfg."', '".$idtpl."')";

            $db->query($sql);
            
            /* update 'cat_lang' table */
            $sql = "UPDATE ".$cfg["tab"]["cat_lang"]." SET idtplcfg = '".$new_idtplcfg."' WHERE idcat = '".$idcat."' AND idlang = '".$lang."'";
            $db->query($sql);

        }
        
    }
    
} // end function

/**
 *
 * Fetch all deeper categories by a given id
 *
 * @param int $idcat Id of category
 * @return array Array with all deeper categories
 *
 * @author Olaf Niemann <olaf.niemann@4fb-de>
 *         Jan Lengowski <jan.lengowski@4fb.de>
 *
 * @copyright four for business AG <www.4fb.de>
 */
function conDeeperCategoriesArray($idcat_start)
{
    global $db, $client, $cfg;

    $sql = "SELECT
                *
            FROM
                ".$cfg["tab"]["cat_tree"]." AS A,
                ".$cfg["tab"]["cat"]." AS B
            WHERE
                A.idcat  = B.idcat AND
                idclient = '".$client."'
            ORDER BY
                idtree";

    $db->query($sql);

    $i = 0;

    while ( $db->next_record() ) {

        if ($db->f("parentid") < $idcat_start) {        // ending part of tree
            $i = 0;
        }
        
        if ($db->f("idcat") == $idcat_start) {        // starting part of tree
            $i = 1;
        }
        
        if ($i == 1) {
            $catstring[] = $db->f("idcat");
        }
        
    }
    return $catstring;
}

/**
 * Recursive function to create an location string
 *
 * @param int $idcat ID of the starting category
 * @param string $seperator Seperation string
 * @param string $cat_str Category location string (by reference)
 * @param boolean $makeLink create location string with links
 * @param string $linkClass stylesheet class for the links
 *
 * @return string location string
 *
 * @author Jan Lengowski <jan.lengowski@4fb.de>
 * @author Marco Jahn <marco.jahn@4fb.de>
 *
 * @copyright four for business AG <www.4fb.de>
 */
function conCreateLocationString($idcat, $seperator, &$cat_str, $makeLink = false, $linkClass = "")
{
    global $cfg, $client, $lang, $sess;

    if ($idcat == 0)
    {
        $cat_str = "Lost and Found";
        return;
    }
    $db = new DB_Contenido;
    
    $sql = "SELECT
                a.name AS name,
                a.idcat AS idcat,
                b.parentid AS parentid
            FROM
                ".$cfg["tab"]["cat_lang"]." AS a,
                ".$cfg["tab"]["cat"]." AS b
            WHERE
                a.idlang    = '".$lang."' AND
                b.idclient  = '".$client."' AND
                b.idcat     = '".$idcat."' AND
                a.idcat     = b.idcat";
                
    $db->query($sql);
    $db->next_record();

    $name       = $db->f("name");
    $parentid   = $db->f("parentid");

	//create link
	if ($makeLink == true)
	{
		$linkUrl = $sess->url("front_content.php?idcat=$idcat");
		$name = '<a href="'.$linkUrl.'" class="'.$linkClass.'">'.$name.'</a>';	
	}

    $tmp_cat_str = $name . $seperator . $cat_str;
    $cat_str = $tmp_cat_str;

    if ( $parentid != 0 ) {
        conCreateLocationString($parentid, $seperator, $cat_str, $makeLink, $linkClass);
        
    } else {
        $sep_length = strlen($seperator);
        $str_length = strlen($cat_str);
        $tmp_length = $str_length - $sep_length;
        $cat_str = substr($cat_str, 0, $tmp_length);
    }
}

/**
 * Set a start-article
 *
 * @param int $idcatart Idcatart of the article
 *
 * @return void
 *
 * @author Olaf Niemann <olaf.niemann@4fb-de>
 *         Jan Lengowski <jan.lengowski@4fb.de>
 *
 * @copyright four for business AG <www.4fb.de>
 */
function conMakeStart($idcatart, $is_start)
{
    global $is_start;
    global $db, $cfg, $lang;

    $sql = "SELECT idcat, is_start FROM ".$cfg["tab"]["cat_art"]." WHERE idcatart = '$idcatart'";

    $db->query($sql);
    $db->next_record();

    $tmp_idcat = $db->f("idcat");
    
    $set = $is_start;
    
    /* JL : 23.06.03
    if ( !isset($is_start) ) {   
    	$set = ( $db->f("is_start") == 0 ) ? 1 : 0;
    	
    } else {
		$set = !$is_start;
		    	
    } */

	$sql = "UPDATE ".$cfg["tab"]["cat_art"]." SET is_start = 0 WHERE idcat = $tmp_idcat";
    $db->query($sql);

    $sql = "UPDATE ".$cfg["tab"]["cat_art"]." SET is_start='$set' WHERE idcatart = '$idcatart'";
    $db->query($sql);
    
    if ( $set == 1 ) {
   		
   		// deactivate timemanagement if article is a start-article   		
   		$sql = "SELECT idart FROM ".$cfg["tab"]["cat_art"]." WHERE idcatart = $idcatart";
   		
   		$db->query($sql);
   		$db->next_record();
   		
   		$idart = $db->f("idart");
   		
   		$sql = "UPDATE ".$cfg["tab"]["art_lang"]." SET timemgmt = 0 WHERE idart = $idart AND idlang = $lang";
   		$db->query($sql); 
   	
    }

}


/**
 * Generates the code for one
 * article
 *
 * @param int $idcat Id of category
 * @param int $idart Id of article
 * @param int $lang Id of language
 * @param int $client Id of client
 * @param int $layout Layout-ID of alternate Layout (if false, use associated layout)
 *
 * @author Jan Lengowski <jan.lengowski@4fb.de>
 * @copyright four for business AG <www.4fb.de>
 */
function conGenerateCode($idcat, $idart, $lang, $client, $layout = false)
{
		global $frontend_debug;
		
        $debug = 0;

        if ($debug) echo "conGenerateCode($idcat, $idart, $lang, $client, $layout);<br>";

        global $db, $db2, $sess, $cfg, $code, $cfgClient, $client, $lang, $encoding;

        if ( !is_object($db2) ) $db2 = new DB_Contenido;

        /* extract IDCATART */
        $sql = "SELECT
                    idcatart
                FROM
                    ".$cfg["tab"]["cat_art"]."
                WHERE
                    idcat = '".$idcat."' AND
                    idart = '".$idart."'";

        $db->query($sql);
        $db->next_record();

        $idcatart = $db->f("idcatart");

        /* If neither the
           article or the category is
           configured, no code will be
           created and an error occurs. */
        $sql = "SELECT
                    a.idtplcfg AS idtplcfg
                FROM
                    ".$cfg["tab"]["art_lang"]." AS a,
                    ".$cfg["tab"]["art"]." AS b
                WHERE
                    a.idart     = '".$idart."' AND
                    a.idlang    = '".$lang."' AND
                    b.idart     = a.idart AND
                    b.idclient  = '".$client."'";

        $db->query($sql);
        $db->next_record();

        if ( $db->f("idtplcfg") != 0 ) {

            /* Article is configured */
            $idtplcfg = $db->f("idtplcfg");

            if ($debug) echo "configuration for article found: $idtplcfg<br><br>";

            $a_c = array();

            $sql2 = "SELECT
                        *
                     FROM
                        ".$cfg["tab"]["container_conf"]."
                     WHERE
                        idtplcfg = '".$idtplcfg."'
                     ORDER BY
                        number ASC";

            $db2->query($sql2);

            while ( $db2->next_record() ) {
                $a_c[$db2->f("number")] = $db2->f("container");

            }

        } else {

            /* Check whether category is
             configured. */
            $sql = "SELECT
                        a.idtplcfg AS idtplcfg
                    FROM
                        ".$cfg["tab"]["cat_lang"]." AS a,
                        ".$cfg["tab"]["cat"]." AS b
                    WHERE
                        a.idcat     = '".$idcat."' AND
                        a.idlang    = '".$lang."' AND
                        b.idcat     = a.idcat AND
                        b.idclient  = '".$client."'";

            $db->query($sql);
            $db->next_record();

            if ( $db->f("idtplcfg") != 0 ) {

                /* Category is configured,
                   extract varstring */
                $idtplcfg = $db->f("idtplcfg");

                if ($debug) echo "configuration for category found: $idtplcfg<br><br>";

                $a_c = array();

                $sql2 = "SELECT
                            *
                         FROM
                            ".$cfg["tab"]["container_conf"]."
                         WHERE
                            idtplcfg = '".$idtplcfg."'
                         ORDER BY
                            number ASC";

                $db2->query($sql2);

                while ( $db2->next_record() ) {
                    $a_c[$db2->f("number")] = $db2->f("container");

                }

            } else {

                /* Article nor Category
                   is configured. Creation of
                   Code is not possible. Write
                   Errormsg to DB. */

                if ($debug) echo "Neither CAT or ART are configured!<br><br>";
                   
                $code = 'echo "<html><body>No code was created for this art in this category.</body><html>";';

                $sql = "SELECT * FROM ".$cfg["tab"]["code"]." WHERE idcatart='$idcatart' AND idlang='$lang'";

                $db->query($sql);

                if ($db->next_record()) {
                        $sql = "UPDATE ".$cfg["tab"]["code"]." SET code='$code', idlang='$lang', idclient='$client' WHERE idcatart='$idcatart' AND idlang='$lang'";
                        $db->query($sql);
                } else {
                        $sql = "INSERT INTO ".$cfg["tab"]["code"]." (idcode, idcatart, code, idlang, idclient) VALUES ('".$db->nextid($cfg["tab"]["code"])."', '$idcatart', '$code', '$lang', '$client')";
                        $db->query($sql);
                }

                return "0601";

            }

        }

        /* Get IDLAY and IDMOD array */
        $sql = "SELECT
                    a.idlay AS idlay,
                    a.idtpl AS idtpl
                FROM
                    ".$cfg["tab"]["tpl"]." AS a,
                    ".$cfg["tab"]["tpl_conf"]." AS b
                WHERE
                    b.idtplcfg  = '".$idtplcfg."' AND
                    b.idtpl     = a.idtpl";

        $db->query($sql);
        $db->next_record();

        $idlay = $db->f("idlay");
        
        if ($layout != false)
        {
        	$idlay = $layout;
        }
        
        $idtpl = $db->f("idtpl");

        if ( $debug ) echo "Usging Layout: $idlay and Template: $idtpl for generation of code.<br><br>";

        /* List of used modules */
        $sql = "SELECT
                    number,
                    idmod
                FROM
                    ".$cfg["tab"]["container"]."
                WHERE
                    idtpl = '".$idtpl."'
                ORDER BY
                    number ASC";

        $db->query($sql);

        while ( $db->next_record() ) {
            $a_d[$db->f("number")] = $db->f("idmod");
        }

        /* Get code from Layout */
        $sql = "SELECT * FROM ".$cfg["tab"]["lay"]." WHERE idlay = '".$idlay."'";

        $db->query($sql);
        $db->next_record();

        $code = $db->f("code");
        $code = AddSlashes($code);

        /* Create code for all containers */
        if ($idlay) {

                $tmp_returnstring = tplBrowseLayoutForContainers($idlay);
                $a_container = explode("&", $tmp_returnstring);

                foreach ($a_container as $key=>$value) {

                    $sql = "SELECT * FROM ".$cfg["tab"]["mod"]." WHERE idmod='".$a_d[$value]."'";

                    $db->query($sql);
                    $db->next_record();

                    $output = $db->f("output");
                    $output = AddSlashes($output)."\n";

                    $template = $db->f("template");

                    $tmp1 = preg_split("/&/", $a_c[$value]);

                    $varstring = array();

                    foreach ($tmp1 as $key1=>$value1) {

                            $tmp2 = explode("=", $value1);
                            foreach ($tmp2 as $key2 => $value2) {
                                    $varstring["$tmp2[0]"] = $tmp2[1];
                            }
                    }

                    foreach ($varstring as $key3=>$value3) {
                        $CiCMS_VALUE = "C".$key3."CMS_VALUE";
                        $tmp = urldecode($value3);
                        $tmp = str_replace("\'", "'", $tmp);    // ' war das einzige Sonderzeichen was mit \ maskiert wurde. !?
                        $output = str_replace("CMS_VALUE[$key3]", $tmp, $output);
                    }

                    $output = ereg_replace("(CMS_VALUE\[)([0-9]*)(\])", "", $output);
                    
                    if ($frontend_debug["container_display"] == true)
                    {
                    	$fedebug .= "Container: CMS_CONTAINER[$value]".'\n';
                    }
                    if ($frontend_debug["module_display"] == true)
                    {
                    	$fedebug .= "Modul: ".$db->f("name").'\n';
                    }
					if ($frontend_debug["module_timing"] == true)
                    {
                    	$fedebug .= 'Eval-Time: $modtime'.$value.'\n';
						$output = '<?php $modstart'.$value.' = getmicrotime(); ?>'.$output.'<?php $modend'.$value.' = getmicrotime()+0.001; $modtime'.$value.' = $modend'.$value.' - $modstart'.$value.'; ?>';
                    }
                    
                    if ($fedebug != "")
                    {
                    	$output = addslashes('<?php echo \'<img onclick="javascript:showmod'.$value.'();" src="'.$cfg['path']['contenido_fullhtml'].'images/but_preview.gif">\'; ?>'."<br>").$output;
                    	$output = $output . addslashes( '<?php echo \'<script language="javascript">function showmod'.$value.' () { window.alert(\\\'\'. "'.addslashes($fedebug).'".\'\\\');} </script>\'; ?>' );
                    }
                    
                    $code  = str_replace("CMS_CONTAINER[$value]", $output, $code);
                    $fedebug = "";

                }
        }

        /* Find out what kind of CMS_... Vars are in use */
        $sql = "SELECT
                    *
                FROM
                    ".$cfg["tab"]["content"]." AS A,
                    ".$cfg["tab"]["art_lang"]." AS B,
                    ".$cfg["tab"]["type"]." AS C
                WHERE
                    A.idtype    = C.idtype AND
                    A.idartlang = B.idartlang AND
                    B.idart     = '".$idart."' AND
                    B.idlang    = '".$lang."'";

        $db->query($sql);

        while ( $db->next_record() ) {
            $a_content[$db->f("type")][$db->f("typeid")] = $db->f("value");
        }

        $sql = "SELECT idartlang, pagetitle FROM ".$cfg["tab"]["art_lang"]." WHERE idart='".$idart."' AND idlang='".$lang."'";

        $db->query($sql);
        $db->next_record();

        $idartlang = $db->f("idartlang");
        $pagetitle = stripslashes($db->f("pagetitle"));

        /* replace all CMS_TAGS[] */
        $sql = "SELECT type, code FROM ".$cfg["tab"]["type"];
        
        $db->query($sql);

        while ( $db->next_record() ) {

            $tmp = preg_match_all("/(".$db->f("type")."\[+\d+\])/", $code, $match);
            $a_[strtolower($db->f("type"))] = $match[0];

            $success = array_walk($a_[strtolower($db->f("type"))],'extractNumber');

            foreach ($a_[strtolower($db->f("type"))] as $val) {
                eval ($db->f("code"));
                $code  = str_replace("".$db->f("type")."[$val]", $tmp, $code);
                $keycode[$db->f("type")][$val] = $tmp;
                
            }
            
        }
        
        
        if(is_array($keycode)){
            saveKeywordsForArt($keycode, $idart, "auto", $lang);
        }

		$enc = '<meta http-equiv="Content-Type" content="text/html; charset='.$encoding[$lang].'">';

        /* add/replace title */
        if ($pagetitle != "")
        {
        $code = preg_replace("/<title>.*?<\/title>/", "{TITLE}", $code);

        if ( strstr($code, "{TITLE}") ) {
            $code = str_replace("{TITLE}", addslashes("<title>$pagetitle</title>"), $code);
        } else {
            $code = str_replace("</head>", addslashes("<title>".$pagetitle."</title>\n</head>"), $code);
        }
        }
        
        $availableTags = conGetAvailableMetaTagTypes();
	
		foreach ($availableTags as $key => $value)
		{
			$metavalue = conGetMetaValue($idartlang,$key);
			
			if (strlen($metavalue) > 0)
			{
				$metatags .= "<meta name=\"".$value["name"]."\" content=\"$metavalue\">\n";
			}
			
		}


        /* Add meta tags */
        $code = str_replace("</head>", addslashes("<meta name=\"generator\" content=\"CMS Contenido ".$cfg['version']."\">\n".$metatags.$enc."\n</head>"), $code);
        
        /* write code into the database */
        $date = date("Y-m-d H:i:s");
        
        if ($layout == false)
        {
            $sql = "SELECT * FROM ".$cfg["tab"]["code"]." WHERE idcatart = '".$idcatart."' AND idlang = '".$lang."'";
            
            $db->query($sql);
            
            if ($db->next_record()) {
                if ($debug) echo "UPDATED code for lang:$lang, client:$client, idcatart:$idcatart";
                $sql = "UPDATE ".$cfg["tab"]["code"]." SET code='".$code."', idlang='".$lang."', idclient='".$client."' WHERE idcatart='".$idcatart."' AND idlang='".$lang."'";
                $db->query($sql);
            } else {
                if ($debug) echo "INSERTED code for lang:$lang, client:$client, idcatart:$idcatart";
                $sql = "INSERT INTO ".$cfg["tab"]["code"]." (idcode, idcatart, code, idlang, idclient) VALUES ('".$db->nextid($cfg["tab"]["code"])."', '".$idcatart."', '".$code."', '".$lang."', '".$client."')";
                $db->query($sql);
            }
            
            $sql = "UPDATE ".$cfg["tab"]["cat_art"]." SET createcode = '0' WHERE idcatart='".$idcatart."'";
            $db->query($sql);
        }

return $code;

} // end function


/**
 * Create code for one article in all categorys
 *
 * @param int $idart Article ID
 *
 * @author Jan Lengowski <Jan.Lengowski@4fb.de>
 * @copyright four for business AG 2003
 */
function conGenerateCodeForArtInAllCategories($idart)
{
    global $lang, $client, $cfg;
    $db = new DB_Contenido;

    $sql = "SELECT idcatart FROM ".$cfg["tab"]["cat_art"]." WHERE idart = '".$idart."'";

    $db->query($sql);
    
    while ($db->next_record())
    {
        conSetCodeFlag($db->f("idcatart"));
    }    
}


/**
 * Generate code for all articles in a category
 *
 * @param int $idcat Category ID
 *
 * @author Jan Lengowski <Jan.Lengowski@4fb.de>
 * @copyright four for business AG 2003
 */
function conGenerateCodeForAllArtsInCategory($idcat)
{
    global $cfg;
    $db = new DB_Contenido;

    $sql = "SELECT idcatart FROM ".$cfg["tab"]["cat_art"]." WHERE idcat='$idcat'";
    $db->query($sql);
    
    while ($db->next_record())
    {
        conSetCodeFlag($db->f("idcatart"));
    }
}

/**
 * Generate code for the active client
 *
 * @author Jan Lengowski <Jan.Lengowski@4fb.de>
 * @copyright four for business AG 2003
 */
function conGenerateCodeForClient() {

    global $client, $cfg;
    $db = new DB_Contenido;

    $sql = "SELECT A.idcatart
            FROM ".$cfg["tab"]["cat_art"]." as A, ".$cfg["tab"]["cat"]." as B
            WHERE B.idclient='$client' AND B.idcat=A.idcat";
    $db->query($sql);

    while ($db->next_record())
    {
        conSetCodeFlag($db->f("idcatart"));
    }
}

/**
 * Create code for all arts using the
 * same layout
 *
 * @param int $idlay Layout-ID
 *
 * @author Jan Lengowski <Jan.Lengowski@4fb.de>
 * @copyright four for business AG 2003
 */
function conGenerateCodeForAllartsUsingLayout($idlay)
{
    global $cfg;
    $db = new DB_Contenido;

    $sql = "SELECT idtpl FROM ".$cfg["tab"]["tpl"]." WHERE idlay='$idlay'";
    $db->query($sql);
    while ($db->next_record())
    {
        conGenerateCodeForAllartsUsingTemplate($db->f("idtpl"));
    }
}

/**
 * Create code for all articles using
 * the same module
 *
 * @param int $idmod Module id
 *
 * @author Jan Lengowski <Jan.Lengowski@4fb.de>
 * @copyright four for business AG 2003
 */
function conGenerateCodeForAllartsUsingMod($idmod)
{
    global $cfg;
    $db = new DB_Contenido;

    $sql = "SELECT idtpl FROM ".$cfg["tab"]["container"]." WHERE idmod = '".$idmod."'";
    $db->query($sql);

    while($db->next_record())
    {
        conGenerateCodeForAllArtsUsingTemplate($db->f("idtpl"));
    }    
}


/**
 * Generate code for all articles
 * using one template
 *
 * @param int $idtpl Template-Id
 *
 * @author Jan Lengowski <Jan.Lengowski@4fb.de>
 * @copyright four for business AG 2003
 */
function conGenerateCodeForAllArtsUsingTemplate($idtpl)
{
    global $cfg, $lang, $client;
	
	$db = new DB_Contenido;
	$db2 = new DB_Contenido;    
    
    /* Search all categories */
    $sql = "SELECT
                b.idcat
            FROM
                ".$cfg["tab"]["tpl_conf"]." AS a,
                ".$cfg["tab"]["cat_lang"]." AS b,
                ".$cfg["tab"]["cat"]." AS c
            WHERE
                a.idtpl     = '".$idtpl."' AND
                b.idtplcfg  = a.idtplcfg AND
                b.idlang    = '".$lang."' AND
                c.idclient  = '".$client."' AND
                b.idcat     = c.idcat";

    $db->query($sql);

    while ($db->next_record())
    {
        $sql = "SELECT idcatart FROM ".$cfg["tab"]["cat_art"]." WHERE idcat='".$db->f("idcat")."'";
        $db2->query($sql);

        while ($db2->next_record())
        {
            conSetCodeFlag($db2->f("idcatart"));
        }
    }
    
    /* Search all articles */
    $sql = "SELECT
                b.idart
            FROM
                ".$cfg["tab"]["tpl_conf"]." AS a,
                ".$cfg["tab"]["art_lang"]." AS b,
                ".$cfg["tab"]["art"]." AS c
            WHERE
                a.idtpl     = '".$idtpl."' AND
                b.idtplcfg  = a.idtplcfg AND
                b.idlang    = '".$lang."' AND
                c.idclient  = '".$client."' AND
                b.idart     = c.idart";

    $db->query($sql);

    while ($db->next_record())
    {
        $sql = "SELECT idcatart FROM ".$cfg["tab"]["cat_art"]." WHERE idart='".$db->f("idart")."'";
        $db2->query($sql);

        while ($db2->next_record())
        {
            conSetCodeFlag($db2->f("idcatart"));
        }
    }
}


/**
 * Create code for all articles
 *
 * @author Jan Lengowski <Jan.Lengowski@4fb.de>
 * @copyright four for business AG 2003
 */
function conGenerateCodeForAllArts()
{
    global $cfg;
    $db = new DB_Contenido;

    $sql = "SELECT idcatart FROM ".$cfg["tab"]["cat_art"];
    $db->query($sql);
    
    while ($db->next_record())
    {
        conSetCodeFlag($db->f("idcatart"));
    }
}

/**
 * Set code creation flag to true
 *
 * @param int $idcatart Contenido Category-Article-ID
 *
 * @author Jan Lengowski <Jan.Lengowski@4fb.de>
 * @copyright four for business AG 2003
 */
function conSetCodeFlag($idcatart)
{
    global $cfg;
    $db = new DB_Contenido;

    $sql = "UPDATE ".$cfg["tab"]["cat_art"]." SET createcode = '1' WHERE idcatart='$idcatart'";
    $db->query($sql);
}


/**
 * Set articles on/offline for the time management function
 *
 * @param none
 *
 * @author Timo A. Hummel <Timo.Hummel@4fb.de>
 * @copyright four for business AG 2003
 */
function conFlagOnOffline()
{
	global $cfg;
	$db = new DB_Contenido;
	$db2 = new DB_Contenido;
	$sql = "SELECT idartlang FROM ".$cfg["tab"]["art_lang"]." WHERE NOW() > datestart AND NOW() < dateend AND timemgmt = 1";
	$db->query($sql);
	
	while ($db->next_record())
	{
		$sql = "UPDATE ".$cfg["tab"]["art_lang"]." SET online = 1 WHERE idartlang = ".$db->f("idartlang");
		$db2->query($sql);
	}	
}


/**
 * Move articles for the time management function
 *
 * @param none
 *
 * @author Timo A. Hummel <Timo.Hummel@4fb.de>
 * @copyright four for business AG 2003
 */
function conMoveArticles()
{
    global $cfg;
    $db = new DB_Contenido;
    $db2 = new DB_Contenido;

	/* Set all articles which are before our starttime to offline */
	$sql = "SELECT idartlang FROM ".$cfg["tab"]["art_lang"]." WHERE NOW() < datestart AND timemgmt = 1";

	$db->query($sql);
	
	while ($db->next_record())
	{
		$sql = "UPDATE ".$cfg["tab"]["art_lang"] ." SET online = 0 WHERE idartlang = ".$db->f("idartlang");
		$db2->query($sql);
	}
	
	/* Set all articles which are in between of our start/endtime to online */
	$sql = "SELECT idartlang FROM ".$cfg["tab"]["art_lang"]." WHERE NOW() > datestart AND NOW() < dateend AND online = 0 AND timemgmt = 1";
	
	$db->query($sql);
	
	while ($db->next_record())
	{
		$sql = "UPDATE ".$cfg["tab"]["art_lang"]." SET online = 0 WHERE idartlang = " . $db->f("idartlang");
		$db2->query($sql);
	}
	
	/* Set all articles after our endtime to offline */
	$sql = "SELECT idartlang FROM ".$cfg["tab"]["art_lang"]." WHERE NOW() > datestart AND NOW() < dateend AND online = 0 AND timemgmt = 1";
	
	$db->query($sql);
	
	while ($db->next_record())
	{
		$sql = "UPDATE ".$cfg["tab"]["cart_lang"]." SET online = 0 WHERE idartlang = " . $db->f("idartlang");
		$db2->query($sql);
	}
	
	
	/* Perform after-end updates */ 
    $sql = "SELECT idartlang, idart, time_move_cat, time_target_cat, time_online_move FROM ".$cfg["tab"]["art_lang"]." WHERE NOW() > dateend AND timemgmt = 1";

    $db->query($sql);

    while ($db->next_record())
    {
    	$sql = "UPDATE ".$cfg["tab"]["art_lang"]." SET online = 0 WHERE idartlang = ".$db->f("idartlang");
    	$db2->query($sql);
    	if ($db->f("time_move_cat") == "1")
    	{
    		$sql = "UPDATE ".$cfg["tab"]["cat_art"]." SET idcat = ".$db->f("time_target_cat") . " WHERE idart = " . $db->f("idart");
    		$db2->query($sql);
    		
    		if ($db->f("time_online_move") == "1")
    		{
    			$sql = "UPDATE ".$cfg["tab"]["art_lang"] ." SET timemgmt = 0, online = 1 WHERE idart = ".$db->f("idart");
    		} else {
    			$sql = "UPDATE ".$cfg["tab"]["art_lang"] ." SET timemgmt = 0, online = 0 WHERE idart = ".$db->f("idart");
    		}
    		$db2->query($sql);
    	}
    }    
}

/**
 * Returns all available meta tag types
 *
 * @param none
 *
 * @author Timo A. Hummel <Timo.Hummel@4fb.de>
 * @copyright four for business AG 2003
 */
function conGetAvailableMetaTagTypes ()
{
	global $cfg;
	
	$db = new DB_Contenido;
	
	$sql = "SELECT idmetatype, metatype, fieldtype, maxlength
				FROM ".$cfg["tab"]["meta_type"];
				
	$db->query($sql);
	
	$metatag = array();
	
	while ($db->next_record())
	{
		$newentry["name"] = $db->f("metatype");
		$newentry["fieldtype"] = $db->f("fieldtype");
		$newentry["maxlength"] = $db->f("maxlength");
		$metatag[$db->f("idmetatype")] = $newentry;
	}
	
	return $metatag;
	
}

/**
 * Get the meta tag value for a specific article
 *
 * @param $idartlang ID of the article
 * @param $idmetatype Metatype-ID
 *
 * @author Timo A. Hummel <Timo.Hummel@4fb.de>
 * @copyright four for business AG 2003
 */
function conGetMetaValue($idartlang, $idmetatype)
{
	global $cfg;
	
	$db = new DB_Contenido;
	
	$sql = "SELECT metavalue
				FROM ".$cfg["tab"]["meta_tag"]
				." WHERE idartlang = '$idartlang'
					 AND idmetatype = '$idmetatype'";
				
	$db->query($sql);
	
	if ($db->next_record())
	{
		return $db->f("metavalue");
	} else {
		return "";
	}
	
}

/**
 * Set the meta tag value for a specific article
 *
 * @param $idartlang ID of the article
 * @param $idmetatype Metatype-ID
 * @param $value Value of the meta tag
 *
 * @author Timo A. Hummel <Timo.Hummel@4fb.de>
 * @copyright four for business AG 2003
 */
function conSetMetaValue($idartlang, $idmetatype, $value)
{
	global $cfg;
	
	$db = new DB_Contenido;
	$sql = "DELETE FROM ".$cfg["tab"]["meta_tag"]."
			WHERE idartlang = '$idartlang'
					 AND idmetatype = '$idmetatype'";
					 
	$db->query($sql);
	
	$nextid = $db->nextid($cfg["tab"]["meta_tag"]);
	
	$sql = "INSERT INTO ".$cfg["tab"]["meta_tag"]
				." SET idartlang = '$idartlang',
					   idmetatype = '$idmetatype',
					   idmetatag = '$nextid',
                       metavalue = '$value'";
				
	$db->query($sql);

}

/**
 * Returns the idartlang for a given article and language
 *
 * @param $idart ID of the article
 * @param $idlang ID of the language
 * @return mixed idartlang of the article or false if nothing was found
 *
 * @author Timo A. Hummel <Timo.Hummel@4fb.de>
 * @copyright four for business AG 2003
 */
function getArtLang ($idart, $idlang)
{
	global $cfg;
	
	$db = new DB_Contenido;
	$sql = "SELECT idartlang FROM " . $cfg["tab"]["art_lang"]. " WHERE ".
		   "idart = '$idart' AND idlang = '$idlang'";

	$db->query($sql);
	if ($db->next_record())
	{
		return $db->f("idartlang");
	} else {
		return false;
	}
}

function conCopyTemplateConfiguration ($srcidtplcfg)
{
	global $cfg;
	
	$sql = "SELECT idtpl FROM ".$cfg["tab"]["tpl_conf"] ." WHERE idtplcfg = '$srcidtplcfg'";
	$db = new DB_Contenido;
	$db->query($sql);
	
	if (!$db->next_record())
	{
		return false;	
	}
	
	$idtpl = $db->f("idtpl");
	
	$nextidtplcfg = $db->nextid($cfg["tab"]["tpl_conf"]);
	$created = date("Y-m-d H:i:s");
	
	$sql = "INSERT INTO ".$cfg["tab"]["tpl_conf"] . " (idtplcfg, idtpl, created) VALUES ('$nextidtplcfg', '$idtpl', '$created')";
	$db->query($sql);
	
	return $nextidtplcfg;
}

function conCopyContainerConf ($srcidtplcfg, $dstidtplcfg)
{
	global $cfg;
	
	$sql = "SELECT number, container ".$cfg["tab"]["container_conf"] . " WHERE idtplcfg = '$srcidtplcfg'";
	$db = new DB_Contenido;
	
	while ($db->next_record())
	{
		$val[$db->f("number")] = $db->f("container");
	}
	
	if (!is_array($val))
	{
		return false;
	}
	
	foreach ($val as $key => $value)
	{
		$nextidcontainerc = $db->nextid($cfg["tab"]["container_conf"]);
		
		$sql = "INSERT INTO ".$cfg["tab"]["container_conf"]." (idcontainerc, idtplcfg, number, container) VALUES ('$nextidcontainerc', '$dstidtplcfg', '$key', '$value')";
		$db->query($sql);	
	}
	
	return true;
	
}

function conCopyContent ($srcidartlang, $dstidartlang)
{
	global $cfg;
	
	$db = new DB_Contenido;
	
	$sql = "SELECT idtype, typeid, value, version, author FROM ".$cfg["tab"]["content"]." WHERE idartlang = '$srcidartlang'";
	
	$db->query($sql);
	
	$id = 0;
	
	while ($db->next_record())
	{
		$id++;
		$val[$id]["idtype"] = $db->f("idtype");
		$val[$id]["typeid"] = $db->f("typeid");
		$val[$id]["value"] = $db->f("value");
		$val[$id]["version"]  = $db->f("version");
		$val[$id]["author"] = $db->f("author");	
	} 	
	
	if (!is_array($val))
	{
		return false;
	}
	
	foreach ($val as $key => $value)
	{
		$nextid = $db->nextid($cfg["tab"]["content"]);
		$idtype = $value["idtype"];
		$typeid = $value["typeid"];
		$lvalue = $value["value"];
		$version = $value["version"];
		$author = $value["author"];
		$created = date("Y-m-d H:i:s");
		
		$sql = "INSERT INTO ".$cfg["tab"]["content"]
		      ." (idcontent, idartlang, idtype, typeid, value, version, author, created) ".
		      "VALUES ('$nextid', '$dstidartlang', '$idtype', '$typeid', '$lvalue', '$version', '$author', '$created')";
		      
		$db->query($sql);	
		
	}
}

function conCopyArtLang ($srcidart, $dstidart)
{
	global $cfg;
	
	$db = new DB_Contenido;
	$db2 = new DB_Contenido;
	
	$sql = "SELECT idartlang, idlang, idtplcfg, title, pagetitle, summary, 
			author, online, redirect, redirect, redirect_url,
			artsort, timemgmt, datestart, dateend, status, free_use_01,
			free_use_02, free_use_03, time_move_cat, time_target_cat,
			time_online_move, external_redirect, locked FROM
			".$cfg["tab"]["art_lang"]." WHERE idart = '$srcidart'";
	
	$db->query($sql);
		
	while ($db->next_record())
	{
		$nextid = $db2->nextid($cfg["tab"]["art_lang"]);
		/* Copy the template configuration */
		if ($db->f("idtplcfg") != 0)
		{
			$newidtplcfg = conCopyTemplateConfiguration($db->f("idtplcfg"));
		 	conCopyContainerConf($db->f("idtplcfg"), $newidtplcfg);	
		}
		
		conCopyContent($db->f("idartlang"), $nextid);		
		
		$idartlang = $nextid;
		$idart = $dstidart;
		$idlang = $db->f("idlang");
		$idtplcfg = $newidtplcfg;
		$title = "Copy of ".$db->f("title");
		$pagetitle = $db->f("pagetitle");
		$summary = $db->f("summary");
		$created = date("Y-m-d H:i:s");
		$author = $db->f("author");
		$online = $db->f("online");
		$redirect = $db->f("redirect");
		$redirecturl = $db->f("redirect_url");
		$artsort = $db->f("artsort");
		$timemgmt = $db->f("timemgmt");
		$datestart = $db->f("datestart");
		$dateend = $db->f("dateend");
		$status = $db->f("status");
		$freeuse01 = $db->f("free_use_01");
		$freeuse02 = $db->f("free_use_02");
		$freeuse03 = $db->f("free_use_03");
		$timemovecat = $db->f("time_move_cat");
		$timetargetcat = $db->f("time_target_cat");
		$timeonlinemove = $db->f("time_online_move");
		$externalredirect = $db->f("external_redirect");
		$locked = $db->f("locked");
		
		$sql = "INSERT INTO ".$cfg["tab"]["art_lang"]."
				(idartlang, idart, idlang, idtplcfg, title,
				pagetitle, summary, created, lastmodified,
				author, online, redirect, redirect_url,
				artsort, timemgmt, datestart, dateend, 
				status, free_use_01, free_use_02, free_use_03,
				time_move_cat, time_target_cat, time_online_move,
				external_redirect, locked) VALUES ('$idartlang',
				'$idart', '$idlang', '$idtplcfg', '$title',
				'$pagetitle', '$summary', '$created', '$created',
				'$author', '$online', '$redirect', '$redirecturl',
				'$artsort', '$timemgmt', '$datestart', '$dateend',
				'$status', '$freeuse01', '$freeuse02', '$freeuse03',
				'$timemovecat', '$timetargetcat', '$timeonlinemove',
				'$externalredirect', '$locked')";
		$db2->query($sql);	
	}			
}

function conCopyArticle ($srcidart)
{
	global $cfg;
	
	$db = new DB_Contenido;
	$db2 = new DB_Contenido;
	
	$sql = "SELECT idclient FROM ".$cfg["tab"]["art"] ." WHERE idart = '$srcidart'";
	
	$db->query($sql); 

	if (!$db->next_record())
	{
		return false;
	}
	
	$idclient = $db->f("idclient");
	$dstidart = $db->nextid($cfg["tab"]["art"]);
	
	$sql = "INSERT INTO ".$cfg["tab"]["art"]." (idart, idclient) VALUES ('$dstidart', '$idclient')";
	$db->query($sql);
	
	conCopyArtLang($srcidart, $dstidart);
	 
	/* Update category */
	$sql = "SELECT idcat FROM ".$cfg["tab"]["cat_art"]." WHERE idart = '$srcidart'";
	$db->query($sql);
	while ($db->next_record())
	{
		$nextid = $db2->nextid($cfg["tab"]["cat_art"]);
		
		$idcatart = $nextid;
		$idcat = $db->f("idcat");
		$idart = $dstidart;
		$is_start = 0;
		$status = $db->f("status");
		$createcode = 0;
		
		$sql = "INSERT INTO ".$cfg["tab"]["cat_art"]
			  ." (idcatart, idcat, idart, is_start, status,
					createcode) VALUES ('$idcatart', '$idcat',
                    '$idart', '$is_start', '$status', '$createcode')";
        $db->query($sql); 
		
	}
	
	return $dstidart;
	 
}

function conGetTopmostCat($idcat, $minLevel = 0)
{
    global $cfg, $client, $lang;

    $db = new DB_Contenido;
    
    $sql = "SELECT
                a.name AS name,
                a.idcat AS idcat,
                b.parentid AS parentid,
				c.level AS level
            FROM
                ".$cfg["tab"]["cat_lang"]." AS a,
                ".$cfg["tab"]["cat"]." AS b,
				".$cfg["tab"]["cat_tree"]." AS c
            WHERE
                a.idlang    = '".$lang."' AND
                b.idclient  = '".$client."' AND
                b.idcat     = '".$idcat."' AND
				c.idcat		= b.idcat AND
                a.idcat     = b.idcat";
                
    $db->query($sql);
    $db->next_record();

    $name       = $db->f("name");
    $parentid   = $db->f("parentid");
	$thislevel = $db->f("level");
	
    if ( $parentid != 0 && $thislevel >= $minLevel) {
        return conGetTopmostCat($parentid, $minLevel);
    } else {
		return $idcat;
	}
}
?>
