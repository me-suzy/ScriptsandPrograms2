<?php

require_once("db/class_task.inc");
require_once("db/class_project.inc");
require_once("db/class_person.inc");
require_once("render/render_list.inc");
require_once("lists/list_persons.inc");
require_once("lists/list_efforts.inc");


#=====================================================================================================
# personList active (people with account)
#=====================================================================================================
function personListAccounts() {
    global $PH;
    global $auth;

    ### create from handle ###
    $PH->defineFromHandle();

    ### set up page and write header ####
    {
        $page= new Page();
    	$page->cur_tab='people';
        $page->title="Active People";
        if(!($auth->cur_user->user_rights & RIGHT_PROJECT_VIEWALL)) {
            $page->title_minor="relating to ". $page->title_minor=$auth->cur_user->name;
        }
        else {
            $page->title_minor="admin view";
        }
        $page->type="List";

        $page->options=array(
            new NaviOption(array('target_id'=>'personListAccounts',           'name'=>'With Account')),
            new NaviOption(array('target_id'=>'personList',           'name'=>'All')),
            #new NaviOption(array('target_id'=>'companyList',     'name'=>'Companies')),
            #new NaviOptionSeparator(),
            new NaviOptionWiki()
        );


    	### render title ###
        echo(new PageHeader);
    }
    echo (new PageContentOpen);

	#--- list persons --------------------------------------------------------
	{
        $order_by=get('sort_'.$PH->cur_page->id."_persons");

    	$persons=Person::getAll($order_by,true);

        $list= new ListBlock_persons();
        $list->title= "People/Project Overview";
        unset($list->columns['office_phone']);
        unset($list->columns['tagline']);
        unset($list->columns['name']);
        unset($list->columns['personal_phone']);
        unset($list->columns['companies']);
        
        if($auth->cur_user->user_rights & RIGHT_PERSON_CREATE) {
            $list->no_items_html=$PH->getLink('personNew','');
        }
        else {
            $list->no_items_html="no related persons";
        }
        $list->render_list(&$persons);
	}

    echo(new PageContentClose);
	echo(new PageHtmlEnd);
}

#=====================================================================================================
# personList active (people with account)
#=====================================================================================================
function personList() {
    global $PH;
    global $auth;

    ### create from handle ###
    $PH->defineFromHandle();

    ### set up page and write header ####
    {
        $page= new Page();
    	$page->cur_tab='people';
        $page->title="Persons";
        if(!($auth->cur_user->user_rights & RIGHT_PROJECT_VIEWALL)) {
            $page->title_minor="relating to ". $page->title_minor=$auth->cur_user->name;
        }
        else {
            $page->title_minor="admin view";
        }
        $page->type="List";

        $page->options=array(
            new NaviOption(array('target_id'=>'personListAccounts',           'name'=>'With Account')),
            new NaviOption(array('target_id'=>'personList',           'name'=>'All')),
            #new NaviOption(array('target_id'=>'companyList',     'name'=>'Companies')),
            #new NaviOptionSeparator(),
            new NaviOptionWiki()
        );



    	### render title ###
        echo(new PageHeader);
    }
    echo (new PageContentOpen);

	#--- list persons --------------------------------------------------------
	{
        $order_by=get('sort_'.$PH->cur_page->id."_persons");

    	$persons=Person::getAll($order_by,false);

        $list= new ListBlock_persons();
        $list->title= $page->title;
        unset($list->columns['profile']);
        unset($list->columns['projects']);

        
        if($auth->cur_user->user_rights & RIGHT_PERSON_CREATE) {
            $list->no_items_html=$PH->getLink('personNew','');
        }
        else {
            $list->no_items_html="no related persons";
        }
        $list->render_list(&$persons);
	}



    echo(new PageContentClose);
	echo(new PageHtmlEnd);
}




#=====================================================================================================
# personView
#=====================================================================================================
function personView() {
    global $PH;
    global $auth;

	### get current person ###
    $id=getOnePassedId('person','persons_*');
    if(!$person= new Person($id)) {
        $PH->abortWarning("invalid person-id");
		return;
	}

    ### validate view right ###
    $person->validateView();

    ### create from handle ###
    $PH->defineFromHandle(array('person'=>$person->id));

    ### set up page ####
    {
        $page= new Page();
    	$page->cur_tab='people';
        $page->title=$person->name;
        $page->title_minor="Overview";
        $page->type="Person";

        ### breadcrumbs  ###
        $page->crumbs=array(
            $PH->getLink('personList'),
    		$PH->getLink('personView',$person->name,array('person'=>$person->id)),
    	);

        $page->options=array(
            new NaviOption(array('target_id'=>'personView',         'name'=>'Overview', 'target_params'=>array('person'=>$id ))),
            new NaviOption(array('target_id'=>'personViewEfforts',         'name'=>'Efforts', 'target_params'=>array('person'=>$id ))),
            new NaviOptionWiki()
        );


        ### page functions ###
        $page->add_function(new PageFunction(array(
            'target'=>'personEdit',
            'params'=>array('person'=>$person->id),
            'icon'=>'edit',
            'tooltip'=>'Edit this person',
            'name'=>'Profile',
        )));
        $page->add_function(new PageFunction(array(
            'target'=>'personEditRights',
            'params'=>array('person'=>$person->id),
            'icon'=>'edit',
            'tooltip'=>'Edit User Rights',
            'name'=>'User Rights',
        )));
        $page->add_function(new PageFunction(array(
            'target'=>'companyNew',
            'params'=>array('person'=>$person->id),
            'icon'=>'new',
            'tooltip'=>'Create new Company',
        )));


    	### render title ###
        echo(new PageHeader);
    }
    echo (new PageContentOpen_Columns);

    #--- write info block ------------
    {
        $block=new PageBlock(array('title'=>'Summary'));
        $block->render_blockStart();
        echo "<div class=text>";
        if($person->comments) {
            echo "$person->comments";
        }
        if($person->personal_street) {
            echo "<p><label>Adress</label>$person->personal_street</p>";
        }
        if($person->personal_zipcode) {
            echo "<p><label></label>$person->personal_zipcode</p>";
        }
        if($person->mobile_phone) {
            echo "<p><label>Mobile:</label>$person->mobile_phone</p>";
        }
        if($person->office_phone) {
            echo "<p><label>Office:</label>$person->office_phone</p>";
        }
        if($person->personal_phone) {
            echo "<p><label>Private:</label>$person->personal_phone</p>";
        }
        if($person->office_fax) {
            echo "<p><label>Fax:</label>$person->office_fax</p>";
        }
        if($person->office_homepage) {
            echo "<p><label>Web:</label>".url2linkExtern($person->office_homepage)."</p>";
        }
        if($person->personal_homepage) {
            echo "<p><label>Web:</label>".url2linkExtern($person->personal_homepage)."</p>";
        }

        if($person->office_email) {
            echo "<p><label>E-Mail:</label>".url2linkMail($person->office_email)."</p>";
        }
        if($person->personal_email) {
            echo "<p><label>E-Mail:</label>".url2linkMail($person->personal_email)."</p>";
        }

        /*if($person->intranet) {
            echo "<p><label>Intra:</label>".url2linkExtern($person->intranet)."</p>";
        }
        if($person->email) {
            echo "<p><label>Mail:</label>".url2linkMail($person->email)."</p>";
        }*/

        ### functions ####
        echo "</div>";
        $block->render_blockEnd();
    }




    #--- list companies -----------------------------------
    {
        require_once("lists/list_companies.inc");
    	$companies= $person->getCompanies();
        $list=new ListBlock_companies();
        $list->title="works for";
        unset($list->columns['short']);
        unset($list->columns['homepage']);
        unset($list->columns['homepage']);
        unset($list->columns['people']);
        unset($list->functions['companyDelete']);
        unset($list->functions['companyNew']);
        $list->no_items_html="not related to a company";
        $list->render_list(&$companies);
    }


    echo(new PageContentNextCol);

    #--- list project -----------------------------------
    {
        /**
        *  @@@note: passing colum to person->getProject is not simple...
        *  the sql-querry currently just querry project-persons, which do not contain anything usefull
        *   possible solution:
        *       1. rewrite the querry-string
        *       2. rewrite all order-keys to something like company.name
        */
        $order_by= get('sort_'.$PH->cur_page->id."_projects");

        require_once("lists/list_projects.inc");

    	$projects= $person->getProjects($order_by);
        $list=new ListBlock_projects();
        $list->title="works in Projects";
        unset($list->functions['projDelete']);
        unset($list->functions['projNew']);
        if($auth->cur_user->user_rights & RIGHT_PROJECT_CREATE) {
            $list->no_items_html=$PH->getLink('projNew','',array('person'=>$person->id));
        }
        else {
            $list->no_items_html="no active projects";
        }

        $list->render_list(&$projects);
    }



    #echo "<a href=\"javascript:document.my_form.go.value='tasksMoveToFolder';document.my_form.submit();\">move to task-folder</a>";
    echo (new PageContentClose);
	echo (new PageHtmlEnd());

}

/**
* display efforts for person...
*/
function personViewEfforts()
{
    global $PH;

	### get current project ###
    $id=getOnePassedId('person','persons_*');
    if(!$person= new Person($id)) {
        $PH->abortWarning("invalid person-id");
		return;
	}

    ### create from handle ###
    $PH->defineFromHandle(array('person'=>$person->id));

    ### set up page ####
    {
        $page= new Page();
    	$page->cur_tab='people';
        $page->title=$person->name;
        $page->title_minor="Efforts";
        $page->type="Person";

        ### breadcrumbs  ###
        $page->crumbs=array(
            $PH->getLink('personList'),
    		"$person->name",
    	);

        $page->options=array(
            new NaviOption(array('target_id'=>'personView',         'name'=>'Overview', 'target_params'=>array('person'=>$id ))),
            new NaviOption(array('target_id'=>'personViewEfforts',         'name'=>'Efforts', 'target_params'=>array('person'=>$id ))),
            #new NaviOption(array('target_id'=>'projView',         'name'=>'Overview', 'target_params'=>array('prj'=>$id ))),
            #new NaviOption(array('target_id'=>'projViewEfforts',         'name'=>'Efforts', 'target_params'=>array('prj'=>$id ))),
            #new NaviOptionSeparator(),
            new NaviOptionWiki()
        );


        echo(new PageHeader);
    }
    echo (new PageContentOpen_Columns);

    echo(new PageContentNextCol);


    #--- list efforts --------------------------------------------------------------------------
    {
        $order_by=get('sort_'.$PH->cur_page->id."_efforts");

        $efforts= $person->getEfforts($order_by);
        $list= new ListBlock_efforts();
        unset($list->functions['effortNew']);
        unset($list->functions['effortNew']);
        $list->no_items_html='no efforts yet';
        $list->render_list(&$efforts);
	}

    echo '<input type="hidden" name="person" value="'.$person->id.'">';

    echo (new PageContentClose);
	echo (new PageHtmlEnd());


}




#=====================================================================================================
# personNew
# - requires prj or task or tsk_*
#=====================================================================================================
function personNew() {
    global $PH;

    $name=get('new_name')
        ? get('new_name')
        :"New Person";


    ### build new object ###
    $newPerson= new Person(array(
        'id'=>0,
        'name'=>$name,
        )
    );
    //$newPerson->insert();
    $PH->show('personEdit',array('person'=>$newPerson->id),$newPerson);

}


/**
* personEdit
*
* @@@ add user-rights validation (it's already checked by pageHandle but, just to be sure)
*/

function personEdit($person=NULL) {
    global $PH;
	global $auth;

    ### new object not in database ###
    if($person) {
        #if($person->id !== 0) {
        #    $PH->abortWarning("invalid id for a new object");
        #}
    }
    else {
        $id= getOnePassedId('person','persons*');   # WARNS if multiple; ABORTS if no id found
        if(!$person= new Person($id)) {
            $PH->abortWarning("ERROR: could not get Person");
            return;
        }
    }

	### validate rights ###
	if(
		(
		 $auth->cur_user->id == $person->id 
		 &&
		 $auth->cur_user->user_rights & RIGHT_PERSON_EDIT_SELF
		)
		||
		$auth->cur_user->user_rights & RIGHT_PERSON_EDIT
	) {
	}
	else {
		$PH->abortWarning("not allowed to edit",ERROR_RIGHTS);
	}



    ### set up page and write header ####
    {
        $page= new Page(array('use_jscalendar'=>true, 'autofocus_field'=>'person_name'));
    	$page->cur_tab='people';
        $page->type="Edit Person";
        $page->title=$person->name;
        $page->title_minor='';

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

        foreach($person->fields as $field) {
            $form->add($field->getFormElement(&$person));
        }

		### show password-fields if can login ###
		/**
		* since the password as stored as md5-hash, we can initiate current password,
		* but have have to make sure the it is not changed on submit
		*/
    	$form->add(new Form_password('person_password1','Password',"__dont_change__"));
    	$form->add(new Form_password('person_password2','confirm Password',"__dont_change__"));


        ### theme & profile ###
        if($auth->cur_user->user_rights & RIGHT_PERSON_EDIT_RIGHTS) {
            global $g_theme_names;
            global $g_user_profile_names;
            global $g_user_profiles;
            
            /**
            * if checkbox not rendered, submit might reset $person->can_login.
            * ...be sure the user_rights match
            */
            $form->add(new Form_checkbox("person_can_login","Person can login",$person->can_login));

            $form->add(new Form_Dropdown('person_theme',  "Theme", array_flip($g_theme_names), $person->theme));

            ### display "undefined" profile if rights changed ### # will be skipped when submitting 
            $profile_num= $person->profile; 
            $reset=""; 
            $default_rights= $g_user_profiles[$g_user_profile_names[$profile_num]]['default_user_rights']; 

            if($default_rights != $person->user_rights) { 
                $profile_num=0; 
                $reset= " (resetting rights)"; 
            }
            

            $form->add(new Form_Dropdown('person_profile',  "Profile $reset", array_flip($g_user_profile_names), $profile_num));
        }

        ### create another person ###
        if($auth->cur_user->user_rights & RIGHT_PERSON_CREATE && $person->id == 0) {
            #$form->add(new Form_checkbox("create_another","",));
            $checked= get('create_another')
            ? 'checked'
            : '';

            $form->form_options[]="<span class=option><input name='create_another' class='checker' type=checkbox $checked>Create another person after submit</span>";     ;
        }

        echo ($form);

        $PH->go_submit= 'personEditSubmit';
        echo "<input type=hidden name='person' value='$person->id'>";

        ### pass company-id? ###
        if($c= get('company')) {
            echo "<input type=hidden name='company' value='$c'>";
        }
    }
    echo (new PageContentClose);
}


/**
* personEditSubmit
*
* @@@ add user-rights validation (it's already checked by pageHandle but, just to be sure)
*/
function personEditSubmit()
{
    global $PH;
    global $auth;
       
    ### cancel ? ###
    if(get('form_do_cancel')) {
        if(!$PH->showFromPage()) {
            $PH->show('home',array());
        }
        exit;
    }
    

    ### get person ####
    $id= getOnePassedId('person');

    ### temporary obj, not in db
    if($id == 0) {
        $person= new Person();
    }
    else {
        if(!$person= new Person($id)) {
            $PH->abortWarning("Could not get person");
            return;
        }
    }
    

	### validate rights ###
	if(
		(
		 $auth->cur_user->id == $person->id 
		 &&
		 $auth->cur_user->user_rights & RIGHT_PERSON_EDIT_SELF
		)
		||
		$auth->cur_user->user_rights & RIGHT_PERSON_EDIT
	) {
	}
	else {
		$PH->abortWarning("not allowed to edit",ERROR_RIGHTS);
	}

    
	$flag_ok=true;      # update valid?

    # retrieve all possible values from post-data
    # NOTE:
    # - this could be an security-issue.
    # - TODO: as some kind of form-edit-behaviour to field-definition
    foreach($person->fields as $f) {
        $name=$f->name;
        $f->parseForm(&$person);
    }

        

    ### rights & theme & profile ###
    if($auth->cur_user->user_rights & RIGHT_PERSON_EDIT_RIGHTS) {

        /**
        * if profile != 0, it will OVERWRITE (or reinit) user_rights
        *
        * therefore persEdit set profil to 0 if rights don't fit profile. It will
        * then be skipped here
        */
        if($profile=get('person_profile')) {
            $person->profile= $profile;
            global $g_user_profile_names;
            global $g_user_profiles;
            if($profile_settings= $g_user_profiles[$g_user_profile_names[$profile]]) {
                if(isset($profile_settings['default_user_rights'])) {
                    $person->user_rights= $profile_settings['default_user_rights'];
                }
            }
        }

        if($theme= get('person_theme')) {
            $person->theme= $theme;
        }

        /**
        * NOTE, if checkbox is not rendered in editForm, user-account will be disabled!
        * there seems no way the be sure the checkbox has been rendered, if it is not checked in form
        */
        if($can_login= get('person_can_login')) {
            $person->can_login= 1;
        }
        else {
            $person->can_login= 0;
        }
    }



    $t_nickname= get('person_nickname');

    ### check if changed nickname is unique
    if($p2= Person::getByNickname($t_nickname)) { # another person with this nick?
        if($p2->id != $person->id) {
            $PH->message="NOTE: Nickname has to be unique";
            $flag_ok = false;
        }
    }
    #$person->nickname= $t_nickname;

	### password entered? ###
    $t_password1= get('person_password1');
    $t_password2= get('person_password2');
    $flag_password_ok=true;
	if(($t_password1 || $t_password2) && $t_password1!="__dont_change__") {

        ### check if password match ###
        if($t_password1 !== $t_password2) {
            $PH->message="passwords don't match";
            $flag_ok = false;
            $flag_password_ok = false;
        }
    
        ### check if password is good enough ###
    	if($person->can_login) {
            $password_length= strlen($t_password1);
            $password_count_numbers= strlen(preg_replace("/[^\d]/",'',$t_password1));
            $password_count_special= strlen(preg_replace("/[^\wd]/",'',$t_password1));
    
            $password_value= -7 + $password_length + $password_count_numbers*2 + $password_count_special*4;
            if($password_value < confGet('CHECK_PASSWORD_LEVEL')){
                $PH->message="Password is too weak (please add numbers, special chars or length)";
                $flag_ok= false;
                $flag_password_ok = false;

            }
    	}

        if($flag_password_ok) {
	        $person->password= md5($t_password1);
	    }
	}
	
	if($flag_ok && $person->can_login) {
	    if(!$person->nickname) {
            $PH->message="Login-accounts require a unique nickname";
            $flag_ok=false;
        }
    }



	### repeat form if invalid data ###
	if(!$flag_ok) {
        $PH->show('personEdit',NULL,$person);

		exit;
	}


    ### insert new object ###
    if($person->id == 0) {
        if($person->insert()) {

            ### link to a company ###
            if($c_id= get('company')) {
                require_once("db/class_company.inc");

                if($c= new Company($c_id)) {
                    require_once("db/class_employment.inc");
                    $e= new Employment(array(
                        'id'=>0,
                        'person'=>$person->id,
                        'company'=>$c->id
                    ));
                    $e->insert();
                }
            }
        }
        else {
            $PH->message="WARNING: could not insert object";
        }
    }

    ### ... or update existing ###
    else {
        $person->update();
    }

    ### create another person ###
    if(get('create_another')) {
        if($c_id= get('company')) {
            $PH->show('personNew',array('company'=>$c_id));
        }
        else {
            $PH->show('personNew');
        }
    }
    else {
        ### display taskView ####
        if(!$PH->showFromPage()) {
            $PH->show('home',array());
        }
    }
}


#=====================================================================================================
# personDelete
#=====================================================================================================
function personDelete(){
    global $PH;

    ### get person ####
    $ids= getPassedIds('person','persons_*');

    if(!$ids) {
        $PH->abortWarning("Select some persons to delete");
        return;
    }

    $counter=0;
    $errors=0;
    foreach($ids as $id) {
        if(!$e= new Person($id)) {
            $PH->abortWarning("Invalid person-id!");
        }
        if($e->delete()) {
            $counter++;
        }
        else {
            $errors++;
        }
    }
    if($errors) {
        $PH->message="WARNING: Failed to delete $errors persons";
    }
    else {
        $PH->message="Moved $counter persons to dumpster";
    }

    ### display taskView ####
    if(!$PH->showFromPage()) {
        $PH->show('projView',array('prj'=>$person->project));
    }
}


/*************************************************************************************
* edit user rights
* 
* the user-rights-validation is checked by pageHandler (requires RIGHT_PERSON_EDIT_RIGHTS)
*/
function personEditRights($person=NULL)
{
    global $PH;
    global $auth;
    global $USER_RIGHT_VALUES;

    ### get person ####
    if(!$person) {
        $ids= getPassedIds('person','persons_*');

        if(!$ids) {
            $PH->abortWarning("Select some persons to delete");
            return;
        }
        $person= new Person($ids[0]);
    }

    ### set up page and write header ####
    {
        $page= new Page(array('autofocus_field'=>'person_nickname'));
    	$page->cur_tab='people';
        $page->type='Edit Person';
        $page->title='Adjust user-rights of '.$person->name;
        $page->title_minor='';
        echo(new PageHeader);
    }
    echo (new PageContentOpen);

    ### write form #####
    {
        global $STATUS_NAMES;
        require_once("render/render_form.inc");

        echo "<div>";
        echo "Please consider that activating login-accounts might trigger security-issues.";
        echo "</div>";

        $form=new PageForm();
        $form->button_cancel=true;

        $form->add(new Form_checkbox("person_can_login","Person can login",$person->can_login));

        foreach($USER_RIGHT_VALUES as $key=>$value) {
            $form->add(new Form_checkbox("right_".$value, $key, $person->user_rights & $value));
        }
        echo ($form);

        $PH->go_submit= $PH->getValidPageId('personEditRightsSubmit');
        echo "<input type=hidden name='person' value='$person->id'>";

    }
    echo (new PageContentClose);
}




/****************************************************************************************
* personEditRightsSubmit - submit entered login information
* 
* the user-rights-validation is checked by pageHandler (requires RIGHT_PERSON_EDIT_RIGHTS)
*/
function personEditRightsSubmit(){
    global $PH;
    global $USER_RIGHT_VALUES;


    ### cancel ###
    if(get('form_do_cancel')) {
        if(!$PH->showFromPage()) {
            $PH->show('home',array());
        }
        exit;
    }

    ### get person ####
    $id= getOnePassedId('person');  # aborts if not found
    if(!$person = new Person($id)) {
        $PH->abortWarning("Could not get person");
        return;
    }

    $flag_ok= TRUE;     # was required for advanced form-validation (currently not required)


    ### get rights ###
    foreach($USER_RIGHT_VALUES as $key=>$value) {
        if(get("right_".$value)) {
            $person->user_rights |= $value;
        }
        else {
            $person->user_rights &= $value ^ RIGHT_ALL;
        }
    }


    ### if anything fine, update and go back ###
    if($flag_ok) {
        $person->update();
        $PH->message="User rights changed";

        ### display taskView ####
        if(!$PH->showFromPage()) {
            $PH->show('home',array());
        }
    }
    ### otherwise return to form ###
    else {
        $PH->show('personEditRights',NULL,$person);
    }
}



?>
