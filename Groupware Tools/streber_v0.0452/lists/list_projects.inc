<?php
# streber - a php based project management system
# Copyright (c) 2005 Thomas Mann - thomas@pixtur.de
# Distributed under the terms and conditions of the GPL as stated in docs/license.txt

/**
 * derived ListBlock-class for listing projects
 *
 * @includedby:     pages/*
 *
 * @author:         Thomas Mann
 * @uses:           ListBlock
 * @usedby:
 *
 */

class ListBlock_projects extends ListBlock {

    public function __construct() {
        $this->id='projects';
        $this->bg_style='bg_projects';

		$this->title="related Projects";
		$this->add_col(new ListBlockCol(array(
			'key'=>'_select_col_',
			'name'=>"S",
			'tooltip'=>"Project state (the icons have tooltips, too)",
		)));
		$this->add_col( new ListBlockColPrio(array(
			'key'=>'prio',
			'name'=>"P",
			'tooltip'=>"Project priority (the icons have tooltips, too)",
			'sort'=>1
		)));
		$this->add_col( new ListBlockColStatus(array(
			'key'=>'status',
			'name'=>"S",
			'tooltip'=>"Task-Status",
			'sort'=>0
		)));
    	$this->add_col( new ListBlockColMethod(array(
            'key'=>'company',
    		'name'=>"Company",
    		'tooltip'=>"Company",
    		'func'=>'getCompanyLink',
    	)));
   		/*$this->add_col( new ListBlockColHtml(array(
			'key'=>'short',
			'name'=>"Name Short",
			'tooltip'=>"Shortnames used in other lists",
			'sort'=>0,
			'format'=>'<nobr><a href="index.php?go=projView&prj={?id}">{?short}</a></nobr>'
		)));*/
   		$this->add_col( new ListBlockColHtml(array(
			'key'=>'name',
			'name'=>"Name",
			'tooltip'=>"Task name. More Details as tooltips",
			'width'=>'30%',
			'sort'=>0,
			'format'=>'<a href="index.php?go=projView&prj={?id}">{?name}</a>'
		)));

   		$this->add_col( new ListBlockColHtml(array(
			'key'=>'status_summary',
			'name'=>"Status Summary",
			'tooltip'=>"Short discription of the current status",
			'format'=>'{?status_summary}'
		)));

        $this->add_col( new ListBlockCol_ProjectPersons);

        $this->add_col( new ListBlockCol_ProjectEffortSum);

    	$this->add_col( new ListBlockColMethod(array(
    		'name'=>"Tasks",
    		'tooltip'=>"Number of open Tasks",
    		'sort'=>0,
    		'func'=>'getNumTasks',
            'style'=>'right'
    	)));
   		$this->add_col( new ListBlockColDate(array(
			'key'=>'date_start',
			'name'=>"Opened",
			'tooltip'=>"Day the Project opened",
			'sort'=>0,
		)));
   		$this->add_col( new ListBlockColDate(array(
			'key'=>'date_closed',
			'name'=>"Closed",
			'tooltip'=>"Day the Project state changed to closed",
			'sort'=>0,
		)));

        #---- functions ----
        global $PH;
        $this->add_function(new ListFunction(array(
            'target'=>$PH->getPage('projEdit')->id,
            'name'  =>'Edit project',
            'id'    =>'projEdit',
            'icon'  =>'edit',
            'context_menu'=>'submit',
        )));
        $this->add_function(new ListFunction(array(
            'target'=>$PH->getPage('projDelete')->id,
            'name'  =>'Delete project',
            'id'    =>'projDelete',
            'icon'  =>'delete'
        )));

        $this->add_function(new ListFunction(array(
            'target'=>$PH->getPage('effortNew')->id,
            'name'  =>'Log hours for a project',
            'id'    =>'effortNew',
            'context_menu'=>'submit',
            'icon'  =>'loghours',
        )));

        $this->add_function(new ListFunction(array(
            'id'=>'state_open',
            'target'=>$PH->getPage('projChangeStatus')->id,
            'name'  =>'Open / Close',
            'id'    =>'projState',
            'context_menu'=>'submit',
        )));

        $this->add_function(new ListFunction(array(
            'target'=>$PH->getPage('projNew')->id,
            'name'  =>'Create new project',
            'id'    =>'projNew',
            'icon'  =>'new',
            'context_menu'=>'submit',
        )));

        $this->add_function(new ListFunction(array(
            'target'=>$PH->getPage('projDuplicate')->id,
            'name'  =>'Duplicate project',
            'id'    =>'projDuplicate',
            'dropdown_menu'=>true,
            'context_menu'=>'submit',

        )));
    }
}


/**
* column active projects
*
*/
class ListBlockCol_ProjectPersons extends ListBlockCol
{
    public $name='People';
    public $tooltip="... working in project";
    public $id='persons';
    public $width="70%";

	function render_tr(&$project, $style="") 
	{
	    global $PH;


    	print "<td>";
    	if($pps= $project->getProjectPersons()) {

            $str_delimiter="";
    	    foreach($pps as $pp){
    	        
    	        ### ###
    	        if( $person= new Person($pp->person)) {
                    #$img_prio= "<img src=\"themes/".getCurTheme()."/img/prio_{$p->prio}.png\">";    	        
    	            $link= $PH->getLink('personView',$person->getShort(),array('person'=>$person->id));

                    print  $str_delimiter . $link;    	        

    	            $str_delimiter=", ";
    	        }
    	        
    	    }
    	}
    	print "</td>";
	}
}

?>