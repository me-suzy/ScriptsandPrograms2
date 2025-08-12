<?php
// ================================================
// SPAW PHP WYSIWYG editor control
// ================================================
// Configuration file for CONTENIDO
// ================================================
// Developed: Alan Mendelevich, alan@solmetra.lt
// Copyright: Solmetra (c)2003 All rights reserved.
// ------------------------------------------------
//                                www.solmetra.com
// ================================================
// Modified: Martin Horwath, horwath@opensa.org
// SPAW1.0.3 for Contenido 4.3.2.1, 2003-10-08 v0.1
// ================================================

   $contenido_path = "../../../../"; // CONTENIDO
  @include ("config.php"); // CONTENIDO
  @include ($contenido_path . "includes/config.php"); // CONTENIDO
  @include_once ($cfg["path"]["wysiwyg"] . 'class/lang.class.php'); // CONTENIDO
  @include_once ($cfg["path"]["contenido"].$cfg["path"]["includes"] . 'functions.i18n.php'); // CONTENIDO
  @include_once ($cfg["path"]["contenido"].$cfg["path"]["classes"] . 'class.user.php'); // CONTENIDO
   i18nInit($cfg["path"]["contenido"].$cfg["path"]["locale"], $belang);

if ($cfgClient["set"] != "set")
{
    $sql = "SELECT
                idclient,
                frontendpath,
                htmlpath,
                errsite_cat,
                errsite_art
            FROM
                ".$cfg["tab"]["clients"];

    $db = new DB_Contenido;
    $db->query($sql);

    while ($db->next_record())
    {
            $cfgClient["set"] = "set";
            $cfgClient[$db->f("idclient")]["path"]["frontend"] = $db->f("frontendpath");
            $cfgClient[$db->f("idclient")]["path"]["htmlpath"] = $db->f("htmlpath");
            $errsite_idcat[$db->f("idclient")] = $db->f("errsite_cat");
            $errsite_idart[$db->f("idclient")] = $db->f("errsite_art");

            $cfgClient[$db->f("idclient")]["images"] = $db->f("htmlpath")."images/";
            $cfgClient[$db->f("idclient")]["upload"] = "upload/";

            $cfgClient[$db->f("idclient")]["htmlpath"]["frontend"] = $cfgClient[$db->f("idclient")]["path"]["htmlpath"];
            $cfgClient[$db->f("idclient")]["upl"]["path"] = $cfgClient[$db->f("idclient")]["path"]["frontend"]."upload/";
            $cfgClient[$db->f("idclient")]["upl"]["htmlpath"] = $cfgClient[$db->f("idclient")]["htmlpath"]["frontend"]."upload/";
            $cfgClient[$db->f("idclient")]["upl"]["frontendpath"] = "upload/";
            $cfgClient[$db->f("idclient")]["css"]["path"] = $cfgClient[$db->f("idclient")]["path"]["frontend"] . "css/";
            $cfgClient[$db->f("idclient")]["js"]["path"] = $cfgClient[$db->f("idclient")]["path"]["frontend"] . "js/";

    }
}

// directory where spaw files are located
$spaw_root = $cfg['path']['wysiwyg'];
$spaw_dir = $cfg['path']['wysiwyg_html'];
$spaw_base_url = $cfgClient[$client]["path"]["htmlpath"].$cfgClient[$client]["upload"];

$spaw_default_toolbars = 'default';
$spaw_default_theme = 'default';
$langs = i18nGetAvailableLanguages(); // CONTENIDO
$spaw_default_lang = $langs[$belang][4]; // CONTENIDO
$spaw_default_css_stylesheet = $spaw_dir.'wysiwyg.css';

// add javascript inline or via separate file
$spaw_inline_js = false;

// use active toolbar (reflecting current style) or static
$spaw_active_toolbar = true;

// style data
$user = new User;
$user->loadUserByUserID($auth->auth["uid"]);
$styles = explode(";",$user->getUserProperty("wysiwyg","spaw-styles",true));

switch ($type)
{
	case "CMS_HTML":
			$editorheight = $user->getUserProperty("wysiwyg","spaw-height-html",true);
			break;
	case "CMS_HTMLHEAD":
			$editorheight = $user->getUserProperty("wysiwyg","spaw-height-head",true);
			break;
	default:
			$editorheight = $user->getUserProperty("wysiwyg","spaw-height",true);
			break;
}

echo $editorheight;

if (!is_numeric($editorheight))
{
	$editorheight = 350;
}

if (is_array($styles))
{
    foreach ($styles as $style)
    {
    	$spaw_dropdown_data['style'][$style] = $style;
    }
} else {
	/* Default styles */
    $spaw_dropdown_data['style']['default'] = 'Normal';
    $spaw_dropdown_data['style']['style1'] = 'Style No1';
    $spaw_dropdown_data['style']['style2'] = 'Style No2';
}

$toolbar_mode = $user->getUserProperty("wysiwyg","spaw-toolbar-mode",true);

if ($toolbar_mode == false)
{
	$toolbar_mode = "default";
}
//$css_stylesheet = $cfgClient[$client]["path"]["htmlpath"].$user->getUserProperty("wysiwyg","spaw-stylesheet-file");

$spaw_dropdown_data['font']['Arial, Helvetica, Verdana, Sans Serif'] = 'Arial';
$spaw_dropdown_data['font']['Courier, Courier New'] = 'Courier';
$spaw_dropdown_data['font']['Tahoma, Verdana, Arial, Helvetica, Sans Serif'] = 'Tahoma';
$spaw_dropdown_data['font']['Times New Roman, Times, Serif'] = 'Times';
$spaw_dropdown_data['font']['Verdana, Tahoma, Arial, Helvetica, Sans Serif'] = 'Verdana';

$spaw_dropdown_data['fontsize']['1'] = '1';
$spaw_dropdown_data['fontsize']['2'] = '2';
$spaw_dropdown_data['fontsize']['3'] = '3';
$spaw_dropdown_data['fontsize']['4'] = '4';
$spaw_dropdown_data['fontsize']['5'] = '5';
$spaw_dropdown_data['fontsize']['6'] = '6';

$spaw_dropdown_data['paragraph']['<P>'] = 'Normal';
$spaw_dropdown_data['paragraph']['<H1>'] = 'Heading 1';
$spaw_dropdown_data['paragraph']['<H2>'] = 'Heading 2';
$spaw_dropdown_data['paragraph']['<H3>'] = 'Heading 3';
$spaw_dropdown_data['paragraph']['<H4>'] = 'Heading 4';
$spaw_dropdown_data['paragraph']['<H5>'] = 'Heading 5';
$spaw_dropdown_data['paragraph']['<H6>'] = 'Heading 6';

// allowed extentions for image files
$spaw_valid_imgs = array('gif', 'jpg', 'jpeg', 'png');

$spaw_debug = "Debug:<br>spaw_root:".$spaw_root."<br>spaw_base_url:".$spaw_base_url."<br>spaw_dir:".$spaw_dir
?>
