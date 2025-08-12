<?php

/******************************************
* File      :   includes.mycontenido_lastarticles.php
* Project   :   Contenido
* Descr     :   Displays all last edited articles
*               of a category 
*
* Author    :   Timo A. Hummel
* Created   :   08.05.2003
* Modified  :   08.05.2003
*
* © four for business AG
*****************************************/


        # Generate template
		$tpl->reset();
		
		$tpl->set('s', 'VERSION', $cfg['version']);
        $tpl->generate($cfg['path']['templates'] . $cfg['templates']['info']);
    


?>
