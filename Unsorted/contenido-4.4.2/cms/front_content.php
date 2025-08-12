<?php

/******************************************
* File      :   front_content.php
* Project   :   Contenido
* Descr     :   Contenido Frontend
*
* Author    :   Olaf Niemann,
*               Jan Lengowski
*
* Created   :   21.01.2003
* Modified  :   21.01.2003
*
* © four for business AG
******************************************/
include_once ("config.php");
include_once ($contenido_path . "includes/config.php");
cInclude("includes", "cfg_sql.inc.php");
cInclude("includes", "functions.general.php");
cInclude("includes", "functions.i18n.php");
cInclude("includes", "functions.tpl.php");
cInclude("includes", "functions.con.php");
cInclude("includes", "functions.mod.php");
cInclude("includes", "functions.api.php");
cInclude("classes", 'class.inuse.php');
cInclude("classes", 'class.user.php');
cInclude("classes", 'class.notification.php');
cInclude("classes", 'class.table.php');

/* Include cronjob-Emulator */
$oldpwd = getcwd();
chdir($cfg["path"]["contenido"].$cfg["path"]["cronjobs"]);
cInclude("includes", "pseudo-cron.inc.php");
chdir($oldpwd);


if ($contenido)
{

    //Backend
    page_open(array('sess' => 'Contenido_Session',
                    'auth' => 'Contenido_Challenge_Crypt_Auth',
                    'perm' => 'Contenido_Perm'));
                    
} else {

    //Frontend
    page_open(array('sess' => 'Contenido_Frontend_Session',
                    'auth' => 'Contenido_Frontend_Challenge_Crypt_Auth',
                    'perm' => 'Contenido_Perm'));
                        
}

$db = new DB_Contenido;

$sess->register("cfgClient");
$sess->register("errsite_idcat");
$sess->register("errsite_idart");
$sess->register("encoding");

if ($cfgClient["set"] != "set")
{
    rereadClients();
}

        $sql = "SELECT
				idlang,
                encoding
            FROM
            ".$cfg["tab"]["lang"];
            
        $db->query($sql);
        
        while ($db->next_record())
        {
        	$encoding[$db->f("idlang")] = $db->f("encoding");
        }
        
// Sprache wechseln
if (isset($changelang)) $lang = $changelang;

// Client wechseln
if (isset($changeclient)){
    $client = $changeclient;
    unset($lang);
}

// Client initialisieren
if (!isset($client)) {
        $sess->register("client");
        //load_client defined in frontend/config.php
        $client = $load_client;
}

// Initialize language
if (!isset($lang)) {
    $sess->register("lang");
    //if is an entry load_lang in frontend/config.php use it,    else use the first language of this client
    if(isset($load_lang)){
        //load_client is set in    frontend/config.php
        $lang = $load_lang;

    }else{

        $sql = "SELECT
                    A.idlang
                FROM
                    ".$cfg["tab"]["clients"]." AS A,
                    ".$cfg["tab"]["lang"]." AS B
                WHERE
                    idclient='$client' AND
                    A.idlang=B.idlang AND
                    B.active='1'
                LIMIT
                    0,1";

        $db->query($sql);
        $db->next_record();
        
        $lang = $db->f("idlang");

    }
}

if (isset($username))
{
  $auth->login_if(true);
}

if (isset($logout))
{
  $auth->logout(true);
  $auth->unauth(true);
  $auth->auth["uname"] = "nobody";
}

//  Fehlerseite
$errsite = "Location: front_content.php?client=$client&idcat=".$errsite_idcat[$client]."&idart=".$errsite_idart[$client]."&lang=$lang&error=1";

if (!$idcatart) {

        if (!$idart) {

                if (!$idcat) {

                        $sql = "SELECT
                                    idart,
                                    B.idcat
                                FROM
                                    ".$cfg["tab"]["cat_art"]." AS A,
                                    ".$cfg["tab"]["cat_tree"]." AS B,
                                    ".$cfg["tab"]["cat"]." AS C
                                WHERE
                                    A.idcat=B.idcat AND
                                    B.idcat=C.idcat AND
                                    is_start='1' AND
                                    idclient='$client'
                                ORDER BY
                                    idtree ASC";
                                    
                        $db->query($sql);

                        if ($db->next_record()) {

                                $idart = $db->f("idart");
                                $idcat = $db->f("idcat");
                                
                        } else {
                                if ($contenido)
                                    die (i18n("No start article for this category"));
                                else
                                {
                                	if ($error == 1)
                                	{
                                		echo "Fatal error: Could not display error page. Error to display was: 'No start article in this category'";
                                	} else {
                                		header ($errsite);
                                	}
                                }
                        }
                        
                } else {

                        $sql = "SELECT idart FROM ".$cfg["tab"]["cat_art"]." WHERE idcat='".$idcat."' AND is_start='1'";
                        $db->query($sql);
                        
                        if ($db->next_record()) {
                                $idart = $db->f("idart");
                        } else {
                                //im backend richtige Fehlermeldung
                                if ($contenido)
                                    die (i18n("No start article for this category"));
                                else
                                {
                                	if ($error == 1)
                                	{
                                		echo "Fatal error: Could not display error page. Error to display was: 'No start article in this category'";
                                	} else {
                                    header ($errsite);
                                	}
                                }
                        }

                }
        }
        
} else {
	
    $sql = "SELECT idcat, idart FROM ".$cfg["tab"]["cat_art"]." WHERE idcatart='".$idcatart."'";

    $db->query($sql);
    $db->next_record();

    $idcat = $db->f("idcat");
    $idart = $db->f("idart");

}

/* Get idcatart */
if ( 0 != $idart && 0 != $idcat ) {

    $sql = "SELECT idcatart FROM ".$cfg["tab"]["cat_art"]." WHERE idart = '".$idart."' AND idcat = '".$idcat."'";

    $db->query($sql);
    $db->next_record();
    
    $idcatart = $db->f("idcatart");


}


/* If user hast contenido-
   backend rights. */
if ($contenido) {

    $perm->load_permissions();

    /* Change mode edit / view */
    if (isset($changeview)) {
        $sess->register("view");
        $view = $changeview;
    }
    
    	$idartlang = getArtLang($idart, $lang);
    	
	$col = new InUseCollection;
	
	/* Remove all own marks */
	$col->removeSessionMarks($sess->id);
	
	if (($obj = $col->checkMark("article", $idartlang)) === false)
	{
		$col->markInUse("article", $idartlang, $sess->id, $auth->auth["uid"]);
		$inUse = false;
		$disabled = "";						
	} else {
		
		$vuser = new User;
		$vuser->loadUserByUserID($obj->get("userid"));
		$inUseUser = $vuser->getField("username");
		$inUseUserRealName = $vuser->getField("realname");
		
		$message = sprintf(i18n("Article is in use by %s (%s)"), $inUseUser, $inUseUserRealName);
		
		if (!is_object($notification))
		{
			$notification = new Contenido_Notification;
		}
		$notification->displayNotification("warning", $message);			
		$inUse = true;
    	$disabled = 'disabled="disabled"';			
	}
    
    $sql = "SELECT locked FROM ".$cfg["tab"]["art_lang"]." WHERE idart='".$idart."' AND idlang = '".$lang."'";
    $db->query($sql);
    $db->next_record();
    $locked = $db->f("locked");	
	if ($locked == 1)
	{
		$inUse = true;
		$disabled = 'disabled="disabled"';
	}
    /* Check if the user
       has permission to edit
       articles in this category */
    if ($perm->have_perm_area_action_item("con_editcontent","con_editart", $idcat) && $inUse == false) {

            /* Create buttons for editing */
            $edit_preview = '<table cellspacing="0" cellpadding="4" border="0">';

            if ( $view == "edit" ) {

                $edit_preview = '   <tr>
                                        <td width="18">
                                            <a title="Preview" style="font-family: Verdana; font-size: 10px; color: #000000; text-decoration: none" href="'.$sess->url("front_content.php?changeview=prev&idcat=$idcat&idart=$idart").'"><img src="'.$cfg["path"]["contenido_fullhtml"].$cfg["path"]["images"].'but_preview.gif" alt="Preview" title="Preview" border="0"></a>
                                        </td>
                                        <td width="18">
                                            <a title="Preview" style="font-family: Verdana; font-size: 10px; color: #000000; text-decoration: none" href="'.$sess->url("front_content.php?changeview=prev&idcat=$idcat&idart=$idart").'">Preview</a>
                                        </td>
                                    </tr>';

            } else {

                $edit_preview = '   <tr>
                                        <td width="18">
                                            <a title="Preview" style="font-family: Verdana; font-size: 10px; color: #000000; text-decoration: none" href="'.$sess->url("front_content.php?changeview=edit&idcat=$idcat&idart=$idart").'"><img src="'.$cfg["path"]["contenido_fullhtml"].$cfg["path"]["images"].'but_edit.gif" alt="Preview" title="Preview" border="0"></a>
                                        </td>
                                        <td width="18">
                                            <a title="Preview" style="font-family: Verdana; font-size: 10px; color: #000000; text-decoration: none" href="'.$sess->url("front_content.php?changeview=edit&idcat=$idcat&idart=$idart").'">Edit</a>
                                        </td>
                                    </tr>';

            }


          /* Display articles */
          $sql = "SELECT idart,is_start FROM ".$cfg["tab"]["cat_art"]." WHERE idcat='".$idcat."' ORDER BY idart";

          $db->query($sql);
          
          $a = 1;

          $edit_preview .= '<tr><td colspan="2"><table cellspacing="0" cellpadding="2" border="0"></tr><td style="font-family: verdana; font-size:10; color:#000000; text-decoration:none">Articles in category:<br>';
          
          while ( $db->next_record() && ($db->affected_rows() != 1) ) {

                $class="font-family:'Verdana'; font-size:10; color:#000000; text-decoration: underline; font-weight:normal";
                if ( !isset($idart) ) {
                    if($db->f("is_start")=="1"){
                       $class = "font-family: verdana; font-size:10; color:#000000; text-decoration: underline ;font-weight:bold";
                    }
                }else{
                    if($idart==$db->f("idart")){
                       $class = "font-family: verdana; font-size:10; color:#000000; text-decoration: underline; font-weight:bold";
                    }
                }

                $edit_preview .= "<a style=\"$class\" href=\"".$sess->url("front_content.php?idart=".$db->f("idart")."&idcat=$idcat")."\">$a</a>&nbsp;";
                $a++;
          }
          
          $edit_preview .= '</td></tr></table></td></tr></table>';

    }

} // end if $contenido


/* If mode is 'edit' and user has permissoion
   edit articles in this idcat  */
if ( $inUse == false && $view == "edit" && ($perm->have_perm_area_action_item("con_editcontent", "con_editart", $idcat) ) ) {

    cInclude("includes", "functions.tpl.php");
    cInclude("includes", "functions.con.php");
    include($cfg["path"]["contenido"].$cfg["path"]["includes"]."include.con_editcontent.php");
    
} else {

    /* Mark submenuitem 'Preview' */
    if ($contenido) {
        $markscript = markSubMenuItem(4, true);
    }
    
    /* 'mode' is preview or article displayed
       in the front-end */
        
    $sql = "SELECT
                createcode
            FROM
                ".$cfg["tab"]["cat_art"]."
            WHERE
                idcat = '".$idcat."' AND
                idart = '".$idart."'";

    $db->query($sql);
    $db->next_record();

    /* Check if code is expired,
       create new code if needed */
    if ( $db->f("createcode") == 0 && $force == 0) {
        	$sql = "SELECT code FROM ".$cfg["tab"]["code"]." WHERE idcatart = '".$idcatart."' AND idlang = '".$lang."'";
            $db->query($sql);
            
			   if ($db->num_rows() == 0)
            {
			   		conGenerateCode($idcat, $idart, $lang, $client);
        	$sql = "SELECT code FROM ".$cfg["tab"]["code"]." WHERE idcatart = '".$idcatart."' AND idlang = '".$lang."'";
            $db->query($sql);
			   }
            
               if ( $db->next_record() ) {
            	$code = $db->f("code");        
               } else {
                  if ( $contenido )
                        $code = "echo \"No code available.\";";
            else            
            {
                  	if ($error == 1)
            	{
            		echo "Fatal error: Could not display error page. Error to display was: 'No code available'";	
                    } else {
            		header ($errsite);
            	}
            }
        
        }

    } else {

        // echo "creating code for idcat:$idcat, idart:$idart, lang:$lang, client:$client<br><br>";

        cInclude("includes", "functions.con.php");
        cInclude("includes", "functions.tpl.php");
        cInclude("includes", "functions.mod.php");
        
        conGenerateCode($idcat, $idart, $lang, $client);

        $sql = "SELECT code FROM ".$cfg["tab"]["code"]." WHERE idcatart = '".$idcatart."' AND idlang = '".$lang."'";
        
        $db->query($sql);
        $db->next_record();
        
      	$code = $db->f("code");
    }
    
    /*  Add mark Script to code */
    $code = preg_replace("/<\/head>/i", "$markscript\n</head>", $code);

    /* Check if category is public */
    $sql = "SELECT public FROM ".$cfg["tab"]["cat_lang"]." WHERE idcat='".$idcat."' AND idlang='".$lang."'";

    $db->query($sql);
    $db->next_record();

    $public = $db->f("public");

    if ($public == 0 && $auth->auth["uid"] == "nobody") {
    	
		$sql = "SELECT user_id, value FROM " .$cfg["tab"]["user_prop"]." WHERE type='frontend' and name='allowed_ip'";
		$db->query($sql);
		
		while ($db->next_record())
		{
			$user_id = $db->f("user_id");
		
			$range = $db->f("value");
			$slash = strpos($range, "/");

			if ($slash == false)
			{
				$netmask = "255.255.255.255";
				$network = $range;
			} else {
				$network = substr($range, 0, $slash);
				$netmask = substr($range, $slash+1, strlen($range)-$slash-1);
			}
	
			if (IP_match($network,$netmask,$_SERVER["REMOTE_ADDR"]))
			{
								$sql = "SELECT idright 
					FROM ". $cfg["tab"]["rights"]. " AS A,
						 ". $cfg["tab"]["actions"] . " AS B,
						 ". $cfg["tab"]["area"] ." AS C
					 WHERE B.name = 'front_allow' AND C.name = 'str' AND A.user_id = '".$user_id."' AND A.idcat = '$idcat'
							AND A.idarea = C.idarea AND B.idaction = A.idaction";
							
					$db2 = new DB_Contenido;
					$db2->query($sql);
			
					if ($db2->num_rows() > 0)
					{
						$auth->auth["uid"] = $user_id;
						$validated = 1;
						
					}
			}
		}
		
		if ($validated != 1)
		{
			$allow = false;
			$sql = "SELECT idright 
					FROM ". $cfg["tab"]["rights"]. " AS A,
						 ". $cfg["tab"]["actions"] . " AS B,
						 ". $cfg["tab"]["area"] ." AS C
					 WHERE B.name = 'front_allow' AND C.name = 'str' AND A.user_id = '".$auth->auth["uid"]."' AND A.idcat = '$idcat'
							AND A.idarea = C.idarea AND B.idaction = A.idaction";
			$db2 = new DB_Contenido;
			$db2->query($sql);
			
			if (!$db2->next_record()) { $allow = true; }
			$auth->login_if($allow);
		}
    }

	/* Sanity: If the stat table doesn't contain an entry, create one */
	$sql = "SELECT idcatart FROM ".$cfg["tab"]["stat"]." WHERE idcatart = '$idcatart'";
	$db->query($sql);
	
	if ($db->next_record())
	{
    	/* Update the statistics. */
    	$sql = "UPDATE ".$cfg["tab"]["stat"]." SET visited = visited + 1 WHERE idcatart = '".$idcatart."' AND idclient = '$client' AND idlang = '".$lang."'";
    	$db->query($sql);
	} else {
		/* Insert new record */
		$next = $db->nextid($cfg["tab"]["stat"]);
		$sql = "INSERT INTO ".$cfg["tab"]["stat"]." (visited, idcatart, idlang, idstat, idclient) VALUES ('1', '$idcatart', '$lang', '$next', '$client')";
		$db->query($sql);
	}

    /* Check for redirect.
       Properties for the redirect are
       choosen in the article properties */
    $sql = "SELECT is_start FROM ".$cfg["tab"]["cat_art"]." WHERE idcatart='".$idcatart."'";
    $db->query($sql);
    $db->next_record();
    $isstart = $db->f("is_start");
    
    $sql = "SELECT timemgmt FROM ".$cfg["tab"]["art_lang"]." WHERE idart='".$idart."' AND idlang = '".$lang."'";
    $db->query($sql);
    $db->next_record();
    
    if (($db->f("timemgmt") == "1") && ($isstart != 1))
    {
    	$sql = "SELECT online, redirect, redirect_url FROM ".$cfg["tab"]["art_lang"]." WHERE idart='".$idart."' AND idlang = '".$lang."' AND NOW() > datestart AND NOW() < dateend";
    } else {
    	$sql = "SELECT online, redirect, redirect_url FROM ".$cfg["tab"]["art_lang"]." WHERE idart='".$idart."' AND idlang = '".$lang."'";
    }

    $db->query($sql);
    $db->next_record();
    
    $online       = $db->f("online");
    $redirect     = $db->f("redirect");
    $redirect_url = $db->f("redirect_url");
	
    if ( $online ) {

        if($redirect == '1' && $redirect_url != ''){
            header ("Location: $redirect_url");
            exit;

        } else {
        	eval("?>\n".$code."\n<?php\n");
        	
     }
        
    } else {

        if ($contenido) {
            eval("?>\n".$code."\n<?php\n");

        } else {
           if ($error == 1)
           {
           		echo "Fatal error: Could not display error page. Error to display was: 'No contenido session variable set. Probable error cause: Start article in this category is not set on-line.'";
           } else {
           	   		header ($errsite);
           }
        }

        
    }

}

function IP_match($network, $mask, $ip) {

bcscale(3);
 $ip_long=ip2long($ip);
$mask_long=ip2long($network);

 #
 # Convert mask to divider
 #
 if (ereg("^[0-9]+$",$mask)) {
 /// 212.50.13.0/27 style mask (Cisco style)
  $divider=bcpow(2,(32-$mask));
 } else {
 /// 212.50.13.0/255.255.255.0 style mask
   $xmask=ip2long($mask);
  if ( $xmask < 0 ) $xmask=bcadd(bcpow(2,32),$xmask);
  $divider=bcsub(bcpow(2,32),$xmask);
 }
 #
 # Test is IP within specified mask
 #
 if ( floor(bcdiv($ip_long,$divider)) == floor(bcdiv($mask_long,$divider)) ){
 # match - this IP is within specified mask
   return true;
 } else {
 # fail - this IP is NOT within specified mask
   return false;
 }
}


page_close();
?>
