<?php

/******************************************
* File      :   include.style_edit_form.php
*
* Author    :   Olaf Niemann
* Created   :   22.04.2003
* Modified  :   22.04.2003
*
* © four for business AG
******************************************/

$tpl->reset();

if (!$perm->have_perm_area_action($area, $action))
{
    $notification->displayNotification("error", i18n("Permission denied"));
} else {

        $path = $cfgClient[$client]["css"]["path"];
        if ( $action == "style_create" && $final != 1)
        {
            $tpl->set('s', 'ACTION', $sess->url("main.php?area=$area&frame=$frame&action=style_create&final=1"));
            $tpl->set('s', 'PATH', $path);
            $tpl->set('s', 'FILENAME', "");
            $tpl->set('s', 'CODE', '');

            $tpl->generate($cfg['path']['templates'] . $cfg['templates']['style_new_form']);


        }

        if ( $action == "style_create" && $final == 1)
        {
            # Security checks
            # Check 1: If filename does not end with ".css", append .css
            if (strcmp(substr($file, strlen($file)-4,4),".css"))
            {
                $file .= ".css";
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

            styleEdit($file, $code);
        }

        if ( isset($file) ) {

            if (!strrchr($file, "/"))
            {

                if (file_exists($path.$file))
                {
                    $code = implode ('', file($path.$file));

                    # Set static pointers
                    $tpl->set('s', 'ACTION',    $sess->url("main.php?area=$area&frame=$frame&action=style_edit"));
                    $tpl->set('s', 'FILENAME', $file);
                    $tpl->set('s', 'CODE', $code);
            
                    $tpl->generate($cfg['path']['templates'] . $cfg['templates']['style_edit_form']);
                } else {
                    $notification->displayNotification("error", i18n("File is not existing or readable"));
                }
            } else {
                    $notification->displayNotification("error", i18n("Invalid file"));
            }

        } else {
        $tpl->reset();
        $tpl->set('s', 'CONTENTS', '');
        $tpl->generate($cfg['path']['templates'] . $cfg['templates']['blank']);
}

}

?>
