<?php
# streber - a php based project management system
# Copyright (c) 2005 Thomas Mann - thomas@pixtur.de
# Distributed under the terms and conditions of the GPL as stated in docs/license.txt

/**
 * derived ListBlock-class for listing persons
 *
 * @includedby:     pages/company.inc, pages/person.inc, pages/proj.inc
 *
 * @author:         Thomas Mann
 * @uses:           ListBlock
 * @usedby:
 *
 */

class ListBlock_persons extends ListBlock {

    public function __construct() {
        $this->id='persons';
        $this->bg_style='bg_people';

		$this->title="Your related persons";
		$this->add_col(new ListBlockCol(array(
			'key'=>'_select_col_',
			'name'=>"S",
		)));
   		$this->add_col( new ListBlockColHtml(array(
			'key'=>'nickname',
			'name'=>"Name Short",
			'tooltip'=>"Shortnames used in other lists",
			'sort'=>0,
			'format'=>'<nobr><a href="index.php?go=personView&person={?id}">{?nickname}</a></nobr>'
		)));

    	$this->add_col( new ListBlockCol_PersonProfile());
    	$this->add_col( new ListBlockCol_PersonProjects());

   		$this->add_col( new ListBlockColHtml(array(
			'key'=>'name',
			'name'=>"Name",
			'tooltip'=>"Task name. More Details as tooltips",
			'sort'=>0,
			'format'=>'<nobr><span class="item person"><a class="item person" href="index.php?go=personView&person={?id}">{?name}</a></span></nobr>'
		)));
   		$this->add_col( new ListBlockColHtml(array(
			'key'=>'personal_phone',
			'name'=>"Private",
			'format'=>'<nobr>{?personal_phone}</nobr>'
		)));
   		$this->add_col( new ListBlockColHtml(array(
			'key'=>'mobile_phone',
			'name'=>"Mobil",
			'format'=>'<nobr>{?mobile_phone}</nobr>'
		)));
   		$this->add_col( new ListBlockColHtml(array(
			'key'=>'office_phone',
			'name'=>"Office",
			'format'=>'<nobr>{?office_phone}</nobr>'
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
    		'id'=>'companies',
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
            'target'=>$PH->getPage('personEdit')->id,
            'name'  =>'Edit person',
            'id'    =>'personEdit',
            'icon'  =>'edit',
            'context_menu'=>'submit',
        )));
        $this->add_function(new ListFunction(array(
            'target'=>$PH->getPage('personEditRights')->id,
            'name'  =>'Edit User Rights',
            'id'    =>'personEditRights',
            'context_menu'=>'submit',
        )));
        $this->add_function(new ListFunction(array(
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
    }
}



/**
* column user-profile
*
*/
class ListBlockCol_PersonProfile extends ListBlockCol
{
    public $name='Profile';
    public $tooltip="Account settings for user (do not confuse with project rights)";
    public $id='profile';

	function render_tr(&$person, $style="") 
	{
	    global $g_user_profile_names;
	    global $g_user_profiles;

        $profile_num= $person->profile; 
        $str_profile= $g_user_profile_names[$profile_num]; 
        $default_rights= $g_user_profiles[$g_user_profile_names[$profile_num]]['default_user_rights']; 

        if($default_rights != $person->user_rights) { 
            $str_profile.="(adjusted)"; 
        }
		print "<td>$str_profile</td>";
	}
}


/**
* column active projects
*
*/
class ListBlockCol_PersonProjects extends ListBlockCol
{
    public $name='Active Projects';
    public $tooltip="Account settings for user (do not confuse with project rights)";
    public $id='projects';
    public $width="70%";

	function render_tr(&$person, $style="") 
	{
	    global $PH;


    	$projects= $person->getProjects();
    	print "<td>";

        if($projects) {
            $str_delimiter="";
    	    foreach($projects as $p){
    	        
    	        ### ###
                $img_prio= "<img src=\"themes/".getCurTheme()."/img/prio_{$p->prio}.png\">";    	        
    	        $link= $PH->getLink('projView',$p->getShort(),array('prj'=>$p->id));

                print  $str_delimiter .  $img_prio.$link;    	        

    	        $str_delimiter=", ";
    	        
    	    }
    	}
    	print "</td>";
	}
}

?>