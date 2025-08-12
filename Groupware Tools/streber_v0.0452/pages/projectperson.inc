<?php
# streber - a php5 based project management system  (c) 2005 Thomas Mann / thomas@pixtur.de
# Distributed under the terms and conditions of the GPL as stated in _docs/license.html


/**
*
* editing persons in project
*
*/


require_once("db/class_task.inc");
require_once("db/class_project.inc");
require_once("db/class_projectperson.inc");
require_once("render/render_list.inc");


/**
* this function is not used for now
*
* the "normal" way to create projectPersons is be pages/proj.inc > projAddPerson()
*/
/*
function projectPersonNew()
{
    global $PH;

    $name=get('new_name')
        ? get('new_name')
        :"Team Member";

    ### first try single project-id ###
    $id=getOnePassedId('prj','projects_*',true,'No project selected ?');
    if(!$project= new Project($id)) {
        $PH->abortWarning("ERROR: could not get Project",ERROR_FATAL);
    }

    ### build new object ###
    $pp= new ProjectPerson(array(
        'id'=>0,
        'name'=>$name,
        'project'=>$project->id,
        )
    );
    $PH->show('projectPersonEdit',array('projectperson'=>$newProjectPerson->id),$newProjectPerson);
}
*/


function projectPersonEdit($pp= NULL)
{
    global $PH;

    if($pp) {

    }
    else {
        $id= getOnePassedId('projectperson','projectpersons*', true, 'No team member selected?');   # WARNS if multiple; ABORTS if no id found
        if(!$pp= new ProjectPerson($id)) {
            $PH->abortWarning("ERROR: could not get Project Person");
            return;
        }
    }

    ### get project ###
    if(!$project= new Project($pp->project)) {
        $PH->abortWarning("ERROR: could not get project");
    }

    ### get person ###
    if(!$person= new Person($pp->person)) {
        $PH->abortWarning("ERROR: could not get person");
    }

    ### set up page and write header ####
    {
        $page= new Page(array('use_jscalendar'=>true, 'autofocus_field'=>'projectperson_name'));
    	$page->cur_tab='projects';
        $page->type="Edit Team Member";
        $page->title="role of ".$person->name. " in ".$project->name;

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
        require_once("render/render_form.inc");

    $form=new PageForm();
    $form->button_cancel=true;

    ### create an assoc with roles and find the current role ###
    {
        global $g_theme_names;
        global $g_user_profile_names;
        global $g_user_profiles;
        
        ### display "undefined" profile if rights changed ###
        # will be skipped when submitting
        $profile_num= 0;#;$person->profile;
        $reset="";
        #$profile_settings= $g_user_profiles[$g_user_profile_names[$profile_num]];


        $count=0;
        foreach($g_user_profiles as $profile_name => $profile_settings) {
            
            if($pp->level_view          == $profile_settings['level_view']
                && $pp->level_create    == $profile_settings['level_create']
                && $pp->level_edit      == $profile_settings['level_edit']
                && $pp->level_delete    == $profile_settings['level_delete']
            ){
                $profile_num=$count;
                break;
            }
            $count++;
        }            

        $form->add(new Form_Dropdown('person_profile',  "Role in this project", array_flip($g_user_profile_names), $profile_num));
    }


        $form->add($pp->fields['name']->getFormElement(&$pp));


        echo ($form);

        $PH->go_submit='projectPersonEditSubmit';
        echo "<input type=hidden name='projectperson' value='$pp->id'>";
        echo "<input type=hidden name='projectperson_project' value='$pp->project'>";
    }
    echo (new PageContentClose);
}


function projectPersonEditSubmit()
{
    global $PH;

    ### cancel ###
    if(get('form_do_cancel')) {
        if(!$PH->showFromPage()) {
            $PH->show('projView',array('prj'=>$pp->project));
        }
        exit;
    }

    ### get projectperson ####
    $id= getOnePassedId('projectperson',true,'invalid id');

    if($id == 0) {
        $pp= new ProjectPerson(array('id'=>0));
    }
    else {
        $pp= new ProjectPerson($id);
        if(!$pp) {
            $PH->abortWarning("Could not get project person");
            return;
        }
    }

    ### get project ###
    if(!$project= new Project($pp->project)) {
        $PH->abortWarning("ERROR: could not get project",ERROR_FATAL);
    }

    ### get person ###
    if(!$person= new Person($pp->person)) {
        $PH->abortWarning("ERROR: could not get project",ERROR_FATAL);
    }

    # retrieve all possible values from post-data
    # NOTE:
    # - this could be an security-issue.
    # - TODO: as some kind of form-edit-behaviour to field-definition
    foreach($pp->fields as $f) {
        $name=$f->name;
        $f->parseForm(&$pp);
    }



    ### set rights role ###
    /**
    * if profile != 0, it will OVERWRITE (or reinit) user_rights
    *
    * therefore persEdit set profil to 0 if rights don't fit profile. It will
    * then be skipped here
    */
    if($profile=get('person_profile')) {

        global $g_user_profile_names;
        global $g_user_profiles;

        if($profile_settings= $g_user_profiles[$g_user_profile_names[$profile]]) {
            $pp->level_view = $profile_settings['level_view'];
            $pp->level_edit = $profile_settings['level_edit'];
            $pp->level_create =$profile_settings['level_create'];
            $pp->level_delete = $profile_settings['level_delete'];
            $pp->level_reduce = $profile_settings['level_reduce'];

            $pp->role=$g_user_profile_names[$profile];
            $PH->message="Changed role of <b>'$person->name'</b> to <b>'".$g_user_profile_names[$profile]."'</b>";

        }
    }

    ### write to db ###
    if($pp->id == 0) {
        $pp->insert();
    }
    else {
        $pp->update();
    }

    ### return to from-page ###
    if(!$PH->showFromPage()) {
        $PH->show('projView',array('prj'=>$pp->project));
    }
}


function projectPersonDelete()
{
    global $PH;

    ### get project person ####
    $ids= getPassedIds('projectperson','projectpersons_*');

    if(!$ids) {
        $PH->abortWarning("No team-member(s) selected?",ERROR_NOTE);
        return;
    }

    $counter=0;
    $errors=0;
    foreach($ids as $id) {
        $pp= new ProjectPerson($id);
        if(!$pp) {
            $PH->abortWarning("Invalid project person-id!");
        }
        if($pp->delete()) {
            $counter++;
        }
        else {
            $errors++;
        }
    }
    if($errors) {
        $PH->message="WARNING: Failed to remove $errors members from team";
    }
    else {
        $PH->message="Unassigned $counter team member(s) from project";
    }

    if(!$PH->showFromPage()) {
        $PH->show('projView',array('prj'=>$pp->project));
    }
}


?>