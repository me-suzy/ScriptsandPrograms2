<?php


require_once("db/class_task.inc");
require_once("db/class_project.inc");
require_once("db/class_comment.inc");
require_once("render/render_list.inc");

#=====================================================================================================
# commentNew
# - requires comment or task or comments_* - param
#=====================================================================================================
function commentNew() {
    global $PH;
    global $COMMENTTYPE_VALUES;

    $project=NULL;

    $name=get('new_name')
        ? get('new_name')
        :"New Comment";


    ### build new object ###
    $newComment= new Comment(array(
        'id'=>0,
        'name'=>$name,
    ));


    ### try single project-id ###
    if($id=getOnePassedId('prj','projects_*',false)) { #no not abort if not found
        if($project= new Project($id)) {
            $newComment->project= $project->id;
        }
    }

    ### try single task-id ###
    if($id=getOnePassedId('tsk','tasks_*',false)) { #no not abort if not found
        if($task= new Task($id)) {
            $newComment->task= $task->id;

            ### try to figure project-id from task ###
            if(!$newComment->project) {
                $newComment->project= $task->getProject()->id;
            }
        }
    }

    ### try single company-id ###
    if($id=getOnePassedId('company','companies_*',false)) { #no not abort if not found
        if($company= new Company($id)) {
            $newComment->company= $company->id;
        }
    }

    ### try single person-id ###
    if($id=getOnePassedId('person','persons_*',false)) { #no not abort if not found
        if($person= new Person($id)) {
            $newComment->person= $person->id;
        }
    }

    ### try single person-id ###
    if($id=getOnePassedId('comment','comments_*',false)) { #no not abort if not found
        if($comment= new Comment($id)) {
            $newComment->comment= $comment->id;
            $newComment->name="Reply to ".$comment->name;
            $newComment->occasion=$COMMENTTYPE_VALUES['Reply'];
        }
    }


    ### get current project ###
    if(!$project) {
        if($task) {
            if(!$project= new Project($task->project)) {
                $PH->abortWarning('invalid project id',ERROR_FATAL);
            }
        }
        else {
            $PH->abortWarning('can´t access project',ERROR_BUG);
        }
    }

    ### set a valid create-level ###
    $newComment->pub_level= $project->getCurrentLevelCreate();

    ### render form ###
    $PH->show('commentEdit',array('comment'=>$newComment->id), $newComment);
}


#=====================================================================================================
# comment edit
#=====================================================================================================
function commentEdit($comment=NULL) 
{
    global $PH;

    ### edit existing object or get from database ? ###
    if(!$comment) {
        $id= getOnePassedId('comment','comments*');   # WARNS if multiple; ABORTS if no id found
        $comment= new Comment($id);
        if(!$comment) {
            $PH->abortWarning("ERROR: could not get Comment", ERROR_FATAL);
            return;
        }
    }


    ### check user-rights ###
    if(!$project= new Project($comment->project)) {
        $PH->abortWarning("comment without project?", ERROR_BUG);
    }
    $project->validateEditItem($comment);   # aborts if not enough rights to edit

    ### set up page and write header ####
    {
        $page= new Page(array('use_jscalendar'=>true, 'autofocus_field'=>'comment_name'));
    	$page->cur_tab='projects';
        $page->type="Comment";
        if($comment->id) {
            $page->title="Edit Comment";
        }
        else {
            $page->title="New Comment";
        }

        if($comment->task && ($task= new Task($comment->task))) {
            $page->title_minor= "On task ".$task->name;
        }

        else if($project= new Project($comment->project)) {
            $page->title_minor= "On project ".$project->getLink(false);
        }


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
        global $COMMENTTYPE_VALUES;
        global $PUB_LEVEL_VALUES;
        require_once("render/render_form.inc");

        $form=new PageForm();
        $form->button_cancel=true;

        foreach($comment->fields as $field) {
            $form->add($field->getFormElement(&$comment));
        }

        $form->add(new Form_HiddenField('comment_project', '', $comment->project));
        /*
        $form->add(new Form_HiddenField('comment_task', '', $comment->task));
        $form->add(new Form_HiddenField('comment_comment', '', $comment->comment));
        */

        $form->add(new Form_Dropdown('comment_type',  "Occasion",$COMMENTTYPE_VALUES,$comment->occasion));

        ### public-level ###
        if(($pub_levels=$comment->getValidUserSetPublevel())
            && count($pub_levels)>1) {
            $form->add(new Form_Dropdown('comment_pub_level',  "Public to",$pub_levels,$comment->pub_level));
        }


        echo ($form);

/*        $form->add(new Form_Input('comment_name',       'Name',$comment->name));
        $form->add(new Form_Edit('comment_description',  'Description', $comment->description));
        */

        #echo($form);

        $PH->go_submit= 'commentEditSubmit';
        echo "<input type=hidden name='comment' value='$comment->id'>";
    }
    echo (new PageContentClose);
}


#=====================================================================================================
# commentEditSubmit
#=====================================================================================================
function commentEditSubmit(){
    global $PH;
    
    ### cancel ###
    if(get('form_do_cancel')) {
        if(!$PH->showFromPage()) {
            $PH->show('home');
        }
        exit;
    }
    
   

    ### get comment ####
    $id= getOnePassedId('comment');

    ### new object? ###
    if($id == 0) {
        $comment= new Comment();
    }
    ### ...or from db ###
    else {
        $comment= new Comment($id);
        if(!$comment) {
            $PH->abortWarning("Could not get comment");
            return;
        }
    }

    # retrieve all possible values from post-data
    # NOTE:
    # - this could be an security-issue.
    # - TODO: as some kind of form-edit-behaviour to field-definition
    foreach($comment->fields as $f) {
        $name=$f->name;
        $f->parseForm(&$comment);
    }
    if($tmp= get('comment_project')) {
        $comment->project= $tmp;
    }
    if($tmp= get('comment_task')) {
        $comment->task= $tmp;
    }

    if($tmp= get('comment_pub_level')) {
        $comment->pub_level= $tmp;
    }


    ### be sure the comment is connected somewhere ###
    if(!$comment->project && !$comment->task && !$comment->comment && !$comment->company && !$comment->person) {
        $PH->abortWarning("ERROR:Comment not connected anywhere. This is an internal error and should be reported");
    }

    ### write to db ###
    if($comment->id == 0) {
        $comment->insert();
    }
    else  {
        $comment->update();
    }

    ### display taskView ####
    if(!$PH->showFromPage()) {
        $PH->show('home');
    }
}


#=====================================================================================================
# commentsDelete
#=====================================================================================================
function commentsDelete(){
    global $PH;

    ### get comment ####
    $ids= getPassedIds('comment','comments_*');

    if(!$ids) {
        $PH->abortWarning("Select some comments to delete");
        return;
    }

    $counter=0;
    $errors=0;
    foreach($ids as $id) {
        $e= new Comment($id);
        if(!$e) {
            $PH->abortWarning("Invalid comment-id!");
        }
        if($e->delete()) {
            $counter++;
        }
        else {
            $errors++;
        }
    }
    if($errors) {
        $PH->message="WARNING: Failed to delete $errors comments";
    }
    else {
        $PH->message="Moved $counter comments to dumpster";
    }

    ### display taskView ####
    if(!$PH->showFromPage()) {
        $PH->show('home');
    }
}

#---------------------------------------------------------------------------
# commentsMoveToFolder
#---------------------------------------------------------------------------
#todo:
# - check for valid ids
# - check for rights
function commentsMoveToFolder()
{
    global $PH;
    global $vars;

    $ids= getPassedIds('comment','comments_*');

    if(!$ids) {
        $PH->abortWarning("Select some comments to move");
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
    else {
        $PH->abortWarning("count not get target task",ERROR_BUG);
    }


    $count=0;
    foreach($ids as $id) {
        if($comment= new Comment($id)) {

            $comment->task= $target_id;
            

            ### check user-rights ###
            if(!$project= new Project($comment->project)) {
                $PH->abortWarning("comment without project?", ERROR_BUG);
            }
            $project->validateEditItem($comment);   # aborts if not enough rights to edit

            ### reset comment on comment ###
            if($comment->comment) {
                $comment->comment=0;
                $PH->message= "is no longer a reply";
            }
            
            $comment->update();
        }
    }

    ### return to from-page? ###
    if(!$PH->showFromPage()) {
        $PH->show('home');
    }
}



?>