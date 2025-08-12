<?php

function changeSort() {
    global $PH;

    ### get sort-order for table-def ###
    $pageid=get('pageid');
    $tableid=get('tableid');
    $col=get('column');


    if(isset($pageid) && isset($tableid) && isset($col)) {


        $id="sort_".$pageid."_".$tableid;


        ### get sorting from cookie ###
        if($tmp=get($id)) {
            $list_old=split(",",$tmp);

            ### just reverse sort order? ###
            if($list_old[0] == "$col ASC") {
                $list_old[0] ="$col DESC";
            }
            else if($list_old[0] == "$col DESC") {
                $list_old[0] ="$col ASC";
            }
            else {
                $new_sort = "$col ASC";
                $list_new=array();

                foreach($list_old as $c) {

                    ### remove inside list ###
                    if($c == "$col ASC") {
                        $new_sort= "$col DESC";

                    }
                    else if($c == "$col DESC") {
                        $new_sort= "$col ASC";
                    }
                    else {
                        $list_new[]= $c;
                    }
                }
                array_unshift($list_new, $new_sort);
                $list_old= $list_new;
            }
            $str=join(",",$list_old);
        }
        else {
            $str="$col ASC";
        }
        setcookie(
            $id,
            $str,
            time()+60*60*24*30,
            '',
            '',
            0);

        ### keep for current page ###
        global $vars;
        $vars[$id]=$str;
    }


    ### set up page ####
    if(!$PH->showFromPage()) {
        $PH->show('home',array());
    }
}


/**
* change current task-list-view to tree
*/
function changeListStyle() {
    global $PH;

    $block_id= get('block_id');
    $page_id=get('page_id');

    $style=get('style');
    

    if(!$block_id || !$style){
        $PH->abortWarning("ChangeListStyle() missed parameters",ERROR_BUG); 
    }
    
    
    $id="liststyle_". $page_id ."_". $block_id;
    
    setcookie(
        $id,
        $style,
        time()+60*60*24*30,
        '',
        '',
        0);

    ### keep for current page ###
    global $vars;
    $vars[$id]=$style;



    ### return to from-page ###
    if(!$PH->showFromPage()) {
        $PH->show('home');
    }
}


?>