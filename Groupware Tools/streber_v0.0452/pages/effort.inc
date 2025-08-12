<?php


require_once("db/class_task.inc");
require_once("db/class_project.inc");
require_once("db/class_effort.inc");
require_once("render/render_list.inc");

#=====================================================================================================
# effortNew
# - requires prj or task or tsk_*
#=====================================================================================================
function effortNew() {
    global $PH;

    $name=get('new_name')
        ? get('new_name')
        :"New Effort";

    ### first try single project-id ###
    $id=getOnePassedId('prj','projects_*');
    if(!$project= new Project($id)) {
        $PH->abortWarning("ERROR: could not get Project");
        return;
    }

    ### try to get task ###
    $task_id=0;
    if($task_ids= getPassedIds('tsk','tasks_*')) {
        if(count($task_ids) > 1) {
            $PH->message = "only expected one task. Used the first one.";
        }
        $task_id= $task_ids[0];
    }    
    ### try to get folder ###
    else if($task_ids= getPassedIds('','folders_*')) {
        if(count($task_ids) > 1) {
            $PH->message = "only expected one task. Used the first one.";
        }
        $task_id= $task_ids[0];
    }

    ### guess start-time from last effort / start at 10:00 if new day ###
    if($last=Effort::getDateCreatedLast()) {
    	$last_day=getdate(strtotime($last));
	}
	else {
		$last_day="1980-01-01";
	}

    $today=getdate(time());
    if($last_day['yday'] != $today['yday']) {
        $last=date("Y-m-d 10:00:00", time());
    }

    ### build new object ###
    $newEffort= new Effort(array(
        'id'        =>0,
        'name'      =>$name,
        'project'   =>$project->id,
        'time_start'=>$last,
        'task'      =>$task_id,
        )
    );
    $PH->show('effortEdit',array('effort'=>$newEffort->id),$newEffort);
}


#=====================================================================================================
# effort edit
#=====================================================================================================
function effortEdit($effort=NULL) {
    global $PH;

    if($effort) {

    }
    else {
        $id= getOnePassedId('effort','efforts*');   # WARNS if multiple; ABORTS if no id found
        $effort= new Effort($id);
        if(!$effort) {
            $PH->abortWarning("ERROR: could not get Effort");
            return;
        }
    }

    if(!$project=new Project($effort->project)) {
            $PH->abortWarning("ERROR: could not get Project",ERROR_BUG);
    }


    ### set up page and write header ####
    {
        $page= new Page(array('use_jscalendar'=>true, 'autofocus_field'=>'effort_name'));
    	$page->cur_tab='projects';
        $page->type="Edit Effort";
        $page->title=$effort->name;



        if($effort->id) {
            $page->title="Edit Effort";
        }
        else {
            $page->title="New Effort";
        }

        $page->title_minor= "On project ".$project->getLink(false);


    	$page->crumbs=array(
       	);
    	$page->options=array(
            $PH->getWikiLink()
    	);
        echo(new PageHeader);
    }
    echo (new PageContentOpen);

    ### write form #####
    {
        global $STATUS_NAMES;
        require_once("render/render_form.inc");

        $form=new PageForm();
        $form->button_cancel=true;
        
        ### automatically write fields ###
        foreach($effort->fields as $field) {
            $form->add($field->getFormElement(&$effort));
        }
        
        ### get meta-tasks / folders ###
        $folders= $project->getFolders();
        if($folders) {
            $folder_list= array("undefined"=>"0");
            if($effort->task) {
                if($task= new Task($effort->task)) {
    

                    $folder_list[$task->name]= $task->id;
                }
            }

            foreach($folders as $f) {
                $folder_list[$f->name]=$f->id;
            }
            $form->add(new Form_Dropdown('effort_task',  "For task",$folder_list, $effort->task));

        }
        
        ### public-level ###
        if(($pub_levels= $effort->getValidUserSetPublevel())
            && count($pub_levels)>1) {
            $form->add(new Form_Dropdown('effort_pub_level',  "Public to",$pub_levels,$effort->pub_level));
        }

        
        echo ($form);

        $PH->go_submit='effortEditSubmit';
        echo "<input type=hidden name='effort' value='$effort->id'>";
        echo "<input type=hidden name='effort_project' value='$effort->project'>";
    }
    echo (new PageContentClose);
}


#=====================================================================================================
# effortEditSubmit
#=====================================================================================================
function effortEditSubmit(){
    global $PH;

    ### cancel ###
    if(get('form_do_cancel')) {
        if(!$PH->showFromPage()) {
            $PH->show('projView',array('prj'=>$effort->project));
        }
        exit;

    }

    ### get effort ####
    $id= getOnePassedId('effort');

    if($id == 0) {
        $effort= new Effort(array('id'=>0));
    }
    else {
        $effort= new Effort($id);
        if(!$effort) {
            $PH->abortWarning("Could not get effort");
            return;
        }
    }

    ### get project ###
    $effort->project=get('effort_project');
    if(!$project = new Project($effort->project)) {
        $PH->abortWarning("Could not get project of effort");
    }

    # retrieve all possible values from post-data
    # NOTE:
    # - this could be an security-issue.
    # - TODO: as some kind of form-edit-behaviour to field-definition
    foreach($effort->fields as $f) {
        $name=$f->name;
        $f->parseForm(&$effort);
    }


    ### pub level ###
    if($pub_level=get('effort_pub_level')) {

        ### not a new effort ###
        if($effort->id) {
             if($pub_level > $effort->getValidUserSetPublevel() ) {
                 $PH->abortWarning('invalid data',ERROR_RIGHTS);
             }
        }
        else {
            #@@@ check for person create rights
        }
        $effort->pub_level = $pub_level;
    }


    ### link to task ###
    if($task_id = get('effort_task')) {
        if($task= new Task($task_id)) {
            $effort->task = $task->id;
        }
    }
    

    ### write to db ###
    if($effort->id == 0) {
        $effort->insert();
    }
    else {
        $effort->update();
    }

    ### display taskView ####
    if(!$PH->showFromPage()) {
        $PH->show('projView',array('prj'=>$effort->project));
    }
}


#=====================================================================================================
# effortDelete
#=====================================================================================================
function effortDelete(){
    global $PH;

    ### get effort ####
    $ids= getPassedIds('effort','efforts_*');

    if(!$ids) {
        $PH->abortWarning("Select some efforts to delete");
        return;
    }

    $counter=0;
    $errors=0;
    foreach($ids as $id) {
        $e= new Effort($id);
        if(!$e) {
            $PH->abortWarning("Invalid effort-id!");
        }
        if($e->delete()) {
            $counter++;
        }
        else {
            $errors++;
        }
    }
    if($errors) {
        $PH->message="WARNING: Failed to delete $errors efforts";
    }
    else {
        $PH->message="Moved $counter efforts to dumpster";
    }

    if(!$PH->showFromPage()) {
        $PH->show('projView',array('prj'=>$effort->project));
    }
}


?>
