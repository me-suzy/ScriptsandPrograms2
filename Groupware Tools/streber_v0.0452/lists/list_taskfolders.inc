<?php
# streber - a php based project management system
# Copyright (c) 2005 Thomas Mann - thomas@pixtur.de
# Distributed under the terms and conditions of the GPL as stated in docs/license.txt

/**
 * derived ListBlock-class for listing taskfolders
 *
 * NOTE:
 *  Different from other list-block taskFolders get's the listed
 *  items automatically. Therefore render() has to be called without array.
 *
 *
 * @includedby:     pages/*
 *
 * @author:         Thomas Mann
 * @uses:           ListBlock
 * @usedby:
 *
 */

class ListBlock_taskfolders extends ListBlock {

    ### overwrite ###
    public $title= 'Folders';
    public $bg_style='bg_misc';
    public $id=    'folders';

    private $cur_task_id=-1;
    private $hidden=false;
    private $task_folders=NULL;

    /**
    *  constructor
    */
    public function __construct(Project $project) {

        if(!$project) throw new RenderException("ListBlock_taskfolders() needs project-object as argument");
        $this->task_folders= $project->getFolders();

        #--- render nothing if no folders ---
        if(!count($this->task_folders)) {
            $this->hidden= true;
        }


        #--- in taskView highlight current task ---
        global $PH;

        if($PH->cur_page_id == 'taskView') {
            $task_id= get('tsk');
            $cur_task= new Task($task_id);
            if(!$cur_task) {
                $PH->abortWarning("invalid task-id");   #@@@ not good inside lists / render Exception might be more appropriate
                return;
            }

            #--- use parent, if not a folder for itself ------
            if(!$cur_task->is_folder) {
                $cur_task= new Task($cur_task->parent_task);
            }
            if($cur_task && is_object($cur_task)) {
                $this->cur_task_id= $cur_task->id;
            }
        }

        #--- create task for project-root---
        $task_none=new Task();
        $task_none->name="..none::";
        $task_none->id=0;
        $task_none->project= $project->id;
        array_unshift($this->task_folders,$task_none);

    	#--- add columns --------------------------------------------------------
    	$this->add_col(new ListBlockCol(array(
    		'key'=>'_select_col_',
    		'name'=>"S",
    		'tooltip'=>"Select all, range, no row",
    	)));
    	$this->add_col( new ListBlockCol_TaskName(array('use_short_names'=>true)));
    	$this->add_col( new ListBlockColMethod(array(
    		'name'=>"Tasks",
    		'tooltip'=>"Number of subtasks",
    		'sort'=>0,
    		'func'=>'getNumSubtasks',
            'style'=>'right'
    	)));
    	
    	#--- functions ----------------------------------------
        ### functions ###
        $this->add_function(new ListFunction(array(
            'target'=>$PH->getPage('taskEdit')->id,
            'name'  =>'Edit',
            'id'    =>'taskEdit',
            'icon'  =>'edit',
            'context_menu'=>'submit',
        )));
        $this->add_function(new ListFunction(array(
            'target'=>$PH->getPage('taskNewFolder')->id,
            'name'  =>'New',
            'id'    =>'taskNewFolder',
            'icon'  =>'new',
            'tooltip'=>'Create new folder under selected task',
        )));

        $this->add_function(new ListFunction(array(
            'target'=>$PH->getPage('tasksMoveToFolder')->id,
            'name'  =>'Move selected to folder',
            'id'    =>'tasksMoveToFolder',
            'context_menu'=>'submit',
            'dropdown_menu'=>0,
            
        )));

        $this->add_function(new ListFunction(array(
            'target'=>$PH->getPage('effortNew')->id,
            'name'  =>'Log hours for select tasks',
            'id'    =>'effortNew',
            'icon'    =>'loghours',
            'context_menu'=>'submit'
        )));    	
    	
    	$this->add_col( new ListBlockCol_TaskSumEfforts());

    }

    /**
    * complete render / write as html-output
    */
    public function __toString() {
        if($this->hidden) {
            return;
        }

        $this->render_header();
    	#$this->render_thead();
    	foreach($this->task_folders as $f) {

            ### hilight the current task-folder ###
            if($f->id == $this->cur_task_id) {
    		    $this->render_trow($f,'current');
            }
            else {
    		    $this->render_trow($f);
            }
    	}
    	$this->render_tfoot();
    }
}



?>