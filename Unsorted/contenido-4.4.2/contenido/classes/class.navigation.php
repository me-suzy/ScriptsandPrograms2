<?php

/**
 * class Contenido_Navigation
 *
 * Class for the dynamic Contenido
 * backend navigation.
 *
 * @author Jan Lengowski <Jan.Lengowski@4fb.de>
 * @copyright four for business AG <http://www.4fb.de>
 *
 */

cInclude("classes", "class.lang.php");

class Contenido_Navigation {

    /**
     * @var debug
     */
    var $debug = 0;

    /**
     * array storing all data
     * @var array
     */
    var $data = array();

    /**
     * Constructor
     */
    function Contenido_Navigation() {

        global $db, $xml, $cfg, $sess, $cfgPath, $perm, $con_cfg, $belang;
        $db2     = new DB_Contenido;
        $plugxml = new XML_Doc;

        # Load language file
        if ($xml->load($cfg['path']['xml'] . $cfg['lang'][$belang]) == false)
        {
        	if ($xml->load($cfg['path']['xml'] . 'lang_en_US.xml') == false)
        	{
        		die("Unable to load any XML language file");
        	}
        }

        # Load main items
        $sql = "SELECT idnavm, location FROM ".$cfg["tab"]["nav_main"]." ORDER BY idnavm";

        $db->query($sql);

        # Loop result and build array
        while ( $db->next_record() ) {

            /* Extract names from the XML document.
			   If a ";" is found entry is from a plugin ->
			   exlode location, first is xml file path,
			   second is xpath location in xml file */
            if ( strstr($db->f('location'), ';') ) {

                $locs = explode(";", $db->f("location"));

                $file 	= trim($locs[0]);
                $xpath  = trim($locs[1]);

                $plugxml->load($cfg["path"]["plugins"].$file);
                $main = $plugxml->valueOf($xpath);

            } else {
                $main = $xml->valueOf($cfgPath['xmlroot'] . $db->f('location'));
            }

            # Build data array
            $this->data[$db->f('idnavm')] = array($main);

            $sql = "SELECT
                        a.location AS location,
                        b.name AS area,
                        b.relevant
                    FROM
                        ".$cfg["tab"]["nav_sub"]." AS a,
                        ".$cfg["tab"]["area"]." AS b
                    WHERE
                        a.idnavm 	= '".$db->f('idnavm')."' AND
                        a.level 	= '0' AND
                        b.idarea 	= a.idarea AND
                        a.online 	= '1' AND
                        b.online 	= '1'
                    ORDER BY
                        a.idnavs";

            $db2->query($sql);

            while ( $db2->next_record() ) {

                $area = $db2->f('area');

                if ($perm->have_perm_area_action($area) || $db2->f('relevant') == 0){

                   if ( strstr($db2->f('location'), ';') ) {

                       $locs = explode(";", $db2->f("location"));

                       $file 	= trim($locs[0]);
                       $xpath   = trim($locs[1]);

                       $plugxml->load($cfg["path"]["plugins"].$file);
                       $name = $plugxml->valueOf($xpath);

                   } else {

                      $name = $xml->valueOf($cfgPath['xmlroot'] . $db2->f('location'));

                   }

                   $this->data[$db->f('idnavm')][] = array($name, $area);

                }

            } // end while

        } // end while

        # debugging information
        if ($this->debug) {
            echo '<pre>';
            print_r($this->data);
            echo '</pre>';
        }

    } # end function

    #
    # Method thats builds the
    # Contenido header document
    #
    function buildHeader($lang) {

        global $cfg, $sess, $client, $changelang, $auth;

        $main = new Template;
        $sub  = new Template;

        $cnt = 0;
        $t_sub = '';

        # Loop data/navigation array
        foreach ($this->data as $id => $item)
        {
            # First image is different from
            # the following ones
            $t_img = (1 == $id) ? 'border_start_light.gif' : 'border_light_light.gif';

            # reset subtemplate
            $sub->reset();

			$genSubMenu = false;
            # loop the array
            foreach ($item as $key => $value)
            {
                # check for an array -> submenu item
                if (is_array($value))
                {
                    $sub->set('s', 'SUBID', 'sub_'.$id);
                    $sub->set('d', 'SUBIMGID', 'img_'.$id.'_'.$sub->dyn_cnt);
                    $sub->set('d', 'CAPTION', '<a class="sub" target="content" href="'.$sess->url("frameset.php?area=$value[1]").'" onclick="imgOn(\''.'img_'.$id.'_'.$sub->dyn_cnt.'\', this)">'.$value[0].'</a>');
                    $sub->next();

                    $genSubMenu = true;
                }
            }

            if ($genSubMenu == true)
            {
                # first entry in array is a main menu item
                $main->set('d', 'IMAGE', $t_img);
                $main->set('d', 'MIMGID', 'mimg_'.$id);
                $main->set('d', 'OPTIONS', 'style="background-color:#A9AEC2" id="'.$id.'"');
                $main->set('d', 'CAPTION', '<a class="main" onclick="show(\'sub_'.$id.'\', this)" href="javascript://">'.$item[0].'</a>');
                $main->next();

                $numSubMenus ++;

            } else {
                # first entry in array is a main menu item
            }

            # generate a sub menu item.
            $t_sub .= $sub->generate($cfg['path']['templates'] . $cfg['templates']['submenu'], true);
            $cnt ++;
        }

        $main->set('s', 'RIGHTBORDER', 'border_light_dark');
        $main->set('s', 'SUBMENUS', $t_sub);

        $main->set('s', 'MYCONTENIDO', '<a class="main" target="content" href="' .$sess->url("frameset.php?area=mycontenido_overview&menuless=1&frame=4").'"><img src="'.$cfg["path"]["contenido_fullhtml"].$cfg["path"]["images"].'my_contenido.gif" border="0" alt="MyContenido" title="MyContenido"></a>');
        $main->set('s', 'INFO', '<a class="main" target="content" href="' .$sess->url("frameset.php?area=info&menuless=1&frame=4").'"><img src="'.$cfg["path"]["contenido_fullhtml"].$cfg["path"]["images"].'info.gif" border="0" alt="Info" title="Info"></a>');
        $main->set('s', 'LOGOUT', $sess->url("logout.php"));
//         $main->set('s', 'HELP', '<a class="main" target="content" href="' .$sess->url("frameset.php?area=mycontenido&menuless=1&frame=4").'"><img src="'.$cfg["path"]["contenido_fullhtml"].$cfg["path"]["images"].'hilfe.gif" border="0" alt="Hilfe" title="Hilfe"></a>');

        $main->set('s', 'HELP', '');
        $main->set('s', 'KILLPERMS', '<a class="main" target="header" href="' . $sess->url("header.php?killperms=1").'"><img src="'.$cfg["path"]["contenido_fullhtml"].$cfg["path"]["images"].'mycon.gif" border="0" alt="Reload Permission" title="Reload Permissions"></a>');

		$tpl = new Template();
        $classuser = new User();
        $classclient = new Client();

        $tpl->set('s', 'NAME', 'changelang');
        $tpl->set('s', 'CLASS', 'text_medium');
        $tpl->set('s', 'OPTIONS', 'onchange="changeContenidoLanguage(this.value)"');

		$availableLanguages = new Languages;
		$availableLanguages->select();
		$db = new DB_Contenido;

		if ($availableLanguages->count() > 0)
		{
			while ($myLang = $availableLanguages->nextAccessible())
			{
				$key = $myLang->get("idlang");
				$value = $myLang->get("name");

				/* I want to get rid of such silly constructs
                   very soon :) */

               $sql = "SELECT idclient FROM ".$cfg["tab"]["clients_lang"]." WHERE
						idlang = '$key'";

			   $db->query($sql);

			   if ($db->next_record())
			   {
			   	  if ($db->f("idclient") == $client)
			   	  {
                if ($key == $lang) {
                	$tpl->set('d', 'SELECTED', 'selected');
            	} else {
                    $tpl->set('d', 'SELECTED', '');
            	}

                $tpl->set('d', 'VALUE', $key);
            	$tpl->set('d', 'CAPTION', $value.' ('.$key.')');
            	$tpl->next();

			   	  }
			   }

			}
		} else {
            $tpl->set('d', 'VALUE', 0);
            $tpl->set('d', 'CAPTION', '-- Sprache anlegen --');
            $tpl->next();
		}


        $select = $tpl->generate($cfg['path']['templates'] . $cfg['templates']['generic_select'],true);

        $main->set('s','ACTION', $sess->url("index.php"));
        $main->set('s', 'LANG', $select);
        $main->set('s', 'CHOSENCLIENT', "<b>".i18n("Client").":</b> ".$classclient->getClientName($client)." (".$client.")");
        $main->set('s', 'CHOSENUSER', "<b>".i18n("User").":</b> ".$classuser->getRealname($auth->auth["uid"]));
        $main->set('s', 'SID', $sess->id);

        $main->set('s', 'MAINLOGINLINK', $sess->url("main.php?area=login&frame=1"));
        # generate header
        $main->generate($cfg['path']['templates'] . $cfg['templates']['header']);

    } # end function

} # end class

?>
