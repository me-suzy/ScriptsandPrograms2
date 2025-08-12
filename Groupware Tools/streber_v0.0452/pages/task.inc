<?php
require_once("db/class_issue.inc");
require_once("db/class_task.inc");
require_once("db/class_project.inc");
require_once("render/render_list.inc");
require_once("lists/list_taskfolders.inc");
require_once("lists/list_comments.inc");
require_once("lists/list_tasks.inc");


#---------------------------------------------------------------------------
# taskView
#---------------------------------------------------------------------------
function TaskView(){
    global $PH;

    ### get task ####
    $tsk=get('tsk');
#    $tsk=getOnePassedId('tsk','tasks_*');   # causes error if no task found

    if(!$task=new Task($tsk)) {
        $PH->abortWarning("invalid task-id",ERROR_FATAL);
    }
    
    $parent_project= new Project($task->project);

    ### create from handle ###
    #$from_handle= $PH->setFromHandle(array('tsk'=>$task->id));
    $from_handle= $PH->defineFromHandle(array('tsk'=>$task->id));

    #@@@ echo '<input type="hidden" name="from" value="'.$from_handle.'">';


    ### set up page and write header ####
    {
        $page= new Page();
    	$page->cur_tab='projects';
        $page->type= $task->is_folder
                ? "Task with subtasks"
                : "Task";
        $page->title=$task->name;
        $page->title_minor=$task->short;
        if($task->state== -1) {
            $page->title_minor="(deleted ". date("D, d.m.Y",strtotime($task->deleted)) .")";
        }


    	$page->crumbs=array(
            $PH->getLink('projList'),
            $PH->getLink('projView',$parent_project->name,array('prj'=>$task->project)),
       	);

        ### breadcrumb + folders ##
        $folder=$task->getFolder();
        foreach($folder as $f) {
            $page->crumbs[]=$PH->getLink('taskView',$f->getShort(),array('tsk'=>$f->id));
        }

        $page->crumbs[]= $PH->getLink('taskView',$task->name,array('tsk'=>$task->id));

    	$page->options=array(
            $PH->getLink('error','Overview'),
            $PH->getLink('error','Efforts'),
            $PH->getLink('error','Assignment-History'),
            '',
            $PH->getWikiLink(),
    	);

        ### page functions ###
        $page->add_function(new PageFunction(array(
            'target'=>'taskEdit',
            'params'=>array('tsk'=>$task->id),
            'icon'=>'edit',
            'tooltip'=>'Edit this task',
            'name'=>'edit'
        )));
        if(!$task->issue_report) {
            $page->add_function(new PageFunction(array(
                'target'=>'taskAddIssueReport',
                'params'=>array('tsk'=>$task->id),
                'icon'=>'edit',
                'tooltip'=>'Append bug report',
                'name'=>'Append bug report'
            )));
        }
        if($task->state == 1) {
            $page->add_function(new PageFunction(array(
                'target'=>'tasksDelete',
                'params'=>array('tsk'=>$task->id),
                'icon'=>'delete',
                'tooltip'=>'Delete this task',
                'name'=>'delete'
            )));
        }
        if($task->state == -1) {
            $page->add_function(new PageFunction(array(
                'target'=>'tasksUndelete',
                'params'=>array('tsk'=>$task->id),
                'icon'=>'undelete',
                'tooltip'=>'Restore this task',
                'name'=>'undelete'
            )));
        }

    	### render title ###
        echo(new PageHeader);
    }
    echo (new PageContentOpen_Columns);

    #--- info block ------------
    {
        $block=new PageBlock(array('title'=>'Summary'));
        $block->render_blockStart();
        echo "<div class=text>";

        ### short description, if folder ###
        if($task->is_folder) {
            if($task->description) {
                $str= text2html($task->description);
                echo "<p><label>Summary:</label>$str</p>";
            }
        }
        
        global $STATUS_NAMES;
        if($status=$STATUS_NAMES[$task->status]) {
            echo "<p><label>Status:</label>$status</p>";
        }


        echo "<p><label>Opened</label>$task->date_start</p>";

        if($person_creator= new Person($task->created_by)) {
            echo "<p><label>Created by:</label>".$person_creator->getLink()."</p>" ;
        }
        
        $sum_efforts= $task->getSumEfforts();
        echo "sum=$sum_efforts";

        echo "</div>";

        $block->render_blockEnd();
    }


	#--- list task folders -----------------------------------------------------------
    $folders= new ListBlock_taskFolders($parent_project);
    $folders->render();

    echo(new PageContentNextCol);

    #--- list subtasks ---------------------------------------------------------
    if($task->is_folder){
        $subtasks=$task->getSubtasks();

        $list_tasks= new ListBlock_tasks();
        $list_tasks->title='Subtasks';
        $list_tasks->no_items_html=$PH->getLink('taskNew','create new Subtask',array('tsk'=>$task->id,'prj'=>$task->project,'parent_task'=>$task->id));

        $list_tasks->render_list(&$subtasks);

    }
    ### full description for normal tasks ###
    else {
        #--- description block ------------
        {
            $block=new PageBlock(array('title'=>'Details'));
            $block->render_blockStart();
            echo "<div class=text>";

            if($task->description) {
                $str= text2html($task->description);
                echo "<p><label>Summary:</label>$str</p>";
            }
            echo "</div>";

            $block->render_blockEnd();
        }
    }

    #--- issue report -------------------------------------------------------------
    if($task->issue_report) {

        $block=new PageBlock(array(
            'title'=>'Issue report',
            'id'=>'issue_report'

        ));
        $block->render_blockStart();
        $issue= new Issue($task->issue_report);

        echo "<div class=text>";
        if($issue->plattform) {
            echo "<p><label>Plattform:</label>$issue->plattform</p>";
        }
        if($issue->os) {
            echo "<p><label>OS:</label>$issue->os</p>";
        }
        if($issue->version) {
            echo "<p><label>Version:</label>$issue->version</p>";
        }
        if($issue->production_build) {
            echo "<p><label>Build:</label>$issue->production_build</p>";
        }

        if($issue->steps_to_reproduce) {
            $text= text2html($issue->steps_to_reproduce);
            echo "<p><label>Steps to reproduce:</label><p>$text</p></p>";
        }
        if($issue->expected_result) {
            $text= text2html($issue->expected_result);
            echo "<p><label>expected_result:</label><p>$text</p></p>";
        }
        if($issue->suggested_solution) {
            $text= text2html($issue->suggested_solution);
            echo "<p><label>suggested_solution:</label><p>$text</p></p>";
        }

        echo "</div>";

        $block->render_blockEnd();
    }

    #--- list comments -------------------------------------------------------------
    {
        $comments= $task->getComments();
        $list=new ListBlock_comments();
        $list->no_items_html=$PH->getLink('commentNew','',array('tsk'=>$task->id));
        $list->render_list($comments);
    }

    echo '<input type="hidden" name="prj" value="'.$task->project.'">';

    /**
    * give parameter for create of new items (subtasks, efforts, etc)
    */
    echo '<input type="hidden" name="parent_task" value="'.$task->id.'">';

    echo (new PageQuickNew);

    echo (new PageContentClose);
	echo(new PageHtmlEnd);
}


#---------------------------------------------------------------------------
# taskNew
#---------------------------------------------------------------------------
function TaskNew(){

    global $PH;
    $prj=getOnePassedId('prj','project_*',true,'No project selected?'); # aborts with error if not found
    if(!$project= new Project($prj)) {
        $PH->abortWarning("invalid project-id",ERROR_FATAL);
    }

    ### get id of parent_task
    $parent_task_id=0;
    {
        $task_ids= GetPassedIds('parent_task','tasks_*'); # aborts with error if not found
        if(count($task_ids) >= 1) {
            $parent_task_id= $task_ids[0];
        }

    }

    $name=stripslashes(stripslashes(get('new_name')));  # @@@ hack to get rid of slashed strings
    $estimated='00:00:00';
    preg_match('/^([^\s]*\:)?\s*(.*?)\(?([\d\.]*h)?\)?\s*(\?*)?(\!*)?\s*(in\s*(\d+)\s*days?)?$/',$name,$matches);
    #preg_match("/([\d\.,]*h)?\s*(\?*)?(\!*)?\s*$/",$name,$matches);
    if(count($matches)>2) {
        @list($tmp,$label,$name,$estimated,$question,$important,$tmp,$due_in_days)=$matches;
    }
    ### extract priority ###
    $prio=3;
    if($question=="??") $prio=5;
    if($question=="?") $prio=4;
    if($important=="!") $prio=2;
    if($important=="!!") $prio=1;

    ### extract estimated time ###
    ### replace , with .
    preg_match("/(.*)h/",$estimated,$matches);
    $estimated='00-00-00';
    if(count($matches)>1) {
        $estimated=$matches[1];
        $hh=intval($estimated);
        $mm=intval($estimated*60%60);
        $ss=0;
        #$estimated=$hh.'-'.$mm.'-'.$ss;
        $estimated= sprintf("%02d:%02d:%02d", $hh, $mm, $ss);
    }

    #--- try figure label or folder ---
    $is_folder= false;
    $best_label=0;
    if($label) {
        $label= strtolower(trim(str_replace(':','',$label)));

        if($label=="folder") {
            $PH->message="I guess you wanted to create a folder...";
            $is_folder= true;
        }
        else {
            $labels=split(',',$project->labels);
            $best_lev=2;    #
            $count= 1;
            foreach($labels as $l) {
                if(preg_match("/^$label/",$l)) {
                    $best_label=$count;
                    $best_lev=0;
                    break;
                }
                $lev=levenshtein(trim(strtolower($l)),$label);
                if($lev < $best_lev) {
                    $best_label= $count;
                    $best_lev= $lev;
                }
                $count++;
            }
            if($best_lev<2) {
                $PH->message="Assumed <b>'$label'</b> to be mean label <b>'".$labels[$best_label-1]."'</b>";
            }
            else {
                $name= $label.':'.$name;
            }
        }
    }

    ### build dummy form ###
    $newtask= new Task(array(
        'id'=>0,
        'name'=>$name,
        'project'=>$prj,
        'state'=>1,
        'prio'=>$prio,
        'estimated'=>$estimated,
        'label'=>$best_label,
        'is_folder'=>$is_folder,
        'parent_task'=>$parent_task_id,
        )
    );

    ### add an issue? ###
    if(get('add_issue')) {
        $newtask->issue_report=-1;

        ### find bug-label ###
        $labels=split(',',$project->labels);
        $count=1;
        foreach($labels as $l) {
            if($l == "bug" || $l == "Bug" || $l == "Fehler") {
                $newtask->label= $count;
                break;
            }
            $count++;
        }
    }

    #--- due in some days ---
    if(isset($due_in_days) && $due_in_days != "") {
        $due=date("Y-m-d H-i-s",time()+ 60*60*24*$due_in_days);
        $newtask->date_due= $due;
    }

    ### set a valid create-level ###
    $newtask->pub_level= $project->getCurrentLevelCreate();

    ### insert without editing ###
    if((get('noedit'))) {
        $newtask->insert();
        if(!$PH->showFromPage()) {
            $PH->show('projView',array('prj'=>$prj));
        }
    }


    ### pass newobject to edit-page ###
    else {
        $PH->show('taskEdit',array('tsk'=>$newtask->id),$newtask);
    }
}


/**
* create new folder
*/
function TaskNewFolder() 
{
    global $PH;
 
    $prj_id=getOnePassedId('prj','',true,'No project selected?'); # aborts with error if not found
    if(!$project= new Project($prj_id)) {
        $PH->abortWarning("invalid project-id",ERROR_FATAL);
    }
 
   
    ### get id of parent_task
    $parent_task_id=0;
    {
        $task_ids= GetPassedIds('','folders_*'); # aborts with error if not found
        if(count($task_ids) >= 1) {
            $parent_task_id= $task_ids[0];
        }
    }
    

    ### build dummy form ###
    $newtask= new Task(array(
        'id'=>0,
        'name'=>"New Folder",
        'project'=>$prj_id,
        'is_folder'=>1,
        'parent_task'=>$parent_task_id,
        )
    );    
    $PH->show('taskEdit',array('tsk'=>$newtask->id),$newtask);
}

#---------------------------------------------------------------------------
# taskEdit
#---------------------------------------------------------------------------
function TaskEdit($task=NULL){
    global $PH;

    ### object or from database? ###
    if(!$task) {
        
        ### try task-list and folder list ###
        if(!$id=getOnePassedId('tsk','tasks_*',false,"No task selected?")) {
           $id=getOnePassedId('tsk','folders_*',true,"No task selected?");
        }
        
        if(!$task= new Task($id)) {
            $PH->abortWarning("invalid id ", ERROR_FATAL);
        }
    }

    ### get parent project ####
    if(!$parent_project= new Project($task->project)) {
        $PH->abortWarning("FATAL error! parent project not found");
    }

    ### abort, if not enough rights ###
    $parent_project->validateEditItem($task);

    ### set up page and write header ####
    {
        $page= new Page(array('use_jscalendar'=>true,'autofocus_field'=>'task_name'));
    	$page->cur_tab='projects';
        $page->type="Edit Task";
        $page->title=$task->name;
        $page->title_minor=$task->short;


    	$page->crumbs=array(
            $PH->getLink('projList'),
            $PH->getLink('projView',$parent_project->name,array('prj'=>$task->project)),
    		$task->getShort(),
       	);
    	$page->options=array(
            '',
            $PH->getWikiLink(),
    	);

        echo(new PageHeader);
    }
    echo (new PageContentOpen);

    ### write form #####
    {
        require_once("render/render_form.inc");

        global $STATUS_VALUES;
        global $REPRODUCIBILITY_VALUES;
        global $SEVERITY_VALUES;
        global $PRIORITY_VALUES;


        $form=new PageForm();
        $form->button_cancel=true;

        $form->add($task->fields['name']->getFormElement(&$task));
        $form->add($task->fields['short']->getFormElement(&$task));
        $form->add($task->fields['is_folder']->getFormElement(&$task));

        ### public-level ###
        if(($pub_levels=$task->getValidUserSetPublevel())
            && count($pub_levels)>1) {
            $form->add(new Form_Dropdown('task_pub_level',  "Public to",$pub_levels,$task->pub_level));
        }

        #$form->add($task->fields['prio']->getFormElement(&$task));
        $form->add(new Form_Dropdown('task_prio',  "Prio",$PRIORITY_VALUES,$task->prio));

        $form->add(new Form_Dropdown('task_status',  "Status",$STATUS_VALUES,$task->status));
        $form->add($task->fields['date_due']->getFormElement(&$task));
        $form->add($task->fields['date_due_end']->getFormElement(&$task));
        $form->add($task->fields['completion']->getFormElement(&$task));
        $form->add($task->fields['estimated']->getFormElement(&$task));

        $form->add($task->fields['parent_task']->getFormElement(&$task));

        echo "<input type=hidden name='task_project' value='$parent_project->id'>";

        $labels=array('undefined'=>0);
        $counter=1;
        foreach(split(",",$parent_project->labels) as $l) {
            $labels[$l]=$counter++;
        }
        $form->add(new Form_Dropdown('task_label',  "Label",$labels,$task->label));
        $form->add($task->fields['description']->getFormElement(&$task));


        ### use issue-report ###
        if($task->issue_report != 0) {

            if($task->issue_report == -1) {
                $issue= new Issue();
            }
            else {
                $issue= new Issue($task->issue_report);
            }

            $form->add(new Form_Dropdown('issue_severity',"Severity", $SEVERITY_VALUES, $issue->severity));
            $form->add(new Form_Dropdown('issue_reproducibility',"reproducibility", $REPRODUCIBILITY_VALUES, $issue->reproducibility));
            foreach($issue->fields as $field) {
                $form->add($field->getFormElement(&$issue));
            }
            $form->add(new Form_HiddenField('task_issue_report','',$task->issue_report));

        }


        echo($form);

        $PH->go_submit= 'taskEditSubmit';
        if($return=get('return')) {
            echo "<input type=hidden name='return' value='$return'>";
        }
        echo "<input type=hidden name='tsk' value='$task->id'>";

        #@@@ passing project-id is an security-issue, because it might allow to add tasks to unverified projects.
        # Double-checking project-rights in taskEditSubmit() required
    }

    echo (new PageContentClose);
	echo(new PageHtmlEnd);
}


#---------------------------------------------------------------------------
# taskEditSubmit
#---------------------------------------------------------------------------
function TaskEditSubmit()
{
    global $PH;

    ### cancel? ###
    if(get('form_do_cancel')) {
        if(!$PH->showFromPage()) {
            $PH->show('taskView',array('tsk'=>$task->id));
        }
        exit;
    }


    ### temporary object or from database? ###
    $tsk_id=getOnePassedId('tsk','',true,'invalid id');
    if($tsk_id == 0) {
        $task= new Task(array(
            'id'=>0,
        ));
    }
    else {
        $task= new Task($tsk_id);
        if(!$task) {
            $PH->abortWarning("invalid task-id");
        }
    }
    $was_a_folder= $task->is_folder;    # keep this, to see if option changed

    # retrieve all possible values from post-data
    # NOTE:
    # - this could be an security-issue.
    # - TODO: as some kind of form-edit-behaviour to field-definition
    foreach($task->fields as $f) {
        $name=$f->name;
        $f->parseForm(&$task);
    }
    $task->fields['parent_task']->parseForm(&$task);


    ### pub level ###
    if($pub_level=get('task_pub_level')) {
        if($task->id) {
             if($pub_level > $task->getValidUserSetPublevel() ) {
                 $PH->abortWarning('invalid data',ERROR_RIGHTS);
             }
        }
        else {
            #@@@ check for person create rights
        }
        $task->pub_level = $pub_level;
    }

    #--- check project---
    if($task->id == 0) {
        if(!$task->project=get('task_project')) {
            $PH->abortWarning("task requires project to be set");
        }
    }

    #--- ungroup child tasks? ---
    if(!$task->is_folder && $was_a_folder) {
        $num_children= $task->ungroupSubtasks();
        $num_subtasks=2;
        $parent=new Task($task->parent_task);
        $parent_str= $parent
            ? $parent->name
            : 'Project';

        if($num_subtasks) {
            $PH->message="NOTICE: Ungrouped $num_subtasks subtasks to <b>$parent_str</b>";
        }
    }
    else if($task->is_folder && !$was_a_folder) {
        $PH->message="HINT: You turned task <b>'$task->name'</b> into a folder. Folders are shown in the task-folders list. <a href=''>Read more about folders</a>";
    }
    
    #--- be sure parent-task is folder ---
    if($task->parent_task && $parent_task= new Task($task->parent_task)) {
        if(!$parent_task->is_folder) {
            $parent_task->is_folder=1;
            $parent_task->update();

        }
    }


    ### if issue-report ###
    if(get('task_issue_report') !=0) {

        ### get temporay ###
        if($task->issue_report ==-1) {
            $issue= new Issue();
        }
        ### get from database ###
        else {
            $issue= new Issue($task->issue_report);
        }

        ### querry form-information ###
        foreach($issue->fields as $f) {
            $name=$f->name;
            $f->parseForm(&$issue);
        }

        ### write to db ###
        if($issue->id ==0 ) {
            $issue->insert();
        }
        else {
            $issue->update();
        }

        $task->issue_report= $issue->id;
    }

    ### write to db ###
    if($task->id == 0) {
        $task->insert();
    }
    else {
        $task->update();
    }

    ### return to from-page? ###
    if(!$PH->showFromPage()) {
        $PH->show('taskView',array('tsk'=>$task->id));
    }
}

#---------------------------------------------------------------------------
# tasksMoveToFolder
#---------------------------------------------------------------------------
#todo:
# - check for valid ids
# - check for rights
function TasksMoveToFolder(){
    global $PH;
    global $vars;

    $task_ids= getPassedIds('tsk','tasks_*');

    if(!$task_ids) {
        $PH->abortWarning("Select some tasks to move");
        return;
    }

    $folder_ids= getPassedIds('folder','folders_*');

    if(count($folder_ids) != 1) {
        $PH->abortWarning("Select excactly ONE folder to move tasks into");
        return;
    }

    $target_id=0;
    if($folder_task= new Task($folder_ids[0])) {
        $target_id= $folder_task->id;
    }


    $count=0;
    foreach($task_ids as $id) {
        if($task= new Task($id)) {

            ### check user-rights ###
            if(!$project= new Project($task->project)) {
                $PH->abortWarning("task without project?", ERROR_BUG);
            }
            $project->validateEditItem($task);   # aborts if not enough rights to edit

            $task->parent_task= $target_id;
            $task->update();
        }
    }

    ### return to from-page? ###
    if(!$PH->showFromPage()) {
        $PH->show('home');
    }
}


#---------------------------------------------------------------------------
# taskDelete
#---------------------------------------------------------------------------
function TasksDelete() {
    global $PH;
    $tsk=get('tsk');
    $tasks_selected=get('tasks_*');
    $ids=getPassedIds('tsk','tasks_*');


    if(count($ids)==1) {
        $tsk=$ids[0];
        $task= new Task($tsk);
        if(!$task) {
            $PH->message="ERROR: could not retrieve task";
            $PH->show('home');
            return;
        }
        $project_id= $task->project;
        if(!$project_id) {
            throw new myException("could not get task's project");
        }

        ### create message ####
        $PH->message="Task '$task->name' deleted";

        ### delete task ###
        $task->delete();

        ### go to project view ###
        ### return to from-page? ###
        if(!$PH->showFromPage()) {
            $PH->show('projView',array('prj'=>$project_id));
        }
    }
    else if($ids) {
       #--- get tasks ----
        $tasks=array();
        $num_tasks=count($tasks);
        $num_subtasks=0;
        foreach($ids as $id) {
            $task= new Task($id);
            if(!$task) {
                $PH->abortWarning("invalid task-id");
            }
            $num_subtasks+= $task->ungroupSubtasks();
            if(!$task->delete()) {
                $PH->message="WARNING: Failed to delete task $task->name";
            }
        }
        $PH->message="Moved ".$num_tasks." tasks to <a href=''>Dumpster</a>.";
        if($num_subtasks) {
            $PH->message.="<br> ungrouped $num_subtasks subtasks to above parents.";
        }

        ### return to from-page? ###
        if(!$PH->showFromPage()) {
            $PH->show('home');
        }
    }
    else {
        $PH->message="No task(s) selected for deletion...";
        if(!$PH->showFromPage()) {
            $PH->show('home');
        }
    }
}

#---------------------------------------------------------------------------
# taskUndelete
#---------------------------------------------------------------------------
function TasksUndelete() {
    global $PH;
    $tsk=get('tsk');
    $tasks_selected=get('tasks_*');
    $ids=getPassedIds('tsk','tasks_*');


    if(count($ids)==1) {
        $tsk=$ids[0];
        $task= new Task($tsk);
        if(!$task) {
            $PH->message="ERROR: could not retrieve task";
            $PH->show('home');
            return;
        }
        $project_id= $task->project;
        if(!$project_id) {
            throw new myException("could not get task's project");
        }


        ### delete task ###
        if($task->state!= -1) {
            $PH->message="Task $task->name don't need to be restored";
        }
        else {
            $task->state=1;
            if($task->update()) {
                $PH->message="Task '$task->name' restored"; 
            }
            else {
                $PH->message="Failed to restore Task '$task->name'"; 
            }
            
        }


        ### go to project view ###
        ### return to from-page? ###
        if(!$PH->showFromPage()) {
            $PH->show('projView',array('prj'=>$project_id));
        }
    }
    else if($ids) {
       #--- get tasks ----
        $tasks=array();
        $num_tasks=count($tasks);
        $num_subtasks=0;
        foreach($ids as $id) {


            $task= new Task($id);
            if(!$task) {
                $PH->message="ERROR: could not retrieve task";
                $PH->show('home');
                return;
            }
            $project_id= $task->project;
            if(!$project_id) {
                throw new myException("could not get task's project");
            }
    
    
            ### delete task ###
            if($task->state!= -1) {
                $PH->message="Task $task->name don't need to be restored";
            }
            else {
                $task->state=1;
                if($task->update()) {
                    $PH->message="Task '$task->name' restored"; 
                }
                else {
                    $PH->message="Failed to restore Task '$task->name'"; 
                }
                
            }
        }

        ### return to from-page? ###
        if(!$PH->showFromPage()) {
            $PH->show('home');
        }
    }
    else {
        $PH->message="No task(s) selected for restoring...";
        if(!$PH->showFromPage()) {
            $PH->show('home');
        }
    }
}



#---------------------------------------------------------------------------
# taskComplete
#---------------------------------------------------------------------------
function TasksComplete() {
    global $PH;

    $ids= getPassedIds('','tasks_*');

    if(!$ids) {
        $PH->abortWarning("Select some task(s) to mark as completed", ERROR_NOTE);
        return;
    }

    $count=0;
    $errors=0;
    foreach($ids as $id) {
        if($task= new Task($id)) {
            $count++;
            $task->status=5;
            $task->date_closed= date("Y-m-d", time());
            $task->update();
        }
        else {
            $errors++;
        }

    }
    $PH->message="Marked $count tasks as completed.";
    if($errors) {
        $PH->message.= " ($errors error(s) occured)";
    }

    ### return to from-page ###
    if(!$PH->showFromPage()) {
        $PH->show('home');
    }
}

#---------------------------------------------------------------------------
# tasksApproved
#---------------------------------------------------------------------------
function TasksApproved() {
    global $PH;

    $ids= getPassedIds('','tasks_*');

    if(!$ids) {
        $PH->abortWarning("Select some task(s) to mark as approved", ERROR_NOTE);
        return;
    }

    $count=0;
    $errors=0;
    foreach($ids as $id) {
        if($task= new Task($id)) {
            $count++;
            $task->status=6;
            $task->date_closed= date("Y-m-d", time());
            $task->update();
        }
        else {
            $errors++;
        }

    }
    $PH->message="Marked $count tasks as approaved and hidden from project-view.";
    if($errors) {
        $PH->message.= " ($errors error(s) occured)";
    }

    ### return to from-page ###
    if(!$PH->showFromPage()) {
        $PH->show('home');
    }
}

/**
* add an issue-report to an existing task
*/
function TaskAddIssueReport() {
    global $PH;


    $id= getOnePassedId('tsk','tasks_*',true,'No task selected to add issue-report?');

    if($task= new Task($id)) {
        if($task->issue_report) {
            $PH->abortWarning("Task already has an issue-report");
            exit;
        }
        $issue=new Issue();
        $issue->insert();
        $task->issue_report= $issue->id;
        $PH->message="Adding issue-report to task";
        $PH->show('taskEdit',array('tsk'=>$task->id),$task);
        exit;

    }
    else {
        $PH->message="Could not get task";
    }

    ### return to from-page ###
    if(!$PH->showFromPage()) {
        $PH->show('home');
    }
}


?>
