<?php

{
    global $projectperson_fields;
    $$projectperson_fields=array();
    addProjectItemFields(&$projectperson_fields);

    foreach(array(
        new FieldInternal(array(    'name'=>'id',
            'default'=>0,
            'in_db_object'=>1,
            'in_db_item'=>1,
        )),
        new FieldInternal(array(    'name'=>'state',
            'default'=>1,
            'in_db_object'=>1,
            'in_db_item'=>1,
        )),
        new FieldInternal(array(    'name'=>'person',
        )),
        new FieldInternal(array(    'name'=>'project',
            'default'=>0,
            'in_db_object'=>1,
            'in_db_item'=>1,
        )),
        new FieldString(array(      'name'=>'name',
            'default'=>'member',
            'title'=>'job',
        )),

        new FieldInternal(array(    'name'=>'proj_rights',
        )),
        new FieldInternal(array(    'name'=>'level_create',
            'default'=>PUB_LEVEL_OPEN,
        )),
        new FieldInternal(array(    'name'=>'level_view',
            'default'=>PUB_LEVEL_OPEN,
        )),
        new FieldInternal(array(    'name'=>'level_edit',
            'default'=>PUB_LEVEL_OPEN,
        )),
        new FieldInternal(array(    'name'=>'level_reduce',
            'default'=>PUB_LEVEL_OPEN,
        )),
        new FieldInternal(array(    'name'=>'level_delete',
            'default'=>PUB_LEVEL_OPEN,
        )),
        new FieldInternal(array(    'name'=>'role',  # this is only a cache for string-output
            'default'=>'member',
        )),
    ) as $f) {
        $projectperson_fields[$f->name]=$f;
    }
}


//====================================================================
// Project Person - jointable between projects and persons
//====================================================================
class ProjectPerson extends DbProjectItem {
    public $name;
    public $project;

	//=== constructor ================================================
	function __construct ($id_or_array=NULL)
    {
        global $projectperson_fields;
        $this->fields= &$projectperson_fields;

        parent::__construct($id_or_array);
        $this->type=ITEM_PROJECTPERSON;
   	}

    /**
    * give person-object to this projectProject
    */
    public function getPerson()
    {
        return new Person($this->person);
    }
    
    
    /**
    * set attributes to values defined in profile-list
    */
    public function initWithUserProfile($profile_name=NULL) 
    {
        if(is_null($profile_name)) {
            trace("ERROR","initWithProfile() needes profile");
        }
        global $g_user_profile_names;
        global $g_user_profiles;

        if(!isset($g_user_profiles[$profile_name])) {
            trace("ERROR","undefined profile '$profile_name'");
            return NULL;
        }
        $profile= $g_user_profiles[$profile_name];

        ### try to initialize standard-values ###
        if(isset($profile['job_name'])) {
            $this->name= $profile['job_name'];
        }
        $this->role= $profile_name ;


        ### build assoc array of defined members ###
        $vars=array();
        foreach(get_object_vars($this) as $key=>$value) {
            $vars[$key]=true;
        }
            
        ### try to initialize other values if profile-attribute-name matches with pp-member ###
        foreach($profile as $key=>$value) {
            if(isset($vars[$key])) {  
                $this->$key= $value;
            }
            else {
                #trace("WARNING","person '$person->name' has undefined variable '$key' in profile");
            }
        }
    }
}




?>