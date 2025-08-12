<?php
# streber - a php based project management system
# Copyright (c) 2005 Thomas Mann - thomas@pixtur.de
# Distributed under the terms and conditions of the GPL as stated in docs/license.txt

/**
 * derived ListBlock-class for listing tasks
 *
 * @includedby:     pages/*
 *
 * @author:         Thomas Mann
 * @uses:           ListBlock
 * @usedby:
 *
 */
class ListBlock_tasks extends ListBlock 
{

    public function __construct() {
        global $PH;
        $this->id='tasks';
        $this->bg_style='bg_projects';
        $this->no_items_html=NULL;
		$this->title="Tasks";


        ### columns ###
		$this->add_col( new ListBlockCol(array(
			'key'=>'_select_col_',
			'name'=>"S",
			'tooltip'=>"This is a tooltip",
		)));
		$this->add_col( new ListBlockColPrio(array(
			'key'=>'prio',
			'name'=>"P",
			'tooltip'=>"This is a tooltip",
			'sort'=>1
		)));
		$this->add_col( new ListBlockColStatus(array(
			'key'=>'status',
			'name'=>"S",
			'tooltip'=>"Task-Status",
			'sort'=>0
		)));

        $this->add_col(new ListBlockColMethod(array(
			'key'=>'parent_task',
			'name'=>'Folder',
			'func'=>'getFolderLinks'
		)));
	    $this->add_col( new ListBlockCol_TaskLabel());
	    $this->add_col( new ListBlockCol_TaskName(array('use_short_names'=>false,'indention'=>true)));
	    $this->add_col( new ListBlockCol_TaskCreatedBy(array('use_short_names'=>false,'indention'=>true)));

     	/*$this->add_col( new ListBlockColHtml(array(
			'key'=>'name',
			'name'=>"Name",
			'tooltip'=>"Task name. More Details as tooltips",
			'width'=>'90%',
			'format'=>'<a href="index.php?go=taskView&tsk={?id}">{?name}</a>'
		)));*/
        $this->add_col( new listBlockColDate(array(
			'key'=>'date_start',
            'name'=>'Started'
        )));
        $this->add_col( new listBlockColDate(array(
			'key'=>'date_closed',
            'name'=>'Closed'
        )));

		$this->add_col( new ListBlockColTime(array(
			'key'=>'estimated',
			'name'=>"Est.",
			'tooltip'=>"Estimated time in hours",
			'sort'=>0
		)));
		$this->add_col( new ListBlockCol_TimeDue);
        $this->add_col( new ListBlockColPubLevel());


        ### functions ###
        $this->add_function(new ListFunction(array(
            'target'=>$PH->getPage('taskEdit')->id,
            'name'  =>'Edit',
            'id'    =>'taskEdit',
            'icon'  =>'edit',
            'context_menu'=>'submit',
        )));
        $this->add_function(new ListFunction(array(
            'target'=>$PH->getPage('taskNew')->id,
            'name'  =>'New',
            'id'    =>'taskNew',
            'icon'  =>'new',
        )));
        $this->add_function(new ListFunction(array(
            'target'=>$PH->getPage('tasksDelete')->id,
            'name'  =>'Delete',
            'id'    =>'tasksDelete',
            'icon'  =>'delete',
            'context_menu'=>'submit',
        )));
        $this->add_function(new ListFunction(array(
            'target'=>$PH->getPage('tasksComplete')->id,
            'name'  =>'Status->Completed',
            'id'    =>'tasksCompleted',
            'icon'  =>'complete',
            'context_menu'=>'submit',
        )));
        $this->add_function(new ListFunction(array(
            'target'=>$PH->getPage('tasksApproved')->id,
            'name'  =>'Status->Approved',
            'id'    =>'tasksApproved',
            'context_menu'=>'submit',
        )));

        $this->add_function(new ListFunction(array(
            'target'=>$PH->getPage('tasksMoveToFolder')->id,
            'name'  =>'Move selected to folder',
            'id'    =>'tasksMoveToFolder',
            'context_menu'=>'submit'
        )));

        $this->add_function(new ListFunction(array(
            'target'=>$PH->getPage('effortNew')->id,
            'name'  =>'Log hours for select tasks',
            'id'    =>'effortNew',
            'icon'    =>'loghours',
            'context_menu'=>'submit'
        )));

    }

    /**
    * render complete
    *
    * @@@ why style-attribute?
    */
    public function render_list(&$tasks=NULL) 
    {
		$this->render_header();

		$style='';
        if(!$tasks && $this->no_items_html) {
            $this->render_tfoot_empty();
        }
        else {
    		$this->render_thead();
            $count_estimated=0;
    		foreach($tasks as $t) {
                $style=($t->is_folder)
                    ?' isFolder'
                    :'';
                ### done ###
                if(@intval($t->status)>3) {
                    $style.= 'isDone';
                }
                else {
                    $count_estimated+=$t->estimated;
                }
      			$this->render_trow(&$t,$style);
            }
               		#$this->render_trow(&$t);

            $this->summary=count($tasks)." tasks / {$count_estimated} h open";
    		$this->render_tfoot();
        }
    }
}


class ListBlockCol_TaskLabel extends ListBlockCol
{
    public $name='Label';
    public $id='label';
    public $key='label';

	function render_tr(&$obj, $style="") 
	{
        measure_start('col_label');
		if(!isset($obj) || !$obj instanceof Task) {
			trace("WARNING","ListBlockColHtml->render_tr() called without valid object");
   			return;
		}
        $value="";
        if($obj->label) {
            if(!$project= new Project($obj->project)) {
                trace("Warning","task without project?");
            }
            $labels=split(",",$project->labels);
            $value= $labels[$obj->label-1];
        }
		print "<td>$value</td>";
        measure_stop('col_label');
	}
}

class ListBlockCol_TaskCreatedBy extends ListBlockCol
{
    public $name='Created by';
    public $id='createdby';
    public $key='created_by';

	function render_tr(&$obj, $style="nowrap") {
		if(!isset($obj) || !$obj instanceof Task) {
   			return;
		}
        $value="";
        if($obj->created_by) {
            if($person= new Person($obj->created_by)) {
                $value=$person->getLink();
            }
        }
		print "<td class=nowrap>$value</td>";
	}
}

class ListBlockCol_TaskName extends ListBlockCol
{
    public $use_short_names=false;
    public $name='Name, Comments';
    public $indention=true;
    public $key='name';

    public function __construct($args=NULL) {
        parent::__construct($args);
        $this->width='90%';
    }

	function render_tr(&$task, $style="") 
	{
        measure_start('col_taskname');

        global $PH;
		if(!isset($task) || !is_object($task)) {
			trace("WARNING","ListBlockColHtml->render_tr() called without valid object");
   			return;
		}
        $class_label= $task->label
            ? "label$task->label"
            : '';

        ### descriptions ###
        if($task->description) {
            $diz=preg_replace("/[\"\'\r\n]/",'',$task->description);
            $description= '<span class=diz title="'.$diz.'">described with '.strlen($task->description).'b</span>';
        }
        else {
            $description='';
        }

        ### descriptions ###
        $discussion='';
        if($num_comments=$task->getNumComments()) {
            $diz="has $num_comments comments";
            $discussion= ' <span class=comments title="' .$diz. '"> </span>';
        }

        ### task with zero-id is project-root ###
        if(!$task->id) {
            $link=$PH->getLink('projView',"...project...",array('prj'=>$task->project),$class_label);
            echo '<td>'.$link."</td>";
        }
        else {
            $link= $this->use_short_names
                ? $PH->getLink('taskView',$task->getShort(),array('tsk'=>$task->id),$class_label)
                : $PH->getLink('taskView',$task->name,array('tsk'=>$task->id),$class_label);
            if($this->indention) {
                $indent='';
                for($i=1; $i < $task->level; $i++) {
                    $indent.="<span class=indention>&nbsp;</span>";
                }
                $str_indented= $task->level
                    ? $str_indented='indented'
                    : '';

                echo "<td>$indent<span class='$str_indented'>$link</span>$description$discussion</td>";
            }
            else {
                echo "<td>$link$discription$discussion</td>";
            }
        }
        measure_stop('col_taskname');
   	}
}


class ListBlockCol_TaskSumEfforts extends ListBlockCol
{

    public $name=   "Efforts";
    public $tooltip="Sum of all books efforts (including subtasks)";

	function render_tr(&$obj, $style="nowrap") {
		if(!isset($obj) || !$obj instanceof Task) {
   			return;
		}
        $sum=$obj->getSumEfforts();
        $str=  round($sum/60/60,1)."h";

		print "<td class=nowrap title='Effort in hours'>$str</td>";
	}
}

?>