<?php

require_once("db/class_task.inc");
require_once("db/class_project.inc");
require_once("render/render_list.inc");


#---------------------------------------------------------------------------
# quicknew
#---------------------------------------------------------------------------
function quickNew(){
    global $PH;

    $type=get('type');

    switch($type) {
    case 'task':
        $PH->show('taskNew');
        break;
    case 'effort':
        $PH->show('effortNew');
        break;
    case 'comment':
        $PH->show('commentNew');
        break;
    default:
        $PH->abortWarning("unknown type for quicknew $type");
        break;
    }
}



?>