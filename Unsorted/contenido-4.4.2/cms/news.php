<?php
$targetidcatart = 20;

include ("config.php");
include ($contenido_path . "includes/config.php");
include ($cfg["path"]["contenido"].$cfg["path"]["includes"] . "cfg_sql.inc.php");
#include ($cfg["path"]["contenido"].$cfg["path"]["includes"] . "cfg_language_".$language.".inc.php");
include ($cfg["path"]["contenido"].$cfg["path"]["includes"] . "functions.general.php");

$oldpwd = getcwd();
chdir($cfg["path"]["contenido"].$cfg["path"]["cronjobs"]);
include ($cfg["path"]["contenido"].$cfg["path"]["includes"] . "pseudo-cron.inc.php");
chdir($oldpwd);



$db = new DB_Contenido;

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
        //load_client defined in frontend/config.php
        $client = $load_client;
}

// Initialize language
if (!isset($lang)) {
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

if (strlen($_GET["stop"]) == 32)
{
	$loc .= "&stop=".$_GET["stop"];
}

if (strlen($_GET["goon"]) == 32){
	$loc .= "&goon=".$_GET["goon"];
}

if (strlen($_GET["unsubscribe"]) == 32)
{
	$loc .= "&unsubscribe=".$_GET["unsubscribe"];
}

if (strlen($_GET["confirm"]) == 32)
{
	$loc .= "&confirm=".$_GET["confirm"];
}
header ("Location: ".$cfgClient[$client]["path"]["htmlpath"]."front_content.php?idcatart=$targetidcatart".$loc);

?>
