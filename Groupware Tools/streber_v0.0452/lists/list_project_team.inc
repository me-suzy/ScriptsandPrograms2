<?php
# streber - a php based project management system
# Copyright (c) 2005 Thomas Mann - thomas@pixtur.de
# Distributed under the terms and conditions of the GPL as stated in docs/license.txt

/**
 * derived ListBlock-class for listing project persons
 *
 * @includedby:     pages/company.inc, pages/person.inc, pages/proj.inc
 *
 * @author:         Thomas Mann
 * @uses:           ListBlock
 * @usedby:
 *
 */

class ListBlock_projectTeam extends ListBlock {

    public function __construct() {
        $this->id='projectpersons';
        $this->bg_style='bg_people';

		$this->title="Your related persons";
		$this->add_col(new ListBlockCol(array(
			'key'=>'_select_col_',
			'name'=>"S",
		)));
   		/*$this->add_col( new ListBlockColHtml(array(
			'key'=>'nickname',
			'name'=>"Name Short",
			'tooltip'=>"Shortnames used in other lists",
			'sort'=>0,
			'format'=>'<nobr><a href="index.php?go=personView&person={?id}">{?nickname}</a></nobr>'
		)));*/

   		$this->add_col( new ListBlockCol_ProjectPersonName());
   		$this->add_col( new ListBlockCol_ProjectRole());
   		$this->add_col( new ListBlockColHtml(array(
			'key'=>'role',
			'name'=>"Rights",
			'tooltip'=>"Persons rights in this project",
			'format'=>'{?role}'
		)));
   		/*
        $this->add_col( new ListBlockColHtml(array(
			'key'=>'phone_personal',
			'name'=>"Private",
			'format'=>'<nobr>{?phone_personal}</nobr>'
		)));
   		$this->add_col( new ListBlockColHtml(array(
			'key'=>'mobile',
			'name'=>"Mobil",
			'format'=>'<nobr>{?phone_mobile}</nobr>'
		)));
   		$this->add_col( new ListBlockColHtml(array(
			'key'=>'office',
			'name'=>"Office",
			'format'=>'<nobr>{?phone_office}</nobr>'
		)));
   		$this->add_col( new ListBlockColHtml(array(
			'key'=>'tagline',
			'name'=>"Tagline",
			'format'=>'{?tagline}'
		)));
    	$this->add_col( new ListBlockColMethod(array(
    		'name'=>"Companies",
    		'sort'=>0,
    		'func'=>'getCompanyLinks',
    	)));

        /*$this->add_col( new ListBlockCol_ProjectEffortSum);

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
        */

        #---- functions ----
        global $PH;
        $this->add_function(new ListFunction(array(
            'target'=>$PH->getPage('projectPersonEdit')->id,
            'name'  =>'Edit team member',
            'id'    =>'projectPersonEdit',
            'icon'  =>'edit',
            'context_menu'=>'submit',
        )));
        $this->add_function(new ListFunction(array(
            'target'=>$PH->getPage('projAddPerson')->id,
            'name'  =>'Add team member',
            'id'    =>'projectPersonAdd',
            'icon'  =>'add',
            'context_menu'=>'submit',
        )));
        $this->add_function(new ListFunction(array(
            'target'=>$PH->getPage('projectPersonDelete')->id,
            'name'  =>'Remove person from team',
            'id'    =>'projectPersonDelete',
            'icon'  =>'sub',
            'context_menu'=>'submit',
        )));
        /*$this->add_function(new ListFunction(array(
            'target'=>$PH->getPage('personDelete')->id,
            'name'  =>'Delete person',
            'id'    =>'personDelete',
            'icon'  =>'delete'
        )));
        $this->add_function(new ListFunction(array(
            'target'=>$PH->getPage('personNew')->id,
            'name'  =>'Create new person',
            'id'    =>'personNew',
            'icon'  =>'new',
            'context_menu'=>'submit',
        )));
        */
    }
}

class ListBlockCol_ProjectPersonName extends ListBlockCol
{
    public $name='Member';
    public $key='person';

    public function __construct($args=NULL) {
        parent::__construct($args);
    }

	function render_tr(&$pp, $style="") {

        if($person= $pp->getPerson()) {

		    print "<td>".$person->getLink()."</td>";
        }
	}
}


class ListBlockCol_ProjectRole extends ListBlockCol
{
    public $name='Role';
    public $key='view_level';

    public function __construct($args=NULL) {
        parent::__construct($args);
    }

	function render_tr(&$obj, $style="") 
	{
     

		print "<td>$obj->name</td>";
	}
}

?>