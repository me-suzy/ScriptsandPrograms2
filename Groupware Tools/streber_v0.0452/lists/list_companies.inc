<?php
# streber - a php based project management system
# Copyright (c) 2005 Thomas Mann - thomas@pixtur.de
# Distributed under the terms and conditions of the GPL as stated in docs/license.txt

/**
 * derived ListBlock-class for listing companies
 *
 * @includedby:     pages/*
 *
 * @author:         Thomas Mann
 * @uses:           ListBlock
 * @usedby:
 *
 */

class ListBlock_companies extends ListBlock {

    public function __construct() {
        $this->id='companies';
        $this->bg_style='bg_people';

		$this->title="related companies";
		$this->add_col(new ListBlockCol(array(
			'key'=>'_select_col_',
			'name'=>"S",
			'tooltip'=>"Project state (the icons have tooltips, too)",
		)));
   		$this->add_col( new ListBlockColHtml(array(
			'key'=>'short',
			'name'=>"Name Short",
			'tooltip'=>"Shortnames used in other lists",
			'sort'=>0,
			'format'=>'<nobr><a href="index.php?go=companyView&company={?id}">{?short}</a></nobr>'
		)));
   		$this->add_col( new ListBlockColHtml(array(
			'key'=>'name',
			'name'=>"Name",
			'width'=>'90%',
			'sort'=>0,
			'format'=>'<a class="item company" href="index.php?go=companyView&company={?id}">{?name}</a>'
		)));
   		$this->add_col( new ListBlockColHtml(array(
			'key'=>'phone',
			'name'=>"Phone",
			'tooltip'=>"Phone-Number",
			'format'=>'<nobr>{?phone}</nobr>'
		)));
   		$this->add_col( new ListBlockColLinkExtern(array(
			'key'=>'homepage',
			'name'=>"Homepage",
		)));
    	$this->add_col( new ListBlockColMethod(array(
    		'name'=>"Proj",
    		'tooltip'=>"Number of open Projects",
    		'func'=>'getNumOpenProjects',
            'style'=>'right'
    	)));
    	$this->add_col( new ListBlockColMethod(array(
    		'name'=>"People",
            'id'=>'people',
    		'tooltip'=>"People working for this person",
    		'sort'=>0,
            'style'=>'nowrap',
    		'func'=>'getPersonLinks',
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
            'target'=>$PH->getPage('companyEdit')->id,
            'name'  =>'Edit company',
            'id'    =>'companyEdit',
            'icon'  =>'edit',
            'context_menu'=>'submit',
        )));
        $this->add_function(new ListFunction(array(
            'target'=>$PH->getPage('companyDelete')->id,
            'name'  =>'Delete company',
            'id'    =>'companyDelete',
            'icon'  =>'delete'
        )));
        $this->add_function(new ListFunction(array(
            'target'=>$PH->getPage('companyNew')->id,
            'name'  =>'Create new company',
            'id'    =>'companyNew',
            'icon'  =>'new',
            'context_menu'=>'submit',
        )));
    }
}

?>