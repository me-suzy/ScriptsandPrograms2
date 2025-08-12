<?php
# streber - a php based project management system
# Copyright (c) 2005 Thomas Mann - thomas@pixtur.de
# Distributed under the terms and conditions of the GPL as stated in docs/license.txt

/**
 * derived ListBlock-class for listing changes of an project
 *
 * @includedby:     pages/*
 *
 * @author:         Thomas Mann
 * @uses:           ListBlock
 * @usedby:
 *
 */

class ListBlock_projectchanges extends ListBlock {

    public $bg_style="bg_time";
    
    public function __construct() {

        global $PH;
        $this->id='changes';

		$this->title="Changes";

		$this->add_col( new ListBlockCol(array(
			'key'=>'_select_col_',
			'name'=>"S",
			'tooltip'=>"This is a tooltip",
		)));
		/*$this->add_col(new ListBlockColMethod(array(
			'key'=>'p.name',
			'name'=>'Project',
			'func'=>'getProjectLink'
		)));
		$this->add_col(new ListBlockColMethod(array(
			'key'=>'person',
			'name'=>'person',
			'func'=>'getPersonLink'
		)));
		$this->add_col( new ListBlockColHtml(array(
			'key'=>'name',
			'name'=>"Name",
			'tooltip'=>"Task name. More Details as tooltips",
			'width'=>'50%',
			'sort'=>0,
			'format'=>'{?name}'
		)));
		$this->add_col( new ListBlockCol_EffortDate);
		$this->add_col( new ListBlockCol_EffortDateEnd);
		$this->add_col( new ListBlockCol_EffortAmount);
		$this->add_col( new ListBlockCol_DayGraph);*/
		$this->add_col(new ListBlockCol_ChangesEditType());
		$this->add_col(new ListBlockCol_ChangesItemType());
        $this->add_col(new ListBlockCol_ChangesItemName());
        $this->add_col(new ListBlockCol_ChangesByPerson());
        $this->add_col(new ListBlockCol_Person(array(
			'key'=>'created_by',
            'name'=>'Created by',
            'tooltip'=>'Item was originally created by',
        )));

        $this->add_col( new listBlockColDate(array(
			'key'=>'modified',
            'name'=>'modified'
        )));
        $this->add_col(new ListBlockCol_ChangesItemState());
        $this->add_col( new ListBlockColPubLevel());



        /*#---- functions ----
        $this->add_function(new ListFunction(array(
            'target'=>$PH->getPage('chanEdit')->id,
            'name'  =>'Edit effort',
            'id'    =>'effortEdit',
            'icon'  =>'edit',
            'context_menu'=>'submit',
        )));
        $this->add_function(new ListFunction(array(
            'target'=>$PH->getPage('effortNew')->id,
            'name'  =>'New effort',
            'id'    =>'effortNew',
            'icon'  =>'new',
            'context_menu'=>'submit',
        )));
        $this->add_function(new ListFunction(array(
            'target'=>$PH->getPage('effortDelete')->id,
            'name'  =>'Delete',
            'id'    =>'effortDelete',
            'icon'  =>'delete',
            'context_menu'=>'submit',
        )));
        */
    }
}





/**
* returns the type of latest edit (create,modified,deleted)
*
*/
class ListBlockCol_ChangesEditType extends ListBlockCol
{
    public $name='C';
    public $tooltip="Created,Modified or Deleted";

	function render_tr(&$obj, $style="") {
        $date_created=$obj->created;
        $date_modified=$obj->modified;
        $date_deleted=$obj->deleted;

       
        $str="";
        if($date_deleted >= $date_modified) {
            $str= "Deleted";
        }
        else if($date_modified > $date_created) {
            $str= "Modified";
        }
        else {
            $str= "Created";
        }

		print "<td>$str</td>";
	}
}


/**
* prints person causing change (depending what happed last)
*
*/
class ListBlockCol_ChangesByPerson extends ListBlockCol
{
    public $name='by Person';
    public $tooltip="Person who did the last change";
    public $key= 'modified_by';

	function render_tr(&$obj, $style="") {
        $date_created=$obj->created;
        $date_modified=$obj->modified;
        $date_deleted=$obj->deleted;

        $person=NULL;
        $str="";
        if($date_deleted >= $date_modified) {
            $person= new Person($obj->deleted_by);
        }
        else if($date_modified > $date_created) {
            $person= new Person($obj->modified_by);
        }
        else {
            $person= new Person($obj->created_by);
        }

        $str_link="";
        if($person) {
            $str_link=$person->getLink();
        }
		print "<td>$str_link</td>";
	}
}


/**
* prints person causing change (depending what happed last)
*
*/
class ListBlockCol_Person extends ListBlockCol
{


	function render_tr(&$obj, $style="") {

        $key=$this->key;
        if($person= new Person($obj->$key)) {
            $str_link=$person->getLink();
        }
		print "<td>$str_link</td>";
	}
}


/**
* returns the type of item
*
*/
class ListBlockCol_ChangesItemType extends ListBlockCol
{
    public $name='T';
    public $tooltip="Item of item: [T]ask, [C]omment, [E]ffort, etc ";
    public $key= 'type';

	function render_tr(&$obj, $style="") {
	    global $g_item_names;
        if(!$typename= $g_item_names[$obj->type]) {
            trace("WARNING","undefined item-type",$obj);
        }
       
		print "<td>$typename</td>";
	}
}


/**
* returns the state of item (alive/deleted)
*
*/
class ListBlockCol_ChangesItemState extends ListBlockCol
{
    public $name='Del';
    public $tooltip="is item deleted";
    public $key= 'state';

	function render_tr(&$obj, $style="") {
	    $str= "?";
        switch($obj->state) {
            case 1:
                $str="";
                break;
            case -1:
                $str="deleted";
                break;
                
        }
       
		print "<td>$str</td>";
	}
}

/**
* returns the name and link to item
*
*/
class ListBlockCol_ChangesItemName extends ListBlockCol
{
    public $name='Name';
    public $tooltip="";
    public $width="80%";
    
	function render_tr(&$obj, $style="") 
	{
	    global $PH;

        $str_url="";
        $str_name="";
        $str_addon="";
        switch($obj->type) {
            case ITEM_TASK:
                if($task= new Task($obj->id)) {
                    $str_name= $task->name;
                    $str_url= $PH->getUrl('taskView',array('tsk'=>$task->id));
                }
                break;

            case ITEM_COMMENT:
                require_once("db/class_comment.inc");
                if($comment= new Comment($obj->id)) {
                    $str_name= $comment->name;
                    if($comment->comment) {
                        $str_url= $PH->getUrl('taskView',array('tsk'=>$comment->task));
                        $str_addon="(on comment)";
                    }

                    else if($comment->task) {
                        $str_url= $PH->getUrl('taskView',array('tsk'=>$comment->task));
                        $str_addon="(on task)";

                    }

                    else {
                        $str_url= $PH->getUrl('projView',array('prj'=>$comment->project));
                        $str_addon="(on project)";
                    }
                }
                break;

            case ITEM_PROJECTPERSON:
                if($pp= new ProjectPerson($obj->id)) {
                    if(!$person= new Person($pp->person)) {
                        $PH->abortWarning("ProjectPerson has invalid person-pointer!",ERROR_BUG);
                    }
                    $str_name= $person->name;
                    $str_url= $PH->getUrl('personView',array('person'=>$person->id));
                    
                }
                break;

            case ITEM_EFFORT:
                require_once("db/class_effort.inc");
                if($e= new Effort($obj->id)) {
                    $str_name= $e->name;
                    $str_url= $PH->getUrl('effortEdit',array('effort'=>$e->id));
                    
                }
                break;

            default:
                break;
            
        }
		print "<td><a href='$str_url'>$str_name</a> $str_addon</td>";
	}
}





?>