<?php

/******************************************
* File      :   include.js_edit_form.php
*
* Author    :   Olaf Niemann
* Created   :   22.04.2003
* Modified  :   22.04.2003
*
* © four for business AG
******************************************/

$tpl->reset();

$path = $cfgClient[$client]["js"]["path"];



if (!$perm->have_perm_area_action($area, $action))
{
    $notification->displayNotification("error", i18n("Permission denied"));
} else {

        if ( $action == "js_create" && $final != 1)
        {
            $tpl->set('s', 'ACTION', $sess->url("main.php?area=$area&frame=$frame&action=js_create&final=1"));
            $tpl->set('s', 'PATH', $path);
            $tpl->set('s', 'FILENAME', "");
            $tpl->set('s', 'CODE', '');

            $tpl->generate($cfg['path']['templates'] . $cfg['templates']['js_new_form']);


        }

        if ( $action == "js_create" && $final == 1)
        {
            # Security checks
            # Check 1: If filename does not end with ".css", append .css
            if (strcmp(substr($file, strlen($file)-3,3),".js"))
            {
                $file .= ".js";
            }
            # Check 2: Don't allow the file to be placed anywhere else as in
            #          the specified CSS path, strip anything before the last /
            $lastslashpos = strrchr($file, "/");

            if ($lastslashpos)
            {
                $file = substr($lastslashpos, strlen($file)-$lastslashpos);
            }

            # Create the file
            touch($path.$file);

            jsEdit($file, $code);
        }

        if ( isset($file) ) {

            if (!strrchr($file, "/"))
            {

                if (file_exists($path.$file))
                {
                    $code = implode ('', file($path.$file));

                    # Set static pointers
                    $tpl->set('s', 'ACTION',    $sess->url("main.php?area=$area&frame=$frame&action=js_edit"));
                    $tpl->set('s', 'FILENAME', $file);
                    $tpl->set('s', 'CODE', $code);
            
                    $tpl->generate($cfg['path']['templates'] . $cfg['templates']['js_edit_form']);
                } else {
                    $notification->displayNotification("error", "Datei existiert nicht oder ist nicht lesbar");
                }
            } else {
                    $notification->displayNotification("error", "Ung&uuml;ltige Datei");
            }

        } else {
        $tpl->reset();
        $tpl->set('s', 'CONTENTS', '');
        $tpl->generate($cfg['path']['templates'] . $cfg['templates']['blank']);
}

}

?>
